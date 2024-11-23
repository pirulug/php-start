<?php

require_once "core.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username         = trim($_POST['username']);
  $email            = trim($_POST['email']);
  $password         = $_POST['password'];
  $confirm_password = $_POST['confirm_password'];

  // Validar el usuario (mínimo 5 caracteres, sin espacios)
  if (strlen($username) < 5 || preg_match('/\s/', $username)) {
    $messageHandler->addMessage("El usuario debe tener al menos 5 caracteres y no contener espacios.", "danger");

  }

  // Verificar que el usuario no esté duplicado
  $check_username_query = "SELECT * FROM users WHERE user_name = :username";
  $stmt                 = $connect->prepare($check_username_query);
  $stmt->bindParam(':username', $username, PDO::PARAM_STR);
  $stmt->execute();

  if ($stmt->rowCount() > 0) {
    $messageHandler->addMessage("El nombre de usuario ya está registrado. Por favor, elige otro.", "danger");
  }

  // Validar el email
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $messageHandler->addMessage("El email ingresado no es válido.", "danger");
  }

  // Verificar que el email no esté duplicado
  $check_email_query = "SELECT * FROM users WHERE user_email = :email";
  $stmt              = $connect->prepare($check_email_query);
  $stmt->bindParam(':email', $email, PDO::PARAM_STR);
  $stmt->execute();

  if ($stmt->rowCount() > 0) {
    $messageHandler->addMessage("El email ya está registrado.", "danger");
  }

  // Validar la contraseña
  if (strlen($password) < 6) {
    $messageHandler->addMessage("La contraseña debe tener al menos 6 caracteres.", "danger");
  }

  // Validar que las contraseñas coincidan
  if ($password !== $confirm_password) {
    $messageHandler->addMessage("Las contraseñas no coinciden.", "danger");
  }

  // Si no hay errores, continuar con el registro
  if (!$messageHandler->hasMessagesOfType('danger')) {
    $hashed_password = $encryption->encrypt($password);

    // Insertar los datos en la tabla `users`
    $insert_query = "INSERT INTO users (user_name, user_email, user_password) VALUES (:username, :email, :password)";
    $stmt         = $connect->prepare($insert_query);
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':password', $hashed_password, PDO::PARAM_STR);

    if ($stmt->execute()) {
      $messageHandler->addMessage("Registro exitoso. Ahora puedes iniciar sesión.", "success", "toast");
      header("Location: " . SITE_URL . "/signin.php");
    } else {
      $messageHandler->addMessage("Error al registrar al usuario: " . $stmt->errorInfo()[2], "danger");
    }
  }
}


$pageTitle = "Registrate";
include "views/signup.view.php";