<?php

require_once "../core/libs/Cipher.php";

// Crear instancia
$cip = new Cipher("AES-256-CBC", '$STARTPHP@2024PIRU', "456232132132432234132");

// ==========================================================
// 🔐 ENCRIPTAR / DESENCRIPTAR
// ==========================================================
$textoPlano = "admin123";
$cifrado    = $cip->encrypt($textoPlano);
$descifrado = $cip->decrypt($cifrado);

echo "🔐 Cifrado: $cifrado <br>";
echo "🔓 Descifrado: $descifrado <br><br>";

// ==========================================================
// 🔑 HASHING (con diferentes longitudes y algoritmos)
// ==========================================================
echo "SHA256 (64 chars): " . $cip->hash("mi_password") . "<br>";
echo "SHA512 (128 chars): " . $cip->hash("mi_password", "sha512") . "<br>";
echo "MD5 (32 chars): " . $cip->hash("mi_password", "md5") . "<br>";


$hash = $cip->hash("secreto", "sha256");

echo "Verificar hash correcto: " . ($cip->verifyHash("secreto", $hash, "sha256") ? "✅ Sí" : "❌ No") . "<br>";
echo "Verificar hash incorrecto: " . ($cip->verifyHash("otro", $hash, "sha256") ? "✅ Sí" : "❌ No") . "<br><br>";

// ==========================================================
// 🔢 CONVERSIÓN BASE10 ↔ BSTR
// ==========================================================
$id = 123;

// --- STR ---
$str = $cip->b10ToBstr($id, "lowercase");
echo "123 → STR: $str<br>";
echo "$str → " . $cip->bstrToB10($str, "lowercase") . "<br><br>";

// --- NUM ---
$num = $cip->b10ToBstr($id, "uppercase");
echo "123 → NUM: $num<br>";
echo "$num → " . $cip->bstrToB10($num, "uppercase") . "<br><br>";

// --- MIX ---
$mix = $cip->b10ToBstr($id, "mixed");
echo "123 → MIX: $mix<br>";
echo "$mix → " . $cip->bstrToB10($mix, "mixed") . "<br><br>";

// ==========================================================
// 🚀 PRUEBAS EXTRA: otros números
// ==========================================================
$values = [0, 1, 42, 2025, 46789123];
foreach ($values as $v) {
  echo "Number: $v<br>";
  echo "  Lowercase: " . $cip->b10ToBstr($v, "lowercase") . "<br>";
  echo "  Uppercase: " . $cip->b10ToBstr($v, "uppercase") . "<br>";
  echo "  Numbers: " . $cip->b10ToBstr($v, "numbers") . "<br>";
  echo "  Mixed: " . $cip->b10ToBstr($v, "mixed") . "<br><br>";
}
