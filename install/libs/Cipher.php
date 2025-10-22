<?php

class Cipher {
  private string $method;
  private string $secretKey;
  private string $secretIv;

  private array $alphabets = [
    'lowercase' => 'abcdefghijklmnopqrstuvwxyz',
    'uppercase' => 'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
    'numbers'   => '0123456789',
    'mixed'     => 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'
  ];
  public function __construct(
    string $method = 'AES-256-CBC',
    string $secretKey = '$STARTPHP@2024PIRU',
    string $secretIv = '456232132132432234132'
  ) {
    $this->method    = $method;
    $this->secretKey = hash('sha256', $secretKey);
    $this->secretIv  = substr(hash('sha256', $secretIv), 0, 16);
  }

  /* ============================================================
   * CIFRADO Y DESCIFRADO
   * ============================================================ */
  public function encrypt(string $plainText): string {
    $encrypted = openssl_encrypt($plainText, $this->method, $this->secretKey, 0, $this->secretIv);
    if ($encrypted === false)
      throw new Exception('Encryption failed.');
    return base64_encode($encrypted);
  }

  public function decrypt(string $cipherText): string {
    $decoded   = base64_decode($cipherText, true);
    $decrypted = openssl_decrypt($decoded, $this->method, $this->secretKey, 0, $this->secretIv);
    // if ($decrypted === false)
      // throw new Exception('Decryption failed.');
    return $decrypted;
  }

  /* ============================================================
   * BASE10 <-> BASESTRING conversion (lowercase, uppercase, numbers, mixed)
   * ============================================================ */
  public function b10ToBstr(int $number, string $mode = 'lowercase'): string {
    if (!isset($this->alphabets[$mode])) {
      throw new InvalidArgumentException("Invalid mode: $mode (use 'lowercase', 'uppercase', 'numbers' or 'mixed')");
    }

    $alphabet = $this->alphabets[$mode];
    $base     = strlen($alphabet);

    if ($number < 0) {
      throw new InvalidArgumentException("Number must be positive.");
    }

    if ($number === 0) {
      return $alphabet[0];
    }

    $result = '';
    while ($number > 0) {
      $result = $alphabet[$number % $base] . $result;
      $number = intdiv($number, $base);
    }
    return $result;
  }

  public function bstrToB10(string $str, string $mode = 'lowercase'): int {
    if (!isset($this->alphabets[$mode])) {
      throw new InvalidArgumentException("Invalid mode: $mode (use 'lowercase', 'uppercase', 'numbers' or 'mixed')");
    }

    $alphabet = $this->alphabets[$mode];
    $base     = strlen($alphabet);
    $length   = strlen($str);
    $number   = 0;

    for ($i = 0; $i < $length; $i++) {
      $char = $str[$i];
      $pos  = strpos($alphabet, $char);
      if ($pos === false) {
        throw new InvalidArgumentException("Invalid character: '$char'");
      }
      $number = $number * $base + $pos;
    }

    return $number;
  }

  /* ============================================================
   * HASHING (Configurable)
   * ============================================================ */
  public function hash(string $string, string $algo = 'sha256'): string {
    $hash = hash($algo, $string);
    return $hash;
  }

  public function verifyHash(string $string, string $hash, string $algo = 'sha256'): bool {
    return hash($algo, $string) === $hash;
  }
}
