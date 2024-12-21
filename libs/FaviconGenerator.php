<?php

class FaviconGenerator {
  private string $uploadDir;

  public function __construct(string $uploadDir) {
    $this->uploadDir = rtrim($uploadDir, '/') . '/';

    // Crear directorio si no existe
    if (!is_dir($this->uploadDir)) {
      mkdir($this->uploadDir, 0777, true);
    }
  }

  public function generate(string $sourceFile): void {
    // Verificar si el archivo existe y es PNG
    if (!file_exists($sourceFile) || mime_content_type($sourceFile) !== 'image/png') {
      throw new Exception("El archivo debe ser un PNG válido.");
    }

    // Cargar la imagen base
    $sourceImage = @imagecreatefrompng($sourceFile);
    if (!$sourceImage) {
      throw new Exception("No se pudo cargar el archivo PNG.");
    }

    // Configurar transparencia en la imagen original
    imagesavealpha($sourceImage, true);

    // Generar iconos
    $this->resizeAndSave($sourceImage, 192, 192, 'android-chrome-192x192.png');
    $this->resizeAndSave($sourceImage, 512, 512, 'android-chrome-512x512.png');
    $this->resizeAndSave($sourceImage, 180, 180, 'apple-touch-icon.png');
    $this->resizeAndSave($sourceImage, 16, 16, 'favicon-16x16.png');
    $this->resizeAndSave($sourceImage, 32, 32, 'favicon-32x32.png');
    $this->generateIco($sourceImage);
    $this->generateWebManifest();

    imagedestroy($sourceImage);

    echo "Favicon generado exitosamente. Archivos disponibles en '{$this->uploadDir}'.";
  }

  private function resizeAndSave($sourceImage, int $width, int $height, string $filename): void {
    // Crear una nueva imagen con transparencia
    $resizedImage = imagecreatetruecolor($width, $height);
    imagealphablending($resizedImage, false);
    imagesavealpha($resizedImage, true);

    // Rellenar con un fondo transparente
    $transparentColor = imagecolorallocatealpha($resizedImage, 0, 0, 0, 127);
    imagefill($resizedImage, 0, 0, $transparentColor);

    // Copiar y redimensionar la imagen original
    imagecopyresampled(
      $resizedImage,
      $sourceImage,
      0, 0, 0, 0,
      $width, $height,
      imagesx($sourceImage),
      imagesy($sourceImage)
    );

    // Guardar la imagen redimensionada como PNG
    $outputPath = $this->uploadDir . $filename;
    imagepng($resizedImage, $outputPath);

    // Liberar memoria
    imagedestroy($resizedImage);
  }

  private function generateIco($sourceImage): void {
    // Los tamaños que deseas para los iconos
    $sizes = [16, 32, 48, 64, 128];

    // Ruta donde se guardará el archivo .ico
    $icoFile = $this->uploadDir . 'favicon.ico';

    // Creamos un archivo temporal para la imagen GD
    $tempImageFile = tempnam(sys_get_temp_dir(), 'favicon_') . '.png';

    // Guardamos la imagen GD en un archivo temporal
    imagepng($sourceImage, $tempImageFile);

    // Creamos un objeto Imagick para cargar la imagen desde el archivo temporal
    $imagick = new Imagick();
    $imagick->readImage($tempImageFile);

    // Convertimos la imagen a una imagen con fondo transparente (si no lo tiene)
    $imagick->setImageFormat('png');
    $imagick->setImageBackgroundColor('transparent');

    // Creamos un array de imágenes redimensionadas
    $ico = new Imagick(); // Instanciamos el objeto Imagick para el archivo .ico final
    foreach ($sizes as $size) {
      // Redimensionamos la imagen
      $resizedImage = clone $imagick; // Creamos una copia de la imagen original
      $resizedImage->resizeImage($size, $size, Imagick::FILTER_LANCZOS, 1);

      // Añadimos la imagen redimensionada al archivo .ico
      $ico->addImage($resizedImage);

      // Liberamos la memoria de la imagen redimensionada
      $resizedImage->destroy();
    }

    // Guardamos el archivo .ico
    $ico->setFormat('ico');
    $ico->writeImage($icoFile);

    // Limpiamos recursos
    $imagick->destroy();
    $ico->destroy();

    // Eliminamos el archivo temporal
    unlink($tempImageFile);
  }

  private function generateWebManifest(): void {
    $manifest = [
      "name"  => "Favicon Generator",
      "icons" => [
        [
          "src"   => "android-chrome-192x192.png",
          "sizes" => "192x192",
          "type"  => "image/png"
        ],
        [
          "src"   => "android-chrome-512x512.png",
          "sizes" => "512x512",
          "type"  => "image/png"
        ],
        [
          "src"   => "apple-touch-icon.png",
          "sizes" => "180x180",
          "type"  => "image/png"
        ]
      ]
    ];

    $manifestPath = $this->uploadDir . 'site.webmanifest';
    file_put_contents($manifestPath, json_encode($manifest, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
  }
}