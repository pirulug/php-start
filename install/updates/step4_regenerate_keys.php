<h2>Regenerar claves de encriptación</h2>
<p>Esto generará nuevas claves de encriptación y actualizará el archivo <code>config.php</code>.</p>

<form method="post">
  <input type="submit" name="regenerate" value="Regenerar Claves" class="btn btn-warning">
</form>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $secret_key = generarCadenaAleatoria("mixto", true, 24, true);
  $secret_iv  = generarCadenaAleatoria("numeros", false, 24);

  $_SESSION['new_key'] = $secret_key;
  $_SESSION['new_iv']  = $secret_iv;

  // Reescribir config.php
  $tpl = file_get_contents("config.tpl");
  $configContent = str_replace(
    ['<DB_HOST>', '<DB_USER>', '<DB_PASSWORD>', '<DB_NAME>', '<SITE_NAME>', '<SITE_URL>', '<SECRET_KEY>', '<SECRET_IV>'],
    [DB_HOST, DB_USER, DB_PASS, DB_NAME, SITE_NAME, $_SESSION['new_site_url'] ?? SITE_URL, $secret_key, $secret_iv],
    $tpl
  );

  file_put_contents('../config.php', $configContent);

  header("Location: update.php?step=" . ($_SESSION['update_passwords'] ? 5 : 6));
  exit;
}
?>
