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
    return $_SERVER['REMOTE_ADDR'] ?? null;
  }

  private function isBot(string $ua): bool {
    return (bool) preg_match('/bot|crawl|slurp|spider|mediapartners/i', $ua);
  }

  public function trackVisit(string $pageTitle, string $pageUri, ?string $ip = null): void {
    $ext = pathinfo(parse_url($pageUri, PHP_URL_PATH), PATHINFO_EXTENSION);
    if (in_array(strtolower($ext), ['ico', 'png', 'jpg', 'jpeg', 'gif', 'css', 'js', 'svg', 'webp'])) {
      return;
    }

    $ip        = $ip ?? $this->getClientIp() ?? '0.0.0.0';
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
    $referer   = $_SERVER['HTTP_REFERER'] ?? '';
    $isBot     = $this->isBot($userAgent);

    $cookie = $_COOKIE['visitor_session_id'] ?? null;
    if (!$cookie) {
      $cookie = bin2hex(random_bytes(16));
      setcookie('visitor_session_id', $cookie, time() + 3600 * 24 * 7, '/');
    }

    // Cache de sesión para reducir consultas
    $visitorId = $_SESSION['v_id'] ?? null;
    $lastTrack = $_SESSION['v_last_track'] ?? 0;
    $currentTime = time();

    if (!$visitorId) {
      $visitorId = $this->registerVisitor($ip, $userAgent, $referer, $isBot);
      $_SESSION['v_id'] = $visitorId;
    }

    $pageId = $this->registerPage($pageUri, $pageTitle, $cookie);

    // Solo actualizar sesión y online cada 60 segundos para ahorrar recursos
    if (($currentTime - $lastTrack) > 60) {
      $this->registerSession($visitorId, $cookie, $pageUri);
      $this->updateUserOnline($visitorId, $pageId, $ip, $referer);
      $_SESSION['v_last_track'] = $currentTime;
    }
  }

  private function registerVisitor(string $ip, string $userAgent, string $referer, bool $isBot): ?int {
    $browser  = $this->getBrowser($userAgent);
    $platform = $this->getPlatform($userAgent);
    $device   = $this->getDevice($userAgent);

    $stmt = $this->connect->prepare("SELECT visitor_country, visitor_region, visitor_city FROM visitors WHERE visitor_ip = :ip LIMIT 1");
    $stmt->bindParam(':ip', $ip);
    $stmt->execute();
    $geo = $stmt->fetch(PDO::FETCH_OBJ);
    
    $country = ($geo && isset($geo->visitor_country)) ? $geo->visitor_country : 'Desconocido';
    $region  = ($geo && isset($geo->visitor_region))  ? $geo->visitor_region  : null;
    $city    = ($geo && isset($geo->visitor_city))    ? $geo->visitor_city    : null;
    $v_is_bot = $isBot ? 1 : 0;

    $sql = "
      INSERT INTO visitors (
        visitor_ip, visitor_user_agent, visitor_browser,
        visitor_platform, visitor_device, visitor_is_bot,
        visitor_country, visitor_region, visitor_city,
        visitor_referer, visitor_total_hits
      ) VALUES (
        :ip, :ua, :browser, :platform, :device, :is_bot,
        :country, :region, :city,
        :referer, 1
      )
      ON DUPLICATE KEY UPDATE
        visitor_total_hits = visitor_total_hits + 1,
        visitor_last_visit = CURRENT_TIMESTAMP,
        visitor_referer    = VALUES(visitor_referer),
        visitor_id         = LAST_INSERT_ID(visitor_id)
    ";

    $stmt = $this->connect->prepare($sql);
    $stmt->bindParam(':ip', $ip);
    $stmt->bindParam(':ua', $userAgent);
    $stmt->bindParam(':browser', $browser);
    $stmt->bindParam(':platform', $platform);
    $stmt->bindParam(':device', $device);
    $stmt->bindParam(':is_bot', $v_is_bot);
    $stmt->bindParam(':country', $country);
    $stmt->bindParam(':region', $region);
    $stmt->bindParam(':city', $city);
    $stmt->bindParam(':referer', $referer);
    $stmt->execute();

    return (int) $this->connect->lastInsertId();
  }

  private function registerPage(string $uri, ?string $title, string $sessionCookie): int {
    $path_search = '%"uri":"' . $uri . '"%';
    
    $stmt = $this->connect->prepare("
      SELECT COUNT(*) 
      FROM visitor_sessions 
      WHERE visitor_session_cookie = :cookie AND (visitor_session_path LIKE :path OR visitor_session_start_page = :uri)
    ");
    $stmt->bindParam(':cookie', $sessionCookie);
    $stmt->bindParam(':path', $path_search);
    $stmt->bindParam(':uri', $uri);
    $stmt->execute();
    
    $hasVisited = $stmt->fetchColumn() > 0;
    $uniqueIncrement = $hasVisited ? 0 : 1;

    $stmt = $this->connect->prepare("
      INSERT INTO visitor_pages (
        visitor_page_uri,
        visitor_page_title,
        visitor_page_total_views,
        visitor_page_unique_visitors
      ) VALUES (:uri, :title, 1, :inc)
      ON DUPLICATE KEY UPDATE
        visitor_page_total_views = visitor_page_total_views + 1,
        visitor_page_unique_visitors = visitor_page_unique_visitors + :inc2,
        visitor_page_last_viewed = CURRENT_TIMESTAMP,
        visitor_page_title = VALUES(visitor_page_title),
        visitor_page_id = LAST_INSERT_ID(visitor_page_id)
    ");
    $stmt->bindParam(':uri', $uri);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':inc', $uniqueIncrement);
    $stmt->bindParam(':inc2', $uniqueIncrement);
    $stmt->execute();

    return (int) $this->connect->lastInsertId();
  }

  private function registerSession(int $visitorId, string $cookie, string $pageUri): void {
    $stmt = $this->connect->prepare("
      SELECT *
      FROM visitor_sessions
      WHERE visitor_session_cookie = :cookie
      LIMIT 1
    ");
    $stmt->bindParam(':cookie', $cookie);
    $stmt->execute();
    $session = $stmt->fetch(PDO::FETCH_OBJ);

    if ($session) {
      $path = $session->visitor_session_path ? json_decode($session->visitor_session_path, true) : [];
      $path[] = ['uri' => $pageUri, 'time' => date('Y-m-d H:i:s')];
      $path_json = json_encode($path);
      $session_id = $session->visitor_session_id;

      $stmt = $this->connect->prepare("
        UPDATE visitor_sessions
        SET visitor_session_path = :path,
            visitor_session_end_page = :uri,
            visitor_session_end_time = CURRENT_TIMESTAMP
        WHERE visitor_session_id = :sid
      ");
      $stmt->bindParam(':path', $path_json);
      $stmt->bindParam(':uri', $pageUri);
      $stmt->bindParam(':sid', $session_id);
      $stmt->execute();
      return;
    }

    $path_init = json_encode([['uri' => $pageUri, 'time' => date('Y-m-d H:i:s')]]);

    $stmt = $this->connect->prepare("
      INSERT INTO visitor_sessions (
        visitor_session_visitor_id,
        visitor_session_cookie,
        visitor_session_start_page,
        visitor_session_end_page,
        visitor_session_path
      ) VALUES (:vid, :cookie, :uri_start, :uri_end, :path)
    ");
    $stmt->bindParam(':vid', $visitorId);
    $stmt->bindParam(':cookie', $cookie);
    $stmt->bindParam(':uri_start', $pageUri);
    $stmt->bindParam(':uri_end', $pageUri);
    $stmt->bindParam(':path', $path_init);
    $stmt->execute();
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

    $stmt->bindParam(':vid', $visitorId);
    $stmt->bindParam(':pid', $pageId);
    $stmt->bindParam(':ip', $ip);
    $stmt->bindParam(':ref', $referer);
    $stmt->execute();

    if (mt_rand(1, 20) === 1) {
      $this->connect->query("
        DELETE FROM visitor_useronlines
        WHERE visitor_useronline_last_activity < (CURRENT_TIMESTAMP - INTERVAL 10 MINUTE)
      ");
    }
  }

  private function getLocationFromAPI(string $ip): array {
    if ($ip === '127.0.0.1' || $ip === '::1' || $ip === '0.0.0.0') {
      return ['country' => 'LC', 'region' => 'Local', 'city' => 'Localhost'];
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
      'country' => $data['countryCode'] ?? 'Desconocido',
      'region'  => $data['region'] ?? null,
      'city'    => $data['city'] ?? null
    ];
  }

  public function resolveUnknownCountries(int $limit = 100): int {
    $stmt = $this->connect->prepare("
      SELECT visitor_id, visitor_ip
      FROM visitors
      WHERE visitor_country = 'Desconocido' OR visitor_country IS NULL
      LIMIT :l
    ");
    $stmt->bindParam(':l', $limit, PDO::PARAM_INT);
    $stmt->execute();

    $rows    = $stmt->fetchAll(PDO::FETCH_OBJ);
    $updated = 0;

    if (count($rows) === 0) return 0;

    $stmt_upd = $this->connect->prepare("
      UPDATE visitors
      SET visitor_country = :c,
          visitor_region = :r,
          visitor_city = :ci
      WHERE visitor_id = :id
    ");

    foreach ($rows as $row) {
      $geo = $this->getLocationFromAPI($row->visitor_ip);
      
      // Si sigue siendo Desconocido, lo marcamos como '??' para no re-intentar en el próximo bucle
      $c  = ($geo['country'] === 'Desconocido') ? '??' : $geo['country'];
      $r  = $geo['region'];
      $ci = $geo['city'];
      $id = $row->visitor_id;

      $stmt_upd->bindParam(':c', $c);
      $stmt_upd->bindParam(':r', $r);
      $stmt_upd->bindParam(':ci', $ci);
      $stmt_upd->bindParam(':id', $id);
      $stmt_upd->execute();

      $updated++;
      if ($updated % 5 === 0) usleep(100000);
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
