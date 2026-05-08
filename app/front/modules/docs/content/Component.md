# Component Engine

El `Component Engine` es la factoría unificada de elementos de interfaz del framework. Agrupa las funcionalidades de botones, paginación y filtros bajo una estructura coherente y fácil de mantener.

## Estructura de Acceso

Puedes acceder a los componentes de dos maneras:
1.  **Vía Factoría `Component`**: Recomendado para nuevos desarrollos.
2.  **Vía Clases Originales**: Mantenido por compatibilidad (`ActionBtn`, `Paginator`, `FilterBar`).

```php
// Ejemplo usando la factoría
Component::btn()->save()->render();
Component::pager($total)->render();
Component::filter($route)->render();
```

---

## 1. Botones de Acción (ActionBtn)

Permite generar botones estandarizados con permisos y confirmaciones integradas.

### Métodos de Factoría
| Método | Tipo | Clase CSS | Propósito |
| :--- | :--- | :--- | :--- |
| `link($url)` | link | `btn-outline-secondary` | Enlace genérico. |
| `save($url)` | submit/link | `btn-primary` | Acción principal (Guardar). |
| `edit($url)` | link | `btn-success` | Edición de recursos. |
| `delete($url)` | delete | `btn-outline-danger` | Acción destructiva con SweetAlert. |
| `view($url)` | link | `btn-info` | Ver detalles. |
| `cancel($url)` | button/link | `btn-secondary` | Cancelar o cerrar. |
| `active($url)` | link | `btn-info` | Activar recurso. |
| `deactivate($url)` | link | `btn-warning` | Desactivar recurso. |

### Configuración Fluida
```php
Component::btn()
  ->edit($url)
  ->can('users.edit')         // Validación de permisos
  ->icon('fa-solid fa-user')   // Icono personalizado
  ->text('Perfil')            // Texto personalizado
  ->render();
```

---

## 2. Paginación (Paginator)

Gestiona la lógica de navegación y leyendas de registros.

### Uso Básico
```php
$pager = Component::pager($total_rows, $limit)->items('usuarios');

echo $pager->legend(); // "Mostrando 10 de 100 usuarios"
echo $pager->render(); // Lista de páginas (1, 2, 3...)
```

---

## 3. Barra de Filtros (FilterBar)

Genera formularios de búsqueda y filtrado con persistencia de estado automática.

### Uso Básico
```php
echo Component::filter(admin_route('users'))
  ->select('role', 'Todos los roles', $roles, 'role_id', 'role_name')
  ->select('status', 'Todos los estados', [1 => 'Activos', 0 => 'Inactivos'])
  ->search('Buscar...')
  ->render();
```

> [!TIP]
> El botón de "Limpiar Filtros" aparece automáticamente solo si el usuario ha realizado alguna búsqueda o aplicado algún filtro.
