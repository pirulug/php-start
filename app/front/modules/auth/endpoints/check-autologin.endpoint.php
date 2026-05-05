<?php

/**
 * Endpoint: Check Autologin (Remember Me)
 */

try {
  // 1. Si ya hay sesión activa, no hacemos nada
  if (is_logged_in()) {
  echo json_encode([
      'success' => true,
      'message' => 'Sesión ya activa',
      'data'    => ['logged' => true]
  ]);
  exit;
  }

  // 2. Si no hay cookie, fuera
  if (!isset($_COOKIE[COOKIE_PREFIX . 'auth'])) {
  echo json_encode([
      'success' => false,
      'message' => 'Sin cookie de persistencia',
      'data'    => ['logged' => false]
  ]);
  exit;
  }

  // 3. Procesar cookie
  $dataEncrypted = $_COOKIE[COOKIE_PREFIX . 'auth'];
  $dataDecrypted = $cipher->decrypt($dataEncrypted);

  if (!$dataDecrypted || !str_contains($dataDecrypted, ':')) {
  throw new Exception('Token corrupto');
  }

  [$user_id, $token] = explode(':', $dataDecrypted, 2);
  $token = trim($token);
  $user_id = trim($user_id);

  if (!is_numeric($user_id) || empty($token)) {
  throw new Exception('Estructura inválida');
  }

  // 4. Validar en BD
  $stmt = $connect->prepare("
  SELECT u.*, um.usermeta_value AS token_hash
  FROM users u
  LEFT JOIN usermeta um
      ON um.user_id = u.user_id
      AND um.usermeta_key = 'remember_token'
  WHERE u.user_id = :user_id
  AND u.user_status = 1
  LIMIT 1
  ");

  $stmt->execute([':user_id' => $user_id]);

  if ($stmt->rowCount() === 0) {
  throw new Exception('Usuario no válido');
  }

  $user = $stmt->fetch(PDO::FETCH_OBJ);

  if (
  empty($user->token_hash) ||
  !hash_equals($user->token_hash, hash('sha256', $token))
  ) {
  throw new Exception('Token inválido');
  }

  // 5. LOGIN EXITOSO
  $_SESSION['user_id'] = $user->user_id;
  $_SESSION['signin']  = true;

  // Actualizar último login
  $stmtUpdate = $connect->prepare("UPDATE users SET user_last_login = NOW() WHERE user_id = :uid");
  $stmtUpdate->execute([':uid' => $user->user_id]);

  echo json_encode([
  'success' => true,
  'message' => 'Login automático exitoso',
  'data'    => [
      'logged'   => true,
      'user'     => $user->user_nickname,
      'redirect' => front_route("account/profile")
  ]
  ]);

} catch (Exception $e) {
  // Limpiar cookie si es un error de formato o token inválido
  if (!str_contains($e->getMessage(), 'database') && !str_contains($e->getMessage(), 'Conexión')) {
  setcookie(COOKIE_PREFIX . 'auth', '', time() - 3600, '/');
  }

  echo json_encode([
  'success' => false,
  'message' => 'Error en autologin',
  'debug'   => $e->getMessage()
  ]);
}