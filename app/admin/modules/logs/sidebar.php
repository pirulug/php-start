<?php

Sidebar::item('Logs', admin_route('logs'))
  ->icon('terminal')
  ->can('logs.list');