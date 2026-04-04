<?php

/**
 * Renderiza el breadcrumb a partir de un array de items.
 * 
 * @param array|null $custom_breadcrumbs Array de items: [['label' => 'Inicio', 'link' => '/'], ['label' => 'Perfil']]
 */
function render_breadcrumb(?array $custom_breadcrumbs = null): void {
  if ($custom_breadcrumbs === null) {
     return;
  }

  echo '<nav aria-label="breadcrumb">';
  echo '<ol class="breadcrumb">';

  $total = count($custom_breadcrumbs);
  foreach ($custom_breadcrumbs as $i => $item) {
    $isLast = ($i === $total - 1);
    $label  = $item['label'] ?? '';
    $link   = $item['link'] ?? null;

    if ($isLast || empty($link)) {
      echo '<li class="breadcrumb-item active" aria-current="page">' . $label . '</li>';
    } else {
      echo '<li class="breadcrumb-item"><a href="' . $link . '">' . $label . '</a></li>';
    }
  }

  echo '</ol>';
  echo '</nav>';
}
