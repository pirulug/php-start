document.addEventListener("DOMContentLoaded", () => {
  const SITE_URL = document.querySelector('meta[name="site-url"]').content;

  document
    .getElementById("testMail")
    .addEventListener("click", function (event) {
      event.preventDefault();

      const button = event.target;
      const loading = document.getElementById("loading");
      const form = button.closest("form");

      // Pedir correo de destino
      const destinatario = prompt(
        "✉️ Ingresa el correo electrónico donde deseas recibir la prueba:"
      );

      // Si el usuario cancela o deja vacío
      if (!destinatario || destinatario.trim() === "") {
        alert("⚠️ Debes ingresar un correo para enviar la prueba.");
        return;
      }

      // Deshabilitar el botón y mostrar el loading
      button.setAttribute("disabled", true);
      loading.style.display = "inline-block";

      // Crear los datos del formulario + correo destino
      const formData = new FormData(form);
      formData.append("destinatario", destinatario.trim());

      fetch(SITE_URL + "/ajax/test/mail", {
        method: "POST",
        body: formData,
      })
        .then((response) => response.json())
        .then((data) => {
          // console.log("Respuesta del servidor:", data);
          if (data.success) {
            alert("✅ Correo de prueba enviado con éxito a: " + destinatario);
          } else {
            alert("⚠️ Error al enviar el correo: " + data.message);
          }
        })
        .catch((error) => {
          console.error("Error en fetch:", error);
          alert("❌ Error al enviar el correo de prueba.");
        })
        .finally(() => {
          button.removeAttribute("disabled");
          loading.style.display = "none";
        });
    });
});
