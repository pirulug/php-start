document.addEventListener("DOMContentLoaded", () => {
  // Selecciona todos los botones con atributos personalizados para SweetAlert2
  document.querySelectorAll("[sa-title]").forEach((button) => {
    button.addEventListener("click", () => {
      // Obtén los atributos personalizados del botón
      const title = button.getAttribute("sa-title") || "Sin título";
      const text = button.getAttribute("sa-text") || "";
      const icon = button.getAttribute("sa-icon") || "info";
      const confirmButtonText =
        button.getAttribute("sa-confirm-btn-text") || "Aceptar";
      const cancelButtonText = button.getAttribute("sa-cancel-btn-text") || "";
      const redirectUrl = button.getAttribute("sa-redirect-url") || null;
      const timer = parseInt(button.getAttribute("sa-timer"), 10) || 0; // Timer en milisegundos
      const showConfirmButton =
        button.getAttribute("sa-show-confirm-btn") !== "false"; // Determina si mostrar el botón de confirmar
      const showCancelButton =
        button.getAttribute("sa-show-cancel-btn") !== "false"; // Determina si mostrar el botón de cancelar

      // Configura el objeto de opciones de SweetAlert2
      const options = {
        title,
        text,
        icon,
        showConfirmButton,
        confirmButtonText,
        showCancelButton,
        cancelButtonText,
        timer: timer > 0 ? timer : null, // Solo se agrega el timer si es mayor a 0
        allowOutsideClick: true, // Permite cerrar al hacer clic fuera del modal
        allowEscapeKey: true, // Permite cerrar con la tecla ESC
        reverseButtons: false, // Invierte los botones (Cancelar primero, luego Confirmar)
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
