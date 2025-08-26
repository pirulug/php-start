<h2>Actualizar íconos y logos</h2>
<form method="post">
  <label>Favicon: <input type="text" name="favicon" value="favicon.ico"></label><br>
  <label>Logo claro: <input type="text" name="white_logo" value="whitelogo.png"></label><br>
  <label>Logo oscuro: <input type="text" name="dark_logo" value="darklogo.png"></label><br>
  <label>Imagen OG: <input type="text" name="og_image" value="og_image.png"></label><br>
  <input type="submit" value="Actualizar logos" class="btn btn-success">
</form>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
  $fields = ['favicon', 'white_logo', 'dark_logo', 'og_image'];

  foreach ($fields as $field) {
    $value = trim($_POST[$field]);
    $stmt = $pdo->prepare("UPDATE options SET option_value = :value WHERE option_key = :key");
    $stmt->execute([':value' => $value, ':key' => $field]);
  }

  header("Location: update.php?step=" . ($_SESSION['update_keys'] ? 4 : ($_SESSION['update_passwords'] ? 5 : 6)));
  exit;
}
?>
