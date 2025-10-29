<div class="container my-3">
  <div class="row">
    <div class="col-md-3">
      <div class="card">
        <div class="card-body">
          <div class="text-center">
            <img class="img-fluid rounded mb-1" src="<?= SITE_URL ?>/uploads/user/<?= $user->user_image ?>"
              alt="Profile Image">
          </div>

          <h3 class="text-center"><?= $user->user_display_name ?></h3>

          <p class="text-muted text-center"><?= $user->role_name ?></p>

          <ul class="list-group list-group-flush mb-1">
            <li class="list-group-item">
              <b>Username</b>
              <a class="pull-right"><?= $user->user_name ?></a>
            </li>
            <li class="list-group-item">
              <b>Last Login</b>
              <a class="pull-right"><?= $user->user_last_login ?></a>
            </li>
            <li class="list-group-item">
              <b>Member Since </b>
              <a class="pull-right"><?= $user->user_created ?></a>
            </li>
          </ul>

          <div class="d-grid">
            <a href="http://localhost:8050/profile/index/edit" class="btn btn-primary btn-block">
              <b>
                <i class="fa fa-pencil"></i>
                Edit
              </b>
            </a>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-9">
      <div class="card">
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered table-striped">
              <tbody>
                <tr>
                  <td width="160"><strong>Name</strong>:</td>
                  <td><?= $user->user_display_name ?></td>
                </tr>
                <tr>
                  <td><strong>Username</strong>:</td>
                  <td><?= $user->user_name ?></td>
                </tr>
                <tr>
                  <td><strong>Email</strong>:</td>
                  <td><?= $user->user_email ?></td>
                </tr>
                <tr>
                  <td><strong>Role</strong>:</td>
                  <td><?= $user->role_name ?></td>
                </tr>
                <tr>
                  <td><strong>Last Login</strong>:</td>
                  <td><?= $user->user_last_login ?></td>
                </tr>
                <tr>
                  <td><strong>Member Since</strong>:</td>
                  <td><?= $user->user_created ?></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>