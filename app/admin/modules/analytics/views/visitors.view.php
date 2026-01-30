<?php start_block("title") ?>
Visitantes
<?php end_block() ?>

<div class="card">
  <div class="card-body">
    <table class="table table-bordered table-hover align-middle">
      <thead>
        <tr>
          <th>Última visualización</th>
          <th>Información para visitantes</th>
          <th>Remitente</th>
          <th>Página de entrada</th>
          <th>Página de salida</th>
          <th>Vistas totales</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($visitorsTable as $row): ?>
          <tr>
            <td class="wps-pd-l">
              <?= date('m/d, H:i', strtotime($row->visitor_last_visit)) ?>
            </td>

            <td class="wps-pd-l">
              <ul class="wps-visitor__information--container">

                <?php if ($row->visitor_country): ?>
                  <li class="wps-visitor__information">
                    <span title="<?= $row->visitor_country ?>">
                      <?= $row->visitor_country ?>
                    </span>
                  </li>
                <?php endif; ?>

                <?php if ($row->visitor_platform): ?>
                  <li class="wps-visitor__information">
                    <span title="<?= $row->visitor_platform ?>">
                      <?= $row->visitor_platform ?>
                    </span>
                  </li>
                <?php endif; ?>

                <?php if ($row->visitor_device): ?>
                  <li class="wps-visitor__information">
                    <span title="<?= $row->visitor_device ?>">
                      <?= $row->visitor_device ?>
                    </span>
                  </li>
                <?php endif; ?>

                <?php if ($row->visitor_browser): ?>
                  <li class="wps-visitor__information">
                    <span title="<?= $row->visitor_browser ?>">
                      <?= $row->visitor_browser ?>
                    </span>
                  </li>
                <?php endif; ?>

                <li class="wps-visitor__information">
                  <a href="visitor.php?id=<?= (int) $row->visitor_id ?>">
                    <span class="wps-visitor__information__incognito-img"></span>
                  </a>
                </li>

              </ul>
            </td>

            <td class="wps-pd-l">
              <div class="wps-referral-link">
                <span class="wps-referral-label">
                  <?= $row->visitor_referer ?: 'Tráfico directo' ?>
                </span>
              </div>
            </td>

            <td class="wps-pd-l">
              <?php if ($row->visitor_session_start_page): ?>
                <span class="wps-ellipsis-text">
                  <?= $row->visitor_session_start_page ?>
                </span>
              <?php endif; ?>
            </td>

            <td class="wps-pd-l">
              <?php if ($row->visitor_session_end_page): ?>
                <span class="wps-ellipsis-text">
                  <?= $row->visitor_session_end_page ?>
                </span>
              <?php endif; ?>
            </td>

            <td class="wps-pd-l text-center">
              <a href="visitor.php?id=<?= (int) $row->visitor_id ?>">
                <?= (int) $row->visitor_total_hits ?>
              </a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>