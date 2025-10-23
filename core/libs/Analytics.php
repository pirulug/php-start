<?php

class Analytics {
  private $connect;

  public function __construct($pdo) {
    $this->connect = $pdo;
  }

  public function trackVisit($pageTitle, $pageUri) {
    // Ignorar recursos estáticos
    $ext = pathinfo(parse_url($pageUri, PHP_URL_PATH), PATHINFO_EXTENSION);
    if (in_array(strtolower($ext), ['ico', 'png', 'jpg', 'jpeg', 'gif', 'css', 'js', 'svg', 'webp']))
      return;

    // Identificar IP, agente, referer, cookie
    $ip        = $_SERVER['REMOTE_ADDR'];
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
    $referer   = $_SERVER['HTTP_REFERER'] ?? '';
    $cookie    = $_COOKIE['anly_session_id'] ?? null;

    if (!$cookie) {
      $cookie = bin2hex(random_bytes(16));
      setcookie('anly_session_id', $cookie, time() + 3600 * 24 * 7, '/');
    }

    // Registrar o actualizar visitante
    $visitorId = $this->registerVisitor($ip, $userAgent, $referer);

    // Registrar o actualizar página
    $pageId = $this->registerPage($pageUri, $pageTitle);

    // Registrar sesión (trayectoria, entrada, salida)
    $this->registerSession($visitorId, $cookie, $pageUri, $referer);

    // Actualizar usuarios online
    $this->updateUserOnline($visitorId, $pageId, $ip, $referer);
  }

  private function registerVisitor($ip, $userAgent, $referer) {
    // Detectar datos del agente
    $browser  = $this->getBrowser($userAgent);
    $platform = $this->getPlatform($userAgent);
    $device   = $this->getDevice($userAgent);
    $location = $this->getCountryFromIP($ip);

    // Insertar o actualizar visitante
    $stmt = $this->connect->prepare("
        INSERT INTO anly_visitor (
            anly_visitor_ip,
            anly_visitor_user_agent,
            anly_visitor_browser,
            anly_visitor_platform,
            anly_visitor_device,
            anly_visitor_country,
            anly_visitor_region,
            anly_visitor_city,
            anly_visitor_referer,
            anly_visitor_total_hits
        ) VALUES (
            :ip, :ua, :browser, :platform, :device,
            :country, :region, :city, :referer, 1
        )
        ON DUPLICATE KEY UPDATE
            anly_visitor_total_hits = anly_visitor_total_hits + 1,
            anly_visitor_last_visit = NOW(),
            anly_visitor_referer = VALUES(anly_visitor_referer)
    ");
    $stmt->execute([
      ':ip'       => $ip,
      ':ua'       => $userAgent,
      ':browser'  => $browser,
      ':platform' => $platform,
      ':device'   => $device,
      ':country'  => $location['country'],
      ':region'   => $location['region'],
      ':city'     => $location['city'],
      ':referer'  => $referer,
    ]);

    // Obtener el ID del visitante
    $stmt = $this->connect->prepare("
        SELECT anly_visitor_id FROM anly_visitor
        WHERE anly_visitor_ip = :ip AND anly_visitor_user_agent = :ua
        LIMIT 1
    ");
    $stmt->execute([':ip' => $ip, ':ua' => $userAgent]);
    $visitor = $stmt->fetch(PDO::FETCH_ASSOC);

    return $visitor ? $visitor['anly_visitor_id'] : null;
  }


  private function registerPage($uri, $title) {
    $stmt = $this->connect->prepare("SELECT anly_pages_id FROM anly_pages WHERE anly_pages_uri = ? LIMIT 1");
    $stmt->execute([$uri]);
    $page = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($page) {
      $this->connect->prepare("UPDATE anly_pages 
                SET anly_pages_total_views = anly_pages_total_views + 1, 
                    anly_pages_last_viewed = NOW() 
                WHERE anly_pages_id = ?")
        ->execute([$page['anly_pages_id']]);
      return $page['anly_pages_id'];
    } else {
      $this->connect->prepare("INSERT INTO anly_pages (
                anly_pages_uri, anly_pages_title, 
                anly_pages_total_views, anly_pages_unique_visitors
            ) VALUES (?, ?, 1, 1)")
        ->execute([$uri, $title]);
      return $this->connect->lastInsertId();
    }
  }

  private function registerSession($visitorId, $cookie, $pageUri, $referer) {
    $stmt = $this->connect->prepare("SELECT * FROM anly_sessions WHERE anly_sessions_cookie = ? LIMIT 1");
    $stmt->execute([$cookie]);
    $session = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($session) {
      // Actualizar trayectoria
      $path     = $session['anly_sessions_path'] ? json_decode($session['anly_sessions_path'], true) : [];
      $path[]   = ['uri' => $pageUri, 'time' => date('Y-m-d H:i:s')];
      $jsonPath = json_encode($path);

      $this->connect->prepare("UPDATE anly_sessions 
                SET anly_sessions_path = ?, 
                    anly_sessions_end_page = ?, 
                    anly_sessions_end_time = NOW() 
                WHERE anly_sessions_id = ?")
        ->execute([$jsonPath, $pageUri, $session['anly_sessions_id']]);
    } else {
      // Nueva sesión
      $path = json_encode([['uri' => $pageUri, 'time' => date('Y-m-d H:i:s')]]);
      $this->connect->prepare("INSERT INTO anly_sessions (
                anly_sessions_visitor_id, anly_sessions_cookie, 
                anly_sessions_start_page, anly_sessions_end_page, 
                anly_sessions_path
            ) VALUES (?, ?, ?, ?, ?)")
        ->execute([$visitorId, $cookie, $pageUri, $pageUri, $path]);
    }
  }

  private function updateUserOnline($visitorId, $pageId, $ip, $referer) {
    $stmt = $this->connect->prepare("REPLACE INTO anly_useronline 
            (anly_useronline_visitor_id, anly_useronline_page_id, 
             anly_useronline_ip, anly_useronline_referer, 
             anly_useronline_last_activity) 
             VALUES (?, ?, ?, ?, NOW())");
    $stmt->execute([$visitorId, $pageId, $ip, $referer]);
  }

  // Localización por tabla iplocation
  private function getCountryFromIP($ip) {
    $ipNum = sprintf('%u', ip2long($ip));
    $stmt  = $this->connect->prepare("SELECT 
            iplo_country_name AS country, 
            iplo_region_name AS region, 
            iplo_city_name AS city 
            FROM iplocation WHERE ? BETWEEN iplo_ip_from AND iplo_ip_to LIMIT 1");
    $stmt->execute([$ipNum]);
    return $stmt->fetch(PDO::FETCH_ASSOC) ?: ['country' => 'Desconocido', 'region' => null, 'city' => null];
  }

  // --- Detectar navegador / plataforma / dispositivo ---
  private function getBrowser($ua) {
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

  private function getPlatform($ua) {
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

  private function getDevice($ua) {
    if (preg_match('/Mobile|Android|iPhone/i', $ua))
      return 'Smartphone';
    if (preg_match('/Tablet|iPad/i', $ua))
      return 'Tablet';
    return 'Desktop';
  }
}
