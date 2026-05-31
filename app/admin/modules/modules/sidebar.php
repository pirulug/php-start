<?php

Sidebar::item("Módulos", admin_route("modules"))
  ->icon("layers")
  ->can("modules.admin");
