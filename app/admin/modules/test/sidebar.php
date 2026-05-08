<?php

$test = Sidebar::group('Test', 'folder');

$test->item('Nuevo Test', admin_route('test/new'))
  ->can('test.new');

$test->item('Lista de Test', admin_route('test'))
  ->can('test.list');
