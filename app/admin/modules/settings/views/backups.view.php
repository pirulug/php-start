<?php start_block("title"); ?>
Respaldos de Base de Datos
<?php end_block(); ?>

<div class="card">
  <div class="card-header   d-flex flex-wrap justify-content-between align-items-center">
    <div>
    </div>
    <a href="?action=backup" class="btn btn-primary ">
      <i class="fa fa-plus me-1"></i> Nuevo Respaldo
    </a>
  </div>

  <div class="card-body">
    <ul class="list-group list-group-flush">
      <?php if (empty($files)): ?>
        <li class="list-group-item text-center py-5">
          <div class="text-body-secondary mb-2">
            <i class="fa-regular fa-folder-open fa-3x opacity-50"></i>
          </div>
          <p class="text-body-secondary mb-0">No hay respaldos disponibles.</p>
        </li>
      <?php else: ?>
        <?php foreach ($files as $file):
          $filename = basename($file);
          // Formato de fecha más legible
          $timestamp = date("d/m/Y", filemtime($file));
          $time      = date("h:i A", filemtime($file));
          // Calculamos tamaño (opcional, mejora UX)
          $filesize = round(filesize($file) / 1024, 2) . ' KB';
          ?>
          <li
            class="list-group-item d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">

            <div class="d-flex align-items-center gap-3">
              <div class="d-flex align-items-center justify-content-center bg-primary rounded p-2"
                style="width: 45px; height: 45px;">
                <i class="fa-solid fa-file-zipper text-white"></i>
              </div>
              <div>
                <div class="fw-bold text-body-emphasis">
                  Respaldo del <?= $timestamp ?>
                </div>
                <div class="small text-body-secondary">
                  <i class="fa-regular fa-clock me-1"></i><?= $time ?>
                  <span class="mx-1">&bull;</span>
                  <?= $filesize ?>
                </div>
              </div>
            </div>

            <div class="btn-group" role="group" aria-label="Acciones de respaldo">
              <a href="?action=download&file=<?= urlencode($filename) ?>" class="btn btn-outline-success" title="Descargar">
                <i class="fa fa-download"></i> <span class="d-none d-lg-inline">Descargar</span>
              </a>

              <a href="?action=restore&file=<?= urlencode($filename) ?>" class="btn btn-outline-warning"
                onclick="return confirm('ATENCIÓN: Esto sobrescribirá la base de datos actual. ¿Estás seguro?');"
                title="Restaurar">
                <i class="fa fa-rotate-left"></i> <span class="d-none d-lg-inline">Restaurar</span>
              </a>

              <a href="?action=delete&file=<?= urlencode($filename) ?>" class="btn btn-outline-danger"
                onclick="return confirm('¿Eliminar este respaldo permanentemente? No se puede deshacer.');"
                title="Eliminar">
                <i class="fa fa-trash"></i>
              </a>
            </div>

          </li>
        <?php endforeach; ?>
      <?php endif; ?>
    </ul>
  </div>
</div>