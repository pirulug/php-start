# UploadFile

Clase PHP estilo Fluent para validar y subir archivos (PDF, DOCX, XLSX, TXT...).

## Resumen

UploadFile es una clase sencilla que permite configurar la subida de archivos mediante métodos encadenables (fluent interface). Proporciona validación de extensiones, límite de tamaño, creación automática del directorio y nombres personalizados.

## Instalación

Copia `UploadFile.php` en tu proyecto y `require` o `include` donde lo necesites.

```php
require_once __DIR__ . '/UploadFile.php';

// ó usando autoload (PSR-4 / composer) si lo tienes configurado.
```

## API / Métodos

| Método | Descripción | Valor por defecto / Tipo |
| --- | --- | --- |
| `file(array $file)` | Asigna el array de `$_FILES['campo']`. | —   |
| `dir(string $dir)` | Directorio de destino (ruta absoluta o relativa). | —   |
| `allowedTypes(array $types)` | Lista de extensiones permitidas (sin punto). | `['pdf','docx','xlsx','txt']` |
| `maxSize(int $bytes)` | Tamaño máximo en bytes. | `5 * 1024 * 1024` (5 MB) |
| `name(string $name)` | Nombre personalizado (sin extensión). Si se omite se genera uno único. | —   |
| `upload()` | Ejecuta las validaciones y mueve el archivo. Devuelve un array con el resultado. | array |

## Valores de retorno

```php
// En caso de éxito
[
  'success' => true,
  'message'  => 'Archivo subido con éxito.',
  'file_name' => 'miarchivo.pdf',
  'file_path' => '/ruta/a/uploads/miarchivo.pdf'
]

// En caso de error
[
  'success' => false,
  'message' => 'Descripción del error'
]
```

## Ejemplo de uso — Endpoint PHP

Formulario HTML (cliente):

```
<form action="upload.php" method="post" enctype="multipart/form-data">
  <label>Seleccionar archivo:</label>
  <input type="file" name="document" required>
  <button type="submit">Subir</button>
</form>
```

Archivo servidor `upload.php`:

```php
file($_FILES['document'])
        ->dir(__DIR__ . '/uploads/documents')
        ->allowedTypes(['pdf', 'docx'])
        ->maxSize(10 * 1024 * 1024) // 10 MB
        ->name('contrato_cliente')
        ->upload();

    if ($uploader['success']) {
        echo 'Archivo guardado en: ' . htmlspecialchars($uploader['file_path']);
    } else {
        echo 'Error: ' . htmlspecialchars($uploader['message']);
    }
}
?>
```

## Detalles y recomendaciones

*   La validación de tipo se realiza por extensión. Para mayor seguridad, valida el MIME type con `finfo_file()` o analiza el contenido del archivo.
*   Si tu aplicación está expuesta, guarda los archivos fuera de la carpeta pública y crea un endpoint seguro para servirlos.
*   Controla permisos y evita nombres colisionantes si no quieres sobrescribir. Puedes prefijar con ID de usuario o fecha.
*   Considera limpiar archivos temporales y entradas antiguas con un cron job.

## Manejo de errores comunes

| Error | Causa |
| --- | --- |
| `No se pudo crear el directorio` | Permisos insuficientes o ruta inválida. |
| `El archivo excede el tamaño máximo` | Limite establecido en `maxSize()` o configuración `upload_max_filesize` en php.ini. |
| `Error al mover el archivo` | Problema con `move_uploaded_file`, permisos o directorio inexistente. |

## Clase — referencia rápida

```php
<?php
class UploadFile
{
  private $file;
  private $uploadDir;
  private $allowedTypes = ['pdf','docx','xlsx','txt'];
  private $maxSize = 5242880; // 5MB
  private $customName = null;

  public function file(array $file) { /* ... */ }
  public function dir(string $dir) { /* ... */ }
  public function allowedTypes(array $types) { /* ... */ }
  public function maxSize(int $bytes) { /* ... */ }
  public function name(string $name) { /* ... */ }
  public function upload() { /* ... */ }
}
```

## Changelog

*   **v1.0** — Implementación inicial: fluent API, validaciones básicas.

## Licencia

MIT — usa, modifica y redistribuye libremente, incluyendo en proyectos comerciales. Incluye la cláusula de atribución si así lo deseas.

¿Quieres que convierta este HTML en un archivo listo para descargar o que añada ejemplos con validación MIME y protección CSRF? Dime y lo adapto.