<?php start_block("title") ?>
Conexiones API
<?php end_block() ?>

<div class="container my-3">
<div class="row g-3 mt-3">

  <!-- SIDEBAR DE NAVEGACIÓN -->
  <div class="col-md-4 col-lg-3">
    <div class="card sticky-top bg-body" style="top: 2rem; z-index: 1;">
      <div class="card-header bg-transparent border-bottom p-3">
        <h6 class="m-0 fw-bold text-uppercase small text-body-secondary">Área Personal</h6>
      </div>
      <div class="list-group list-group-flush bg-transparent">
        <a href="<?= home_route("account/profile") ?>" 
           class="list-group-item list-group-item-action d-flex align-items-center gap-2 py-3 mb-1 rounded-3">
          <i class="fa-solid fa-id-card fa-fw"></i>
          <span class="">Vista General</span>
        </a>
        <a href="<?= home_route("account/settings/profile") ?>" 
           class="list-group-item list-group-item-action d-flex align-items-center gap-2 py-3 mb-1 rounded-3">
          <i class="fa-solid fa-circle-user fa-fw"></i>
          <span class="">Ajustes de Perfil</span>
        </a>
        <a href="<?= home_route("account/settings/password") ?>" 
           class="list-group-item list-group-item-action d-flex align-items-center gap-2 py-3 mb-1 rounded-3">
          <i class="fa-solid fa-shield-halved fa-fw"></i>
          <span class="">Privacidad y Seguridad</span>
        </a>
        <a href="<?= home_route("account/settings/api") ?>" 
           class="list-group-item list-group-item-action d-flex align-items-center gap-2 py-3 mb-1 rounded-3 <?= strpos($_SERVER['REQUEST_URI'], 'api') !== false ? 'active' : '' ?>">
          <i class="fa-solid fa-key fa-fw"></i>
          <span class="fw-bold ">Conexiones API</span>
        </a>
      </div>
    </div>
  </div>

  <!-- CONTENIDO PRINCIPAL -->
  <div class="col-md-8 col-lg-9">
    <div class="card mb-3">
      <div class="card-header bg-transparent border-bottom py-3 d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0 fs-6 fw-bold text-uppercase text-body">
          <i class="fa-solid fa-plug-circle-check me-2 text-primary"></i>Tus Credenciales API
        </h5>
        <?php if (empty($api_keys)): ?>
          <button type="button" class="btn btn-primary btn-sm text-uppercase small fw-bold btn-generate-key px-3">
            <i class="fa-solid fa-plus me-1"></i> Generar mi Llave
          </button>
        <?php endif; ?>
      </div>
      <div class="card-body p-0">
        <?php if (empty($api_keys)): ?>
          <div class="text-center py-5">
            <div class="bg-body d-inline-block p-3 border rounded-circle mb-3">
              <i class="fa-solid fa-key fa-2x text-body-secondary opacity-25"></i>
            </div>
            <h6 class="fw-bold text-body">No tienes ninguna API Key integrada</h6>
            <p class="text-body-secondary small mb-0">Crea una llave para interactuar con nuestros servicios externos.</p>
          </div>
        <?php else: ?>
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
              <thead class="bg-body">
                <tr>
                  <th class="ps-3 py-3  text-uppercase text-body-secondary fw-bold border-top-0">Token de Acceso</th>
                  <th class="py-3  text-uppercase text-body-secondary fw-bold border-top-0">Generada el</th>
                  <th class="text-end pe-3 py-3  text-uppercase text-body-secondary fw-bold border-top-0">Acciones</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($api_keys as $key): ?>
                  <tr>
                    <td class="ps-3 py-4">
                      <div class="input-group input-group-sm" style="max-width: 320px;">
                        <input type="text" class="form-control font-monospace  bg-body border" value="<?= $key->api_key ?>" readonly>
                        <button class="btn btn-primary border-0" type="button" onclick="copyToClipboard('<?= $key->api_key ?>', this)" title="Copiar al portapapeles">
                          <i class="fa fa-copy"></i>
                        </button>
                      </div>
                    </td>
                    <td class="py-4">
                      <span class="text-body-secondary small"><?= date('d M, Y', strtotime($key->api_key_created)) ?></span>
                    </td>
                    <td class="text-end pe-3 py-4">
                      <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-sm btn-outline-primary btn-regenerate-key" 
                                data-id="<?= $key->api_key_id ?>" title="Regenerar Token">
                          <i class="fa-solid fa-arrows-rotate"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-danger btn-delete-key" 
                                data-id="<?= $key->api_key_id ?>" 
                                title="Revocar Acceso">
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

    <!-- AYUDA / DOCUMENTACIÓN -->
    <div class="card bg-body border">
      <div class="card-body p-3 d-flex gap-3">
        <div class="text-info">
             <i class="fa-solid fa-circle-question fs-3"></i>
        </div>
        <div>
          <h6 class="fw-bold mb-1 text-body">Seguridad en tu integración</h6>
          <p class="text-body-secondary small mb-3">
            Las Llaves de API (API Keys) funcionan como una contraseña maestra para desarrolladores. Permiten que otras aplicaciones realicen acciones en tu nombre en esta plataforma.
          </p>
          <div class="alert alert-light border py-1 px-3 mb-0 d-inline-block  text-danger fw-bold bg-body">
            <i class="fa-solid fa-triangle-exclamation me-1"></i> Nunca reveles tu llave en foros, código público o chats.
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  window.copyToClipboard = function(text, btn) {
    const icon = btn.querySelector('i');
    
    const showSuccess = () => {
      icon.classList.remove('fa-copy');
      icon.classList.add('fa-check');
      btn.classList.replace('btn-primary', 'btn-success');
      
      setTimeout(() => {
        icon.classList.remove('fa-check');
        icon.classList.add('fa-copy');
        btn.classList.replace('btn-success', 'btn-primary');
      }, 2000);
    };

    if (navigator.clipboard && window.isSecureContext) {
      navigator.clipboard.writeText(text).then(showSuccess);
    } else {
      const textArea = document.createElement("textarea");
      textArea.value = text;
      textArea.style.position = "fixed"; 
      textArea.style.left = "-999999px";
      textArea.style.top = "-999999px";
      document.body.appendChild(textArea);
      textArea.focus();
      textArea.select();
      try {
        document.execCommand('copy');
        showSuccess();
      } catch (err) {
        console.error('No se pudo copiar el texto: ', err);
      }
      document.body.removeChild(textArea);
    }
  }

  function showConfirm(title, text, type, confirmText, callback) {
    if (typeof Swal !== 'undefined') {
      Swal.fire({
        title: title,
        text: text,
        icon: type,
        showCancelButton: true,
        confirmButtonColor: type === 'warning' ? '#d33' : '#3085d6',
        confirmButtonText: confirmText,
        cancelButtonText: 'Cancelar'
      }).then((result) => {
        if (result.isConfirmed) callback();
      });
    } else if (confirm(text)) {
      callback();
    }
  }

  document.querySelectorAll('.btn-delete-key').forEach(btn => {
    btn.addEventListener('click', function() {
      const id = this.getAttribute('data-id');
      showConfirm('¿Revocar acceso?', 'Esta llave dejará de funcionar permanentemente.', 'warning', 'Sí, revocar', () => {
        window.location.href = window.location.pathname + '?delete_key=' + id;
      });
    });
  });

  document.querySelectorAll('.btn-regenerate-key').forEach(btn => {
    btn.addEventListener('click', function() {
      showConfirm('¿Regenerar llave API?', 'Tu token actual será invalidado y se emitirá uno nuevo.', 'info', 'Sí, regenerar', () => {
        submitAction('regenerate_key');
      });
    });
  });

  document.querySelectorAll('.btn-generate-key').forEach(btn => {
    btn.addEventListener('click', function() {
      showConfirm('¿Crear nueva llave?', 'Se generará una credencial para integrar con servicios externos.', 'question', 'Generar ahora', () => {
        submitAction('generate_key');
      });
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
