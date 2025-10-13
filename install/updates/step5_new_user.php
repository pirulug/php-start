<?php
/**
 * Step 5: Resetear todos los usuarios y crear un administrador por defecto
 */

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reset_users'])) {
  try {
    // 1. Vaciar la tabla users
    $connect->exec("TRUNCATE TABLE users");

    // 2. Datos del admin por defecto
    $adminUser   = "admin";
    $adminEmail  = "admin@example.com"; // cámbialo si quieres
    $adminPass   = $cipher->encrypt("admin123");
    $adminRole   = 1;
    $adminStatus = 1;

    // 3. Insertar nuevo admin
    $stmt = $connect->prepare("
            INSERT INTO users (user_name, user_email, user_password, role_id, user_status)
            VALUES (:user_name, :user_email, :user_password, :role_id, :user_status)
        ");

    $stmt->execute([
      ':user_name'     => $adminUser,
      ':user_email'    => $adminEmail,
      ':user_password' => $adminPass,
      ':role_id'     => $adminRole,
      ':user_status'   => $adminStatus,
    ]);

    echo "<p class='text-success'>✅ Todos los usuarios fueron eliminados y se creó un administrador por defecto (admin / admin123).</p>";
    echo "<a href='update.php?step=6' class='btn btn-primary'>Finalizar</a>";

  } catch (Exception $e) {
    echo "<p class='text-danger'>❌ Error al resetear usuarios: "
      . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . "</p>";
  }
} else {
  // Mostrar formulario de confirmación
  ?>
  <h2>⚠️ Resetear Usuarios</h2>
  <p>Este proceso eliminará <strong>todos los usuarios existentes</strong> en la base de datos y creará un nuevo usuario
    administrador con las siguientes credenciales:</p>
  <ul>
    <li><b>Usuario:</b> admin</li>
    <li><b>Password:</b> admin123</li>
  </ul>
  <p>El nuevo administrador tendrá siempre <code>ID = 1</code>.</p>
  <p class="text-danger"><strong>Advertencia:</strong> Esta acción es irreversible.</p>

  <form method="post">
    <input type="hidden" name="reset_users" value="1">
    <button type="submit" class="btn btn-danger">Sí, resetear usuarios</button>
    <a href="update.php" class="btn btn-secondary">Cancelar</a>
  </form>
  <?php
}
?>