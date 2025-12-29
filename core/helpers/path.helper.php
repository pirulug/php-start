<?php

/*
|--------------------------------------------------------------------------
| Error handler simple (sin stack trace)
|--------------------------------------------------------------------------
*/
function path_error(string $title, array $data): void {
  http_response_code(500);

  echo "<pre style='
    background:#111;
    color:#eee;
    padding:20px;
    font-family:monospace;
    border-left:5px solid #e74c3c;
  '>";

  echo strtoupper($title) . " ERROR\n\n";

  foreach ($data as $key => $value) {
    echo str_pad($key, 10, ' ', STR_PAD_RIGHT) . ": {$value}\n";
  }

  echo "</pre>";

  exit;
}

/*
|--------------------------------------------------------------------------
| Resolver paths por módulo (SIN EXCEPCIONES)
|--------------------------------------------------------------------------
*/
function resolve_module_path(
  string $basePath,
  string $subDir,
  string $name,
  string $ext,
  string $type
): string {

  if (strpos($name, '.') === false) {
    path_error($type, [
      'Motivo'   => 'Formato inválido',
      'Esperado' => 'modulo.archivo',
      'Recibido' => $name
    ]);
  }

  [$module, $file] = explode('.', $name, 2);

  if ($module === '' || $file === '') {
    path_error($type, [
      'Motivo' => 'Módulo o archivo vacío',
      'Valor'  => $name
    ]);
  }

  $path = $basePath
    . '/'
    . $module
    . '/'
    . $subDir
    . '/'
    . $file
    . $ext;

  if (!file_exists($path)) {
    path_error($type, [
      'Motivo'  => 'Archivo no encontrado',
      'Módulo'  => $module,
      'Archivo' => $file . $ext,
      'Ruta'    => $path
    ]);
  }

  return $path;
}

/* =========================================================
 * ADMIN
 * ========================================================= */

function admin_action(string $name, string $ext = '.action.php'): string {
  return resolve_module_path(
    BASE_DIR . '/app/admin/modules',
    'actions',
    $name,
    $ext,
    'admin action'
  );
}

function admin_view(string $name, string $ext = '.view.php'): string {
  return resolve_module_path(
    BASE_DIR . '/app/admin/modules',
    'views',
    $name,
    $ext,
    'admin view'
  );
}

function admin_layout(string $name = 'main', string $ext = '.layout.php'): string {
  $path = BASE_DIR . '/app/admin/layouts/' . $name . $ext;

  if (!file_exists($path)) {
    path_error('admin layout', [
      'Motivo' => 'Layout no encontrado',
      'Layout' => $name . $ext,
      'Ruta'   => $path
    ]);
  }

  return $path;
}

/* =========================================================
 * HOME
 * ========================================================= */

function home_action(string $name, string $ext = '.action.php'): string {
  return resolve_module_path(
    BASE_DIR . '/app/home/modules',
    'actions',
    $name,
    $ext,
    'home action'
  );
}

function home_view(string $name, string $ext = '.view.php'): string {
  return resolve_module_path(
    BASE_DIR . '/app/home/modules',
    'views',
    $name,
    $ext,
    'home view'
  );
}

function home_layout(string $name = 'main', string $ext = '.layout.php'): string {
  $path = BASE_DIR . '/app/home/layouts/' . $name . $ext;

  if (!file_exists($path)) {
    path_error('home layout', [
      'Motivo' => 'Layout no encontrado',
      'Layout' => $name . $ext,
      'Ruta'   => $path
    ]);
  }

  return $path;
}
