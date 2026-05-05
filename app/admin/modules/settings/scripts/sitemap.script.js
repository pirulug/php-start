document.addEventListener("DOMContentLoaded", function () {
  const container = document.getElementById("url-container");
  if (!container) return;

  // Los datos de 'pages' deben pasarse vía data-attribute o variable global
  const pages = JSON.parse(container.dataset.pages || '[]');
  const appUrl = container.dataset.appUrl;
  const today = container.dataset.today;

  if (pages.length > 0) {
    pages.forEach((page) => addPageField(container, page, today));
  } else {
    addPageField(container, { loc: appUrl, lastmod: today, priority: '1.0', changefreq: 'daily' }, today);
  }
});

function addPageField(container, page = {}, defaultDate = '') {
  const url = page.loc || '';
  const lastmod = page.lastmod || defaultDate;
  const priority = page.priority || '0.5';
  const freq = page.changefreq || 'monthly';

  const tr = document.createElement("tr");
  tr.classList.add("sitemap-row");

  tr.innerHTML = `
        <td>
            <div class="input-group">
                <span class="input-group-text"><i class="fa-solid fa-link small opacity-50"></i></span>
                <input type="text" class="form-control" name="url[]" value="${url}" placeholder="https://..." required>
            </div>
        </td>
        <td>
            <input type="date" class="form-control form-control-sm" name="lastmod[]" value="${lastmod}" required>
        </td>
        <td>
            <select name="changefreq[]" class="form-select form-select-sm">
                <option value="always" ${freq === "always" ? "selected" : ""}>Siempre</option>
                <option value="hourly" ${freq === "hourly" ? "selected" : ""}>Cada hora</option>
                <option value="daily" ${freq === "daily" ? "selected" : ""}>Diario</option>
                <option value="weekly" ${freq === "weekly" ? "selected" : ""}>Semanal</option>
                <option value="monthly" ${freq === "monthly" ? "selected" : ""}>Mensual</option>
                <option value="yearly" ${freq === "yearly" ? "selected" : ""}>Anual</option>
                <option value="never" ${freq === "never" ? "selected" : ""}>Nunca</option>
            </select>
        </td>
        <td>
            <div class="d-flex align-items-center gap-2">
                <input type="range" class="form-range flex-grow-1" name="priority[]" value="${priority}" min="0.0" max="1.0" step="0.1" oninput="this.nextElementSibling.innerText = parseFloat(this.value).toFixed(1)">
                <span class="badge priority-badge">${parseFloat(priority).toFixed(1)}</span>
            </div>
        </td>
        <td class="text-end pe-4">
            <button type="button" class="btn btn-outline-danger btn-sm" onclick="removePageField(this)" title="Eliminar fila">
                <i class="fa-solid fa-trash-can"></i>
            </button>
        </td>
    `;

  container.appendChild(tr);
}

function removePageField(button) {
  const row = button.closest("tr");
  const container = document.getElementById("url-container");

  if (container.rows.length > 1) {
    row.style.opacity = '0';
    row.style.transform = 'translateX(20px)';
    row.style.transition = 'all 0.3s ease';
    setTimeout(() => row.remove(), 300);
  } else {
    alert("El sitemap debe contener al menos una entrada.");
  }
}

function addNewPage() {
  const container = document.getElementById("url-container");
  if (!container) return;
  const defaultDate = container.dataset.today;
  addPageField(container, {}, defaultDate);
  // Scroll suave a la nueva fila
  container.lastElementChild.scrollIntoView({ behavior: 'smooth', block: 'end' });
}
