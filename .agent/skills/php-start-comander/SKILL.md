---
name: PHP-Start Comander & Pagination Expert
description: Reglas para la generación de CRUDs mediante CLI y la implementación OBLIGATORIA de paginación manual (Sin PaginatorPlus).
---

# PHP-Start Comander & Manual Pagination

Esta Skill rige la creación de nuevos módulos y la visualización de listados de datos. Es un estándar de nivel Senior para el framework.

## 1. Workflow de Creación (Comander CLI)

**REGLA DE ORO:** Antes de escribir una sola línea de código para un nuevo módulo, el **PRIMER PASO** es indicar el comando de generación.

```bash
# Formato: php comander/modules.php create [plural] [singular] --context=[admin|home|api|ajax]
php comander/modules.php create products product --context=admin
```

## 2. Paginación Manual (¡PROHIBIDO PaginatorPlus!)

Para los archivos `list.action.php` y `list.view.php`, no se deben usar librerías de terceros. La paginación debe ser artesanal.

### A. Lógica en `list.action.php` (Backend)
1. Capturar página actual: `$p = (int)($_GET['p'] ?? 1);`.
2. Realizar `COUNT(*)` con `prepare/bindParam` para obtener `$total_rows`.
3. Calcular `$total_pages = ceil($total_rows / $limit);`.
4. Calcular `$offset = ($p - 1) * $limit;`.
5. Ejecutar consulta final con `LIMIT :limit OFFSET :offset` (usando `PDO::PARAM_INT`).

### B. Interfaz en `list.view.php` (Frontend)
Generar un paginador Bootstrap 5 con truncado (elipsis) dinámico.
**Formato visual esperado:** `[Primero] [1] [..] [4] [5] [6] [..] [100] [Último]`

## 3. Manejo de Botoneras (`ActionBtn`)

Las acciones en tablas deben usar la clase `ActionBtn` y siempre cifrar los parámetros sensibles (como IDs) que viajan por la URL.

```php
// Ejemplo en Vista
<?= ActionBtn::edit(admin_route("modulo/edit", [$cipher->encrypt($item->id)]))->can('modulo.edit') ?>

<?= ActionBtn::delete(admin_route("modulo/delete", [$cipher->encrypt($item->id)]))
    ->can('modulo.delete')
    ->saTitle('¿Eliminar?')
    ->saText('Es irreversible.') ?>
```

## 4. Procesamiento de IDs en Controller
Siempre descifrar el ID al recibirlo en la `Action`:
`$id = $cipher->decrypt($args['id']);`
