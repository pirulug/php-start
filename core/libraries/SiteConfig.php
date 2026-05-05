<?php

/**
 * SiteConfig
 *
 * Clase encargada de la gestión de la configuración global del sitio.
 * Carga las opciones desde la tabla 'options', soporta almacenamiento en caché
 * durante la ejecución y proporciona métodos de acceso simplificados para SEO,
 * formatos, SMTP y redes sociales.
 *
 * @author Pirulug
 * @link   https://github.com/pirulug
 */
class SiteConfig {
  // --------------------------------------------------------------------------
  // PROPIEDADES DE ESTADO
  // --------------------------------------------------------------------------

  private PDO $db;
  private ?object $data = null;
  private bool $loaded = false;

  /**
   * Constructor: inyecta la conexión a la base de datos.
   *
   * @param PDO $db Objeto de conexión.
   */
  public function __construct(PDO $db) {
    $this->db = $db;
  }

  // --------------------------------------------------------------------------
  // SECCIÓN: CARGA Y ACCESO BÁSICO
  // --------------------------------------------------------------------------

  /**
   * Carga las opciones desde la base de datos si no han sido cargadas.
   * Realiza un parseo automático de valores JSON a objetos PHP.
   * Soporta caché en archivo para mejorar el rendimiento.
   */
  private function load(): void {
    if ($this->loaded) {
      return;
    }

    $cacheFile = BASE_DIR . '/storage/caches/site_options.cache.php';

    // Intentar cargar desde caché
    if (file_exists($cacheFile)) {
      $cacheData = require $cacheFile;
      if ($cacheData) {
        $this->data   = (object) $cacheData;
        $this->loaded = true;
        return;
      }
    }

    $data = new stdClass();

    try {
      $stmt = $this->db->prepare("SELECT option_key, option_value FROM options");
      $stmt->execute();

      $rows = $stmt->fetchAll(PDO::FETCH_OBJ);

      foreach ($rows as $row) {
        $value = $row->option_value;

        // Parseo automático de JSON a Objetos/Arrays
        if (is_string($value) && (strpos($value, '{') === 0 || strpos($value, '[') === 0)) {
          $json = json_decode($value); // Volvemos a objetos
          if (json_last_error() === JSON_ERROR_NONE) {
            $value = $json;
          }
        }

        $data->{$row->option_key} = $value;
      }

      // Guardar en caché para futuras peticiones
      $cacheContent = "<?php\n\n// Protección contra acceso directo\ndefined('BASE_DIR') OR exit('No direct script access allowed');\n\nreturn " . var_export($data, true) . ";\n";
      file_put_contents($cacheFile, $cacheContent);
    } catch (Exception $e) {
      // Si la tabla no existe o falla, permitimos datos vacíos para activar fallbacks
    }

    $this->data   = $data;
    $this->loaded = true;
  }

  /**
   * Obtiene un valor de configuración mediante una llave.
   *
   * @param string $key Clave de la opción.
   * @param mixed $default Valor por defecto si no existe.
   * @return mixed Valor de la opción.
   */
  public function get(string $key, mixed $default = null): mixed {
    $this->load();
    return $this->data->{$key} ?? $default;
  }

  /**
   * Método mágico para acceso rápido mediante propiedades.
   *
   * @param string $name Nombre de la opción.
   * @return mixed
   */
  public function __get(string $name) {
    return $this->get($name);
  }

  // --------------------------------------------------------------------------
  // SECCIÓN: SEO Y GENERAL
  // --------------------------------------------------------------------------

  /**
   * Obtiene el nombre del sitio.
   */
  public function siteName(): string {
    return $this->get('site_name', defined('APP_NAME') ? APP_NAME : 'PHP Start');
  }

  /**
   * Obtiene la URL base del sitio.
   */
  public function siteUrl(): string {
    return $this->get('site_url', defined('APP_URL') ? APP_URL : 'http://localhost');
  }

  /**
   * Obtiene la descripción meta para SEO.
   */
  public function siteDescription(): string {
    return $this->get('site_description', '');
  }

  /**
   * Obtiene las palabras clave para SEO.
   */
  public function siteKeywords(): string {
    return $this->get('site_keywords', '');
  }

  /**
   * Obtiene el código de lenguaje (ISO 639-1).
   */
  public function language(): string {
    return $this->get('site_language', 'es');
  }

  /**
   * Obtiene la zona horaria configurada.
   */
  public function timezone(): string {
    return $this->get('site_timezone', 'America/Lima');
  }

  /**
   * Genera el título de la página combinando el nombre del sitio.
   *
   * @param string|null $pageTitle Título específico de la página.
   * @return string Título final.
   */
  public function title(?string $pageTitle = null): string {
    $site = $this->siteName();
    return $pageTitle ? "{$pageTitle} | {$site}" : $site;
  }

  /**
   * Obtiene la versión actual del sistema.
   */
  public function version(): string {
    return $this->get('version', '1.0.0');
  }

  // --------------------------------------------------------------------------
  // SECCIÓN: FORMATOS DE FECHA Y HORA
  // --------------------------------------------------------------------------

  /**
   * Obtiene el formato de fecha (ej: d/m/Y).
   */
  public function dateFormat(): string {
    return $this->get('date_format', 'd/m/Y');
  }

  /**
   * Obtiene el formato de hora (ej: H:i a).
   */
  public function timeFormat(): string {
    return $this->get('time_format', 'H:i a');
  }

  /**
   * Obtiene el formato completo de fecha y hora.
   */
  public function datetimeFormat(): string {
    return $this->get('datetime_format', 'd/m/Y - H:i a');
  }

  // --------------------------------------------------------------------------
  // SECCIÓN: MULTIMEDIA Y ASSETS
  // --------------------------------------------------------------------------

  /**
   * Obtiene los metadatos del favicon.
   */
  public function favicon(): ?object {
    return $this->get('favicon');
  }

  /**
   * Obtiene los logos para temas claros y oscuros.
   */
  public function logo(): object {
    return (object) [
      'dark'  => $this->get('dark_logo'),
      'light' => $this->get('white_logo'),
    ];
  }

  /**
   * Obtiene la imagen por defecto para Open Graph (OG).
   */
  public function ogImage(): ?string {
    return $this->get('og_image');
  }

  // --------------------------------------------------------------------------
  // SECCIÓN: SERVICIOS (SMTP, CAPTCHA, SOCIAL)
  // --------------------------------------------------------------------------

  /**
   * Obtiene la configuración de correo SMTP.
   */
  public function smtp(): object {
    return (object) [
      'host'       => $this->get('smtp_host'),
      'email'      => $this->get('smtp_email'),
      'password'   => $this->get('smtp_password'),
      'port'       => $this->get('smtp_port', '587'),
      'encryption' => $this->get('smtp_encryption', 'tls'),
    ];
  }

  /**
   * Obtiene la configuración de seguridad Captcha.
   */
  public function captcha(): object {
    return (object) [
      'enabled'    => (bool) $this->get('captcha_enabled', false),
      'type'       => $this->get('captcha_type', 'vanilla'),
      'google'     => (object) [
        'site_key'   => $this->get('google_recaptcha_site_key'),
        'secret_key' => $this->get('google_recaptcha_secret_key')
      ],
      'cloudflare' => (object) [
        'site_key'   => $this->get('cloudflare_turnstile_site_key'),
        'secret_key' => $this->get('cloudflare_turnstile_secret_key')
      ]
    ];
  }

  /**
   * Obtiene el listado de redes sociales configuradas.
   * Soporta retrocompatibilidad con el formato antiguo de llaves individuales.
   *
   * @return array Listado de objetos de redes sociales.
   */
  public function social(): array {
    $social = $this->get('site_social');

    if (is_array($social)) {
      return $social;
    }

    $list     = [];
    $old_data = (is_object($social) || is_array($social)) ? (array) $social : [
      'facebook'  => $this->get('facebook'),
      'twitter'   => $this->get('twitter'),
      'instagram' => $this->get('instagram'),
      'youtube'   => $this->get('youtube'),
      'linkedin'  => $this->get('linkedin'),
      'tiktok'    => $this->get('tiktok'),
      'github'    => $this->get('github'),
    ];

    foreach ($old_data as $name => $url) {
      if (!empty($url)) {
        $list[] = (object) [
          'name' => $name,
          'url'  => $url,
          'icon' => null
        ];
      }
    }

    return $list;
  }

  // --------------------------------------------------------------------------
  // SECCIÓN: UTILIDADES Y MANTENIMIENTO
  // --------------------------------------------------------------------------

  /**
   * Indica si el sitio está en modo mantenimiento.
   */
  public function isMaintenanceMode(): bool {
    return (bool) $this->get('maintenance_mode', false);
  }

  /**
   * Obtiene el mensaje de aviso para el modo mantenimiento.
   */
  public function maintenanceMessage(): string {
    return $this->get('site_maintenance_msg', 'Estamos trabajando en mejoras. Volvemos pronto.');
  }

  /**
   * Limpia el estado interno forzando una nueva carga desde la DB.
   *
   * @return self Instancia.
   */
  public function refresh(): self {
    $cacheFileJson = BASE_DIR . '/storage/caches/site_options.json';
    $cacheFilePhp  = BASE_DIR . '/storage/caches/site_options.cache.php';

    if (file_exists($cacheFileJson)) {
      unlink($cacheFileJson);
    }
    if (file_exists($cacheFilePhp)) {
      unlink($cacheFilePhp);
    }

    $this->loaded = false;
    $this->data   = null;
    return $this;
  }
}