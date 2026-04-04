<?php start_block('title'); ?>
Gestión de Captcha
<?php end_block(); ?>

<?php start_block('breadcrumb'); ?>
<?php render_breadcrumb([
  ['label' => 'Dashboard', 'link' => admin_route('dashboard')],
  ['label' => 'Componentes'],
  ['label' => 'Captcha']
]) ?>
<?php end_block(); ?>

<div class="row g-4">
    <!-- Información de Tipos -->
    <div class="col-xl-8">
        <div class="card h-100">
            <div class="card-header pb-0 border-0 bg-transparent">
                <h5 class="card-title fw-bold"><i class="fa-solid fa-shield-halved me-1 text-primary"></i> Tipos de Captcha Soportados</h5>
                <p class="text-body-secondary small">Configura el motor de validación desde el panel de ajustes globales de seguridad.</p>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-borderless align-middle">
                        <thead class="text-body-secondary border-bottom">
                            <tr>
                                <th>Tipo</th>
                                <th>Proveedor</th>
                                <th>Ventajas</th>
                                <th>Dependencias</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill">Vanilla</span></td>
                                <td class="small">Interno (PHP)</td>
                                <td class="small text-success">Privacidad total, sin APIs externas.</td>
                                <td class="small text-body-secondary">Librería GD.</td>
                            </tr>
                            <tr>
                                <td><span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill">reCAPTCHA</span></td>
                                <td class="small">Google Cloud</td>
                                <td class="small text-success">Alta precisión y detección invisible.</td>
                                <td class="small text-body-secondary">Google Keys.</td>
                            </tr>
                            <tr>
                                <td><span class="badge bg-info-subtle text-info border border-info-subtle rounded-pill">Turnstile</span></td>
                                <td class="small">Cloudflare</td>
                                <td class="small text-success">Interacción mínima, alta privacidad.</td>
                                <td class="small text-body-secondary">Turnstile Keys.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <hr class="my-4 opacity-25">

                <h6 class="fw-bold mb-3"><i class="fa-solid fa-code me-1 text-success"></i> Snippets de Implementación</h6>
                <p class="small text-body-secondary">La clase <code>CaptchaManager</code> abstrae la lógica de cualquier proveedor activo:</p>
                
                <div class="bg-body-tertiary p-3 rounded-3 border">
                    <pre class="mb-0 small"><code class="text-info" style="font-family: 'Fira Code', monospace;">$captcha = new CaptchaManager();
echo $captcha->render(); // Se usa en la vista

// En la acción se valida contra el $_POST
if (!$captcha->validate($_POST)) {
    return "Error de validación";
}</code></pre>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulario de Prueba -->
    <div class="col-xl-4">
        <div class="card h-100 border-primary">
            <div class="card-header border-0 bg-transparent py-3">
                <h5 class="card-title mb-0 fw-bold"><i class="fa-solid fa-vial me-1 text-primary"></i> Demostración en Vivo</h5>
            </div>
            <div class="card-body pt-0">
                <form action="<?= admin_route('componentes/captcha') ?>" method="POST">
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-body-secondary small">Dato de prueba</label>
                        <input class="form-control" type="text" name="texto" placeholder="Ingresa un texto..." required>
                        <div class="form-text small">Si el captcha es válido, verás una notificación de éxito.</div>
                    </div>

                    <!-- Contenedor del Captcha con estilo matching -->
                    <div class="mb-4 bg-body-tertiary p-3 rounded-3 border text-center d-flex align-items-center justify-content-center" style="min-height: 120px;">
                        <div class="w-100 h-100">
                           <?= $captcha->render(); ?>
                        </div>
                    </div>

                    <div class="d-grid">
                        <?= ActionBtn::save()->text('Validar Captcha')->render(); ?>
                    </div>
                </form>
            </div>
            <div class="card-footer border-0 bg-transparent pt-0 pb-3">
                <div class="p-2 rounded-3 border bg-body-tertiary d-flex align-items-center justify-content-center text-center">
                   <div class="small text-body-secondary">
                        <i class="fa-solid fa-plug me-1 text-primary"></i> 
                        Motor activo: <span class="badge bg-body-secondary text-body border"><?= strtoupper($config->get('captcha_type') ?? 'VANILLA') ?></span>
                   </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 mt-2">
        <div class="p-3 border rounded-3 bg-body-tertiary">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div class="small">
                    <i class="fa-solid fa-gear me-2 text-primary"></i>
                    <span class="text-body-secondary">Puedes cambiar el motor de captcha predeterminado en los </span>
                    <a href="<?= admin_route('settings/captcha') ?>" class="fw-bold">Ajustes de Seguridad</a>.
                </div>
                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="simulateFlash()">
                   <i class="fa-solid fa-bell me-1"></i> Simular Flash
                </button>
            </div>
        </div>
    </div>
</div>

<?php start_block("js"); ?>
<script>
/**
 * Simulación de mensaje flash detectado por sa.js
 */
function simulateFlash() {
    const div = document.createElement('div');
    div.setAttribute('data-sa-flash', '');
    div.setAttribute('data-sa-type', 'info');
    div.setAttribute('data-sa-title', 'Previsualización');
    div.setAttribute('data-sa-text', 'El componente de alerta global (PiruSA) funciona en conjunto con Captchas.');
    document.body.appendChild(div);
    
    if (typeof PiruSA !== 'undefined') {
        PiruSA.checkFlashMessages(); // Re-chequear para la demo
    }
    div.remove();
}
</script>
<?php end_block(); ?>