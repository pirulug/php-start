# UploadImage

La clase `UploadImage` es una utilidad avanzada para el manejo de imágenes. A diferencia de una carga de archivo estándar, esta clase permite procesar la imagen (redimensionar, optimizar, cambiar formato) y soporta tanto cargas mediante formularios como descargas desde URLs remotas.

## Características Principales

- **Procesamiento de Imagen**: Soporte para redimensionamiento, recortes (crop) y optimización.
- **Conversión de Formato**: Permite transformar imágenes (ej. JPG a WEBP) durante la carga.
- **Multiprocesador**: Utiliza la extensión `Imagick` si está disponible para mayor calidad, con fallback automático a `GD`.
- **Carga Remota**: Capacidad para descargar y procesar imágenes directamente desde una URL.
- **Variantes de Tamaño**: Soporte para generar múltiples versiones de una misma imagen (ej. thumbnails).

## Métodos de Configuración (Fluent)

### Origen y Destino
- `file(array $file)`: Recibe el archivo de `$_FILES`.
- `url(string $url)`: Recibe una URL externa para descargar la imagen.
- `dir(string $dir)`: Carpeta de destino.

### Procesamiento y Optimización
- `convertTo(string $ext)`: Formato de salida deseado (`jpg`, `png`, `webp`).
- `optimize(int $level)`: Nivel de calidad del 0 al 10 (Predeterminado: 7).
- `width(int $w)` / `height(int $h)`: Redimensiona la imagen principal.
- `resize(string $key, int $w, int $h)`: Crea una variante adicional (ej. `resize('thumb', 150, 150)`).

### Validación
- `supported(array $types)`: Extensiones permitidas (Predeterminado: jpg, jpeg, png, webp).
- `maxSize(int $bytes)`: Límite de peso bruto.

---

## Ejecución

### `upload()`
Ejecuta la descarga/movimiento, aplica las transformaciones y guarda el archivo y sus variantes.

**Retorno notable:**
- `file_name`: Nombre de la imagen principal.
- `resized`: Arreglo de variantes con sus rutas y nombres (ej. `$result['resized']['thumb']['file']`).

---

## Ejemplos de Implementación

### 1. Carga, Conversión a WEBP y Miniatura
```php
$manager = new UploadImage();

$res = $manager->file($_FILES['avatar'])
               ->dir(BASE_DIR . '/storage/profile')
               ->convertTo('webp')
               ->optimize(8)
               ->width(800) // Redimensionar original a 800px de ancho
               ->resize('thumb', 150, 150) // Crear miniatura cuadrada
               ->upload();

if ($res['success']) {
    $main = $res['file_name'];
    $thumb = $res['resized']['thumb']['file'];
}
```

### 2. Descarga de Imagen desde URL Externa
```php
$manager = new UploadImage();

$res = $manager->url('https://sitio.com/foto.jpg')
               ->dir(BASE_DIR . '/storage/remote')
               ->fileName('foto_capturada')
               ->upload();
```

## Notas Técnicas

- **Dependencias**: El redimensionamiento y los recortes requieren `Imagick` para funcionar. Si no está instalado, la clase solo podrá realizar conversiones básicas de formato y optimización mediante `GD`.
- **Seguridad**: Valida el MIME type real del contenido descargado por URL para asegurar que sea realmente una imagen antes de procesarla.
- **Limpieza**: Los archivos temporales generados durante el procesamiento se eliminan automáticamente al finalizar la operación de `upload()`.