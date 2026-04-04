# PaginatorPlus

Clase para la gestión de paginación tradicional (Server-Side sin AJAX) con soporte integrado para filtrado, ordenamiento y generación de interfaces HTML compatibles con Bootstrap.

## Características

- **Generador de Consultas**: Interfaz fluida para construir consultas SQL complejas (`JOIN`, `WHERE`, `GROUP BY`).
- **Búsqueda Integrada**: Maneja automáticamente el parámetro `search` de la URL para filtrar resultados.
- **Paginación Inteligente**: Calcula automáticamente el total de páginas y genera los enlaces de navegación con elipsis (`...`).
- **Seguridad**: Utiliza Sentencias Preparadas (PDO) para todas las cláusulas dinámicas.

## Métodos Principales

### `__construct(PDO $pdo)`
Inicializa la clase. Detecta automáticamente los parámetros `page` y `search` de la superglobal `$_GET`.

---

### `from(string $table): self`
Define la tabla principal de la consulta.

---

### `select(array $columns): self`
Define las columnas a recuperar (ej: `['id', 'name', 'date']`).

---

### `join(string $table, string $first, string $operator, string $second, string $type = 'INNER'): self`
Añade una unión a otra tabla (ej: `join('categories', 'p.cat_id', '=', 'categories.id')`).

---

### `where(string $column, string $operator, mixed $value): self`
Añade una condición a la consulta. Los valores se vinculan automáticamente mediante marcadores PDO.

---

### `search(array $columns): self`
Define en qué columnas se aplicará el término de búsqueda recibido por `$_GET['search']`.

---

### `orderBy(string $column, string $direction = 'DESC'): self`
Define el orden de los resultados.

---

### `perPage(int $perPage): self`
Define cuántos registros se mostrarán por página. Por defecto es 10.

---

### `get(): array`
Ejecuta las consultas (conteo y recuperación) y retorna el array de objetos con los resultados.

---

### `renderLinks(string $baseUrl = '?'): string`
Genera el HTML necesario para mostrar la barra de paginación de Bootstrap.
- **$baseUrl**: Prefijo de la URL para los enlaces (ej: `admin/users.php?`).

## Ejemplo de Uso Completo

### Implementación en un Controlador

```php
// 1. Inicializar
$paginator = new PaginatorPlus($connect);

// 2. Configurar consulta
$users = $paginator
    ->from('users u')
    ->select(['u.user_id', 'u.user_name', 'r.role_name'])
    ->join('roles r', 'r.role_id', '=', 'u.user_role_id')
    ->where('u.user_status', '=', 1)
    ->search(['u.user_name', 'u.user_email'])
    ->orderBy('u.user_id', 'DESC')
    ->perPage(15)
    ->get(); // Ejecuta las queries

// 3. En la vista (HTML)
foreach ($users as $user) {
    echo "<li>{$user->user_name} ({$user->role_name})</li>";
}

// 4. Mostrar paginación
echo $paginator->renderLinks();
```

## Propiedades Públicas

- **totalItems**: (int) Cantidad total de registros encontrados.
- **totalPages**: (int) Cantidad total de páginas calculadas.
