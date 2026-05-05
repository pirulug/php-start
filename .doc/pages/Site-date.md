# SiteDate Helper & Library

Esta librería centraliza el manejo de fechas y horas en el sistema, asegurando que todos los formatos respeten la configuración dinámica establecida en el panel de administración (`Zona Horaria`, `Formato de Fecha`, etc.).

## 1. Función Global `site_date()`

La forma más sencilla de mostrar fechas en tus vistas es usando el helper global `site_date()`.

### Uso Básico
```php
// Muestra la fecha y hora actual (formato datetime por defecto)
echo site_date(); 

// Formatear una fecha específica (string o timestamp)
echo site_date($user->created_at);
```

### Formatos Predefinidos
Puedes pasar un segundo argumento para especificar el tipo de formato basado en la configuración del sitio:

```php
// Formato de Fecha (date_format) -> Ej: 20/04/2026
echo site_date('now', 'date');

// Formato de Hora (time_format) -> Ej: 08:30 pm
echo site_date('now', 'time');

// Formato Completo (datetime_format) -> Ej: 20/04/2026 08:30 pm
echo site_date('now', 'datetime');
```

### Formatos Personalizados
También puedes pasar cualquier formato estándar de PHP `date()`:

```php
echo site_date('now', 'Y-m-d'); // 2026-04-20
echo site_date('now', 'l, d F Y'); // Lunes, 20 Abril 2026
```

---

## 2. Clase `SiteDate` (Librería Core)

Para operaciones más complejas o inyección de dependencias, puedes usar la clase directamente.

### Inicialización
```php
$sd = new SiteDate();
```

### Métodos Disponibles

| Método | Descripción |
| :--- | :--- |
| `format($date, $format)` | Formateador universal. Si `$format` es null, usa `datetime_format`. |
| `date($date)` | Alias para `$this->format($date, 'date')`. |
| `time($date)` | Alias para `$this->format($date, 'time')`. |
| `datetime($date)` | Alias para `$this->format($date, 'datetime')`. |
| `getTimezone()` | Retorna el objeto `DateTimeZone` actual del sitio. |

### Ejemplo en una Acción
```php
$siteDate = new SiteDate();
$mensaje = "El reporte se generó el " . $siteDate->datetime();
```

---

## 3. Configuración Soportada
La clase `SiteDate` lee automáticamente los siguientes valores de la tabla `options`:
- **`site_timezone`**: Define la zona horaria (ej: `America/Lima`).
- **`date_format`**: Formato para fechas cortas.
- **`time_format`**: Formato para horas.
- **`datetime_format`**: Formato para fecha y hora completa.

> [!IMPORTANT]
> Si los formatos no están definidos en la base de datos, la clase utilizará valores por defecto seguros (`d/m/Y H:i a`).

> [!TIP]
> Puedes cambiar estos formatos en cualquier momento desde **Ajustes > Fecha y Hora** en el panel administrativo.