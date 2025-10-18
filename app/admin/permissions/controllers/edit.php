<?php

// =============================================
// OBTENER ID DEL ROL
// =============================================

$permission_id = $id;

if ($permission_id <= 0) {
  $notifier->add("No se ha especificado un rol válido.", "warning");
  exit;
}

// ======================= OBTENER PERMISO =======================
try {
  $stmt = $connect->prepare("SELECT * FROM permissions WHERE permission_id = :id");
  $stmt->bindParam(':id', $permission_id, PDO::PARAM_INT);
  $stmt->execute();
  $permission = $stmt->fetch(PDO::FETCH_OBJ);

  if (!$permission) {
    die("<div class='alert alert-warning'>⚠️ No se encontró el permiso.</div>");
  }
} catch (PDOException $e) {
  die("Error al cargar permiso: " . $e->getMessage());
}

// ======================= ACTUALIZAR PERMISO =======================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $permission_name        = trim($_POST['permission_name'] ?? '');
  $permission_key_name    = trim($_POST['permission_key_name'] ?? '');
  $permission_description = trim($_POST['permission_description'] ?? '');
  $group_id               = intval($_POST['group_id'] ?? 0);
  $new_group_name         = trim($_POST['new_group_name'] ?? '');
  $new_group_key          = trim($_POST['new_group_key'] ?? '');

  try {
    // Si se crea un nuevo grupo
    if ($new_group_name !== '' && $new_group_key !== '') {
      $stmt = $connect->prepare("
                INSERT INTO permission_groups (permission_group_name, permission_group_key_name)
                VALUES (:gname, :gkey)
            ");
      $stmt->bindParam(':gname', $new_group_name, PDO::PARAM_STR);
      $stmt->bindParam(':gkey', $new_group_key, PDO::PARAM_STR);
      $stmt->execute();
      $group_id = $connect->lastInsertId();
    }

    if ($permission_name && $permission_key_name && $group_id > 0) {
      $stmt = $connect->prepare("
                UPDATE permissions 
                SET permission_name = :name,
                    permission_key_name = :key,
                    permission_description = :desc,
                    permission_group_id = :group_id
                WHERE permission_id = :id
            ");
      $stmt->bindParam(':name', $permission_name, PDO::PARAM_STR);
      $stmt->bindParam(':key', $permission_key_name, PDO::PARAM_STR);
      $stmt->bindParam(':desc', $permission_description, PDO::PARAM_STR);
      $stmt->bindParam(':group_id', $group_id, PDO::PARAM_INT);
      $stmt->bindParam(':id', $permission_id, PDO::PARAM_INT);
      $stmt->execute();

      $notifier->add("Permiso «{$permission->permission_name}» actualizado correctamente.", "success");

      header("Location:" . $_SERVER['REQUEST_URI']);
      exit();
    } else {
      $notifier->add("Completa todos los campos obligatorios.", "warning");
    }
  } catch (PDOException $e) {
    if ($e->getCode() == 23000) {
      $notifier->add("Ya existe una clave con ese nombre.", "warning");
    } else {
      $notifier->add("Error: " . htmlspecialchars($e->getMessage()), "danger");
    }
  }
}

// ======================= OBTENER GRUPOS =======================
$stmt = $connect->prepare("SELECT permission_group_id, permission_group_name 
                                  FROM permission_groups 
                                  ORDER BY permission_group_name ASC");
$stmt->execute();
$groups = $stmt->fetchAll(PDO::FETCH_OBJ);
