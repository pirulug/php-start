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
    <?php require_once BASE_DIR . '/app/admin/modules/account/views/partials/sidebar.php'; ?>
  </div>

  <!-- CONTENIDO PRINCIPAL -->
  <div class="col-md-8 col-lg-9">
    <div class="card mb-3">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="card-title mb-0 fw-bold text-uppercase">
          <i class="fa-solid fa-key me-2 text-primary"></i>Gestionar API Keys
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
            <h6 class="fw-bold">No tienes ninguna API Key</h6>
            <p class="text-body-secondary small mb-3">Genera una llave para integrar tu cuenta con otros servicios.</p>
            <button type="button" class="btn btn-primary text-uppercase small fw-bold btn-generate-key px-4">
              Generar Primera Llave
            </button>
          </div>
        <?php else: ?>
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
              <thead class="bg-body-secondary">
                <tr>
                  <th class="small py-3 ps-3 text-uppercase">Token</th>
                  <th class="small py-3 text-uppercase">Fecha de Creación</th>
                  <th class="small py-3 text-end pe-3 text-uppercase">Acciones</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($api_keys as $key): ?>
                  <tr>
                    <td class="py-3 ps-3">
                      <div class="input-group" style="max-width: 320px;">
                        <input type="text" class="form-control font-monospace small bg-body" value="<?= $key->api_key ?>"
                          readonly>
                        <button class="btn btn-outline-primary" type="button"
                          onclick="copyToClipboard('<?= $key->api_key ?>', this)" title="Copiar">
                          <i class="fa fa-copy"></i>
                        </button>
                      </div>
                    </td>
                    <td class="py-3">
                      <?php if ($key->api_key_created): ?>
                        <span class="text-body-secondary small fw-medium"><?= format_datetime($key->api_key_created) ?></span>
                      <?php else: ?>
                        <span class="badge bg-body-secondary text-body-secondary border px-2">Legacy</span>
                      <?php endif; ?>
                    </td>
                    <td class="text-end pe-3">
                      <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-sm btn-outline-primary btn-regenerate-key px-2"
                          data-id="<?= $key->api_key_id ?>" title="Regenerar">
                          <i class="fa-solid fa-arrows-rotate"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-danger btn-delete-key px-2"
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

    <!-- INFORMACIÓN DE AYUDA -->
    <div class="card bg-body border-info-subtle shadow-none">
      <div class="card-body d-flex gap-3 p-3">
        <div
          class="bg-info-subtle p-2 rounded-3 border border-info-subtle d-flex align-items-center justify-content-center"
          style="width: 45px; height: 45px;">
          <i class="fa-solid fa-circle-question text-info fs-4"></i>
        </div>
        <div>
          <h6 class="fw-bold mb-1">¿Para qué sirven las API Keys?</h6>
          <p class="text-body-secondary small mb-0">
            Las llaves de API permiten que aplicaciones externas se comuniquen con este sistema de forma segura en tu
            nombre.
            <strong>Nunca compartas tu API Key con nadie.</strong>
          </p>
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
        textArea.style.position = "fixed";
        textArea.style.left = "-9999px";
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();
        try { document.execCommand('copy'); showSuccess(); } catch (err) { }
        document.body.removeChild(textArea);
      }
    }

    document.querySelectorAll('.btn-delete-key').forEach(btn => {
      btn.addEventListener('click', function () {
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
          }).then((result) => { if (result.isConfirmed) window.location.href = window.location.pathname + '?delete_key=' + id; });
        } else if (confirm('¿Estás seguro?')) { window.location.href = window.location.pathname + '?delete_key=' + id; }
      });
    });

    document.querySelectorAll('.btn-regenerate-key').forEach(btn => {
      btn.addEventListener('click', function () {
        if (typeof Swal !== 'undefined') {
          Swal.fire({
            title: '¿Regenerar llave?',
            text: 'La llave actual será invalidada y recibirás una nueva de inmediato.',
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Sí, regenerar',
            cancelButtonText: 'Cancelar'
          }).then((result) => { if (result.isConfirmed) submitAction('regenerate_key'); });
        }
      });
    });

    document.querySelectorAll('.btn-generate-key').forEach(btn => {
      btn.addEventListener('click', function () {
        if (typeof Swal !== 'undefined') {
          Swal.fire({
            title: '¿Generar API Key?',
            text: 'Se creará una nueva llave de acceso para tu cuenta.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Sí, generar',
            cancelButtonText: 'Cancelar'
          }).then((result) => { if (result.isConfirmed) submitAction('generate_key'); });
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