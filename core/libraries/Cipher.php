<?php

/**
 * Cipher
 *
 * Clase encargada del cifrado y descifrado de información sensible.
 * Proporciona métodos para proteger datos mediante algoritmos de cifrado simétrico,
 * hashing de contraseñas y conversiones de base.
 *
 * @author Pirulug
 * @link   https://github.com/pirulug
 */
class Cipher {
  // --------------------------------------------------------------------------
  // PROPIEDADES DE CONFIGURACIÓN
  // --------------------------------------------------------------------------

  private string $method;
  private string $secretKey;
  private string $secretIv;

  private array $alphabets = [
    'lowercase' => 'abcdefghijklmnopqrstuvwxyz',
    'uppercase' => 'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
    'numbers'   => '0123456789',
    'mixed'     => 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'
  ];

  // --------------------------------------------------------------------------
  // SECCIÓN: CONFIGURACIÓN (FLUENT API)
  // --------------------------------------------------------------------------

  /**
   * Define el método de cifrado (ej: aes-256-cbc).
   *
   * @param string $method Algoritmo soportado por OpenSSL.
   * @return self Instancia.
   */
  public function method(string $method): self {
    $method = strtolower($method);

    if (!in_array($method, openssl_get_cipher_methods(), true)) {
      throw new InvalidArgumentException('Método de cifrado no soportado: ' . $method);
    }

    $this->method = $method;
    return $this;
  }

  /**
   * Define la llave secreta para el cifrado.
   *
   * @param string $key Llave cruda.
   * @return self Instancia.
   */
  public function secretkey(string $key): self {
    $this->secretKey = hash('sha256', $key, true);
    return $this;
  }

  /**
   * Define el vector de inicialización (IV).
   *
   * @param string $iv Vector de 16 bytes.
   * @return self Instancia.
   */
  public function secretiv(string $iv): self {
    if (strlen($iv) !== 16) {
      throw new InvalidArgumentException('El IV debe tener 16 bytes.');
    }

    $this->secretIv = $iv;
    return $this;
  }

  /**
   * Valida que el cifrador tenga todos los parámetros necesarios.
   */
  private function validateCipher(): void {
    if (!$this->method || !$this->secretKey || !$this->secretIv) {
      throw new RuntimeException('Cipher no configurado correctamente.');
    }
  }

  // --------------------------------------------------------------------------
  // SECCIÓN: CIFRADO Y DESCIFRADO
  // --------------------------------------------------------------------------

  /**
   * Cifra un texto plano y lo devuelve en formato Base64.
   *
   * @param string $plainText Texto original.
   * @return string Texto cifrado.
   */
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

  /**
   * Descifra un texto en Base64.
   *
   * @param string $cipherText Texto cifrado en Base64.
   * @return string|false Texto original o false en caso de error.
   */
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

  // --------------------------------------------------------------------------
  // SECCIÓN: CONVERSIÓN DE BASES
  // --------------------------------------------------------------------------

  /**
   * Convierte un número decimal a una cadena en base personalizada.
   *
   * @param int $number Número a convertir.
   * @param string $mode Alfabeto (lowercase, uppercase, numbers, mixed).
   * @return string Cadena resultante.
   */
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

  /**
   * Convierte una cadena en base personalizada a número decimal.
   *
   * @param string $str Cadena a convertir.
   * @param string $mode Alfabeto utilizado.
   * @return int Número decimal resultante.
   */
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

  // --------------------------------------------------------------------------
  // SECCIÓN: HASHING Y SEGURIDAD DE CONTRASEÑAS
  // --------------------------------------------------------------------------

  /**
   * Genera un hash criptográfico.
   *
   * @param string $value Valor a hashear.
   * @param string $algo Algoritmo (sha256 por defecto).
   * @return string Hash generado.
   */
  public function hash(string $value, string $algo = 'sha256'): string {
    if (!in_array($algo, hash_algos(), true)) {
      throw new InvalidArgumentException('Algoritmo hash no soportado.');
    }

    return hash($algo, $value);
  }

  /**
   * Verifica la integridad de un hash mediante comparación segura.
   *
   * @param string $value Valor original.
   * @param string $hash Hash a comparar.
   * @param string $algo Algoritmo utilizado.
   * @return bool True si coinciden.
   */
  public function verifyHash(string $value, string $hash, string $algo = 'sha256'): bool {
    return hash_equals(hash($algo, $value), $hash);
  }

  /**
   * Genera un hash seguro para contraseñas usando algoritmos del sistema.
   *
   * @param string $password Contraseña en texto plano.
   * @return string Hash generado.
   */
  public function password(string $password): string {
    return password_hash($password, PASSWORD_DEFAULT);
  }

  /**
   * Verifica una contraseña contra su hash.
   *
   * @param string $password Contraseña en texto plano.
   * @param string $hash Hash almacenado.
   * @return bool True si es válida.
   */
  public function verifyPassword(string $password, string $hash): bool {
    return password_verify($password, $hash);
  }

  /**
   * Comprueba si el hash de la contraseña requiere ser regenerado.
   */
  public function needsRehash(string $hash): bool {
    return password_needs_rehash($hash, PASSWORD_DEFAULT);
  }
}