# Site Format Helpers

Esta librería centraliza las funciones de formateo para fechas, números y moneda. Toda la configuración es gestionada dinámicamente desde la base de datos a través del objeto global `$config` (instancia de `SiteConfig`), asegurando coherencia en toda la aplicación.

## 1. Fechas y Horas

Los helpers de fecha utilizan la configuración global del sitio (`Zona Horaria`, `Formato de Fecha`, etc.).

### `format_date($date)`
Formatea una fecha según la configuración del sitio (Solo Fecha).
- **Ejemplo:** `<?= format_date('now') ?>` -> `20/04/2026`

### `format_time($date)`
Formatea una hora según la configuración del sitio (Solo Hora).
- **Ejemplo:** `<?= format_time('now') ?>` -> `08:30 pm`

### `format_datetime($date)`
Formatea fecha y hora según la configuración del sitio.
- **Ejemplo:** `<?= format_datetime('now') ?>` -> `20/04/2026 08:30 pm`

---

## 2. Números y Moneda

El formateo de números y moneda utiliza separadores de miles y decimales configurables desde el panel administrativo.

### `format_number($number)`
Formatea un número de forma inteligente. Muestra decimales solo si existen en el número original.
- **Ejemplo:** `<?= format_number(1250.5) ?>` -> `1 250.5`
- **Ejemplo:** `<?= format_number(1250) ?>` -> `1 250`

### `format_number_decimal($number, $decimals = null)`
Formatea un número forzando siempre la aparición de decimales. Si no se especifica `$decimals`, usa el valor por defecto de la configuración.
- **Ejemplo:** `<?= format_number_decimal(1250, 2) ?>` -> `1 250.00`

### `format_money($number, $symbol = null)`
Formatea un monto como moneda, incluyendo el símbolo y la posición (antes/después) según la configuración.
- **Ejemplo:** `<?= format_money(1250.50) ?>` -> `$ 1,250.50`

---

## 3. Configuración Global
Estos helpers dependen de la tabla `options` gestionada por `$config`. Algunos valores clave son:

- **Fechas**: `site_timezone`, `date_format`, `time_format`, `datetime_format`.
- **Números**: `number_decimal_sep`, `number_thousand_sep`, `number_decimals`.
- **Moneda**: `currency_symbol`, `currency_position`, `currency_decimal_sep`, `currency_thousand_sep`, `currency_decimals`.

> [!IMPORTANT]
> Los helpers de moneda y números utilizan configuraciones separadas para permitir, por ejemplo, usar un espacio como separador de miles en números generales pero una coma en moneda.

> [!TIP]
> Puedes cambiar estos formatos en cualquier momento desde **Ajustes > General** o **Ajustes > Fecha y Hora** en el panel administrativo.

---

## 4. Demostración de Código (PHP)

Puedes utilizar estos helpers directamente en tus plantillas para mostrar datos formateados de manera elegante y profesional:

```php
<!-- Ejemplo en una vista HTML/PHP -->
<div class="card-body">
  <p>Total a Pagar: <strong><?= format_money(2500.75) ?></strong></p>
  <p>Stock disponible: <?= format_number_decimal(1250.00) ?></p>
  <p>Última actualización: <small><?= format_datetime('now') ?></small></p>
</div>
```