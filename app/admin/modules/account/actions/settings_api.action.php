<?php

$user_id_to_manage = $_SESSION['user_id'];
$page_title = "Mis API Keys";

// ========================================================
// ACCIÓN: GENERAR O REGENERAR API KEY
// ========================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && (isset($_POST['generate_key']) || isset($_POST['regenerate_key']))) {
  $new_key = bin2hex(random_bytes(16)); 

  try {
    $connect->beginTransaction();

    // Eliminamos la llave anterior si el usuario está regenerando
    if (isset($_POST['regenerate_key'])) {
      $stmt_del = $connect->prepare("DELETE FROM user_api_keys WHERE user_id = :user_id");
      $stmt_del->bindParam(':user_id', $user_id_to_manage);
      $stmt_del->execute();
    } else {
      // Validamos que no exista una llave previa para evitar duplicados
      $stmt_check = $connect->prepare("SELECT COUNT(*) FROM user_api_keys WHERE user_id = :user_id");
      $stmt_check->bindParam(':user_id', $user_id_to_manage);
      $stmt_check->execute();
      
      if ($stmt_check->fetchColumn() >= 1) {
        throw new Exception("Ya tienes una API Key activa. Usa la opción de regenerar si necesitas una nueva.");
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
