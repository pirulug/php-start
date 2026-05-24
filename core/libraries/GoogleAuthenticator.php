<?php

/**
 * GoogleAuthenticator
 *
 * Clase para generación y verificación de claves de Autenticación de Dos Factores (2FA)
 * compatibles con Google Authenticator y similares, utilizando el estándar TOTP (RFC 6238).
 *
 * @author Pirulug
 */
class GoogleAuthenticator {
  private int $codeLength = 6;

  /**
   * Genera un secreto aleatorio de 16 caracteres en Base32.
   *
   * @return string
   */
  public function createSecret(): string {
    $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
    $secret = '';
    for ($i = 0; $i < 16; $i++) {
      $secret .= $chars[random_int(0, 31)];
    }
    return $secret;
  }

  /**
   * Calcula el código TOTP para un secreto y una ranura de tiempo.
   *
   * @param string $secret Secreto en Base32.
   * @param int|null $timeSlice Ranura de tiempo (timestamp / 30).
   * @return string Código de 6 dígitos.
   */
  public function getCode(string $secret, ?int $timeSlice = null): string {
    if ($timeSlice === null) {
      $timeSlice = floor(time() / 30);
    }

    $secretKey = $this->base32Decode($secret);

    // Empaquetar la ranura de tiempo en formato binario de 64 bits de red (big-endian)
    $timeBin = pack('N*', 0) . pack('N*', $timeSlice);

    // Generar HMAC-SHA1
    $hmac = hash_hmac('sha1', $timeBin, $secretKey, true);

    // Truncamiento dinámico (RFC 4226)
    $offset = ord(substr($hmac, -1)) & 0x0F;
    $hashpart = substr($hmac, $offset, 4);

    // Convertir a número entero de 32 bits
    $value = unpack('N', $hashpart);
    $value = $value[1];
    $value = $value & 0x7FFFFFFF;

    $modulo = 10 ** $this->codeLength;
    $code = $value % $modulo;

    return str_pad((string)$code, $this->codeLength, '0', STR_PAD_LEFT);
  }

  /**
   * Obtiene la URL de aprovisionamiento para el código QR.
   *
   * @param string $user Nombre del usuario.
   * @param string $secret Secreto en Base32.
   * @param string $title Título del emisor (sitio).
   * @return string URL otpauth.
   */
  public function getQrCodeUrl(string $user, string $secret, string $title): string {
    return 'otpauth://totp/' . rawurlencode($title . ':' . $user) . '?secret=' . $secret . '&issuer=' . rawurlencode($title);
  }

  /**
   * Verifica si un código proporcionado es válido para un secreto.
   * Permite un margen de tolerancia para desfase de reloj (discrepancy).
   *
   * @param string $secret Secreto en Base32.
   * @param string $code Código de 6 dígitos ingresado.
   * @param int $discrepancy Margen de desfase (1 equivale a +/- 30 segundos).
   * @return bool True si es válido.
   */
  public function verifyCode(string $secret, string $code, int $discrepancy = 1): bool {
    $currentTimeSlice = floor(time() / 30);

    for ($i = -$discrepancy; $i <= $discrepancy; $i++) {
      $calculatedCode = $this->getCode($secret, $currentTimeSlice + $i);
      if (hash_equals($calculatedCode, $code)) {
        return true;
      }
    }

    return false;
  }

  /**
   * Decodifica una cadena Base32.
   *
   * @param string $base32 Cadena a decodificar.
   * @return string Binario decodificado.
   */
  private function base32Decode(string $base32): string {
    $base32 = strtoupper($base32);
    $allowedChars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
    
    // Eliminar caracteres de relleno '='
    $base32 = rtrim($base32, '=');
    $base32Len = strlen($base32);
    
    $binaryString = '';
    for ($i = 0; $i < $base32Len; $i++) {
      $pos = strpos($allowedChars, $base32[$i]);
      if ($pos === false) {
        continue; // Ignorar caracteres no válidos
      }
      $binaryString .= str_pad(decbin($pos), 5, '0', STR_PAD_LEFT);
    }
    
    $octets = str_split($binaryString, 8);
    $decoded = '';
    foreach ($octets as $octet) {
      if (strlen($octet) === 8) {
        $decoded .= chr(bindec($octet));
      }
    }
    
    return $decoded;
  }
}
