<?php start_block('title'); ?>
Editar <?= $policy->post_title ?>
<?php end_block(); ?>

<?php start_block('breadcrumb'); ?>
<?php render_breadcrumb([
  ['label' => 'Dashboard', 'link' => admin_route('dashboard')],
  ['label' => 'Políticas', 'link' => admin_route('policies')],
  ['label' => 'Editar']
]) ?>
<?php end_block(); ?>

<?php start_block('css'); ?>
<?= static_libs_css("simplemde", "simplemde.css") ?>
<?php end_block(); ?>

<?php start_block('js'); ?>
<?= static_libs_js("simplemde", "simplemde.js") ?>
<?= url_script_admin('policies', 'edit') ?>
<?php end_block(); ?>

<div class="row justify-content-center">
  <form action="" method="POST" id="policyForm">
    <div id="policy-editor-cfg" data-protected="<?= $is_protected ? 'true' : 'false' ?>"></div>

    <div class="card mb-3">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="card-title fw-bold mb-0 text-primary uppercase"><i class="fa-solid fa-file-pen me-2"></i>Editar
          Documento</h6>
        <span class="badge bg-secondary-subtle text-secondary small px-3 py-2">ID: #<?= $policy->post_id ?></span>
      </div>
      <div class="card-body">
        <div class="row g-3">
          <div class="col-md-7">
            <label class="ghost-label">Título de la Política</label>
            <input type="text" name="st_title" id="st_title" class="form-control fw-bold"
              value="<?= clear_html($_POST['st_title'] ?? $policy->post_title) ?>" required>
          </div>
          <div class="col-md-3">
            <label class="ghost-label">Tipo de Contenido</label>
            <select name="st_type" id="st_type" class="form-select">
              <option value="markdown" <?= ($_POST['st_type'] ?? $policy->policy_type) === 'markdown' ? 'selected' : '' ?>>Editor Markdown (H2+)
              </option>
              <option value="faq" <?= ($_POST['st_type'] ?? $policy->policy_type) === 'faq' ? 'selected' : '' ?>>Preguntas Frecuentes (FAQ)
              </option>
            </select>
          </div>
          <div class="col-md-2">
            <label class="ghost-label">Estado</label>
            <select name="st_status" class="form-select">
              <option value="1" <?= ($_POST['st_status'] ?? $policy->post_status) == 1 ? 'selected' : '' ?>>Activo</option>
              <option value="0" <?= ($_POST['st_status'] ?? $policy->post_status) == 0 ? 'selected' : '' ?>>Borrador</option>
            </select>
          </div>

          <div class="col-md-12">
            <label class="ghost-label text-secondary small">Slug / URL Amigable</label>
            <div class="input-group input-group-sm">
              <span class="input-group-text bg-body">/</span>
              <input type="text" name="st_slug" id="st_slug" class="form-control" value="<?= clear_html($_POST['st_slug'] ?? $policy->post_slug) ?>"
                <?= $is_protected ? 'readonly' : '' ?>>
              <?php if ($is_protected): ?>
                <button class="btn btn-outline-warning" type="button" id="unlock-slug" title="Desbloquear para corregir">
                  <i class="fa-solid fa-lock"></i>
                </button>
              <?php endif; ?>
            </div>
            <?php if ($is_protected): ?>
              <div class="form-text text-info small mt-1" id="slug-lock-msg">
                <i class="fa-solid fa-circle-info me-1"></i> Este slug es crítico. Haz clic en el candado si necesitas corregirlo.
              </div>
            <?php endif; ?>

          </div>

          <div class="col-md-12 mt-3 pt-3 border-top">
            <!-- EDITOR MARKDOWN -->
            <div id="editor-markdown" style="<?= $policy->policy_type === 'faq' ? 'display:none;' : '' ?>">
              <div class="d-flex justify-content-between align-items-center mb-3">
                <label class="fw-bold small text-uppercase text-secondary m-0">Contenido del Documento</label>
                <span
                  class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 small text-uppercase">MODO
                  MARKDOWN</span>
              </div>
              <textarea id="simplemde"
                name="st_content"><?= clear_html($_POST['st_content'] ?? ($policy->policy_type === 'markdown' ? $policy->post_content : '')) ?></textarea>
            </div>

            <!-- EDITOR FAQ -->
            <div id="editor-faq" style="<?= $policy->policy_type === 'markdown' ? 'display:none;' : '' ?>">
              <div class="d-flex justify-content-between align-items-center mb-3">
                <label class="fw-bold small text-uppercase text-secondary m-0">Preguntas y Respuestas</label>
                <span
                  class="badge bg-purple-subtle text-purple border border-purple-subtle px-3 py-2 small text-uppercase">MODO
                  FAQ</span>
              </div>

              <div id="faq-container">
                <?php if ($policy->policy_type === 'faq' && !empty($faqs)): ?>
                  <?php foreach ($faqs as $i => $f): ?>
                    <div class="faq-item fade-in">
                      <div class="faq-header">
                        <span class="faq-number">Pregunta #<?= $i + 1 ?></span>
                        <button type="button" class="btn btn-sm faq-remove" title="Eliminar"><i
                            class="fa-solid fa-trash-can"></i></button>
                      </div>
                      <div class="faq-body">
                        <div class="mb-3">
                          <label class="ghost-label small">Pregunta</label>
                          <input type="text" name="faq_q[]" class="form-control fw-bold bg-body"
                            placeholder="¿Cómo podemos ayudarte?" value="<?= htmlspecialchars($f['q']) ?>">
                        </div>
                        <div>
                          <label class="ghost-label small">Respuesta</label>
                          <textarea name="faq_a[]" class="form-control bg-body" rows="3"
                            placeholder="Escribe la respuesta detallada aquí..."><?= htmlspecialchars($f['a']) ?></textarea>
                        </div>
                      </div>
                    </div>
                  <?php endforeach; ?>
                <?php else: ?>
                  <div class="faq-item fade-in">
                    <div class="faq-header">
                      <span class="faq-number">Pregunta #1</span>
                      <button type="button" class="btn btn-sm faq-remove" title="Eliminar"><i
                          class="fa-solid fa-trash-can"></i></button>
                    </div>
                    <div class="faq-body">
                      <div class="mb-3">
                        <label class="ghost-label small">Pregunta</label>
                        <input type="text" name="faq_q[]" class="form-control fw-bold bg-body"
                          placeholder="¿Cómo podemos ayudarte?">
                      </div>
                      <div>
                        <label class="ghost-label small">Respuesta</label>
                        <textarea name="faq_a[]" class="form-control bg-body" rows="3"
                          placeholder="Escribe la respuesta detallada aquí..."></textarea>
                      </div>
                    </div>
                  </div>
                <?php endif; ?>
              </div>

              <div class="text-center mt-3 pt-3 border-top">
                <button type="button" id="add-faq"
                  class="btn btn-outline-primary btn-sm px-4 fw-bold text-uppercase small">
                  <i class="fa-solid fa-plus me-2"></i>Añadir Nueva Pregunta
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="bg-body p-3 rounded d-flex justify-content-end gap-2 sticky-bottom">
      <a href="<?= admin_route('policies') ?>" class="btn btn-outline-secondary px-4 text-uppercase small fw-bold">
        <i class="fa-solid fa-arrow-left me-2"></i>
        Cancelar
      </a>
      <button type="submit" class="btn btn-primary px-5 text-uppercase small fw-bold">
        <i class="fa-solid fa-floppy-disk me-2"></i>
        Guardar Cambios
      </button>
    </div>
  </form>
</div>