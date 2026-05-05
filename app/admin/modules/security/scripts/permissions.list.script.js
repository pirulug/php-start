/**
 * Scripts para el listado de permisos y acciones en lote.
 */
document.addEventListener('DOMContentLoaded', function () {
  
  // Tooltips
  const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
  tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
  });

  const bulkBar = document.getElementById('bulk-bar');
  const selectedCount = document.getElementById('selected-count');
  const checkboxes = document.querySelectorAll('.permission-check');
  const selectAllCheckboxes = document.querySelectorAll('.select-all-group');

  // -----------------------------------------------------------------------------
  // SECCIÓN: GESTIÓN DE SELECCIÓN EN LOTE
  // -----------------------------------------------------------------------------

  function updateBulkBar() {
    const checkedBoxes = document.querySelectorAll('.permission-check:checked');
    const count = checkedBoxes.length;

    if (count > 0) {
      if (selectedCount) selectedCount.textContent = count;
      bulkBar.classList.add('show');
    } else {
      bulkBar.classList.remove('show');
      resetBulkNewGroup();
    }
  }

  checkboxes.forEach(cb => {
    cb.addEventListener('change', updateBulkBar);
  });

  selectAllCheckboxes.forEach(toggler => {
    toggler.addEventListener('change', function () {
      const target = this.getAttribute('data-target');
      const listItems = document.querySelectorAll(`[data-group="${target}"] .permission-check`);
      listItems.forEach(cb => {
        cb.checked = this.checked;
      });
      updateBulkBar();
    });
  });

  // -----------------------------------------------------------------------------
  // SECCIÓN: GRUPO PERSONALIZADO EN LOTE
  // -----------------------------------------------------------------------------

  const groupSelect = document.querySelector('select[name="new_group_id"]');
  const newGroupSection = document.getElementById('bulk-new-group-section');
  const newGroupNameInput = document.getElementById('bulk_new_group_name');

  if (groupSelect) {
    groupSelect.addEventListener('change', function () {
      if (this.value === "-1") {
        newGroupSection.classList.remove('d-none');
        newGroupNameInput.focus();
      } else {
        newGroupSection.classList.add('d-none');
      }
    });
  }

  function resetBulkNewGroup() {
    if (groupSelect) groupSelect.value = "0";
    if (newGroupSection) newGroupSection.classList.add('d-none');
    if (newGroupNameInput) newGroupNameInput.value = "";
    const keyInput = document.getElementById('bulk_new_group_key');
    if (keyInput) keyInput.value = "";
  }

  window.resetBulkNewGroup = resetBulkNewGroup;

  // -----------------------------------------------------------------------------
  // SECCIÓN: GENERADOR DE SLUGS
  // -----------------------------------------------------------------------------

  window.generateBulkSlug = function (text, targetId) {
    const slug = text.toString().toLowerCase()
      .normalize("NFD").replace(/[\u0300-\u036f]/g, "")
      .replace(/\s+/g, '.')
      .replace(/[^\w\.]+/g, '')
      .replace(/\.\.+/g, '.')
      .replace(/^\.+/, '')
      .replace(/\.+$/, '');

    const target = document.getElementById(targetId);
    if (target) target.value = slug;
  };
});
