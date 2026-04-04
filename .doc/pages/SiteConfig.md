# SiteConfig

Clase encargada de la gestión de configuraciones dinámicas del sitio almacenadas en la base de datos (tabla `options`). Implementa carga diferida (lazy loading) y conversión automática de valores JSON a objetos PHP.

## Características

- **Carga Diferida**: Los datos solo se consultan cuando se solicita la primera opción.
- **Conversión Automática**: Detecta automáticamente si un valor es una cadena JSON y lo convierte a un objeto.
- **Interfaz Fluida**: Ofrece métodos directos para las opciones más comunes.

## Métodos Principales

### `__construct(PDO $db)`
Inicializa la clase con una conexión a la base de datos.
- **$db**: Instancia de PDO.

---

### `get(string $key, mixed $default = null): mixed`
Obtiene un valor de configuración por su clave.
- **$key**: Nombre de la opción en la tabla.
- **$default**: Valor a retornar si la clave no existe.

---

### `siteName(): ?string`
Acceso directo al nombre del sitio (`site_name`).

---

### `siteUrl(): ?string`
Acceso directo a la URL principal del sitio (`site_url`).

---

### `timezone(): ?string`
Acceso directo a la zona horaria del sitio (`site_timezone`).

---

### `title(?string $pageTitle = null): string`
Genera una etiqueta de título combinando el título de la página y el nombre del sitio.
- **$pageTitle**: (Opcional) Título de la sección actual.
- **Retorno**: `"Mi Pagina | Mi Sitio"` o solo `"Mi Sitio"`.

---

### `favicon(): ?object`
Obtiene la configuración del favicon (`favicon`). Generalmente retorna un objeto con las rutas de las imágenes.

---

### `logo(): object`
Retorna un objeto con las rutas del logo para modo claro y oscuro.
- **Estructura**: `{ dark: "...", light: "..." }`.

---

### `refresh(): self`
Limpia la caché interna de la clase, forzando una nueva consulta a la base de datos en el próximo acceso.

## Ejemplo de Uso

### Uso Básico en Bootstrap

El sistema inicializa esta clase globalmente en `core/bootstrap/base.php` bajo la variable `$config`.

```php
// Obtener un valor genérico
$analyticsId = $config->get('google_analytics_id');

// Generar título de página
$pageTitle = $config->title("Dashboard"); // "Dashboard | Nombre del Sitio"
```

### Acceso a Logos y Favicon

```php
$logos = $config->logo();
echo $logos->dark; // Ruta del logo oscuro

$fav = $config->favicon();
if ($fav) {
    echo "<link rel='icon' href='{$fav->icon}'>";
}
```

### Refrescar Configuración
Si realizas cambios en la tabla `options` durante la ejecución, puedes forzar la recarga:

```php
$config->refresh();
$newValue = $config->get('mi_opcion');
```

## Integración con Base de Datos

La clase espera una tabla llamada `options` con al menos las siguientes columnas:
- `option_key`: (VARCHAR) Clave única de la opción.
- `option_value`: (TEXT) Valor de la opción (puede ser texto plano o JSON).
