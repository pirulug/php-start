<?php start_block("title") ?>
Demostración de Código QR Especializado
<?php end_block() ?>

<?php start_block('breadcrumb'); ?>
<?php render_breadcrumb([
  ['label' => 'Dashboard', 'link' => admin_route('dashboard')],
  ['label' => 'Componentes', 'link' => admin_route('componentes/captcha')],
  ['label' => 'QR Codes']
]) ?>
<?php end_block(); ?>

<?php start_block("css") ?>
<?= static_libs_css("prismjs", "prismjs.css") ?>
<style>
  .qr-example-card {
    transition: all 0.3s ease;
  }

  .qr-example-card:hover {
    transform: translateY(-5px);
  }

  .qr-img-demo {
    max-width: 150px;
    height: auto;
    background: #fff;
    padding: 8px;
    border-radius: 6px;
  }
</style>
<?php end_block() ?>

<?php start_block("js") ?>
<?= static_libs_js("prismjs", "prismjs.js") ?>
<script>
  document.getElementById('qrForm').addEventListener('submit', function (e) {
    e.preventDefault();

    const text = (document.getElementById('qrText').value);
    const level = document.getElementById('qrLevel').value;
    const density = document.getElementById('qrDensity').value;
    const color = document.getElementById('qrColor').value.replace('#', '');
    const bg = document.getElementById('qrBg').value.replace('#', '');
    const scale = document.getElementById('qrScale').value;

    const baseUrl = "<?= APP_URL ?>/service/qrcode/render";
    const finalUrl = `${baseUrl}?d=${text}&s=${level}&sf=${scale}&md=${density}&fc=${color}&bc=${bg}`;

    const container = document.getElementById('resultContainer');
    const img = document.getElementById('qrResultImg');
    const code = document.getElementById('qrUrlCode');

    img.src = finalUrl;
    code.innerText = finalUrl;
    container.classList.remove('d-none');
  });
</script>
<?php end_block() ?>

<div class="row">
  <div class="col-12">
    <div class="card mb-3">
      <div class="card-header">
        <h5 class="card-title mb-0">Librería QRCode</h5>
      </div>
      <div class="card-body">
        <p>Motor especializado para la generación de códigos QR (2D) con soporte completo para niveles de corrección de
          errores (ECL), personalización de colores y estilos de módulos.</p>

        <h6>Integración:</h6>
        <pre><code class="language-php">$qr = new QRCode("Contenido", ['s' => 'qrh', 'sf' => 5]);
$qr->output_image();</code></pre>
      </div>
    </div>
  </div>

  <div class="col-md-6">
    <div class="card mb-3">
      <div class="card-header">
        <h5 class="card-title mb-0">Niveles de Corrección (ECL)</h5>
      </div>
      <div class="card-body">
        <div class="row text-center">
          <div class="col-6 mb-3">
            <label class="d-block small text-muted text-uppercase mb-2">Bajo (L) - 7%</label>
            <img src="<?= APP_URL ?>/service/qrcode/render?s=qrl&d=ECL-LOW&sf=4"
              class="qr-img-demo img-fluid border bg-body">
          </div>
          <div class="col-6 mb-3">
            <label class="d-block small text-muted text-uppercase mb-2">Medio (M) - 15%</label>
            <img src="<?= APP_URL ?>/service/qrcode/render?s=qrm&d=ECL-MEDIUM&sf=4"
              class="qr-img-demo img-fluid border bg-body">
          </div>
          <div class="col-6">
            <label class="d-block small text-muted text-uppercase mb-2">Cuartil (Q) - 25%</label>
            <img src="<?= APP_URL ?>/service/qrcode/render?s=qrq&d=ECL-QUARTILE&sf=4"
              class="qr-img-demo img-fluid border bg-body">
          </div>
          <div class="col-6">
            <label class="d-block small text-muted text-uppercase mb-2">Alto (H) - 30%</label>
            <img src="<?= APP_URL ?>/service/qrcode/render?s=qrh&d=ECL-HIGH&sf=4"
              class="qr-img-demo img-fluid border bg-body">
          </div>
        </div>
      </div>
    </div>

    <div class="card mb-3">
      <div class="card-header">
        <h5 class="card-title mb-0">Personalización de Colores</h5>
      </div>
      <div class="card-body">
        <div class="row text-center">
          <div class="col-6 mb-3">
            <label class="d-block small text-muted text-uppercase mb-2">Corporativo Azul</label>
            <img src="<?= APP_URL ?>/service/qrcode/render?d=BLUE-QR&sf=4&bc=E3F2FD&fc=0D47A1"
              class="qr-img-demo img-fluid border bg-body">
          </div>
          <div class="col-6 mb-3">
            <label class="d-block small text-muted text-uppercase mb-2">Negativo (Dark)</label>
            <img src="<?= APP_URL ?>/service/qrcode/render?d=DARK-QR&sf=4&bc=212529&fc=F8F9FA"
              class="qr-img-demo img-fluid border bg-body">
          </div>
          <div class="col-6">
            <label class="d-block small text-muted text-uppercase mb-2">Estilo Moderno</label>
            <img src="<?= APP_URL ?>/service/qrcode/render?d=MODERN&sf=4&bc=FFF5F5&fc=E53E3E"
              class="qr-img-demo img-fluid border bg-body">
          </div>
          <div class="col-6">
            <label class="d-block small text-muted text-uppercase mb-2">Alto Contraste</label>
            <img src="<?= APP_URL ?>/service/qrcode/render?d=CONTRAST&sf=4&bc=000&fc=FFF"
              class="qr-img-demo img-fluid border bg-body">
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-6">
    <div class="card mb-3">
      <div class="card-header">
        <h5 class="card-title mb-0">Estilos y Densidad (Dot Style)</h5>
      </div>
      <div class="card-body">
        <div class="row text-center">
          <div class="col-6 mb-3">
            <label class="d-block small text-muted text-uppercase mb-2">Densidad 80% (md=0.8)</label>
            <img src="<?= APP_URL ?>/service/qrcode/render?d=DOTS-80&sf=4&md=0.8"
              class="qr-img-demo img-fluid border bg-body">
          </div>
          <div class="col-6 mb-3">
            <label class="d-block small text-muted text-uppercase mb-2">Densidad 60% (md=0.6)</label>
            <img src="<?= APP_URL ?>/service/qrcode/render?d=DOTS-60&sf=4&md=0.6"
              class="qr-img-demo img-fluid border bg-body">
          </div>
          <div class="col-6">
            <label class="d-block small text-muted text-uppercase mb-2">Puntos Finos (md=0.4)</label>
            <img src="<?= APP_URL ?>/service/qrcode/render?d=FINE-DOTS&sf=4&md=0.4"
              class="qr-img-demo img-fluid border bg-body">
          </div>
          <div class="col-6">
            <label class="d-block small text-muted text-uppercase mb-2">Máxima Densidad (Default)</label>
            <img src="<?= APP_URL ?>/service/qrcode/render?d=SOLID-QR&sf=4&md=1"
              class="qr-img-demo img-fluid border bg-body">
          </div>
        </div>
      </div>
    </div>

    <div class="card mb-3">
      <div class="card-header">
        <h5 class="card-title mb-0">Escalado y Márgenes</h5>
      </div>
      <div class="card-body text-center">
        <div class="mb-4">
          <label class="d-block small text-muted text-uppercase mb-2">Padding Extendido (p=40)</label>
          <img src="<?= APP_URL ?>/service/qrcode/render?d=PADDING-QR&sf=3&p=40&bc=F8F9FA"
            class="qr-img-demo img-fluid border bg-body">
          <div class="mt-1 small text-muted">Añade una "Zona Silenciosa" mayor alrededor.</div>
        </div>
        <div class="mb-0">
          <label class="d-block small text-muted text-uppercase mb-2">Escala Grande (sf=10)</label>
          <img src="<?= APP_URL ?>/service/qrcode/render?d=BIG-QR&sf=10" class="qr-img-demo img-fluid border bg-body"
            style="max-width: 120px;">
          <div class="mt-1 small text-muted">Alta resolución para impresión.</div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="card mb-3">
  <div class="card-header">
    <h5 class="card-title mb-0">Generador Interactivo</h5>
  </div>
  <div class="card-body">
    <form id="qrForm">
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Contenido / URL</label>
          <textarea class="form-control" id="qrText" rows="2"
            placeholder="Ingresa el texto o URL...">https://github.com/pirulug/php-start</textarea>
        </div>
        <div class="col-md-3">
          <label class="form-label">Nivel de Error</label>
          <select class="form-select" id="qrLevel">
            <option value="qrl">Bajo (L) - 7%</option>
            <option value="qrm">Medio (M) - 15%</option>
            <option value="qrq">Cuartil (Q) - 25%</option>
            <option value="qrh" selected>Alto (H) - 30%</option>
          </select>
        </div>
        <div class="col-md-3">
          <label class="form-label">Densidad (Puntos)</label>
          <select class="form-select" id="qrDensity">
            <option value="1">Sólido (100%)</option>
            <option value="0.9">90%</option>
            <option value="0.8" selected>80%</option>
            <option value="0.6">60%</option>
            <option value="0.4">40% (Dots)</option>
          </select>
        </div>
        <div class="col-md-3">
          <label class="form-label">Color QR (FC)</label>
          <input type="color" class="form-control form-control-color w-100" id="qrColor" value="#000000">
        </div>
        <div class="col-md-3">
          <label class="form-label">Color Fondo (BC)</label>
          <input type="color" class="form-control form-control-color w-100" id="qrBg" value="#ffffff">
        </div>
        <div class="col-md-2">
          <label class="form-label">Escala</label>
          <input type="number" class="form-control" id="qrScale" value="6" min="1" max="20">
        </div>
        <div class="col-md-4 d-flex align-items-end">
          <button type="submit" class="btn btn-primary w-100 text-uppercase small fw-bold">
            <i class="fa-solid fa-sync me-2"></i> Generar QR
          </button>
        </div>
      </div>
    </form>

    <div class="mt-4 text-center">
      <div id="resultContainer" class="p-4 bg-body rounded border d-none qr-example-card">
        <img id="qrResultImg" src="" class="img-fluid border p-2 bg-white rounded shadow-sm mb-3"
          style="max-width: 250px;">
        <div class="mt-2">
          <code id="qrUrlCode" class="small text-muted d-block text-break"></code>
        </div>
      </div>
    </div>
  </div>
</div>