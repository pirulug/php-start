document.addEventListener('DOMContentLoaded', function () {
  // Copiar al portapapeles
  document.querySelectorAll('.btn-copy').forEach(btn => {
    btn.addEventListener('click', function () {
      const key = this.getAttribute('data-key');
      const icon = this.querySelector('i');

      const showSuccess = () => {
        icon.classList.remove('fa-copy');
        icon.classList.add('fa-check', 'text-success');
        setTimeout(() => {
          icon.classList.remove('fa-check', 'text-success');
          icon.classList.add('fa-copy');
        }, 1500);
      };

      if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(key).then(showSuccess);
      } else {
        const textArea = document.createElement("textarea");
        textArea.value = key;
        textArea.style.position = "fixed";
        textArea.style.left = "-999999px";
        textArea.style.top = "-999999px";
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();
        try {
          document.execCommand('copy');
          showSuccess();
        } catch (err) {
          console.error('No se pudo copiar el texto: ', err);
        }
        document.body.removeChild(textArea);
      }
    });
  });

  // Eliminar llave
  document.querySelectorAll('.btn-delete-key').forEach(btn => {
    btn.addEventListener('click', function () {
      const id = this.getAttribute('data-id');

      if (typeof Swal !== 'undefined') {
        Swal.fire({
          title: '¿Eliminar API Key?',
          text: 'La llave de este usuario dejará de funcionar inmediatamente.',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Sí, eliminar',
          cancelButtonText: 'Cancelar'
        }).then((result) => {
          if (result.isConfirmed) {
            window.location.href = window.location.pathname + '?delete_key=' + id;
          }
        });
      } else {
        if (confirm('¿Estás seguro de que deseas eliminar la API Key de este usuario?')) {
          window.location.href = window.location.pathname + '?delete_key=' + id;
        }
      }
    });
  });

  // Generar llave
  document.querySelectorAll('.btn-generate-key').forEach(btn => {
    btn.addEventListener('click', function () {
      if (typeof Swal !== 'undefined') {
        Swal.fire({
          title: '¿Generar API Key?',
          text: 'Se creará una nueva llave de acceso para este usuario.',
          icon: 'question',
          showCancelButton: true,
          confirmButtonText: 'Sí, generar',
          cancelButtonText: 'Cancelar'
        }).then((result) => {
          if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'generate_key';
            input.value = '1';
            form.appendChild(input);
            document.body.appendChild(form);
            form.submit();
          }
        });
      } else {
        if (confirm('¿Estás seguro de que deseas generar una nueva API Key para este usuario?')) {
          const form = document.createElement('form');
          form.method = 'POST';
          const input = document.createElement('input');
          input.type = 'hidden';
          input.name = 'generate_key';
          input.value = '1';
          form.appendChild(input);
          document.body.appendChild(form);
          form.submit();
        }
      }
    });
  });

  // Regenerar llave
  document.querySelectorAll('.btn-regenerate-key').forEach(btn => {
    btn.addEventListener('click', function () {
      if (typeof Swal !== 'undefined') {
        Swal.fire({
          title: '¿Regenerar API Key?',
          text: 'La llave actual del usuario será invalidada y se generará una nueva.',
          icon: 'info',
          showCancelButton: true,
          confirmButtonText: 'Sí, regenerar',
          cancelButtonText: 'Cancelar'
        }).then((result) => {
          if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'regenerate_key';
            input.value = '1';
            form.appendChild(input);
            document.body.appendChild(form);
            form.submit();
          }
        });
      } else {
        if (confirm('¿Estás seguro de que deseas regenerar la API Key de este usuario?')) {
          const form = document.createElement('form');
          form.method = 'POST';
          const input = document.createElement('input');
          input.type = 'hidden';
          input.name = 'regenerate_key';
          input.value = '1';
          form.appendChild(input);
          document.body.appendChild(form);
          form.submit();
        }
      }
    });
  });
});
