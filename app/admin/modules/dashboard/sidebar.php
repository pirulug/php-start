<?php

Sidebar::item('Dashboard', admin_route('dashboard'))
  ->icon('sliders')
  ->can('dashboard.dashboard');