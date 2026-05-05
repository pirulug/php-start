document.addEventListener('DOMContentLoaded', function () {
  const container = document.getElementById('social-container');
  const addButton = document.getElementById('add-social');

  // Leer el mapa de iconos desde el atributo data
  const iconsMap = JSON.parse(container.dataset.icons || '{}');

  function getDomain(url) {
    try {
      const domain = new URL(url).hostname;
      return domain;
    } catch (e) {
      return null;
    }
  }

  function updatePreview(tr) {
    const nameInput = tr.querySelector('.social-name');
    const iconInput = tr.querySelector('.social-icon-input');
    const urlInput = tr.querySelector('.social-url-input');
    const previewCell = tr.querySelector('.preview-cell');

    const name = nameInput.value.toLowerCase().trim();
    const customIcon = iconInput.value.trim();
    const url = urlInput.value.trim();

    let iconClass = customIcon || iconsMap[name] || null;

    if (iconClass) {
      previewCell.innerHTML = `<i class="${iconClass} fs-4 text-secondary"></i>`;
    } else {
      const domain = getDomain(url);
      if (domain) {
        previewCell.innerHTML = `<img src="https://www.google.com/s2/favicons?domain=${domain}&sz=64" style="width: 24px; height: 24px; object-fit: contain; border-radius: 4px;" alt="favicon">`;
      } else {
        previewCell.innerHTML = `<i class="fa-solid fa-link fs-4 text-secondary"></i>`;
      }
    }
  }

  // Inicializar Sortable
  if (typeof Sortable !== 'undefined') {
    new Sortable(container, {
      handle: '.handle',
      animation: 150,
      ghostClass: 'bg-light'
    });
  }

  addButton.addEventListener('click', function () {
    const emptyRow = container.querySelector('.empty-row');
    if (emptyRow) emptyRow.remove();

    const tr = document.createElement('tr');
    tr.innerHTML = `
            <td class="text-center text-muted handle" style="cursor: grab;">
                <i class="fa-solid fa-grip-vertical"></i>
            </td>
            <td class="text-center preview-cell">
                <i class="fa-solid fa-link fs-4 text-secondary"></i>
            </td>
            <td>
                <input type="text" name="social_names[]" class="form-control social-name" placeholder="Ej: twitter">
            </td>
            <td>
                <input type="text" name="social_icons[]" class="form-control social-icon-input" placeholder="Ej: fa-brands fa-x-twitter">
            </td>
            <td>
                <input type="text" name="social_urls[]" class="form-control social-url-input" placeholder="https://...">
            </td>
            <td class="text-end">
                <button type="button" class="btn btn-link text-danger p-0 remove-social">
                    <i class="fa-solid fa-trash-can"></i>
                </button>
            </td>
        `;
    container.appendChild(tr);
  });

  container.addEventListener('click', function (e) {
    if (e.target.closest('.remove-social')) {
      e.target.closest('tr').remove();
      if (container.children.length === 0) {
        container.innerHTML = '<tr class="empty-row"><td colspan="5" class="text-center py-4 text-secondary small">No hay redes sociales configuradas.</td></tr>';
      }
    }
  });

  container.addEventListener('input', function (e) {
    if (e.target.classList.contains('social-name') || e.target.classList.contains('social-icon-input') || e.target.classList.contains('social-url-input')) {
      updatePreview(e.target.closest('tr'));
    }
  });
});
