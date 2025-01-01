# SweetAlert2 Dinámico

Este script permite mostrar modales de SweetAlert2 de manera dinámica, utilizando atributos personalizados en los botones HTML. Puedes definir el comportamiento de cada modal a través de estos atributos.

## Instrucciones de uso

Incluir SweetAlert2 en tu proyecto

Asegúrate de que SweetAlert2 esté correctamente instalado o cargado en tu proyecto. Puedes hacerlo a través de un CDN en tu archivo HTML:

```js
<script src="assets/js/sa.js"></script>
```

## Incluir el Script Dinámico

Copia el siguiente código en tu archivo JavaScript principal o en un archivo independiente y asegúrate de que se ejecute después de que la página haya cargado:

```javascript
document.addEventListener("DOMContentLoaded", () => {
  // Selecciona todos los botones con atributos personalizados para SweetAlert2
  document.querySelectorAll("[sa-title]").forEach((button) => {
    button.addEventListener("click", () => {
      // Obtén los atributos personalizados del botón
      const title = button.getAttribute("sa-title") || "Sin título";
      const text = button.getAttribute("sa-text") || "";
      const icon = button.getAttribute("sa-icon") || "info";
      const confirmButtonText = button.getAttribute("sa-confirm-btn-text") || "Aceptar";
      const cancelButtonText = button.getAttribute("sa-cancel-btn-text") || "";
      const redirectUrl = button.getAttribute("sa-redirect-url") || null;
      const timer = parseInt(button.getAttribute("sa-timer"), 10) || 0; // Timer en milisegundos
      const showConfirmButton = button.getAttribute("sa-show-confirm-btn") !== "false"; // Determina si mostrar el botón de confirmar
      const showCancelButton = button.getAttribute("sa-show-cancel-btn") !== "false"; // Determina si mostrar el botón de cancelar

      // Configura el objeto de opciones de SweetAlert2
      const options = {
        title,
        text,
        icon,
        showCancelButton,
        confirmButtonText,
        cancelButtonText,
        timer: timer > 0 ? timer : null, // Solo se agrega el timer si es mayor a 0
        showConfirmButton,
        allowOutsideClick: true, // Permite cerrar al hacer clic fuera del modal
        allowEscapeKey: true, // Permite cerrar con la tecla ESC
        reverseButtons: true, // Invierte los botones (Cancelar primero, luego Confirmar)
      };

      // Muestra el modal
      Swal.fire(options).then((result) => {
        if (result.isConfirmed) {
          if (redirectUrl) {
            window.location.href = redirectUrl; // Redirigir al confirmar
          } else {
            console.log("Confirmado, pero no se proporcionó URL de redirección.");
          }
        } else if (result.dismiss === Swal.DismissReason.cancel) {
          console.log("Cancelado");
        }
      });
    });
  });
});
```

Usar los Atributos Personalizados en los Botones

Para que un botón active el modal, utiliza los siguientes atributos en el HTML del botón:

- `sa-title`: (Requerido) El título del modal.
- `sa-text`: (Opcional) El texto que aparecerá en el modal.
- `sa-icon`: (Opcional) El icono que se mostrará en el modal. Opciones disponibles: "success", "error", "warning", "info", "question".
- `sa-confirm-btn-text`: (Opcional) El texto del botón de confirmación (por defecto: "Aceptar").
- `sa-cancel-btn-text`: (Opcional) El texto del botón de cancelación.
- `sa-redirect-url`: (Opcional) URL a la que se redirigirá si el usuario confirma la acción.
- `sa-timer`: (Opcional) Tiempo en milisegundos que el modal permanecerá visible antes de cerrarse automáticamente (por ejemplo, 5000 para 5 segundos).
- `sa-show-confirm-btn`: (Opcional) Valor "false" para ocultar el botón de confirmar. Por defecto es "true".
- `sa-show-cancel-btn`: (Opcional) Valor "false" para ocultar el botón de cancelar. Por defecto es "true".

## Ejemplo de un botón:

```html
<button sa-title="¡Advertencia!" sa-text="¿Estás seguro de que deseas eliminar este archivo?" sa-icon="warning" sa-confirm-btn-text="Eliminar" sa-cancel-btn-text="Cancelar" sa-redirect-url="delete.php?id=1" sa-timer="5000">Eliminar archivo</button>
```
Este botón mostrará un modal de advertencia con un temporizador de 5 segundos. Si el usuario confirma, se redirigirá a delete.php?id=1.

## Ejemplos adicionales

### Botón de éxito (sin cancelación):

```html
<button sa-title="Éxito" sa-text="La operación fue exitosa." sa-icon="success" sa-confirm-btn-text="OK" sa-show-cancel-btn="false">Operación exitosa</button>
```

### Botón de error (con reintentar y cancelar):

```html
<button sa-title="Error" sa-text="Hubo un error en la operación." sa-icon="error" sa-confirm-btn-text="Reintentar" sa-cancel-btn-text="Cancelar">Operación fallida</button>
```

### Botón de información (sin confirmación):

```html
<button sa-title="Información" sa-text="Este es un mensaje informativo." sa-icon="info" sa-confirm-btn-text="Entendido" sa-show-confirm-btn="false">Información</button>
```

```html
<button class="btn btn-danger" sw2-title="¿Eliminar usuario?" sw2-text="Esta acción no se puede deshacer."
    sw2-icon="warning" sw2-confirm-btn-text="Sí, eliminar" sw2-cancel-btn-text="No, cancelar"
    sw2-redirect-url="index.html?id=1">
    Eliminar Usuario
  </button>
```

### Opciones de configuración avanzadas

- `allowOutsideClick`: Permite cerrar el modal al hacer clic fuera de él (valor predeterminado: true).
- `allowEscapeKey`: Permite cerrar el modal con la tecla ESC (valor predeterminado: true).
- `reverseButtons`: Invierte el orden de los botones (valor predeterminado: true).