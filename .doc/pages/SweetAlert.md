# Gestión de Alertas (SweetAlert2)

El sistema integra **SweetAlert2** mediante un helper de JavaScript (`sa.js`) que permite disparar diálogos de confirmación, alertas de éxito y mensajes de error de forma declarativa usando atributos en el HTML.

## Atributos de Activación (`sa-*`)

Cualquier elemento (botón, enlace, etc.) que contenga el atributo `sa-title` será interceptado automáticamente por el helper.

| Atributo | Propósito | Valor por Defecto |
| :--- | :--- | :--- |
| `sa-title` | El título principal del modal. | "¿Estás seguro?" |
| `sa-text` | Texto descriptivo o cuerpo del mensaje. | (Vacío) |
| `sa-icon` | Icono de SweetAlert (`success`, `error`, `warning`, `info`, `question`). | `info` |
| `sa-confirm-btn-text` | Texto del botón de confirmación. | "Aceptar" |
| `sa-cancel-btn-text` | Texto del botón de cancelación. | "Cancelar" |
| `sa-redirect-url` | URL a la que se redirigirá tras confirmar. | (Nulo) |
| `sa-form-id` | ID del formulario que se enviará tras confirmar. | (Nulo) |
| `sa-timer` | Tiempo en milisegundos para autocierre. | (Nulo) |
| `sa-show-cancel-btn` | Define si se muestra el botón de cancelar (`true`/`false`). | `true` |

---

## Ejemplos de Uso

### 1. Confirmación de Eliminación (Redirección)
Este es el uso más común, integrado nativamente con la clase `ActionBtn`.

```html
<button type="button" 
        class="btn btn-outline-danger"
        sa-title="¿Eliminar usuario?"
        sa-text="Esta acción no se puede deshacer."
        sa-icon="warning"
        sa-confirm-btn-text="Sí, eliminar"
        sa-redirect-url="/admin/users/delete/1">
    Eliminar
</button>
```

### 2. Confirmación de Envío de Formulario
Útil para acciones críticas antes de procesar el POST de un formulario.

```html
<form id="miFormulario" action="/procesar" method="POST">
    <!-- Campos del formulario -->
    <button type="button" 
            class="btn btn-primary"
            sa-title="¿Confirmar envío?"
            sa-text="Se procesarán los datos ingresados."
            sa-form-id="miFormulario">
        Enviar Datos
    </button>
</form>
```

### 3. Alertas Automáticas (Flash Messages)
El sistema puede disparar alertas automáticamente al cargar la página si detecta elementos con el atributo `data-sa-flash`. Ideal para mensajes provenientes de sesiones de servidor.

```html
<!-- Ejemplo de mensaje generado tras un registro exitoso -->
<div data-sa-flash 
     data-sa-type="success" 
     data-sa-title="¡Usuario Registrado!" 
     data-sa-text="El acceso ha sido creado correctamente.">
</div>
```

#### Atributos adicionales para Flash:
- `data-sa-toast`: Si se incluye, la alerta se muestra como un brindis (toast) pequeño en lugar de un modal central.
- `data-sa-position`: Posición del toast (ej: `top-end`, `bottom-start`).

---

## Integración con PHP (`ActionBtn`)

La clase PHP `ActionBtn` genera automáticamente estos atributos cuando se utiliza el tipo `delete`.

```php
echo ActionBtn::delete('/ruta/eliminar')
    ->saTitle('¿Proceder con la baja?')
    ->saText('El registro será eliminado permanentemente.')
    ->render();
```

## Notas Técnicas
- **Delegación de Eventos**: El script usa delegación en el `document`, por lo que funcionará con elementos inyectados dinámicamente vía AJAX sin necesidad de reinicialización.
- **Estética PiruUI**: Las alertas heredan automáticamente los estilos de los botones de **PiruUI** mediante la configuración de `customClass` en el objeto `PiruSA`.
