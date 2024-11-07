<?php

class FileUpload {
  private $uploadDir;
  private $allowedMimeTypes;
  private $maxFileSize;

  // Constructor
  public function __construct($uploadDir = 'uploads/', $allowedMimeTypes = ['image/jpeg', 'image/png', 'application/pdf'], $maxFileSize = 10485760) {
    $this->uploadDir        = $uploadDir;
    $this->allowedMimeTypes = $allowedMimeTypes;
    $this->maxFileSize      = $maxFileSize;

    // Crear el directorio de destino si no existe
    if (!is_dir($this->uploadDir)) {
      mkdir($this->uploadDir, 0777, true);
    }
  }

  // Subir un archivo desde el sistema local
  public function uploadFromLocal($file, $rename = true) {
    // Validar si se ha subido un archivo
    if ($file['error'] !== UPLOAD_ERR_OK) {
      return ['status' => false, 'message' => 'Error en la subida del archivo.'];
    }

    // Validar el tipo MIME y el tamaño del archivo
    $fileMime = mime_content_type($file['tmp_name']);
    if (!in_array($fileMime, $this->allowedMimeTypes)) {
      return ['status' => false, 'message' => 'Tipo de archivo no permitido.'];
    }

    if ($file['size'] > $this->maxFileSize) {
      return ['status' => false, 'message' => 'El archivo es demasiado grande.'];
    }

    // Definir el nombre del archivo
    $fileName = $rename ? $this->generateUniqueFileName($file['name']) : $file['name'];

    // Mover el archivo al directorio de destino
    $destination = $this->uploadDir . DIRECTORY_SEPARATOR . $fileName;
    if (move_uploaded_file($file['tmp_name'], $destination)) {
      return ['status' => true, 'message' => 'Archivo subido exitosamente.', 'file_path' => $destination];
    } else {
      return ['status' => false, 'message' => 'Error al mover el archivo al directorio de destino.'];
    }
  }

  // Subir un archivo desde una URL
  public function uploadFromUrl($url) {
    // Validar la URL
    if (!filter_var($url, FILTER_VALIDATE_URL)) {
      return ['status' => false, 'message' => 'URL no válida.'];
    }

    // Obtener el contenido del archivo desde la URL
    $fileContent = file_get_contents($url);
    if ($fileContent === false) {
      return ['status' => false, 'message' => 'No se pudo obtener el archivo desde la URL.'];
    }

    // Obtener el nombre del archivo desde la URL
    $fileName = basename(parse_url($url, PHP_URL_PATH));

    // Definir la ruta de destino
    $destination = $this->uploadDir . DIRECTORY_SEPARATOR . $fileName;

    // Guardar el archivo en el servidor
    if (file_put_contents($destination, $fileContent)) {
      return ['status' => true, 'message' => 'Archivo subido desde la URL exitosamente.', 'file_path' => $destination];
    } else {
      return ['status' => false, 'message' => 'Error al guardar el archivo desde la URL.'];
    }
  }

  // Generar un nombre de archivo único
  private function generateUniqueFileName($fileName) {
    $ext = pathinfo($fileName, PATHINFO_EXTENSION);
    return uniqid('file_', true) . '.' . $ext;
  }

  // Establecer un nuevo directorio de destino
  public function setUploadDir($dir) {
    $this->uploadDir = $dir;

    // Crear el directorio si no existe
    if (!is_dir($this->uploadDir)) {
      mkdir($this->uploadDir, 0777, true);
    }
  }

  // Establecer nuevos tipos MIME permitidos
  public function setAllowedMimeTypes($mimeTypes) {
    $this->allowedMimeTypes = $mimeTypes;
  }

  // Establecer un nuevo tamaño máximo para los archivos
  public function setMaxFileSize($maxSize) {
    $this->maxFileSize = $maxSize;
  }
}

/*
<form action="upload.php" method="POST" enctype="multipart/form-data">
    <input type="file" name="fileToUpload">
    <input type="submit" value="Subir Archivo" name="submit">
</form>

require_once 'FileUpload.php';

$fileUpload = new FileUpload();

// Subir archivo desde el formulario
if (isset($_FILES['fileToUpload'])) {
    $result = $fileUpload->uploadFromLocal($_FILES['fileToUpload']);
    echo $result['message'];
}

// Subir archivo desde una url
$url = 'https://example.com/somefile.pdf';
$result = $fileUpload->uploadFromUrl($url);
echo $result['message'];
*/