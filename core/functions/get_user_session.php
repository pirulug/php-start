<?php

/**
 * Obtiene TODOS los datos del usuario logeado
 * usando SELECT * y expone todo como propiedades (->)
 *
 * @param PDO $connect
 * @param int $user_id
 * @return object|null
 */
function get_user_session_information(PDO $connect, int $user_id) {
  /* --------------------------------------------------
   * 1. Usuario + Rol (SELECT *)
   * -------------------------------------------------- */
  $stmt = $connect->prepare("
        SELECT *
        FROM users u
        LEFT JOIN roles r ON r.role_id = u.role_id
        WHERE u.user_id = :user_id
          AND u.user_deleted IS NULL
        LIMIT 1
    ");

  $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
  $stmt->execute();

  $user = $stmt->fetch(PDO::FETCH_OBJ);

  if (!$user) {
    return null;
  }

  /* --------------------------------------------------
   * 2. Usermeta (SELECT *)
   * Cada meta se convierte en propiedad del objeto
   * -------------------------------------------------- */
  $stmtMeta = $connect->prepare("
        SELECT *
        FROM usermeta
        WHERE user_id = :user_id
    ");

  $stmtMeta->bindParam(':user_id', $user_id, PDO::PARAM_INT);
  $stmtMeta->execute();

  $metas = $stmtMeta->fetchAll(PDO::FETCH_OBJ);

  foreach ($metas as $meta) {
    $key = $meta->usermeta_key;

    // Evitar sobreescribir columnas base
    if (!property_exists($user, $key)) {
      $user->$key = $meta->usermeta_value;
    }
  }

  return $user;
}
