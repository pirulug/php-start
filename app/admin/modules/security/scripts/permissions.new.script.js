/**
 * Scripts para la creación/edición de permisos.
 */
document.addEventListener('DOMContentLoaded', function () {
  
  const nameInput = document.getElementById('permission_name');
  const keyInput = document.getElementById('permission_key_name');
  const groupSelect = document.getElementById('group_name');
  const customGroupInput = document.getElementById('new_group_name');
  const groupModeNew = document.getElementById('mode_new');

  // -----------------------------------------------------------------------------
  // SECCIÓN: GENERADOR DE SLUGS AUTOMÁTICO
  // -----------------------------------------------------------------------------

  function updateKey() {
    const name = nameInput.value;
    
    const cleanName = name.toString().toLowerCase()
      .normalize("NFD").replace(/[\u0300-\u036f]/g, "")
      .replace(/\s+/g, '.')
      .replace(/[^\w\.]+/g, '')
      .replace(/\.\.+/g, '.')
      .replace(/^\.+/, '')
      .replace(/\.+$/, '');

    keyInput.value = cleanName;
  }

  if (nameInput && keyInput) {
    nameInput.addEventListener('input', updateKey);
    
    if (groupSelect) groupSelect.addEventListener('change', updateKey);
    if (customGroupInput) customGroupInput.addEventListener('input', updateKey);

    // Listeners para los radio buttons de modo de grupo
    const modeRadios = document.querySelectorAll('input[name="group_mode"]');
    modeRadios.forEach(radio => {
      radio.addEventListener('change', updateKey);
    });
  }
});
