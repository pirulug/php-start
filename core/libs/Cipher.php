<?php
/**
 * Cipher
 *
 * Clase encargada del cifrado y descifrado de información sensible.
 * Proporciona métodos para proteger datos mediante algoritmos de
 * cifrado simétrico, garantizando confidencialidad e integridad.
 *
 * Permite gestionar claves, vectores de inicialización y
 * formatos de salida de forma segura.
 *
 * @author Pirulug
 * @link   https://github.com/pirulug
 */
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

  public function method(string $method): self {
    $method = strtolower($method);

    if (!in_array($method, openssl_get_cipher_methods(), true)) {
      throw new InvalidArgumentException('Método de cifrado no soportado: ' . $method);
    }

    $this->method = $method;
    return $this;
  }

  public function secretkey(string $key): self {
    $this->secretKey = hash('sha256', $key, true);
    return $this;
  }

  public function secretiv(string $iv): self {
    if (strlen($iv) !== 16) {
      throw new InvalidArgumentException('El IV debe tener 16 bytes.');
    }

    $this->secretIv = $iv;
    return $this;
  }

  private function validateCipher(): void {
    if (!$this->method || !$this->secretKey || !$this->secretIv) {
      throw new RuntimeException('Cipher no configurado correctamente.');
    }
  }

  public function encrypt(string $plainText): string {
    $this->validateCipher();

    $encrypted = openssl_encrypt(
      $plainText,
      $this->method,
      $this->secretKey,
      0,
      $this->secretIv
    );

    if ($encrypted === false) {
      throw new RuntimeException('Error al cifrar.');
    }

    return base64_encode($encrypted);
  }

  public function decrypt(string $cipherText): string|false {
    $this->validateCipher();

    $decoded = base64_decode($cipherText, true);
    if ($decoded === false) {
      return false;
    }

    return openssl_decrypt(
      $decoded,
      $this->method,
      $this->secretKey,
      0,
      $this->secretIv
    );
  }

  public function b10ToBstr(int $number, string $mode = 'lowercase'): string {
    if (!isset($this->alphabets[$mode])) {
      throw new InvalidArgumentException('Modo inválido.');
    }

    if ($number === 0) {
      return $this->alphabets[$mode][0];
    }

    $alphabet = $this->alphabets[$mode];
    $base     = strlen($alphabet);
    $result   = '';

    while ($number > 0) {
      $result = $alphabet[$number % $base] . $result;
      $number = intdiv($number, $base);
    }

    return $result;
  }

  public function bstrToB10(string $str, string $mode = 'lowercase'): int {
    if (!isset($this->alphabets[$mode])) {
      throw new InvalidArgumentException('Modo inválido.');
    }

    $alphabet = $this->alphabets[$mode];
    $base     = strlen($alphabet);
    $number   = 0;

    foreach (str_split($str) as $char) {
      $pos = strpos($alphabet, $char);
      if ($pos === false) {
        throw new InvalidArgumentException('Carácter inválido.');
      }
      $number = ($number * $base) + $pos;
    }

    return $number;
  }

  public function hash(string $value, string $algo = 'sha256'): string {
    if (!in_array($algo, hash_algos(), true)) {
      throw new InvalidArgumentException('Algoritmo hash no soportado.');
    }

    return hash($algo, $value);
  }

  public function verifyHash(string $value, string $hash, string $algo = 'sha256'): bool {
    return hash_equals(hash($algo, $value), $hash);
  }

  public function password(string $password): string {
    return password_hash($password, PASSWORD_DEFAULT);
  }

  public function verifyPassword(string $password, string $hash): bool {
    return password_verify($password, $hash);
  }

  public function needsRehash(string $hash): bool {
    return password_needs_rehash($hash, PASSWORD_DEFAULT);
  }
}
