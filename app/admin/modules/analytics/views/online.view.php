<?php start_block("title") ?>
Visitantes en linea
<?php end_block() ?>

<div class="card">
  <div class="card-body">
    <table class="table">
      <thead>
        <tr>
          <th scope="col" class="wps-pd-l">
            <span class="wps-order">Última visualización</span>
          </th>
          <th scope="col" class="wps-pd-l">
            Información para visitantes
          </th>
          <th scope="col" class="wps-pd-l">
            Remitente
          </th>
          <th scope="col" class="wps-pd-l">
            Página actual
          </th>
        </tr>
      </thead>

      <tbody>
        <?php foreach ($onlineVisitors as $row): ?>
          <tr>
            <td class="wps-pd-l">
              <?= date('m/d, H:i', strtotime($row->visitor_useronline_last_activity)) ?>
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
                <?php if ($row->visitor_useronline_referer): ?>
                  <a class="wps-link-arrow wps-link-arrow--external" target="_blank"
                    href="<?= $row->visitor_useronline_referer ?>">
                    <span><?= parse_url($row->visitor_useronline_referer, PHP_URL_HOST) ?></span>
                  </a>
                <?php else: ?>
                  <span class="wps-referral-label">Tráfico directo</span>
                <?php endif; ?>
              </div>
            </td>

            <td class="wps-pd-l">
              <?php if ($row->visitor_page_title): ?>
                <span class="wps-ellipsis-text">
                  <?= $row->visitor_page_title ?>
                </span>
              <?php else: ?>
                <span class="wps-ellipsis-text">—</span>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>