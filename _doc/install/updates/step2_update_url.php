<h2>Actualizar URL del Sitio</h2>
<form method="post">
  <label>URL nueva:
    <input type="text" name="site_url" value="<?= obtenerUrlBase() ?>" required>
  </label><br>
  <input type="submit" value="Actualizar URL" class="btn btn-success">
</form>

<?php

function obtenerUrlBase() {
  $protocolo = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
  $host      = $_SERVER['HTTP_HOST'];
  $script    = $_SERVER['SCRIPT_NAME'];
  $path      = str_replace(basename($script), '', $script);

  return $protocolo . $host;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $new_url = trim($_POST['site_url']);
  $pdo     = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
  $stmt    = $pdo->prepare("UPDATE options SET option_value = :url WHERE option_key = 'site_url'");
  $stmt->execute([':url' => $new_url]);

  $_SESSION['new_site_url'] = $new_url;

  header("Location: update.php");
  exit();
}
?>