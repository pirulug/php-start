document.addEventListener("DOMContentLoaded", () => {
  const SITE_URL = document.querySelector('meta[name="site-url"]').content;

  const button = document.getElementById("testMail");
  const loading = document.getElementById("loading");
  const iconSend = document.getElementById("iconSend");
  const btnText = document.getElementById("btnText");

  button.addEventListener("click", function (event) {
    event.preventDefault();

    const to = prompt(
      "Ingresa el correo electrónico donde deseas recibir el mensaje de prueba:"
    );

    if (!to || to.trim() === "") {
      alert("Debes ingresar un correo válido.");
      return;
    }

    // Estado loading (Bootstrap)
    button.disabled = true;
    loading.classList.remove("d-none");
    iconSend.classList.add("d-none");
    btnText.textContent = "Enviando prueba...";

    const formData = new FormData();
    formData.append("to", to.trim());
    formData.append("subject", "Correo de prueba");
    formData.append(
      "body",
      "Este es un correo de prueba enviado desde la API para verificar la configuración del servidor."
    );

    fetch(SITE_URL + "/ajax/mail", {
      method: "POST",
      body: formData
    })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          alert("Correo de prueba enviado correctamente a: " + to);
          
          btnText.textContent = "Conexión exitosa";
          button.classList.remove("btn-outline-secondary");
          button.classList.add("btn-outline-success");
        } else {
          btnText.textContent = "Error de conexión";
          button.classList.remove("btn-outline-secondary");
          button.classList.add("btn-outline-danger");

          let message = data.message || "Error desconocido";

          if (data.errors) {
            message += "\n\nDetalles:\n";
            Object.values(data.errors).forEach(err => {
              message += "- " + err + "\n";
            });
          }

          alert(message);
        }
      })
      .catch(() => {
        btnText.textContent = "Error de conexión";
        button.classList.remove("btn-outline-secondary");
        button.classList.add("btn-outline-danger");
        alert("No se pudo enviar el correo de prueba.");
      })
      .finally(() => {
        loading.classList.add("d-none");
        iconSend.classList.remove("d-none");
        button.disabled = false;
      });
  });
});
