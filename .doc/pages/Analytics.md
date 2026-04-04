# Analytics

Clase para el seguimiento de visitas, sesiones y estadísticas de usuarios en tiempo real. Gestiona la persistencia en base de datos de visitantes únicos, páginas vistas y actividad online.

## Características

- **Seguimiento Automático**: Registra IP, User Agent, Referer y ruta de la página.
- **Detección de Dispositivos**: Identifica el navegador, plataforma (OS) y tipo de dispositivo (Desktop, Smartphone, Tablet).
- **Gestión de Sesiones**: Utiliza cookies de larga duración para agrupar múltiples visitas de un mismo usuario.
- **Geolocalización**: Soporte para resolución de ubicación (País, Ciudad) mediante API externa.
- **Filtrado de Activos**: Ignora automáticamente peticiones a archivos estáticos (imágenes, CSS, JS).

## Métodos Principales

### `__construct(PDO $pdo)`
Inicializa la clase con la conexión a la base de datos.

---

### `geoApiUrl(string $url): self`
Configura la URL de la API de geolocalización.
- **$url**: Debe contener el marcador `{ip}`.

---

### `trackVisit(string $pageTitle, string $pageUri, ?string $ip = null): void`
El método principal para registrar una visita.
- **$pageTitle**: Título legible de la página.
- **$pageUri**: URI o ruta de la página actual.
- **$ip**: (Opcional) IP del cliente si se conoce de antemano.

---

### `resolveUnknownCountries(int $limit = 100): int`
Procesa registros con país "Desconocido" consultando la API de geolocalización de forma asíncrona o programada.
- **$limit**: Cantidad máxima de registros a procesar por ejecución.
- **Retorno**: Número de registros actualizados satisfactoriamente.

## Ejemplo de Uso

### Integración en el index principal

Se recomienda llamar a este método en el punto de entrada de la aplicación después de cargar el bootstrap.

```php
// En index.php o router.php
$analytics = new Analytics($connect);

// Opcional: configurar API personalizada
$analytics->geoApiUrl('https://api.mi-geo.com/{ip}');

// Rastrear la visita actual
$analytics->trackVisit(
    $config->title("Página de Inicio"),
    $_SERVER['REQUEST_URI']
);
```

### Ejecución de Tarea de Geolocalización (Cron)

Para no penalizar el rendimiento del usuario final, la resolución de países puede ejecutarse en segundo plano.

```php
// En un script de tarea programada
$analytics = new Analytics($connect);
$updated = $analytics->resolveUnknownCountries(50);
echo "Se actualizaron $updated ubicaciones.";
```

## Estructura de Tablas Necesaria

La clase interactúa con las siguientes tablas:
1. `visitors`: Almacena datos únicos por IP/UA.
2. `visitor_pages`: Estadísticas por URL.
3. `visitor_sessions`: Relación de rutas seguidas por un usuario en una sesión.
4. `visitor_useronlines`: Tracking de usuarios activos en los últimos 10 minutos.
