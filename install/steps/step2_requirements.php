<?php

function checkPHPVersion($requiredVersion) {
  return version_compare(PHP_VERSION, $requiredVersion, '>=');
}

function checkExtensions($requiredExtensions) {
  $results = [];
  foreach ($requiredExtensions as $extension) {
    $results[$extension] = extension_loaded($extension);
  }
  return $results;
}

// Definir la versión de PHP requerida
$requiredPHPVersion = '8.0.0';

// Definir las extensiones requeridas
$requiredExtensions = [
  'pdo',
  'pdo_mysql',
  'mbstring',
]; // Añadir más según sea necesario

$phpVersionOk = checkPHPVersion($requiredPHPVersion);
$extensionResults = checkExtensions($requiredExtensions);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $allExtensionsOk = !in_array(false, $extensionResults, true);
  if ($phpVersionOk && $allExtensionsOk) {
    header('Location: index.php?step=3');
    exit;
  } else {
    $error = 'Por favor, asegúrate de que todas las extensiones requeridas estén habilitadas y que la versión de PHP sea la correcta.';
  }
}
?>

<?php include 'includes/header.php'; ?>

<h2>Verificación de Requisitos del Sistema</h2>
<form method="post">
  <h3>Versión de PHP</h3>
  <p>
    Versión actual: <?= PHP_VERSION; ?> - 
    <?php if ($phpVersionOk): ?>
      <span class="badge text-bg-success rounded-pill">
        <i class="fa fa-check"></i> Compatible
      </span>
    <?php else: ?>
      <span class="badge text-bg-danger rounded-pill">
        <i class="fa fa-x"></i> No compatible (Requiere <?= $requiredPHPVersion; ?> o superior)
      </span>
    <?php endif; ?>
  </p>

  <h3>Extensiones</h3>
  <ul class="list-group">
    <?php foreach ($requiredExtensions as $extension): ?>
      <li class="list-group-item">
        <?= $extension; ?>:
        <?php if ($extensionResults[$extension]): ?>
          <span class="badge text-bg-success rounded-pill">
            <i class="fa fa-check"></i>
            Habilitado
          </span>
        <?php else: ?>
          <span class="badge text-bg-danger rounded-pill">
            <i class="fa fa-x"></i>
            Deshabilitado
          </span>
        <?php endif; ?>
      </li>
    <?php endforeach; ?>
  </ul>

  <?php if (isset($error)): ?>
    <p class="alert alert-danger"><?php echo $error; ?></p>
  <?php endif; ?>

  <div class="d-grid">
    <button class="btn btn-primary">Siguiente</button>
  </div>
</form>

<?php include 'includes/footer.php'; ?>
