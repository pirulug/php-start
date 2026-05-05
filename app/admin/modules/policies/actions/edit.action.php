<?php

/**
 * Edición de Política.
 * Gestiona el contenido según el tipo de documento elegido.
 */

$id = $cipher->decrypt($args['id']);

// 1. Obtener datos del post y su meta 'type'
$stmt      = $connect->prepare("
  SELECT p.*, pm.postmeta_value as policy_type 
  FROM posts p
  LEFT JOIN postmeta pm ON p.post_id = pm.post_id AND pm.postmeta_key = 'type'
  WHERE p.post_id = :id AND p.post_type = 'policy'
");
$v_id_load = $id;
$stmt->bindParam(':id', $v_id_load);
$stmt->execute();
$policy = $stmt->fetch(PDO::FETCH_OBJ);

if (!$policy) {
  header("Location: " . admin_route('policies'));
  exit();
}

$protected_slugs = ['faqs', 'privacy-policy', 'terms-and-conditions'];
$is_protected    = in_array($policy->post_slug, $protected_slugs);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $title  = clear_input($_POST['st_title'] ?? '');
  $slug   = clear_input($_POST['st_slug'] ?? $policy->post_slug);
  $type   = clear_input($_POST['st_type'] ?? $policy->policy_type);
  $status = (int) ($_POST['st_status'] ?? 1);

  // Procesar contenido según tipo elegido en el form
  if ($type === 'faq') {
    $questions = $_POST['faq_q'] ?? [];
    $answers   = $_POST['faq_a'] ?? [];
    $faq_data  = [];
    foreach ($questions as $i => $q) {
      $faq_data[] = [
        'q' => clear_input($q),
        'a' => clear_input($answers[$i])
      ];
    }
    $content = json_encode($faq_data);
  } else {
    $content = clear_textarea($_POST['st_content'] ?? '');
  }

  if (empty($title) || (empty($slug) && !$is_protected)) {
    $notifier->message("El título y el slug son obligatorios.")->danger()->bootstrap()->add();
  } else {
    try {
      $connect->beginTransaction();

      // 1. Actualizar tabla posts
      $query = "UPDATE posts SET 
            post_title = :title, 
            post_slug = :slug, 
            post_content = :content, 
            post_status = :status 
            WHERE post_id = :id";

      $stmt = $connect->prepare($query);

      $v_title   = $title;
      $v_slug    = $slug; // Se permite el cambio si el usuario lo editó
      $v_content = $content;

      $v_status  = $status;
      $v_id_upd  = $id;

      $stmt->bindParam(':title', $v_title);
      $stmt->bindParam(':slug', $v_slug);
      $stmt->bindParam(':content', $v_content);
      $stmt->bindParam(':status', $v_status);
      $stmt->bindParam(':id', $v_id_upd);
      $stmt->execute();

      // 2. Actualizar meta 'type'
      $stmtMeta = $connect->prepare("
        INSERT INTO postmeta (post_id, postmeta_key, postmeta_value) 
        VALUES (:post_id, 'type', :type)
        ON DUPLICATE KEY UPDATE postmeta_value = VALUES(postmeta_value)
      ");
      $v_type   = $type;
      $stmtMeta->bindParam(':post_id', $id);
      $stmtMeta->bindParam(':type', $v_type);
      $stmtMeta->execute();

      $connect->commit();

      $notifier->message("Cambios guardados correctamente.")->success()->bootstrap()->add();
      header("Refresh: 0");
      exit();

    } catch (Exception $e) {
      if ($connect->inTransaction())
        $connect->rollBack();
      $notifier->message("Error al actualizar: " . $e->getMessage())->danger()->bootstrap()->add();
    }
  }
}

// Preparar datos para la vista
$faqs = ($policy->policy_type === 'faq') ? json_decode($policy->post_content, true) : [];