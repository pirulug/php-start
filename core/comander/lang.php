<?php

/**
 * Generador de Idiomas (I18N) para Módulos
 * 
 * Escanea el código fuente de un módulo en busca de funciones __() y _e()
 * y genera un archivo es.php limpio con todas las llaves encontradas.
 * 
 * Uso: php ps lang {module} {context}
 */

// =============================================================================
// SECCIÓN: CONFIGURACIÓN Y AYUDA
// =============================================================================

// Parseo avanzado de argumentos
$options = [
  'module'  => null,
  'context' => 'admin',
  'copy'    => null,
  'help'    => false,
];

for ($i = 1; $i < count($argv); $i++) {
  $arg = $argv[$i];
  
  // Flags largos con asignación directa (ej: --module=dashboard)
  if (strpos($arg, '--module=')  === 0) $options['module']  = substr($arg, 9);
  if (strpos($arg, '--context=') === 0) $options['context'] = substr($arg, 10);
  if (strpos($arg, '--copy=')    === 0) $options['copy']    = substr($arg, 7);
  
  // Flags cortos o largos con espacio (ej: -m dashboard)
  if ($arg === '--module'  || $arg === '-m')  $options['module']  = $argv[++$i] ?? null;
  if ($arg === '--context' || $arg === '-c')  $options['context'] = $argv[++$i] ?? 'admin';
  if ($arg === '--copy'    || $arg === '-cp') $options['copy']    = $argv[++$i] ?? null;
  
  if ($arg === '--help' || $arg === '-h') $options['help'] = true;
}

// Soporte para argumentos posicionales (retrocompatibilidad)
if (!$options['module'] && isset($argv[2]) && strpos($argv[2], '-') !== 0) {
  $options['module'] = $argv[2];
  if (isset($argv[3]) && strpos($argv[3], '-') !== 0) {
    $options['context'] = $argv[3];
  }
}

if ($options['help']) {
  echo "\nGenerador de Idiomas (I18N)\n";
  echo "--------------------------------------------------------\n";
  echo "Uso: php ps lang [modulo] [contexto] [opciones]\n";
  echo "Uso Pro: php ps lang -m {modulo} -c {contexto} -cp {copia}\n\n";
  echo "Opciones:\n";
  echo "  -m,  --module   Nombre del módulo.\n";
  echo "  -c,  --context  Contexto (admin|front|api). Default: admin.\n";
  echo "  -cp, --copy     Copia es.php a otro idioma (ej: en).\n";
  echo "  -h,  --help     Muestra esta ayuda.\n";
  echo "--------------------------------------------------------\n\n";
  exit();
}

// =============================================================================
// SECCIÓN: LÓGICA DE EJECUCIÓN
// =============================================================================

$module  = $options['module'];
$context = $options['context'];
$copyTo  = $options['copy'];

if (!$module) {
  echo "\n[ERROR] Debes especificar el nombre del módulo.\n";
  echo "Uso: php ps lang -m {modulo}\n\n";
  exit();
}

$moduleDir = BASE_DIR . "/app/{$context}/modules/{$module}";

if (!is_dir($moduleDir)) {
  echo "\n[ERROR] El módulo '{$module}' no existe en el contexto '{$context}'.\n";
  echo "RUTA: {$moduleDir}\n\n";
  exit();
}

echo "\n[SOLICITADO] Escaneando módulo: {$module} ({$context})\n";
echo "--------------------------------------------------------\n";

$found_keys = [];

/**
 * Escanea archivos recursivamente buscando __() y _e()
 */
function scan_translations($dir, &$found_keys) {
  $files = scandir($dir);
  foreach ($files as $file) {
    if ($file === '.' || $file === '..') continue;
    
    $path = $dir . DIRECTORY_SEPARATOR . $file;
    
    if (is_dir($path)) {
      // Evitar escanear la propia carpeta de lenguajes o scripts JS
      if ($file === 'languages' || $file === 'scripts') continue;
      scan_translations($path, $found_keys);
    } else {
      if (pathinfo($path, PATHINFO_EXTENSION) === 'php') {
        $content = file_get_contents($path);
        
        // Regex para capturar el primer argumento de __() y _e()
        // Busca: __('texto') o _e("texto")
        preg_match_all('/(?:__| _e)\s*\(\s*([\'"])(.*?)\1/s', $content, $matches);
        
        if (!empty($matches[2])) {
          foreach ($matches[2] as $key) {
            // Limpiar escapes de comillas si existieran en el código original
            $key = stripslashes($key);
            $found_keys[] = $key;
          }
        }
      }
    }
  }
}

// Iniciar escaneo
scan_translations($moduleDir, $found_keys);

// Eliminar duplicados preservando el orden de primera aparición
$found_keys = array_unique($found_keys);

if (empty($found_keys)) {
  echo "\e[33mNo se encontraron cadenas de traducción en el módulo.\e[0m\n\n";
  exit(0);
}

// Asegurar existencia del directorio languages
$langDir = $moduleDir . "/languages";
if (!is_dir($langDir)) {
  mkdir($langDir, 0777, true);
}

$esFile = $langDir . "/es.php";

// Generar el contenido del archivo
$buffer = "<?php\n\n";
$buffer .= "/**\n";
$buffer .= " * Archivo de traducción generado automáticamente por PS Lang\n";
$buffer .= " * Módulo: {$module}\n";
$buffer .= " * Contexto: {$context}\n";
$buffer .= " */\n\n";
$buffer .= "return [\n";

foreach ($found_keys as $key) {
  // Escapar comillas simples para el archivo PHP
  $safeKey = addslashes($key);
  $buffer .= "  '{$safeKey}' => '{$safeKey}',\n";
}

$buffer .= "];\n";

// Guardar archivo (Sobreescritura limpia según solicitud)
file_put_contents($esFile, $buffer);

echo "--------------------------------------------------------\n";
echo "[OK] Proceso finalizado con éxito.\n";
echo "ARCHIVO: " . basename($esFile) . "\n";

// Si se solicitó copia a otro idioma
if ($copyTo) {
  $copyFile = $langDir . "/{$copyTo}.php";
  
  // Si el archivo ya existe, podríamos querer preservar valores. 
  // Pero como el usuario pidió "limpie", lo generaremos de nuevo.
  $copyBuffer = str_replace("Módulo: {$module}", "Módulo: {$module} (Copy)", $buffer);
  file_put_contents($copyFile, $copyBuffer);
  echo "COPIA: " . basename($copyFile) . "\n";
}

// Limpiar cache de idiomas para que los cambios se vean de inmediato
$cachePattern = BASE_DIR . "/storage/caches/lang_{$context}_*.cache.php";
foreach (glob($cachePattern) as $cacheFile) {
  @unlink($cacheFile);
}

echo "CADENAS: " . count($found_keys) . "\n\n";
