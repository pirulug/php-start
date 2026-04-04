# AntiXSS

La clase `AntiXSS` es un componente de seguridad crítico encargado de la prevención y mitigación de ataques de Scripting entre Sitios (XSS). Su función principal es sanitizar y validar los datos de entrada y salida para evitar la inyección de scripts maliciosos en la aplicación.

## Características Principales

- **Filtrado de Etiquetas Peligrosas**: Elimina automáticamente etiquetas como `<script>`, `<iframe>`, `<form>`, entre otras.
- **Limpieza de Eventos HTML**: Detecta y remueve atributos de eventos de JavaScript (`onclick`, `onload`, etc.).
- **Procesamiento de Protocolos Maliciosos**: Bloquea el uso de esquemas `javascript:`, `vbscript:` y `data:`.
- **Soporte Recursivo**: Capacidad para limpiar arreglos multidimensionales (útil para envíos de formularios completos).
- **Codificación de Salida**: Opción integrada para escapar caracteres especiales mediante `htmlspecialchars`.

## Métodos Disponibles

### `clean(string $input, bool $escape = true)`

Este es el método principal para sanitizar una cadena de texto individual.

**Sucesión de procesos internos:**
1. **Decodificación**: Convierte entidades HTML a sus caracteres correspondientes para evitar evasiones mediante codificación.
2. **Filtrado de Patrones**: Aplica expresiones regulares para eliminar contenido peligroso.
3. **Limpieza de Etiquetas**: Ejecuta `strip_tags()` para eliminar cualquier etiqueta HTML restante.
4. **Normalización**: Sustituye múltiples espacios en blanco o saltos de línea por un único espacio.
5. **Escape (Opcional)**: Si `$escape` es `true`, aplica `htmlspecialchars` con soporte para HTML5 y UTF-8.

**Parámetros:**
- `string $input`: El texto original a limpiar.
- `bool $escape`: Define si se deben convertir caracteres especiales en entidades HTML al final del proceso.

**Retorno:**
- `string`: La cadena de texto sanitizada.

---

### `cleanArray(array $data, bool $escape = true)`

Limpia de forma recursiva todos los elementos de un arreglo. Es ideal para procesar arreglos globales como `$_POST` o `$_GET`.

**Funcionamiento:**
- Recorre cada elemento del arreglo.
- Si el valor es una cadena, llama a `clean()`.
- Si el valor es otro arreglo, se llama a sí mismo de forma recursiva.

**Parámetros:**
- `array $data`: El arreglo con los datos a sanitizar.
- `bool $escape`: Define si se aplica el escape de salida a las cadenas encontradas.

**Retorno:**
- `array`: El arreglo original con todos sus valores de texto procesados.

## Patrones de Bloqueo

La clase utiliza una lista interna de patrones (PATTERNS) para identificar y eliminar contenido malicioso:

| Patrón Detectado | Acción |
| :--- | :--- |
| `<script>...</script>` | Eliminación completa del bloque. |
| `<iframe>`, `<object>`, `<embed>`, `<applet>` | Eliminación completa para evitar marcos externos. |
| `<form>...</form>` | Eliminación para evitar inyecciones de formularios falsos. |
| `javascript:`, `vbscript:`, `data:` | Bloqueo de protocolos en atributos de enlace o src. |
| Atributos `on[evento]` | Eliminación de disparadores de JavaScript (ej. `onclick`, `onmouseover`). |

## Ejemplos de Uso

### Sanitización de una Cadena Individual

```php
$antixss = new AntiXSS();

// Ejemplo con intento de inyección
$input = "<script>alert('Ataque');</script>Hola <b onclick='hack()'>Mundo</b>";

// Versión con escape (Predeterminado)
// Resultado: "Hola Mundo" (con entidades escapadas si hubiera símbolos)
echo $antixss->clean($input);

// Versión sin escape final
$rawClean = $antixss->clean($input, false); 
```

### Sanitización de un Formulario Completo

```php
$antixss = new AntiXSS();

// Procesar todos los datos recibidos por POST
$_POST = $antixss->cleanArray($_POST);

// Ahora los datos en $_POST están libres de etiquetas y scripts peligrosos
$username = $_POST['username'];
```

## Notas Técnicas

- La clase utiliza `ENT_QUOTES | ENT_HTML5` para asegurar la máxima compatibilidad y seguridad al tratar con comillas simples, dobles y el estándar moderno de HTML.
- Es recomendable siempre mantener el parámetro `$escape` en `true` a menos que los datos vayan a ser procesados por otro motor de plantillas que realice su propio escapado.