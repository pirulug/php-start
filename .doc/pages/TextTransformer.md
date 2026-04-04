# TextTransformer

La clase `TextTransformer` es una herramienta versátil diseñada para la normalización, transformación y limpieza de cadenas de texto mediante una interfaz fluida (Fluent Interface). Permite encadenar múltiples reglas de transformación de forma secuencial para obtener resultados precisos en la generación de identificadores, nombres de usuario o limpieza de datos.

## Características Principales

- **Interfaz Fluida**: Permite encadenar métodos de transformación para mayor legibilidad.
- **Transliteración Inteligente**: Soporte para convertir caracteres Unicode (acentos, ñ, etc.) a sus equivalentes ASCII.
- **Presets Predefinidos**: Atajos para tareas comunes como la generación de slugs de URL o usernames seguros.
- **Generador de IDs Cortos**: Utilidad integrada para crear identificadores aleatorios personalizables.
- **Extensibilidad**: Soporte para inyectar presets personalizados mediante el constructor.

---

## Métodos de Configuración Base

Estos métodos permiten definir el texto de entrada y las reglas de transformación estándar.

- `text(string $text)`: Establece la cadena de texto inicial sobre la cual se aplicarán las reglas.
- `replace(string $pattern, string $replacement)`: Añade una regla de reemplazo basada en expresiones regulares.
- `lowercase()`: Convierte el texto a minúsculas con soporte multibyte (UTF-8).
- `uppercase()`: Convierte el texto a mayúsculas con soporte multibyte (UTF-8).
- `transliterate()`: Realiza la transliteración de caracteres especiales. Requiere la extensión PHP `intl` para un rendimiento óptimo, con un fallback manual integrado.

---

## Presets Predefinidos (Atajos)

Proporcionan configuraciones rápidas para escenarios de uso frecuente:

| Preset | Descripción |
| :--- | :--- |
| `slug()` | Translitera y reemplaza caracteres no alfanuméricos por guiones. Ideal para URLs. |
| `username()` | Translitera y limpia el texto dejando solo caracteres válidos para nombres de usuario (`a-z`, `0-9`, `_`, `.`). |
| `leet()` | Aplica una transformación básica de ofuscación alfanumérica (ej. `a` => `4`, `e` => `3`). |
| `safe()` | Elimina cualquier carácter que no sea alfanumérico, guion, punto o espacio. |

---

## Utilidades de Generación

- `shortId(int $length = 6, string $alphabet = null)`: Genera una cadena aleatoria de la longitud especificada. Se puede proporcionar un alfabeto personalizado. Este método ignora el texto previo establecido por `text()`.

---

## Control y Ejecución

- `reset()`: Elimina todas las reglas de transformación acumuladas hasta el momento.
- `apply()`: Ejecuta secuencialmente todas las reglas configuradas y devuelve la cadena resultante. Al final del proceso, realiza un `trim` automático de caracteres sobrantes (`-`, `_`, espacios).

---

## Ejemplos de Implementación

### 1. Generación de Slug para Artículo
```php
$transformer = new TextTransformer();
$slug = $transformer->text('Canción de Verano 2024!')
                    ->slug()
                    ->lowercase()
                    ->apply();
// Resultado: "cancion-de-verano-2024"
```

### 2. Formateo de Username Seguro
```php
$username = (new TextTransformer())
            ->text('Juan Pérez C-123')
            ->username()
            ->lowercase()
            ->apply();
// Resultado: "juanperezc-123"
```

### 3. Generación de ID Corto Personalizado
```php
$shortId = (new TextTransformer())
           ->shortId(12, 'ABCDEF0123456789')
           ->apply();
// Resultado: (ejemplo) "4A2F8B1C9D0E"
```

### 4. Uso de Presets Personalizados (Inyectados)
```php
$custom = [
    'dotSeparated' => [
        '/\s+/' => '.',
        '/[^a-z0-9\.]/i' => ''
    ]
];

$transformer = new TextTransformer($custom);
$result = $transformer->text('Mi Texto Demo')
                      ->dotSeparated()
                      ->lowercase()
                      ->apply();
// Resultado: "mi.texto.demo"
```

## Notas Técnicas

- **Dependencias**: Se recomienda habilitar la extensión `intl` en PHP para que la transliteración (`transliterate`) sea exacta y soporte todos los idiomas Unicode.
- **Orden de Ejecución**: Las reglas se ejecutan estrictamente en el orden en que son llamadas. Por ejemplo, llamar a `lowercase()` antes o después de `slug()` puede afectar el resultado final si el preset depende de comparaciones sensibles a mayúsculas.
- **Multibyte**: Todas las transformaciones internas utilizan funciones `mb_` para asegurar la integridad de textos en UTF-8.