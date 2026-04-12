<?php

$encrypted_id = $args['id'] ?? null;

if (!$encrypted_id) {
  $notifier->message("ID de usuario no especificado.")->bootstrap()->danger()->add();
  header("Location: " . admin_route("users"));
  exit;
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
  exit;
}

$page_title = "Gestionar API Keys: " . $managed_user->user_login;

// ========================================================
// ACCIÓN: GENERAR O REGENERAR API KEY
// ========================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && (isset($_POST['generate_key']) || isset($_POST['regenerate_key']))) {
  $new_key = bin2hex(random_bytes(16)); 

  try {
    $connect->beginTransaction();

    if (isset($_POST['regenerate_key'])) {
      $stmt_del = $connect->prepare("DELETE FROM user_api_keys WHERE user_id = :user_id");
      $stmt_del->bindParam(':user_id', $user_id_to_manage);
      $stmt_del->execute();
    } else {
      $stmt_check = $connect->prepare("SELECT COUNT(*) FROM user_api_keys WHERE user_id = :user_id");
      $stmt_check->bindParam(':user_id', $user_id_to_manage);
      $stmt_check->execute();
      
      if ($stmt_check->fetchColumn() >= 1) {
        throw new Exception("Este usuario ya tiene una API Key activa. Usa la opción de regenerar.");
      }
    }

    $stmt = $connect->prepare("INSERT INTO user_api_keys (user_id, api_key) VALUES (:user_id, :api_key)");
    $stmt->bindParam(':user_id', $user_id_to_manage);
    $stmt->bindParam(':api_key', $new_key);
    $stmt->execute();

    $connect->commit();
    $msg = isset($_POST['regenerate_key']) ? "API Key regenerada correctamente." : "API Key generada correctamente.";
    $notifier->message($msg)->bootstrap()->success()->add();
  } catch (Exception $e) {
    if ($connect->inTransaction()) $connect->rollBack();
    $notifier->message("Error: " . $e->getMessage())->bootstrap()->danger()->add();
  }
  
  header("Location: " . $_SERVER['REQUEST_URI']);
  exit;
}

// ========================================================
// ACCIÓN: ELIMINAR API KEY
// ========================================================
if (isset($_GET['delete_key'])) {
  $key_id = intval($_GET['delete_key']);
  
  try {
    $stmt = $connect->prepare("DELETE FROM user_api_keys WHERE api_key_id = :id AND user_id = :user_id");
    $stmt->bindParam(':id', $key_id);
    $stmt->bindParam(':user_id', $user_id_to_manage);
    $stmt->execute();

    $notifier->message("API Key eliminada correctamente.")->bootstrap()->success()->add();
  } catch (Exception $e) {
    $notifier->message("Error al eliminar API Key: " . $e->getMessage())->bootstrap()->danger()->add();
  }

  $url = strtok($_SERVER['REQUEST_URI'], '?'); // Limpiamos los parámetros de la URL
  header("Location: " . $url);
  exit;
}

// ========================================================
// CONSULTA DE LLAVES ACTIVAS
// ========================================================
$stmt = $connect->prepare("SELECT * FROM user_api_keys WHERE user_id = :user_id ORDER BY api_key_created DESC");
$stmt->bindParam(':user_id', $user_id_to_manage);
$stmt->execute();
$api_keys = $stmt->fetchAll(PDO::FETCH_OBJ);
