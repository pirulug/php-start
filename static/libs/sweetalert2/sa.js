/**
 * PiruSA - Helper de SweetAlert2 para PhpStart
 * 
 * Gestiona alertas dinámicas mediante atributos data y eventos delegados.
 * Soporta redirecciones, envíos de formularios y alertas automáticas (flash).
 */

const PiruSA = {

  /**
   * Inicialización global
   */
  init: function () {
    this.bindEvents();
    this.checkFlashMessages();
  },

  /**
   * Delegación de eventos para elementos con atributos sa-*
   */
  bindEvents: function () {
    document.addEventListener('click', (e) => {
      const target = e.target.closest('[sa-title]');
      if (target) {
        e.preventDefault();
        this.fire(target);
      }
    });
  },

  /**
   * Ejecuta la alerta basada en los atributos del elemento
   * @param {HTMLElement} el 
   */
  fire: function (el) {
    const options = {
      title: el.getAttribute('sa-title') || '¿Estás seguro?',
      text: el.getAttribute('sa-text') || '',
      icon: el.getAttribute('sa-icon') || 'info',
      showCancelButton: el.getAttribute('sa-show-cancel-btn') !== 'false',
      confirmButtonText: el.getAttribute('sa-confirm-btn-text') || 'Aceptar',
      cancelButtonText: el.getAttribute('sa-cancel-btn-text') || 'Cancelar',
      confirmButtonColor: 'var(--bs-primary)',
      cancelButtonColor: 'var(--bs-secondary)',
      timer: parseInt(el.getAttribute('sa-timer'), 10) || null,
      showConfirmButton: el.getAttribute('sa-show-confirm-btn') !== 'false',
      allowOutsideClick: el.getAttribute('sa-allow-outside') !== 'false',
      customClass: {
        confirmButton: 'btn btn-primary px-4',
        cancelButton: 'btn btn-outline-secondary px-4'
      },
      buttonsStyling: false
    };

    const redirectUrl = el.getAttribute('sa-redirect-url');
    const formId = el.getAttribute('sa-form-id');

    Swal.fire(options).then((result) => {
      if (result.isConfirmed) {
        if (formId) {
          const form = document.getElementById(formId);
          if (form) form.submit();
        } else if (redirectUrl) {
          window.location.href = redirectUrl;
        }
      }
    });
  },

  /**
   * Busca elementos con data-sa-flash para mostrar alertas automáticas al cargar
   */
  checkFlashMessages: function () {
    const flashElements = document.querySelectorAll('[data-sa-flash]');
    flashElements.forEach(el => {
      const type = el.getAttribute('data-sa-type') || 'info';
      const title = el.getAttribute('data-sa-title') || 'Mensaje';
      const text = el.getAttribute('data-sa-text') || '';

      Swal.fire({
        icon: type,
        title: title,
        text: text,
        toast: el.hasAttribute('data-sa-toast'),
        position: el.getAttribute('data-sa-position') || 'center',
        timer: 3000,
        timerProgressBar: true,
        showConfirmButton: !el.hasAttribute('data-sa-toast')
      });
    });
  }
};

// Auto-inicialización
document.addEventListener('DOMContentLoaded', () => PiruSA.init());
