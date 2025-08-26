<?php

$notifier = new Notifier();

// Agregar notificaciones
$notifier->add("Mensaje Bootstrap de éxito", "success", "bs");
$notifier->add("Alerta SweetAlert", "danger", "sa");
$notifier->add("Notificación Toast", "info", "toast");

// Verificar si hay algún error en Bootstrap
if ($notifier->has("bootstrap", "danger")) {
  echo "<p>Hay mensajes de error en Bootstrap</p>";
}

// Mostrar todas
$notifier->showBootstrap();
$notifier->showToasts();
$notifier->showSweetAlerts();
