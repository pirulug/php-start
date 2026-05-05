<?php

/**
 * Captcha
 *
 * Clase encargada de la generación de imágenes de captcha dinámicas.
 * Permite configurar dimensiones, longitud del código, estilo del fondo y
 * tipos de caracteres. Utiliza la librería GD para el renderizado.
 *
 * @author Pirulug
 * @link   https://github.com/pirulug
 */
class Captcha {
  // --------------------------------------------------------------------------
  // PROPIEDADES DE CONFIGURACIÓN
  // --------------------------------------------------------------------------

  private int $width = 200;
  private int $height = 80;
  private ?string $code = null;
  private int $codeLength = 6;
  private string $font = "C:\Windows\Fonts\arial.ttf";
  private string $sessionKey = 'fluid_captcha';
  private string $backgroundType = 'grid';
  private string $characterType = 'alphanumeric';

  /**
   * Inicializa la clase asegurando que la sesión esté activa.
   */
  public function __construct() {
    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }
  }

  // --------------------------------------------------------------------------
  // SECCIÓN: CONFIGURACIÓN (FLUENT API)
  // --------------------------------------------------------------------------

  /**
   * Define el ancho de la imagen generada.
   *
   * @param int $width Ancho en píxeles.
   * @return self Instancia.
   */
  public function width(int $width): self {
    $this->width = $width;
    return $this;
  }

  /**
   * Define el alto de la imagen generada.
   *
   * @param int $height Alto en píxeles.
   * @return self Instancia.
   */
  public function height(int $height): self {
    $this->height = $height;
    return $this;
  }

  /**
   * Define la longitud del código de verificación.
   *
   * @param int $length Cantidad de caracteres.
   * @return self Instancia.
   */
  public function codeLength(int $length): self {
    $this->codeLength = $length;
    return $this;
  }

  /**
   * Define la llave de sesión donde se almacenará el código.
   *
   * @param string $key Nombre de la llave.
   * @return self Instancia.
   */
  public function sessionKey(string $key): self {
    $this->sessionKey = $key;
    return $this;
  }

  /**
   * Define el tipo de ruido de fondo.
   *
   * @param string $type Estilo (grid, lines, dots).
   * @return self Instancia.
   */
  public function background(string $type): self {
    $valid                = ['grid', 'lines', 'dots'];
    $this->backgroundType = in_array($type, $valid, true) ? $type : 'grid';
    return $this;
  }

  /**
   * Define la ruta física de la fuente TTF.
   *
   * @param string $font Ruta al archivo .ttf.
   * @return self Instancia.
   */
  public function font(string $font): self {
    $this->font = $font;
    return $this;
  }

  /**
   * Configura el generador para usar solo números.
   *
   * @return self Instancia.
   */
  public function number(): self {
    $this->characterType = 'number';
    return $this;
  }

  /**
   * Configura el generador para usar solo letras.
   *
   * @return self Instancia.
   */
  public function letter(): self {
    $this->characterType = 'letter';
    return $this;
  }

  /**
   * Configura el generador para usar caracteres alfanuméricos.
   *
   * @return self Instancia.
   */
  public function alphanumeric(): self {
    $this->characterType = 'alphanumeric';
    return $this;
  }

  // --------------------------------------------------------------------------
  // SECCIÓN: GENERACIÓN Y RENDERIZADO
  // --------------------------------------------------------------------------

  /**
   * Inicia el proceso de generación de imagen y salida al navegador.
   * Finaliza la ejecución del script tras renderizar.
   */
  public function generate(): void {
    $this->code                  = $this->generateCode();
    $_SESSION[$this->sessionKey] = $this->code;

    if (ob_get_length()) {
      ob_clean();
    }

    $this->createWithGD();
    exit;
  }

  /**
   * Lógica interna de renderizado usando GD.
   */
  private function createWithGD(): void {
    $img = imagecreatetruecolor($this->width, $this->height);
    $bg  = imagecolorallocate($img, 255, 255, 255);
    imagefill($img, 0, 0, $bg);

    $this->drawBackgroundGD($img);

    $font      = $this->getFont();
    $charWidth = $this->width / $this->codeLength;

    for ($i = 0; $i < $this->codeLength; $i++) {
      $rgb   = $this->getRandomColorRGB("bright");
      $color = imagecolorallocate($img, $rgb['r'], $rgb['g'], $rgb['b']);
      $angle = random_int(-20, 20);

      if ($font) {
        $fontSize   = random_int(24, 32);
        $bbox       = imagettfbbox($fontSize, $angle, $font, $this->code[$i]);
        $charHeight = $bbox[1] - $bbox[7];
        $y          = ($this->height + $charHeight) / 2;
        $x          = ($i * $charWidth) + ($charWidth - ($bbox[2] - $bbox[0])) / 2;

        imagettftext($img, $fontSize, $angle, (int) $x, (int) $y, $color, $font, $this->code[$i]);
      } else {
        $x = ($i * $charWidth) + 10;
        $y = ($this->height - 15) / 2;
        imagestring($img, 5, (int) $x, (int) $y, $this->code[$i], $color);
      }
    }

    header('Content-Type: image/webp');
    imagewebp($img);
    imagedestroy($img);
  }

  /**
   * Genera el código aleatorio según la configuración de caracteres.
   *
   * @return string Código generado.
   */
  private function generateCode(): string {
    switch ($this->characterType) {
      case 'number':
        $chars = '23456789';
        break;
      case 'letter':
        $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ';
        break;
      default:
        $chars = '23456789ABCDEFGHJKLMNPQRSTUVWXYZ';
    }

    $code = '';
    for ($i = 0; $i < $this->codeLength; $i++) {
      $code .= $chars[random_int(0, strlen($chars) - 1)];
    }

    return $code;
  }

  /**
   * Dibuja patrones de ruido sobre el lienzo de la imagen.
   *
   * @param GdImage $img Recurso de imagen.
   */
  private function drawBackgroundGD($img): void {
    $rgb   = $this->getRandomColorRGB("pastel");
    $color = imagecolorallocate($img, $rgb['r'], $rgb['g'], $rgb['b']);

    if ($this->backgroundType === 'grid') {
      for ($x = 0; $x <= $this->width; $x += 15) {
        imageline($img, $x, 0, $x, $this->height, $color);
      }
      for ($y = 0; $y <= $this->height; $y += 15) {
        imageline($img, 0, $y, $this->width, $y, $color);
      }
    } elseif ($this->backgroundType === 'lines') {
      $lineSpacing = 8;
      for ($y = 0; $y <= $this->height; $y += $lineSpacing) {
        imageline($img, 0, $y, $this->width, $y, $color);
      }
    } elseif ($this->backgroundType === 'dots') {
      $dotSpacing = 10;
      for ($x = 0; $x <= $this->width; $x += $dotSpacing) {
        for ($y = 0; $y <= $this->height; $y += $dotSpacing) {
          imagefilledellipse($img, $x, $y, 2, 2, $color);
        }
      }
    }
  }

  /**
   * Verifica la existencia de la fuente configurada.
   *
   * @return string|false Ruta de la fuente o false.
   */
  private function getFont(): string|false {
    return file_exists($this->font) ? $this->font : false;
  }

  // --------------------------------------------------------------------------
  // SECCIÓN: VALIDACIÓN (ESTÁTICA)
  // --------------------------------------------------------------------------

  /**
   * Valida un código de captcha contra la sesión.
   * Limpia el código de la sesión tras la validación.
   *
   * @param string $input Código enviado por el usuario.
   * @param string $key Llave de sesión utilizada.
   * @return bool True si coinciden.
   */
  public static function validate(string $input, string $key = 'fluid_captcha'): bool {
    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }

    if (!isset($_SESSION[$key])) {
      return false;
    }

    $valid = strtoupper($input) === $_SESSION[$key];
    unset($_SESSION[$key]);

    return $valid;
  }

  // --------------------------------------------------------------------------
  // SECCIÓN: UTILIDADES DE COLOR
  // --------------------------------------------------------------------------

  /**
   * Genera un color aleatorio en formato RGB.
   *
   * @param string $style Estilo de color (pastel, bright, normal).
   * @return array Mapa RGB [r, g, b].
   */
  private function getRandomColorRGB(string $style = 'normal'): array {
    if ($style === 'pastel') {
      $r = mt_rand(128, 220);
      $g = mt_rand(128, 220);
      $b = mt_rand(128, 220);
    } elseif ($style === "bright") {
      $colors      = [[255, 0, 0], [0, 255, 0], [0, 0, 255], [0, 0, 0]];
      [$r, $g, $b] = $colors[array_rand($colors)];
    } else {
      $r = mt_rand(0, 200);
      $g = mt_rand(0, 200);
      $b = mt_rand(0, 200);
    }

    return ['r' => $r, 'g' => $g, 'b' => $b];
  }
}