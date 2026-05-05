<?php

/**
 * Creación de nueva Política.
 */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $title  = clear_input($_POST['st_title'] ?? '');
  $slug   = clear_input($_POST['st_slug'] ?? '');
  $type   = clear_input($_POST['st_type'] ?? 'markdown');
  $status = (int) ($_POST['st_status'] ?? 1);

  if (empty($slug)) {
    $slug = (new TextTransformer())->text($title)->slug()->lowercase()->apply();
  }

  // Determinar contenido según tipo
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

  if (empty($title) || empty($content)) {
    $notifier->message("El título y el contenido son obligatorios.")->danger()->bootstrap()->add();
  } else {
    try {
      $connect->beginTransaction();

      // 1. Insertar en tabla posts
      $stmt = $connect->prepare("
        INSERT INTO posts (post_author, post_title, post_slug, post_content, post_type, post_status) 
        VALUES (:author, :title, :slug, :content, 'policy', :status)
      ");

      $v_author  = $_SESSION['user_id'] ?? 1;
      $v_title   = $title;
      $v_slug    = $slug;
      $v_content = $content;
      $v_status  = $status;

      $stmt->bindParam(':author', $v_author);
      $stmt->bindParam(':title', $v_title);
      $stmt->bindParam(':slug', $v_slug);
      $stmt->bindParam(':content', $v_content);
      $stmt->bindParam(':status', $v_status);
      $stmt->execute();

      $post_id = $connect->lastInsertId();

      // 2. Insertar tipo en postmeta
      $stmtMeta = $connect->prepare("
        INSERT INTO postmeta (post_id, postmeta_key, postmeta_value) 
        VALUES (:post_id, 'type', :type)
      ");
      $v_type   = $type;
      $stmtMeta->bindParam(':post_id', $post_id);
      $stmtMeta->bindParam(':type', $v_type);
      $stmtMeta->execute();

      $connect->commit();

      $notifier->message("Nueva política creada exitosamente.")->success()->bootstrap()->add();
      header("Location: " . admin_route('policies'));
      exit();

    } catch (Exception $e) {
      if ($connect->inTransaction())
        $connect->rollBack();
      $notifier->message("Error al crear: " . $e->getMessage())->danger()->bootstrap()->add();
    }
  }
}