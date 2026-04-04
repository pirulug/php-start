<?php start_block('title'); ?>
Redes Sociales
<?php end_block(); ?>

<?php start_block('breadcrumb'); ?>
<?php render_breadcrumb([
  ['label' => 'Dashboard', 'link' => admin_route('dashboard')],
  ['label' => 'Ajustes', 'link' => admin_route('settings/general')],
  ['label' => 'Redes Sociales']
]) ?>
<?php end_block(); ?>

<div class="card">
  <div class="card-body">
    <p class="text-muted">Configuración de redes sociales (En desarrollo).</p>
  </div>
</div>
