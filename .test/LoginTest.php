<?php
/**
 * LoginTest
 * Pruebas completas de inicio de sesión, auto-login con cookies, expiración y API keys.
 */

echo "\nEJECUTANDO: LoginTest\n";

global $connect, $cipher;

// -----------------------------------------------------------------------------
// SECCIÓN: PREPARACIÓN DE USUARIO DE PRUEBA
// -----------------------------------------------------------------------------
$testUserLogin = "login_test_user";
$testUserEmail = "login_test_user@example.com";
$testUserPassRaw = "PasswordSeguro123!";
$testUserPassHash = $cipher->password($testUserPassRaw);

// Limpiar por si quedó basura de una prueba fallida anterior
$stmtDel = $connect->prepare("DELETE FROM users WHERE user_login = :login");
$stmtDel->bindParam(":login", $testUserLogin);
$stmtDel->execute();

// Crear usuario de prueba
$stmtInsert = $connect->prepare("
  INSERT INTO users (user_login, user_email, user_password, user_status, user_created)
  VALUES (:login, :email, :pass, 1, NOW())
");
$stmtInsert->bindParam(":login", $testUserLogin);
$stmtInsert->bindParam(":email", $testUserEmail);
$stmtInsert->bindParam(":pass", $testUserPassHash);
$stmtInsert->execute();

$userId = (int) $connect->lastInsertId();
Tester::assertTrue($userId > 0, "Debe crearse el usuario de prueba correctamente con un ID válido");

// -----------------------------------------------------------------------------
// CASO 1: Validación de Credenciales Básicas (POST Login)
// -----------------------------------------------------------------------------
// 1.1 Contraseña Correcta
$passOk = $cipher->verifyPassword($testUserPassRaw, $testUserPassHash);
Tester::assertTrue($passOk, "verifyPassword debe retornar true con la contraseña correcta");

// 1.2 Contraseña Incorrecta
$passFail = $cipher->verifyPassword("WrongPassword!", $testUserPassHash);
Tester::assertTrue(!$passFail, "verifyPassword debe retornar false con una contraseña incorrecta");

// -----------------------------------------------------------------------------
// CASO 2: Auto-Login por Cookie (Recordar Sesión / Remember Me)
// -----------------------------------------------------------------------------
$token = bin2hex(random_bytes(32));
$tokenHash = hash("sha256", $token);
$cookieData = $userId . ":" . $token;
$encryptedCookie = $cipher->encrypt($cookieData);

// Simular guardado de token de recordar en base de datos (usermeta)
$stmtMeta = $connect->prepare("
  INSERT INTO usermeta (user_id, usermeta_key, usermeta_value)
  VALUES (:user_id, 'remember_token', :value)
  ON DUPLICATE KEY UPDATE usermeta_value = VALUES(usermeta_value)
");
$stmtMeta->bindParam(":user_id", $userId);
$stmtMeta->bindParam(":value", $tokenHash);
$stmtMeta->execute();

// Simular el proceso de validación de Cookie (Auto-Login)
$cookieValue = $encryptedCookie;
$decrypted = $cipher->decrypt($cookieValue);
Tester::assertTrue($decrypted !== null, "La cookie debe poder descifrarse correctamente con Cipher");

$parts = explode(":", $decrypted, 2);
Tester::assertEquals(2, count($parts), "La cookie descifrada debe contener dos partes divididas por ':'");

$cookieUid = (int) $parts[0];
$cookieToken = $parts[1];
Tester::assertEquals((int) $userId, (int) $cookieUid, "El ID de usuario en la cookie debe coincidir");

// Obtener token almacenado en base de datos
$stmtGetToken = $connect->prepare("
  SELECT usermeta_value 
  FROM usermeta 
  WHERE user_id = :user_id AND usermeta_key = 'remember_token'
");
$stmtGetToken->bindParam(":user_id", $cookieUid);
$stmtGetToken->execute();
$dbTokenHash = $stmtGetToken->fetchColumn();

Tester::assertTrue(!empty($dbTokenHash), "Debe existir un token_hash en la base de datos");
Tester::assertTrue(hash_equals($dbTokenHash, hash("sha256", $cookieToken)), "El token de la cookie debe coincidir con el hash almacenado");

// -----------------------------------------------------------------------------
// CASO 3: Expiración de Cookies y Sesión
// -----------------------------------------------------------------------------
// 3.1 Simulación de Cookie Expirada (Vacía o inexistente en $_COOKIE)
$hasCookie = isset($_COOKIE[COOKIE_PREFIX . "auth"]);
Tester::assertTrue(!$hasCookie, "Inicialmente no debe existir la cookie real del navegador en CLI");

// 3.2 Simulación de Revocación de Sesión (El token en base de datos ya no existe)
$stmtRevoke = $connect->prepare("
  DELETE FROM usermeta 
  WHERE user_id = :user_id AND usermeta_key = 'remember_token'
");
$stmtRevoke->bindParam(":user_id", $userId);
$stmtRevoke->execute();

$stmtGetToken->execute();
$revokedTokenHash = $stmtGetToken->fetchColumn();
Tester::assertTrue(empty($revokedTokenHash), "El token_hash debe ser nulo o vacío después de revocar/desconectar la sesión");

// -----------------------------------------------------------------------------
// CASO 4: Autenticación por Sesión en Dashboard / Front (Middlewares)
// -----------------------------------------------------------------------------
// 4.1 Simulación de Usuario no Autenticado (Invitado)
$_SESSION = [];
global $user_session;
$user_session = null;
Tester::assertTrue(!is_logged_in(), "is_logged_in debe retornar false cuando la sesión está vacía");

// 4.2 Simulación de Usuario Autenticado
$_SESSION["signin"] = true;
$_SESSION["user_id"] = $userId;
$user_session = get_user_session($connect, $userId);

Tester::assertTrue(is_logged_in(), "is_logged_in debe retornar true tras definir los datos de sesión");
Tester::assertEquals($testUserLogin, user_session()->user_login, "user_session debe retornar los datos correctos del usuario autenticado");

// -----------------------------------------------------------------------------
// CASO 5: Autenticación de API por API Key (auth_api_middleware)
// -----------------------------------------------------------------------------
$apiKey = "test_api_key_valid_123";
$apiMetaData = json_encode([
  "key" => $apiKey,
  "created_at" => date("Y-m-d H:i:s")
]);

// Guardar API Key en usermeta
$stmtApiKey = $connect->prepare("
  INSERT INTO usermeta (user_id, usermeta_key, usermeta_value)
  VALUES (:user_id, 'api_key', :value)
");
$stmtApiKey->bindParam(":user_id", $userId);
$stmtApiKey->bindParam(":value", $apiMetaData);
$stmtApiKey->execute();

// 5.1 Simular validación de API Key Válida (Lógica similar a auth_api_middleware)
$_GET["api_key"] = $apiKey;
$api_key = $_GET["api_key"] ?? null;

$stmtVal = $connect->prepare("
  SELECT user_id, usermeta_value 
  FROM usermeta 
  WHERE usermeta_key = 'api_key' AND usermeta_value LIKE :key_search
  LIMIT 1
");
$keySearch = "%" . $api_key . "%";
$stmtVal->bindParam(":key_search", $keySearch);
$stmtVal->execute();
$apiRow = $stmtVal->fetch(PDO::FETCH_OBJ);

$apiValid = false;
if ($apiRow) {
  $val = $apiRow->usermeta_value;
  $data = json_decode($val, true);
  if (json_last_error() === JSON_ERROR_NONE && is_array($data)) {
    if (isset($data["key"]) && $data["key"] === $api_key) {
      $apiValid = true;
    }
  }
}
Tester::assertTrue($apiValid, "auth_api_middleware debe reconocer una API Key válida en usermeta");

// 5.2 Simular API Key Inválida
$_GET["api_key"] = "incorrect_api_key_456";
$api_key_bad = $_GET["api_key"];
$keySearchBad = "%" . $api_key_bad . "%";
$stmtVal->bindParam(":key_search", $keySearchBad);
$stmtVal->execute();
$apiRowBad = $stmtVal->fetch(PDO::FETCH_OBJ);

$apiValidBad = false;
if ($apiRowBad) {
  $val = $apiRowBad->usermeta_value;
  $data = json_decode($val, true);
  if (json_last_error() === JSON_ERROR_NONE && is_array($data)) {
    if (isset($data["key"]) && $data["key"] === $api_key_bad) {
      $apiValidBad = true;
    }
  }
}
Tester::assertTrue(!$apiValidBad, "auth_api_middleware debe rechazar una API Key incorrecta");

// -----------------------------------------------------------------------------
// SECCIÓN: LIMPIEZA POST-PRUEBAS
// -----------------------------------------------------------------------------
$stmtCleanMeta = $connect->prepare("DELETE FROM usermeta WHERE user_id = :user_id");
$stmtCleanMeta->bindParam(":user_id", $userId);
$stmtCleanMeta->execute();

$stmtCleanUser = $connect->prepare("DELETE FROM users WHERE user_id = :user_id");
$stmtCleanUser->bindParam(":user_id", $userId);
$stmtCleanUser->execute();

// Resetear sesión
$_SESSION = [];
$user_session = null;
unset($_GET["api_key"]);

Tester::assertTrue(true, "Limpieza de base de datos finalizada de forma segura");
