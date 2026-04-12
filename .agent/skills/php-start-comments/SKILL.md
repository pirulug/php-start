---
name: PHP-Start Comments Standard
description: Reglas para la estandarización de comentarios en línea, bloques de sección y PHPDoc dentro del framework PHP-Start.
---

# PHP-Start Comments Standard

Este documento define las reglas obligatorias para comentar el código en PHP. Mantener un estándar de documentación facilita la lectura, el mantenimiento y la colaboración en el proyecto.

## 0. Reglas Generales

- **IDIOMA:** Los comentarios deben escribirse en **Español** técnico y profesional.
- **TONO:** Evitar lenguaje informal o subjetivo. **PROHIBIDO** el uso de emojis.
- **GRAMÁTICA:** Iniciar con mayúscula y usar puntuación correcta.

## 🏗️ 1. Comentarios de Bloque (Secciones)

Se utilizan para dividir lógicamente el archivo en partes principales (ej. Acciones, Consultas, Renderizado).

- **Formato:** Doble barra `//` seguida de una línea de guiones iguales `=` de exactamente **56 caracteres** de longitud total (incluyendo el comentario).
- **Contenido:** Texto en **MAYÚSCULAS**.

**Ejemplo:**
```php
// ========================================================
// PROCESAMIENTO DE DATOS
// ========================================================
```

## 📖 2. Documentación de Funciones y Clases (PHPDoc)

Todo método, función o clase debe tener un bloque descriptivo.

- **Formato:** Estilo estándar `/** ... */`.
- **Contenido:** 
  - Breve descripción de la funcionalidad.
  - Enumeración si la funcionalidad tiene pasos clave (opcional).

**Ejemplo:**
```php
/**
 * Crea una nueva instancia de botón de tipo enlace.
 * 1. Inicializa el objeto.
 * 2. Asigna la URL y las clases base.
 */
public static function link(string $url): self { ... }
```

## ✍️ 3. Comentarios en Línea

Se usan para explicar por qué se hace algo complejo, no qué se hace (si el código es evidente).

- **Formato:** Doble barra `//` seguida de un espacio.
- **Ubicación:** Preferiblemente encima de la línea de código o al final de la misma si es muy corta.

**Ejemplo:**
```php
$user_id = $_SESSION['user_id']; // Capturamos el ID de la sesión actual
```

## 🚫 4. Comentarios Prohibidos

- **CÓDIGO MUERTO:** No dejar bloques de código comentados. Si no se usa, elíminalo.
- **REDUNDANCIA:** Evitar comentarios que solo repiten el nombre de la variable (ej. `$total = 0; // Total igual a cero`).
- **TODOs:** Los comentarios `TODO` o `FIXME` deben ser temporales y eliminarse antes del commit final de una tarea.

---
> [!IMPORTANT]
> Al refactorizar o crear nuevos archivos, este estándar debe aplicarse de forma retroactiva a cualquier lógica nueva o modificada.
