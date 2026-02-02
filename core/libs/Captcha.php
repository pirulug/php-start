<?php

/**
 * Captcha
 *
 * @author Pirulug
 * @link   https://github.com/pirulug
 */

class Captcha {
  private $width = 200;
  private $height = 80;
  private $code;
  private $codeLength = 6;
  private $font = "C:\Windows\Fonts\arial.ttf";
  private $sessionKey = 'fluid_captcha';
  private $backgroundType = 'grid'; // grid | lines | dots
  private $characterType = 'alphanumeric'; // alphanumeric | letter | number

  public function __construct() {
    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }
  }

  public function width($width) {
    $this->width = (int) $width;
    return $this;
  }

  public function height($height) {
    $this->height = (int) $height;
    return $this;
  }

  public function codeLength($length) {
    $this->codeLength = (int) $length;
    return $this;
  }

  public function sessionKey($key) {
    $this->sessionKey = $key;
    return $this;
  }

  public function background($type) {
    $valid                = ['grid', 'lines', 'dots'];
    $this->backgroundType = in_array($type, $valid, true) ? $type : 'grid';
    return $this;
  }

  public function font($font) {
    $this->font = $font;
    return $this;
  }

  public function number() {
    $this->characterType = 'number';
    return $this;
  }

  public function letter() {
    $this->characterType = 'letter';
    return $this;
  }

  public function alphanumeric() {
    $this->characterType = 'alphanumeric';
    return $this;
  }

  private function generateCode() {
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

  public function generate() {
    $this->code                  = $this->generateCode();
    $_SESSION[$this->sessionKey] = $this->code;

    if (ob_get_length()) {
      ob_clean();
    }

    $this->createWithGD();

    exit;
  }

  private function createWithGD() {
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
        $charHeight = $bbox[1] - $bbox[7]; // altura real del texto
        $y          = ($this->height + $charHeight) / 2;

        $x = ($i * $charWidth) + ($charWidth - ($bbox[2] - $bbox[0])) / 2;

        imagettftext(
          $img,
          $fontSize,
          $angle,
          (int) $x,
          (int) $y,
          $color,
          $font,
          $this->code[$i]
        );
      } else {
        // fallback a imagestring si no hay fuente TTF
        $x = ($i * $charWidth) + 10;
        $y = ($this->height - 15) / 2;
        imagestring($img, 5, (int) $x, (int) $y, $this->code[$i], $color);
      }
    }

    imagepng($img);
    imagedestroy($img);
  }

  private function drawBackgroundGD($img) {
    $rgb   = $this->getRandomColorRGB("pastel");
    $color = imagecolorallocate($img, $rgb['r'], $rgb['g'], $rgb['b']);

    if ($this->backgroundType === 'grid') {
      // Dibujar cuadrícula (líneas verticales y horizontales)
      for ($x = 0; $x <= $this->width; $x += 15) {
        imageline($img, $x, 0, $x, $this->height, $color);
      }
      for ($y = 0; $y <= $this->height; $y += 15) {
        imageline($img, 0, $y, $this->width, $y, $color);
      }
    } elseif ($this->backgroundType === 'lines') {
      // Dibujar solo líneas horizontales
      $lineSpacing = 8;
      for ($y = 0; $y <= $this->height; $y += $lineSpacing) {
        imageline($img, 0, $y, $this->width, $y, $color);
      }
    } elseif ($this->backgroundType === 'dots') {
      // Dibujar puntos
      $dotSpacing = 10;
      for ($x = 0; $x <= $this->width; $x += $dotSpacing) {
        for ($y = 0; $y <= $this->height; $y += $dotSpacing) {
          imagefilledellipse($img, $x, $y, 2, 2, $color);
        }
      }
    }
  }

  private function getFont() {
    $font = $this->font;

    if (file_exists($font)) {
      return $font;
    }

    return false;
  }

  public static function validate($input, $key = 'fluid_captcha') {
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

  private function getRandomColorRGB(string $style = 'normal'): array {
    if ($style === 'pastel') {
      $r = mt_rand(128, 220);
      $g = mt_rand(128, 220);
      $b = mt_rand(128, 220);
    } elseif ($style === "bright") {
      $colors      = [
        [255, 0, 0],
        [0, 255, 0],
        [0, 0, 255],
        [0, 0, 0],
      ];
      [$r, $g, $b] = $colors[array_rand($colors)];
    } else {
      $r = mt_rand(0, 200);
      $g = mt_rand(0, 200);
      $b = mt_rand(0, 200);
    }

    return ['r' => $r, 'g' => $g, 'b' => $b];
  }
}