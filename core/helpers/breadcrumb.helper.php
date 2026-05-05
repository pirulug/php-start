<?php

// --------------------------------------------------------------------------
// SECCIÓN: NAVEGACIÓN (BREADCRUMB)
// --------------------------------------------------------------------------

/**
 * Renderiza el componente de migas de pan (breadcrumb) a partir de un array de items.
 *
 * @param array|null $custom_breadcrumbs Lista de items [['label' => '...', 'link' => '...']].
 */
function render_breadcrumb($custom_breadcrumbs = null) {
  if ($custom_breadcrumbs === null) {
    return;
  }

  echo '<nav aria-label="breadcrumb">';
  echo '<ol class="breadcrumb mb-0">';

  $total = count($custom_breadcrumbs);
  foreach ($custom_breadcrumbs as $i => $item) {
    $isLast = ($i === $total - 1);
    $label  = $item['label'] ?? '';
    $link   = $item['link'] ?? null;

    if ($isLast || empty($link)) {
      echo '<li class="breadcrumb-item active" aria-current="page">' . $label . '</li>';
    } else {
      echo '<li class="breadcrumb-item"><a href="' . $link . '" class="text-decoration-none">' . $label . '</a></li>';
    }
  }

  echo '</ol>';
  echo '</nav>';
}