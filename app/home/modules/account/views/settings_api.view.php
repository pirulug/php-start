<?php start_block("title") ?>
Mis API Keys
<?php end_block() ?>

<div class="container mt-3">
  <div class="row g-3">

    <!-- SIDEBAR -->
    <div class="col-md-4 col-lg-3">
      <div class="card sticky-top" style="top: 6rem; z-index: 1;">
        <div class="card-header bg-transparent border-bottom p-3">
          <h6 class="m-0 fw-bold text-uppercase small text-body-secondary">Ajustes</h6>
        </div>
        <div class="list-group list-group-flush">
          <a href="<?= home_route("account/settings/profile") ?>" 
             class="list-group-item list-group-item-action d-flex align-items-center gap-2 py-3 <?= strpos($_SERVER['REQUEST_URI'], 'profile') !== false ? 'active' : '' ?>">
            <i class="fa-solid fa-user-circle fa-fw"></i>
            <span>Mi Perfil</span>
          </a>
          <a href="<?= home_route("account/settings/password") ?>" 
             class="list-group-item list-group-item-action d-flex align-items-center gap-2 py-3 <?= strpos($_SERVER['REQUEST_URI'], 'password') !== false ? 'active' : '' ?>">
            <i class="fa-solid fa-shield-halved fa-fw"></i>
            <span>Seguridad</span>
          </a>
          <a href="<?= home_route("account/settings/api") ?>" 
             class="list-group-item list-group-item-action d-flex align-items-center gap-2 py-3 <?= strpos($_SERVER['REQUEST_URI'], 'api') !== false ? 'active' : '' ?>">
            <i class="fa-solid fa-key fa-fw"></i>
            <span>API Keys</span>
          </a>
        </div>
      </div>
    </div>

    <!-- CONTENT -->
    <div class="col-md-8 col-lg-9">
      
      <div class="card mb-3">
        <div class="card-header bg-transparent border-bottom py-3 d-flex justify-content-between align-items-center">
          <h5 class="card-title mb-0">Mis API Keys</h5>
          <?php if (empty($api_keys)): ?>
            <button type="button" class="btn btn-primary btn-sm px-3 text-uppercase small fw-bold btn-generate-key">
              <i class="fa-solid fa-plus me-1"></i> Generar Nueva Llave
            </button>
          <?php endif; ?>
        </div>

        <div class="card-body">
          <div class="alert alert-info mb-3 d-flex" role="alert">
            <i class="fa-solid fa-circle-info mt-1 me-3 fs-5"></i>
            <div class="small">
              Las <strong>API Keys</strong> te permiten acceder a tus datos de forma programática. 
              Mantenlas seguras y nunca las proporciones a terceros. Si crees que una llave se ha filtrado, elimínala inmediatamente.
            </div>
          </div>

          <?php if (empty($api_keys)): ?>
            <div class="text-center py-5 border">
              <div class="mb-3 opacity-25">
                <i class="fa-solid fa-key fa-4x"></i>
              </div>
              <h6 class="fw-bold">Aún no tienes API Keys</h6>
              <p class="small mb-0">Genera una llave para comenzar a usar nuestra API.</p>
            </div>
          <?php else: ?>
            <div class="table-responsive">
              <table class="table table-hover align-middle border-top">
                <thead>
                  <tr>
                    <th class="small py-3">Token</th>
                    <th class="small py-3">Fecha de Creación</th>
                    <th class="small py-3 text-end">Acciones</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($api_keys as $key): ?>
                    <tr>
                      <td>
                        <div class="input-group input-group-sm" style="max-width: 280px;">
                          <input type="text" class="form-control font-monospace small" value="<?= $key->api_key ?>" readonly>
                          <button class="btn btn-outline-secondary btn-copy" type="button" data-key="<?= $key->api_key ?>" title="Copiar Token">
                            <i class="fa-regular fa-copy"></i>
                          </button>
                        </div>
                      </td>
                      <td>
                        <span class="text-muted small"><?= date('d M, Y', strtotime($key->api_key_created)) ?></span>
                      </td>
                      <td class="text-end">
                        <button type="button" class="btn btn-sm btn-outline-primary btn-regenerate-key" 
                                data-id="<?= $key->api_key_id ?>" title="Regenerar">
                          <i class="fa-solid fa-arrows-rotate"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-danger btn-delete-key" 
                                data-id="<?= $key->api_key_id ?>" 
                                title="Eliminar Llave">
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
        
        let originalText = this.getAttribute('title');
        this.setAttribute('title', 'Copiado!');
        
        setTimeout(() => {
          icon.classList.remove('fa-check', 'text-success');
          icon.classList.add('fa-copy');
          this.setAttribute('title', originalText);
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
          title: '¿Eliminar esta llave?',
          text: 'La API Key dejará de funcionar de inmediato.',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#fe7444',
          confirmButtonText: 'Sí, eliminar',
          cancelButtonText: 'No, mantener'
        }).then((result) => {
          if (result.isConfirmed) {
            window.location.href = window.location.pathname + '?delete_key=' + id;
          }
        });
      } else {
        if (confirm('¿Estás seguro de que deseas eliminar la API Key?')) {
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
          text: 'Se creará una nueva llave de acceso para tu cuenta.',
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
        if (confirm('¿Estás seguro de que deseas generar una nueva API Key?')) {
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
          title: '¿Regenerar esta llave?',
          text: 'La llave actual dejará de funcionar y se generará una nueva.',
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
        if (confirm('¿Estás seguro de que deseas regenerar tu API Key? La actual dejará de funcionar.')) {
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
