<?php start_block('title'); ?>
Permisos: <?= clear_html($user->user_login) ?>
<?php end_block(); ?>

<?php start_block('breadcrumb'); ?>
<?php render_breadcrumb([
  ['label' => 'Dashboard', 'link' => admin_route('dashboard')],
  ['label' => 'Usuarios', 'link' => admin_route('users')],
  ['label' => 'Permisos de ' . $user->user_login]
]) ?>
<?php end_block(); ?>

<?php start_block('js'); ?>
<?= admin_modules_script('users', 'permissions.view') ?>
<?php end_block(); ?>

<div class="row g-3">

  <div class="col-md-8">
    <!-- Buscador de permisos -->
    <div class="bg-body p-3 rounded mb-3">
      <div class="input-group">
        <span class="input-group-text bg-transparent border-end-0">
          <i class="fa-solid fa-magnifying-glass text-muted"></i>
        </span>
        <input type="text" id="search-permissions" class="form-control border-start-0 ps-0" placeholder="Filtrar permisos por nombre o clave...">
      </div>
    </div>

    <form method="POST">
      
      <div class="card mb-3">
        <div class="card-header p-0 border-bottom-0">
          <ul class="nav nav-tabs px-3 pt-2 bg-body-secondary" id="permissionTabs" role="tablist">
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
        </div>

        <div class="card-body p-0">
          <div class="tab-content" id="permissionTabsContent">
            
            <!-- TAB ADMIN -->
            <div class="tab-pane fade show active ctx-admin-container" id="admin-perms" role="tabpanel">
              
              <?php 
                // Extraer gatekeeper
                $gatekeeper = null;
                // En este módulo la llave incluye el contexto (admin:access.admin)
                $gatekeeperKey = 'admin:access.admin';

                foreach ($groupedPermissions as $group => $ctxs) {
                  if (isset($ctxs['admin'])) {
                    foreach ($ctxs['admin'] as $p) {
                      if ($p->key === $gatekeeperKey) {
                        $gatekeeper = $p;
                        break 2;
                      }
                    }
                  }
                }

                if ($gatekeeper) {
                  $isInherited = in_array($gatekeeperKey, $rolePermissions);
                  $isPersonalized = in_array($gatekeeperKey, $userPermissions);
                }
              ?>

              <!-- GATEKEEPER SWITCH (MISMO ESTILO QUE ROLES) -->
              <?php if ($gatekeeper): ?>
                <div class="p-3">
                  <div class="bg-primary bg-opacity-10 p-3 rounded border border-primary border-opacity-25 mb-4">
                    <div class="form-check form-switch d-flex align-items-center gap-3 ps-0">
                      <div class="flex-grow-1">
                        <label class="form-check-label fw-bold text-primary mb-0" for="perm-admin:access.admin">
                          <i class="fa-solid fa-shield-halved me-2"></i>ACCESO AL PANEL ADMINISTRATIVO
                        </label>
                        <div class="small text-muted">Habilita la entrada base al sistema y activa los permisos de esta pestaña.</div>
                        <?php if ($isInherited): ?>
                          <div class="badge bg-secondary bg-opacity-10 text-secondary small mt-1">HEREDADO DEL ROL</div>
                        <?php endif; ?>
                      </div>
                      <input id="perm-admin:access.admin" class="form-check-input ms-0 h4 mb-0" type="checkbox" 
                        name="permissions[]" value="<?= $gatekeeper->key ?>" 
                        data-perm-key="access.admin" style="cursor: pointer;"
                        <?= ($isInherited || $isPersonalized) ? 'checked' : '' ?>
                        <?= $isInherited ? 'disabled data-inherited="true"' : '' ?>
                        >
                    </div>
                  </div>
                </div>
              <?php endif; ?>

              <div class="px-3 pb-3">
                <?php foreach ($groupedPermissions as $groupName => $contexts): ?>
                  <?php if (isset($contexts['admin'])): ?>
                    <?php 
                      $admin_perms = array_filter($contexts['admin'], function($p) use ($gatekeeperKey) { 
                        return $p->key !== $gatekeeperKey; 
                      });
                      if (empty($admin_perms)) continue;
                    ?>
                    <div class="card mb-4 card-group-container">
                      <div class="card-header bg-body d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 text-uppercase fw-bold text-primary small">
                          <i class="fa-solid fa-layer-group me-2 text-secondary"></i><?= clear_html($groupName) ?>
                        </h6>
                        <button type="button" class="btn btn-sm btn-link text-decoration-none text-uppercase small fw-bold p-0 btn-toggle-group" 
                                onclick="toggleGroup('group-admin-<?= md5($groupName) ?>')">
                          <i class="fa-solid fa-check-double me-1"></i>Seleccionar todo
                        </button>
                      </div>
                      <div class="list-group list-group-flush" id="group-admin-<?= md5($groupName) ?>">
                        <?php foreach ($admin_perms as $perm): ?>
                          <?php 
                            $isInherited = in_array($perm->key, $rolePermissions);
                            $isPersonalized = in_array($perm->key, $userPermissions);
                            $permId = "perm_" . md5($perm->key);
                          ?>
                          <label class="list-group-item d-flex align-items-center py-3 hover-bg-body transition-all border-0 border-bottom" data-perm-search="<?= clear_html($perm->name . ' ' . $perm->key) ?>">
                            <div class="form-check form-switch mb-0">
                              <input class="form-check-input" type="checkbox" name="permissions[]" value="<?= $perm->key ?>" id="<?= $permId ?>"
                                <?= ($isInherited || $isPersonalized) ? 'checked' : '' ?>
                                <?= $isInherited ? 'disabled data-inherited="true"' : '' ?>>
                            </div>
                            <div class="ms-3 flex-grow-1">
                              <div class="fw-bold text-body small">
                                <?= clear_html($perm->name) ?>
                                <?php if ($isInherited): ?>
                                  <span class="badge bg-secondary bg-opacity-10 text-secondary small ms-1" style="font-size: 0.6rem;">HEREDADO</span>
                                <?php endif; ?>
                              </div>
                              <div class="text-muted small font-monospace" style="font-size: 0.7rem;"><?= clear_html($perm->key) ?></div>
                            </div>
                          </label>
                        <?php endforeach; ?>
                      </div>
                    </div>
                  <?php endif; ?>
                <?php endforeach; ?>
              </div>
            </div>

            <!-- TAB FRONT -->
            <div class="tab-pane fade ctx-front-container" id="front-perms" role="tabpanel">
              <div class="p-3">
                <?php foreach ($groupedPermissions as $groupName => $contexts): ?>
                  <?php if (isset($contexts['front'])): ?>
                    <div class="card mb-4 card-group-container">
                      <div class="card-header bg-body d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 text-uppercase fw-bold text-info small">
                          <i class="fa-solid fa-layer-group me-2 text-secondary"></i><?= clear_html($groupName) ?>
                        </h6>
                        <button type="button" class="btn btn-sm btn-link text-decoration-none text-uppercase small fw-bold p-0" 
                                onclick="toggleGroup('group-front-<?= md5($groupName) ?>')">
                          <i class="fa-solid fa-check-double me-1"></i>Seleccionar todo
                        </button>
                      </div>
                      <div class="list-group list-group-flush" id="group-front-<?= md5($groupName) ?>">
                        <?php foreach ($contexts['front'] as $perm): ?>
                          <?php 
                            $isInherited = in_array($perm->key, $rolePermissions);
                            $isPersonalized = in_array($perm->key, $userPermissions);
                            $permId = "perm_" . md5($perm->key);
                          ?>
                          <label class="list-group-item d-flex align-items-center py-3 hover-bg-body transition-all border-0 border-bottom" data-perm-search="<?= clear_html($perm->name . ' ' . $perm->key) ?>">
                            <div class="form-check form-switch mb-0">
                              <input class="form-check-input" type="checkbox" name="permissions[]" value="<?= $perm->key ?>" id="<?= $permId ?>"
                                <?= ($isInherited || $isPersonalized) ? 'checked' : '' ?>
                                <?= $isInherited ? 'disabled data-inherited="true"' : '' ?>>
                            </div>
                            <div class="ms-3 flex-grow-1">
                              <div class="fw-bold text-body small">
                                <?= clear_html($perm->name) ?>
                                <?php if ($isInherited): ?>
                                  <span class="badge bg-secondary bg-opacity-10 text-secondary small ms-1" style="font-size: 0.6rem;">HEREDADO</span>
                                <?php endif; ?>
                              </div>
                              <div class="text-muted small font-monospace" style="font-size: 0.7rem;"><?= clear_html($perm->key) ?></div>
                            </div>
                          </label>
                        <?php endforeach; ?>
                      </div>
                    </div>
                  <?php endif; ?>
                <?php endforeach; ?>
              </div>
            </div>

          </div>
        </div>
      </div>

      <!-- BOTONERA -->
      <div class="bg-body p-3 rounded d-flex justify-content-end gap-2 sticky-bottom shadow-sm">
        <a href="<?= admin_route('users') ?>" class="btn btn-outline-secondary px-4 text-uppercase small fw-bold">
          <i class="fa-solid fa-arrow-left me-2"></i>Volver
        </a>
        <button type="submit" class="btn btn-primary px-5 text-uppercase small fw-bold">
          <i class="fa-solid fa-floppy-disk me-2"></i>Guardar Permisos
        </button>
      </div>
    </form>
  </div>

  <div class="col-md-4">
    <div class="card mb-3 sticky-top" style="top: 1rem;">
      <div class="card-body text-center p-3">
        <div class="mb-3">
          <?php if (!empty($user->user_image) && file_exists(BASE_DIR . "/storage/uploads/user/" . ($user->user_image))): ?>
            <img src="<?= APP_URL . "/storage/uploads/user/" . $user->user_image ?>" alt="Avatar"
              class="rounded-circle border" style="width: 100px; height: 100px; object-fit: cover;">
          <?php else: ?>
            <div
              class="rounded-circle bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center text-primary fw-bold border border-primary border-opacity-25"
              style="width: 100px; height: 100px; font-size: 2.5rem;">
              <?= strtoupper(substr($user->user_login, 0, 1)) ?>
            </div>
          <?php endif; ?>
        </div>
        <h6 class="mb-1 text-body fw-bold"><?= clear_html($user->user_display_name ?: $user->user_login) ?></h6>
        <p class="text-muted small mb-3"><?= clear_html($user->user_email) ?></p>
        <hr>
        <div class="text-start small">
          <div class="d-flex justify-content-between mb-2">
            <span class="text-muted">Rol actual:</span>
            <span class="badge bg-info bg-opacity-10 text-info text-uppercase"><?= clear_html($user->role_name ?? 'Sin Rol') ?></span>
          </div>
          <div class="alert alert-warning p-2 mb-0 border-0 small">
            <i class="fa-solid fa-circle-info me-1"></i>
            Los permisos marcados como <span class="badge bg-secondary text-white">HEREDADO</span> no pueden ser editados desde aquí, ya que vienen definidos en el rol del usuario.
          </div>
        </div>
      </div>
    </div>
  </div>
</div>