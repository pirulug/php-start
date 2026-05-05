# AntiXSS

La clase `AntiXSS` es un componente de seguridad crítico encargado de la prevención y mitigación de ataques de Scripting entre Sitios (XSS). Su función principal es sanitizar y validar los datos de entrada y salida para evitar la inyección de scripts maliciosos en la aplicación.

## Características Principales

- **Limpieza de Caracteres Invisibles**: Elimina caracteres nulos (`\x00`) y de control que intentan evadir filtros.
- **Filtrado de Etiquetas Peligrosas**: Elimina automáticamente etiquetas como `<script>`, `<iframe>`, `<form>`, `<svg>`, `<math>`, entre otras.
- **Limpieza de Eventos HTML**: Detecta y remueve atributos de eventos de JavaScript (`onclick`, `onload`, etc.).
- **Procesamiento de Protocolos Maliciosos**: Bloquea el uso de esquemas `javascript:`, `vbscript:`, `data:`, `mocha:`, etc.
- **Soporte Recursivo**: Capacidad para limpiar arreglos multidimensionales.
- **Blindaje Anti-PHP**: Detecta y elimina etiquetas de apertura y cierre de PHP (`<?php`, `<?=`).
- **Limpieza de Bucle (Recursiva)**: Aplica los filtros repetidamente hasta que el contenido deja de cambiar, evitando etiquetas anidadas.

## Métodos Disponibles

### `clean(string $input, bool $escape = true)`

Este es el método principal para sanitizar una cadena de texto individual.

**Sucesión de procesos internos:**
1. **Limpieza de Control**: Elimina caracteres nulos y no imprimibles.
2. **Decodificación**: Convierte entidades HTML para evitar evasiones por codificación.
3. **Bucle de Limpieza**: Aplica expresiones regulares de forma recursiva.
4. **Limpieza de Etiquetas**: Ejecuta `strip_tags()` para eliminar cualquier etiqueta HTML restante.
5. **Escape (Opcional)**: Si `$escape` es `true`, aplica `htmlspecialchars`.

---

### `cleanHtml(string $input, string $allowedTags = '...')`

Limpia contenido HTML (Rich Text) permitiendo etiquetas seguras. Ideal para editores WYSIWYG.

**Diferencia con `clean()`:**
- No aplica `strip_tags()` a todo, sino que permite una lista blanca de etiquetas.
- No aplica `htmlspecialchars()` al final, preservando el formato visual.
- Mantiene la protección agresiva contra scripts y eventos.

---

### `cleanArray(array $data, bool $escape = true)`

Limpia de forma recursiva todos los elementos de un arreglo.

## Patrones de Bloqueo Actualizados

| Patrón Detectado | Acción |
| :--- | :--- |
| `<script>`, `<iframe>`, `<object>`, `<embed>` | Eliminación completa del bloque. |
| `<svg>`, `<math>` | Bloqueo de vectores XSS modernos. |
| `<?php ... ?>`, `<? ... ?>` | Eliminación de inyección de código servidor. |
| `<style>`, `<link>`, `<meta>`, `<base>` | Prevención de alteración de DOM y redirecciones. |
| `on[evento]` | Eliminación de disparadores JS (ahora más agresiva). |

## Helpers de Seguridad Relacionados

Además de la clase, el sistema cuenta con helpers globales en `clear_data.helper.php`:

### `clear_data($string)`
Uso estándar para inputs de texto. Llama a `clean()` y aplica `trim()`.

### `clear_html($string, $allowedTags)`
Uso para campos con formato. Llama a `cleanHtml()`.

### `is_safe_image($file_array)`
Valida si un archivo de `$_FILES` es una imagen real y no un script camuflado (revisando extensión, MIME real e integridad de imagen).

## Notas Técnicas

- La limpieza es **recursiva**: si un atacante intenta `<scr<script>ipt>`, el primer pase borra el script interno y el segundo pase borra lo que queda.
- El sistema de **Diagnóstico de Seguridad** (`test/system`) audita automáticamente el uso de estos métodos en todos los módulos.