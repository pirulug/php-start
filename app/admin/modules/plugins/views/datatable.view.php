<?php start_block("title"); ?>
Usuarios
<?php end_block(); ?>

<?php start_block("css"); ?>
<link href="/static/assets/css/datatables.css" rel="stylesheet" crossorigin="anonymous" />
<?php end_block(); ?>

<div class="card">
  <div class="card-body">

    <table id="usersTable" class="table table-striped w-100">
      <thead>
        <tr>
          <th>ID</th>
          <th>Usuario</th>
          <th>Nickname</th>
          <th>Email</th>
          <th>Rol</th>
          <th>Estado</th>
          <th>Avatar</th>
          <th>Creado</th>
          <th>Último login</th>
          <th>Acciones</th>
        </tr>
      </thead>
    </table>

  </div>
</div>

<?php start_block("js"); ?>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script src="/static/assets/js/datatables.js" crossorigin="anonymous">
</script>

<script>
  // const SITE_URL = document.querySelector('meta[name="site-url"]').content;

  const table = new DataTable("#usersTable", {
    processing: true,
    serverSide: true,
    ajax: {
      url: '/ajax/datatable',
      type: 'POST'
    },
    columns: [
      { data: 'user_id' },
      { data: 'user_login' },
      { data: 'user_nickname' },
      { data: 'user_email' },
      { data: 'role_name' },
      { data: 'user_status', orderable: false, searchable: false },
      { data: 'user_image', orderable: false, searchable: false },
      { data: 'user_created' },
      { data: 'user_last_login', orderable: false },
      { data: 'actions', orderable: false, searchable: false }
    ],
    language: {
      paginate: {
        first: '<i class="fa fa-angles-left"></i>',
        previous: '<i class="fa fa-angle-left"></i>',
        next: '<i class="fa fa-angle-right"></i>',
        last: '<i class="fa fa-angles-right"></i>'
      },
      url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
    },
    pageLength: 10,
    order: [[0, 'desc']]
  });
</script>
<?php end_block(); ?>