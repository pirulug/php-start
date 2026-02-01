<?php

Sidebar::header('Test');

Sidebar::item('Test', admin_route('test'))
  ->icon('sliders')
  ->can('test.test');