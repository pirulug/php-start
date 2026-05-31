<?php

Sidebar::item("Módulos", admin_route("modules"))
  ->icon("box")
  ->can("access.admin");
