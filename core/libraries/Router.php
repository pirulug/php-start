<?php

/**
 * Router
 *
 * Motor de enrutamiento del framework.
 * Permite la definición de rutas con parámetros dinámicos,
 * grupos de rutas, prefijos, contextos y gestión de middlewares.
 *
 * @author Pirulug
 * @link   https://github.com/pirulug
 */
class Router {
  // --------------------------------------------------------------------------
  // PROPIEDADES ESTÁTICAS (ALMACENAMIENTO)
  // --------------------------------------------------------------------------

  protected static array $routes = [];
  protected static string $prefix = '';
  protected static string $context = CTX_FRONT;

  // --------------------------------------------------------------------------
  // PROPIEDADES DE INSTANCIA (BUILDER)
  // --------------------------------------------------------------------------

  protected array $route = [];

  /**
   * Constructor privado para forzar el uso del método estático route().
   *
   * @param string $uri URI normalizada de la ruta.
   */
  protected function __construct(string $uri) {
    $this->route = [
      'uri'         => $uri,
      'action'      => null,
      'view'        => null,
      'layout'      => null,
      'middlewares' => [],
      'context'     => self::$context,
    ];
  }

  // --------------------------------------------------------------------------
  // SECCIÓN: GRUPOS Y PREFIJOS
  // --------------------------------------------------------------------------

  /**
   * Define un grupo de rutas compartiendo prefijo y contexto.
   *
   * @param string $prefix Prefijo de la URL (ej: /admin).
   * @param string $context Contexto de ejecución (admin, api, home).
   * @param callable $callback Función que contiene las definiciones de rutas.
   */
  public static function prefix(string $prefix, string $context, callable $callback): void {
    $previousPrefix  = self::$prefix;
    $previousContext = self::$context;

    self::$prefix  = trim($prefix, '/');
    self::$context = $context;

    $callback();

    self::$prefix  = $previousPrefix;
    self::$context = $previousContext;
  }

  // --------------------------------------------------------------------------
  // SECCIÓN: DEFINICIÓN DE RUTAS
  // --------------------------------------------------------------------------

  /**
   * Inicia la definición de una nueva ruta.
   *
   * @param string $uri URI de la ruta (soporta parámetros entre llaves {id}).
   * @return self Instancia del constructor de ruta.
   */
  public static function route(string $uri): self {
    $uri = self::buildUri($uri);
    return new self($uri);
  }

  /**
   * Registra la ruta configurada en el listado global de rutas.
   */
  public function register(): void {
    $uri = $this->route['uri'];

    // Pre-calcular patrón de regex y nombres de parámetros para optimizar resolución
    preg_match_all('#\{([a-zA-Z0-9_]+)(?::((?:[^{}]*|\{[^{}]*\})*))?\}#', $uri, $matches, PREG_SET_ORDER);
    $paramNames = [];
    $pattern = $uri;
    
    foreach ($matches as $match) {
        $paramNames[] = $match[1];
        $regex = $match[2] ?? '[^/]+';
        $pattern = str_replace($match[0], '(' . $regex . ')', $pattern);
    }

    $this->route['pattern']     = '#^' . $pattern . '$#';
    $this->route['param_names'] = $paramNames;

    self::$routes[$uri] = $this->route;
  }

  // --------------------------------------------------------------------------
  // SECCIÓN: CONFIGURACIÓN (FLUENT API)
  // --------------------------------------------------------------------------

  /**
   * Cambia el contexto de la ruta actual.
   *
   * @param string $context admin, front, api.
   * @return self
   */
  public function setContext(string $context): self {
    $this->route['context'] = $context;
    return $this;
  }

  /**
   * Obtiene los datos de la configuración de la ruta actual.
   *
   * @return array
   */
  public function getRoute(): array {
    return $this->route;
  }

  /**
   * Asigna un archivo de acción a la ruta. Soporta sintaxis módulo@accion.
   *
   * @param string $path Ruta física o sintaxis de módulo.
   * @return self Instancia.
   */
  public function action(string $path): self {
    if (strpos($path, '@') !== false) {
      $parts   = explode('@', $path);
      $module  = $parts[0];
      $this->route['module'] = $module;

      $path    = str_replace('@', '/', $path);
      $context = $this->route['context'];

      $path = match ($context) {
        'admin' => $this->resolvePath(BASE_DIR . '/app/admin/modules', 'actions', $path, '.action.php', 'admin action'),
        'api'   => $this->resolvePath(BASE_DIR . '/app/api', 'actions', $path, '.php', 'api action'),
        default => $this->resolvePath(BASE_DIR . '/app/front/modules', 'actions', $path, '.action.php', 'front action')
      };
    }
    $this->route['action'] = $path;
    return $this;
  }

  /**
   * Asigna un archivo de vista a la ruta. Soporta sintaxis módulo@vista.
   *
   * @param string $path Ruta física o sintaxis de módulo.
   * @return self Instancia.
   */
  public function view(string $path): self {
    if (strpos($path, '@') !== false) {
      $parts   = explode('@', $path);
      $module  = $parts[0];
      $this->route['module'] = $module;

      $path    = str_replace('@', '/', $path);
      $context = $this->route['context'];

      $path = match ($context) {
        'admin' => $this->resolvePath(BASE_DIR . '/app/admin/modules', 'views', $path, '.view.php', 'admin view'),
        'api'   => $this->resolvePath(BASE_DIR . '/app/api', 'views', $path, '.view.php', 'api view'),
        default => $this->resolvePath(BASE_DIR . '/app/front/modules', 'views', $path, '.view.php', 'front view')
      };
    }
    $this->route['view'] = $path;
    return $this;
  }

  /**
   * Asigna un archivo de endpoint a la ruta. Soporta sintaxis módulo@endpoint.
   *
   * @param string $path Ruta física o sintaxis de módulo.
   * @return self Instancia.
   */
  public function endpoint(string $path): self {
    if (strpos($path, '@') !== false) {
      $parts   = explode('@', $path);
      $module  = $parts[0];
      $this->route['module'] = $module;

      $path    = str_replace('@', '/', $path);
      $context = $this->route['context'];

      $path = match ($context) {
        'admin' => $this->resolvePath(BASE_DIR . '/app/admin/modules', 'endpoints', $path, '.endpoint.php', 'admin endpoint'),
        'api'   => $this->resolvePath(BASE_DIR . '/app/api', 'endpoints', $path, '.php', 'api endpoint'),
        default => $this->resolvePath(BASE_DIR . '/app/front/modules', 'endpoints', $path, '.endpoint.php', 'front endpoint')
      };
    }
    $this->route['action'] = $path;
    return $this;
  }

  /**
   * Asigna el layout decorador para la vista.
   *
   * @param string $path Nombre del layout o ruta completa.
   * @return self Instancia.
   */
  public function layout(string $path = 'main'): self {
    if (strpos($path, '/') === false && strpos($path, '\\') === false) {
      $context = $this->route['context'];

      $path = match ($context) {
        'admin' => $this->resolvePath(BASE_DIR . '/app/admin/layouts', null, $path, '.layout.php', 'admin layout'),
        'api'   => $this->resolvePath(BASE_DIR . '/app/api/layouts', null, $path, '.layout.php', 'api layout'),
        default => $this->resolvePath(BASE_DIR . '/app/front/layouts', null, $path, '.layout.php', 'front layout')
      };
    }
    $this->route['layout'] = $path;
    return $this;
  }

  /**
   * Añade un middleware a la cola de ejecución de la ruta.
   *
   * @param string $name Nombre del middleware.
   * @param mixed $params Parámetros adicionales para el middleware.
   * @return self Instancia.
   */
  public function middleware(string $name, $params = null): self {
    $this->route['middlewares'][] = [$name, $params];
    return $this;
  }

  /**
   * Atajo para asignar una validación de permisos mediante middleware.
   *
   * @param string $permission Llave del permiso requerido.
   * @return self Instancia.
   */
  public function permission(string $permission): self {
    $this->route['middlewares'][] = ['permission', $permission];
    return $this;
  }

  // --------------------------------------------------------------------------
  // SECCIÓN: RESOLUCIÓN DE RUTAS
  // --------------------------------------------------------------------------

  /**
   * Busca y resuelve una ruta coincidente para la URI solicitada.
   * Gestiona la extracción de parámetros dinámicos.
   *
   * @param string $uri URI de la petición actual.
   * @return array|null Datos de la ruta resuelta o null si no existe.
   */
  public static function resolve(string $uri): ?array {
    $uri = self::normalizeUri($uri);

    foreach (self::$routes as $route) {
      if (isset($route['pattern']) && preg_match($route['pattern'], $uri, $matches)) {
        array_shift($matches);
        $params = [];

        foreach ($route['param_names'] as $index => $name) {
          $params[$name] = $matches[$index] ?? null;
        }

        $route['params'] = $params;
        return $route;
      }
    }

    return null;
  }

  // --------------------------------------------------------------------------
  // SECCIÓN: HELPERS DE NORMALIZACIÓN
  // --------------------------------------------------------------------------

  /**
   * Construye la URI final concatenando prefijos globales.
   *
   * @param string $uri URI base.
   * @return string URI completa.
   */
  protected static function buildUri(string $uri): string {
    $uri  = trim($uri, '/');
    $full = trim(self::$prefix . '/' . $uri, '/');

    return $full === '' ? '/' : $full;
  }

  /**
   * Normaliza la URI eliminando barras laterales excesivas.
   *
   * @param string $uri URI cruda.
   * @return string URI normalizada.
   */
  protected static function normalizeUri(string $uri): string {
    $uri = trim($uri, '/');
    return $uri === '' ? '/' : $uri;
  }

  /**
   * Obtiene todas las rutas registradas.
   *
   * @return array
   */
  public static function getRoutes(): array {
    return self::$routes;
  }

  // --------------------------------------------------------------------------
  // SECCIÓN: RESOLUTOR DE ARCHIVOS (INTERNAL)
  // --------------------------------------------------------------------------

  /**
   * Detiene la ejecución y muestra un error de ruta formateado.
   *
   * @param string $title Título del error.
   * @param array $data Datos descriptivos del error.
   */
  protected function pathError(string $title, array $data): void {
    http_response_code(500);

    echo "<pre style='
    background:#111;
    color:#eee;
    padding:20px;
    font-family:monospace;
    border-left:5px solid #e74c3c;
    '>";

    echo strtoupper($title) . " ERROR\n\n";

    foreach ($data as $key => $value) {
      echo str_pad($key, 10, ' ', STR_PAD_RIGHT) . ": {$value}\n";
    }

    echo "</pre>";

    exit();
  }

  /**
   * Resuelve la ruta física de un archivo dentro de un módulo o layout.
   *
   * @param string $basePath Ruta base.
   * @param string|null $subDir Subdirectorio (actions, views) o null para layouts.
   * @param string $name Nombre del archivo o modulo/archivo.
   * @param string $ext Extensión.
   * @param string $type Etiqueta para error.
   * @return string Ruta física absoluta.
   */
  protected function resolvePath(string $basePath, ?string $subDir, string $name, string $ext, string $type): string {
    if ($subDir !== null) {
      if (strpos($name, '/') === false) {
        $this->pathError($type, [
          'Motivo'   => 'Formato inválido',
          'Esperado' => 'modulo/archivo',
          'Recibido' => $name
        ]);
      }

      [$module, $file] = explode('/', $name, 2);

      if ($module === '' || $file === '') {
        $this->pathError($type, [
          'Motivo' => 'Módulo o archivo vacío',
          'Valor'  => $name
        ]);
      }

      $path = "{$basePath}/{$module}/{$subDir}/{$file}{$ext}";
    } else {
      $path = "{$basePath}/{$name}{$ext}";
    }

    if (!file_exists($path)) {
      $this->pathError($type, [
        'Motivo'  => 'Archivo no encontrado',
        'Ruta'    => $path
      ]);
    }

    return $path;
  }
}
