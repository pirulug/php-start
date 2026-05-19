document.addEventListener("DOMContentLoaded", function () {
  const testMailBtn = document.getElementById("testMail");
  const loading = document.getElementById("loading");
  const iconSend = document.getElementById("iconSend");
  const btnText = document.getElementById("btnText");

  if (testMailBtn) {
    testMailBtn.addEventListener("click", async function (event) {
      event.preventDefault();

      const { value: email } = await Swal.fire({
        title: "Probar Conexión SMTP",
        input: "email",
        inputLabel: "Ingresa el correo electrónico para recibir el mensaje de prueba",
        inputPlaceholder: "ejemplo@correo.com",
        showCancelButton: true,
        confirmButtonText: "Enviar Prueba",
        cancelButtonText: "Cancelar",
        inputValidator: (value) => {
          if (!value) {
            return "¡Debes ingresar un correo válido!";
          }
        }
      });

      if (!email) return;

      // Estado loading
      testMailBtn.disabled = true;
      if (loading) loading.classList.remove("d-none");
      if (iconSend) iconSend.classList.add("d-none");
      if (btnText) btnText.textContent = "Enviando prueba...";

      try {
        const response = await Mail.send({
          to: email.trim(),
          subject: "Correo de prueba",
          body: `<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
</head>
<body style="font-family: sans-serif; background-color: #f8f9fa; padding: 20px;">
  <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 30px; border-radius: 8px; border: 1px solid #dee2e6;">
    <h2 style="color: #0d6efd; margin-top: 0;">Correo de Prueba SMTP</h2>
    <p style="font-size: 16px; color: #212529;">Este es un correo de prueba enviado desde la API para verificar la configuración del servidor.</p>
    <hr style="border: 0; border-top: 1px solid #dee2e6; margin: 20px 0;">
    <p style="font-size: 12px; color: #6c757d; margin-bottom: 0;">Este mensaje ha sido generado automáticamente por el sistema PHP Start.</p>
  </div>
</body>
</html>`,
          bypass: true,
          lang: "es"
        });

        if (response.success) {
          Swal.fire({
            title: "¡Éxito!",
            text: `Correo de prueba enviado correctamente a: ${email}`,
            icon: "success"
          });

          if (btnText) btnText.textContent = "Conexión exitosa";
          testMailBtn.classList.remove("btn-outline-secondary");
          testMailBtn.classList.add("btn-outline-success");
        } else {
          if (btnText) btnText.textContent = "Error de conexión";
          testMailBtn.classList.remove("btn-outline-secondary");
          testMailBtn.classList.add("btn-outline-danger");

          let message = response.message || "Error desconocido";
          if (response.errors) {
            message += "<br><br><b>Detalles:</b><br>";
            Object.values(response.errors).forEach(err => {
              message += "- " + err + "<br>";
            });
          }

          Swal.fire({
            title: "Error",
            html: message,
            icon: "error"
          });
        }
      } catch (error) {
        if (btnText) btnText.textContent = "Error de conexión";
        testMailBtn.classList.remove("btn-outline-secondary");
        testMailBtn.classList.add("btn-outline-danger");
        
        Swal.fire({
          title: "Error Crítico",
          text: "No se pudo establecer comunicación con el servidor de envío.",
          icon: "error"
        });
      } finally {
        if (loading) loading.classList.add("d-none");
        if (iconSend) iconSend.classList.remove("d-none");
        testMailBtn.disabled = false;
      }
    });
  }
});
