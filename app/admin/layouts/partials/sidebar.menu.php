<?php

$modules = require BASE_DIR . '/app/admin/modules.php';

foreach ($modules as $module => $enabled) {

  if (!$enabled) {
    continue;
  }

  $menu = BASE_DIR . "/app/admin/modules/{$module}/menu.php";

  if (is_file($menu)) {
    require_once $menu;
  }
}

// Header
// Sidebar::header('Plugin');

// Menu Simple
// Sidebar::item('Dashboard', admin_route('dashboard'))
//   ->icon('sliders')
//   ->can('dashboard.dashboard');

// Menu con submenus
// Sidebar::group('Analytics', 'pie-chart', function ($group) {

//   $group->item('Resumen', admin_route('analytics/summary'))
//     ->can('analytics.summary');

//   $group->item('Visitantes', admin_route('analytics/visitors'))
//     ->can('analytics.list');

// });