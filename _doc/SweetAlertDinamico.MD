# SweetAlert2 Dinámico

Este script permite mostrar modales de SweetAlert2 de manera dinámica, utilizando atributos personalizados en los botones HTML. Puedes definir el comportamiento de cada modal a través de estos atributos.

## Instrucciones de uso

Incluir SweetAlert2 en tu proyecto

Asegúrate de que SweetAlert2 esté correctamente instalado o cargado en tu proyecto. Puedes hacerlo a través de un CDN en tu archivo HTML:

```js
<script src="assets/js/sw-dinamico.js"></script>
```

## Incluir el Script Dinámico

Copia el siguiente código en tu archivo JavaScript principal o en un archivo independiente y asegúrate de que se ejecute después de que la página haya cargado:

```javascript
document.addEventListener("DOMContentLoaded", () => {
  // Selecciona todos los botones con atributos personalizados para SweetAlert2
  document.querySelectorAll("[sw-title]").forEach((button) => {
    button.addEventListener("click", () => {
      // Obtén los atributos personalizados del botón
      const title = button.getAttribute("sw-title") || "Sin título";
      const text = button.getAttribute("sw-text") || "";
      const icon = button.getAttribute("sw-icon") || "info";
      const confirmButtonText = button.getAttribute("sw-confirm-btn-text") || "Aceptar";
      const cancelButtonText = button.getAttribute("sw-cancel-btn-text") || "";
      const redirectUrl = button.getAttribute("sw-redirect-url") || null;
      const timer = parseInt(button.getAttribute("sw-timer"), 10) || 0; // Timer en milisegundos
      const showConfirmButton = button.getAttribute("sw-show-confirm-btn") !== "false"; // Determina si mostrar el botón de confirmar
      const showCancelButton = button.getAttribute("sw-show-cancel-btn") !== "false"; // Determina si mostrar el botón de cancelar

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

- `sw-title`: (Requerido) El título del modal.
- `sw-text`: (Opcional) El texto que aparecerá en el modal.
- `sw-icon`: (Opcional) El icono que se mostrará en el modal. Opciones disponibles: "success", "error", "warning", "info", "question".
- `sw-confirm-btn-text`: (Opcional) El texto del botón de confirmación (por defecto: "Aceptar").
- `sw-cancel-btn-text`: (Opcional) El texto del botón de cancelación.
- `sw-redirect-url`: (Opcional) URL a la que se redirigirá si el usuario confirma la acción.
- `sw-timer`: (Opcional) Tiempo en milisegundos que el modal permanecerá visible antes de cerrarse automáticamente (por ejemplo, 5000 para 5 segundos).
- `sw-show-confirm-btn`: (Opcional) Valor "false" para ocultar el botón de confirmar. Por defecto es "true".
- `sw-show-cancel-btn`: (Opcional) Valor "false" para ocultar el botón de cancelar. Por defecto es "true".

## Ejemplo de un botón:

```html
<button sw-title="¡Advertencia!" sw-text="¿Estás seguro de que deseas eliminar este archivo?" sw-icon="warning" sw-confirm-btn-text="Eliminar" sw-cancel-btn-text="Cancelar" sw-redirect-url="delete.php?id=1" sw-timer="5000">Eliminar archivo</button>
```
Este botón mostrará un modal de advertencia con un temporizador de 5 segundos. Si el usuario confirma, se redirigirá a delete.php?id=1.

## Ejemplos adicionales

### Botón de éxito (sin cancelación):

```html
<button sw-title="Éxito" sw-text="La operación fue exitosa." sw-icon="success" sw-confirm-btn-text="OK" sw-show-cancel-btn="false">Operación exitosa</button>
```

### Botón de error (con reintentar y cancelar):

```html
<button sw-title="Error" sw-text="Hubo un error en la operación." sw-icon="error" sw-confirm-btn-text="Reintentar" sw-cancel-btn-text="Cancelar">Operación fallida</button>
```

### Botón de información (sin confirmación):

```html
<button sw-title="Información" sw-text="Este es un mensaje informativo." sw-icon="info" sw-confirm-btn-text="Entendido" sw-show-confirm-btn="false">Información</button>
```

### Opciones de configuración avanzadas

- `allowOutsideClick`: Permite cerrar el modal al hacer clic fuera de él (valor predeterminado: true).
- `allowEscapeKey`: Permite cerrar el modal con la tecla ESC (valor predeterminado: true).
- `reverseButtons`: Invierte el orden de los botones (valor predeterminado: true).