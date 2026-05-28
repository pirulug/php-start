<?php start_block('title'); ?>
<?= $page_title ?>
<?php end_block(); ?>

<?php start_block('breadcrumb'); ?>
<?php render_breadcrumb([
  ['label' => 'Dashboard', 'link' => admin_route('dashboard')],
  ['label' => 'Usuarios', 'link' => admin_route('users')],
  ['label' => 'Gestionar API Keys']
]) ?>
<?php end_block(); ?>

<?php start_block('js'); ?>
<?= admin_modules_script("users", "api") ?>
<?php end_block(); ?>

<div class="row g-3">

  <!-- CONTENT -->
  <div class="col-12">

    <div class="card mb-3">
      <div class="card-header d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-3">

          <?= Button::cancel(admin_route("users", [], ["p" => ($_GET["p"] ?? 1)]))->text("Volver")->classes("btn btn-outline-secondary")->icon("fa fa-arrow-left")->render() ?>

          <h5 class="card-title mb-0"><?= __("Gestionar API Keys de") ?>
            <strong><?= clear_html($managed_user->user_login) ?></strong>
          </h5>
        </div>
        <?php if (empty($api_keys)): ?>
          <button type="button" class="btn btn-primary btn-sm text-uppercase small fw-bold btn-generate-key">
            <i class="fa-solid fa-plus me-1"></i> <?= __("Generar Nueva Llave") ?>
          </button>
        <?php endif; ?>
      </div>

      <div class="card-body">
        <p class="text-muted small">
          <?= __("Desde aquí puedes gestionar las llaves de acceso del usuario. Las API Keys permiten al usuario autenticarse en la API del sistema.") ?>
        </p>

        <?php if (empty($api_keys)): ?>
          <div class="alert border py-3 text-center">
            <i class="fa-solid fa-key fa-2x mb-2 opacity-50"></i>
            <p class="mb-0 small"><?= __("Este usuario no tiene ninguna API Key generada aún.") ?></p>
          </div>
        <?php else: ?>
          <div class="table-responsive">
            <table class="table table-hover align-middle">
              <thead>
                <tr>
                  <th class="small py-3"><?= __("API Key") ?></th>
                  <th class="small py-3"><?= __("Creada") ?></th>
                  <th class="small py-3 text-end"><?= __("Acciones") ?></th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($api_keys as $key): ?>
                  <tr>
                    <td>
                      <div class="input-group">
                        <input type="text" id="api_key_<?= $key->api_key_id ?>" class="form-control font-monospace"
                          value="<?= $key->api_key ?>" readonly>
                        <button class="btn btn-outline-secondary btn-copy" type="button" data-key="<?= $key->api_key ?>"
                          title="Copiar">
                          <i class="fa-solid fa-copy"></i>
                        </button>
                      </div>
                    </td>
                    <td>
                      <span class="text-muted"><?= format_datetime($key->api_key_created) ?></span>
                    </td>
                    <td class="text-end">
                      <button type="button" class="btn btn-sm btn-outline-primary btn-regenerate-key"
                        data-id="<?= $key->api_key_id ?>" title="Regenerar">
                        <i class="fa-solid fa-arrows-rotate"></i>
                      </button>
                      <button type="button" class="btn btn-sm btn-outline-danger btn-delete-key"
                        data-id="<?= $key->api_key_id ?>" title="Eliminar">
                        <i class="fa-solid fa-trash"></i>
                      </button>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>