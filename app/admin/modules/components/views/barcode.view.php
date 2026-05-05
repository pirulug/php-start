<?php start_block("title") ?>
Demostración de Códigos de Barras y QR
<?php end_block() ?>

<?php start_block('breadcrumb'); ?>
<?php render_breadcrumb([
  ['label' => 'Dashboard', 'link' => admin_route('dashboard')],
  ['label' => 'Componentes', 'link' => admin_route('componentes/captcha')],
  ['label' => 'Barcodes & QR']
]) ?>
<?php end_block(); ?>

<?php start_block("css") ?>
<link rel="stylesheet" href="<?= APP_URL ?>/static/plugins/prismjs/prismjs.css">
<style>
  .barcode-example {
    transition: transform 0.2s;
  }
  .barcode-example:hover {
    transform: scale(1.05);
  }
</style>
<?php end_block() ?>

<div class="row">
  <div class="col-12">
    <div class="card mb-3">
      <div class="card-header">
        <h5 class="card-title mb-0">Librería BarcCode</h5>
      </div>
      <div class="card-body">
        <p>El sistema cuenta con un motor potente para la generación de múltiples simbologías de códigos de barras y códigos QR. Soporta formatos <strong>PNG, SVG, GIF y JPG</strong>.</p>

        <h6>Uso básico:</h6>
        <pre><code class="language-php">$generator = new BarcCode();
$options = ['bc' => 'FFF', 'sx' => 2, 'sy' => 2];
$generator->output_image('png', 'code128', '12345678', $options);</code></pre>
      </div>
    </div>
  </div>

  <div class="col-md-6">
    <div class="card mb-3">
      <div class="card-header">
        <h5 class="card-title mb-0">Simbologías Comunes (1D)</h5>
      </div>
      <div class="card-body text-center">
        <div class="mb-4">
          <label class="d-block small text-muted text-uppercase mb-2">Code 128 (ID Producto)</label>
          <img src="<?= APP_URL ?>/service/barcode/render?s=code128&d=PROD-12345&sx=2&th=10" class="barcode-example img-fluid border p-2 bg-body">
        </div>
        <div class="mb-4">
          <label class="d-block small text-muted text-uppercase mb-2">EAN 13 (Retail)</label>
          <img src="<?= APP_URL ?>/service/barcode/render?s=ean13&d=775123456789&sx=2&th=15" class="barcode-example img-fluid border p-2 bg-body">
        </div>
        <div class="mb-0">
          <label class="d-block small text-muted text-uppercase mb-2">UPC-A</label>
          <img src="<?= APP_URL ?>/service/barcode/render?s=upca&d=12345678901&sx=2&th=15" class="barcode-example img-fluid border p-2 bg-body">
        </div>
      </div>
    </div>

    <div class="card mb-3">
      <div class="card-header">
        <h5 class="card-title mb-0">Formatos de Salida</h5>
      </div>
      <div class="card-body text-center">
        <div class="row">
          <div class="col-6 mb-3">
            <label class="d-block small text-muted text-uppercase mb-2">PNG (Bitmap)</label>
            <img src="<?= APP_URL ?>/service/barcode/render?f=png&s=code128&d=FORMAT-PNG&sx=1.5&th=10" class="barcode-example img-fluid border p-2 bg-body">
          </div>
          <div class="col-6 mb-3">
            <label class="d-block small text-muted text-uppercase mb-2">SVG (Vector)</label>
            <img src="<?= APP_URL ?>/service/barcode/render?f=svg&s=code128&d=FORMAT-SVG&sx=1.5&th=10" class="barcode-example img-fluid border p-2 bg-body">
          </div>
          <div class="col-6">
            <label class="d-block small text-muted text-uppercase mb-2">GIF</label>
            <img src="<?= APP_URL ?>/service/barcode/render?f=gif&s=code128&d=FORMAT-GIF&sx=1.5&th=10" class="barcode-example img-fluid border p-2 bg-body">
          </div>
          <div class="col-6">
            <label class="d-block small text-muted text-uppercase mb-2">JPG</label>
            <img src="<?= APP_URL ?>/service/barcode/render?f=jpg&s=code128&d=FORMAT-JPG&sx=1.5&th=10" class="barcode-example img-fluid border p-2 bg-body">
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-6">
    <div class="card mb-3">
      <div class="card-header">
        <h5 class="card-title mb-0">Personalización (Colores y Padding)</h5>
      </div>
      <div class="card-body text-center">
        <div class="mb-4">
          <label class="d-block small text-muted text-uppercase mb-2">Colores Personalizados</label>
          <img src="<?= APP_URL ?>/service/barcode/render?s=code128&d=COLORS&sx=2&th=15&bc=E3F2FD&cm=1565C0&tc=0D47A1" class="barcode-example img-fluid border p-2 bg-body">
          <div class="mt-1 small text-muted">bc=E3F2FD (Fondo), cm=1565C0 (Barras)</div>
        </div>
        <div class="mb-0">
          <label class="d-block small text-muted text-uppercase mb-2">Padding Extendido</label>
          <img src="<?= APP_URL ?>/service/barcode/render?s=code128&d=PADDING&sx=2&th=15&p=40&bc=F5F5F5" class="barcode-example img-fluid border p-2 bg-body">
          <div class="mt-1 small text-muted">p=40 (40px de margen)</div>
        </div>
      </div>
    </div>

    <div class="card mb-3">
      <div class="card-header">
        <h5 class="card-title mb-0">Escalado y Texto</h5>
      </div>
      <div class="card-body text-center">
        <div class="mb-4">
          <label class="d-block small text-muted text-uppercase mb-2">Escala Horizontal (sx=4)</label>
          <img src="<?= APP_URL ?>/service/barcode/render?s=code128&d=LARGE&sx=4&th=15" class="barcode-example img-fluid border p-2 bg-body">
        </div>
        <div class="mb-0">
          <label class="d-block small text-muted text-uppercase mb-2">Texto en SVG (Fuentes)</label>
          <img src="<?= APP_URL ?>/service/barcode/render?f=svg&s=code128&d=CUSTOM-FONT&sx=2&th=20&tf=Verdana&ts=14" class="barcode-example img-fluid border p-2 bg-body">
          <div class="mt-1 small text-muted">tf=Verdana, ts=14 (Solo SVG)</div>
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
    <form id="barcodeForm">
      <div class="row g-3">
        <div class="col-md-4">
          <label class="form-label">Simbología</label>
          <select class="form-select" name="s" id="sym">
            <optgroup label="Lineal (1D)">
              <option value="code128">Code 128</option>
              <option value="code39">Code 39</option>
              <option value="ean13">EAN 13</option>
              <option value="ean8">EAN 8</option>
              <option value="upca">UPC-A</option>
              <option value="itf">ITF (Interleaved 2 of 5)</option>
            </optgroup>
            <optgroup label="Matricial (2D)">
              <option value="qr">Código QR</option>
              <option value="dmtx">DataMatrix</option>
            </optgroup>
          </select>
        </div>
        <div class="col-md-5">
          <label class="form-label">Dato a codificar</label>
          <input type="text" class="form-control" name="d" id="data" value="PHP-START-2026" required>
        </div>
        <div class="col-md-3 d-flex align-items-end">
          <button type="submit" class="btn btn-primary w-100 text-uppercase small fw-bold">
            <i class="fa-solid fa-sync me-2"></i> Generar
          </button>
        </div>
      </div>
    </form>

    <div class="mt-4 text-center">
      <div id="resultContainer" class="p-3 bg-body rounded border d-none">
        <img id="barcodeResult" src="" class="img-fluid mb-2">
        <div class="mt-2">
          <code id="barcodeUrl" class="small text-muted"></code>
        </div>
      </div>
    </div>
  </div>
</div>

<?php start_block("js") ?>
<script src="<?= APP_URL ?>/static/plugins/prismjs/prismjs.js"></script>
<script>
  document.getElementById('barcodeForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const sym = document.getElementById('sym').value;
    const data = encodeURIComponent(document.getElementById('data').value);
    const baseUrl = sym === 'qr' ? "<?= APP_URL ?>/service/qrcode/render" : "<?= APP_URL ?>/service/barcode/render";
    
    // Escala dinámica según el tipo
    const scale = sym === 'qr' ? `&sf=5` : (sym === 'dmtx' ? '&sf=5' : '&sx=2&th=15');
    const finalUrl = `${baseUrl}?s=${sym}&d=${data}${scale}`;

    const container = document.getElementById('resultContainer');
    const img = document.getElementById('barcodeResult');
    const code = document.getElementById('barcodeUrl');

    img.src = finalUrl;
    code.innerText = finalUrl;
    container.classList.remove('d-none');
  });
</script>
<?php end_block() ?>
