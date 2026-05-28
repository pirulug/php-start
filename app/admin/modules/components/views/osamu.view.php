<?php block_start("title"); ?>
Osamu WYSIWYG
<?php block_end(); ?>

<?php block_start("breadcrumb"); ?>
<?php render_breadcrumb([
  ["label" => "Dashboard", "link" => admin_route("dashboard")],
  ["label" => "Componentes"],
  ["label" => "Osamu WYSIWYG"]
]) ?>
<?php block_end(); ?>

<?php block_start("css"); ?>
<?= static_libs_css("osamujs", "osamu.css") ?>
<?= static_libs_css("prismjs", "prismjs.css") ?>
<?php block_end(); ?>

<?php block_start("js"); ?>
<?= static_libs_js("osamujs", "osamu.js") ?>
<?= static_libs_js("prismjs", "prismjs.js") ?>
<?= static_libs_js("liteyoutube", "liteyoutube.js") ?>
<script>
  document.addEventListener("DOMContentLoaded", () => {

    // Editor completo con todas las funcionalidades
    const editorFull = new Osamu("#editor_content", {
      placeholder: "Redacta el contenido de tu publicación utilizando texto enriquecido, enlaces e imágenes...",
      height: "360px",
      uploadUrl: "<?= admin_route('components/osamu/upload') ?>",
      liteYouTube: false,
      autoGrow: true,
      preset: "full"
    });

    // Editor básico: solo texto y listas (ideal para campos de descripción cortos)
    const editorBasic = new Osamu("#editor_excerpt", {
      placeholder: "Escribe un resumen breve del contenido...",
      height: "360px",
      preset: "basic"
    });

    // Ejemplo de API pública: getValue() y setValue()
    const btnGetValue = document.getElementById("btn-get-value");
    if (btnGetValue) {
      btnGetValue.addEventListener("click", () => {
        const html = editorFull.getValue();
        alert("Contenido del editor (primeros 200 caracteres):\n\n" + html.substring(0, 200));
      });
    }

  });
</script>
<?php block_end(); ?>

<?php
// Obtener el contenido guardado en la sesión
$saved_content = $_SESSION["osamu_demo_content"] ?? "";
// Limpiar de la sesión una vez obtenido para evitar que se quede fijo
unset($_SESSION["osamu_demo_content"]);
?>

<div class="row g-3">

  <!-- Tarjeta Informativa / API Reference -->
  <div class="col-lg-4">
    <div class="card">
      <div class="card-body">
        <h6 class="text-primary fw-bold mb-3 d-flex align-items-center gap-2">
          <i class="fa-solid fa-circle-info"></i>
          Osamu.js v2
        </h6>
        <p class="small text-secondary mb-3">
          Editor WYSIWYG nativo en Vanilla JS. Soporte multi-instancia, presets de toolbar configurables y API pública.
        </p>

        <hr class="my-3 opacity-10">

        <h6 class="fw-bold small text-uppercase text-secondary mb-2">Presets disponibles:</h6>
        <ul class="small ps-3 text-secondary mb-3">
          <li class="mb-1"><code>full</code> - Barra completa con todas las herramientas</li>
          <li class="mb-1"><code>content</code> - Sin codeBlock ni YouTube</li>
          <li class="mb-1"><code>basic</code> - Solo texto, listas y enlace</li>
          <li class="mb-1"><code>minimal</code> - Solo formato inline</li>
        </ul>

        <hr class="my-3 opacity-10">

        <h6 class="fw-bold small text-uppercase text-secondary mb-2">API Pública:</h6>
        <ul class="small ps-3 text-secondary mb-3">
          <li class="mb-1"><code>editor.getValue()</code> - Obtiene el HTML</li>
          <li class="mb-1"><code>editor.setValue(html)</code> - Carga HTML</li>
          <li class="mb-1"><code>editor.destroy()</code> - Desmonta el editor</li>
        </ul>

        <hr class="my-3 opacity-10">

        <h6 class="fw-bold small text-uppercase text-secondary mb-2">Opciones:</h6>
        <table class="table table-sm small text-secondary mb-0">
          <thead>
            <tr>
              <th>Opción</th>
              <th>Default</th>
            </tr>
          </thead>
          <tbody>
            <tr><td><code>preset</code></td><td><code>"full"</code></td></tr>
            <tr><td><code>toolbar</code></td><td>Array manual</td></tr>
            <tr><td><code>height</code></td><td><code>"300px"</code></td></tr>
            <tr><td><code>minHeight</code></td><td><code>"200px"</code></td></tr>
            <tr><td><code>autoGrow</code></td><td><code>false</code></td></tr>
            <tr><td><code>uploadUrl</code></td><td><code>null</code></td></tr>
            <tr><td><code>liteYouTube</code></td><td><code>false</code></td></tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Formulario del Editor -->
  <div class="col-lg-8">

    <form action="" method="POST">

      <!-- Editor FULL -->
      <div class="card mb-3">
        <div class="card-header d-flex align-items-center justify-content-between">
          <h6 class="mb-0 fw-bold d-flex align-items-center gap-2">
            <i class="fa-solid fa-pen-to-square text-primary"></i>
            Editor Completo
            <span class="badge bg-primary ms-1">preset: full</span>
          </h6>
          <button type="button" id="btn-get-value" class="btn btn-sm btn-outline-secondary text-uppercase fw-bold">
            <i class="fa-solid fa-eye me-1"></i>
            getValue()
          </button>
        </div>
        <div class="card-body">
          <label for="editor_content" class="form-label">Contenido <span class="text-danger">*</span></label>
          <textarea name="editor_content" id="editor_content" class="form-control"><?= clear_html($saved_content) ?></textarea>
        </div>
      </div>

      <!-- Editor BASIC (multi-instancia) -->
      <div class="card mb-3">
        <div class="card-header">
          <h6 class="mb-0 fw-bold d-flex align-items-center gap-2">
            <i class="fa-solid fa-align-left text-secondary"></i>
            Editor Básico (segunda instancia)
            <span class="badge bg-secondary ms-1">preset: basic</span>
          </h6>
        </div>
        <div class="card-body">
          <label for="editor_excerpt" class="form-label">Resumen / Extracto</label>
          <textarea name="editor_excerpt" id="editor_excerpt" class="form-control" ></textarea>
          <div class="form-text">Este editor comparte la misma página sin conflictos gracias al soporte multi-instancia.</div>
        </div>
      </div>

      <!-- Botonera -->
      <div class="bg-body p-3 rounded d-flex justify-content-end gap-2 sticky-bottom">
        <button type="submit" class="btn btn-primary px-5 text-uppercase small fw-bold">
          <i class="fa-solid fa-floppy-disk me-2"></i>
          Guardar y Procesar
        </button>
      </div>

    </form>

    <!-- Vista Previa del Resultado -->
    <?php if (!empty($saved_content)): ?>
      <div class="card border border-success border-opacity-25 mt-3">
        <div class="card-header d-flex align-items-center justify-content-between">
          <h6 class="mb-0 fw-bold text-success d-flex align-items-center gap-2">
            <i class="fa-solid fa-circle-check"></i>
            Resultado del Procesamiento en PHP
          </h6>
          <span class="badge bg-success">clear_textarea()</span>
        </div>
        <div class="card-body">
          <div class="mb-3">
            <label class="form-label small fw-bold text-secondary">Vista Previa Renderizada:</label>
            <div class="p-3 border rounded">
              <?= $saved_content ?>
            </div>
          </div>
          <div class="mb-0">
            <label class="form-label small fw-bold text-secondary">HTML Sanitizado:</label>
            <pre class="language-html"><code class="language-html"><?= htmlspecialchars($saved_content) ?></code></pre>
          </div>
        </div>
      </div>
    <?php endif; ?>

  </div>

</div>
