<?php

$encrypted_id = $args['id'] ?? null;

if (!$encrypted_id) {
  $notifier->message("ID de usuario no especificado.")->bootstrap()->danger()->add();
  header("Location: " . admin_route("users"));
  exit();
}

$user_id_to_manage = $cipher->decrypt($encrypted_id); // Desciframos el ID del usuario

// Obtenemos los datos del usuario para el título y la validación
$stmt_user = $connect->prepare("SELECT user_login FROM users WHERE user_id = :id");
$stmt_user->bindParam(':id', $user_id_to_manage);
$stmt_user->execute();
$managed_user = $stmt_user->fetch(PDO::FETCH_OBJ);

if (!$managed_user) {
  $notifier->message("Usuario no encontrado.")->bootstrap()->danger()->add();
  header("Location: " . admin_route("users"));
  exit();
}

$page_title = "Gestionar API Keys: " . $managed_user->user_login;

// ========================================================
// ACCIÓN: GENERAR O REGENERAR API KEY
// ========================================================
$generate_key   = isset($_POST['generate_key']) ? clear_input($_POST['generate_key']) : null;
$regenerate_key = isset($_POST['regenerate_key']) ? clear_input($_POST['regenerate_key']) : null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($generate_key || $regenerate_key)) {
  $now          = date('Y-m-d H:i:s');
  $new_key_data = [
    'key'        => bin2hex(random_bytes(16)),
    'created_at' => $now,
    'updated_at' => $now
  ];
  $json_key     = json_encode($new_key_data);

  try {
    $connect->beginTransaction();

    // Actualizamos o insertamos la llave (UPSERT)
    $stmt = $connect->prepare("
    INSERT INTO usermeta (user_id, usermeta_key, usermeta_value) 
    VALUES (:user_id, 'api_key', :api_val)
    ON DUPLICATE KEY UPDATE usermeta_value = VALUES(usermeta_value)
  ");
    $stmt->bindParam(':user_id', $user_id_to_manage);
    $stmt->bindParam(':api_val', $json_key);
    $stmt->execute();

    $connect->commit();
    $msg = isset($_POST['regenerate_key']) ? "API Key regenerada correctamente." : "API Key generada correctamente.";
    $notifier->message($msg)->bootstrap()->success()->add();
  } catch (Exception $e) {
    if ($connect->inTransaction())
      $connect->rollBack();
    $notifier->message("Error: " . $e->getMessage())->bootstrap()->danger()->add();
  }

  header("Location: " . $_SERVER['REQUEST_URI']);
  exit();
}

// ========================================================
// ACCIÓN: ELIMINAR API KEY (Ahora limpia el valor en lugar de borrar la fila)
// ========================================================
if (isset($_GET['delete_key'])) {
  $meta_id = intval($_GET['delete_key']);

  try {
    $stmt = $connect->prepare("
    UPDATE usermeta 
    SET usermeta_value = '{}' 
    WHERE usermeta_id = :id AND user_id = :user_id AND usermeta_key = 'api_key'
  ");
    $stmt->bindParam(':id', $meta_id);
    $stmt->bindParam(':user_id', $user_id_to_manage);
    $stmt->execute();

    $notifier->message("API Key eliminada correctamente.")->bootstrap()->success()->add();
  } catch (Exception $e) {
    $notifier->message("Error al eliminar API Key: " . $e->getMessage())->bootstrap()->danger()->add();
  }

  $url = strtok($_SERVER['REQUEST_URI'], '?'); // Limpiamos los parámetros de la URL
  header("Location: " . $url);
  exit();
}

// ========================================================
// CONSULTA DE LLAVES ACTIVAS
// ========================================================
$stmt = $connect->prepare("
  SELECT usermeta_id as api_key_id, usermeta_value
  FROM usermeta 
  WHERE user_id = :user_id AND usermeta_key = 'api_key' 
");
$stmt->bindParam(':user_id', $user_id_to_manage);
$stmt->execute();
$raw_keys = $stmt->fetchAll(PDO::FETCH_OBJ);

// Hydrate objects for the view
$api_keys = [];
// Hydrate objects for the view
$api_keys = [];
foreach ($raw_keys as $row) {
  $val = $row->usermeta_value;

  // Si el valor está vacío o es un JSON vacío, lo ignoramos para la vista
  if (empty($val) || $val === '{}' || $val === '[]')
    continue;

  $data = json_decode($val, true);

  if (json_last_error() === JSON_ERROR_NONE && is_array($data)) {
    // Ignorar si el JSON existe pero la llave está vacía
    if (empty($data['key']))
      continue;

    $api_keys[] = (object) [
      'api_key_id'      => $row->api_key_id,
      'api_key'         => $data['key'],
      'api_key_created' => $data['created_at'] ?? 'N/A'
    ];
  } else {
    // Formato antiguo (Plain Text)
    $api_keys[] = (object) [
      'api_key_id'      => $row->api_key_id,
      'api_key'         => $val,
      'api_key_created' => 'Legacy'
    ];
  }
}