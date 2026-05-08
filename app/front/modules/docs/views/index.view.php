<?php start_block('title') ?>
  Documentación - <?= clear_html($page) ?>
<?php end_block() ?>

<?php start_block('css') ?>
<?= static_libs_css("prismjs","prismjs.css") ?>
<style>
  .docs-sidebar {
    position: sticky;
    top: 1rem;
    max-height: calc(100vh - 2rem);
    overflow-y: auto;
  }
  .docs-content {
    line-height: 1.8;
    font-size: 1.05rem;
  }
  .docs-content h1, .docs-content h2, .docs-content h3 {
    margin-top: 2rem;
    margin-bottom: 1rem;
    font-weight: 700;
  }
  .docs-menu-item {
    transition: all 0.2s ease;
    padding: 8px 12px;
    display: block;
    text-decoration: none;
    color: var(--pr-secondary-color);
    border-radius: 8px;
    font-size: 0.9rem;
    font-weight: 500;
  }
  .docs-menu-item:hover {
    background: rgba(var(--pr-primary-rgb), 0.1);
    color: var(--pr-primary);
    padding-left: 18px;
  }
  .docs-menu-item.active {
    background: var(--pr-primary);
    color: #fff !important;
    box-shadow: 0 4px 12px rgba(var(--pr-primary-rgb), 0.3);
  }
</style>
<?php end_block() ?>

<?php start_block('js') ?>
<?= static_libs_js("prismjs","prismjs.js") ?>
<?php end_block() ?>

<div class="container py-3">
  <div class="row g-3">
    <!-- Menú Lateral -->
    <div class="col-md-3">
      <div class="docs-sidebar">
        <div class="card h-100">
          <div class="card-body">
            <h6 class="fw-bold text-uppercase small mb-3 text-secondary px-2">Librerías Core</h6>
            <div class="d-flex flex-column gap-1">
              <?php foreach ($docs_menu as $item): ?>
                <a href="<?= $item['link'] ?>" class="docs-menu-item <?= $item['active'] ? 'active' : '' ?>">
                  <i class="fa-solid fa-book-open me-2 opacity-50"></i>
                  <?= clear_html($item['label']) ?>
                </a>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Contenido -->
    <div class="col-md-9">
      <div class="card h-100">
        <div class="card-body p-md-5 docs-content">
          <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="<?= front_route('docs') ?>">Docs</a></li>
              <li class="breadcrumb-item active" aria-current="page"><?= clear_html($page) ?></li>
            </ol>
          </nav>
          
          <div class="markdown-body">
            <?= $html_content ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
