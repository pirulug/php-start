<?php

class UploadFile {
  private $file;
  private $uploadDir;

  private $allowedTypes = ['pdf', 'docx', 'xlsx', 'txt'];
  private $maxSize = 5 * 1024 * 1024; // 5MB
  private $customName = null;

  private $result = [
    'success'   => false,
    'message'   => '',
    'file_name' => null,
    'file_path' => null
  ];

  /** Cargar archivo desde $_FILES */
  public function file(array $file) {
    $this->file = $file;
    return $this;
  }

  /** Directorio de destino */
  public function dir(string $dir) {
    $this->uploadDir = rtrim($dir, '/');
    return $this;
  }

  /** Extensiones permitidas */
  public function allowedTypes(array $types) {
    $this->allowedTypes = $types;
    return $this;
  }

  /** Tamaño máximo */
  public function maxSize(int $bytes) {
    $this->maxSize = $bytes;
    return $this;
  }

  /** Nombre personalizado sin extensión */
  public function name(string $name) {
    $this->customName = $name;
    return $this;
  }

  /** Ejecutar la subida */
  public function upload() {
    // Verificar archivo
    if (!isset($this->file) || $this->file['error'] !== UPLOAD_ERR_OK) {
      return $this->fail("Error al subir el archivo.");
    }

    // Validar extensión
    $ext = strtolower(pathinfo($this->file['name'], PATHINFO_EXTENSION));

    if (!in_array($ext, $this->allowedTypes)) {
      return $this->fail("La extensión .$ext no está permitida. Permitidas: " . implode(", ", $this->allowedTypes));
    }

    // Validar tamaño
    if ($this->file['size'] > $this->maxSize) {
      return $this->fail("El archivo excede el tamaño máximo de " . ($this->maxSize / 1024 / 1024) . " MB.");
    }

    // Crear directorio si no existe
    if (!is_dir($this->uploadDir)) {
      if (!mkdir($this->uploadDir, 0755, true) && !is_dir($this->uploadDir)) {
        return $this->fail("No se pudo crear el directorio de destino.");
      }
    }

    // Determinar nombre
    $fileName = $this->customName
      ? $this->customName . '.' . $ext
      : uniqid("file_", true) . '.' . $ext;

    $filePath = $this->uploadDir . "/" . $fileName;

    // Mover archivo
    if (!move_uploaded_file($this->file['tmp_name'], $filePath)) {
      return $this->fail("Error al mover el archivo al directorio de destino.");
    }

    return $this->success("Archivo subido con éxito.", $fileName, $filePath);
  }

  private function fail(string $msg) {
    return $this->result = [
      'success' => false,
      'message' => $msg
    ];
  }

  private function success(string $msg, string $name, string $path) {
    return $this->result = [
      'success'   => true,
      'message'   => $msg,
      'file_name' => $name,
      'file_path' => $path
    ];
  }
}
