/**
 * Scripts para la personalización de permisos de usuario.
 */
document.addEventListener('DOMContentLoaded', function () {

  // -----------------------------------------------------------------------------
  // SECCIÓN: TOGGLE DE GRUPOS Y CONTEXTOS
  // -----------------------------------------------------------------------------

  window.toggleGroup = function (groupId) {
    const container = document.getElementById(groupId);
    if (!container) return;

    // Solo afectamos a los que no estén deshabilitados (por herencia o por el gatekeeper)
    const checkboxes = container.querySelectorAll('input[type="checkbox"]:not(:disabled)');
    if (checkboxes.length === 0) return;

    const allChecked = Array.from(checkboxes).every(cb => cb.checked);

    checkboxes.forEach(cb => {
      cb.checked = !allChecked;
      cb.dispatchEvent(new Event('change'));
    });
  };

  // -----------------------------------------------------------------------------
  // SECCIÓN: GATEKEEPER LOCK (access.admin)
  // -----------------------------------------------------------------------------

  const gatekeeperCheckbox = document.querySelector('input[data-perm-key="access.admin"]');

  function updateAdminPermissionsLock() {
    if (!gatekeeperCheckbox) return;

    const isAdminChecked = gatekeeperCheckbox.checked;
    const adminContainer = document.querySelector('.ctx-admin-container');
    if (!adminContainer) return;

    // 1. Bloquear/Desbloquear Checkboxes
    const adminCheckboxes = adminContainer.querySelectorAll('input[type="checkbox"]');
    adminCheckboxes.forEach(cb => {
      if (cb === gatekeeperCheckbox) return;

      // Si es heredado, nunca lo desbloqueamos
      if (cb.dataset.inherited === 'true') return;

      cb.disabled = !isAdminChecked;
      const card = cb.closest('.list-group-item');
      if (card) {
        card.style.opacity = isAdminChecked ? '1' : '0.5';
      }
    });

    // 2. Bloquear/Desbloquear botones de selección de grupo
    const toggleButtons = adminContainer.querySelectorAll('.btn-toggle-group');
    toggleButtons.forEach(btn => {
      btn.disabled = !isAdminChecked;
      btn.style.opacity = isAdminChecked ? '1' : '0.5';
      btn.style.pointerEvents = isAdminChecked ? 'auto' : 'none';
    });
  }

  if (gatekeeperCheckbox) {
    gatekeeperCheckbox.addEventListener('change', updateAdminPermissionsLock);
    updateAdminPermissionsLock();
  }

  // -----------------------------------------------------------------------------
  // SECCIÓN: BÚSQUEDA FILTRADA
  // -----------------------------------------------------------------------------

  const searchInput = document.getElementById('search-permissions');
  if (searchInput) {
    searchInput.addEventListener('input', function () {
      const term = this.value.toLowerCase();
      const items = document.querySelectorAll('.list-group-item[data-perm-search]');

      items.forEach(item => {
        const text = item.getAttribute('data-perm-search').toLowerCase();
        item.classList.toggle('d-none', !text.includes(term));
      });

      document.querySelectorAll('.card-group-container').forEach(card => {
        const visibleItems = card.querySelectorAll('.list-group-item:not(.d-none)');
        card.classList.toggle('d-none', visibleItems.length === 0 && term !== '');
      });
    });
  }

  // -----------------------------------------------------------------------------
  // SECCIÓN: PRE-SUBMIT
  // -----------------------------------------------------------------------------

  const form = document.querySelector('form');
  if (form) {
    form.addEventListener('submit', function () {
      const disabledCheckboxes = document.querySelectorAll('input[type="checkbox"]:disabled');
      disabledCheckboxes.forEach(cb => {
        // Habilitamos solo los que NO son heredados para que se guarden
        if (cb.dataset.inherited !== 'true') {
          cb.disabled = false;
        }
      });
    });
  }
});
