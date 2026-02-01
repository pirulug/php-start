<?php

Sidebar::group('Analytics', 'pie-chart', function ($group) {

  $group->item('Resumen', admin_route('analytics/summary'))
    ->can('analytics.summary');

  $group->item('Visitantes', admin_route('analytics/visitors'))
    ->can('analytics.list');

  $group->item('Visitas', admin_route('analytics/views'))
    ->can('analytics.list');

  $group->item('Visitas en linea', admin_route('analytics/online'))
    ->can('analytics.list');

  $group->item('Top de visitantes', admin_route('analytics/top'))
    ->can('analytics.list');

  $group->item('Mapa de visitantes', admin_route('analytics/mapa'))
    ->can('analytics.list');

});