# FaviconGenerator

Clase especializada en la creación de un conjunto completo de iconos para sitios web modernos (Favicons) a partir de una única imagen de origen en formato PNG. Genera archivos optimizados para navegadores de escritorio, dispositivos Android, iOS y el archivo de manifiesto de aplicaciones web.

## Características

- **Soporte Multi-Plataforma**: Crea iconos específicos para Android (`chrome-192/512`), Apple (`touch-icon`) y navegadores clásicos (`favicon-16/32`).
- **Generación de ICO Real**: Utiliza `Imagick` para crear un archivo `.ico` multi-capa con resoluciones desde 16px hasta 128px.
- **Web Manifest Automático**: Genera el archivo `site.webmanifest` vinculando los iconos generados.
- **Cache Busting**: Añade un hash único de 8 caracteres al nombre de cada archivo para evitar problemas de caché tras una actualización.
- **Transparencia**: Preserva el canal alfa (transparencia) en todos los redimensionamientos.

## Requerimientos

- **PHP GD Library**: Para el redimensionamiento de las imágenes PNG.
- **PHP Imagick Extension**: Mandatorio para la compilación del archivo `.ico`.

## Métodos Principales

### `__construct(string $uploadDir)`
Define el directorio donde se guardarán los archivos generados.
- **$uploadDir**: Ruta absoluta al directorio de destino. Si no existe, se intentará crear con permisos `0777`.

---

### `generate(string $sourceFile): array`
Procesa la imagen de origen y genera todos los activos.
- **$sourceFile**: Ruta al archivo PNG de origen (mínimo recomendado 512x512px).
- **Retorno**: Array asociativo con los nombres de los archivos generados indexados por su propósito (ej: `favicon.ico`, `apple-touch-icon`, `webmanifest`).
- **Lanza**: `Exception` si el archivo no es un PNG válido o si falla la carga.

## Ejemplo de Uso

```php
try {
    $generator = new FaviconGenerator(BASE_DIR . '/static/uploads/favicons');
    
    $results = $generator->generate(BASE_DIR . '/static/assets/img/logo-source.png');
    
    // El $results contendrá algo como:
    // [
    //   'favicon.ico' => 'favicon-f3c9a1b2.ico',
    //   'apple-touch-icon' => 'apple-touch-icon-f3c9a1b2.png',
    //   'webmanifest' => 'site-f3c9a1b2.webmanifest',
    //   ...
    // ]
    
    // Guardar estos nombres en la base de datos (SiteConfig)
    update_favicon_settings($results);

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
```

## Archivos Generados

| Identificador | Tamaño / Formato | Uso |
| :--- | :--- | :--- |
| `android-chrome-192x192` | 192x192 PNG | Chrome en Android (PWA) |
| `android-chrome-512x512` | 512x512 PNG | Splash Screen en Android |
| `apple-touch-icon` | 180x180 PNG | Icono de inicio en iOS |
| `favicon-16x16` | 16x16 PNG | Pestaña del navegador (pequeño) |
| `favicon-32x32` | 32x32 PNG | Pestaña del navegador (estándar) |
| `favicon.ico` | Multi-tamaño ICO | Compatibilidad con navegadores antiguos |
| `webmanifest` | JSON | Configuración de PWA |

## Integración en el HTML

Para utilizar los iconos generados, se deben incluir etiquetas similares a estas en el `<head>`:

```html
<link rel="apple-touch-icon" sizes="180x180" href="/uploads/favicons/apple-touch-icon-f3c9a1b2.png">
<link rel="icon" type="image/png" sizes="32x32" href="/uploads/favicons/favicon-32x32-f3c9a1b2.png">
<link rel="icon" type="image/png" sizes="16x16" href="/uploads/favicons/favicon-16x16-f3c9a1b2.png">
<link rel="manifest" href="/uploads/favicons/site-f3c9a1b2.webmanifest">
```
