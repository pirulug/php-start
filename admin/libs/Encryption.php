<?php

class Encryption {
  /**
   * Encrypt a string using AES-256-CBC method.
   *
   * @param string $string The string to encrypt.
   * @return string The encrypted string.
   * @throws Exception If encryption fails.
   */
  public static function encrypt(string $string): string {
    $key       = hash('sha256', SECRET_KEY);
    $iv        = substr(hash('sha256', SECRET_IV), 0, 16);
    $encrypted = openssl_encrypt($string, METHOD, $key, 0, $iv);

    if ($encrypted === false) {
      throw new Exception('Encryption failed.');
    }

    return base64_encode($encrypted);
  }

  /**
   * Decrypt a string using AES-256-CBC method.
   *
   * @param string $string The encrypted string.
   * @return string The decrypted string.
   * @throws Exception If decryption fails.
   */
  public static function decrypt(string $string): string {
    $key       = hash('sha256', SECRET_KEY);
    $iv        = substr(hash('sha256', SECRET_IV), 0, 16);
    $decrypted = openssl_decrypt(base64_decode($string), METHOD, $key, 0, $iv);

    if ($decrypted === false) {
      throw new Exception('Decryption failed.');
    }

    return $decrypted;
  }
}
