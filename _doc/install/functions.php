<?php
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