<div class="card border-primary mb-4">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="card-title mb-0">
      <strong>Respaldos de Bases de Datos</strong>: Por favor respalda antes de restaurar a cualquier versión anterior.
    </h5>
    <a href="?action=backup" class="btn btn-primary">
      <i class="fa fa-database me-1"></i> Respaldar Base de Datos
    </a>
  </div>

  <div class="card-body">
    <ul class="list-group">
      <?php if (empty($files)): ?>
        <li class="list-group-item text-muted">No hay respaldos disponibles.</li>
      <?php else: ?>
        <?php foreach ($files as $file):
          $filename  = basename($file);
          $timestamp = date("D d M Y h:i A", filemtime($file));
          ?>
          <li class="list-group-item d-flex justify-content-between align-items-center">
            <div>
              <strong>Respaldo tomado el <span class="text-primary"><?= $timestamp ?></span></strong>
            </div>
            <div class="btn-group">
              <a href="?action=download&file=<?= urlencode($filename) ?>" class="btn btn-primary btn-sm">
                <i class="fa fa-download me-1"></i> Descargar
              </a>
              <a href="?action=restore&file=<?= urlencode($filename) ?>" class="btn btn-warning btn-sm"
                onclick="return confirm('¿Seguro que deseas restaurar este respaldo?');">
                <i class="fa fa-database me-1"></i> Restaurar
              </a>
              <a href="?action=delete&file=<?= urlencode($filename) ?>" class="btn btn-danger btn-sm"
                onclick="return confirm('¿Eliminar este respaldo permanentemente?');">
                <i class="fa fa-trash me-1"></i> Borrar
              </a>
            </div>
          </li>
        <?php endforeach; ?>
      <?php endif; ?>
    </ul>
  </div>
</div>