<?php
/*------------------------------------------------------------------------------
| SECURITY UPDATE SCRIPT - STEP 4
|-------------------------------------------------------------------------------
| - Genera nuevas claves de encriptación (ENCRYPT_KEY y ENCRYPT_IV).
| - Hace respaldo de config.php antes de modificarlo.
| - Reencripta todas las contraseñas en la tabla `users` usando transacción.
------------------------------------------------------------------------------*/

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['regenerate_and_reencrypt'])) {
  try {
    // ==========================
    // 1. Generar nuevas claves
    // ==========================
    $secret_key = generarCadenaAleatoria("mixto", true, 32, true); // 32 chars
    $secret_iv  = generarCadenaAleatoria("mixto", false, 16);      // 16 chars exact

    if (strlen($secret_key) !== 32 || strlen($secret_iv) !== 16) {
      throw new Exception("Error generando claves: longitud incorrecta.");
    }

    // Guardar en sesión temporal (por si se necesita en otro paso)
    $_SESSION['new_key'] = $secret_key;
    $_SESSION['new_iv']  = $secret_iv;

    // ==========================
    // 2. Reencriptar contraseñas con transacción
    // ==========================
    $connect->beginTransaction();

    $stmt  = $connect->query("SELECT user_id, user_password FROM users");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($users as $user) {
      // Desencriptar con claves viejas
      $decrypted = $cipher->decrypt($user['user_password']);
      if ($decrypted === false || $decrypted === null) {
        throw new Exception("No se pudo desencriptar la contraseña del usuario ID {$user['user_id']}");
      }

      // Crear instancia con las NUEVAS claves
      $newCipher = new Cipher(ENCRYPT_METHOD, $secret_key, $secret_iv);

      // Reencriptar con las nuevas claves
      $reencrypted = $newCipher->encrypt($decrypted);

      // Guardar en la BD
      $update = $connect->prepare("UPDATE users SET user_password = :pass WHERE user_id = :id");
      $update->bindParam(':pass', $reencrypted, PDO::PARAM_STR);
      $update->bindParam(':id', $user['user_id'], PDO::PARAM_INT);
      $update->execute();
    }

    // ==========================
    // 3. Actualizar config.php (solo si todo salió bien)
    // ==========================
    $configFile = '../config.php';
    $backupFile = "../config.backup." . date("Ymd_His") . ".php";

    if (!copy($configFile, $backupFile)) {
      throw new Exception("No se pudo crear respaldo de config.php");
    }

    $config = file_get_contents($configFile);
    $config = preg_replace(
      [
        "/const ENCRYPT_KEY = '(.*)';/",
        "/const ENCRYPT_IV\s+= '(.*)';/"
      ],
      [
        "const ENCRYPT_KEY = '{$secret_key}';",
        "const ENCRYPT_IV  = '{$secret_iv}';"
      ],
      $config
    );

    if (file_put_contents($configFile, $config) === false) {
      throw new Exception("No se pudo actualizar config.php");
    }

    // Confirmar cambios en DB
    $connect->commit();

    echo "<p class='text-success'>✅ Claves regeneradas y contraseñas reencriptadas correctamente.</p>";
    echo "<p>Se creó un respaldo de <code>config.php</code> en: <code>{$backupFile}</code></p>";
    echo "<a href='update.php?step=6' class='btn btn-primary'>Finalizar</a>";

  } catch (Exception $e) {
    $connect->rollBack();
    echo "<p class='text-danger'>⚠️ Error crítico: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . "</p>";
    echo "<p>El sistema NO modificó las claves ni config.php.</p>";
  }
}
?>

<!-- Formulario -->
<h2>Regenerar claves y reencriptar contraseñas</h2>
<p>Este proceso actualizará <code>config.php</code> con nuevas claves de encriptación y reencriptará todas las
  contraseñas de usuarios. Se creará un respaldo automático de <code>config.php</code>.</p>

<form method="post" onsubmit="return confirm('⚠️ Este proceso es irreversible. ¿Deseas continuar?');">
  <input type="submit" name="regenerate_and_reencrypt" value="Ejecutar Proceso Completo" class="btn btn-danger">
</form>