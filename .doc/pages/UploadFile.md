# UploadFile

La clase `UploadFile` es el componente encargado de gestionar la carga de archivos al servidor. Proporciona una capa de validación para asegurar que los archivos cumplan con los requisitos de tipo y tamaño, además de facilitar la organización en directorios y el renombrado seguro.

## Características Principales

- **Validación de Tipos**: Filtra archivos por extensiones permitidas (PDF, Word, Excel, etc.).
- **Control de Tamaño**: Limita el peso máximo de los archivos para proteger el almacenamiento.
- **Gestión de Directorios**: Crea automáticamente la ruta de destino si no existe.
- **Renombrado Seguro**: Ofrece opciones para generar nombres únicos mediante `uniqid` o `sha1_file` (hash).
- **Interfaz Fluida**: Configuración encadenable antes de la ejecución de la carga.

## Métodos de Configuración (Fluent)

- `file(array $file)`: Recibe el arreglo del archivo desde `$_FILES['input_name']`.
- `dir(string $dir)`: Define la ruta física donde se guardará el archivo.
- `allowedTypes(array $types)`: Define las extensiones permitidas (ej. `['pdf', 'zip']`).
- `maxSize(int $bytes)`: Define el límite de peso en bytes (Predeterminado: 5MB).
- `name(string $name)`: Establece un nombre específico para el archivo (sin extensión).
- `prefix(string $prefix)`: Añade un prefijo al nombre del archivo final.
- `unique()`: Fuerza el uso de un nombre único generado aleatoriamente.
- `hash()`: Genera el nombre del archivo basándose en su contenido (SHA1), ideal para evitar duplicados exactos.

---

## Ejecución

### `upload()`
Procesa la validación y mueve el archivo al destino. Devuelve un arreglo con el estado de la operación.

**Estructura del retorno:**
- `success` (bool): `true` si se subió correctamente.
- `message` (string): Detalles del éxito o error.
- `file_name` (string): Nombre final del archivo guardado.
- `file_path` (string): Ruta completa del archivo en el servidor.

---

## Ejemplo de Implementación

### Carga de un Documento PDF
```php
$uploader = new UploadFile();

$result = $uploader->file($_FILES['manual_pdf'])
                  ->dir(BASE_DIR . '/storage/documents')
                  ->allowedTypes(['pdf'])
                  ->maxSize(10 * 1024 * 1024) // 10MB
                  ->prefix('doc_')
                  ->hash()
                  ->upload();

if ($result['success']) {
    echo "Archivo guardado como: " . $result['file_name'];
} else {
    echo "Error: " . $result['message'];
}
```

## Notas Técnicas

- La clase utiliza `move_uploaded_file()` para asegurar que el archivo provenga de una carga legítima de PHP.
- Por seguridad, el método `name()` elimina cualquier punto introducido para evitar manipulaciones de extensión.
- Si no se especifica un nombre, prefijo o estrategia (unique/hash), la clase generará un nombre único por defecto.