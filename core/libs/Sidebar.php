<?php

class Sidebar
{
  /* =========================================================
   * ESTADO INTERNO
   * ========================================================= */

  protected static array $items = [];
  protected static ?int $currentGroupIndex = null;
  protected static ?int $lastItemIndex = null;

  /* =========================================================
   * HEADER
   * ========================================================= */

  public static function header(string $text): void
  {
    self::$items[] = [
      'type' => 'header',
      'text' => $text,
    ];
  }

  /* =========================================================
   * ITEM
   * ========================================================= */

  public static function item(string $text, string $url): self
  {
    $item = [
      'type'       => 'item',
      'text'       => $text,
      'url'        => self::normalizeUrl($url),
      'icon'       => null,
      'permission' => null,
      'context'    => CTX_ADMIN,
    ];

    if (self::$currentGroupIndex !== null) {
      self::$items[self::$currentGroupIndex]['items'][] = $item;
      self::$lastItemIndex = count(self::$items[self::$currentGroupIndex]['items']) - 1;
    } else {
      self::$items[] = $item;
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

    self::$items[] = $group;
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

  public function icon(string $icon): self
  {
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

  public function can(string $permission, string $context = CTX_ADMIN): self
  {
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

  /* =========================================================
   * RENDER
   * ========================================================= */

  public static function render(): void
  {
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

  protected static function renderItem(array $item): void
  {
    if (!self::isVisible($item)) {
      return;
    }

    $active = self::isActive($item['url']);

    echo '<li class="sidebar-item' . ($active ? ' active' : '') . '">';
    echo '<a class="sidebar-link" href="' . $item['url'] . '">';

    if (!empty($item['icon'])) {
      echo '<i class="align-middle" data-feather="' . $item['icon'] . '"></i>';
    }

    echo '<span class="align-middle">' . $item['text'] . '</span>';
    echo '</a></li>';
  }

  protected static function renderGroup(array $group): void
  {
    if ($group['permission'] && !can($group['permission'], $group['context'])) {
      return;
    }

    $visibleItems = array_filter(
      $group['items'],
      fn ($item) => is_array($item) && self::isVisible($item)
    );

    if (empty($visibleItems)) {
      return;
    }

    $active = false;
    foreach ($visibleItems as $item) {
      if (self::isActive($item['url'])) {
        $active = true;
        break;
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
   * HELPERS
   * ========================================================= */

  protected static function normalizeUrl(string $url): string
  {
    return '/' . trim($url, '/');
  }

  protected static function isActive(string $url): bool
  {
    $current = '/' . trim($_GET['url'] ?? '', '/');
    return $current === $url || str_starts_with($current, $url . '/');
  }

  protected static function isVisible(array $item): bool
  {
    if (!empty($item['permission']) && !can($item['permission'], $item['context'])) {
      return false;
    }
    return true;
  }
}
