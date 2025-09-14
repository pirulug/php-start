<h2>Actualizar URL del Sitio</h2>

<form method="post">
  <label>URL nueva:
    <input type="url" name="site_url" value="<?= htmlspecialchars(obtenerUrlBase(), ENT_QUOTES, 'UTF-8') ?>" required>
  </label><br>
  <input type="submit" value="Actualizar URL" class="btn btn-success">
</form>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  try {
    // Validar URL
    $new_url = trim($_POST['site_url']);
    if (!filter_var($new_url, FILTER_VALIDATE_URL)) {
      throw new Exception("La URL ingresada no es válida.");
    }

    // Usar la conexión existente
    global $connect;
    $stmt = $connect->prepare("UPDATE options SET option_value = :url WHERE option_key = 'site_url'");
    $stmt->bindParam(':url', $new_url, PDO::PARAM_STR);
    $stmt->execute();

    // Guardar en sesión
    $_SESSION['new_site_url'] = $new_url;

    // Mensaje de confirmación
    echo "<p style='color:green;'>✅ La URL del sitio se actualizó correctamente.</p>";

  } catch (Exception $e) {
    echo "<p style='color:red;'>⚠️ Error: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . "</p>";
  }
}
?>