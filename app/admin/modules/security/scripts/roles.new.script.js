/**
 * Scripts para la creación de nuevos roles.
 */
document.addEventListener('DOMContentLoaded', function () {
  
  // -----------------------------------------------------------------------------
  // SECCIÓN: TOGGLE DE GRUPOS Y CONTEXTOS
  // -----------------------------------------------------------------------------
  
  /**
   * Alterna todos los checkboxes dentro de un contenedor.
   * @param {string} groupId ID del contenedor.
   */
  window.toggleGroup = function(groupId) {
    const container = document.getElementById(groupId);
    if (!container) return;
    
    const checkboxes = container.querySelectorAll('input[type="checkbox"]');
    const allChecked = Array.from(checkboxes).every(cb => cb.checked);
    
    checkboxes.forEach(cb => {
      cb.checked = !allChecked;
      // Disparar evento change para posibles listeners
      cb.dispatchEvent(new Event('change'));
    });
  };

  // -----------------------------------------------------------------------------
  // SECCIÓN: RESALTADO DE GATEKEEPER (access.admin)
  // -----------------------------------------------------------------------------
  
  const gatekeeperCheckbox = document.querySelector('input[value="access.admin"], [data-perm-key="access.admin"]');
  
  /**
   * Actualiza el estado de los permisos administrativos.
   */
  function updateAdminPermissionsLock() {
    if (!gatekeeperCheckbox) return;

    const isAdminChecked = gatekeeperCheckbox.checked;
    // Buscar todos los checkboxes de contexto admin que no sean el gatekeeper
    const adminCheckboxes = document.querySelectorAll('.ctx-admin-container input[type="checkbox"]');
    
    adminCheckboxes.forEach(cb => {
      if (cb === gatekeeperCheckbox) return;
      
      cb.disabled = !isAdminChecked;
      if (!isAdminChecked) {
        const card = cb.closest('.form-check');
        if (card) card.style.opacity = '0.5';
      } else {
        const card = cb.closest('.form-check');
        if (card) card.style.opacity = '1';
      }
    });

    // Bloquear también los botones de "Seleccionar todo"
    const toggleButtons = document.querySelectorAll('.ctx-admin-container .btn-toggle-group');
    toggleButtons.forEach(btn => {
      btn.disabled = !isAdminChecked;
      btn.style.opacity = isAdminChecked ? '1' : '0.5';
      btn.style.pointerEvents = isAdminChecked ? 'auto' : 'none';
    });
  }

  if (gatekeeperCheckbox) {
    // Escuchar cambios en el gatekeeper
    gatekeeperCheckbox.addEventListener('change', updateAdminPermissionsLock);
    
    // Ejecutar al inicio (especialmente útil en edición)
    updateAdminPermissionsLock();
  }

  // -----------------------------------------------------------------------------
  // SECCIÓN: BÚSQUEDA FILTRADA DE PERMISOS
  // -----------------------------------------------------------------------------
  
  const searchInput = document.getElementById('search-permissions');
  if (searchInput) {
    searchInput.addEventListener('input', function() {
      const term = this.value.toLowerCase();
      const perms = document.querySelectorAll('.form-check[data-perm-search]');
      
      perms.forEach(perm => {
        const text = perm.getAttribute('data-perm-search').toLowerCase();
        const col = perm.closest('.col-12');
        
        if (text.includes(term)) {
          col.classList.remove('d-none');
        } else {
          col.classList.add('d-none');
        }
      });
      
      // Ocultar grupos vacíos (opcional)
      document.querySelectorAll('.mb-5').forEach(group => {
        const visiblePerms = group.querySelectorAll('.col-12:not(.d-none)');
        if (visiblePerms.length === 0 && term !== '') {
          group.classList.add('d-none');
        } else {
          group.classList.remove('d-none');
        }
      });
    });
  }

  // -----------------------------------------------------------------------------
  // SECCIÓN: PRE-SUBMIT (Habilitar para enviar)
  // -----------------------------------------------------------------------------
  
  const form = document.querySelector('form');
  if (form) {
    form.addEventListener('submit', function() {
      // Habilitar todos los checkboxes antes de enviar para que lleguen al servidor
      const disabledCheckboxes = document.querySelectorAll('.ctx-admin-container input[type="checkbox"]:disabled');
      disabledCheckboxes.forEach(cb => cb.disabled = false);
    });
  }
});
