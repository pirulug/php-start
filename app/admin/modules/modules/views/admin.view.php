<?php block_start("title") ?>
  Módulos de Administración
<?php block_end() ?>

<?php block_start("breadcrumb"); ?>
<?php render_breadcrumb([
  ["label" => "Dashboard", "link" => admin_route("dashboard")],
  ["label" => "Módulos"]
]) ?>
<?php block_end(); ?>

<?php block_start("css") ?>
<style>
  .module-list-group .list-group-item {
    transition: background-color 0.2s ease, border-color 0.2s ease;
    border-color: rgba(0, 0, 0, 0.08);
  }
  [data-bs-theme="dark"] .module-list-group .list-group-item {
    border-color: rgba(255, 255, 255, 0.08);
  }
  .drag-handle {
    cursor: grab;
    color: var(--pr-secondary-color);
    display: flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    border-radius: 4px;
    transition: background-color 0.15s ease, color 0.15s ease;
  }
  .drag-handle:hover {
    background-color: var(--pr-secondary-bg);
    color: var(--pr-primary);
  }
  .sortable-chosen {
    background-color: var(--pr-secondary-bg) !important;
    border-color: var(--pr-primary-border-subtle) !important;
  }
  .sortable-ghost {
    opacity: 0.4;
  }
  .cursor-grab {
    cursor: grab;
  }
  
  #admin-modules-list .drag-handle {
    display: flex !important;
  }
  #admin-modules-no-sidebar-list .drag-handle {
    display: none !important;
  }
</style>
<?php block_end() ?>

<form action="<?= admin_route("modules") ?>" method="POST" id="modules-form">
  <div class="card mb-3">
    <div class="card-header bg-transparent border-bottom py-3">
      <?php require_once BASE_DIR . "/app/admin/modules/modules/views/partials/nav.partial.php"; ?>
    </div>
    
    <div class="card-body p-3">
      <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
        <div>
          <h5 class="card-title mb-1">Módulos de Administración</h5>
          <p class="text-muted small mb-0">Gestiona el estado y el orden de aparición de los módulos del panel administrativo.</p>
        </div>
        <div class="form-check form-switch m-0 d-flex align-items-center gap-2">
          <input class="form-check-input" type="checkbox" role="switch" id="toggle-no-sidebar-modules" checked>
          <span class="text-muted small">Ver módulos sin menú lateral</span>
        </div>
      </div>
      
      <!-- SECCIÓN: CON SIDEBAR (ORDENABLES) -->
      <div class="mb-3">
        <h6 class="text-uppercase fw-bold text-primary small mb-2">Módulos con Menú Lateral (Sidebar)</h6>
        <p class="text-muted small">Arrastra y ordena las posiciones del menú lateral. Los interruptores activan o desactivan las rutas y el menú.</p>
        
        <div class="list-group module-list-group" id="admin-modules-list">
          <?php foreach ($view_admin_with_sidebar as $name => $info): ?>
            <div class="list-group-item d-flex align-items-center justify-content-between p-3 module-item" data-module="<?= clear_html($name) ?>">
              <div class="d-flex align-items-center gap-3">
                <div class="drag-handle cursor-grab">
                  <i class="fa-solid fa-grip-vertical"></i>
                </div>
                <div>
                  <h6 class="mb-0 text-uppercase fw-bold">
                    <?= clear_html($name) ?>
                    <?php if ($info["protected"]): ?>
                      <i class="fa-solid fa-lock text-warning small ms-2" title="Módulo del sistema protegido"></i>
                    <?php endif; ?>
                  </h6>
                  <span class="text-muted small">Ubicado en /app/admin/modules/<?= clear_html($name) ?></span>
                </div>
              </div>
              
              <div class="d-flex align-items-center gap-4">
                <!-- Switch: Activo -->
                <div class="form-check form-switch m-0 d-flex align-items-center gap-2">
                  <input class="form-check-input" type="checkbox" role="switch" name="admin_active[<?= clear_html($name) ?>]" id="switch_admin_active_<?= clear_html($name) ?>" <?= $info["active"] ? "checked" : "" ?> <?= $info["protected"] ? "disabled" : "" ?>>
                  <span class="small text-muted">Activo</span>
                </div>
                
                <!-- Switch: Sidebar -->
                <?php if ($info["has_sidebar"]): ?>
                  <div class="form-check form-switch m-0 d-flex align-items-center gap-2">
                    <input class="form-check-input sidebar-switch-input" type="checkbox" role="switch" name="admin_sidebar[<?= clear_html($name) ?>]" id="switch_admin_sidebar_<?= clear_html($name) ?>" <?= $info["sidebar"] ? "checked" : "" ?>>
                    <span class="small text-muted">Sidebar</span>
                  </div>
                <?php endif; ?>
                
                <input type="hidden" class="module-order-input" name="admin_order[<?= clear_html($name) ?>]" value="<?= (int)$info["order"] ?>">
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
      
      <!-- SECCIÓN: SIN SIDEBAR (NO ORDENABLES) -->
      <div class="mt-3" id="no-sidebar-section">
        <h6 class="text-uppercase fw-bold text-secondary small mb-2">Módulos de Soporte / Lógica (Sin Menú)</h6>
        <p class="text-muted small">Módulos de administración que no definen ítems de barra lateral. Puedes activarlos o desactivarlos.</p>
        
        <div class="list-group module-list-group" id="admin-modules-no-sidebar-list">
          <?php foreach ($view_admin_no_sidebar as $name => $info): ?>
            <div class="list-group-item d-flex align-items-center justify-content-between p-3 module-item" data-module="<?= clear_html($name) ?>">
              <div class="d-flex align-items-center gap-3">
                <div class="drag-handle cursor-grab">
                  <i class="fa-solid fa-grip-vertical"></i>
                </div>
                <div>
                  <h6 class="mb-0 text-uppercase fw-bold">
                    <?= clear_html($name) ?>
                    <?php if ($info["protected"]): ?>
                      <i class="fa-solid fa-lock text-warning small ms-2" title="Módulo del sistema protegido"></i>
                    <?php endif; ?>
                  </h6>
                  <span class="text-muted small">Ubicado en /app/admin/modules/<?= clear_html($name) ?></span>
                </div>
              </div>
              
              <div class="d-flex align-items-center gap-4">
                <!-- Switch: Activo -->
                <div class="form-check form-switch m-0 d-flex align-items-center gap-2">
                  <input class="form-check-input" type="checkbox" role="switch" name="admin_active[<?= clear_html($name) ?>]" id="switch_admin_no_active_<?= clear_html($name) ?>" <?= $info["active"] ? "checked" : "" ?> <?= $info["protected"] ? "disabled" : "" ?>>
                  <span class="small text-muted">Activo</span>
                </div>
                
                <!-- Switch: Sidebar -->
                <?php if ($info["has_sidebar"]): ?>
                  <div class="form-check form-switch m-0 d-flex align-items-center gap-2">
                    <input class="form-check-input sidebar-switch-input" type="checkbox" role="switch" name="admin_sidebar[<?= clear_html($name) ?>]" id="switch_admin_no_sidebar_<?= clear_html($name) ?>" <?= $info["sidebar"] ? "checked" : "" ?>>
                    <span class="small text-muted">Sidebar</span>
                  </div>
                <?php endif; ?>
                
                <input type="hidden" class="module-order-input" name="admin_order[<?= clear_html($name) ?>]" value="<?= (int)$info["order"] ?>">
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
      
    </div>
  </div>

  <div class="bg-body p-3 rounded d-flex justify-content-end gap-2 sticky-bottom">
    <a href="<?= admin_route("dashboard") ?>" class="btn btn-outline-secondary px-4 text-uppercase fw-bold">
      <i class="fa-solid fa-arrow-left me-2"></i>
      Volver
    </a>
    <button type="submit" class="btn btn-primary px-5 text-uppercase fw-bold">
      <i class="fa-solid fa-floppy-disk me-2"></i>
      Guardar Cambios
    </button>
  </div>
</form>

<?php block_start("js") ?>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
  document.addEventListener("DOMContentLoaded", function () {
    const withSidebarList = document.getElementById("admin-modules-list");
    const noSidebarList = document.getElementById("admin-modules-no-sidebar-list");
    
    function recalculateOrders() {
      if (withSidebarList) {
        const items = withSidebarList.querySelectorAll(".module-item");
        items.forEach((item, index) => {
          const input = item.querySelector(".module-order-input");
          if (input) {
            input.value = index + 1;
          }
        });
      }
    }

    if (withSidebarList) {
      new Sortable(withSidebarList, {
        animation: 150,
        handle: ".drag-handle",
        onEnd: function () {
          recalculateOrders();
        }
      });
    }

    document.addEventListener("change", function (e) {
      if (e.target && e.target.classList.contains("sidebar-switch-input")) {
        const checkbox = e.target;
        const item = checkbox.closest(".module-item");
        if (!item) return;
        
        if (checkbox.checked) {
          if (withSidebarList) {
            withSidebarList.appendChild(item);
            recalculateOrders();
          }
        } else {
          if (noSidebarList) {
            const orderInput = item.querySelector(".module-order-input");
            if (orderInput) {
              orderInput.value = 999;
            }
            noSidebarList.appendChild(item);
            recalculateOrders();
          }
        }
      }
    });

    const toggleSwitch = document.getElementById("toggle-no-sidebar-modules");
    const noSidebarSection = document.getElementById("no-sidebar-section");
    if (toggleSwitch && noSidebarSection) {
      toggleSwitch.addEventListener("change", function () {
        if (this.checked) {
          noSidebarSection.style.display = "block";
        } else {
          noSidebarSection.style.display = "none";
        }
      });

      if (toggleSwitch.checked) {
        noSidebarSection.style.display = "block";
      } else {
        noSidebarSection.style.display = "none";
      }
    }
  });
</script>
<?php block_end() ?>
