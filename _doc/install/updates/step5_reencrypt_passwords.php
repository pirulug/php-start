<h2>Reencriptar contraseñas de usuarios</h2>
<p>Esto tomará las contraseñas actuales y las volverá a cifrar con las nuevas claves.</p>

<form method="post">
  <input type="submit" name="reencrypt" value="Reencriptar todas" class="btn btn-danger">
</form>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $key = $_SESSION['new_key'];
  $iv  = $_SESSION['new_iv'];

  $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
  $stmt = $pdo->query("SELECT user_id, user_password FROM users");
  $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

  $encOld = new Encryption();
  $encNew = new Encryption();

  foreach ($users as $user) {
    try {
      $decrypted = $encOld->decrypt($user['user_password'], SECRET_KEY, SECRET_IV);
      $reencrypted = $encNew->encrypt($decrypted, $key, $iv);

      $update = $pdo->prepare("UPDATE users SET user_password = :pass WHERE user_id = :id");
      $update->execute([
        ':pass' => $reencrypted,
        ':id' => $user['user_id']
      ]);
    } catch (Exception $e) {
      echo "<p>Error con usuario ID {$user['user_id']}: {$e->getMessage()}</p>";
    }
  }

  echo "<p class='text-success'>Contraseñas reencriptadas correctamente.</p>";
  echo "<a href='update.php?step=6' class='btn btn-primary'>Finalizar</a>";
}
?>
