<?php start_block('title'); ?>
Editar Rol
<?php end_block(); ?>

<?php start_block('breadcrumb'); ?>
<?php render_breadcrumb([
  ['label' => 'Dashboard', 'link' => admin_route('dashboard')],
  ['label' => 'Roles', 'link' => admin_route('roles')],
  ['label' => 'Editar']
]) ?>
<?php end_block(); ?>

<?php start_block('js'); ?>
<?= url_script_admin('security', 'roles.edit') ?>
<?php end_block(); ?>

<form method="POST" autocomplete="off">
  <div class="row g-3">

    <div class="col-12 col-md-4 col-xl-3">
      <div class="card" style="position: sticky; top: 1rem;">
        <div class="card-body">
          <div class="mb-3">
            <label for="role_name" class="form-label">Nombre del Rol <span class="text-danger">*</span></label>
            <input type="text" id="role_name" name="role_name" class="form-control"
              value="<?= htmlspecialchars($role->role_name ?? "") ?>" required>
          </div>

          <div class="mb-3">
            <label for="role_description" class="form-label">Descripción</label>
            <textarea id="role_description" class="form-control" name="role_description" rows="6"
              placeholder="Describe la función de este rol..."><?= htmlspecialchars($role->role_description ?? "") ?></textarea>
          </div>

          <hr>

          <div class="mb-0">
            <label class="form-label">Buscador de Permisos</label>
            <div class="input-group">
              <input type="text" id="search-permissions" class="form-control" placeholder="Filtrar por nombre o clave...">
              <span class="input-group-text bg-body"><i class="fa-solid fa-magnifying-glass opacity-50"></i></span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-12 col-md-8 col-xl-9">
      <div class="card mb-3">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="card-title mb-0 d-flex align-items-center gap-2">
            <i class="fa-solid fa-shield-halved text-success"></i>
            Permisos Asignados
          </h5>
          <?php if (isset($assigned_permissions)): ?>
            <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill">
              <?= count($assigned_permissions) ?> activos
            </span>
          <?php endif; ?>
        </div>

        <div class="card-body p-0">
          <ul class="nav nav-tabs px-3 pt-2 bg-body-secondary border-bottom" id="permissionTabs" role="tablist">
            <li class="nav-item" role="presentation">
              <button class="nav-link active text-uppercase small fw-bold" id="admin-tab" data-bs-toggle="tab" data-bs-target="#admin-perms" type="button" role="tab">
                <i class="fa-solid fa-user-shield me-2"></i>Administrador
              </button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link text-uppercase small fw-bold" id="front-tab" data-bs-toggle="tab" data-bs-target="#front-perms" type="button" role="tab">
                <i class="fa-solid fa-display me-2"></i>Front-end
              </button>
            </li>
          </ul>

          <div class="tab-content p-3" id="permissionTabsContent">
            <!-- TAB ADMIN -->
            <div class="tab-pane fade show active ctx-admin-container" id="admin-perms" role="tabpanel">
              
              <?php 
                $gatekeeper = null;
                foreach ($groupedPermissions as $group => $ctxs) {
                  if (isset($ctxs['admin'])) {
                    foreach ($ctxs['admin'] as $p) {
                      if ($p->permission_key_name === 'access.admin') {
                        $gatekeeper = $p;
                        break 2;
                      }
                    }
                  }
                }
              ?>

              <!-- GATEKEEPER SWITCH -->
              <?php if ($gatekeeper): ?>
                <div class="bg-primary bg-opacity-10 p-3 rounded border border-primary border-opacity-25 mb-4">
                  <div class="form-check form-switch d-flex align-items-center gap-3 ps-0">
                    <div class="flex-grow-1">
                      <label class="form-check-label fw-bold text-primary mb-0" for="perm-<?= $gatekeeper->permission_id ?>">
                        <i class="fa-solid fa-shield-halved me-2"></i>ACCESO AL PANEL ADMINISTRATIVO
                      </label>
                      <div class="small text-muted">Habilita la entrada base al sistema y activa los permisos de esta pestaña.</div>
                    </div>
                    <input id="perm-<?= $gatekeeper->permission_id ?>" class="form-check-input ms-0 h4 mb-0" type="checkbox"
                      name="permissions[]" value="<?= $gatekeeper->permission_id ?>" data-perm-key="access.admin" style="cursor: pointer;"
                      <?= in_array($gatekeeper->permission_id, $assigned_permissions) ? 'checked' : '' ?>>
                  </div>
                </div>
              <?php endif; ?>

              <?php foreach ($groupedPermissions as $groupName => $contexts): ?>
                <?php if (isset($contexts['admin'])): ?>
                  <?php 
                    $admin_count = count(array_filter($contexts['admin'], function($p) {
                      return $p->permission_key_name !== 'access.admin';
                    }));
                    if ($admin_count === 0) continue;
                  ?>
                  <div class="mb-5">
                    <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
                      <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-primary rounded-pill"><?= $admin_count ?></span>
                        <h6 class="mb-0 text-uppercase fw-bold text-primary"><?= htmlspecialchars($groupName) ?></h6>
                      </div>
                      <button type="button" class="btn btn-sm btn-link text-decoration-none text-uppercase small fw-bold p-0 btn-toggle-group" 
                              onclick="toggleGroup('ctx-admin-<?= md5($groupName) ?>')">
                        <i class="fa-solid fa-check-double me-1"></i>Seleccionar todo
                      </button>
                    </div>
                    <div class="row g-3" id="ctx-admin-<?= md5($groupName) ?>">
                      <?php foreach ($contexts['admin'] as $perm): ?>
                        <?php if ($perm->permission_key_name === 'access.admin') continue; ?>
                        <div class="col-12 col-md-6 col-lg-4">
                          <div class="form-check p-3 rounded h-100 bg-body border d-flex align-items-start gap-2" 
                               data-perm-search="<?= htmlspecialchars($perm->permission_name . ' ' . $perm->permission_key_name) ?>">
                            <input id="perm-<?= $perm->permission_id ?>" class="form-check-input mt-1" type="checkbox"
                              name="permissions[]" value="<?= $perm->permission_id ?>" <?= in_array($perm->permission_id, $assigned_permissions) ? 'checked' : '' ?> data-perm-key="<?= htmlspecialchars($perm->permission_key_name) ?>">
                            <label class="form-check-label w-100" for="perm-<?= $perm->permission_id ?>">
                              <span class="d-block fw-bold text-body"><?= htmlspecialchars($perm->permission_name) ?></span>
                              <span class="d-block text-muted small font-monospace mt-1"><?= htmlspecialchars($perm->permission_key_name) ?></span>
                            </label>
                          </div>
                        </div>
                      <?php endforeach; ?>
                    </div>
                  </div>
                <?php endif; ?>
              <?php endforeach; ?>
            </div>

            <!-- TAB FRONT -->
            <div class="tab-pane fade ctx-front-container" id="front-perms" role="tabpanel">
              <?php foreach ($groupedPermissions as $groupName => $contexts): ?>
                <?php if (isset($contexts['front'])): ?>
                  <div class="mb-5">
                    <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
                      <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-info rounded-pill"><?= count($contexts['front']) ?></span>
                        <h6 class="mb-0 text-uppercase fw-bold text-info"><?= htmlspecialchars($groupName) ?></h6>
                      </div>
                      <button type="button" class="btn btn-sm btn-link text-decoration-none text-uppercase small fw-bold p-0" 
                              onclick="toggleGroup('ctx-front-<?= md5($groupName) ?>')">
                        <i class="fa-solid fa-check-double me-1"></i>Seleccionar todo
                      </button>
                    </div>
                    <div class="row g-3" id="ctx-front-<?= md5($groupName) ?>">
                      <?php foreach ($contexts['front'] as $perm): ?>
                        <div class="col-12 col-md-6 col-lg-4">
                          <div class="form-check p-3 rounded h-100 bg-body border d-flex align-items-start gap-2" 
                               data-perm-search="<?= htmlspecialchars($perm->permission_name . ' ' . $perm->permission_key_name) ?>">
                            <input id="perm-<?= $perm->permission_id ?>" class="form-check-input mt-1" type="checkbox"
                              name="permissions[]" value="<?= $perm->permission_id ?>" <?= in_array($perm->permission_id, $assigned_permissions) ? 'checked' : '' ?> data-perm-key="<?= htmlspecialchars($perm->permission_key_name) ?>">
                            <label class="form-check-label w-100" for="perm-<?= $perm->permission_id ?>">
                              <span class="d-block fw-bold text-body"><?= htmlspecialchars($perm->permission_name) ?></span>
                              <span class="d-block text-muted small font-monospace mt-1"><?= htmlspecialchars($perm->permission_key_name) ?></span>
                            </label>
                          </div>
                        </div>
                      <?php endforeach; ?>
                    </div>
                  </div>
                <?php endif; ?>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
      </div>

      <div class="bg-body p-3 rounded d-flex justify-content-end gap-2 sticky-bottom">
        <a href="<?= admin_route("roles") ?>" class="btn btn-outline-secondary px-4 text-uppercase small fw-bold">
          <i class="fa-solid fa-arrow-left me-2"></i>
          Cancelar
        </a>
        <button type="submit" class="btn btn-primary px-5 text-uppercase small fw-bold">
          <i class="fa-solid fa-floppy-disk me-2"></i>
          Guardar Cambios
        </button>
      </div>
    </div>

  </div>
</form>