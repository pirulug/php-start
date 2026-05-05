/**
 * Script para el restablecimiento de contraseña (Front).
 */

document.addEventListener('DOMContentLoaded', function() {
  const form = document.getElementById('reset_password_form');
  if (!form) return;

  const btn = document.getElementById('btn_submit');
  const btnText = btn.querySelector('.btn-text');
  const btnLoading = btn.querySelector('.btn-loading');

  const endpoint = '/auth/endpoint/reset';
  const redirect = '/signin';

  form.addEventListener('submit', function(e) {
    e.preventDefault();

    // Estado de carga
    btn.disabled = true;
    if (btnText) btnText.classList.add('d-none');
    if (btnLoading) btnLoading.classList.remove('d-none');

    const formData = new FormData(form);

    fetch(endpoint, {
      method: 'POST',
      body: formData
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        Swal.fire({
          icon: 'success',
          title: '¡Enviado!',
          text: data.message,
          confirmButtonColor: '#0d6efd'
        }).then(() => {
          if (redirect) window.location.href = redirect;
        });
      } else {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: data.message,
          confirmButtonColor: '#0d6efd'
        });
        // Restaurar botón si hay error
        btn.disabled = false;
        if (btnText) btnText.classList.remove('d-none');
        if (btnLoading) btnLoading.classList.add('d-none');
      }
    })
    .catch(error => {
      console.error('Error:', error);
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Hubo un error al procesar tu solicitud. Inténtalo de nuevo.',
        confirmButtonColor: '#0d6efd'
      });
      // Restaurar botón
      btn.disabled = false;
      if (btnText) btnText.classList.remove('d-none');
      if (btnLoading) btnLoading.classList.add('d-none');
    });
  });
});
