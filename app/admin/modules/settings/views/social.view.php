<?php start_block('title'); ?>
Redes Sociales
<?php end_block(); ?>

<?php start_block('breadcrumb'); ?>
<?php render_breadcrumb([
  ['label' => 'Dashboard', 'link' => admin_route('dashboard')],
  ['label' => 'Settings'],
  ['label' => 'Social']
]) ?>
<?php end_block(); ?>

<?php
$social      = $config->social();
$socialIcons = [
  'facebook'  => 'fa-brands fa-facebook',
  'twitter'   => 'fa-brands fa-x-twitter',
  'instagram' => 'fa-brands fa-instagram',
  'youtube'   => 'fa-brands fa-youtube',
  'linkedin'  => 'fa-brands fa-linkedin',
  'tiktok'    => 'fa-brands fa-tiktok',
  'github'    => 'fa-brands fa-github',
  'whatsapp'  => 'fa-brands fa-whatsapp',
  'telegram'  => 'fa-brands fa-telegram',
  'threads'   => 'fa-brands fa-threads',
  'discord'   => 'fa-brands fa-discord',
  'twitch'    => 'fa-brands fa-twitch',
];

function get_external_favicon($url) {
  if (empty($url))
    return null;
  $domain = parse_url($url, PHP_URL_HOST);
  if (!$domain)
    return null;
  return "https://www.google.com/s2/favicons?domain={$domain}&sz=64";
}
?>
<?php start_block('js') ?>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<?= url_script_admin('settings', 'social') ?>
<?php end_block(); ?>

<form action="" method="POST">
  <div class="card mb-3">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="card-title mb-0 text-uppercase small fw-bold">Listado de Redes Sociales</h5>
      <button type="button" class="btn btn-outline-primary btn-sm text-uppercase small fw-bold" id="add-social">
        <i class="fa-solid fa-plus me-1"></i> Agregar Red
      </button>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" id="social-table">
          <thead>
            <tr>
              <th style="width: 40px;"></th>
              <th style="width: 60px;" class="text-center">Vista</th>
              <th style="width: 200px;">Nombre (ID)</th>
              <th style="width: 250px;">Icono FontAwesome (Opcional)</th>
              <th>Enlace / URL</th>
              <th style="width: 50px;"></th>
            </tr>
          </thead>
          <tbody id="social-container" data-icons='<?= json_encode($socialIcons) ?>'>
            <?php if (empty($social)): ?>
              <tr class="empty-row">
                <td colspan="6" class="text-center py-4 text-secondary small">No hay redes sociales
                  configuradas.</td>
              </tr>
            <?php else: ?>
              <?php foreach ($social as $item): ?>
                <tr>
                  <td class="text-center text-muted handle" style="cursor: grab;">
                    <i class="fa-solid fa-grip-vertical"></i>
                  </td>
                  <td class="text-center preview-cell">
                    <?php
                    $iconClass  = $item->icon ?: ($socialIcons[strtolower($item->name)] ?? null);
                    $extFavicon = get_external_favicon($item->url);
                    ?>
                    <?php if ($iconClass): ?>
                      <i class="<?= $iconClass ?> fs-4 text-secondary"></i>
                    <?php elseif ($extFavicon): ?>
                      <img src="<?= $extFavicon ?>"
                        style="width: 24px; height: 24px; object-fit: contain; border-radius: 4px;" alt="favicon">
                    <?php else: ?>
                      <i class="fa-solid fa-link fs-4 text-secondary"></i>
                    <?php endif; ?>
                  </td>
                  <td>
                    <input type="text" name="social_names[]" class="form-control social-name" value="<?= $item->name ?>"
                      placeholder="Ej: facebook">
                  </td>
                  <td>
                    <input type="text" name="social_icons[]" class="form-control social-icon-input"
                      value="<?= $item->icon ?? '' ?>" placeholder="Ej: fa-brands fa-x-twitter">
                  </td>
                  <td>
                    <input type="text" name="social_urls[]" class="form-control social-url-input" value="<?= $item->url ?>"
                      placeholder="https://...">
                  </td>
                  <td class="text-end">
                    <button type="button" class="btn btn-link text-danger p-0 remove-social">
                      <i class="fa-solid fa-trash-can"></i>
                    </button>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="bg-body p-3 rounded d-flex justify-content-end gap-2 sticky-bottom">
    <a href="<?= admin_route('settings/general') ?>"
      class="btn btn-outline-secondary px-4 text-uppercase small fw-bold">
      <i class="fa-solid fa-arrow-left me-2"></i>
      Cancelar
    </a>
    <button type="submit" class="btn btn-primary px-5 text-uppercase small fw-bold">
      <i class="fa-solid fa-floppy-disk me-2"></i>
      Guardar Cambios
    </button>
  </div>
</form>