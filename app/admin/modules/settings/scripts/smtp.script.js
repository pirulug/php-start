document.addEventListener('DOMContentLoaded', function () {
  const testMailBtn = document.getElementById('testMail');
  const loading = document.getElementById('loading');
  const iconSend = document.getElementById('iconSend');
  const btnText = document.getElementById('btnText');

  if (testMailBtn) {
    testMailBtn.addEventListener('click', async function (event) {
      event.preventDefault();

      const to = prompt(
        "Ingresa el correo electrónico donde deseas recibir el mensaje de prueba:"
      );

      if (!to || to.trim() === "") {
        alert("Debes ingresar un correo válido.");
        return;
      }

      // Estado loading (Bootstrap)
      testMailBtn.disabled = true;
      if (loading) loading.classList.remove('d-none');
      if (iconSend) iconSend.classList.add('d-none');
      if (btnText) btnText.textContent = "Enviando prueba...";

      try {
        // Usando la librería Mail (Cargada en la vista)
        const response = await Mail.send({
          to: to.trim(),
          subject: "Correo de prueba",
          body: "Este es un correo de prueba enviado desde la API para verificar la configuración del servidor."
        });

        if (response.success) {
          alert("Correo de prueba enviado correctamente a: " + to);

          if (btnText) btnText.textContent = "Conexión exitosa";
          testMailBtn.classList.remove("btn-outline-secondary");
          testMailBtn.classList.add("btn-outline-success");
        } else {
          if (btnText) btnText.textContent = "Error de conexión";
          testMailBtn.classList.remove("btn-outline-secondary");
          testMailBtn.classList.add("btn-outline-danger");

          let message = response.message || "Error desconocido";

          if (response.errors) {
            message += "\n\nDetalles:\n";
            Object.values(response.errors).forEach(err => {
              message += "- " + err + "\n";
            });
          }

          alert(message);
        }
      } catch (error) {
        if (btnText) btnText.textContent = "Error de conexión";
        testMailBtn.classList.remove("btn-outline-secondary");
        testMailBtn.classList.add("btn-outline-danger");
        alert("No se pudo enviar el correo de prueba.");
      } finally {
        if (loading) loading.classList.add('d-none');
        if (iconSend) iconSend.classList.remove('d-none');
        testMailBtn.disabled = false;
      }
    });
  }
});
