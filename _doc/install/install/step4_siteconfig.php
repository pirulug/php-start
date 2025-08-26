<?php
if (isset($_SERVER['HTTPS'])) {
  $htp = 'https';
} else {
  $htp = 'http';
}

$site_url = $htp . '://' . $_SERVER['SERVER_NAME'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $_SESSION['site_name']   = $_POST['site_name'];
  $_SESSION['admin_email'] = $_POST['admin_email'];
  $_SESSION['admin_user']  = $_POST['admin_user'];
  $_SESSION['admin_pass']  = $_POST['admin_pass'];
  $_SESSION['site_name']   = $_POST['site_name'];
  $_SESSION['site_url']    = $_POST['site_url'];
  header('Location: index.php?step=5');
  exit;
}
?>

<?php include 'includes/header.php'; ?>

<h2>Configuración del Sitio</h2>
<form method="post">
  <div class="mb-3">
    <label class="form-label">Nombre del Sitio</label>
    <input class="form-control" type="text" name="site_name" value="Php Start" required>
  </div>
  <div class="mb-3">
    <label class="form-label">Url del sitio</label>
    <input class="form-control" type="url" name="site_url" value="<?= $site_url ?>" required>
  </div>
  <div class="mb-3">
    <label class="form-label">Email del Administrador</label>
    <input class="form-control" type="email" name="admin_email" value="admin@admin.com" required>
  </div>
  <div class="mb-3">
    <label class="form-label">Username del Administrador</label>
    <input class="form-control" type="text" name="admin_user" value="admin" required>
  </div>
  <div class="mb-3">
    <label class="form-label">Password del Administrador</label>
    <input class="form-control" type="text" name="admin_pass" value="admin123" required>
  </div>
  <button class="btn btn-primary">Finalizar</button>
</form>

<?php include 'includes/footer.php'; ?>