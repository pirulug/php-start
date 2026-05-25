<?php block_start("title") ?>
  Kiki - Automatización Silenciosa HTML a PDF/Imagen
<?php block_end() ?>

<?php block_start("breadcrumb"); ?>
<?php render_breadcrumb([
  ["label" => "Dashboard", "link" => admin_route("dashboard")],
  ["label" => "Componentes", "link" => admin_route("components/fpdf")],
  ["label" => "Kiki"]
]) ?>
<?php block_end(); ?>

<?php start_block("css") ?>
<?= static_libs_css("prismjs", "prismjs.css") ?>
<?php end_block() ?>

<?php start_block("js") ?>
<?= static_libs_js("prismjs", "prismjs.js") ?>
<script>
document.addEventListener("DOMContentLoaded", () => {
  const kikiButtons = document.querySelectorAll(".btn-danger, .btn-primary, .btn-dark");
  kikiButtons.forEach(btn => {
    // Solo interceptar si es un enlace de protocolo kiki://
    if (btn.tagName === "A" && btn.getAttribute("href").startsWith("kiki://")) {
      btn.addEventListener("click", (e) => {
        e.preventDefault();
        const url = btn.getAttribute("href");

        Swal.fire({
          title: "Iniciar Conversión",
          text: "¿Deseas procesar este comprobante con Kiki?",
          icon: "question",
          showCancelButton: true,
          confirmButtonColor: "#ff0055",
          cancelButtonColor: "#6c757d",
          confirmButtonText: "SÍ, CONVERTIR",
          cancelButtonText: "CANCELAR"
        }).then((result) => {
          if (result.isConfirmed) {
            // Mostrar SweetAlert con barra de carga/progreso simulada
            let timerInterval;
            Swal.fire({
              title: "Procesando documento...",
              html: "Invocando a Kiki de forma silenciosa.<br>Por favor, espera: <b></b> milisegundos.",
              timer: 3500,
              timerProgressBar: true,
              didOpen: () => {
                Swal.showLoading();
                const b = Swal.getHtmlContainer().querySelector("b");
                timerInterval = setInterval(() => {
                  b.textContent = Swal.getTimerLeft();
                }, 100);
              },
              willClose: () => {
                clearInterval(timerInterval);
              }
            }).then(() => {
              // Recargar la pagina para ver el nuevo archivo recibido
              window.location.reload();
            });

            // Invocar el protocolo kiki:// abriendo el link invisible
            window.location.href = url;
          }
        });
      });
    }
  });
});
</script>
<?php block_end() ?>

<div class="row">
  <div class="col-md-5">
    <div class="card mb-3">
      <div class="card-header">
        <h5 class="card-title m-0">Kiki (Protocolo kiki://)</h5>
      </div>
      <div class="card-body">
        <p class="card-text">
          <strong>Kiki</strong> es una herramienta silenciosa de escritorio para Windows. Permite capturar e imprimir cualquier vista HTML del sistema a PDF o Imagen (PNG/JPG) y enviarla directamente de vuelta al servidor por HTTP POST de forma automatizada.
        </p>

        <div class="alert alert-secondary border-0 bg-secondary-subtle text-secondary d-flex align-items-start gap-2 mb-3" role="alert">
          <i class="fa-solid fa-circle-info mt-1"></i>
          <div class="small">
            <strong>Instalacion</strong><br>
            Asegurate de haber ejecutado <code>python _kiki/build.py</code> para compilar y luego haber hecho doble clic en <code>_kiki/register_protocol.exe</code> para asociar el protocolo de Windows.
          </div>
        </div>

        <h6 class="fw-bold mb-2">Probar Conversión en Tiempo Real:</h6>
        <div class="d-grid gap-2">
          <!-- Convertir Factura a PDF -->
          <a href="<?= $kiki_link_pdf ?>" class="btn btn-danger text-uppercase small fw-bold">
            <i class="fa-solid fa-file-pdf me-2"></i>
            Convertir Factura a PDF
          </a>

          <!-- Convertir Factura a Imagen PNG -->
          <a href="<?= $kiki_link_png ?>" class="btn btn-primary text-uppercase small fw-bold">
            <i class="fa-solid fa-file-image me-2"></i>
            Convertir Factura a PNG (Imagen)
          </a>

          <!-- Convertir Factura a PDF Horizontal con Margenes en 0 -->
          <a href="<?= $kiki_link_landscape ?>" class="btn btn-dark text-uppercase small fw-bold">
            <i class="fa-solid fa-print me-2"></i>
            PDF Landscape ( args adicionales )
          </a>

          <hr class="my-2">

          <!-- Convertir Ticket 80mm a PDF (usando ancho especifico en wkhtmltopdf) -->
          <a href="<?= $kiki_link_ticket_pdf ?>" class="btn btn-danger text-uppercase small fw-bold">
            <i class="fa-solid fa-receipt me-2"></i>
            Ticket 80mm a PDF
          </a>

          <!-- Convertir Ticket 80mm a Imagen PNG (usando ancho especifico en wkhtmltoimage) -->
          <a href="<?= $kiki_link_ticket_png ?>" class="btn btn-primary text-uppercase small fw-bold">
            <i class="fa-solid fa-ticket me-2"></i>
            Ticket 80mm a PNG (Imagen)
          </a>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-7">
    <div class="card mb-3">
      <div class="card-header">
        <h5 class="card-title m-0">Entregas Recibidas en el Servidor</h5>
      </div>
      <div class="card-body p-0">
        <?php if (empty($kiki_files)): ?>
          <div class="p-3 text-center text-muted">
            <i class="fa-solid fa-folder-open d-block fs-3 mb-2"></i>
            No se han recibido archivos todavia.
          </div>
        <?php else: ?>
          <div class="table-responsive">
            <table class="table table-hover align-middle table-sm m-0">
              <thead>
                <tr>
                  <th class="ps-3 py-2">Nombre del Archivo</th>
                  <th class="py-2">Formato</th>
                  <th class="py-2">Tamaño</th>
                  <th class="py-2">Fecha de Recepción</th>
                  <th class="text-end pe-3 py-2">Acción</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($kiki_files as $file): ?>
                  <tr>
                    <td class="ps-3 py-3 font-monospace">
                      <?= htmlspecialchars($file['name']) ?>
                    </td>
                    <td>
                      <?php if ($file['ext'] === 'pdf'): ?>
                        <span class="badge bg-danger">PDF</span>
                      <?php else: ?>
                        <span class="badge bg-primary"><?= strtoupper($file['ext']) ?></span>
                      <?php endif; ?>
                    </td>
                    <td>
                      <?= format_number_decimal($file['size'] / 1024, 2) ?> KB
                    </td>
                    <td class="small text-muted">
                      <?= date('d/m/Y H:i:s', $file['date']) ?>
                    </td>
                    <td class="text-end pe-3">
                      <a href="<?= storage_uploads($file['name'], 'kiki') ?>" target="_blank" class="btn btn-outline-secondary btn-sm">
                        <i class="fa-solid fa-eye me-1"></i>
                        Ver
                      </a>
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

<div class="row mt-3">
  <div class="col-12">
    <div class="card mb-3">
      <div class="card-header">
        <h5 class="card-title m-0"><i class="fa-solid fa-code me-2"></i>Código de Ejemplo para Integración</h5>
      </div>
      <div class="card-body">
        <h6 class="fw-bold text-uppercase small text-muted">Ejemplo en PHP (Generación de Ruta/Link)</h6>
        <pre class="language-php"><code class="language-php">&lt;?php
// En el Action (kiki.action.php) construimos la URL usando APP_URL y cifrando el token:
$userId = $_SESSION['user_id'] ?? 0;
$expiry  = time() + 600;
$siteBase = rtrim(APP_URL, "/");
$uploadUrl = $siteBase . admin_route("components/kiki/upload");

// 1. Enlace para Factura A4 (PDF)
$fnPdf = "factura_venta.pdf";
$tkPdf = $cipher-&gt;encrypt($userId . ":" . $expiry . ":" . $fnPdf);
$kikiLinkPdf = "kiki://convert?" . http_build_query([
  "url"        =&gt; $siteBase . admin_route("components/_factura"),
  "upload_url" =&gt; $uploadUrl,
  "token"      =&gt; $tkPdf,
  "format"     =&gt; "pdf",
  "filename"   =&gt; $fnPdf,
  "args"       =&gt; "--margin-top 10 --margin-bottom 10 --margin-left 10 --margin-right 10"
]);

// 2. Enlace para Ticket de 80mm (PDF)
$fnTicket = "ticket_venta.pdf";
$tkTicket = $cipher-&gt;encrypt($userId . ":" . $expiry . ":" . $fnTicket);
$kikiLinkTicket = "kiki://convert?" . http_build_query([
  "url"        =&gt; $siteBase . admin_route("components/_ticket"),
  "upload_url" =&gt; $uploadUrl,
  "token"      =&gt; $tkTicket,
  "format"     =&gt; "pdf",
  "filename"   =&gt; $fnTicket,
  "args"       =&gt; "--page-width 80mm --page-height 200mm --margin-top 0 --margin-bottom 0 --margin-left 0 --margin-right 0"
]);

// 3. Enlace para Factura (Imagen PNG)
$fnPng = "factura_screenshot.png";
$tkPng = $cipher-&gt;encrypt($userId . ":" . $expiry . ":" . $fnPng);
$kikiLinkPng = "kiki://convert?" . http_build_query([
  "url"        =&gt; $siteBase . admin_route("components/_factura"),
  "upload_url" =&gt; $uploadUrl,
  "token"      =&gt; $tkPng,
  "format"     =&gt; "png",
  "filename"   =&gt; $fnPng
]);
?&gt;

&lt;!-- Render en la View (kiki.view.php) --&gt;
&lt;a href="&lt;?= $kikiLinkPdf ?&gt;" class="btn btn-danger"&gt;Imprimir Factura A4&lt;/a&gt;
&lt;a href="&lt;?= $kikiLinkTicket ?&gt;" class="btn btn-dark"&gt;Imprimir Ticket 80mm&lt;/a&gt;
&lt;a href="&lt;?= $kikiLinkPng ?&gt;" class="btn btn-primary"&gt;Capturar Imagen PNG&lt;/a&gt;</code></pre>

        <h6 class="fw-bold text-uppercase small text-muted mt-3">Ejemplo en JS (Invocación Directa)</h6>
        <pre class="language-javascript"><code class="language-javascript">// Si deseas disparar la conversión directamente con JavaScript:
const linkKiki = "&lt;?= $kiki_link_pdf ?&gt;";

// Redireccionar al protocolo lanzará Kiki silenciosamente
window.location.href = linkKiki;</code></pre>
      </div>
    </div>
  </div>
</div>

