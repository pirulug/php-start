<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $db_host = $_POST['db_host'];
  $db_name = $_POST['db_name'];
  $db_user = $_POST['db_user'];
  $db_pass = $_POST['db_pass'];

  // Mensaje de error
  $error_message = '';

  try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Guarda la configuración en la sesión
    $_SESSION['db_host'] = $db_host;
    $_SESSION['db_name'] = $db_name;
    $_SESSION['db_user'] = $db_user;
    $_SESSION['db_pass'] = $db_pass;

    // Redirige al siguiente paso
    header('Location: index.php?step=4');
    exit;
  } catch (PDOException $e) {
    // Determina el tipo de error y proporciona un mensaje adecuado
    if ($e->getCode() === 1049) {
      $error_message = "El nombre de la base de datos no existe. Por favor, verifica que el nombre sea correcto.";
    } elseif ($e->getCode() === 1045) {
      $error_message = "Nombre de usuario o contraseña incorrectos. Por favor, verifica tus credenciales.";
    } elseif ($e->getCode() === 2002) {
      $error_message = "No se puede conectar al servidor de base de datos. Verifica el host y asegúrate de que el servidor esté en funcionamiento.";
    } else {
      $error_message = "No se puede conectar a la base de datos. Error: " . $e->getMessage();
    }
  }
}
?>

<?php include 'includes/header.php'; ?>

<h2>Configuración de la Base de Datos</h2>

<form method="post">
  <div class="mb-3">
    <label class="form-label">Host de la Base de Datos:</label>
    <input class="form-control" type="text" name="db_host" value="localhost" required>
  </div>
  <div class="mb-3">
    <label class="form-label">Nombre de la Base de Datos:</label>
    <input class="form-control" type="text" name="db_name" value="php-start" required>
  </div>
  <div class="mb-3">
    <label class="form-label">Usuario de la Base de Datos:</label>
    <input class="form-control" type="text" name="db_user" value="root" required>
  </div>
  <div class="mb-3">
    <label class="form-label">Contraseña de la Base de Datos:</label>
    <input class="form-control" type="password" name="db_pass" value="">
  </div>

  <button class="btn btn-primary">Siguiente</button>
</form>

<?php if (isset($error_message)): ?>
  <p class="alert alert-danger"><?= $error_message ?></p>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>