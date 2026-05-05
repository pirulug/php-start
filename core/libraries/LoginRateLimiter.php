<?php

/**
 * LoginRateLimiter
 *
 * Clase encargada de gestionar el control de intentos de inicio de sesión.
 * Utiliza un patrón EAV sobre la tabla usermeta para persistir bloqueos
 * temporales basados en IP o ID de usuario.
 *
 * @author Pirulug
 * @link   https://github.com/pirulug
 */
class LoginRateLimiter {
  // --------------------------------------------------------------------------
  // PROPIEDADES Y CONFIGURACIÓN
  // --------------------------------------------------------------------------

  protected PDO $connect;
  protected string $ip;
  protected string $username;

  protected ?int $userId = null;
  protected ?object $meta = null;

  protected int $maxAttemptsUser = 5;
  protected int $maxAttemptsIp = 3;
  protected int $baseBlockSeconds = 60;

  /**
   * Inicializa el limitador con la conexión activa y detecta la IP del cliente.
   *
   * @param PDO $connect Conexión PDO global.
   */
  public function __construct(PDO $connect) {
    $this->connect = $connect;
    $this->ip      = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
  }

  // --------------------------------------------------------------------------
  // SECCIÓN: MÉTODOS DE ENTRADA (FLUENT)
  // --------------------------------------------------------------------------

  /**
   * Define el nombre de usuario desde el cual se realiza el intento.
   *
   * @param string $username Login o email del usuario.
   * @return self Instancia.
   */
  public function fromPost(string $username): self {
    $this->username = trim($username);
    return $this;
  }

  // --------------------------------------------------------------------------
  // SECCIÓN: RESOLUCIÓN Y CARGA
  // --------------------------------------------------------------------------

  /**
   * Intenta localizar el ID del usuario en la base de datos.
   *
   * @return self Instancia.
   */
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

  /**
   * Carga los registros de acceso actuales (meta) desde la base de datos.
   * Si no existen, inicializa un objeto de meta vacío.
   *
   * @return self Instancia.
   */
  public function load(): self {
    $key = $this->userId ? 'login_access' : "login_access:{$this->ip}";

    $sql = "
      SELECT usermeta_value
      FROM usermeta
      WHERE usermeta_key = :key
            AND " . ($this->userId ? "user_id = :uid" : "user_id IS NULL") . "
      LIMIT 1
    ";

    $stmt = $this->connect->prepare($sql);
    $stmt->bindParam(':key', $key);

    if ($this->userId) {
      $stmt->bindParam(':uid', $this->userId, PDO::PARAM_INT);
    }

    $stmt->execute();
    $value = $stmt->fetchColumn();

    if ($value) {
      $this->meta = json_decode($value);
    } else {
      $this->meta = (object) [
        'attempts'      => 0,
        'blocked_until' => null,
        'last_attempt'  => null
      ];
    }

    return $this;
  }

  // --------------------------------------------------------------------------
  // SECCIÓN: VERIFICACIÓN DE ESTADO
  // --------------------------------------------------------------------------

  /**
   * Comprueba si el usuario o la IP se encuentran bloqueados actualmente.
   * Realiza una doble verificación: por ID de usuario y por IP global.
   *
   * @return bool True si está bloqueado, False en caso contrario.
   */
  public function isBlocked(): bool {
    // 1. Verificar bloqueo por IP (Global)
    $ipKey = "login_ip_block:{$this->ip}";
    $stmt  = $this->connect->prepare("SELECT usermeta_value FROM usermeta WHERE usermeta_key = :key AND user_id IS NULL LIMIT 1");
    $stmt->execute([':key' => $ipKey]);
    $ipMeta = json_decode($stmt->fetchColumn());

    if ($ipMeta && isset($ipMeta->blocked_until) && strtotime($ipMeta->blocked_until) > time()) {
      $this->meta = $ipMeta;
      return true;
    }

    // 2. Verificar bloqueo por Usuario (Si existe)
    if ($this->userId) {
      if (!$this->meta) $this->load();
      if ($this->meta && isset($this->meta->blocked_until) && strtotime($this->meta->blocked_until) > time()) {
        return true;
      }
    }

    return false;
  }

  /**
   * Comprueba si el nivel de intentos sugiere un ataque de fuerza bruta.
   *
   * @return bool True si excede el umbral crítico.
   */
  public function isBruteForce(): bool {
    $attempts = $this->meta->attempts ?? 0;
    return $attempts >= 15;
  }

  // --------------------------------------------------------------------------
  // SECCIÓN: PROCESAMIENTO DE INTENTOS
  // --------------------------------------------------------------------------

  /**
   * Registra un intento fallido y actualiza/bloquea el acceso según corresponda.
   * Actualiza tanto el contador del usuario como el de la IP.
   *
   * @return self Instancia.
   */
  public function failed(): self {
    $now = date('Y-m-d H:i:s');

    // 1. Actualizar contador por IP (Siempre)
    $ipKey     = "login_ip_block:{$this->ip}";
    $stmtIp    = $this->connect->prepare("SELECT usermeta_value FROM usermeta WHERE usermeta_key = :key AND user_id IS NULL LIMIT 1");
    $stmtIp->execute([':key' => $ipKey]);
    $ipData    = json_decode($stmtIp->fetchColumn()) ?: (object)['attempts' => 0];
    $ipAttempts = $ipData->attempts + 1;
    
    $ipBlockedUntil = null;
    if ($ipAttempts >= $this->maxAttemptsIp) {
      $ipBlockedUntil = date('Y-m-d H:i:s', time() + $this->calculateBlockTime($ipAttempts, true));
    }

    $newIpData = ['attempts' => $ipAttempts, 'blocked_until' => $ipBlockedUntil, 'last_attempt' => $now];
    $stmtUpdIp = $this->connect->prepare("INSERT INTO usermeta (user_id, usermeta_key, usermeta_value) VALUES (NULL, :key, :val) ON DUPLICATE KEY UPDATE usermeta_value = VALUES(usermeta_value)");
    $stmtUpdIp->execute([':key' => $ipKey, ':val' => json_encode($newIpData)]);

    // 2. Actualizar contador por Usuario (Si existe)
    if ($this->userId) {
      $userAttempts = ($this->meta->attempts ?? 0) + 1;
      $userBlockedUntil = null;
      if ($userAttempts >= $this->maxAttemptsUser) {
        $userBlockedUntil = date('Y-m-d H:i:s', time() + $this->calculateBlockTime($userAttempts, false));
      }

      $newUserData = ['attempts' => $userAttempts, 'blocked_until' => $userBlockedUntil, 'last_attempt' => $now];
      $stmtUpdUser = $this->connect->prepare("INSERT INTO usermeta (user_id, usermeta_key, usermeta_value) VALUES (:uid, 'login_access', :val) ON DUPLICATE KEY UPDATE usermeta_value = VALUES(usermeta_value)");
      $stmtUpdUser->execute([':uid' => $this->userId, ':val' => json_encode($newUserData)]);
      
      $this->meta = (object)$newUserData;
    } else {
      $this->meta = (object)$newIpData;
    }

    return $this;
  }

  /**
   * Limpia los registros de intentos tras un inicio de sesión exitoso.
   */
  public function success(): void {
    // Limpiar IP
    $this->connect->prepare("DELETE FROM usermeta WHERE usermeta_key = :key AND user_id IS NULL")->execute([':key' => "login_ip_block:{$this->ip}"]);
    
    // Limpiar Usuario
    if ($this->userId) {
      $this->connect->prepare("DELETE FROM usermeta WHERE usermeta_key = 'login_access' AND user_id = :uid")->execute([':uid' => $this->userId]);
    }
  }

  /**
   * Bloquea permanentemente una IP por comportamiento malicioso persistente.
   */
  public function blockIpPermanently(): void {
    $key  = "login_ip_block:{$this->ip}";
    $data = [
      'attempts'      => 999,
      'blocked_until' => '2099-12-31 23:59:59',
      'last_attempt'  => date('Y-m-d H:i:s')
    ];

    $this->connect->prepare("INSERT INTO usermeta (user_id, usermeta_key, usermeta_value) VALUES (NULL, :key, :val) ON DUPLICATE KEY UPDATE usermeta_value = VALUES(usermeta_value)")
      ->execute([':key' => $key, ':val' => json_encode($data)]);
  }

  // --------------------------------------------------------------------------
  // SECCIÓN: LÓGICA INTERNA Y MENSAJES
  // --------------------------------------------------------------------------

  /**
   * Calcula el tiempo de bloqueo basado en intentos exponenciales.
   *
   * @param int $attempts Contador de intentos.
   * @param bool $isIp Indica si el bloqueo es por IP.
   * @return int Segundos de bloqueo.
   */
  protected function calculateBlockTime(int $attempts, bool $isIp): int {
    if ($isIp) {
      return rand(120, 300); // Bloqueo de IP más agresivo
    }

    $multiplier = max(1, $attempts - $this->maxAttemptsUser + 1);
    return rand($this->baseBlockSeconds * $multiplier, $this->baseBlockSeconds * ($multiplier + 2));
  }

  /**
   * Genera el mensaje de error descriptivo para el usuario bloqueado.
   *
   * @return string Mensaje formateado con tiempo restante.
   */
  public function getBlockedMessage(): string {
    if (!$this->meta || !$this->meta->blocked_until) {
      return '';
    }

    $blockedUntil = strtotime($this->meta->blocked_until);
    $remaining    = $blockedUntil - time();

    if ($remaining <= 0) {
      return '';
    }

    $minutes = (int) floor($remaining / 60);
    $seconds = $remaining % 60;

    if ($minutes > 0) {
      return "Demasiados intentos fallidos. Intenta nuevamente en {$minutes} min {$seconds} seg.";
    }

    return "Demasiados intentos fallidos. Intenta nuevamente en {$seconds} segundos.";
  }
}