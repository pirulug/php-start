# Analytics (Analítica del Sistema)

El sistema de analítica de **PHP-Start** es un componente modular de alto rendimiento diseñado para el seguimiento de visitas, sesiones y estadísticas en tiempo real sin penalizar la velocidad de carga.

---

## Arquitectura Modular

Todo el motor de analítica reside en `core/system/analytics/`. Utiliza un sistema de **Autoloading** y **Lazy Loading** para asegurar que el código solo se cargue cuando es estrictamente necesario.

### Diferencias Clave:
- **Carga Bajo Demanda**: La lógica pesada de procesamiento solo se carga al llamar a `Analytics::init()`.
- **Rutas Propias**: El componente registra sus propios endpoints AJAX (`/ajax/visitors` y `/ajax/country`).
- **Ayudante Global**: Se accede a través de la clase `Analytics`, que actúa como helper único.

---

## Integración con el Enrutador

La forma recomendada de activar el seguimiento es mediante el método `->analytic()` al definir una ruta en el `router.php` de cada módulo.

### 1. Seguimiento con Título Personalizado
```php
Router::route('inicio')
  ->view('home')
  ->analytic('Página Principal') // Define el título manualmente
  ->register();
```

### 2. Seguimiento con Título Automático
Si llamas a `->analytic()` sin parámetros, el sistema esperará a que se procese la vista y capturará automáticamente el contenido del bloque `start_block('title')`. Si no existe, usará la URL formateada.
```php
Router::route('contacto')
  ->view('contact')
  ->analytic() // Captura el <title> automáticamente de la vista
  ->register();
```

---

## Uso Manual (Backend)

Si necesitas acceder a las estadísticas o realizar un rastreo manual desde el código del servidor, utiliza el inicializador:

```php
// Obtener el servicio (Carga perezosa del componente)
$analytics = Analytics::init();

// Ejemplo: Obtener el resumen para el Dashboard
$summary = $analytics->getDashboardSummary();

// Ejemplo: Ejecutar resolución de geolocalización
$updated = $analytics->resolveUnknownCountries(50);
```

---

## Endpoints de Sistema (AJAX)

El componente expone automáticamente las siguientes rutas para ser consumidas por el panel administrativo o utilidades externas:

- **`/ajax/visitors`**: Devuelve un JSON con el resumen de visitas, online y estadísticas por país.
- **`/ajax/country`**: Endpoint para disparar la resolución de países pendientes vía API de geolocalización.

---

## Estructura de Archivos

- **`core/system/analytics/analytics.helper.php`**: El ayudante (Helper) cargado automáticamente. Proporciona la clase `Analytics`.
- **`core/system/analytics/analytics.system.php`**: El motor unificado (`AnalyticsService`) que contiene toda la lógica de rastreo y base de datos.
- **`core/system/analytics/visitors.action.php`**: Lógica para el endpoint de resumen de visitas.
- **`core/system/analytics/country.action.php`**: Lógica para el endpoint de resolución geográfica.
- **`core/system/analytics/router.php`**: Registro centralizado de las rutas de analítica.

---

## Consideraciones de Rendimiento
- **Eficiencia de Carga**: En rutas donde no se invoca `->analytic()`, el sistema de analítica permanece inactivo y no consume memoria ni recursos de CPU.
- **Geolocalización Diferida**: La resolución de IP a País se realiza bajo demanda o mediante el endpoint `/ajax/country`, evitando ralentizar la navegación del usuario final.