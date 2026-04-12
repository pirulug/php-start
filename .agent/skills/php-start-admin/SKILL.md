---
name: PHP-Start Admin Standards
description: Reglas para la creación y gestión del panel de administración (Backend) en el framework PHP-Start.
---

# PHP-Start Admin Standards

Este documento define la arquitectura y los estándares obligatorios para el desarrollo de módulos y páginas dentro del panel de administración (Backend).

## 1. Arquitectura de Módulos

El panel administrativo se organiza en módulos segregados dentro de `app/admin/modules/`:

- `app/admin/modules.php`: Registro de módulos administrativos activos.
- `app/admin/modules/{modulo}/router.php`: Definiciones de rutas del módulo.
- `app/admin/modules/{modulo}/actions/`: Lógica de procesamiento (Controladores).
- `app/admin/modules/{modulo}/views/`: Interfaz de usuario (Vistas).

### Jerarquía:
```text
app/admin/
├── modules/
│   ├── users/
│   │   ├── actions/
│   │   │   └── list.action.php
│   │   ├── views/
│   │   │   └── list.view.php
│   │   └── router.php
│   └── modules.php
└── layouts/
```

## 2. Enrutamiento Administrativo

Todas las rutas administrativas deben definirse usando la clase `Router` y se encuentran bajo el prefijo definido en `PATH_ADMIN` (usualmente `/admin`).

### Estándar de Registro:
```php
Router::route('mi-modulo/lista')
  ->action(admin_action("modulo.lista"))
  ->view(admin_view("modulo.lista"))
  ->layout(admin_layout())
  ->middleware('auth_admin')
  ->permission("modulo.lista")
  ->register();
```

## 3. Seguridad y Control de Acceso

### Niveles de Protección
1.  **`auth_admin`**: Middleware de nivel superior que asegura que el usuario tenga una sesión válida y acceso al panel de administración.
2.  **`permission("slug.permiso")`**: Middleware granular que valida si el rol del usuario tiene asignado el permiso específico para la acción o vista.

*Es obligatorio usar ambos para cualquier ruta de gestión de datos.*

## 4. Estándares de Plantilla (Vistas)

Las vistas administrativas deben heredar del layout principal (`app/admin/layouts/main.layout.php`) y definir los bloques necesarios.

### Bloques Requeridos:
- `title`: Título de la página que aparece en la pestaña del navegador.
- `breadcrumb`: Navegación secundaria usando el helper `render_breadcrumb()`.
- `css` / `js`: Cargadores dinámicos para recursos específicos como DataTables o Select2.

### Ejemplo de Vista:
```php
<?php start_block('title'); ?> Usuarios <?php end_block(); ?>

<?php start_block('breadcrumb'); ?>
<?php render_breadcrumb([
  ['label' => 'Dashboard', 'link' => admin_route('dashboard')],
  ['label' => 'Usuarios']
]) ?>
<?php end_block(); ?>

<?php start_block("css") ?>
    <!-- Estilos específicos si son necesarios -->
<?php end_block() ?>

<?php start_block("js") ?>
    <!-- Inicialización de plugins y scripts -->
<?php end_block() ?>

<!-- Contenido Principal -->
<div class="card">...</div>
```

## 5. Helpers de Administración

Para mantener la integridad de las rutas y archivos, se **deben** usar los helpers de contexto:

- `admin_action("modulo.archivo")`: Resuelve la ruta al controlador.
- `admin_view("modulo.archivo")`: Resuelve la ruta a la vista.
- `admin_layout("nombre")`: Carga el layout base (por defecto 'main').
- `admin_route("ruta")`: Genera una URL absoluta al panel administrativo.

## 6. Estándares de UI y UX

### Consistencia Visual
- **Tablas**: Usar la clase `.table-hover` y alineación media `.align-middle`.
- **Botones de Acción**: Usar iconos de FontAwesome alineados a la derecha en tablas.
- **Espaciado**: Seguir el estándar de `php-start-ui` con factor `-3`.
- **Notificaciones**: Utilizar el sistema `$notifier` para mensajes de éxito, advertencia o peligro.
