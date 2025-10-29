<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $email     = clear_data($_POST['email'] ?? '');
  $username  = clear_data($_POST['username'] ?? '');
  $password  = clear_data($_POST['password'] ?? '');
  $password2 = clear_data($_POST['password_confirmation'] ?? '');

  // Validaciones
  if (empty($email) || empty($username) || empty($password) || empty($password2)) {
    $notifier->add("Todos los campos son obligatorios", "danger");
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $notifier->add("El correo electrónico no es válido", "danger");
  } elseif ($password !== $password2) {
    $notifier->add("Las contraseñas no coinciden", "danger");
  }

  if (!$notifier->hasErrors()) {
    // Verificar si el usuario o correo existen
    $query_check = "SELECT user_id FROM users WHERE user_email = :email OR user_name = :username";
    $stmt_check  = $connect->prepare($query_check);
    $stmt_check->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt_check->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt_check->execute();

    if ($stmt_check->rowCount() > 0) {
      $notifier->add("El correo o nombre de usuario ya están registrados", "danger");
    } else {
      // Encriptar contraseña
      $encrypted_password = $cipher->encrypt($password);

      // Insertar usuario
      $query_insert = "
        INSERT INTO users (
          user_name, 
          user_password, 
          user_email, 
          user_status, 
          role_id, 
          user_created
        ) VALUES (
          :username, 
          :password, 
          :email, 
          1, 
          2,  -- Rol por defecto
          NOW()
        )
      ";

      $stmt_insert = $connect->prepare($query_insert);
      $stmt_insert->bindParam(':username', $username, PDO::PARAM_STR);
      $stmt_insert->bindParam(':password', $encrypted_password, PDO::PARAM_STR);
      $stmt_insert->bindParam(':email', $email, PDO::PARAM_STR);

      if ($stmt_insert->execute()) {

        // Enviar correo de bienvenida con su contraseña
        $subject = "Bienvenido a " . SITE_NAME ;
        $body    = "
          <h3>Hola {$username},</h3>
          <p>Tu cuenta ha sido creada exitosamente.</p>
          <p><strong>Correo:</strong> {$email}</p>
          <p><strong>Contraseña:</strong> {$password}</p>
          <hr>
          <p>Te recomendamos cambiar tu contraseña al iniciar sesión por primera vez.</p>
          <p>Atentamente,<br>El equipo de " . SITE_NAME . "</p>
        ";

        $result = $mailService->send($email, $subject, $body);

        // Si falla, simplemente mostrar aviso, pero no detener el registro
        if (!$result["success"]) {
          $notifier->add("Usuario creado, pero no se pudo enviar el correo: {$result['message']}", "warning", "toast");
        } else {
          $notifier->add("Cuenta creada exitosamente. Se envió un correo con tus credenciales.", "success", "toast");
        }

        $notifier->add("Cuenta creada exitosamente. Ya puedes iniciar sesión.", "success", "toast");
        header("Location: " . SITE_URL . "/signin");
        exit();
      } else {
        $notifier->add("Error al registrar el usuario. Inténtalo nuevamente.", "danger", "toast");
      }
    }
  }
}