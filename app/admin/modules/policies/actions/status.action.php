<?php

/**
 * Cambio de Estado de Política (Activo/Borrador).
 */

$id = $cipher->decrypt($args['id']);

if ($id) {
  try {
    // 1. Obtener estado actual
    $stmt = $connect->prepare("SELECT post_status FROM posts WHERE post_id = :id AND post_type = 'policy'");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $policy = $stmt->fetch(PDO::FETCH_OBJ);

    if ($policy) {
      // 2. Alternar estado
      $new_status = ($policy->post_status == 1) ? 0 : 1;
      
      $update = $connect->prepare("UPDATE posts SET post_status = :status WHERE post_id = :id");
      $update->bindParam(':status', $new_status, PDO::PARAM_INT);
      $update->bindParam(':id', $id, PDO::PARAM_INT);
      $update->execute();

      $msg = ($new_status == 1) ? "Política activada correctamente." : "Política movida a borradores.";
      $notifier->message($msg)->success()->bootstrap()->add();
    } else {
      $notifier->message("Error: Política no encontrada.")->danger()->bootstrap()->add();
    }

  } catch (Exception $e) {
    $notifier->message("Error al cambiar estado: " . $e->getMessage())->danger()->bootstrap()->add();
  }
}

header("Location: " . admin_route('policies'));
exit();
