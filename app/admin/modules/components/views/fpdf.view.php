<?php start_block("title") ?>
Demostración de FPDF
<?php end_block() ?>

<?php start_block('breadcrumb'); ?>
<?php render_breadcrumb([
  ['label' => 'Dashboard', 'link' => admin_route('dashboard')],
  ['label' => 'Componentes', 'link' => admin_route('componentes/captcha')],
  ['label' => 'Generación de PDF']
]) ?>
<?php end_block(); ?>

<?php start_block("css") ?>
<link rel="stylesheet" href="<?= APP_URL ?>/static/plugins/prismjs/prismjs.css">
<?php end_block() ?>

<div class="row">
  <div class="col-12">
    <div class="card mb-3">
      <div class="card-header">
        <h5 class="card-title mb-0">Generación de PDF con FPDF</h5>
      </div>
      <div class="card-body">
        <p>El sistema integra <strong>FPDF</strong>, una clase PHP que permite generar archivos PDF de forma nativa sin
          depender de librerías externas complejas.</p>

        <div class="alert alert-info border-0 bg-light-info text-info">
          <i class="fa-solid fa-circle-info me-2"></i> Esta implementación se ejecuta directamente desde el módulo de
          componentes, demostrando cómo generar documentos dinámicos (facturas, reportes, carnets) de manera eficiente.
        </div>

        <h6>Ejemplo de implementación técnica:</h6>
        <pre><code class="language-php">require_once BASE_DIR . '/core/vendors/fpdf/fpdf.php';

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(40, 10, '¡Hola Mundo!');
$pdf->Output('I', 'ejemplo.pdf');</code></pre>
      </div>
    </div>
  </div>

  <div class="col-md-6">
    <div class="card mb-3">
      <div class="card-header">
        <h5 class="card-title mb-0">Acciones Disponibles</h5>
      </div>
      <div class="card-body">
        <p class="text-muted small">Haz clic en los botones para probar la generación en tiempo real:</p>

        <div class="d-grid gap-2">
          <a href="<?= admin_route('components/fpdf/render/invoice.pdf') ?>" target="_blank"
            class="btn btn-primary text-uppercase small fw-bold">
            <i class="fa-solid fa-file-invoice me-2"></i> Generar Factura (A4)
          </a>
          <a href="<?= admin_route('components/fpdf/render/ticket.pdf') ?>" target="_blank"
            class="btn btn-outline-secondary text-uppercase small fw-bold">
            <i class="fa-solid fa-receipt me-2"></i> Generar Ticket (80mm)
          </a>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-6">
    <div class="card mb-3">
      <div class="card-header">
        <h5 class="card-title mb-0">Características de FPDF</h5>
      </div>
      <div class="card-body">
        <ul class="list-group list-group-flush small">
          <li class="list-group-item bg-transparent px-0"><i class="fa-solid fa-check text-success me-2"></i> Elección
            de unidad de medida y formato de página.</li>
          <li class="list-group-item bg-transparent px-0"><i class="fa-solid fa-check text-success me-2"></i> Gestión de
            cabeceras y pies de página automáticos.</li>
          <li class="list-group-item bg-transparent px-0"><i class="fa-solid fa-check text-success me-2"></i> Salto de
            página y de línea automáticos.</li>
          <li class="list-group-item bg-transparent px-0"><i class="fa-solid fa-check text-success me-2"></i> Soporte
            para imágenes (JPEG, PNG y GIF).</li>
          <li class="list-group-item bg-transparent px-0"><i class="fa-solid fa-check text-success me-2"></i> Soporte
            para colores y enlaces.</li>
        </ul>
      </div>
    </div>
  </div>
</div>

<?php start_block("js") ?>
<script src="<?= APP_URL ?>/static/plugins/prismjs/prismjs.js"></script>
<?php end_block() ?>