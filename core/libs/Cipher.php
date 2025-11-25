<?php

/**
 * Clase Cipher
 * ----------------------------------------------------------
 * Proporciona métodos para:
 * - Cifrar y descifrar textos usando OpenSSL.
 * - Convertir números base 10 a cadenas personalizadas y viceversa.
 * - Generar y verificar hashes.
 *
 * Incluye soporte para configuración con Fluent Interface:
 *   $cipher = (new Cipher())->method()->secretkey()->secretiv();
 */
class Cipher {

  /** @var string Algoritmo de cifrado OpenSSL */
  private string $method;

  /** @var string Clave secreta (procesada con SHA-256) */
  private string $secretKey;

  /** @var string IV secreto de 16 bytes */
  private string $secretIv;

  /**
   * Conjuntos de caracteres usados para las conversiones Base10 <-> BaseString.
   *
   * @var array<string,string>
   */
  private array $alphabets = [
    'lowercase' => 'abcdefghijklmnopqrstuvwxyz',
    'uppercase' => 'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
    'numbers'   => '0123456789',
    'mixed'     => 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'
  ];

  /**
   * Constructor
   *
   * @param string $method Algoritmo de cifrado (AES-256-CBC por defecto)
   * @param string $secretKey Llave secreta en texto plano
   * @param string $secretIv IV en texto plano
   *
   * Procesa la clave e IV para cumplir con los requisitos de OpenSSL.
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



  /* ============================================================
   * MÉTODOS FLUENT (method chaining)
   * ============================================================ */

  /**
   * Establecer el método de cifrado.
   *
   * @param string $method
   * @return self
   */
  public function method(string $method): self {
    $this->method = $method;
    return $this;
  }

  /**
   * Establecer la clave secreta.
   *
   * La clave se convierte automáticamente con sha256.
   *
   * @param string $key
   * @return self
   */
  public function secretkey(string $key): self {
    $this->secretKey = hash('sha256', $key);
    return $this;
  }

  /**
   * Establecer el IV secreto.
   *
   * OpenSSL requiere un IV de 16 bytes.
   *
   * @param string $iv
   * @return self
   */
  public function secretiv(string $iv): self {
    $this->secretIv = substr(hash('sha256', $iv), 0, 16);
    return $this;
  }



  /* ============================================================
   * CIFRADO Y DESCIFRADO
   * ============================================================ */

  /**
   * Cifra un texto plano usando OpenSSL.
   *
   * @param string $plainText
   * @return string Texto cifrado en Base64
   * @throws Exception
   */
  public function encrypt(string $plainText): string {
    $encrypted = openssl_encrypt($plainText, $this->method, $this->secretKey, 0, $this->secretIv);

    if ($encrypted === false)
      throw new Exception('Error al cifrar el texto.');

    return base64_encode($encrypted);
  }

  /**
   * Descifra un texto cifrado por encrypt().
   *
   * @param string $cipherText Texto en Base64
   * @return string Texto descifrado
   */
  public function decrypt(string $cipherText): string {
    $decoded   = base64_decode($cipherText, true);
    $decrypted = openssl_decrypt($decoded, $this->method, $this->secretKey, 0, $this->secretIv);
    return $decrypted;
  }



  /* ============================================================
   * CONVERSIÓN BASE 10 <-> BASE STRING
   * ============================================================ */

  /**
   * Convierte un número base 10 a una cadena basada en un alfabeto.
   *
   * @param int $number Número entero positivo
   * @param string $mode Modo del alfabeto (lowercase, uppercase, numbers, mixed)
   * @return string Cadena convertida
   * @throws InvalidArgumentException
   */
  public function b10ToBstr(int $number, string $mode = 'lowercase'): string {
    if (!isset($this->alphabets[$mode])) {
      throw new InvalidArgumentException("Modo inválido: $mode");
    }

    $alphabet = $this->alphabets[$mode];
    $base     = strlen($alphabet);

    if ($number < 0) {
      throw new InvalidArgumentException("El número debe ser positivo.");
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

  /**
   * Convierte una cadena en base personalizada a un número base 10.
   *
   * @param string $str Cadena de entrada
   * @param string $mode Alfabeto usado en la cadena
   * @return int Número convertido
   * @throws InvalidArgumentException
   */
  public function bstrToB10(string $str, string $mode = 'lowercase'): int {
    if (!isset($this->alphabets[$mode])) {
      throw new InvalidArgumentException("Modo inválido: $mode");
    }

    $alphabet = $this->alphabets[$mode];
    $base     = strlen($alphabet);
    $length   = strlen($str);
    $number   = 0;

    for ($i = 0; $i < $length; $i++) {
      $char = $str[$i];
      $pos  = strpos($alphabet, $char);
      if ($pos === false) {
        throw new InvalidArgumentException("Carácter no válido: '$char'");
      }
      $number = $number * $base + $pos;
    }

    return $number;
  }



  /* ============================================================
   * HASHING
   * ============================================================ */

  /**
   * Genera un hash usando el algoritmo especificado.
   *
   * @param string $string
   * @param string $algo
   * @return string
   */
  public function hash(string $string, string $algo = 'sha256'): string {
    return hash($algo, $string);
  }

  /**
   * Verifica que un texto coincida con un hash dado.
   *
   * @param string $string Texto original
   * @param string $hash Hash a comparar
   * @param string $algo Algoritmo
   * @return bool
   */
  public function verifyHash(string $string, string $hash, string $algo = 'sha256'): bool {
    return hash($algo, $string) === $hash;
  }
}
