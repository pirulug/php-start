# Router

Clase central para la definición y resolución de rutas del sistema. Soporta agrupamiento por prefijos, contextos diferenciados (Front-end/Admin), middlewares, parámetros dinámicos y seguimiento analítico integrado.

## Características

- **Definición Fluida**: Construcción de rutas mediante encadenamiento de métodos.
- **Grupos y Prefijos**: Organiza rutas bajo un mismo prefijo y contexto con un solo bloque.
- **Parámetros Dinámicos**: Permite capturar variables desde la URL usando la sintaxis `{nombre_parametro}`.
- **Soporte de Middlewares**: Capacidad para inyectar lógica de control (como validación de permisos) antes de procesar la ruta.
- **Contextos**: Diferenciación nativa entre el área pública (`CTX_FRONT`) y la administrativa (`CTX_ADMIN`).

## Métodos Estáticos

### `prefix(string $prefix, string $context, callable $callback): void`
Define un bloque de rutas que comparten prefijo y contexto.
- **$prefix**: Cadena que se antepondrá a todas las rutas del bloque.
- **$context**: Constante de contexto (`CTX_FRONT` o `CTX_ADMIN`).
- **$callback**: Función anónima donde se definen las rutas.

---

### `route(string $uri): self`
Inicia la definición de una nueva ruta.
- **$uri**: Ruta relativa (ej: `"perfil/{id}"`).

---

### `resolve(string $uri): ?array`
Busca una coincidencia para la URI recibida entre todas las rutas registradas.
- **Retorno**: Array con la configuración de la ruta y sus parámetros extraídos, o `null` si no hay coincidencias.

## Métodos de Instancia (Builder)

### `action(string $path): self`
Define el archivo de lógica (`controller`) que procesará la petición.

---

### `view(string $path): self`
Define el archivo de vista (`.php` o `.pug`) que se renderizará.

---

### `layout(string $path): self`
Define el layout (plantilla base) que envolverá a la vista.

---

### `permission(string $permission): self`
Añade automáticamente un middleware de verificación de permisos.

---

### `middleware(string $name, $params = null): self`
Añade un middleware personalizado a la ruta.

---

### `analytic(string $title, ?string $uri = null): self`
Configura los metadatos de analítica (título de página y URI opcional) para esta ruta.

---

### `register(): void`
**Importante**: Este método confirma el registro de la ruta en la lista global. Debe llamarse al final de cada definición.

## Ejemplo de Uso

### Definición de Rutas en `routes/`

```php
// Rutas de administración
Router::prefix('panel', CTX_ADMIN, function() {
    
    // Vista simple
    Router::route('dashboard')
        ->view('admin/dashboard')
        ->analytic('Panel de Control')
        ->register();

    // Ruta con parámetros y permisos
    Router::route('users/edit/{id}')
        ->action('admin/users/update')
        ->view('admin/users/form')
        ->permission('users_manage')
        ->register();
});

// Rutas públicas
Router::route('articulo/{slug}')
    ->view('front/article')
    ->layout('main')
    ->register();
```

## Resolución de Parámetros

Cuando una ruta contiene parámetros (`{slug}`, `{id}`), el método `resolve()` los extrae automáticamente. Si la URL visitada es `/articulo/mi-primer-post`, el resultado incluirá:

```php
[
    'uri' => 'articulo/mi-primer-post',
    'params' => [
        'slug' => 'mi-primer-post'
    ],
    // ... resto de la configuración
]
```
