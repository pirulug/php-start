<?php

class Analytics {
  private $connect;
  private $geoApiUrl = 'https://ipapi.pirulug.pw/api/v1/{ip}';

  public function __construct(PDO $pdo) {
    $this->connect = $pdo;
  }

  public function geoApiUrl(string $url): self {
    $this->geoApiUrl = $url;
    return $this;
  }

  private function getClientIp(): ?string {
    if (!empty($_SERVER['HTTP_CLIENT_IP']))
      return $_SERVER['HTTP_CLIENT_IP'];
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
      return explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
    return $_SERVER['REMOTE_ADDR'] ?? null;
  }

  public function trackVisit(string $pageTitle, string $pageUri, ?string $ip = null): void {
    $ext = pathinfo(parse_url($pageUri, PHP_URL_PATH), PATHINFO_EXTENSION);
    if (in_array(strtolower($ext), ['ico', 'png', 'jpg', 'jpeg', 'gif', 'css', 'js', 'svg', 'webp'])) {
      return;
    }

    $ip        = $ip ?? $this->getClientIp() ?? '0.0.0.0';
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
    $referer   = $_SERVER['HTTP_REFERER'] ?? '';

    $cookie = $_COOKIE['visitor_session_id'] ?? null;
    if (!$cookie) {
      $cookie = bin2hex(random_bytes(16));
      setcookie('visitor_session_id', $cookie, time() + 3600 * 24 * 7, '/');
    }

    $visitorId = $this->registerVisitor($ip, $userAgent, $referer);
    $pageId    = $this->registerPage($pageUri, $pageTitle);
    $this->registerSession($visitorId, $cookie, $pageUri);
    $this->updateUserOnline($visitorId, $pageId, $ip, $referer);
  }

  private function registerVisitor(string $ip, string $userAgent, string $referer): ?int {
    $browser  = $this->getBrowser($userAgent);
    $platform = $this->getPlatform($userAgent);
    $device   = $this->getDevice($userAgent);

    $sql = "
      INSERT INTO visitors (
        visitor_ip, visitor_user_agent, visitor_browser,
        visitor_platform, visitor_device,
        visitor_country, visitor_region, visitor_city,
        visitor_referer, visitor_total_hits
      ) VALUES (
        :ip, :ua, :browser, :platform, :device,
        'Desconocido', NULL, NULL,
        :referer, 1
      )
      ON DUPLICATE KEY UPDATE
        visitor_total_hits = visitor_total_hits + 1,
        visitor_last_visit = CURRENT_TIMESTAMP,
        visitor_referer    = VALUES(visitor_referer)
    ";

    $stmt = $this->connect->prepare($sql);
    $stmt->execute([
      ':ip'       => $ip,
      ':ua'       => $userAgent,
      ':browser'  => $browser,
      ':platform' => $platform,
      ':device'   => $device,
      ':referer'  => $referer,
    ]);

    $stmt = $this->connect->prepare("
      SELECT visitor_id
      FROM visitors
      WHERE visitor_ip = :ip
        AND visitor_user_agent = :ua
      LIMIT 1
    ");
    $stmt->execute([':ip' => $ip, ':ua' => $userAgent]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    return $row ? (int) $row['visitor_id'] : null;
  }

  private function registerPage(string $uri, ?string $title): int {
    $stmt = $this->connect->prepare("
      SELECT visitor_page_id
      FROM visitor_pages
      WHERE visitor_page_uri = ?
      LIMIT 1
    ");
    $stmt->execute([$uri]);
    $page = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($page) {
      $this->connect->prepare("
        UPDATE visitor_pages
        SET visitor_page_total_views = visitor_page_total_views + 1,
            visitor_page_last_viewed = CURRENT_TIMESTAMP
        WHERE visitor_page_id = ?
      ")->execute([$page['visitor_page_id']]);
      return (int) $page['visitor_page_id'];
    }

    $this->connect->prepare("
      INSERT INTO visitor_pages (
        visitor_page_uri,
        visitor_page_title,
        visitor_page_total_views,
        visitor_page_unique_visitors
      ) VALUES (?, ?, 1, 1)
    ")->execute([$uri, $title]);

    return (int) $this->connect->lastInsertId();
  }

  private function registerSession(int $visitorId, string $cookie, string $pageUri): void {
    $stmt = $this->connect->prepare("
      SELECT *
      FROM visitor_sessions
      WHERE visitor_session_cookie = ?
      LIMIT 1
    ");
    $stmt->execute([$cookie]);
    $session = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($session) {
      $path   = $session['visitor_session_path']
        ? json_decode($session['visitor_session_path'], true)
        : [];
      $path[] = ['uri' => $pageUri, 'time' => date('Y-m-d H:i:s')];

      $this->connect->prepare("
        UPDATE visitor_sessions
        SET visitor_session_path = ?,
            visitor_session_end_page = ?,
            visitor_session_end_time = CURRENT_TIMESTAMP
        WHERE visitor_session_id = ?
      ")->execute([
            json_encode($path),
            $pageUri,
            $session['visitor_session_id']
          ]);
      return;
    }

    $path = json_encode([['uri' => $pageUri, 'time' => date('Y-m-d H:i:s')]]);

    $this->connect->prepare("
      INSERT INTO visitor_sessions (
        visitor_session_visitor_id,
        visitor_session_cookie,
        visitor_session_start_page,
        visitor_session_end_page,
        visitor_session_path
      ) VALUES (?, ?, ?, ?, ?)
    ")->execute([$visitorId, $cookie, $pageUri, $pageUri, $path]);
  }

  private function updateUserOnline(int $visitorId, int $pageId, string $ip, string $referer): void {
    $stmt = $this->connect->prepare("
      INSERT INTO visitor_useronlines (
        visitor_useronline_visitor_id,
        visitor_useronline_page_id,
        visitor_useronline_ip,
        visitor_useronline_referer,
        visitor_useronline_last_activity
      ) VALUES (:vid, :pid, :ip, :ref, CURRENT_TIMESTAMP)
      ON DUPLICATE KEY UPDATE
        visitor_useronline_visitor_id = VALUES(visitor_useronline_visitor_id),
        visitor_useronline_page_id    = VALUES(visitor_useronline_page_id),
        visitor_useronline_referer    = VALUES(visitor_useronline_referer),
        visitor_useronline_last_activity = CURRENT_TIMESTAMP
    ");

    $stmt->execute([
      ':vid' => $visitorId,
      ':pid' => $pageId,
      ':ip'  => $ip,
      ':ref' => $referer
    ]);

    $this->connect->query("
      DELETE FROM visitor_useronlines
      WHERE visitor_useronline_last_activity < (CURRENT_TIMESTAMP - INTERVAL 10 MINUTE)
    ");
  }

  private function getLocationFromAPI(string $ip): array {
    if ($ip === '127.0.0.1' || $ip === '::1') {
      return ['country' => 'Localhost', 'region' => null, 'city' => null];
    }

    $url = str_replace('{ip}', rawurlencode($ip), $this->geoApiUrl);

    $ch = curl_init($url);
    curl_setopt_array($ch, [
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_TIMEOUT        => 10
    ]);
    $json = curl_exec($ch);
    curl_close($ch);

    $data = json_decode($json, true);

    if (!$data || !isset($data['success']) || $data['success'] !== true) {
      return ['country' => 'Desconocido', 'region' => null, 'city' => null];
    }

    return [
      'country' => $data['country'] ?? 'Desconocido',
      'region'  => $data['region'] ?? null,
      'city'    => $data['city'] ?? null
    ];
  }

  public function resolveUnknownCountries(int $limit = 100): int {
    $stmt = $this->connect->prepare("
      SELECT visitor_id, visitor_ip
      FROM visitors
      WHERE visitor_country = 'Desconocido'
      LIMIT :l
    ");
    $stmt->bindValue(':l', $limit, PDO::PARAM_INT);
    $stmt->execute();

    $rows    = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $updated = 0;

    foreach ($rows as $row) {
      $geo = $this->getLocationFromAPI($row['visitor_ip']);
      if ($geo['country'] === 'Desconocido')
        continue;

      $this->connect->prepare("
        UPDATE visitors
        SET visitor_country = :c,
            visitor_region = :r,
            visitor_city = :ci
        WHERE visitor_id = :id
          AND visitor_country = 'Desconocido'
      ")->execute([
            ':c'  => $geo['country'],
            ':r'  => $geo['region'],
            ':ci' => $geo['city'],
            ':id' => $row['visitor_id']
          ]);

      $updated++;
      usleep(200000);
    }

    return $updated;
  }

  private function getBrowser(string $ua): string {
    if (preg_match('/Chrome/i', $ua))
      return 'Chrome';
    if (preg_match('/Firefox/i', $ua))
      return 'Firefox';
    if (preg_match('/Safari/i', $ua))
      return 'Safari';
    if (preg_match('/Edge/i', $ua))
      return 'Edge';
    if (preg_match('/MSIE|Trident/i', $ua))
      return 'Internet Explorer';
    return 'Otro';
  }

  private function getPlatform(string $ua): string {
    if (preg_match('/Windows/i', $ua))
      return 'Windows';
    if (preg_match('/Macintosh|Mac OS X/i', $ua))
      return 'macOS';
    if (preg_match('/Linux/i', $ua))
      return 'Linux';
    if (preg_match('/Android/i', $ua))
      return 'Android';
    if (preg_match('/iPhone|iPad|iOS/i', $ua))
      return 'iOS';
    return 'Desconocido';
  }

  private function getDevice(string $ua): string {
    if (preg_match('/Tablet|iPad/i', $ua))
      return 'Tablet';
    if (preg_match('/Mobile|Android|iPhone/i', $ua))
      return 'Smartphone';
    return 'Desktop';
  }
}
