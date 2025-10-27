<link rel="stylesheet" href="<?= SITE_URL . "/static/plugins/datatables/datatables.css" ?>">
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="<?= SITE_URL . "/static/plugins/datatables/datatables.js" ?>"></script>


<div class="card">
  <div class="card-body">

    <table id="tablaUsuarios" class="table table-striped table-bordered" style="width:100%">
      <thead>
        <tr>
          <th>user_id</th>
          <th>user_name</th>
          <th>user_email</th>
          <th>user_first_name</th>
          <th>user_last_name</th>
          <th>user_status</th>
          <th>role_id</th>
          <th>user_created</th>
          <th>user_last_login</th>
        </tr>
      </thead>
    </table>

    <!-- <table id="tablaUsuarios" class="table table-striped" width="100%">
      <thead>
        <tr>
          <th>ID</th>
          <th>Usuario</th>
          <th>Email</th>
          <th>Nombre</th>
          <th>Apellido</th>
          <th>Rol</th>
          <th>Estado</th>
          <th>Creado</th>
        </tr>
      </thead>
    </table> -->
  </div>
</div>


<script>
  document.addEventListener("DOMContentLoaded", function () {
    let table = new DataTable('#tablaUsuarios', {
      processing: true,
      serverSide: true,
      responsive: true,
      ajax: 'http://php-start.test/ajax/users',
      pageLength: 50,
      order: [[0, 'asc']],
      columns: [
        { data: 'user_id' },
        { data: 'user_name' },
        { data: 'user_email' },
        { data: 'user_first_name' },
        { data: 'user_last_name' },
        { data: 'user_status' },
        { data: 'role_id' },
        { data: 'user_created' },
        { data: 'user_last_login' }
      ]
    });
  });
  // $('#tablaUsuarios').DataTable({
  //   ajax: 'http://php-start.test/ajax/users',
  //   serverSide: true,
  //   processing: true,
  //   columns: [
  //     { data: 'id' },
  //     { data: 'username' },
  //     { data: 'email' },
  //     { data: 'first_name' },
  //     { data: 'last_name' },
  //     { data: 'role' },
  //     { data: 'status' },
  //     { data: 'created' }
  //   ],
  //   columnDefs: [
  //     { targets: [1, 6], orderable: false }
  //   ]
  // });

</script>