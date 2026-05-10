/**
 * Backup Module Scripts
 * Handles the premium SweetAlert2 confirmations for restoration actions.
 */

document.addEventListener("DOMContentLoaded", () => {
  const restoreBtns = document.querySelectorAll(".restore-btn");

  restoreBtns.forEach(btn => {
    btn.addEventListener("click", function(e) {
      e.preventDefault();
      const url = this.getAttribute("href");

      Swal.fire({
        title: "¿Restaurar base de datos?",
        text: "ADVERTENCIA: Se eliminarán todas las tablas actuales y se reemplazarán por el contenido del respaldo. Esta acción no se puede deshacer.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Sí, restaurar ahora",
        cancelButtonText: "Cancelar",
        reverseButtons: true
      }).then((result) => {
        if (result.isConfirmed) {
          Swal.fire({
            title: "Restaurando...",
            text: "Por favor, espera mientras se procesa la base de datos.",
            allowOutsideClick: false,
            didOpen: () => {
              Swal.showLoading();
            }
          });
          window.location.href = url;
        }
      });
    });
  });
});
