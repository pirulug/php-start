<?php

require_once "../../core.php";

if (!isUserLoggedIn()) {
  header('Location: ' . APP_URL . '/admin/controllers/login.php');
  exit();
}

if (!$accessControl->hasAccess([0, 1], $_SESSION['user_role'])) {
  header("Location: " . APP_URL . "/admin/controllers/dashboard.php");
  exit();
}

// Si no tine id
if (!isset($_GET["id"]) || $_GET["id"] == "") {
  add_message("Tienes que tener un id.", "danger");
  header("Location: list.php");
  exit();
}

$id = $encryption->decrypt($_GET["id"]);

if (!is_numeric($id)) {
  add_message("El id no encontrado.", "danger");
  header("Location: list.php");
  exit();
}

$query = "SELECT * FROM users WHERE user_id = $id";
$stmt  = $connect->prepare($query);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_OBJ);


if (empty($user)) {
  add_message("Usuario no encontrado.", "danger");
  header("Location: list.php");
  exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Obtener los datos del formulario y limpiarlos
  $user_id       = $encryption->decrypt(cleardata($_POST['user_id']));
  $user_name     = cleardata($_POST['user_name']);
  $user_email    = cleardata($_POST['user_email']);
  $user_role     = cleardata($_POST['user_role']);
  $user_status   = cleardata($_POST['user_status']);
  $user_password = cleardata($_POST['user_password']);
  // $user_password_save = cleardata($_POST['user_password_save']);

  // Validar el nombre de usuario (mínimo 4 caracteres)
  if (strlen($user_name) < 4) {
    add_message("El nombre de usuario debe tener al menos 4 caracteres.", "danger");
  } else {
    // Verificar si el nombre de usuario ya existe en la base de datos
    $query     = "SELECT * FROM users WHERE user_name = :user_name AND user_id != :user_id";
    $statement = $connect->prepare($query);
    $statement->bindParam(':user_name', $user_name);
    $statement->bindParam(':user_id', $user_id);
    $statement->execute();
    $result = $statement->fetch(PDO::FETCH_OBJ);

    if (!empty($result)) {
      add_message("El nombre de usuario ya está en uso.", "danger");
    }
  }

  // Validar el formato y la unicidad del email
  if (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
    add_message("El email ingresado no es válido.", "danger");
  } else {
    // Verificar si el email ya está registrado en la base de datos
    $query     = "SELECT * FROM users WHERE user_email = :user_email AND user_id != :user_id";
    $statement = $connect->prepare($query);
    $statement->bindParam(':user_email', $user_email);
    $statement->bindParam(':user_id', $id);
    $statement->execute();
    $result = $statement->fetch(PDO::FETCH_OBJ);

    if (!empty($result)) {
      add_message("El correo electrónico ya está registrado.", "danger");
    }
  }

  // Contraseña
  if (strlen($user_password) < 6) {
    add_message("La contraseña debe tener al menos 6 caracteres.", "danger");
  } else {
    $user_password = $encryption->encrypt($user_password);
  }

  // Validar selected
  if (!in_array($user_role, [1, 2])) {
    add_message("Seleccionar rol.", "danger");
  }

  // Validar selected
  if (!in_array($user_status, [1, 2])) {
    add_message("Seleccionar estatus.", "danger");
  }

  // Si no hay mensajes de error, proceder con la inserción
  if (!has_error_messages()) {
    $query = "UPDATE users 
              SET 
              user_name = :user_name, 
              user_email = :user_email, 
              user_role = :user_role, 
              user_status = :user_status, 
              user_password = :user_password, 
              user_updated = CURRENT_TIME 
              WHERE 
              user_id = :user_id";
    $stmt  = $connect->prepare($query);
    $stmt->bindParam(':user_name', $user_name);
    $stmt->bindParam(':user_email', $user_email);
    $stmt->bindParam(':user_role', $user_role);
    $stmt->bindParam(':user_status', $user_status);
    $stmt->bindParam(':user_password', $user_password);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();

    add_message("Usuario se actualizo correctamente", "success");
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit();
  }
}

/* ========== Theme config ========= */
$theme_title = "Editar usuario";
$theme_path  = "user-add";
// $theme_scripts = ["js/clear.js"];
// $theme_styles = ["pages/dashboard.css"];
include BASE_DIR_ADMIN . "/views/users/edit.view.php";
/* ================================= */


