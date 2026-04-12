---
name: PHP-Start UI Standards
description: Reglas estrictas de generación de HTML, UI/UX y clases de Bootstrap 5 prohibidas para mantener la compatibilidad con Dark Mode.
---

# PHP-Start UI & UX Standards

Sigue estas reglas al generar cualquier bloque de código HTML o componentes de vista para asegurar una estética premium y compatibilidad total con el sistema de temas.

## 🚫 1. Clases de Bootstrap PROHIBIDAS (Dark Mode Safety)

Para que el framework gestione correctamente el cambio de tema (Light/Dark), tienes **estrictamente prohibido** usar las siguientes clases nativas:

- **Sombras:** ❌ `.shadow`, ❌ `.shadow-sm`, ❌ `.shadow-lg`. 
  - *Alternativa:* Usa los bordes nativos del framework o clases CSS personalizadas que se adapten al modo oscuro.
- **Fondos:** ❌ `.bg-white`, ❌ `.bg-light`, ❌ `.bg-dark`.
  - *Alternativa:* Deja que el contenedor (`.card`, `.modal`, etc.) use su color de fondo automático.
- **Texto:** ❌ `.text-white`, ❌ `.text-light`, ❌ `.text-dark`.
  - *Alternativa:* Usa `.text-body` o deja que el color se herede para asegurar legibilidad.
- **Bordes:** ❌ `.border-0` en elementos `.card`. Se requiere el borde sutil para delimitar secciones en Dark Mode.
- **Redondeado:** ❌ `.rounded` en elementos `.card`. Las tarjetas ya tienen su radio de borde predefinido.
- **Grupos:** ❌ `.btn-group` en columnas de acción de tablas de datos. Usa botones individuales separados para evitar colapsos visuales.

- No usar `.mb-4` si no `.mb-3`
- No usar `.p-4` si no `.p-3`
- No usar `.g-4` si no `.g-3`
- No usar `.mt-4` si no `.mt-3`
- No usar `.pt-4` si no `.pt-3`
- No usar `.gt-4` si no `.gt-3`

- No usar `.bg-body-tertiary` si no `.bg-body`

- No usar `.border-0`
- No usar `.rounded`

## 🏗️ 2. Estructura de Bloques en Vistas

Cada archivo `.view.php` debe seguir esta estructura de bloques obligatoria:

```php
<?php start_block("title") ?> 
    <!-- Título de la página -->
<?php end_block() ?>

<?php start_block('breadcrumb'); ?>
<?php render_breadcrumb([
  ['label' => 'Dashboard', 'link' => admin_route('dashboard')],
  ['label' => 'Modulo', 'link' => admin_route('modulo')],
  ['label' => 'Acción']
]) ?>
<?php end_block(); ?>

<?php start_block("css") ?>
    <!-- Estilos específicos si son necesarios -->
<?php end_block() ?>

<?php start_block("js") ?>
    <!-- Inicialización de plugins y scripts -->
<?php end_block() ?>
```

## 💎 3. Estética Premium

- **Iconografía:** Usa siempre **FontAwesome 6** (clases `fa-solid`, `fa-brands`, etc.).
- **Botones:** Prefiere botones con iconos y etiquetas `text-uppercase small fw-bold` para un look moderno en la administración.
- **Tablas:** Siempre envuelve las tablas en un contenedor `.table-responsive`.
