<?php

/**
 * UploadFile
 *
 * Clase responsable de la carga y validación de archivos.
 * Permite definir extensiones permitidas, tamaño máximo,
 * nombres personalizados, prefijos y generación de nombres únicos.
 *
 * @author Pirulug
 * @link   https://github.com/pirulug
 */
class UploadFile {

  private ?array $file = null;
  private string $uploadDir;

  private array $allowedTypes = ['pdf', 'docx', 'xlsx', 'txt'];
  private int $maxSize = 5242880;

  private ?string $customName = null;
  private string $prefix = '';
  private bool $useUnique = false;
  private bool $useHash = false;

  public function file(array $file): self {
    $this->file = $file;
    return $this;
  }

  public function dir(string $dir): self {
    $this->uploadDir = rtrim($dir, '/');
    return $this;
  }

  public function allowedTypes(array $types): self {
    $this->allowedTypes = array_map('strtolower', $types);
    return $this;
  }

  public function maxSize(int $bytes): self {
    $this->maxSize = $bytes;
    return $this;
  }

  public function name(string $name): self {
    $this->customName = preg_replace('/\./', '', $name);
    return $this;
  }

  public function prefix(string $prefix): self {
    $this->prefix = $prefix;
    return $this;
  }

  public function unique(): self {
    $this->useUnique = true;
    $this->useHash   = false;
    return $this;
  }

  public function hash(): self {
    $this->useHash   = true;
    $this->useUnique = false;
    return $this;
  }

  public function upload(): array {

    if (!$this->file || !isset($this->file['error'])) {
      return $this->fail('No se recibió ningún archivo.');
    }

    if ($this->file['error'] !== UPLOAD_ERR_OK) {
      return $this->fail('Error al subir el archivo.');
    }

    if ($this->file['size'] > $this->maxSize) {
      return $this->fail('El archivo excede el tamaño máximo permitido.');
    }

    $ext = strtolower(pathinfo($this->file['name'], PATHINFO_EXTENSION));

    if (!$ext || !in_array($ext, $this->allowedTypes, true)) {
      return $this->fail("Extensión .$ext no permitida.");
    }

    if (!is_dir($this->uploadDir)) {
      if (!mkdir($this->uploadDir, 0777, true) && !is_dir($this->uploadDir)) {
        return $this->fail('No se pudo crear el directorio de destino.');
      }
    }

    if ($this->useHash) {
      $baseName = sha1_file($this->file['tmp_name']);
    } elseif ($this->useUnique) {
      $baseName = uniqid('file_', true);
    } elseif ($this->customName) {
      $baseName = $this->customName;
    } else {
      $baseName = uniqid('file_', true);
    }

    $fileName = $this->prefix . $baseName . '.' . $ext;
    $filePath = $this->uploadDir . '/' . $fileName;

    if (!move_uploaded_file($this->file['tmp_name'], $filePath)) {
      return $this->fail('No se pudo mover el archivo.');
    }

    return [
      'success'   => true,
      'message'   => 'Archivo subido correctamente.',
      'file_name' => $fileName,
      'file_path' => $filePath
    ];
  }

  private function fail(string $message): array {
    return [
      'success' => false,
      'message' => $message
    ];
  }
}
