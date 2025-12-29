<?php

class Router {
  /* =========================================================
   * PROPIEDADES ESTÁTICAS
   * ========================================================= */

  protected static array $routes = [];
  protected static string $prefix = '';
  protected static string $context = CTX_FRONT;

  /* =========================================================
   * PROPIEDAD DE INSTANCIA (BUILDER)
   * ========================================================= */

  protected array $route;

  /* =========================================================
   * CONSTRUCTOR (INTERNO)
   * ========================================================= */

  protected function __construct(array &$route) {
    $this->route = &$route;
  }

  /* =========================================================
   * PREFIJOS / GRUPOS
   * ========================================================= */

  public static function prefix(string $prefix, string $context, callable $callback): void {
    $previousPrefix  = self::$prefix;
    $previousContext = self::$context;

    self::$prefix  = trim($prefix, '/');
    self::$context = $context;

    $callback();

    self::$prefix  = $previousPrefix;
    self::$context = $previousContext;
  }

  /* =========================================================
   * REGISTRO DE RUTAS (GET)
   * ========================================================= */

  public static function get(string $uri): self {
    $uri = self::buildUri($uri);

    self::$routes[$uri] = [
      'action'      => null,
      'view'        => null,
      'layout'      => null,
      'middlewares' => [],
      'analytics'   => null,
      'context'     => self::$context,
    ];

    return new self(self::$routes[$uri]);
  }

  /* =========================================================
   * CHAINING (BUILDER)
   * ========================================================= */

  public function action(string $path): self {
    $this->route['action'] = $path;
    return $this;
  }

  public function view(string $path): self {
    $this->route['view'] = $path;
    return $this;
  }

  public function layout(string $path): self {
    $this->route['layout'] = $path;
    return $this;
  }

  public function middleware(string $name, $params = null): self {
    $this->route['middlewares'][] = [$name, $params];
    return $this;
  }

  public function analytic(string $title, ?string $uri = null): self {
    $this->route['analytics'] = [
      'title' => $title,
      'uri'   => $uri,
    ];
    return $this;
  }

  /* =========================================================
   * RESOLVER RUTA
   * ========================================================= */

  public static function resolve(string $uri): ?array {
    $uri = self::normalizeUri($uri);

    foreach (self::$routes as $routeUri => $route) {

      preg_match_all('#\{([^}]+)\}#', $routeUri, $paramNames);

      $pattern = preg_replace('#\{[^}]+\}#', '([^/]+)', $routeUri);
      $pattern = '#^' . $pattern . '$#';

      if (preg_match($pattern, $uri, $matches)) {

        array_shift($matches);

        foreach ($paramNames[1] as $index => $name) {
          $_GET[$name] = $matches[$index] ?? null;
        }

        return $route;
      }
    }

    return null;
  }

  /* =========================================================
   * HELPERS
   * ========================================================= */

  protected static function buildUri(string $uri): string {
    $uri  = trim($uri, '/');
    $full = trim(self::$prefix . '/' . $uri, '/');

    return $full === '' ? '/' : $full;
  }

  protected static function normalizeUri(string $uri): string {
    $uri = trim($uri, '/');
    return $uri === '' ? '/' : $uri;
  }
}
