<?php block_start("title") ?>
Explorador de Logs
<?php block_end() ?>

<?php block_start("breadcrumb"); ?>
<?php render_breadcrumb([
  ["label" => "Dashboard", "link" => admin_route("dashboard")],
  ["label" => "Explorador de Logs"]
]) ?>
<?php block_end(); ?>

<?php block_start("css") ?>
<style>
  .log-card-header {
    cursor: pointer;
    user-select: none;
    transition: background-color 0.15s ease;
  }
  .log-card-header:hover {
    background-color: rgba(var(--bs-primary-rgb), 0.05);
  }
  .log-card-header .collapse-icon {
    transition: transform 0.2s ease;
  }
  .log-card-header.collapsed .collapse-icon {
    transform: rotate(-90deg);
  }
  .load-more-btn {
    border-style: dashed;
  }
  #logSearchInput:focus {
    box-shadow: 0 0 0 0.2rem rgba(var(--bs-primary-rgb), 0.2);
  }
  .tab-count-badge {
    font-size: 0.7rem;
    vertical-align: middle;
  }
  .log-empty-state {
    padding: 3rem 1rem;
    text-align: center;
    color: var(--bs-secondary-color);
  }
  .log-empty-state i {
    font-size: 2.5rem;
    margin-bottom: 0.75rem;
    display: block;
    opacity: 0.5;
  }
  .skeleton-row td {
    padding: 0.75rem 0;
  }
  .skeleton-pulse {
    display: inline-block;
    height: 1rem;
    background: linear-gradient(90deg, var(--bs-secondary-bg) 25%, var(--bs-tertiary-bg) 50%, var(--bs-secondary-bg) 75%);
    background-size: 200% 100%;
    animation: skeleton-pulse 1.2s infinite;
    border-radius: 4px;
  }
  @keyframes skeleton-pulse {
    0%   { background-position: 200% 0; }
    100% { background-position: -200% 0; }
  }
</style>
<?php block_end() ?>

<div class="bg-body p-3 rounded mb-3 d-flex align-items-center justify-content-between flex-column flex-md-row gap-3">
  <div>
    <h5 class="m-0 fw-bold text-uppercase"><i class="fa-solid fa-folder-open me-2 text-warning"></i>Logs del Sistema</h5>
    <span class="badge bg-secondary"><?= $total_files ?> archivos totales</span>
  </div>
  <div style="max-width: 320px; width: 100%;">
    <input type="text" id="logSearchInput" class="form-control" placeholder="Buscar usuario, IP o fecha...">
  </div>
</div>

<!-- TABS DE NAVEGACION -->
<ul class="nav nav-tabs mb-3" id="logsTabs" role="tablist">
  <li class="nav-item" role="presentation">
    <button class="nav-link active fw-bold text-uppercase" id="users-tab"
            data-bs-toggle="tab" data-bs-target="#users-pane" type="button" role="tab"
            data-log-tab="usuarios">
      <i class="fa-solid fa-users me-2"></i>Usuarios
      <span class="badge bg-primary ms-1 tab-count-badge"><?= $count_users ?></span>
    </button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="nav-link fw-bold text-uppercase" id="ips-tab"
            data-bs-toggle="tab" data-bs-target="#ips-pane" type="button" role="tab"
            data-log-tab="ips">
      <i class="fa-solid fa-network-wired me-2"></i>Direcciones IP
      <span class="badge bg-secondary ms-1 tab-count-badge"><?= $count_ips ?></span>
    </button>
  </li>
  <?php if ($count_otros > 0): ?>
    <li class="nav-item" role="presentation">
      <button class="nav-link fw-bold text-uppercase" id="other-tab"
              data-bs-toggle="tab" data-bs-target="#other-pane" type="button" role="tab"
              data-log-tab="otros">
        <i class="fa-solid fa-file-invoice me-2"></i>Otros
        <span class="badge bg-warning ms-1 tab-count-badge"><?= $count_otros ?></span>
      </button>
    </li>
  <?php endif; ?>
</ul>

<div class="tab-content" id="logsTabsContent">
  <!-- PANE: USUARIOS -->
  <div class="tab-pane fade show active" id="users-pane" role="tabpanel" tabindex="0">
    <div id="usuarios-container"></div>
    <div id="usuarios-load-more" class="text-center my-3" style="display:none;">
      <button class="btn btn-outline-secondary text-uppercase fw-bold load-more-btn px-4"
              data-tab="usuarios">
        <i class="fa-solid fa-chevron-down me-2"></i>Cargar mas
      </button>
    </div>
  </div>

  <!-- PANE: IPS -->
  <div class="tab-pane fade" id="ips-pane" role="tabpanel" tabindex="0">
    <div id="ips-container"></div>
    <div id="ips-load-more" class="text-center my-3" style="display:none;">
      <button class="btn btn-outline-secondary text-uppercase fw-bold load-more-btn px-4"
              data-tab="ips">
        <i class="fa-solid fa-chevron-down me-2"></i>Cargar mas
      </button>
    </div>
  </div>

  <?php if ($count_otros > 0): ?>
    <!-- PANE: OTROS -->
    <div class="tab-pane fade" id="other-pane" role="tabpanel" tabindex="0">
      <div id="otros-container"></div>
      <div id="otros-load-more" class="text-center my-3" style="display:none;">
        <button class="btn btn-outline-secondary text-uppercase fw-bold load-more-btn px-4"
                data-tab="otros">
          <i class="fa-solid fa-chevron-down me-2"></i>Cargar mas
        </button>
      </div>
    </div>
  <?php endif; ?>
</div>

<?php block_start("js") ?>
<script>
(function() {
  "use strict";

  const ENDPOINT_URL = "<?= APP_URL.admin_route("logs/endpoint/list") ?>";

  // Estado por tab
  const state = {
    usuarios : { page: 1, loading: false, done: false, search: "" },
    ips      : { page: 1, loading: false, done: false, search: "" },
    otros    : { page: 1, loading: false, done: false, search: "" }
  };

  // Ultimo timeout del buscador (debounce)
  let searchTimer = null;

  // -------------------------------------------------------------------------
  // HELPERS DE RENDER
  // -------------------------------------------------------------------------

  /**
   * Devuelve el HTML de la clase de badge segun el tab activo.
   */
  function badge_class(tab) {
    if (tab === "usuarios") return "bg-primary";
    if (tab === "ips")      return "bg-secondary";
    return "bg-warning";
  }

  /**
   * Devuelve el HTML del icono del encabezado de la tarjeta.
   */
  function header_icon(tab) {
    if (tab === "usuarios") return "<i class=\"fa-solid fa-user me-2\"></i>";
    if (tab === "ips")      return "<i class=\"fa-solid fa-location-dot me-2\"></i>";
    return "<i class=\"fa-solid fa-file me-2\"></i>";
  }

  /**
   * Devuelve el texto del encabezado de la tarjeta.
   */
  function header_label(tab, display_key) {
    if (tab === "usuarios") return "Usuario: " + display_key;
    if (tab === "ips")      return "Direccion IP: " + display_key;
    return display_key;
  }

  /**
   * Genera el HTML de una tarjeta colapsable de grupo (usuario o IP).
   */
  function render_group_card(tab, group, idx) {
    const collapse_id = "collapse_" + tab + "_" + group.key.replace(/[^a-zA-Z0-9]/g, "_") + "_" + idx;
    const b_class     = badge_class(tab);
    const icon        = header_icon(tab);
    const label       = header_label(tab, group.display_key);

    let rows_html = "";
    group.files.forEach(function(file) {
      rows_html += `
        <tr>
          <td class="ps-3 py-3 font-monospace fw-bold">${escHtml(file.name)}</td>
          <td>${file.size_kb} KB</td>
          <td class="small text-body-secondary">${file.date_fmt}</td>
          <td class="text-end pe-3">
            <a href="${file.url}" class="btn btn-outline-primary btn-sm text-uppercase fw-bold">
              <i class="fa-solid fa-eye me-1"></i>Ver Log
            </a>
          </td>
        </tr>`;
    });

    return `
      <div class="card mb-3">
        <div class="card-header py-3 d-flex align-items-center justify-content-between log-card-header collapsed"
             data-bs-toggle="collapse" data-bs-target="#${collapse_id}" aria-expanded="false">
          <h6 class="card-title m-0 fw-bold text-uppercase">
            ${icon}${escHtml(label)}
          </h6>
          <div class="d-flex align-items-center gap-2">
            <span class="badge ${b_class} rounded-pill">${group.count} logs</span>
            <i class="fa-solid fa-chevron-down collapse-icon text-body-secondary"></i>
          </div>
        </div>
        <div id="${collapse_id}" class="collapse">
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-hover align-middle table-sm m-0">
                <thead>
                  <tr>
                    <th class="ps-3 py-2">Fecha del Log</th>
                    <th class="py-2">Tamano</th>
                    <th class="py-2">Ultima Modificacion</th>
                    <th class="text-end pe-3 py-2">Accion</th>
                  </tr>
                </thead>
                <tbody>${rows_html}</tbody>
              </table>
            </div>
          </div>
        </div>
      </div>`;
  }

  /**
   * Genera el HTML de las filas de la tabla "otros".
   */
  function render_otros_rows(rows) {
    let html = "";
    rows.forEach(function(file) {
      html += `
        <tr>
          <td class="ps-3 py-3 font-monospace fw-bold">${escHtml(file.relative_path)}</td>
          <td>${file.size_kb} KB</td>
          <td class="small text-body-secondary">${file.date_fmt}</td>
          <td class="text-end pe-3">
            <a href="${file.url}" class="btn btn-outline-primary btn-sm text-uppercase fw-bold">
              <i class="fa-solid fa-eye me-1"></i>Ver Log
            </a>
          </td>
        </tr>`;
    });
    return html;
  }

  /**
   * Genera las filas skeleton para el estado de carga.
   */
  function render_skeleton(count, cols) {
    let html = "";
    for (let i = 0; i < count; i++) {
      let tds = "";
      for (let j = 0; j < cols; j++) {
        const w = [60, 40, 70, 30][j] || 50;
        tds += `<td class="ps-3"><span class="skeleton-pulse" style="width:${w}%;"></span></td>`;
      }
      html += `<tr class="skeleton-row">${tds}</tr>`;
    }
    return html;
  }

  /**
   * Escapa HTML para prevenir XSS.
   */
  function escHtml(str) {
    const d = document.createElement("div");
    d.textContent = str || "";
    return d.innerHTML;
  }

  // -------------------------------------------------------------------------
  // LOGICA DE FETCH
  // -------------------------------------------------------------------------

  /**
   * Carga una pagina de datos del endpoint para el tab indicado.
   * Si reset=true, limpia el contenedor y reinicia la pagina.
   */
  function load_tab(tab, reset) {
    const s           = state[tab];
    const container   = document.getElementById(tab === "otros" ? "otros-container" : tab + "-container");
    const load_more   = document.getElementById((tab === "otros" ? "otros" : tab) + "-load-more");

    if (!container) return;

    if (s.loading) return;
    if (!reset && s.done) return;

    if (reset) {
      s.page   = 1;
      s.done   = false;
      container.innerHTML = "";
      if (load_more) load_more.style.display = "none";
    }

    s.loading = true;

    // Mostrar skeleton en primera pagina
    if (s.page === 1) {
      if (tab === "otros") {
        let wrapper = document.getElementById("otros-table-wrapper");
        if (!wrapper) {
          container.innerHTML = `
            <div class="bg-body p-3 rounded" id="otros-table-wrapper">
              <div class="table-responsive">
                <table class="table table-hover align-middle table-sm m-0">
                  <thead>
                    <tr>
                      <th class="ps-3 py-2">Archivo</th>
                      <th class="py-2">Tamano</th>
                      <th class="py-2">Ultima Modificacion</th>
                      <th class="text-end pe-3 py-2">Accion</th>
                    </tr>
                  </thead>
                  <tbody id="otros-tbody">${render_skeleton(4, 4)}</tbody>
                </table>
              </div>
            </div>`;
        }
      } else {
        container.innerHTML = render_skeleton_cards(3);
      }
    }

    const params = new URLSearchParams({
      tab    : tab,
      page   : s.page,
      search : s.search
    });

    fetch(ENDPOINT_URL + "?" + params.toString(), {
      headers: { "Accept": "application/json" }
    })
    .then(function(r) {
      if (!r.ok) {
        return r.text().then(function(text) {
          throw new Error("HTTP " + r.status + ": " + text.substring(0, 200));
        });
      }
      return r.text().then(function(text) {
        try {
          return JSON.parse(text);
        } catch (e) {
          throw new Error("JSON invalido: " + text.substring(0, 200));
        }
      });
    })
    .then(function(data) {
      s.loading = false;
      s.page++;

      if (data.has_more === false) {
        s.done = true;
        if (load_more) load_more.style.display = "none";
      } else {
        if (load_more) load_more.style.display = "block";
      }

      if (tab === "otros") {
        render_otros_data(data, container, s.page === 2);
      } else {
        render_groups_data(tab, data, container, s.page === 2);
      }

      // Si no hay resultados
      if (tab === "otros") {
        const tbody = document.getElementById("otros-tbody");
        if (tbody && tbody.querySelectorAll("tr:not(.skeleton-row)").length === 0 && data.rows && data.rows.length === 0 && s.page === 2) {
          render_empty(container);
        }
      } else {
        if (container.querySelectorAll(".card").length === 0 && data.groups && data.groups.length === 0 && s.page === 2) {
          render_empty(container);
        }
      }
    })
    .catch(function(err) {
      s.loading = false;
      console.error("Error al cargar logs:", err.message || err);
      container.innerHTML = `<div class="bg-body p-3 rounded text-center text-danger">
        <i class="fa-solid fa-circle-exclamation d-block fs-4 mb-2"></i>
        Error al cargar los datos. Revisa la consola para mas detalles.
      </div>`;
    });
  }

  /**
   * Renderiza los datos de grupos (usuarios o ips) en el contenedor.
   */
  function render_groups_data(tab, data, container, is_first) {
    if (is_first) {
      container.innerHTML = "";
    }

    if (!data.groups || data.groups.length === 0) {
      if (is_first) {
        render_empty(container);
      }
      return;
    }

    let html = "";
    data.groups.forEach(function(group, idx) {
      html += render_group_card(tab, group, (data.page - 1) * 10 + idx);
    });

    const frag = document.createElement("div");
    frag.innerHTML = html;
    while (frag.firstChild) {
      container.appendChild(frag.firstChild);
    }

    // Actualizar icono de colapso al expandir/cerrar via Bootstrap
    container.querySelectorAll(".log-card-header").forEach(function(header) {
      if (header._logCollapseBound) return;
      header._logCollapseBound = true;
      const target_id = header.getAttribute("data-bs-target");
      const collapse_el = document.querySelector(target_id);
      if (collapse_el) {
        collapse_el.addEventListener("show.bs.collapse", function() {
          header.classList.remove("collapsed");
          header.setAttribute("aria-expanded", "true");
        });
        collapse_el.addEventListener("hide.bs.collapse", function() {
          header.classList.add("collapsed");
          header.setAttribute("aria-expanded", "false");
        });
      }
    });
  }

  /**
   * Renderiza los datos de la tabla "otros".
   */
  function render_otros_data(data, container, is_first) {
    let tbody = document.getElementById("otros-tbody");

    if (is_first || !tbody) {
      container.innerHTML = `
        <div class="bg-body p-3 rounded" id="otros-table-wrapper">
          <div class="table-responsive">
            <table class="table table-hover align-middle table-sm m-0">
              <thead>
                <tr>
                  <th class="ps-3 py-2">Archivo</th>
                  <th class="py-2">Tamano</th>
                  <th class="py-2">Ultima Modificacion</th>
                  <th class="text-end pe-3 py-2">Accion</th>
                </tr>
              </thead>
              <tbody id="otros-tbody"></tbody>
            </table>
          </div>
        </div>`;
      tbody = document.getElementById("otros-tbody");
    }

    if (!data.rows || data.rows.length === 0) {
      if (is_first) {
        container.innerHTML = "";
        render_empty(container);
      }
      return;
    }

    tbody.innerHTML = (tbody.innerHTML || "") + render_otros_rows(data.rows);
  }

  /**
   * Renderiza tarjetas skeleton de carga para grupos.
   */
  function render_skeleton_cards(count) {
    let html = "";
    for (let i = 0; i < count; i++) {
      html += `
        <div class="card mb-3">
          <div class="card-header py-3 d-flex align-items-center justify-content-between">
            <span class="skeleton-pulse" style="width: 35%;"></span>
            <span class="skeleton-pulse" style="width: 8%;"></span>
          </div>
        </div>`;
    }
    return html;
  }

  /**
   * Renderiza el estado vacio en el contenedor.
   */
  function render_empty(container) {
    container.innerHTML = `
      <div class="bg-body rounded log-empty-state">
        <i class="fa-solid fa-inbox"></i>
        No se encontraron registros.
      </div>`;
  }

  // -------------------------------------------------------------------------
  // INICIALIZAR Y EVENTOS
  // -------------------------------------------------------------------------

  document.addEventListener("DOMContentLoaded", function() {

    // Cargar tab inicial
    load_tab("usuarios", true);

    // Cargar tab al cambiar (primera vez o con busqueda activa diferente)
    document.querySelectorAll("[data-log-tab]").forEach(function(btn) {
      btn.addEventListener("shown.bs.tab", function() {
        const tab         = btn.getAttribute("data-log-tab");
        const search_el   = document.getElementById("logSearchInput");
        const current_val = search_el ? search_el.value.toLowerCase().trim() : "";

        // Si nunca se cargo (page sigue en 1 y no esta en proceso) o si la busqueda cambio
        if (state[tab].page === 1 && !state[tab].loading) {
          state[tab].search = current_val;
          load_tab(tab, true);
        } else if (current_val !== state[tab].search) {
          state[tab].search = current_val;
          load_tab(tab, true);
        }
      });
    });

    // Botones de cargar mas
    document.querySelectorAll(".load-more-btn").forEach(function(btn) {
      btn.addEventListener("click", function() {
        const tab = btn.getAttribute("data-tab");
        load_tab(tab, false);
      });
    });

    // Buscador con debounce
    const search_input = document.getElementById("logSearchInput");
    if (search_input) {
      search_input.addEventListener("input", function() {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(function() {
          const value      = search_input.value.toLowerCase().trim();
          const active_btn = document.querySelector("[data-log-tab].active");
          const active_tab = active_btn ? active_btn.getAttribute("data-log-tab") : "usuarios";

          state[active_tab].search = value;
          load_tab(active_tab, true);
        }, 350);
      });
    }

  });

})();
</script>
<?php block_end() ?>
