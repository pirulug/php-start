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
    $this->registerSession($visitorId, $cookie, $pageUri, $referer);
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
        visitor_referer, visitor_total_hits, visitor_last_visit
      ) VALUES (
        :ip, :ua, :browser, :platform, :device,
        'Desconocido', NULL, NULL,
        :referer, 1, NOW()
      )
      ON DUPLICATE KEY UPDATE
        visitor_total_hits = visitor_total_hits + 1,
        visitor_last_visit = NOW(),
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
    $stmt = $this->connect->prepare("SELECT visitor_pages_id FROM visitor_pages WHERE visitor_pages_uri = ? LIMIT 1");
    $stmt->execute([$uri]);
    $page = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($page) {
      $this->connect->prepare("
        UPDATE visitor_pages
        SET visitor_pages_total_views = visitor_pages_total_views + 1,
            visitor_pages_last_viewed = NOW()
        WHERE visitor_pages_id = ?
      ")->execute([$page['visitor_pages_id']]);
      return (int) $page['visitor_pages_id'];
    }

    $this->connect->prepare("
      INSERT INTO visitor_pages (
        visitor_pages_uri,
        visitor_pages_title,
        visitor_pages_total_views,
        visitor_pages_unique_visitors
      ) VALUES (?, ?, 1, 1)
    ")->execute([$uri, $title]);

    return (int) $this->connect->lastInsertId();
  }

  private function registerSession(int $visitorId, string $cookie, string $pageUri, string $referer): void {
    $stmt = $this->connect->prepare("SELECT * FROM visitor_sessions WHERE visitor_sessions_cookie = ? LIMIT 1");
    $stmt->execute([$cookie]);
    $session = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($session) {
      $path   = $session['visitor_sessions_path'] ? json_decode($session['visitor_sessions_path'], true) : [];
      $path[] = ['uri' => $pageUri, 'time' => date('Y-m-d H:i:s')];

      $this->connect->prepare("
        UPDATE visitor_sessions
        SET visitor_sessions_path = ?,
            visitor_sessions_end_page = ?,
            visitor_sessions_end_time = NOW()
        WHERE visitor_sessions_id = ?
      ")->execute([json_encode($path), $pageUri, $session['visitor_sessions_id']]);
      return;
    }

    $path = json_encode([['uri' => $pageUri, 'time' => date('Y-m-d H:i:s')]]);
    $this->connect->prepare("
      INSERT INTO visitor_sessions (
        visitor_sessions_visitor_id,
        visitor_sessions_cookie,
        visitor_sessions_start_page,
        visitor_sessions_end_page,
        visitor_sessions_path
      ) VALUES (?, ?, ?, ?, ?)
    ")->execute([$visitorId, $cookie, $pageUri, $pageUri, $path]);
  }

  private function updateUserOnline(int $visitorId, int $pageId, string $ip, string $referer): void {
    $timeout = 5;

    $stmt = $this->connect->prepare("
      SELECT visitor_useronline_id
      FROM visitor_useronline
      WHERE visitor_useronline_visitor_id = :vid
        AND visitor_useronline_ip = :ip
        AND visitor_useronline_last_activity > (NOW() - INTERVAL :t MINUTE)
      LIMIT 1
    ");
    $stmt->bindValue(':vid', $visitorId, PDO::PARAM_INT);
    $stmt->bindValue(':ip', $ip);
    $stmt->bindValue(':t', $timeout, PDO::PARAM_INT);
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
      $this->connect->prepare("
        UPDATE visitor_useronline
        SET visitor_useronline_last_activity = NOW(),
            visitor_useronline_page_id = :pid,
            visitor_useronline_referer = :ref
        WHERE visitor_useronline_id = :id
      ")->execute([
            ':pid' => $pageId,
            ':ref' => $referer,
            ':id'  => $row['visitor_useronline_id']
          ]);
    } else {
      $this->connect->prepare("
        INSERT INTO visitor_useronline (
          visitor_useronline_visitor_id,
          visitor_useronline_page_id,
          visitor_useronline_ip,
          visitor_useronline_referer,
          visitor_useronline_last_activity
        ) VALUES (:vid, :pid, :ip, :ref, NOW())
        ON DUPLICATE KEY UPDATE
          visitor_useronline_last_activity = NOW(),
          visitor_useronline_page_id = VALUES(visitor_useronline_page_id),
          visitor_useronline_referer = VALUES(visitor_useronline_referer)
      ")->execute([
            ':vid' => $visitorId,
            ':pid' => $pageId,
            ':ip'  => $ip,
            ':ref' => $referer
          ]);
    }

    $this->connect->query("
      DELETE FROM visitor_useronline
      WHERE visitor_useronline_last_activity < (NOW() - INTERVAL 10 MINUTE)
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
      CURLOPT_TIMEOUT        => 5
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
    if (preg_match('/Mobile|Android|iPhone/i', $ua))
      return 'Smartphone';
    if (preg_match('/Tablet|iPad/i', $ua))
      return 'Tablet';
    return 'Desktop';
  }
}
