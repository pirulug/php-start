<?php

/**
 * Eliminación de Política.
 * Implementa protección para registros críticos del sistema.
 */

$id              = $cipher->decrypt($args['id']);
$protected_slugs = ['faqs', 'privacy-policy', 'terms-and-conditions'];

if ($id) {
  try {
    // Verificar si es un registro protegido antes de eliminar
    $check    = $connect->prepare("SELECT post_slug FROM posts WHERE post_id = :id AND post_type = 'policy'");
    $v_id_chk = $id;
    $check->bindParam(':id', $v_id_chk);
    $check->execute();
    $record = $check->fetch(PDO::FETCH_OBJ);

    if ($record && in_array($record->post_slug, $protected_slugs)) {
      $notifier->message("Error: No puedes eliminar este documento esencial del sistema.")->danger()->bootstrap()->add();
    } else {
      $stmt     = $connect->prepare("DELETE FROM posts WHERE post_id = :id AND post_type = 'policy'");
      $v_id_del = $id;
      $stmt->bindParam(':id', $v_id_del);
      $stmt->execute();
      $notifier->message("Documento eliminado correctamente.")->success()->bootstrap()->add();
    }

  } catch (Exception $e) {
    $notifier->message("Error al eliminar: " . $e->getMessage())->danger()->bootstrap()->add();
  }
}

header("Location: " . admin_route('policies'));
exit();