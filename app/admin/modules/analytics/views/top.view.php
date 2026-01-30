<?php start_block("title") ?>
Top de visitantes
<?php end_block() ?>

<div class="card">
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-hover table-bordered align-middle mb-0">
        <thead>
          <tr>
            <th class="text-center">Vistas totales</th>
            <th>Información para visitantes</th>
            <th>Remitente</th>
            <th>Página de entrada</th>
            <th>Página de salida</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($topVisitors as $row): ?>
            <tr>
              <td class="text-center fw-semibold">
                <?= (int) $row->visitor_total_hits ?>
              </td>

              <td>
                <div class="d-flex flex-wrap gap-2">
                  <?php if ($row->visitor_country): ?>
                    <span class="badge text-bg-secondary"><?= $row->visitor_country ?></span>
                  <?php endif; ?>

                  <?php if ($row->visitor_platform): ?>
                    <span class="badge text-bg-info"><?= $row->visitor_platform ?></span>
                  <?php endif; ?>

                  <?php if ($row->visitor_device): ?>
                    <span class="badge text-bg-warning"><?= $row->visitor_device ?></span>
                  <?php endif; ?>

                  <?php if ($row->visitor_browser): ?>
                    <span class="badge text-bg-primary"><?= $row->visitor_browser ?></span>
                  <?php endif; ?>

                  <a href="visitor.php?id=<?= (int) $row->visitor_id ?>" class="badge text-bg-dark text-decoration-none">
                    Ver
                  </a>
                </div>
              </td>

              <td>
                <?php if ($row->visitor_referer): ?>
                  <a href="<?= $row->visitor_referer ?>" target="_blank" class="text-decoration-none">
                    <?= parse_url($row->visitor_referer, PHP_URL_HOST) ?>
                  </a>
                <?php else: ?>
                  <span class="text-muted">Tráfico directo</span>
                <?php endif; ?>
              </td>

              <td>
                <?php if ($row->visitor_session_start_page): ?>
                  <span class="text-truncate d-inline-block" style="max-width: 260px;">
                    <?= $row->visitor_session_start_page ?>
                  </span>
                <?php else: ?>
                  <span class="text-muted">—</span>
                <?php endif; ?>
              </td>

              <td>
                <?php if ($row->visitor_session_end_page): ?>
                  <span class="text-truncate d-inline-block" style="max-width: 260px;">
                    <?= $row->visitor_session_end_page ?>
                  </span>
                <?php else: ?>
                  <span class="text-muted">—</span>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>