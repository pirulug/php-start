# Router

Motor de enrutamiento central para la definición y resolución de rutas del sistema. Soporta parámetros dinámicos, grupos con prefijos, contextos diferenciados, gestión de middlewares y resolución automática de archivos de lógica y vista.

## Características

- **Definición Fluida**: Construcción de rutas mediante encadenamiento de métodos (Fluent API).
- **Grupos y Prefijos**: Organiza bloques de rutas bajo un mismo prefijo y contexto compartido.
- **Parámetros Dinámicos con Regex**: Captura variables desde la URL (`{id}`) con soporte opcional para validación mediante expresiones regulares (`{id:[0-9]+}`).
- **Soporte de Middlewares**: Inyección de capas de control antes de procesar la ruta (ej: validación de permisos).
- **Resolución Inteligente**: Mapeo directo a archivos de módulos mediante la sintaxis `modulo@archivo`.

---

## Contextos de Ejecución

El framework utiliza tres contextos principales definidos mediante constantes globales en `path.config.php`. Estos contextos determinan la ruta de búsqueda de los archivos y la lógica de permisos:

- **`CTX_FRONT`** ('front'): Orientado a la cara pública del sitio. Busca archivos en `app/front/`.
- **`CTX_ADMIN`** ('admin'): Orientado al panel administrativo. Busca archivos en `app/admin/`.
- **`CTX_API`** ('api'): Orientado a servicios de datos y endpoints. Busca archivos en `app/api/`.

---

## Métodos Estáticos

### `prefix(string $prefix, string $context, callable $callback): void`
Define un bloque de rutas que comparten un prefijo de URL y un contexto de ejecución.
- **$prefix**: Cadena base (ej: `"admin"`).
- **$context**: Contexto de ejecución (`CTX_FRONT`, `CTX_ADMIN`, `CTX_API`).
- **$callback**: Función donde se definen las rutas hijas.

### `route(string $uri): self`
Inicia la definición de una nueva ruta. La URI se concatena automáticamente con el prefijo del grupo actual.
- **$uri**: Ruta relativa. Soporta parámetros dinámicos: `{param}` o `{param:regex}`.

### `resolve(string $uri): ?array`
Analiza la URI solicitada y busca una coincidencia en el registro global de rutas.
- **Retorno**: Un array con todos los datos de la ruta y los parámetros extraídos, o `null` si no hay coincidencia.

## Métodos de Instancia (Builder)

### `action(string $path): self`
Asigna el archivo de lógica que procesará la petición. 
- **Sintaxis**: Soporta `modulo@archivo` para resolución automática dentro de `app/{context}/modules/{modulo}/actions/{archivo}.action.php`.

### `view(string $path): self`
Asigna el archivo de plantilla que se renderizará.
- **Sintaxis**: Soporta `modulo@archivo` para resolución automática dentro de `app/{context}/modules/{modulo}/views/{archivo}.view.php`.

### `endpoint(string $path): self`
Similar a `action()`, pero diseñado para salidas directas (JSON, archivos, etc.) que no requieren ser envueltas en un layout.
- **Sintaxis**: Resuelve en `app/{context}/modules/{modulo}/endpoints/{archivo}.endpoint.php`.

### `layout(string $path = 'main'): self`
Define el layout maestro que envolverá la vista. Busca automáticamente en `app/{context}/layouts/`.

### `permission(string $permission): self`
Atajo para añadir un middleware de verificación de permisos.

### `middleware(string $name, $params = null): self`
Añade un middleware a la cola de ejecución de la ruta.

### `setContext(string $context): self`
Cambia manualmente el contexto de la ruta (útil fuera de bloques `prefix`).

### `register(): void`
**OBLIGATORIO**: Confirma y guarda la configuración de la ruta. Debe ser el último método de la cadena.

## Ejemplo de Uso

### Definición Modular
```php
// Rutas Administrativas
Router::prefix('admin', CTX_ADMIN, function() {
  
  Router::route('dashboard')
    ->view('dashboard@index')
    ->register();

  // Parámetro con validación numérica (regex)
  Router::route('usuarios/editar/{id:[0-9]+}')
    ->action('users@edit')
    ->view('users@form')
    ->permission('users.edit')
    ->register();
});

// Rutas de Front-end (Área Pública)
Router::prefix('', CTX_FRONT, function() {

  Router::route('/')
    ->view('home@index')
    ->register();

  Router::route('blog/{slug}')
    ->view('blog@post')
    ->register();
});

// Definición de Bloque API
Router::prefix('api', CTX_API, function() {
  
  Router::route('v1/auth/login')
    ->endpoint('auth@login')
    ->register();

  Router::route('v1/users/profile')
    ->endpoint('users@profile')
    ->permission('api.users')
    ->register();
});
```

## Resolución de Parámetros Dinámicos

El Router permite extraer valores variables de la URL de forma sencilla:

- `{slug}`: Coincide con cualquier caracter excepto `/`.
- `{id:[0-9]+}`: Solo coincide si el valor es numérico.

Si la ruta es `blog/post/{slug}` y la URL visitada es `blog/post/mi-articulo`, el Router devolverá los parámetros extraídos en el array de resolución:
```php
'params' => [
  'slug' => 'mi-articulo'
]
```