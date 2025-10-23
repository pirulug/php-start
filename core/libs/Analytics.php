<?php

class Analytics {
  protected $db;

  public function __construct($pdo) {
    $this->db = $pdo;
  }

  public function trackVisit($pageTitle, $pageUri) {

    // Ignorar recursos estáticos (favicon, imágenes, JS, CSS, etc.)
    $ext = pathinfo(parse_url($pageUri, PHP_URL_PATH), PATHINFO_EXTENSION);
    if (in_array(strtolower($ext), ['ico', 'png', 'jpg', 'jpeg', 'gif', 'css', 'js', 'svg', 'webp'])) {
      return; // No registrar estos archivos
    }

    // También ignora el favicon sin extensión explícita
    if (stripos($pageUri, 'favicon.ico') !== false) {
      return;
    }

    $ip        = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
    $referer   = $_SERVER['HTTP_REFERER'] ?? null;
    $browser   = $this->getBrowser($userAgent);
    $platform  = $this->getPlatform($userAgent);
    $device    = $this->getDevice($userAgent);
    $geo       = $this->getCountryFromIP($ip);

    $country = $geo['country'] ?? null;
    $region  = $geo['region'] ?? null;
    $city    = $geo['city'] ?? null;

    $visitorId = $this->getOrCreateVisitor($ip, $userAgent, $browser, $platform, $device, $country, $referer);
    $pageId    = $this->getOrCreatePage($pageTitle, $pageUri);

    $this->db->prepare("
            UPDATE anly_pages 
            SET anly_pages_total_views = anly_pages_total_views + 1,
                anly_pages_last_viewed = NOW()
            WHERE anly_pages_id = ?
        ")->execute([$pageId]);

    $this->db->prepare("
            UPDATE anly_visitor 
            SET anly_visitor_total_hits = anly_visitor_total_hits + 1,
                anly_visitor_last_visit = NOW()
            WHERE anly_visitor_id = ?
        ")->execute([$visitorId]);

    $this->updateUserOnline($visitorId, $pageId, $ip, $userAgent, $platform, $country, $referer);
  }

  protected function getOrCreateVisitor($ip, $userAgent, $browser, $platform, $device, $country, $referer) {
    $stmt = $this->db->prepare("
            SELECT anly_visitor_id FROM anly_visitor
            WHERE anly_visitor_ip = ? AND anly_visitor_user_agent = ?
            LIMIT 1
        ");
    $stmt->execute([$ip, $userAgent]);
    $visitor = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($visitor) {
      return $visitor['anly_visitor_id'];
    }

    $insert = $this->db->prepare("
            INSERT INTO anly_visitor (
                anly_visitor_ip, anly_visitor_user_agent, anly_visitor_browser,
                anly_visitor_platform, anly_visitor_device, anly_visitor_country,
                anly_visitor_referer
            ) VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
    $insert->execute([$ip, $userAgent, $browser, $platform, $device, $country, $referer]);

    return $this->db->lastInsertId();
  }

  protected function getOrCreatePage($title, $uri) {
    $stmt = $this->db->prepare("SELECT anly_pages_id FROM anly_pages WHERE anly_pages_uri = ? LIMIT 1");
    $stmt->execute([$uri]);
    $page = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($page) {
      return $page['anly_pages_id'];
    }

    $insert = $this->db->prepare("
            INSERT INTO anly_pages (anly_pages_uri, anly_pages_title)
            VALUES (?, ?)
        ");
    $insert->execute([$uri, $title]);

    return $this->db->lastInsertId();
  }

  protected function updateUserOnline($visitorId, $pageId, $ip, $agent, $platform, $country, $referer) {
    $this->db->query("DELETE FROM anly_useronline WHERE anly_useronline_last_activity < (NOW() - INTERVAL 10 MINUTE)");

    $stmt = $this->db->prepare("
            SELECT anly_useronline_id FROM anly_useronline
            WHERE anly_useronline_visitor_id = ? LIMIT 1
        ");
    $stmt->execute([$visitorId]);
    $online = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($online) {
      $this->db->prepare("
                UPDATE anly_useronline 
                SET anly_useronline_last_activity = NOW(),
                    anly_useronline_page_id = ?, 
                    anly_useronline_ip = ?, 
                    anly_useronline_agent = ?, 
                    anly_useronline_platform = ?, 
                    anly_useronline_country = ?, 
                    anly_useronline_referer = ?
                WHERE anly_useronline_id = ?
            ")->execute([$pageId, $ip, $agent, $platform, $country, $referer, $online['anly_useronline_id']]);
    } else {
      $this->db->prepare("
                INSERT INTO anly_useronline (
                    anly_useronline_visitor_id, anly_useronline_page_id, 
                    anly_useronline_ip, anly_useronline_agent, 
                    anly_useronline_platform, anly_useronline_country, 
                    anly_useronline_referer
                ) VALUES (?, ?, ?, ?, ?, ?, ?)
            ")->execute([$visitorId, $pageId, $ip, $agent, $platform, $country, $referer]);
    }
  }

  /* ==========================
     Detección de IP y ubicación
  =========================== */
  protected function getCountryFromIP($ip) {
    try {
      $ipNumeric = $this->ipToNumber($ip);

      $stmt = $this->db->prepare("
                SELECT iplo_country_name AS country, iplo_region_name AS region, iplo_city_name AS city
                FROM iplocation
                WHERE ? BETWEEN iplo_ip_from AND iplo_ip_to
                LIMIT 1
            ");
      $stmt->execute([$ipNumeric]);
      $geo = $stmt->fetch(PDO::FETCH_ASSOC);

      if ($geo) {
        return $geo;
      }

      return [
        'country' => 'Desconocido',
        'region'  => null,
        'city'    => null
      ];
    } catch (Exception $e) {
      return [
        'country' => 'Error',
        'region'  => null,
        'city'    => null
      ];
    }
  }

  protected function ipToNumber($ip) {
    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
      // IPv6 a decimal grande
      $binNum = inet_pton($ip);
      $value  = unpack('H*', $binNum)[1];
      return base_convert($value, 16, 10);
    } elseif (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
      // IPv4 a decimal
      return sprintf('%u', ip2long($ip));
    }
    return 0;
  }

  /* =======================
     Métodos de detección
  ======================= */
  protected function getBrowser($userAgent) {
    if (preg_match('/Chrome/i', $userAgent))
      return 'Chrome';
    if (preg_match('/Firefox/i', $userAgent))
      return 'Firefox';
    if (preg_match('/Safari/i', $userAgent))
      return 'Safari';
    if (preg_match('/MSIE|Trident/i', $userAgent))
      return 'Internet Explorer';
    if (preg_match('/Edge/i', $userAgent))
      return 'Edge';
    return 'Desconocido';
  }

  protected function getPlatform($userAgent) {
    if (preg_match('/Windows/i', $userAgent))
      return 'Windows';
    if (preg_match('/Mac/i', $userAgent))
      return 'MacOS';
    if (preg_match('/Linux/i', $userAgent))
      return 'Linux';
    if (preg_match('/Android/i', $userAgent))
      return 'Android';
    if (preg_match('/iPhone|iPad/i', $userAgent))
      return 'iOS';
    return 'Desconocido';
  }

  protected function getDevice($userAgent) {
    if (preg_match('/Mobile|Android|iPhone/i', $userAgent))
      return 'Móvil';
    return 'Desktop';
  }
}
