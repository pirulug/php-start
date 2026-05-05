document.addEventListener('DOMContentLoaded', function () {
  const masterSwitch = document.getElementById('captcha_enabled');
  const mainWrapper = document.getElementById('main_config_wrapper');
  const captchaTypeSelect = document.getElementById('captcha_type');

  const sectionVanilla = document.getElementById('section_vanilla');
  const sectionRecaptcha = document.getElementById('section_recaptcha');
  const sectionCloudflare = document.getElementById('section_cloudflare');

  // Función para manejar la visibilidad de las secciones de configuración
  function updateSections(selectedType) {
    // Ocultar todos
    [sectionVanilla, sectionRecaptcha, sectionCloudflare].forEach(s => {
      if (s) s.classList.add('d-none');
    });

    // Mostrar el seleccionado
    if (selectedType === 'vanilla' && sectionVanilla) {
      sectionVanilla.classList.remove('d-none');
    } else if (selectedType === 'recaptcha' && sectionRecaptcha) {
      sectionRecaptcha.classList.remove('d-none');
    } else if (selectedType === 'cloudflare' && sectionCloudflare) {
      sectionCloudflare.classList.remove('d-none');
    }
  }

  // Toggle principal
  if (masterSwitch && mainWrapper) {
    masterSwitch.addEventListener('change', function () {
      if (this.checked) {
        mainWrapper.classList.remove('d-none');
      } else {
        mainWrapper.classList.add('d-none');
      }
    });
  }

  // Cambio de tipo de Captcha
  if (captchaTypeSelect) {
    captchaTypeSelect.addEventListener('change', function () {
      updateSections(this.value);
    });
  }
});
