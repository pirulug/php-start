<?php

/**
 * Sidebar
 *
 * Clase encargada de la gestión y generación dinámica del menú lateral (Sidebar)
 * del panel administrativo. Soporta grupos colapsables, ítems individuales,
 * encabezados y gestión de permisos/contextos.
 *
 * @author Pirulug
 * @link   https://github.com/pirulug
 */
class Sidebar {
  // --------------------------------------------------------------------------
  // PROPIEDADES DE ESTADO (ALMACENAMIENTO)
  // --------------------------------------------------------------------------

  protected static array $items = [];
  protected static ?int $currentGroupIndex = null;
  protected static ?int $lastItemIndex = null;

  // --------------------------------------------------------------------------
  // SECCIÓN: COMPONENTES DEL MENÚ
  // --------------------------------------------------------------------------

  /**
   * Añade un encabezado de sección al menú.
   *
   * @param string $text Texto del encabezado.
   */
  public static function header(string $text): void {
    self::$currentGroupIndex = null;
    self::$items[]           = [
      'type' => 'header',
      'text' => $text,
    ];
  }

  /**
   * Define un ítem individual en el menú.
   * Si se llama dentro de un grupo, se añade como sub-ítem.
   *
   * @param string $text Texto a mostrar.
   * @param string $url  Ruta de destino.
   * @return self Instancia del constructor para encadenamiento.
   */
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

  /**
   * Define un grupo colapsable de ítems.
   *
   * @param string $text Texto del grupo.
   * @param string|null $icon Icono de FontAwesome 6 (ej: fa-solid fa-box).
   * @param callable|null $callback Función para definir ítems dentro del grupo.
   * @return self Instancia.
   */
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

  /**
   * Restablece el contexto de grupo actual.
   */
  public static function resetGroup(): void {
    self::$currentGroupIndex = null;
  }

  // --------------------------------------------------------------------------
  // SECCIÓN: CONFIGURACIÓN FLUIDA (CHAINING)
  // --------------------------------------------------------------------------

  /**
   * Asigna un icono al último ítem o grupo definido.
   *
   * @param string $icon Clase de FontAwesome 6 (ej: fa-solid fa-user).
   * @return self Instancia.
   */
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

  /**
   * Define el permiso requerido para visualizar el ítem/grupo.
   *
   * @param string $permission Llave del permiso.
   * @param string $context Contexto del permiso (default: admin).
   * @return self Instancia.
   */
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
   * Define patrones de URL adicionales que activarán el estado visual del ítem.
   *
   * @param mixed ...$patterns Listado de patrones de URL.
   * @return self Instancia.
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

  // --------------------------------------------------------------------------
  // SECCIÓN: RENDERIZADO
  // --------------------------------------------------------------------------

  /**
   * Renderiza el listado completo del menú en el sidebar.
   */
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

  /**
   * Renderiza un ítem individual.
   *
   * @param array $item Datos del ítem.
   */
  protected static function renderItem(array $item): void {
    if (!self::isVisible($item)) {
      return;
    }

    $active = self::isActive($item['url'], $item['active_patterns'] ?? []);

    echo '<li class="sidebar-item' . ($active ? ' active' : '') . '">';
    echo '<a class="sidebar-link" href="' . $item['url'] . '">';

    if (!empty($item['icon'])) {
      // Si no contiene fa-, lo tratamos como feather (retrocompatibilidad)
      $iconClass = (strpos($item['icon'], 'fa-') !== false) ? $item['icon'] : '';
      $feather   = (strpos($item['icon'], 'fa-') === false) ? $item['icon'] : '';

      if ($iconClass) {
        echo '<i class="' . $iconClass . ' align-middle me-2"></i>';
      } elseif ($feather) {
        echo '<i class="align-middle me-2" data-feather="' . $feather . '"></i>';
      }
    }

    echo '<span class="align-middle">' . $item['text'] . '</span>';
    echo '</a></li>';
  }

  /**
   * Renderiza un grupo colapsable.
   *
   * @param array $group Datos del grupo.
   */
  protected static function renderGroup(array $group): void {
    if ($group['permission'] && !can_user_permission($group['permission'], $group['context'])) {
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
      if (self::isActive($item['url'], $item['active_patterns'] ?? [])) {
        $active = true;
        break;
      }

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
      $iconClass = (strpos($group['icon'], 'fa-') !== false) ? $group['icon'] : '';
      $feather   = (strpos($group['icon'], 'fa-') === false) ? $group['icon'] : '';

      if ($iconClass) {
        echo '<i class="' . $iconClass . ' align-middle me-2"></i>';
      } elseif ($feather) {
        echo '<i class="align-middle me-2" data-feather="' . $feather . '"></i>';
      }
    }

    echo '<span class="align-middle">' . $group['text'] . '</span>';
    echo '</a>';

    echo '<ul id="' . $id . '" class="sidebar-dropdown list-unstyled collapse' . ($active ? ' show' : '') . '" data-bs-parent="#sidebar">';

    foreach ($visibleItems as $item) {
      self::renderItem($item);
    }

    echo '</ul></li>';
  }

  // --------------------------------------------------------------------------
  // SECCIÓN: HELPERS DE UTILIDAD
  // --------------------------------------------------------------------------

  /**
   * Normaliza la URL para comparaciones internas.
   *
   * @param string $url URL cruda.
   * @return string URL normalizada.
   */
  protected static function normalizeUrl(string $url): string {
    return '/' . trim($url, '/');
  }

  /**
   * Comprueba si una ruta es la activa según la URI actual o patrones.
   *
   * @param string $url URI del ítem.
   * @param array $patterns Patrones adicionales.
   * @return bool True si está activa.
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
   * Lógica de emparejamiento automático por nombre de módulo.
   *
   * @param string $current URI actual.
   * @param string $url URI del ítem.
   * @return bool
   */
  protected static function isModuleAutoMatch(string $current, string $url): bool {
    $currentParts = array_values(array_filter(explode('/', $current)));
    $urlParts     = array_values(array_filter(explode('/', $url)));

    if (empty($currentParts) || empty($urlParts)) {
      return false;
    }

    if ($currentParts[0] !== $urlParts[0]) {
      return false;
    }

    $currentModule = $currentParts[1] ?? '';
    $urlModule     = $urlParts[1] ?? '';

    if ($currentModule === '' || $urlModule === '') {
      return false;
    }

    $currentRoot = rtrim($currentModule, 'seyi');
    $urlRoot     = rtrim($urlModule, 'seyi');

    return $currentRoot === $urlRoot;
  }

  /**
   * Comprueba la visibilidad de un ítem según los permisos del usuario.
   *
   * @param array $item Datos del ítem.
   * @return bool True si es visible.
   */
  protected static function isVisible(array $item): bool {
    if (!empty($item['permission']) && !can_user_permission($item['permission'], $item['context'])) {
      return false;
    }
    return true;
  }
}