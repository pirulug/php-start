<?php

class Analytics {
  private $connect;

  public function __construct(PDO $pdo) {
    $this->connect = $pdo;
  }

  public function trackVisit(string $pageTitle, string $pageUri): void {
    // Ignorar recursos estáticos
    $ext = pathinfo(parse_url($pageUri, PHP_URL_PATH), PATHINFO_EXTENSION);
    if (in_array(strtolower($ext), ['ico', 'png', 'jpg', 'jpeg', 'gif', 'css', 'js', 'svg', 'webp'])) {
      return;
    }

    // Datos del visitante
    $ip        = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
    $referer   = $_SERVER['HTTP_REFERER'] ?? '';

    // Manejo de cookie de sesión
    $cookie = $_COOKIE['visitor_session_id'] ?? null;
    if (!$cookie) {
      $cookie = bin2hex(random_bytes(16));
      setcookie('visitor_session_id', $cookie, time() + 3600 * 24 * 7, '/');
    }

    // Registrar entidades principales
    $visitorId = $this->registerVisitor($ip, $userAgent, $referer);
    $pageId    = $this->registerPage($pageUri, $pageTitle);
    $this->registerSession($visitorId, $cookie, $pageUri, $referer);
    $this->updateUserOnline($visitorId, $pageId, $ip, $referer);
  }

  /** ================= VISITOR ================= **/
  private function registerVisitor(string $ip, string $userAgent, string $referer): ?int {
    // Extraer información del agente
    $browser  = $this->getBrowser($userAgent);
    $platform = $this->getPlatform($userAgent);
    $device   = $this->getDevice($userAgent);

    // Obtener localización desde API (https://ipapi.pirulug.pw/api)
    $location = $this->getLocationFromAPI($ip);

    // Insertar o actualizar visitante
    $sql  = "
      INSERT INTO visitors (
        visitor_ip, visitor_user_agent, visitor_browser,
        visitor_platform, visitor_device,
        visitor_country, visitor_region, visitor_city,
        visitor_referer, visitor_total_hits
      ) VALUES (
        :ip, :ua, :browser, :platform, :device,
        :country, :region, :city, :referer, 1
      )
      ON DUPLICATE KEY UPDATE
        visitor_total_hits = visitor_total_hits + 1,
        visitor_last_visit = NOW(),
        visitor_referer = VALUES(visitor_referer)
    ";
    $stmt = $this->connect->prepare($sql);
    $stmt->execute([
      ':ip'       => $ip,
      ':ua'       => $userAgent,
      ':browser'  => $browser,
      ':platform' => $platform,
      ':device'   => $device,
      ':country'  => $location['country'] ?? 'Desconocido',
      ':region'   => $location['region'] ?? null,
      ':city'     => $location['city'] ?? null,
      ':referer'  => $referer,
    ]);

    // Recuperar ID del visitante
    $stmt = $this->connect->prepare("
      SELECT visitor_id FROM visitors
      WHERE visitor_ip = :ip AND visitor_user_agent = :ua
      LIMIT 1
    ");
    $stmt->execute([':ip' => $ip, ':ua' => $userAgent]);
    $visitor = $stmt->fetch(PDO::FETCH_ASSOC);

    return $visitor ? (int) $visitor['visitor_id'] : null;
  }

  /** ================= PAGES ================= **/
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
    } else {
      $this->connect->prepare("
        INSERT INTO visitor_pages (visitor_pages_uri, visitor_pages_title, visitor_pages_total_views, visitor_pages_unique_visitors)
        VALUES (?, ?, 1, 1)
      ")->execute([$uri, $title]);
      return (int) $this->connect->lastInsertId();
    }
  }

  /** ================= SESSIONS ================= **/
  private function registerSession(int $visitorId, string $cookie, string $pageUri, string $referer): void {
    $stmt = $this->connect->prepare("SELECT * FROM visitor_sessions WHERE visitor_sessions_cookie = ? LIMIT 1");
    $stmt->execute([$cookie]);
    $session = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($session) {
      // Actualizar trayectoria
      $path     = $session['visitor_sessions_path'] ? json_decode($session['visitor_sessions_path'], true) : [];
      $path[]   = ['uri' => $pageUri, 'time' => date('Y-m-d H:i:s')];
      $jsonPath = json_encode($path);

      $this->connect->prepare("
        UPDATE visitor_sessions
        SET visitor_sessions_path = ?, visitor_sessions_end_page = ?, visitor_sessions_end_time = NOW()
        WHERE visitor_sessions_id = ?
      ")->execute([$jsonPath, $pageUri, $session['visitor_sessions_id']]);
    } else {
      $path = json_encode([['uri' => $pageUri, 'time' => date('Y-m-d H:i:s')]]);
      $this->connect->prepare("
        INSERT INTO visitor_sessions (
          visitor_sessions_visitor_id, visitor_sessions_cookie,
          visitor_sessions_start_page, visitor_sessions_end_page,
          visitor_sessions_path
        ) VALUES (?, ?, ?, ?, ?)
      ")->execute([$visitorId, $cookie, $pageUri, $pageUri, $path]);
    }
  }

  /** ================= USER ONLINE ================= **/
  private function updateUserOnline(int $visitorId, int $pageId, string $ip, string $referer): void {
    // Duración de sesión online (5 minutos)
    $timeout = 5; // minutos

    // Verificar si el visitante ya está online recientemente
    $stmt = $this->connect->prepare("
      SELECT visitor_useronline_id
      FROM visitor_useronline
      WHERE visitor_useronline_visitor_id = :vid
        AND visitor_useronline_ip = :ip
        AND visitor_useronline_last_activity > (NOW() - INTERVAL :timeout MINUTE)
      LIMIT 1
    ");
    $stmt->bindValue(':vid', $visitorId, PDO::PARAM_INT);
    $stmt->bindValue(':ip', $ip);
    $stmt->bindValue(':timeout', $timeout, PDO::PARAM_INT);
    $stmt->execute();

    $existing = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existing) {
      // Solo actualizamos la actividad y página
      $update = $this->connect->prepare("
        UPDATE visitor_useronline
        SET visitor_useronline_last_activity = NOW(),
            visitor_useronline_page_id = :page_id,
            visitor_useronline_referer = :referer
        WHERE visitor_useronline_id = :id
      ");
      $update->execute([
        ':page_id' => $pageId,
        ':referer' => $referer,
        ':id'      => $existing['visitor_useronline_id']
      ]);
    } else {
      // Insertar nuevo registro (o reemplazar si ya existe combinación IP+visitor)
      $insert = $this->connect->prepare("
        INSERT INTO visitor_useronline (
          visitor_useronline_visitor_id,
          visitor_useronline_page_id,
          visitor_useronline_ip,
          visitor_useronline_referer,
          visitor_useronline_last_activity
        )
        VALUES (:vid, :page_id, :ip, :referer, NOW())
        ON DUPLICATE KEY UPDATE
          visitor_useronline_last_activity = NOW(),
          visitor_useronline_page_id = VALUES(visitor_useronline_page_id),
          visitor_useronline_referer = VALUES(visitor_useronline_referer)
      ");
      $insert->execute([
        ':vid'     => $visitorId,
        ':page_id' => $pageId,
        ':ip'      => $ip,
        ':referer' => $referer
      ]);
    }

    // Limpieza opcional: eliminar usuarios inactivos (>10 min)
    $this->connect->query("
      DELETE FROM visitor_useronline
      WHERE visitor_useronline_last_activity < (NOW() - INTERVAL 10 MINUTE)
    ");
  }

  /** ================= GEOLOCACIÓN EXTERNA ================= **/
  private function getLocationFromAPI(string $ip): array {
    if ($ip === '127.0.0.1' || $ip === '::1') {
      return ['country' => 'Localhost', 'region' => null, 'city' => null];
    }

    $url  = "https://ipapi.pirulug.pw/api/" . urlencode($ip);
    $json = @file_get_contents($url);

    if (!$json)
      return ['country' => 'Desconocido', 'region' => null, 'city' => null];
    $data = json_decode($json, true);

    if (!isset($data['success']) || !$data['success']) {
      return ['country' => 'Desconocido', 'region' => null, 'city' => null];
    }

    return [
      'country' => $data['country'] ?? 'Desconocido',
      'region'  => $data['region'] ?? null,
      'city'    => $data['city'] ?? null
    ];
  }

  /** ================= UTILIDADES UA ================= **/
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
