# Sidebar

Clase utilitaria para la construcción dinámica y renderizado del menú lateral (sidebar) del panel administrativo. Permite definir elementos, grupos colapsables y encabezados con soporte para iconos, permisos y detección automática de estados activos.

## Características

- **Interfaz Fluida**: Permite encadenar métodos como `icon()` y `can()` para una definición legible.
- **Grupos Colapsables**: Soporta la agrupación de elementos relacionados en menús desplegables.
- **Inferencia de Estado Activo**: Detecta automáticamente si un ítem o grupo debe estar resaltado basándose en la URL actual, incluyendo coincidencias semánticas (ej: `/panel/user` activa un ítem ligado a `/panel/users`).
- **Control de Permisos**: Integración nativa con el sistema de permisos del proyecto para ocultar elementos automáticamente.

## Métodos Estáticos

### `header(string $text): void`
Añade un encabezado de sección al menú.

---

### `item(string $text, string $url): self`
Añade un elemento individual al menú.
- **$text**: Etiqueta visible.
- **$url**: Ruta de destino.
- **Retorno**: Instancia de la clase para encadenamiento.

---

### `group(string $text, ?string $icon = null, ?callable $callback = null): self`
Crea un grupo colapsable.
- **$text**: Nombre del grupo.
- **$icon**: Icono de Feather Icons.
- **$callback**: (Opcional) Función anónima para definir los ítems internos del grupo.

---

### `render(): void`
Genera el HTML final del sidebar basado en los ítems registrados.

## Métodos de Instancia (Chaining)

### `icon(string $icon): self`
Asigna un icono de la librería **Feather Icons** al último elemento o grupo creado.

---

### `can(string $permission, string $context = CTX_ADMIN): self`
Restringe la visibilidad del elemento al permiso especificado.

---

### `activeWhen(...$patterns): self`
Define patrones adicionales de URL que deben marcar este elemento como activo.

## Ejemplo de Uso

### Definición Completa en el Panel

```php
// Encabezado
Sidebar::header("Administración");

// Ítem simple con icono y permiso
Sidebar::item("Panel Principal", "panel/dashboard")
    ->icon("home")
    ->can("dashboard_view");

// Grupo colapsable con callback
Sidebar::group("Usuarios", "users", function($menu) {
    $menu->item("Lista de Usuarios", "panel/users");
    $menu->item("Roles y Permisos", "panel/roles")
        ->can("roles_manage");
});

// Ítem con patrones de activación manual
Sidebar::item("Reportes", "panel/reports")
    ->icon("bar-chart")
    ->activeWhen("panel/stats", "panel/graficos");
```

## Lógica de Activación Semántica

La clase incluye el método `isModuleAutoMatch` que permite que un grupo o ítem se mantenga activo incluso si la URL actual es una sub-ruta o tiene variaciones de pluralización. Por ejemplo:
- Si el ítem apunta a `/panel/cash`, se mantendrá activo en `/panel/cash/create` o `/panel/cashs`.
- Esto asegura que el menú colapsable no se cierre mientras el usuario navega dentro de las funciones de un mismo módulo.
