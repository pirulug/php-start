<?php block_start("title") ?>
Visor de Log - <?= htmlspecialchars(basename($file_param)) ?>
<?php block_end() ?>

<?php block_start("breadcrumb"); ?>
<?php render_breadcrumb([
  ["label" => "Dashboard", "link" => admin_route("dashboard")],
  ["label" => "Logs del Sistema", "link" => admin_route("logs")],
  ["label" => "Visor"]
]) ?>
<?php block_end(); ?>

<?php start_block("css") ?>
<style>
  .log-badge-INFO { background-color: #0dcaf0; color: #000; }
  .log-badge-WARNING { background-color: #ffc107; color: #000; }
  .log-badge-ERROR { background-color: #dc3545; color: #fff; }
  .log-badge-DEBUG { background-color: #6c757d; color: #fff; }
  .log-badge-RAW { background-color: #212529; color: #fff; }
</style>
<?php block_end() ?>

<div class="bg-body p-3 rounded mb-3 d-flex align-items-center justify-content-between">
  <div>
    <h5 class="m-0 fw-bold text-uppercase"><i class="fa-solid fa-file-lines me-2 text-primary"></i><?= htmlspecialchars(basename($file_param)) ?></h5>
    <span class="text-muted small"><?= htmlspecialchars($file_param) ?></span>
  </div>
  <a href="<?= admin_route("logs") ?>" class="btn btn-outline-secondary text-uppercase fw-bold">
    <i class="fa-solid fa-arrow-left me-1"></i> Volver al listado
  </a>
</div>

<div class="bg-body p-3 rounded mb-3">
  <?php if (empty($parsed_entries)): ?>
    <div class="p-4 text-center text-muted">
      <i class="fa-solid fa-info-circle d-block fs-3 mb-2"></i>
      El archivo de log está vacío.
    </div>
  <?php else: ?>
    <div class="table-responsive">
      <table class="table table-hover align-middle table-sm m-0" style="font-size: 12px;">
        <thead>
          <tr>
            <th class="ps-3 py-2" style="width: 15%;">Fecha / Hora</th>
            <th class="py-2" style="width: 8%;">Nivel</th>
            <th class="py-2" style="width: 10%;">IP</th>
            <th class="py-2" style="width: 15%;">Ruta</th>
            <th class="py-2" style="width: 35%;">Mensaje</th>
            <th class="text-end pe-3 py-2" style="width: 17%;">Contexto</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($parsed_entries as $entry): ?>
            <tr>
              <td class="ps-3 py-2 font-monospace text-muted"><?= htmlspecialchars($entry['datetime']) ?></td>
              <td>
                <span class="badge log-badge-<?= htmlspecialchars($entry['level']) ?> text-uppercase small fw-bold">
                  <?= htmlspecialchars($entry['level']) ?>
                </span>
              </td>
              <td class="font-monospace"><?= htmlspecialchars($entry['ip']) ?></td>
              <td class="font-monospace text-truncate" style="max-width: 150px;" title="<?= htmlspecialchars($entry['route']) ?>">
                <?= htmlspecialchars($entry['route']) ?>
              </td>
              <td class="fw-medium"><?= htmlspecialchars($entry['message']) ?></td>
              <td class="text-end pe-3">
                <?php if ($entry['context']): ?>
                  <button class="btn btn-outline-secondary btn-xs py-0 px-2 font-monospace" style="font-size: 10px;" type="button" 
                          onclick="Swal.fire({title: 'Datos de Contexto', html: '<pre class=\'text-start bg-body-secondary p-3 rounded font-monospace small\'>' + JSON.stringify(<?= htmlspecialchars($entry['context']) ?>, null, 2) + '</pre>', confirmButtonColor: '#ff0055'})">
                    <i class="fa-solid fa-braces me-1"></i> Contexto
                  </button>
                <?php else: ?>
                  <span class="text-muted">-</span>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</div>
