<?php start_block('title'); ?>
<?= $page_title ?>
<?php end_block(); ?>

<?php start_block('breadcrumb'); ?>
<?php render_breadcrumb([
  ['label' => 'Dashboard', 'link' => admin_route('dashboard')],
  ['label' => 'Usuarios', 'link' => admin_route('users')],
  ['label' => 'Gestionar API Keys']
]) ?>
<?php end_block(); ?>

<div class="row g-3">

  <!-- CONTENT -->
  <div class="col-12">
    
    <div class="card mb-3">
      <div class="card-header bg-transparent border-bottom py-3 d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-3">
          <a href="<?= admin_route('users') ?>" class="btn btn-outline-secondary btn-sm">
            <i class="fa-solid fa-arrow-left"></i>
          </a>
          <h5 class="card-title mb-0">Gestionar API Keys de <strong><?= htmlspecialchars($managed_user->user_login) ?></strong></h5>
        </div>
        <?php if (empty($api_keys)): ?>
          <button type="button" class="btn btn-primary btn-sm text-uppercase small fw-bold btn-generate-key">
            <i class="fa-solid fa-plus me-1"></i> Generar Nueva Llave
          </button>
        <?php endif; ?>
      </div>

      <div class="card-body">
        <p class="text-muted small">
          Desde aquí puedes gestionar las llaves de acceso del usuario. Las API Keys permiten al usuario autenticarse en la API del sistema.
        </p>

        <?php if (empty($api_keys)): ?>
          <div class="alert border py-4 text-center">
            <i class="fa-solid fa-key fa-2x mb-2 opacity-50"></i>
            <p class="mb-0 small">Este usuario no tiene ninguna API Key generada aún.</p>
          </div>
        <?php else: ?>
          <div class="table-responsive">
            <table class="table table-hover align-middle border-top">
              <thead>
                <tr>
                  <th class="small py-3">API Key</th>
                  <th class="small py-3">Creada</th>
                  <th class="small py-3 text-end">Acciones</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($api_keys as $key): ?>
                  <tr>
                    <td>
                      <div class="input-group input-group-sm" style="max-width: 300px;">
                        <input type="text" class="form-control font-monospace small" value="<?= $key->api_key ?>" readonly>
                        <button class="btn btn-outline-secondary btn-copy" type="button" data-key="<?= $key->api_key ?>">
                          <i class="fa-regular fa-copy"></i>
                        </button>
                      </div>
                    </td>
                    <td>
                      <span class="text-muted small"><?= date('d/m/Y H:i', strtotime($key->api_key_created)) ?></span>
                    </td>
                    <td class="text-end">
                      <button type="button" class="btn btn-sm btn-outline-primary btn-regenerate-key" 
                              data-id="<?= $key->api_key_id ?>" title="Regenerar">
                        <i class="fa-solid fa-arrows-rotate"></i>
                      </button>
                      <button type="button" class="btn btn-sm btn-outline-danger btn-delete-key" 
                              data-id="<?= $key->api_key_id ?>">
                        <i class="fa-solid fa-trash"></i>
                      </button>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function() {
  // Copiar al portapapeles
  document.querySelectorAll('.btn-copy').forEach(btn => {
    btn.addEventListener('click', function() {
      const key = this.getAttribute('data-key');
      navigator.clipboard.writeText(key).then(() => {
        const icon = this.querySelector('i');
        icon.classList.remove('fa-copy');
        icon.classList.add('fa-check', 'text-success');
        setTimeout(() => {
          icon.classList.remove('fa-check', 'text-success');
          icon.classList.add('fa-copy');
        }, 1500);
      });
    });
  });

  // Eliminar llave
  document.querySelectorAll('.btn-delete-key').forEach(btn => {
    btn.addEventListener('click', function() {
      const id = this.getAttribute('data-id');
      
      if (typeof Swal !== 'undefined') {
        Swal.fire({
          title: '¿Eliminar API Key?',
          text: 'La llave de este usuario dejará de funcionar inmediatamente.',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#fe7444',
          confirmButtonText: 'Sí, eliminar',
          cancelButtonText: 'Cancelar'
        }).then((result) => {
          if (result.isConfirmed) {
            window.location.href = window.location.pathname + '?delete_key=' + id;
          }
        });
      } else {
        if (confirm('¿Estás seguro de que deseas eliminar la API Key de este usuario?')) {
          window.location.href = window.location.pathname + '?delete_key=' + id;
        }
      }
    });
  });

  // Generar llave
  document.querySelectorAll('.btn-generate-key').forEach(btn => {
    btn.addEventListener('click', function() {
      if (typeof Swal !== 'undefined') {
        Swal.fire({
          title: '¿Generar API Key?',
          text: 'Se creará una nueva llave de acceso para este usuario.',
          icon: 'question',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#fe7444',
          confirmButtonText: 'Sí, generar',
          cancelButtonText: 'Cancelar'
        }).then((result) => {
          if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'generate_key';
            input.value = '1';
            form.appendChild(input);
            document.body.appendChild(form);
            form.submit();
          }
        });
      } else {
        if (confirm('¿Estás seguro de que deseas generar una nueva API Key para este usuario?')) {
          const form = document.createElement('form');
          form.method = 'POST';
          const input = document.createElement('input');
          input.type = 'hidden';
          input.name = 'generate_key';
          input.value = '1';
          form.appendChild(input);
          document.body.appendChild(form);
          form.submit();
        }
      }
    });
  });

  // Regenerar llave
  document.querySelectorAll('.btn-regenerate-key').forEach(btn => {
    btn.addEventListener('click', function() {
      if (typeof Swal !== 'undefined') {
        Swal.fire({
          title: '¿Regenerar API Key?',
          text: 'La llave actual del usuario será invalidada y se generará una nueva.',
          icon: 'info',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#fe7444',
          confirmButtonText: 'Sí, regenerar',
          cancelButtonText: 'Cancelar'
        }).then((result) => {
          if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'regenerate_key';
            input.value = '1';
            form.appendChild(input);
            document.body.appendChild(form);
            form.submit();
          }
        });
      } else {
        if (confirm('¿Estás seguro de que deseas regenerar la API Key de este usuario?')) {
          const form = document.createElement('form');
          form.method = 'POST';
          const input = document.createElement('input');
          input.type = 'hidden';
          input.name = 'regenerate_key';
          input.value = '1';
          form.appendChild(input);
          document.body.appendChild(form);
          form.submit();
        }
      }
    });
  });
});
</script>
