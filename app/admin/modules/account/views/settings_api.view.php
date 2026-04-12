<?php start_block('title'); ?>
API Keys
<?php end_block(); ?>

<?php start_block('breadcrumb'); ?>
<?php render_breadcrumb([
  ['label' => 'Dashboard', 'link' => admin_route('dashboard')],
  ['label' => 'Cuenta', 'link' => admin_route('account/profile')],
  ['label' => 'API Keys']
]) ?>
<?php end_block(); ?>

<div class="row g-3">

  <!-- SIDEBAR DE NAVEGACIÓN -->
  <div class="col-md-4 col-lg-3">
    <div class="card sticky-top" style="top: 1rem; z-index: 1;">
      <div class="card-header bg-transparent border-bottom p-3">
        <h6 class="m-0 fw-bold text-uppercase small text-body-secondary">Gestión de Cuenta</h6>
      </div>
      <div class="list-group list-group-flush">
        <a href="<?= admin_route("account/profile") ?>" 
           class="list-group-item list-group-item-action d-flex align-items-center gap-2 py-3">
          <i class="fa-solid fa-id-card fa-fw"></i>
          <span class="fs-7">Vista General</span>
        </a>
        <a href="<?= admin_route("account/settings/profile") ?>" 
           class="list-group-item list-group-item-action d-flex align-items-center gap-2 py-3">
          <i class="fa-solid fa-user-circle fa-fw"></i>
          <span class="fs-7">Información del Perfil</span>
        </a>
        <a href="<?= admin_route("account/settings/password") ?>" 
           class="list-group-item list-group-item-action d-flex align-items-center gap-2 py-3">
          <i class="fa-solid fa-shield-halved fa-fw"></i>
          <span class="fs-7">Seguridad y Contraseña</span>
        </a>
        <a href="<?= admin_route("account/settings/api") ?>" 
           class="list-group-item list-group-item-action d-flex align-items-center gap-2 py-3 <?= strpos($_SERVER['REQUEST_URI'], 'api') !== false ? 'active' : '' ?>">
          <i class="fa-solid fa-key fa-fw text-primary"></i>
          <span class="fw-bold fs-7">API Keys</span>
        </a>
      </div>
    </div>
  </div>

  <!-- CONTENIDO PRINCIPAL -->
  <div class="col-md-8 col-lg-9">
    <div class="card mb-3">
      <div class="card-header bg-transparent border-bottom py-3 d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0 fs-6 fw-bold text-uppercase">
          <i class="fa-solid fa-key me-2 text-primary"></i>Gestionar API Keys
        </h5>
        <?php if (empty($api_keys)): ?>
          <button type="button" class="btn btn-primary btn-sm text-uppercase small fw-bold btn-generate-key">
            <i class="fa-solid fa-plus me-1"></i> Generar Nueva Llave
          </button>
        <?php endif; ?>
      </div>
      <div class="card-body p-0">
        <?php if (empty($api_keys)): ?>
          <div class="text-center py-5">
            <div class="bg-body d-inline-block p-4 rounded-circle border mb-3">
              <i class="fa-solid fa-key fa-3x text-body-secondary opacity-25"></i>
            </div>
            <h6 class="fw-bold">No tienes ninguna API Key</h6>
            <p class="text-body-secondary small mb-3">Genera una llave para integrar tu cuenta con otros servicios.</p>
          </div>
        <?php else: ?>
          <div class="table-responsive">
            <table class="table table-hover align-middle border-top mb-0">
              <thead class="bg-body">
                <tr>
                  <th class="small py-3 ps-3">Token</th>
                  <th class="small py-3">Fecha de Creación</th>
                  <th class="small py-3 text-end pe-3">Acciones</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($api_keys as $key): ?>
                  <tr>
                    <td class="py-3 ps-3">
                      <div class="input-group input-group-sm" style="max-width: 280px;">
                        <input type="text" class="form-control font-monospace x-small bg-body" value="<?= $key->api_key ?>" readonly>
                        <button class="btn btn-outline-primary" type="button" onclick="copyToClipboard('<?= $key->api_key ?>', this)" title="Copiar">
                          <i class="fa-regular fa-copy"></i>
                        </button>
                      </div>
                    </td>
                    <td class="py-3">
                      <span class="text-body-secondary small"><?= date('d M, Y', strtotime($key->api_key_created)) ?></span>
                    </td>
                    <td class="text-end pe-3">
                      <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-sm btn-outline-primary btn-regenerate-key" 
                                data-id="<?= $key->api_key_id ?>" title="Regenerar">
                          <i class="fa-solid fa-arrows-rotate"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-danger btn-delete-key" 
                                data-id="<?= $key->api_key_id ?>" 
                                title="Eliminar Llave">
                          <i class="fa-solid fa-trash"></i>
                        </button>
                      </div>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php endif; ?>
      </div>
    </div>

    <!-- INFORMACIÓN DE AYUDA -->
    <div class="card bg-body border">
      <div class="card-body d-flex gap-3 p-3">
        <i class="fa-solid fa-circle-question text-info fs-4"></i>
        <div>
          <h6 class="fw-bold mb-1">¿Para qué sirven las API Keys?</h6>
          <p class="text-body-secondary small mb-0">
            Las llaves de API permiten que aplicaciones externas se comuniquen con este sistema de forma segura en tu nombre. 
            <strong>Nunca compartas tu API Key con nadie.</strong>
          </p>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  window.copyToClipboard = function(text, btn) {
    navigator.clipboard.writeText(text).then(() => {
      const icon = btn.querySelector('i');
      icon.classList.remove('fa-copy');
      icon.classList.add('fa-check');
      btn.classList.replace('btn-outline-primary', 'btn-success');
      
      setTimeout(() => {
        icon.classList.remove('fa-check');
        icon.classList.add('fa-copy');
        btn.classList.replace('btn-success', 'btn-outline-primary');
      }, 2000);
    });
  }

  document.querySelectorAll('.btn-delete-key').forEach(btn => {
    btn.addEventListener('click', function() {
      const id = this.getAttribute('data-id');
      if (typeof Swal !== 'undefined') {
        Swal.fire({
          title: '¿Eliminar llave?',
          text: 'Esta API Key dejará de funcionar inmediatamente.',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          confirmButtonText: 'Sí, eliminar',
          cancelButtonText: 'Cancelar'
        }).then((result) => {
          if (result.isConfirmed) {
            window.location.href = window.location.pathname + '?delete_key=' + id;
          }
        });
      } else if (confirm('¿Estás seguro de que deseas eliminar la API Key?')) {
        window.location.href = window.location.pathname + '?delete_key=' + id;
      }
    });
  });

  document.querySelectorAll('.btn-regenerate-key').forEach(btn => {
    btn.addEventListener('click', function() {
      if (typeof Swal !== 'undefined') {
        Swal.fire({
          title: '¿Regenerar llave?',
          text: 'La llave actual será invalidada y recibirás una nueva de inmediato.',
          icon: 'info',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          confirmButtonText: 'Sí, regenerar',
          cancelButtonText: 'Cancelar'
        }).then((result) => {
          if (result.isConfirmed) {
            submitAction('regenerate_key');
          }
        });
      }
    });
  });

  document.querySelectorAll('.btn-generate-key').forEach(btn => {
    btn.addEventListener('click', function() {
      if (typeof Swal !== 'undefined') {
        Swal.fire({
          title: '¿Generar API Key?',
          text: 'Se creará una nueva llave de acceso para tu cuenta.',
          icon: 'question',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          confirmButtonText: 'Sí, generar',
          cancelButtonText: 'Cancelar'
        }).then((result) => {
          if (result.isConfirmed) {
            submitAction('generate_key');
          }
        });
      }
    });
  });

  function submitAction(name) {
    const form = document.createElement('form');
    form.method = 'POST';
    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = name;
    input.value = '1';
    form.appendChild(input);
    document.body.appendChild(form);
    form.submit();
  }
});
</script>

<style>
  .fs-7 { font-size: 0.875rem; }
  .x-small { font-size: 0.75rem; }
</style>
