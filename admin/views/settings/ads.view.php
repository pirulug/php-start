<?php require BASE_DIR . "/admin/views/partials/top.partial.php"; ?>
<?php require BASE_DIR . "/admin/views/partials/navbar.partial.php"; ?>



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
                <?= $ad->title ?>
              </td>
              <td>
                <?php if ($ad->status == "1"): ?>
                  <span class="badge bg-success">Publish</span>
                <?php else: ?>
                  <span class="badge bg-warning">Draft</span>
                <?php endif; ?>
              </td>
              <td>
                <a href="<?= SITE_URL ?>/admin/controllers/settings/ads_edit.php?id=<?= $ad->id ?>" class="btn btn-success">
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

<?php require BASE_DIR . "/admin/views/partials/footer.partial.php"; ?>
<?php require BASE_DIR . "/admin/views/partials/bottom.partial.php"; ?>