<?php
/**
 * CipherTest
 * Pruebas para la clase Cipher.
 */

echo "\nEJECUTANDO: CipherTest\n";

global $cipher;

// Prueba: Encriptación y Desencriptación
$original = "Mensaje Secreto 123";
$encrypted = $cipher->encrypt($original);
$decrypted = $cipher->decrypt($encrypted);

Tester::assertEquals($original, $decrypted, "El mensaje desencriptado debe ser igual al original");
Tester::assertTrue($original !== $encrypted, "El mensaje encriptado no debe ser igual al original");

// Prueba: Ofuscación de IDs (b10ToBstr)
$id = 500;
$short = $cipher->b10ToBstr($id, "mixed");
$recovered_id = $cipher->bstrToB10($short, "mixed");

Tester::assertEquals((string)$id, (string)$recovered_id, "El ID recuperado de la ofuscación debe ser igual al original");

// Prueba: Hashing de contraseñas
$password = "mi_clave_segura";
$hash = $cipher->password($password);

Tester::assertTrue($cipher->verifyPassword($password, $hash), "La contraseña debe ser verificada correctamente con su hash");
Tester::assertTrue(!$cipher->verifyPassword("otra_clave", $hash), "Una contraseña incorrecta no debe ser validada");
