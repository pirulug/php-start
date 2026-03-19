<?php

class Sidebar {
  /* =========================================================
   * ESTADO INTERNO
   * ========================================================= */

  protected static array $items = [];
  protected static ?int $currentGroupIndex = null;
  protected static ?int $lastItemIndex = null;

  /* =========================================================
   * HEADER
   * ========================================================= */

  public static function header(string $text): void {
    self::$items[] = [
      'type' => 'header',
      'text' => $text,
    ];
  }

  /* =========================================================
   * ITEM
   * ========================================================= */

  public static function item(string $text, string $url): self {
    $item = [
      'type'            => 'item',
      'text'            => $text,
      'url'             => self::normalizeUrl($url),
      'icon'            => null,
      'permission'      => null,
      'context'         => CTX_ADMIN,
      'active_patterns' => [],
    ];

    if (self::$currentGroupIndex !== null) {
      self::$items[self::$currentGroupIndex]['items'][] = $item;
      self::$lastItemIndex                              = count(self::$items[self::$currentGroupIndex]['items']) - 1;
    } else {
      self::$items[]       = $item;
      self::$lastItemIndex = count(self::$items) - 1;
    }

    return new self();
  }

  /* =========================================================
   * GROUP
   * ========================================================= */

  public static function group(
    string $text,
    ?string $icon = null,
    ?callable $callback = null
  ): self {
    $group = [
      'type'       => 'group',
      'text'       => $text,
      'icon'       => $icon,
      'items'      => [],
      'permission' => null,
      'context'    => CTX_ADMIN,
    ];

    self::$items[]           = $group;
    self::$currentGroupIndex = count(self::$items) - 1;

    $instance = new self();

    if ($callback) {
      $callback($instance);
      self::$currentGroupIndex = null;
    }

    return $instance;
  }

  /* =========================================================
   * CHAINING
   * ========================================================= */

  public function icon(string $icon): self {
    if (self::$lastItemIndex === null) {
      return $this;
    }

    if (self::$currentGroupIndex !== null) {
      self::$items[self::$currentGroupIndex]['items'][self::$lastItemIndex]['icon'] = $icon;
    } else {
      self::$items[self::$lastItemIndex]['icon'] = $icon;
    }

    return $this;
  }

  public function can(string $permission, string $context = CTX_ADMIN): self {
    if (self::$lastItemIndex === null) {
      return $this;
    }

    if (self::$currentGroupIndex !== null) {
      self::$items[self::$currentGroupIndex]['items'][self::$lastItemIndex]['permission'] = $permission;
      self::$items[self::$currentGroupIndex]['items'][self::$lastItemIndex]['context']    = $context;
    } else {
      self::$items[self::$lastItemIndex]['permission'] = $permission;
      self::$items[self::$lastItemIndex]['context']    = $context;
    }

    return $this;
  }

  /**
   * (Opcional) Permite definir rutas extra de forma manual.
   */
  public function activeWhen(...$patterns): self {
    if (self::$lastItemIndex === null) {
      return $this;
    }

    $parsedPatterns = [];
    foreach ($patterns as $pattern) {
      if (is_array($pattern)) {
        $parsedPatterns = array_merge($parsedPatterns, $pattern);
      } else {
        $parsedPatterns[] = $pattern;
      }
    }

    if (self::$currentGroupIndex !== null) {
      self::$items[self::$currentGroupIndex]['items'][self::$lastItemIndex]['active_patterns'] = $parsedPatterns;
    } else {
      self::$items[self::$lastItemIndex]['active_patterns'] = $parsedPatterns;
    }

    return $this;
  }

  /* =========================================================
   * RENDER
   * ========================================================= */

  public static function render(): void {
    foreach (self::$items as $item) {
      if (!is_array($item) || !isset($item['type'])) {
        continue;
      }

      if ($item['type'] === 'header') {
        echo '<li class="sidebar-header">' . $item['text'] . '</li>';
        continue;
      }

      if ($item['type'] === 'item') {
        self::renderItem($item);
        continue;
      }

      if ($item['type'] === 'group') {
        self::renderGroup($item);
        continue;
      }
    }
  }

  /* =========================================================
   * INTERNAL RENDERERS
   * ========================================================= */

  protected static function renderItem(array $item): void {
    if (!self::isVisible($item)) {
      return;
    }

    // Un item individual solo se marca como activo si hay coincidencia exacta o manual
    $active = self::isActive($item['url'], $item['active_patterns'] ?? []);

    echo '<li class="sidebar-item' . ($active ? ' active' : '') . '">';
    echo '<a class="sidebar-link" href="' . $item['url'] . '">';

    if (!empty($item['icon'])) {
      echo '<i class="align-middle" data-feather="' . $item['icon'] . '"></i>';
    }

    echo '<span class="align-middle">' . $item['text'] . '</span>';
    echo '</a></li>';
  }

  protected static function renderGroup(array $group): void {
    if ($group['permission'] && !can($group['permission'], $group['context'])) {
      return;
    }

    $visibleItems = array_filter(
      $group['items'],
      fn($item) => is_array($item) && self::isVisible($item)
    );

    if (empty($visibleItems)) {
      return;
    }

    $active  = false;
    $current = '/' . trim($_GET['url'] ?? '', '/');

    foreach ($visibleItems as $item) {
      // 1. Si algún ítem es la ruta exacta
      if (self::isActive($item['url'], $item['active_patterns'] ?? [])) {
        $active = true;
        break;
      }

      // 2. Si estamos en una vista interna del módulo (Ej: /panel/cash/close/5), 
      // iluminamos el grupo para mantener el menú abierto
      if (self::isModuleAutoMatch($current, $item['url'])) {
        $active = true;
      }
    }

    $id = 'group_' . md5($group['text']);

    echo '<li class="sidebar-item' . ($active ? ' active' : '') . '">';
    echo '<a class="sidebar-link' . ($active ? '' : ' collapsed') . '"
            data-bs-toggle="collapse"
            data-bs-target="#' . $id . '">';

    if (!empty($group['icon'])) {
      echo '<i class="align-middle" data-feather="' . $group['icon'] . '"></i>';
    }

    echo '<span class="align-middle">' . $group['text'] . '</span>';
    echo '</a>';

    echo '<ul id="' . $id . '" class="sidebar-dropdown list-unstyled collapse' . ($active ? ' show' : '') . '" data-bs-parent="#sidebar">';

    foreach ($visibleItems as $item) {
      self::renderItem($item);
    }

    echo '</ul></li>';
  }

  /* =========================================================
   * HELPERS Y NÚCLEO DE INFERENCIA AUTOMÁTICA
   * ========================================================= */

  protected static function normalizeUrl(string $url): string {
    return '/' . trim($url, '/');
  }

  /**
   * Determina si un menú debe estar activo basado en coincidencias exactas o prefijos configurados
   */
  protected static function isActive(string $url, array $patterns = []): bool {
    $current = '/' . trim($_GET['url'] ?? '', '/');

    if ($current === $url || str_starts_with($current, $url . '/')) {
      return true;
    }

    foreach ($patterns as $pattern) {
      $np = self::normalizeUrl($pattern);
      if ($current === $np || str_starts_with($current, $np . '/')) {
        return true;
      }
    }

    return false;
  }

  /**
   * Compara dinámicamente si la URL actual pertenece al mismo módulo general de la URL del menú
   */
  protected static function isModuleAutoMatch(string $current, string $url): bool {
    $currentParts = array_values(array_filter(explode('/', $current)));
    $urlParts     = array_values(array_filter(explode('/', $url)));

    if (empty($currentParts) || empty($urlParts)) {
      return false;
    }

    // Verificar que compartan la misma ruta base (ej. 'panel', 'admin')
    if ($currentParts[0] !== $urlParts[0]) {
      return false;
    }

    // El módulo está en la posición 1 (ej: panel/cashs -> cashs, panel/cash/close -> cash)
    $currentModule = $currentParts[1] ?? '';
    $urlModule     = $urlParts[1] ?? '';

    if ($currentModule === '' || $urlModule === '')
      return false;

    // Remover sufijos plurales comunes ('s', 'es', 'y', 'i') para igualar la raíz semántica
    $currentRoot = rtrim($currentModule, 'seyi');
    $urlRoot     = rtrim($urlModule, 'seyi');

    return $currentRoot === $urlRoot;
  }

  protected static function isVisible(array $item): bool {
    if (!empty($item['permission']) && !can($item['permission'], $item['context'])) {
      return false;
    }
    return true;
  }
}