<?php start_block('title'); ?>
<?= $policy->post_title ?>
<?php end_block(); ?>

<div class="container py-3">
  <div class="row justify-content-center">
    <div class="col-lg-9">

      <!-- HEADER PREMIUM -->
      <div class="card mb-3 overflow-hidden"
        style="background: linear-gradient(135deg, var(--pr-primary) 0%, #6610f2 100%); border: none;">
        <div class="card-body p-3 p-md-4 text-center">
          <div
            class="d-inline-flex align-items-center justify-content-center bg-white bg-opacity-25 rounded-circle mb-3"
            style="width: 60px; height: 60px;">
            <i class="fa-solid <?= $is_faq ? 'fa-circle-question' : 'fa-file-shield' ?> text-white fs-2"></i>
          </div>
          <h1 class="fw-bold text-white mb-1 text-uppercase small" style="letter-spacing: 2px;">
            <?= $is_faq ? 'Preguntas Frecuentes' : 'Legal / Políticas' ?></h1>
          <h2 class="display-6 fw-bold text-white mb-0"><?= $policy->post_title ?></h2>
        </div>
      </div>

      <div class="card mb-3">
        <div class="card-body p-3 p-md-4">

          <div
            class="d-flex align-items-center gap-2 text-body-secondary small mb-4 pb-3 border-bottom border-opacity-10">
            <i class="fa-regular fa-clock"></i>
            <span class="text-uppercase fw-bold" style="font-size: 0.7rem; letter-spacing: 0.5px;">Última actualización:
              <?= format_date($policy->post_updated_at) ?></span>
          </div>

          <div class="policy-content" style="line-height: 1.8; font-size: 1.05rem;">
            <?php if ($is_faq): ?>
              <!-- ACCORDION FOR FAQ -->
              <div class="accordion accordion-flush" id="faqAccordion">
                <?php foreach ($faqs as $i => $f): ?>
                  <div class="accordion-item bg-transparent border-bottom border-opacity-10 py-2">
                    <h2 class="accordion-header">
                      <button
                        class="accordion-button <?= $i === 0 ? '' : 'collapsed' ?> bg-transparent fw-bold shadow-none text-body"
                        type="button" data-bs-toggle="collapse" data-bs-target="#faq-<?= $i ?>">
                        <?= htmlspecialchars($f['q']) ?>
                      </button>
                    </h2>
                    <div id="faq-<?= $i ?>" class="accordion-collapse collapse <?= $i === 0 ? 'show' : '' ?>"
                      data-bs-parent="#faqAccordion">
                      <div class="accordion-body text-body-secondary">
                        <?= nl2br(htmlspecialchars($f['a'])) ?>
                      </div>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
            <?php else: ?>
              <!-- MARKDOWN RENDER -->
              <div id="markdown-render" class="richtext-render">
                <textarea id="markdown-source" style="display:none;"><?= $policy->post_content ?></textarea>
                <div class="text-center py-5 opacity-25">
                  <div class="spinner-border text-primary" role="status"></div>
                </div>
              </div>
            <?php endif; ?>
          </div>

        </div>
      </div>

      <!-- BOTONERA FUERA DEL CARD (REGLA 4) -->
      <div class="bg-body p-3 rounded d-flex justify-content-center sticky-bottom mt-3">
        <a href="<?= front_route() ?>" class="btn btn-outline-secondary px-5 text-uppercase small fw-bold">
          <i class="fa-solid fa-arrow-left me-2"></i> Volver al Inicio
        </a>
      </div>

    </div>
  </div>
</div>

<style>
  .richtext-render {
    color: var(--pr-body-color);
  }

  .richtext-render h1,
  .richtext-render h2,
  .richtext-render h3 {
    margin-top: 2rem;
    margin-bottom: 1.25rem;
    font-weight: 800;
    color: var(--pr-primary);
    text-transform: uppercase;
    font-size: 1.25rem;
    letter-spacing: 0.5px;
  }

  .richtext-render p {
    margin-bottom: 1.25rem;
  }

  .richtext-render ul,
  .richtext-render ol {
    margin-bottom: 1.5rem;
    padding-left: 1.25rem;
  }

  .richtext-render li {
    margin-bottom: 0.5rem;
  }

  .accordion-button:not(.collapsed) {
    color: var(--pr-primary) !important;
    background-color: transparent !important;
  }

  .accordion-button::after {
    filter: grayscale(1) opacity(0.5);
  }
</style>

<?php if (!$is_faq): ?>
  <?php start_block('js'); ?>
  <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const source = document.getElementById('markdown-source').value;
      const renderDiv = document.getElementById('markdown-render');
      renderDiv.innerHTML = marked.parse(source);
    });
  </script>
  <?php end_block(); ?>
<?php endif; ?>