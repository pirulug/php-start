<?php start_block('title'); ?>
Respaldos de Seguridad
<?php end_block(); ?>

<?php start_block('breadcrumb'); ?>
<?php render_breadcrumb([
  ['label' => 'Dashboard', 'link' => admin_route('dashboard')],
  ['label' => 'Ajustes', 'link' => admin_route('settings/general')],
  ['label' => 'Resguardos']
]) ?>
<?php end_block(); ?>

<?php start_block("css") ?>
<style>
  .backup-icon {
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #fcd;
    color: #f05;
    border-radius: 12px;
    font-size: 1.25rem;
  }

  [data-bs-theme="dark"] .backup-icon {
    background-color: #301;
    color: #f69;
  }
</style>
<?php end_block() ?>


<div class="card mb-3">
  <div class="card-header d-flex justify-content-between align-items-center">
    <div>
      <h6 class="card-title mb-0 fw-bold"><i class="fa-solid fa-database me-2"></i>Historial de Backups</h6>
      <small class="text-muted">Gestiona las copias de seguridad de tu base de datos.</small>
    </div>
  </div>

  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead>
          <tr>
            <th class="ps-4" style="width: 50px;">#</th>
            <th>Archivo / Fecha</th>
            <th>Detalles</th>
            <th class="text-end pe-4">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($files)): ?>
            <tr>
              <td colspan="4" class="text-center py-5">
                <div class="opacity-25 mb-3">
                  <i class="fa-solid fa-box-open fa-3x"></i>
                </div>
                <div class="text-muted">No se han encontrado respaldos generados todavia.</div>
              </td>
            </tr>
          <?php else: ?>
            <?php foreach ($files as $index => $file):
              $filename = basename($file);
              $mtime    = filemtime($file);
              $size     = number_format(filesize($file) / 1024 / 1024, 2) . ' MB';

              // Ciframos el nombre del archivo para las acciones
              $encName = $cipher->encrypt($filename);
              ?>
              <tr>
                <td class="ps-4">
                  <div class="backup-icon">
                    <i class="fa-solid fa-file-zipper"></i>
                  </div>
                </td>
                <td>
                  <div class="fw-bold fs-6">Respaldo SQL: <?= format_date($mtime) ?></div>
                  <div class="text-muted small"><?= $filename ?></div>
                </td>
                <td>
                  <div class="small"><i class="fa-regular fa-clock me-1 text-primary"></i> <?= format_time($mtime) ?></div>
                  <div class="small"><i class="fa-solid fa-weight-hanging me-1 text-primary"></i> <?= $size ?></div>
                </td>
                <td class="text-end pe-4">
                  <div class="d-flex justify-content-end gap-2">

                    <!-- Botón Descargar -->
                    <?= ActionBtn::link(admin_route('settings/backup', [], ['action' => 'download', 'file' => $encName]))
                      ->icon('fa-solid fa-download')
                      ->text('')
                      ->classes('btn btn-sm btn-outline-success')
                      ->attrs('title="Descargar respaldo"') ?>

                    <!-- Botón Restaurar -->
                    <?= ActionBtn::link(admin_route('settings/backup', [], ['action' => 'restore', 'file' => $encName]))
                      ->icon('fa-solid fa-rotate-left')
                      ->text('')
                      ->classes('btn btn-sm btn-outline-warning')
                      ->attrs('title="Restaurar base de datos" onclick="return confirm(\'ADVERTENCIA: Se eliminaran todas las tablas actuales. ¿Continuar?\')"') ?>

                    <!-- Botón Eliminar -->
                    <?= ActionBtn::delete(admin_route('settings/backup', [], ['action' => 'delete', 'file' => $encName]))
                      ->saTitle('¿Eliminar respaldo?')
                      ->saText('Este archivo se borrara permanentemente del servidor.')
                      ->text('') ?>

                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<div class="alert alert-warning bg-warning-subtle d-flex align-items-center mb-0">
  <i class="fa-solid fa-triangle-exclamation fs-3 me-3"></i>
  <div>
    <h6 class="alert-heading fw-bold mb-1">Nota de Seguridad</h6>
    <p class="mb-0 small">Los archivos SQL contienen la estructura y los datos completos de tu sitio. Manten tus
      respaldos en un lugar seguro y elimina los antiguos periodicamente para liberar espacio en el servidor.</p>
  </div>
</div>

<div class="bg-body p-3 mt-3 rounded d-flex justify-content-end gap-2 sticky-bottom">
  <a href="<?= admin_route('settings/backup', [], ['action' => 'backup']) ?>"
    class="btn btn-primary px-5 fw-bold text-uppercase small">
    <i class="fa-solid fa-plus-circle me-1"></i> Generar Respaldo
  </a>
</div>