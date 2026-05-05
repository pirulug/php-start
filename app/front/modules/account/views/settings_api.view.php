<?php start_block("title") ?>
Conexiones API
<?php end_block() ?>

<div class="container my-3">
  <div class="row g-3">

    <!-- SIDEBAR DE NAVEGACIÓN -->
    <div class="col-md-4 col-lg-3">
      <?php require_once BASE_DIR . '/app/front/modules/account/views/partials/sidebar.php'; ?>
    </div>

    <!-- CONTENIDO PRINCIPAL -->
    <div class="col-md-8 col-lg-9">
      <div class="card mb-3">
        <div class="card-header d-flex justify-content-between align-items-center py-3">
          <h6 class="card-title mb-0 fw-bold text-uppercase">
            <i class="fa-solid fa-plug-circle-check me-2 text-primary"></i>Tus Credenciales API
          </h6>
          <?php if (empty($api_keys)): ?>
            <button type="button" class="btn btn-primary btn-sm text-uppercase small fw-bold btn-generate-key px-3">
              <i class="fa-solid fa-plus me-1"></i> Generar Llave
            </button>
          <?php endif; ?>
        </div>
        <div class="card-body p-0">
          <?php if (empty($api_keys)): ?>
            <div class="text-center py-5">
              <div class="bg-body-secondary d-inline-block p-4 rounded-circle mb-3 border">
                <i class="fa-solid fa-key fa-3x text-body-secondary opacity-25"></i>
              </div>
              <h6 class="fw-bold">No tienes ninguna API Key integrada</h6>
              <p class="text-body-secondary small mb-3">Crea una llave para interactuar con nuestros servicios externos.
              </p>
              <button type="button" class="btn btn-primary text-uppercase small fw-bold btn-generate-key px-4">
                Generar mi Primera Llave
              </button>
            </div>
          <?php else: ?>
            <div class="table-responsive">
              <table class="table table-hover align-middle mb-0">
                <thead class="bg-body-secondary">
                  <tr>
                    <th class="ps-3 py-3 text-uppercase small fw-bold text-body-secondary">Token de Acceso</th>
                    <th class="py-3 text-uppercase small fw-bold text-body-secondary text-nowrap">Generada el</th>
                    <th class="text-end pe-3 py-3 text-uppercase small fw-bold text-body-secondary">Acciones</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($api_keys as $key): ?>
                    <tr>
                      <td class="ps-3 py-3">
                        <div class="input-group" style="max-width: 350px;">
                          <input type="text" class="form-control font-monospace small bg-body" value="<?= $key->api_key ?>"
                            readonly>
                          <button class="btn btn-outline-primary" type="button"
                            onclick="copyToClipboard('<?= $key->api_key ?>', this)" title="Copiar">
                            <i class="fa fa-copy"></i>
                          </button>
                        </div>
                      </td>
                      <td class="py-3">
                        <span class="text-body-secondary small fw-medium"><?= format_datetime($key->api_key_created) ?></span>
                      </td>
                      <td class="text-end pe-3 py-3">
                        <div class="d-flex justify-content-end gap-2">
                          <button type="button" class="btn btn-sm btn-outline-primary px-2 btn-regenerate-key"
                            data-id="<?= $key->api_key_id ?>" title="Regenerar">
                            <i class="fa-solid fa-arrows-rotate"></i>
                          </button>
                          <button type="button" class="btn btn-sm btn-outline-danger px-2 btn-delete-key"
                            data-id="<?= $key->api_key_id ?>" title="Eliminar">
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
      <div class="card bg-body border-info-subtle shadow-none mb-3">
        <div class="card-body p-3 d-flex gap-3">
          <div
            class="bg-info-subtle p-2 rounded-3 border border-info-subtle d-flex align-items-center justify-content-center"
            style="width: 45px; height: 45px;">
            <i class="fa-solid fa-circle-question text-info fs-4"></i>
          </div>
          <div>
            <h6 class="fw-bold mb-1">Seguridad en tu integración</h6>
            <p class="text-body-secondary small mb-2">
              Las Llaves de API funcionan como una contraseña maestra para desarrolladores. Nunca compartas tu llave en
              foros o chats públicos.
            </p>
            <span class="badge bg-danger-subtle text-danger text-uppercase fw-bold" style="font-size: 0.65rem;">
              <i class="fa-solid fa-triangle-exclamation me-1"></i> Información Crítica
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    window.copyToClipboard = function (text, btn) {
      const icon = btn.querySelector('i');
      const showSuccess = () => {
        icon.classList.remove('fa-copy');
        icon.classList.add('fa-check');
        btn.classList.replace('btn-outline-primary', 'btn-success');
        setTimeout(() => {
          icon.classList.remove('fa-check');
          icon.classList.add('fa-copy');
          btn.classList.replace('btn-success', 'btn-outline-primary');
        }, 2000);
      };
      if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(text).then(showSuccess);
      } else {
        const textArea = document.createElement("textarea");
        textArea.value = text;
        textArea.style.position = "fixed"; textArea.style.left = "-9999px";
        document.body.appendChild(textArea);
        textArea.focus(); textArea.select();
        try { document.execCommand('copy'); showSuccess(); } catch (err) { }
        document.body.removeChild(textArea);
      }
    }
    function showConfirm(title, text, type, confirmText, callback) {
      if (typeof Swal !== 'undefined') {
        Swal.fire({ title, text, icon: type, showCancelButton: true, confirmButtonColor: type === 'warning' ? '#d33' : '#3085d6', confirmButtonText, cancelButtonText: 'Cancelar' }).then((result) => { if (result.isConfirmed) callback(); });
      } else if (confirm(text)) { callback(); }
    }
    document.querySelectorAll('.btn-delete-key').forEach(btn => {
      btn.addEventListener('click', function () {
        const id = this.getAttribute('data-id');
        showConfirm('¿Revocar acceso?', 'Esta llave dejará de funcionar permanentemente.', 'warning', 'Sí, revocar', () => { window.location.href = window.location.pathname + '?delete_key=' + id; });
      });
    });
    document.querySelectorAll('.btn-regenerate-key').forEach(btn => {
      btn.addEventListener('click', function () {
        showConfirm('¿Regenerar llave API?', 'Tu token actual será invalidado y se emitirá uno nuevo.', 'info', 'Sí, regenerar', () => { submitAction('regenerate_key'); });
      });
    });
    document.querySelectorAll('.btn-generate-key').forEach(btn => {
      btn.addEventListener('click', function () {
        showConfirm('¿Crear nueva llave?', 'Se generará una credencial para integrar con servicios externos.', 'question', 'Generar ahora', () => { submitAction('generate_key'); });
      });
    });
    function submitAction(name) {
      const form = document.createElement('form'); form.method = 'POST';
      const input = document.createElement('input'); input.type = 'hidden'; input.name = name; input.value = '1';
      form.appendChild(input); document.body.appendChild(form); form.submit();
    }
  });
</script>