<?php

class Encryption {
  private string $method;
  private string $secretKey;
  private string $secretIv;

  /**
   * Constructor to initialize encryption settings.
   *
   * @param string $method The encryption method (default is 'AES-256-CBC').
   * @param string $secretKey The secret key for encryption (default is a pre-defined key).
   * @param string $secretIv The initialization vector for encryption (default is a pre-defined IV).
   */
  public function __construct(
    string $method = 'AES-256-CBC',
    string $secretKey = '$STARTPHP@2024PIRU',
    string $secretIv = '456232132132432234132'
  ) {
    $this->method    = $method;
    $this->secretKey = hash('sha256', $secretKey);
    $this->secretIv  = substr(hash('sha256', $secretIv), 0, 16);
  }

  /**
   * Encrypt a string using the specified method.
   *
   * @param string $string The string to encrypt.
   * @return string The encrypted string.
   * @throws Exception If encryption fails.
   */
  public function encrypt(string $string): string {
    $encrypted = openssl_encrypt($string, $this->method, $this->secretKey, 0, $this->secretIv);

    if ($encrypted === false) {
      throw new Exception('Encryption failed.');
    }

    return base64_encode($encrypted);
  }

  /**
   * Decrypt a string using the specified method.
   *
   * @param string $string The encrypted string.
   * @return string The decrypted string.
   * @throws Exception If decryption fails.
   */
  public function decrypt(string $string): string {
    $decrypted = openssl_decrypt(base64_decode($string), $this->method, $this->secretKey, 0, $this->secretIv);

    if ($decrypted === false) {
      throw new Exception('Decryption failed.');
    }

    return $decrypted;
  }
}
