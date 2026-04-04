<?php start_block("title"); ?>
Usuarios (DataTable)
<?php end_block(); ?>

<?php start_block('breadcrumb'); ?>
<?php render_breadcrumb([
  ['label' => 'Dashboard', 'link' => admin_route('dashboard')],
  ['label' => 'Componentes'],
  ['label' => 'DataTable']
]) ?>
<?php end_block(); ?>

<?php start_block("css"); ?>
<!-- Cargando DataTables desde el plugin local -->
<link href="<?= APP_URL ?>/static/plugins/datatables/datatables.min.css" rel="stylesheet" />
<style>
  div.dataTables_wrapper div.dataTables_processing {
    background-color: var(--bs-body-bg);
    border: 1px solid var(--bs-border-color);
    box-shadow: none;
    color: var(--bs-body-color);
  }
</style>
<?php end_block(); ?>

<div class="card">
  <div class="card-header py-3">
    <h5 class="card-title mb-0"><i class="fa-solid fa-table me-1 text-primary"></i> Listado de Usuarios (AJAX)</h5>
  </div>
  <div class="card-body pt-0">
    <div class="table-responsive">
      <table id="usersTable" class="table table-hover align-middle w-100">
        <thead class="text-secondary small text-uppercase">
          <tr>
            <th>ID</th>
            <th>Usuario</th>
            <th>Nickname</th>
            <th>Email</th>
            <th>Rol</th>
            <th>Estado</th>
            <th>Imagen</th>
            <th>Creado</th>
            <th>Último login</th>
            <th>Acciones</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>
</div>

<?php start_block("js"); ?>
<!-- jQuery CDN (Mantenido por instrucción del usuario) -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- DataTables Plugin Local -->
<script src="<?= APP_URL ?>/static/plugins/datatables/datatables.min.js"></script>

<script>
  $(document).ready(function() {
    new DataTable("#usersTable", {
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
      // Se define la traducción directamente para evitar errores de carga de archivos i18n externos
      language: {
        "sProcessing":     "Procesando...",
        "sLengthMenu":     "Mostrar _MENU_ registros",
        "sZeroRecords":    "No se encontraron resultados",
        "sEmptyTable":     "Ningún dato disponible en esta tabla",
        "sInfo":           "Mostrando _START_ a _END_ de _TOTAL_ entradas",
        "sInfoEmpty":      "Mostrando 0 a 0 de 0 entradas",
        "sInfoFiltered":   "(filtrado de _MAX_ entradas totales)",
        "sInfoPostFix":    "",
        "sSearch":         "Buscar:",
        "sUrl":            "",
        "sInfoThousands":  ",",
        "sLoadingRecords": "Cargando...",
        "oPaginate": {
            "sFirst":    '<i class="fa-solid fa-angles-left"></i>',
            "sLast":     '<i class="fa-solid fa-angles-right"></i>',
            "sNext":     '<i class="fa-solid fa-angle-right"></i>',
            "sPrevious": '<i class="fa-solid fa-angle-left"></i>'
        },
        "oAria": {
            "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
            "sSortDescending": ": Activar para ordenar la columna de manera descendente"
        }
      },
      pageLength: 10,
      order: [[0, 'desc']]
    });
  });
</script>
<?php end_block(); ?>