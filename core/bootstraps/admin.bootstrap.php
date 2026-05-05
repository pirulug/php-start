<?php

// Carga de librerías exclusivas de administración
require_once BASE_DIR . "/core/libraries/admin/Sidebar.php";

// Carga los dominios (ejecuta los archivos .domain.php para registrar rutas)
load_domains(CTX_ADMIN);

// Carga los lenguajes de los módulos
load_module_languages(CTX_ADMIN);
