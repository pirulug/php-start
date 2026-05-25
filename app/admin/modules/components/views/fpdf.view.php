<?php start_block("title") ?>
Generación de PDF (FPDF)
<?php end_block() ?>

<?php start_block('breadcrumb'); ?>
<?php render_breadcrumb([
  ['label' => 'Dashboard', 'link' => admin_route('dashboard')],
  ['label' => 'Componentes', 'link' => admin_route('components/captcha')],
  ['label' => 'Generación de PDF']
]) ?>
<?php end_block(); ?>

<?php start_block("css") ?>
<?= static_libs_css("prismjs", "prismjs.css") ?>
<?php end_block() ?>

<?php start_block("js") ?>
<?= static_libs_js("prismjs", "prismjs.js") ?>
<?php end_block() ?>

<div class="row g-3">
  <!-- SECCIÓN FPDF -->
  <div class="col-12">
    <div class="card mb-3">
      <div class="card-header">
        <h5 class="card-title mb-0">Generación Nativa con FPDF</h5>
      </div>
      <div class="card-body">
        <p>El sistema integra <strong>FPDF</strong>, una clase PHP que permite generar archivos PDF de forma nativa desde el servidor sin depender de ejecutables externos ni permisos especiales de comandos.</p>

        <div class="alert alert-info border-0 bg-info-subtle text-info d-flex align-items-center gap-2 mb-3" role="alert">
          <i class="fa-solid fa-circle-info"></i>
          <span class="small">Ideal para reportes simples, facturas básicas y tickets rápidos en cualquier tipo de hosting.</span>
        </div>

        <h6 class="fw-bold mb-2">Ejemplo de código PHP:</h6>
        <pre class="mb-3"><code class="language-php">require_once BASE_DIR . '/core/vendors/fpdf/fpdf.php';

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(40, 10, '¡Hola Mundo!');
$pdf->Output('I', 'ejemplo.pdf');</code></pre>

        <h6 class="fw-bold mb-2">Probar en tiempo real:</h6>
        <div class="d-flex gap-2 col-md-6">
          <a href="<?= admin_route('components/fpdf/render/invoice.pdf') ?>" target="_blank" class="btn btn-primary text-uppercase small fw-bold flex-fill">
            <i class="fa-solid fa-file-invoice me-2"></i> Factura (A4)
          </a>
          <a href="<?= admin_route('components/fpdf/render/ticket.pdf') ?>" target="_blank" class="btn btn-outline-secondary text-uppercase small fw-bold flex-fill">
            <i class="fa-solid fa-receipt me-2"></i> Ticket (80mm)
          </a>
        </div>
      </div>
    </div>
  </div>
</div>