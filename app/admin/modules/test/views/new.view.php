<?php start_block('title') ?>
  New Test
<?php end_block() ?>

<?php start_block('breadcrumb'); ?>
<?php render_breadcrumb([
  ['label' => 'Dashboard', 'link' => admin_route('dashboard')],
  ['label' => 'Test', 'link' => admin_route('test')],
  ['label' => 'New']
]) ?>
<?php end_block(); ?>

<?php start_block('css') ?>
<link rel="stylesheet" href="">
<style>/* STYLE */</style>
<?php end_block() ?>

<?php start_block('js') ?>
<script src=""></script>
<script>/* SCRIPT */</script>
<?php end_block() ?>

  <div class="card bg-body">
    <div class="card-body">
      <h1>New Test</h1>
      <p>Bienvenido a la sección new del módulo test.</p>
    </div>
  </div>
