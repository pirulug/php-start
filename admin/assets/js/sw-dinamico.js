document.addEventListener("DOMContentLoaded", () => {
  // Selecciona todos los botones con atributos personalizados para SweetAlert2
  document.querySelectorAll("[sw2-title]").forEach((button) => {
    button.addEventListener("click", () => {
      // Obtén los atributos personalizados del botón
      const title = button.getAttribute("sw2-title") || "Sin título";
      const text = button.getAttribute("sw2-text") || "";
      const icon = button.getAttribute("sw2-icon") || "info";
      const confirmButtonText =
        button.getAttribute("sw2-confirm-btn-text") || "Aceptar";
      const cancelButtonText = button.getAttribute("sw2-cancel-btn-text") || "";
      const redirectUrl = button.getAttribute("sw2-redirect-url") || null;
      const timer = parseInt(button.getAttribute("sw2-timer"), 10) || 0; // Timer en milisegundos
      const showConfirmButton =
        button.getAttribute("sw2-show-confirm-btn") !== "false"; // Determina si mostrar el botón de confirmar
      const showCancelButton =
        button.getAttribute("sw2-show-cancel-btn") !== "false"; // Determina si mostrar el botón de cancelar

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
            console.log(
              "Confirmado, pero no se proporcionó URL de redirección."
            );
          }
        } else if (result.dismiss === Swal.DismissReason.cancel) {
          console.log("Cancelado");
        }
      });
    });
  });
});
