# Documentación de la Clase `UploadImage`

La clase **UploadImage** permite subir imágenes, convertirlas, optimizarlas, redimensionarlas y generar múltiples variantes mediante una API Fluida.

## Características principales

*   Subir imágenes con validación de tamaño y extensión.
*   Convertir imágenes a `jpg`, `png` o `webp`.
*   Optimización por calidad (0–10).
*   Redimensionar la imagen principal con `width()` y `height()`.
*   Generar variantes redimensionadas con `resize()`.
*   Soporte para GD y Imagick (si está disponible).

## Uso básico

```php
$upload = (new UploadImage())
    ->file($_FILES['foto'])
    ->dir('uploads/imagenes')
    ->upload();
```



## Métodos disponibles

### `file(array $file)`

Asigna el archivo recibido por `$_FILES`.

```php
$uploader->file($_FILES['imagen']);
```

### `dir(string $path)`

Carpeta donde se guardará la imagen.

```php
$uploader->dir('uploads/productos');
```

### `supported(array $extensiones)`

Define las extensiones permitidas.

```php
$uploader->supported(['jpg','png','webp']);
```

### `maxSize(int $bytes)`

Establece el tamaño máximo del archivo en bytes.

```php
$uploader->maxSize(3 * 1024 * 1024); // 3MB
```

### `convertTo(?string $formato)`

Convierte la imagen al formato indicado.

```php
$uploader->convertTo('webp');
```

### `optimize(int $nivel)`

Define la calidad de compresión (0 a 10).

```php
$uploader->optimize(7);
```

### `fileName(string $nombre)`

Asigna un nombre personalizado al archivo.

```php
$uploader->fileName('avatar_usuario');
```

### `prefix(string $prefijo)`

Establece un prefijo automático para los nombres generados.

```php
$uploader->prefix('profile_');
```

### `resize(string $key, int $width, int $height)`

Crea variantes redimensionadas.

```php
$uploader
    ->resize('small', 150, 150)
    ->resize('medium', 300, 300);
```

### NUEVO: `width(int $px)` y `height(int $px)`

Redimensionan la imagen principal antes de guardarla.

**Si solo usas width o solo height, mantiene proporciones automáticamente.**

```php
$uploader
    ->width(400)
    ->height(400);
```



## Ejemplo completo

```php
$uploader = (new UploadImage())
    ->file($_FILES['user_image'])
    ->dir('uploads/test')
    ->convertTo("webp")
    ->width(300)
    ->height(300)
    ->resize("small", 150, 150)
    ->resize("medium", 300, 300)
    ->resize("large", 600, 600)
    ->upload();

print_r($uploader);
```



## Resultado que devuelve

```php
Array
(
    [success] => 1
    [message] => Imagen subida correctamente.
    [file_name] => img_abc123.webp
    [file_path] => uploads/test/img_abc123.webp
    [resized] => Array
        (
            [small] => Array
                (
                    [file] => small_img_abc123.webp
                    [path] => uploads/test/small_img_abc123.webp
                )
            [medium] => Array
                (
                    [file] => medium_img_abc123.webp
                    [path] => uploads/test/medium_img_abc123.webp
                )
        )
)
```



## 🛠 Requisitos

*   PHP 7.4+ o superior
*   Extensión GD o Imagick (si existe, se usa Imagick automáticamente)
*   Permisos de escritura en el directorio de subida
