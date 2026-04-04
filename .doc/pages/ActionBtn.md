# ActionBtn

La clase `ActionBtn` es un componente de utilidad diseñado para la generación estandarizada y semántica de botones y enlaces dentro del panel administrativo. Proporciona una interfaz fluida (Fluent Interface) que permite configurar permisos, iconos, estilos y comportamientos de forma encadenada.

## Características Principales

- **Interfaz Fluida**: Permite configurar el botón mediante el encadenamiento de métodos.
- **Validación de Permisos**: Integra automáticamente la función global `can()` para ocultar botones si el usuario no tiene los permisos necesarios.
- **Iconografía Flexible**: Soporte nativo para FontAwesome, Bootstrap Icons y Feather Icons.
- **Confirmación SweetAlert**: Lógica integrada para botones de eliminación que requieren confirmación del usuario.
- **Renderizado Inteligente**: Capacidad de alternar entre etiquetas `<a>` y `<button>` según la configuración.

## Métodos de Factoría (Estáticos)

Estos métodos devuelven una nueva instancia de la clase con una configuración predefinida según el propósito de la acción.

| Método | Tipo Inicial | Clase CSS | Propósito |
| :--- | :--- | :--- | :--- |
| `link($url)` | link | `btn-outline-secondary` | Enlace genérico con estilo minimalista. |
| `save($url)` | submit/link | `btn-primary` | Acción principal (Guardar/Crear). Si no se provee URL, actúa como submit. |
| `edit($url)` | link | `btn-success` | Acción de edición o modificación de un recurso. |
| `delete($url)` | delete | `btn-outline-danger` | Acción destructiva con confirmación SweetAlert integrada. |
| `view($url)` | link | `btn-info` | Visualización de detalles o información extendida. |
| `cancel($url)` | button/link | `btn-secondary` | Acciones neutras, cierre de modales o retorno. |
| `active($url)` | link | `btn-info` | Acción para activar o habilitar un recurso. |
| `deactivate($url)` | link | `btn-warning` | Acción para desactivar o inhabilitar un recurso. |
| `archive($url)` | link | `btn-warning` | Acción para archivar registros. |
| `export($url)` | link | `btn-dark` | Acciones de descarga o exportación de datos. |

## Métodos de Configuración (Interfaz Fluida)

Una vez creada la instancia, se pueden encadenar los siguientes métodos para personalizar el comportamiento y la apariencia:

### Seguridad y Permisos
- `can(string $permission)`: Define el permiso necesario para que el botón sea visible. Si la validación falla, el método `render()` devolverá una cadena vacía.

### Contenido y Estética
- `text(string $text)`: Define el texto que se mostrará dentro del botón.
- `icon(string $icon)`: Define el icono a mostrar. Soporta clases de FontAwesome (`fas fa-save`) o atajos de Feather (`feather-user`).
- `classes(string $classes)`: Sobrescribe las clases de Bootstrap predeterminadas por las proporcionadas.
- `attrs(string $attrs)`: Permite añadir atributos HTML adicionales de forma cruda (ej. `data-id="123" target="_blank"`).

### Comportamiento del Elemento
- `asSubmit()`: Fuerza al elemento a renderizarse como un `<button type="submit">`.
- `asButton()`: Fuerza al elemento a renderizarse como un `<button type="button">`.

### Configuración SweetAlert (Solo para tipo 'delete')
- `saTitle(string $title)`: Cambia el título de la ventana de confirmación (Predeterminado: "¿Estás seguro?").
- `saText(string $text)`: Cambia el mensaje descriptivo de la confirmación.

## Soporte de Iconos

La clase `ActionBtn` procesa el valor pasado al método `icon()` para generar el HTML apropiado:

1. **Feather Icons (Atajo)**: Al usar el prefijo `feather-`, se genera un elemento `<i data-feather="nombre"></i>`. Ejemplo: `feather-check`.
2. **Feather Icons (Atributo)**: Si se pasa la cadena completa `data-feather="name"`, se renderiza tal cual dentro de una etiqueta `<i>`.
3. **FontAwesome / Bootstrap Icons**: Cualquier otra cadena se trata como una clase CSS estándar. Ejemplo: `fas fa-trash` o `bi bi-gear`.

## Renderizado

Para obtener el HTML resultante, se puede llamar explícitamente al método `render()` o simplemente imprimir la instancia, ya que implementa el método mágico `__toString()`.

### Ejemplos Detallados de Factoría

```php
// 1. Enlace genérico (outline secondary)
echo ActionBtn::link($url)->text('Documentación');

// 2. Acción principal/Guardado
echo ActionBtn::save()->text('Crear Nodo'); // Como submit
echo ActionBtn::save($url)->text('Ir a Registro'); // Como enlace

// 3. Edición (estilo éxito)
echo ActionBtn::edit($url)->text('Modificar Perfil');

// 4. Eliminación (peligro, SweetAlert)
echo ActionBtn::delete($url)->text('Borrar Cuenta');

// 5. Visualización (estilo info)
echo ActionBtn::view($url)->text('Detalles');

// 6. Cancelación (estilo secundario)
echo ActionBtn::cancel()->text('Cerrar Ventana'); // Como botón

// 7. Activación y Desactivación
echo ActionBtn::active($url)->text('Habilitar');
echo ActionBtn::deactivate($url)->text('Suspender');

// 8. Archivador y Exportación
echo ActionBtn::archive($url)->text('Mover al baúl');
echo ActionBtn::export($url)->text('Bajar Reporte');
```

### Configuración Fluida Completa

```php
echo ActionBtn::delete(admin_route('user/destroy/5'))
    ->can('users.delete')                         // 1. Validación de seguridad
    ->text('Eliminar permanentemente')            // 2. Texto del botón
    ->icon('feather-trash-2')                     // 3. Icono (Feather shortcut)
    ->classes('btn-lg w-100 shadow-sm')           // 4. Clases CSS personalizadas
    ->attrs('data-id="5" id="btn-delete-user"')   // 5. Atributos adicionales
    ->saTitle('¿Proceder con la eliminación?')    // 6. Título SweetAlert
    ->saText('Este cambio borrará registros.')    // 7. Texto SweetAlert
    ->render();                                   // 8. Salida explícita
```

### Comportamientos Especiales

```php
// Forzar comportamiento de botón genérico sin URL
echo ActionBtn::cancel()->asButton()->text('Cerrar');

// Forzar comportamiento de formulario (Submit) desde un tipo link
echo ActionBtn::link('')->asSubmit()->text('Enviar Datos');
```

## Notas Técnicas

- Se requiere que la función global `can(string $permission)` esté disponible en el entorno para el correcto funcionamiento de la validación de visibilidad.
- Al usar Feather Icons, asegúrese de que el script `feather.replace()` se ejecute después de que el DOM esté cargado para transformar los elementos `<i>` en SVGs.