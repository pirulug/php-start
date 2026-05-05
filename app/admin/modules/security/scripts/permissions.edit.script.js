/**
 * Scripts para la edición de permisos.
 */
document.addEventListener('DOMContentLoaded', function () {
  
  const nameInput = document.getElementById('permission_name');
  const keyInput = document.getElementById('permission_key_name');

  // Solo generamos si el usuario borra la clave o está vacía, para no romper claves existentes
  if (nameInput && keyInput) {
    nameInput.addEventListener('input', function() {
      if (keyInput.value === "") {
        const slug = this.value.toString().toLowerCase()
          .normalize("NFD").replace(/[\u0300-\u036f]/g, "")
          .replace(/\s+/g, '.')
          .replace(/[^\w\.]+/g, '')
          .replace(/\.\.+/g, '.')
          .replace(/^\.+/, '')
          .replace(/\.+$/, '');
        keyInput.value = slug;
      }
    });
  }
});
