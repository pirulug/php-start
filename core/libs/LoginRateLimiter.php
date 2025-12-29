<?php

class LoginRateLimiter {
  protected PDO $connect;

  protected string $ip;
  protected string $username;

  protected ?int $userId = null;
  protected ?object $access = null;

  protected int $maxAttemptsUser = 5;
  protected int $maxAttemptsIp = 3;
  protected int $baseBlockSeconds = 60;

  public function __construct(PDO $connect) {
    $this->connect = $connect;
    $this->ip      = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
  }

  /* =====================================================
   * FLUENT ENTRY
   * ===================================================== */

  public function fromPost(string $username): self {
    $this->username = trim($username);
    return $this;
  }

  /* =====================================================
   * USER LOOKUP
   * ===================================================== */

  public function resolveUser(): self {
    $sql = "
            SELECT user_id
            FROM users
            WHERE user_login = :login
              AND user_deleted IS NULL
              AND user_status = 1
            LIMIT 1
        ";

    $stmt = $this->connect->prepare($sql);
    $stmt->bindParam(':login', $this->username);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_OBJ);

    $this->userId = $user ? (int) $user->user_id : null;

    return $this;
  }

  /* =====================================================
   * LOAD ACCESS RECORD
   * ===================================================== */

  public function load(): self {
    $sql = "
        SELECT *
        FROM user_access
        WHERE access_ip = :ip
          AND " . ($this->userId ? "user_id = :uid" : "user_id IS NULL") . "
        LIMIT 1
    ";

    $stmt = $this->connect->prepare($sql);
    $stmt->bindParam(':ip', $this->ip);

    if ($this->userId) {
      $stmt->bindParam(':uid', $this->userId, PDO::PARAM_INT);
    }

    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_OBJ);

    $this->access = $result !== false ? $result : null;

    return $this;
  }


  /* =====================================================
   * BLOCK CHECK
   * ===================================================== */

  public function isBlocked(): bool {
    if (!$this->access || !$this->access->access_blocked_until) {
      return false;
    }

    return strtotime($this->access->access_blocked_until) > time();
  }

  /* =====================================================
   * FAILED LOGIN
   * ===================================================== */

  public function failed(): self {
    if (!$this->access) {
      $this->createAccess();
    }

    $attempts = $this->access->access_attempts + 1;

    $blockedUntil = null;

    if ($this->shouldBlock($attempts)) {
      $blockedUntil = date(
        'Y-m-d H:i:s',
        time() + $this->calculateBlockTime($attempts)
      );
    }

    $sql = "
            UPDATE user_access
            SET
                access_attempts = :attempts,
                access_last_attempt = NOW(),
                access_blocked_until = :blocked
            WHERE access_id = :id
        ";

    $stmt = $this->connect->prepare($sql);
    $stmt->bindParam(':attempts', $attempts, PDO::PARAM_INT);
    $stmt->bindParam(':blocked', $blockedUntil);
    $stmt->bindParam(':id', $this->access->access_id, PDO::PARAM_INT);
    $stmt->execute();

    $this->access->access_attempts      = $attempts;
    $this->access->access_blocked_until = $blockedUntil;

    return $this;
  }

  /* =====================================================
   * SUCCESS LOGIN
   * ===================================================== */

  public function success(): void {
    if (!$this->access) {
      return;
    }

    $sql  = "DELETE FROM user_access WHERE access_id = :id";
    $stmt = $this->connect->prepare($sql);
    $stmt->bindParam(':id', $this->access->access_id, PDO::PARAM_INT);
    $stmt->execute();
  }

  /* =====================================================
   * BRUTE FORCE
   * ===================================================== */

  public function isBruteForce(): bool {
    if (!$this->access) {
      return false;
    }

    return $this->access->access_attempts >= 15;
  }

  public function blockIpPermanently(): void {
    $sql = "
            UPDATE user_access
            SET access_blocked_until = '2099-12-31 23:59:59'
            WHERE access_ip = :ip
        ";

    $stmt = $this->connect->prepare($sql);
    $stmt->bindParam(':ip', $this->ip);
    $stmt->execute();
  }

  /* =====================================================
   * INTERNAL
   * ===================================================== */

  protected function createAccess(): void {
    $sql = "
            INSERT INTO user_access (
                user_id,
                access_ip,
                access_attempts,
                access_last_attempt
            ) VALUES (
                :uid,
                :ip,
                0,
                NOW()
            )
        ";

    $stmt = $this->connect->prepare($sql);
    $stmt->bindParam(':uid', $this->userId);
    $stmt->bindParam(':ip', $this->ip);
    $stmt->execute();

    $this->access = (object) [
      'access_id'            => $this->connect->lastInsertId(),
      'access_attempts'      => 0,
      'access_blocked_until' => null
    ];
  }

  protected function shouldBlock(int $attempts): bool {
    return $this->userId
      ? $attempts >= $this->maxAttemptsUser
      : $attempts >= $this->maxAttemptsIp;
  }

  protected function calculateBlockTime(int $attempts): int {
    if ($this->userId === null) {
      return rand(60, 180);
    }

    $multiplier = max(1, $attempts - $this->maxAttemptsUser + 1);

    return rand(
      $this->baseBlockSeconds * $multiplier,
      $this->baseBlockSeconds * ($multiplier + 2)
    );
  }

  public function getBlockedMessage(): string {
    if (!$this->access || !$this->access->access_blocked_until) {
      return '';
    }

    $blockedUntil = strtotime($this->access->access_blocked_until);
    $remaining    = $blockedUntil - time();

    if ($remaining <= 0) {
      return '';
    }

    $minutes = floor($remaining / 60);
    $seconds = $remaining % 60;

    if ($minutes > 0) {
      return "Demasiados intentos fallidos. Intenta nuevamente en {$minutes} min {$seconds} seg.";
    }

    return "Demasiados intentos fallidos. Intenta nuevamente en {$seconds} segundos.";
  }

}
