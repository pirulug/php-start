---
name: PHP-Start Home Standards
description: Guía de arquitectura y estándares para el desarrollo del área pública (Frontend) del sistema.
---

# PHP-Start Home Standards

Este documento define la arquitectura y los estándares obligatorios para el desarrollo de módulos del área pública (Frontend) en el sistema.

## 1. Arquitectura Modular

El Frontend se organiza en módulos dentro del directorio `app/home/`:

- `app/home/modules.php`: Registro de módulos activos para el área pública.
- `app/home/modules/{modulo}/router.php`: Definición de rutas del módulo.
- `app/api/modules/{modulo}/actions/`: Lógica (Controlador) en archivos `.action.php`.
- `app/api/modules/{modulo}/views/`: Maquetación HTML en archivos `.view.php`.

### Jerarquía:
```text
app/home/
├── modules/
│   ├── account/
│   │   ├── actions/
│   │   │   └── profile.action.php
│   │   ├── views/
│   │   │   └── profile.view.php
│   │   └── router.php
│   └── modules.php
└── layouts/
```

## 2. Enrutamiento del Frontend

Las rutas se definen sin prefijo, ya que el contexto "Home" es el predeterminado para la raíz del sistema.

### Ejemplo de Definición:
```php
Router::route('contacto')
  ->action(home_action('public.contact'))
  ->view(home_view('public.contact'))
  ->layout(home_layout())
  ->register();
```

## 3. Sistema de Plantillas y Bloques

El sistema utiliza un motor de herencia basado en bloques definidos en el layout principal (`app/home/layouts/main.layout.php`).

### Inyección de Contenido en Vistas:
```php
<?php start_block('title'); ?>
  Título de mi Página
<?php end_block(); ?>

<?php start_block("css") ?>
    <!-- Estilos específicos si son necesarios -->
<?php end_block() ?>

<?php start_block("js") ?>
    <!-- Inicialización de plugins y scripts -->
<?php end_block() ?>

<!-- El contenido fuera de bloques se inyecta automáticamente en $content del layout -->
<div class="container py-3">
  <h1>Bienvenido</h1>
</div>
```

## 4. Helpers Mandatarios

Para garantizar la portabilidad y correcta resolución de rutas, se **deben** usar los siguientes helpers:

- `home_action("modulo.archivo")`: Resuelve la ruta al archivo lógico.
- `home_view("modulo.archivo")`: Resuelve la ruta al archivo de vista.
- `home_layout()` / `home_layout("nombre")`: Carga el archivo de estructura base.
- `home_route("ruta")`: Genera una URL absoluta al Frontend.

## 5. Seguridad y Sesiones

### Middleware `auth_home`
Se utiliza para proteger áreas del frontend que requieren inicio de sesión (ej. Perfil del Usuario). Si el usuario no está logueado, lo redirige automáticamente a `/signin`.

### PDO y Datos
- Todas las consultas deben seguir `php-start-rules`.
- Usar `is_logged_in()` para condicionales visuales en el layout o vistas.

## 6. Estándares de UI/UX

- Seguir estrictamente la guía de `php-start-ui`.
- Usar clases de Bootstrap 5 compatibles con Dark Mode (`bg-body`, `text-body`).
- Mantener el espaciado basado en el factor `-3` (`mb-3`, `p-3`, `g-3`).
