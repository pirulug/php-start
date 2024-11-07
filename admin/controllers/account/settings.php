<?php

require_once "../../core.php";

$accessControl->require_login(SITE_URL_ADMIN . "/controllers/login.php");
$accessControl->check_access([1, 2], SITE_URL . "/404.php");


if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Recibir y limpiar los valores del formulario
  $currentPassword = trim($_POST['current_password']);
  $newPassword     = trim($_POST['password']);
  $confirmPassword = trim($_POST['confirm_password']);
  $userId          = $_SESSION['user_id'];

  // Verificar que el campo de la contraseña actual no esté vacío
  if (empty($currentPassword)) {
    $messageHandler->addMessage("El campo 'Contraseña actual' no puede estar vacío.", "danger");
  } else {
    $currentPasswordEncrypted = $encryption->encrypt($currentPassword);

    // Verificar la contraseña actual en la base de datos
    $sql  = "SELECT user_password FROM users WHERE user_id = :user_id";
    $stmt = $connect->prepare($sql);
    $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_OBJ);

    if (!$user || $currentPasswordEncrypted !== $user->user_password) {
      $messageHandler->addMessage("La contraseña actual es incorrecta.", "danger");
    }
  }

  // Verificar que las nuevas contraseñas no estén vacías y coincidan
  // if (!$messageHandler->hasMessagesOfType('danger')) {
  if (empty($newPassword) || empty($confirmPassword)) {
    $messageHandler->addMessage("El campo de nueva contraseña no puede estar vacío.", "danger");
  } elseif ($newPassword !== $confirmPassword) {
    $messageHandler->addMessage("La nueva contraseña y la confirmación no coinciden.", "danger");
  }
  // }

  // Actualizar la contraseña si no hay errores
  if (!$messageHandler->hasMessagesOfType('danger')) {
    $hashedPassword = $encryption->encrypt($newPassword);

    // Actualizar la contraseña en la base de datos
    $sql  = "UPDATE users SET user_password = :new_password WHERE user_id = :user_id";
    $stmt = $connect->prepare($sql);
    $stmt->bindParam(':new_password', $hashedPassword, PDO::PARAM_STR);
    $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);

    if ($stmt->execute()) {
      $messageHandler->addMessage("La contraseña se ha actualizado correctamente.", "success");
      $log->logAction($userId, 'Actualizado', "Cambio de contraseña se ha actualizado correctamente.");
      header("Refresh: 0");  // Refrescar la página para evitar reenvíos del formulario
      exit();
    } else {
      $messageHandler->addMessage("Hubo un error al actualizar la contraseña.", "danger");
    }
  }
}


/* ========== Theme config ========= */
$theme_title = "Configuraciones de la cuenta";
$theme_path  = "account-settings";
include BASE_DIR_ADMIN . "/views/account/settings.view.php";
/* ================================= */