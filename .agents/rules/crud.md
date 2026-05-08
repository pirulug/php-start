# Estándares de Desarrollo CRUD

Este documento define las reglas inquebrantables para la creación de módulos CRUD (Create, Read, Update, Delete) en el framework, asegurando consistencia visual, seguridad y mantenibilidad.

## 1. Estructura de Archivos del Módulo
Cada módulo debe seguir la estructura Action-View:
- `actions/list.action.php`
- `actions/new.action.php`
- `actions/edit.action.php`
- `actions/delete.action.php`
- `views/list.view.php`
- `views/new.view.php`
- `views/edit.view.php`

## 2. Paginación Manual 
Para los archivos `list.action.php` y `list.view.php`, no se deben usar librerías de terceros. La paginación debe ser artesanal.

### A. Lógica en `list.action.php` (Backend)
1. Capturar página actual: `$p = (int)($_GET['p'] ?? 1);`.
2. Realizar `COUNT(*)` con `prepare/bindParam` para obtener `$total_rows`.
3. Calcular `$total_pages = ceil($total_rows / $limit);`.
4. Calcular `$offset = ($p - 1) * $limit;`.
5. Ejecutar consulta final con `LIMIT :limit OFFSET :offset` (usando `PDO::PARAM_INT`).

### B. Interfaz en `list.view.php` (Frontend)
Generar un paginador Bootstrap 5 con truncado (elipsis) dinámico.
- **Contenedor obligatorio**: `.bg-body .p-3 .rounded .d-flex .align-items-center .justify-content-between .sticky-bottom`.
- **Izquierda (Leyenda)**: `<div class="legend"><span class="fw-bold">Mostrando X de Y registros</span></div>`.
- **Derecha (Navegación)**: Lista `.pagination` alineada a la derecha.
- **Formato visual esperado:** `[Primero] [1] [..] [4] [5] [6] [..] [100] [Último]`.
- **Componentes**:
  - Botones "Primero" y "Último" con texto en mayúsculas y `.small .fw-bold`.
  - Iconos de `FontAwesome` para "Anterior" y "Siguiente".
  - Lógica de persistencia de filtros: Reconstruir la URL con `http_build_query($_GET)` eliminando el parámetro `p` previo.

## 3. Estándares de Listado (Interfaz)
Para mantener la coherencia visual, los listados deben seguir esta estructura dividida en tres bloques independientes (`.bg-body .p-3 .rounded .mb-3`).

### A. Cabecera (Acciones y Filtros)
Se utiliza el componente `Button` para acciones principales y `Filter` para el buscador y selectores.
```php
<div class="bg-body p-3 rounded mb-3 text-end">
  <!-- Botón de Acción Principal -->
  <?= Button::new(admin_route('modulo/new'))->text('Nuevo Elemento') ?>

  <hr class="my-2">

  <!-- Formulario de Filtros -->
  <?= Filter::make(admin_route('modulo'))
    ->select('categoria', 'Todas las categorías', $categorias, 'cat_id', 'cat_name')
    ->search('Buscar...')
    ->render() ?>
</div>
```

### B. Cuerpo (Tabla de Datos)
Se utiliza el componente `Table` para renderizar elementos atómicos dentro de las celdas.
```php
<div class="bg-body p-3 rounded mb-3">
  <div class="table-responsive">
    <table class="table table-hover align-middle table-sm m-0">
      <thead>
        <tr>
          <th class="ps-3">Usuario</th>
          <th>Estado</th>
          <th class="text-end pe-3">Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($items as $item): ?>
          <tr>
            <td class="ps-3 py-3">
              <div class="d-flex align-items-center gap-3">
                <?= Table::avatar(storage_uploads($item->img, 'modulo'), $item->name)->circle() ?>
                <div class="d-flex flex-column">
                  <?= Table::text($item->name)->bold() ?>
                  <?= Table::text($item->email)->muted()->small() ?>
                </div>
              </div>
            </td>
            <td><?= Table::status($item->status) ?></td>
            <td class="text-end pe-3">
              <div class="d-flex justify-content-end gap-1">
                <?= Button::edit(admin_route('modulo/edit', [$cipher->encrypt($item->id)])) ?>
                <?= Button::delete(admin_route('modulo/delete', [$cipher->encrypt($item->id)])) ?>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
```

### C. Pie (Paginación y Leyenda)
Se utiliza el componente `Pager` para gestionar la navegación y la información de registros.
```php
<?php $pager = Pager::make($total_rows, $limit)->items('elementos'); ?>

<?php if ($total_rows > $limit): ?>
  <div class="bg-body p-3 rounded d-flex align-items-center justify-content-between sticky-bottom">
    <?= $pager->legend() ?>
    <?= $pager->render() ?>
  </div>
<?php endif; ?>
```

## 4. Formularios (`new.view.php` & `edit.view.php`)

### A. Estructura y Estilos
- **Layout**: Preferir un diseño de dos columnas (`col-lg-8` para datos principales, `col-lg-4` para secundarios/imágenes).
- **Cards**: Usar `.card .mb-3` sin clases adicionales de bordes o sombras.
- **Botonera de Acción**:
  - Debe ir **fuera** del card.
  - Usar `.bg-body .p-3 .rounded .d-flex .justify-content-end .gap-2 .sticky-bottom`.
  - Botón "Cancelar" con `.btn-outline-secondary`.
  - Botón "Guardar" con `.btn-primary`.

### B. Inputs y Etiquetas
- **Clases Base**: Todos los campos de texto deben usar `.form-control`. Los selectores deben usar `.form-select`.
- **Prohibición de Input Group**: Está estrictamente prohibido envolver inputs en `.input-group` para añadir iconos o textos (prepend/append). Los campos deben ser limpios.
- **Excepción de Password**: La única excepción permitida para `.input-group` es el campo de contraseña para incluir el botón de toggle dinámico:
  ```html
  <div class="input-group">
    <input type="password" class="form-control" name="...">
    <button class="btn btn-outline-secondary" type="button" data-pr-toggle-password="">
      <i class="fa-regular fa-eye"></i>
    </button>
  </div>
  ```
- **Labels**: Deben usar **únicamente** la clase `.form-label`. Está prohibido añadir clases de estilo como `.fw-bold`, `.small`, `.text-uppercase`, etc. Marcar requeridos con `<span class="text-danger">*</span>`.
- **Naming**: `entidad_campo` (ej: `user_login`, `user_email`).
- **Limpieza**:
  - Backend (Input línea única): `clear_input($_POST['...'])`.
  - Backend (Textarea / Multilínea): `clear_textarea($_POST['...'])` (Preserva saltos de línea).
  - Frontend: `value="<?= clear_html($data) ?>"`.
- **Persistencia de Datos**: En caso de error al enviar el formulario, es **obligatorio** que los campos mantengan el valor ingresado por el usuario.
  - Implementar usando el operador null coalescing con `$_POST`:
    ```php
    value="<?= clear_html($_POST['campo'] ?? $data->campo ?? '') ?>"
    ```

## 5. Procesamiento de Datos (`new.action.php` & `edit.action.php`)

### A. Flujo de Validación
1. Captura de datos con `clear_input`.
2. Validaciones de negocio (longitud, formato, duplicados).
3. Uso de `$notifier` para mensajes de error/éxito.
4. Persistencia con `PDO` (siempre `bindParam`).

### B. Redirecciones Post-Acción
- Toda operación exitosa debe redirigir al listado o a la edición del elemento.
- Código obligatorio:
  ```php
  header("Location: " . admin_route("modulo"));
  exit();
  ```

## 6. Acciones Especiales (`delete.action.php` / `deactivate.action.php`)
- **Confirmación**: Las eliminaciones deben usar `SweetAlert2` mediante el helper `Button::delete()`.
- **Ofuscación**: Siempre ofuscar los IDs en la URL usando `$cipher->encrypt($id)`.
- **Respuesta**: Al ser acciones de proceso, deben terminar siempre con una redirección y `exit()`.

## 7. Iconografía y UX
- **Iconos**: Usar `FontAwesome` (fa-solid, fa-regular).
- **Botones**: Siempre usar `.text-uppercase .small .fw-bold` para botones de acción principales.
- **Tablas**: La columna de acciones debe estar alineada a la derecha (`.text-end .pe-3`).