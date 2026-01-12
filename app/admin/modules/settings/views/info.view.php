<?php start_block("title") ?>
Información del sistema
<?php end_block() ?>

<div class="row g-3 mb-4">
  <div class="col-md-3">
    <div
      class="card h-100 border-0  border-start border-4 <?php echo $isPhpVersionOk ? 'border-success' : 'border-danger'; ?>">
      <div class="card-body">
        <div class="text-muted text-uppercase small fw-bold mb-1">Versión PHP</div>
        <div class="h4 mb-0"><?php echo $currentPhpVersion; ?></div>
        <div class="small mt-2">
          <?php echo getStatusBadge($isPhpVersionOk, 'Compatible', 'Actualizar req.'); ?>
          <span class="text-muted ms-1">Min: <?php echo $minPhpVersion; ?></span>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-3">
    <div
      class="card h-100 border-0  border-start border-4 <?php echo $isDbVersionOk ? 'border-primary' : 'border-warning'; ?>">
      <div class="card-body">
        <div class="text-muted text-uppercase small fw-bold mb-1">Base de Datos</div>
        <div class="h4 mb-0 text-truncate" title="<?php echo $dbType; ?>"><?php echo strtok($dbType, ' '); ?></div>
        <div class="small mt-2">v<?php echo $dbVersion; ?></div>
      </div>
    </div>
  </div>

  <div class="col-md-3">
    <div
      class="card h-100 border-0  border-start border-4 <?php echo $isMemoryLimitOk ? 'border-info' : 'border-danger'; ?>">
      <div class="card-body">
        <div class="text-muted text-uppercase small fw-bold mb-1">Memoria Límite</div>
        <div class="h4 mb-0"><?php echo $memoryLimit; ?> MB</div>
        <div class="small mt-2 text-muted">Mínimo: <?php echo $minMemoryLimit; ?> MB</div>
      </div>
    </div>
  </div>

  <div class="col-md-3">
    <div class="card h-100 border-0 ">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-end mb-1">
          <div class="text-muted text-uppercase small fw-bold">Almacenamiento</div>
          <div class="small fw-bold"><?php echo $diskPercent; ?>%</div>
        </div>
        <div class="progress mb-2" style="height: 6px;">
          <div class="progress-bar <?php echo $diskClass; ?>" role="progressbar"
            style="width: <?php echo $diskPercent; ?>%"></div>
        </div>
        <div class="small text-muted"><?php echo number_format($freeSpaceMb); ?> MB libres</div>
      </div>
    </div>
  </div>

  <div class="col-md-12">
    <?php if ($systemHealthy): ?>
      <div class="alert alert-success d-flex align-items-center py-2 px-3 m-0 " role="alert">
        <i class="fa-solid fa-circle-check fa-lg me-2"></i>
        <div><strong>Sistema Óptimo</strong><br><small>Todos los requisitos cumplidos</small></div>
      </div>
    <?php else: ?>
      <div class="alert alert-danger d-flex align-items-center py-2 px-3 m-0 " role="alert">
        <i class="fa-solid fa-triangle-exclamation fa-lg me-2"></i>
        <div><strong>Atención Requerida</strong><br><small>Revise los errores abajo</small></div>
      </div>
    <?php endif; ?>
  </div>
</div>

<div class="row g-4">
  <div class="col-lg-6">
    <div class="card   h-100">
      <div class="card-header   pt-4 pb-0">
        <h5 class="card-title text-primary"><i class="fa-brands fa-php me-2"></i>Configuración PHP</h5>
      </div>
      <div class="card-body">
        <ul class="list-group list-group-flush">
          <li class="list-group-item d-flex justify-content-between align-items-center px-0">
            <span>Tiempo Máx. Ejecución</span>
            <span>
              <span class="fw-bold"><?php echo $executionTime; ?>s</span>
              <small class="text-muted ms-1">(Min: <?php echo $maxExecutionTime; ?>s)</small>
              <span class="ms-2"><?php echo getStatusBadge($isExecutionTimeOk, '', ''); ?></span>
            </span>
          </li>
          <li class="list-group-item d-flex justify-content-between align-items-center px-0">
            <span>Max Upload Size</span>
            <span class="badge   border"><?php echo ini_get('upload_max_filesize'); ?></span>
          </li>
          <li class="list-group-item d-flex justify-content-between align-items-center px-0">
            <span>Zona Horaria</span>
            <span class="text-end"><?php echo date_default_timezone_get(); ?></span>
          </li>
          <li class="list-group-item px-0 mt-3">
            <div class="mb-2 fw-bold small text-uppercase text-muted">Extensiones Requeridas</div>
            <div class="d-flex flex-wrap gap-2">
              <?php foreach ($requiredExtensions as $ext): ?>
                <?php if (in_array($ext, $enabledExtensions)): ?>
                  <span class="badge bg-success"><i class="fa-solid fa-check me-1"></i><?php echo $ext; ?></span>
                <?php else: ?>
                  <span class="badge bg-danger"><i class="fa-solid fa-xmark me-1"></i><?php echo $ext; ?></span>
                <?php endif; ?>
              <?php endforeach; ?>
            </div>
            <?php if (!empty($missingExtensions)): ?>
              <div class="alert alert-warning mt-3 py-2 small">
                <i class="fa-solid fa-triangle-exclamation me-1"></i> Faltan:
                <?php echo implode(', ', $missingExtensions); ?>
              </div>
            <?php endif; ?>
          </li>
        </ul>
      </div>
    </div>
  </div>

  <div class="col-lg-6">
    <div class="card   mb-4">
      <div class="card-header   pt-4 pb-0">
        <h5 class="card-title "><i class="fa-solid fa-server me-2"></i>Detalles del Servidor</h5>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-borderless table-sm align-middle mb-0">
            <tbody>
              <tr>
                <td class="text-muted w-50">Software</td>
                <td class="fw-medium text-end text-break"><?php echo $_SERVER['SERVER_SOFTWARE']; ?></td>
              </tr>
              <tr>
                <td class="text-muted">Host / IP</td>
                <td class="fw-medium text-end"><?php echo gethostname(); ?> / <?php echo $_SERVER['SERVER_ADDR']; ?>
                </td>
              </tr>
              <tr>
                <td class="text-muted">Puerto</td>
                <td class="fw-medium text-end"><span
                    class="badge bg-secondary"><?php echo $_SERVER['SERVER_PORT']; ?></span></td>
              </tr>
              <tr>
                <td class="text-muted">OpenSSL</td>
                <td class="fw-medium text-end small"><?php echo str_replace('OpenSSL', '', OPENSSL_VERSION_TEXT); ?>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div class="card   ">
      <div class="card-body d-flex align-items-center">
        <div class=" p-3 rounded-circle  text-primary me-3">
          <i class="fa-brands fa-linux fa-xl"></i>
        </div>
        <div>
          <h6 class="mb-1">Sistema Operativo</h6>
          <div class="small text-muted">
            <?php echo php_uname('s'); ?> <?php echo php_uname('r'); ?> (<?php echo php_uname('m'); ?>)
          </div>
        </div>
      </div>
    </div>
  </div>
</div>