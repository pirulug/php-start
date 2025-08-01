<?php require BASE_DIR_ADMIN . "/views/_partials/top.partial.php"; ?>
<?php require BASE_DIR_ADMIN . "/views/_partials/navbar.partial.php"; ?>

<div class="card">
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-hover align-middle">
        <thead>
          <tr>
            <th>Titulo</th>
            <th>Status</th>
            <th>Acción</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($ads as $ad): ?>
            <tr>
              <td>
                <?= $ad->ad_title ?>
              </td>
              <td>
                <?php if ($ad->ad_status == "1"): ?>
                  <span class="badge bg-success">Publish</span>
                <?php else: ?>
                  <span class="badge bg-warning">Draft</span>
                <?php endif; ?>
              </td>
              <td>
                <a href="<?= SITE_URL ?>/admin/controllers/settings/ads_edit.php?id=<?= $ad->ad_id ?>" class="btn btn-success">
                  <i class="fa fa-edit"></i>
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php require BASE_DIR_ADMIN . "/views/_partials/footer.partial.php"; ?>
<?php require BASE_DIR_ADMIN . "/views/_partials/bottom.partial.php"; ?>