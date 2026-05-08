<?php
/**
 * SecurityTest
 * Pruebas para LoginRateLimiter, Captcha, CaptchaManager.
 */

echo "\nEJECUTANDO: SecurityTest\n";

global $connect;

// 1. Prueba: LoginRateLimiter
$limiter = new LoginRateLimiter($connect);
$limiter->fromPost("user_test_non_existent")->resolveUser()->load();
Tester::assertTrue(!$limiter->isBlocked(), "Un usuario inexistente no debe estar bloqueado inicialmente");
Tester::assertEquals(false, $limiter->isBruteForce(), "Un usuario nuevo no debe activar fuerza bruta");

// 2. Prueba: Captcha (Validación Estática)
$_SESSION['test_captcha'] = 'ABC123';
Tester::assertTrue(Captcha::validate('ABC123', 'test_captcha'), "Captcha::validate debe validar correctamente el código");
Tester::assertTrue(!isset($_SESSION['test_captcha']), "Captcha::validate debe limpiar la sesión tras validar");

// 3. Prueba: CaptchaManager (si existe la clase, de lo contrario omitir)
if (class_exists('CaptchaManager')) {
  $manager = new CaptchaManager();
  Tester::assertTrue(method_exists($manager, 'validate'), "CaptchaManager debe tener el método validate");
}
