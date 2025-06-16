<?php

function tiempoDesdeCambio($fechaCambio) {
  // Crear un objeto DateTime con la fecha del cambio
  $cambio = new DateTime($fechaCambio);

  // Crear un objeto DateTime con la fecha y hora actual
  $actual = new DateTime();

  // Calcular la diferencia entre las dos fechas
  $diferencia = $actual->diff($cambio);

  // Obtener la diferencia en segundos
  $diferenciaSegundos = ($actual->getTimestamp() - $cambio->getTimestamp());

  // Determinar la unidad de tiempo más significativa
  if ($diferenciaSegundos < 60) {
    return 'Hace ' . $diferenciaSegundos . ' segundos';
  } elseif ($diferenciaSegundos < 3600) {
    return 'Hace ' . floor($diferenciaSegundos / 60) . ' minutos';
  } elseif ($diferenciaSegundos < 86400) {
    return 'Hace ' . floor($diferenciaSegundos / 3600) . ' horas';
  } elseif ($diferenciaSegundos < 604800) { // 7 días
    return 'Hace ' . floor($diferenciaSegundos / 86400) . ' días';
  } elseif ($diferencia->y > 0) {
    return 'Hace ' . $diferencia->y . ' años';
  } elseif ($diferencia->m > 0) {
    return 'Hace ' . $diferencia->m . ' meses';
  } elseif ($diferencia->d >= 7) {
    return 'Hace ' . floor($diferencia->d / 7) . ' semanas';
  } else {
    return 'Hace ' . $diferencia->d . ' días';
  }
}