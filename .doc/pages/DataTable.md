# DataTableServerSide

Clase diseñada para facilitar el manejo de tablas con procesamiento del lado del servidor (Server-Side Processing) compatible con DataTables.js. Gestiona automáticamente la paginación, el filtrado/búsqueda y el ordenamiento de grandes volúmenes de datos mediante consultas SQL eficientes.

## Características

- **Procesamiento Eficiente**: Solo recupera de la base de datos los registros necesarios para la vista actual (LIMIT/OFFSET).
- **Interfaz Fluida**: Métodos encadenables para definir la consulta SQL (`select`, `from`, `joins`, `where`).
- **Búsqueda Global**: Soporte para filtrar por múltiples columnas simultáneamente.
- **Ordenamiento Dinámico**: Traduce automáticamente los índices de columna de DataTables a nombres de columna SQL.

## Métodos Principales

### `__construct(PDO $db)`
Inicializa la clase con la conexión a la base de datos.

---

### `select(string $select): self`
Define las columnas a recuperar (ej: `"id, name, email"`). Por defecto es `*`.

---

### `from(string $from): self`
Define la tabla principal (ej: `"users"`).

---

### `joins(string $joins): self`
Añade cláusulas JOIN a la consulta (ej: `"LEFT JOIN roles ON roles.id = users.role_id"`).

---

### `where(string $where): self`
Añade condiciones fijas a la consulta (ej: `"status = 'active'"`). Por defecto es `1=1`.

---

### `columns(array $columns): self`
Mapea el orden de las columnas según lo definido en el HTML/JS de DataTables. Esto permite que el ordenamiento de la UI funcione correctamente con SQL.
- **$columns**: Un array indexado de nombres de columnas.

---

### `searchable(array $searchable): self`
Define las columnas en las que se realizará la búsqueda global.
- **$searchable**: Array de nombres de columnas.

---

### `execute(): array`
Procesa la petición POST de DataTables y ejecuta las consultas.
- **Retorno**: Un array compatible con el formato JSON de DataTables (`draw`, `recordsTotal`, `recordsFiltered`, `rows`).

## Ejemplo de Uso

### Backend (endpoint AJAX)

```php
// En un archivo como api/get_users.php
$dt = new DataTableServerSide($connect);

$response = $dt
    ->select("u.user_id, u.user_name, r.role_name, u.user_created_at")
    ->from("users u")
    ->joins("LEFT JOIN roles r ON r.role_id = u.user_role_id")
    ->where("u.user_status = 1")
    ->columns(['u.user_id', 'u.user_name', 'r.role_name', 'u.user_created_at']) // Orden exacto del HTML
    ->searchable(['u.user_name', 'u.user_email'])
    ->execute();

header('Content-Type: application/json');
echo json_encode($response);
```

### Respuesta del Método `execute()`

La clase retorna una estructura que incluye:
- `draw`: Contador de peticiones para sincronización.
- `recordsTotal`: Total de registros en la tabla sin filtros.
- `recordsFiltered`: Total de registros encontrados después de aplicar filtros de búsqueda.
- `rows`: Array de objetos con los datos de los registros.

## Consideraciones Técnicas

- La clase asume que los parámetros (`draw`, `start`, `length`, `search`, `order`) se envían vía `$_POST`.
- Utiliza **Sentencias Preparadas** (bindValue) para prevenir inyecciones SQL en los parámetros de búsqueda y paginación.
- Para el ordenamiento, los nombres de las columnas en `columns()` deben coincidir exactamente con los alias o nombres reales usados en `select()`.
