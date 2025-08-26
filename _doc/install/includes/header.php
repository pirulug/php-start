<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Asistente de Instalación</title>

  <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
</head>

<body>
  <div class="container">
    <h1 class="text-center my-5">
      <i class="fas fa-file-alt"></i>
      phpInstaller - Installation Wizard
    </h1>

    <div class="card">
      <div class="card-body">

        <div class="d-flex align-items-start">

          <ul class="nav nav-pills nav-fill flex-column me-3">
            <li class="nav-item">
              <?php if ($currentStep == 1 || $currentStep == 2 || $currentStep == 3 || $currentStep == 4 || $currentStep == 5): ?>
                <a href="?step=1" class="nav-link <?= $currentStep == 1 ? "active" : "" ?>">Bienbenido</a>
              <?php else: ?>
                <button class="nav-link" disabled="disabled">Bienbenido</button>
              <?php endif; ?>
            </li>
            <li class="nav-item">
              <?php if ($currentStep == 2 || $currentStep == 3 || $currentStep == 4 || $currentStep == 5): ?>
                <a href="?step=2" class="nav-link <?= $currentStep == 2 ? "active" : "" ?>">Requerimientos</a>
              <?php else: ?>
                <button class="nav-link" disabled="disabled">Requerimientos</button>
              <?php endif; ?>
            </li>
            <li class=" nav-item">
              <?php if ($currentStep == 3 || $currentStep == 4 || $currentStep == 5): ?>
                <a href="?step=3" class="nav-link <?= $currentStep == 3 ? "active" : "" ?>">Base de datos</a>
              <?php else: ?>
                <button class="nav-link" disabled="disabled">Base de datos</button>
              <?php endif; ?>
            </li>
            <li class="nav-item">
              <?php if ($currentStep == 4 || $currentStep == 5): ?>
                <a href="?step=4" class="nav-link <?= $currentStep == 4 ? "active" : "" ?>">Configuracion</a>
              <?php else: ?>
                <button class="nav-link" disabled="disabled">Configuracion</button>
              <?php endif; ?>
            </li>
            <li class="nav-item">
              <?php if ($currentStep == 5): ?>
                <a href="?step=5" class="nav-link <?= $currentStep == 5 ? "active" : "" ?>">Finalizar</a>
              <?php else: ?>
                <button class="nav-link" disabled="disabled">Finalizar</button>
              <?php endif; ?>
            </li>
          </ul>

          <div class="w-100">