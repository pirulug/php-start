<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $permission_name        = trim($_POST['permission_name'] ?? '');
  $permission_key_name    = trim($_POST['permission_key_name'] ?? '');
  $permission_description = trim($_POST['permission_description'] ?? '');
  $group_id               = intval($_POST['group_id'] ?? 0);
  $new_group_name         = trim($_POST['new_group_name'] ?? '');
  $new_group_key          = trim($_POST['new_group_key'] ?? '');

  $permission_context_id = intval($_POST['permission_context_id'] ?? 0);


  try {
    // Si se quiere crear un nuevo grupo
    if ($new_group_name !== '' && $new_group_key !== '') {
      $stmt = $connect->prepare("
                INSERT INTO permission_groups (permission_group_name, permission_group_key_name)
                VALUES (:gname, :gkey)
            ");
      $stmt->bindParam(':gname', $new_group_name, PDO::PARAM_STR);
      $stmt->bindParam(':gkey', $new_group_key, PDO::PARAM_STR);
      $stmt->execute();

      // Obtener el nuevo ID del grupo
      $group_id = $connect->lastInsertId();
    }

    // Validar que haya grupo y campos obligatorios
    if ($permission_name && $permission_key_name && $group_id > 0 && $permission_context_id > 0) {
      $stmt = $connect->prepare("
        INSERT INTO permissions 
        (
          permission_name,
          permission_key_name,
          permission_description,
          permission_group_id,
          permission_context_id
        )
        VALUES
        (
          :name,
          :key,
          :desc,
          :group_id,
          :context_id
        )
      ");

      $stmt->bindParam(':name', $permission_name, PDO::PARAM_STR);
      $stmt->bindParam(':key', $permission_key_name, PDO::PARAM_STR);
      $stmt->bindParam(':desc', $permission_description, PDO::PARAM_STR);
      $stmt->bindParam(':group_id', $group_id, PDO::PARAM_INT);
      $stmt->bindParam(':context_id', $permission_context_id, PDO::PARAM_INT);
      $stmt->execute();

      // $notifier->add("Nuevo permiso agregado: $permission_name", "success");
      $notifier
        ->message("Nuevo permiso agregado: " . htmlspecialchars($permission_name))
        ->bootstrap()
        ->success()
        ->add();
    } else {
      // $notifier->add("Debes seleccionar o crear un grupo y completar los campos del permiso.", "warning");
      $notifier
        ->message("Debes seleccionar o crear un grupo y completar los campos del permiso.")
        ->bootstrap()
        ->warning()
        ->add();
    }
  } catch (PDOException $e) {
    if ($e->getCode() == 23000) {
      // $notifier->add("Ya existe una clave con ese nombre (grupo o permiso).", "warning");
      $notifier
        ->message("Ya existe una clave con ese nombre (grupo o permiso).")
        ->bootstrap()
        ->warning()
        ->add();
    } else {
      // $notifier->add("Error al agregar permiso: " . $e->getMessage(), "danger");
      $notifier
        ->message("Error al agregar permiso: " . $e->getMessage())
        ->bootstrap()
        ->danger()
        ->add();
    }
  }
}

// Grupos de permisos
$stmt = $connect->prepare("SELECT permission_group_id, permission_group_name 
                                  FROM permission_groups 
                                  ORDER BY permission_group_name ASC");
$stmt->execute();
$groups = $stmt->fetchAll(PDO::FETCH_OBJ);

// Contextos de permisos
$stmt = $connect->prepare("
  SELECT
    permission_context_id,
    permission_context_name
  FROM permission_contexts
  ORDER BY permission_context_name ASC
");
$stmt->execute();
$contexts = $stmt->fetchAll(PDO::FETCH_OBJ);
