<?php

// Conexión a la base de datos con PDO
function connect(): PDO {
  try {
    $pdo = new PDO(
      "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
      DB_USER,
      DB_PASS,
      [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Lanza excepciones en errores
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Resultados como arrays asociativos
        PDO::ATTR_EMULATE_PREPARES   => false, // Usar consultas preparadas reales
      ]
    );
    return $pdo;
  } catch (PDOException $e) {
    // Registrar error y evitar mostrar datos sensibles en producción
    error_log("Error de conexión: " . $e->getMessage());
    die("Error al conectar a la base de datos.");
  }
}

function updateOption(PDO $db, string $key, $value): void {
  $stmt = $db->prepare("UPDATE options SET option_value = :value WHERE option_key = :key");
  $stmt->bindValue(':value', is_array($value) ? json_encode($value) : $value, PDO::PARAM_STR);
  $stmt->bindValue(':key', $key, PDO::PARAM_STR);
  $stmt->execute();
}


function generarCadenaAleatoria($tipo = 'mixto', $mayusculas = false, $longitud = 8, $incluirEspeciales = false) {
  $numeros          = '0123456789';
  $letrasMinusculas = 'abcdefghijklmnopqrstuvwxyz';
  $letrasMayusculas = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $especiales       = '!@#$%^&*()-_=+[]{}|;:,.<>?';

  $caracteres = '';

  // Definir los caracteres a usar según el tipo
  if ($tipo == 'numeros') {
    $caracteres = $numeros;
  } elseif ($tipo == 'letras') {
    $caracteres = $letrasMinusculas;
  } else {
    $caracteres = $numeros . $letrasMinusculas;
  }

  // Convertir a mayúsculas si se requiere
  if ($mayusculas) {
    $caracteres = strtoupper($caracteres);
  }

  // Incluir caracteres especiales si se requiere
  if ($incluirEspeciales) {
    $caracteres .= $especiales;
  }

  // Generar la cadena aleatoria
  $cadena = '';
  for ($i = 0; $i < $longitud; $i++) {
    $cadena .= $caracteres[rand(0, strlen($caracteres) - 1)];
  }

  return $cadena;
}

function obtenerUrlBase() {
  $protocolo = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
  $host      = $_SERVER['HTTP_HOST'];
  $script    = $_SERVER['SCRIPT_NAME'];
  $path      = str_replace(basename($script), '', $script);

  return $protocolo . $host;
}