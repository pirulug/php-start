<?php start_block('title'); ?>
Index
<?php end_block(); ?>

<?php start_block('css'); ?>
<style>
  .hero-section {
    padding: 120px 0;
    background: radial-gradient(circle at top right, rgba(var(--pr-primary-rgb), 0.08), transparent 40%),
      radial-gradient(circle at bottom left, rgba(var(--pr-info-rgb), 0.08), transparent 40%);
    border-bottom: 1px solid var(--pr-border-color-translucent);
  }

  .feature-icon {
    width: 70px;
    height: 70px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 20px;
    margin-bottom: 1.5rem;
    transition: all 0.3s ease;
  }

  .card-feature {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border: 1px solid var(--pr-border-color-translucent);
  }

  .card-feature:hover {
    transform: translateY(-10px);
    border-color: var(--pr-primary);
  }

  .card-feature:hover .feature-icon {
    transform: scale(1.1) rotate(5deg);
  }
</style>
<?php end_block(); ?>

<?php start_block('js'); ?>
<script>
  function handleCookies(accepted) {
    const date = new Date();
    date.setTime(date.getTime() + (365 * 24 * 60 * 60 * 1000));
    document.cookie = "cookies_accepted=" + accepted + "; expires=" + date.toUTCString() + "; path=/";

    const banner = document.getElementById("cookie-banner");
    banner.style.transition = "all 0.5s ease";
    banner.style.opacity = "0";
    banner.style.transform = "translateY(100px)";
    setTimeout(() => { banner.style.display = "none"; }, 500);
  }
</script>
<?php end_block(); ?>

<div class="hero-section">
  <div class="container text-center">
    <div class="row justify-content-center">
      <div class="col-lg-9">
        <span class="badge bg-primary-subtle text-primary px-3 py-2 text-uppercase fw-bold mb-4"
          style="letter-spacing: 1px; font-size: 0.7rem;">
          <i class="fa-solid fa-sparkles me-2"></i> Framework PHP Minimalista
        </span>
        <h1 class="display-2 fw-bold mb-4">Potencia tu proyecto con <span
            class="text-primary"><?= $config->get('site_name', 'PHP-Start') ?></span></h1>
        <p class="lead text-body-secondary mb-5 px-lg-5">
          Un framework diseñado para que te enfoques en crear, no en configurar.
          Rápido, seguro y con una arquitectura limpia para tu próxima gran idea.
        </p>

        <div class="d-flex flex-column flex-sm-row justify-content-center gap-3">
          <a href="<?= front_route('signup') ?>" class="btn btn-primary btn-lg px-5 py-3 text-uppercase small fw-bold">
            <i class="fa-solid fa-rocket me-2"></i> Empezar Ahora
          </a>
          <a href="<?= front_route('signin') ?>"
            class="btn btn-outline-secondary btn-lg px-5 py-3 text-uppercase small fw-bold">
            Inicia Sesión
          </a>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="container py-5">
  <div class="row g-4 py-5">

    <div class="col-md-4">
      <div class="card h-100 p-4 card-feature">
        <div class="feature-icon bg-primary-subtle text-primary">
          <i class="fa-solid fa-bolt fs-2"></i>
        </div>
        <h4 class="fw-bold mb-2 text-uppercase small" style="letter-spacing: 0.5px;">Velocidad Extrema</h4>
        <p class="text-body-secondary small mb-0">Optimizado para tiempos de carga mínimos y una respuesta instantánea
          en cada interacción.</p>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card h-100 p-4 card-feature">
        <div class="feature-icon bg-success-subtle text-success">
          <i class="fa-solid fa-shield-halved fs-2"></i>
        </div>
        <h4 class="fw-bold mb-2 text-uppercase small" style="letter-spacing: 0.5px;">Seguridad Robusta</h4>
        <p class="text-body-secondary small mb-0">Protección nativa contra XSS, CSRF e inyección SQL con un sistema de
          cifrado avanzado.</p>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card h-100 p-4 card-feature">
        <div class="feature-icon bg-info-subtle text-info">
          <i class="fa-solid fa-code fs-2"></i>
        </div>
        <h4 class="fw-bold mb-2 text-uppercase small" style="letter-spacing: 0.5px;">Arquitectura Limpia</h4>
        <p class="text-body-secondary small mb-0">Patrón Action-View estricto que mantiene tu código organizado y fácil
          de mantener.</p>
      </div>
    </div>

  </div>
</div>

<?php if (!isset($_COOKIE['cookies_accepted'])): ?>
  <div id="cookie-banner" class="fixed-bottom p-3 mb-3 mx-auto bg-body border shadow-lg"
    style="z-index: 1050; max-width: 800px; width: 95%; border-radius: 20px;">
    <div class="container-fluid">
      <div class="row align-items-center g-3">
        <div class="col-md-8 text-center text-md-start">
          <p class="mb-0 small text-body-secondary">
            <i class="fa-solid fa-cookie-bite text-warning me-2 fs-5"></i>
            <strong>Tu privacidad es importante</strong>. Usamos cookies para mejorar tu experiencia.
            <a href="#" class="text-primary text-decoration-none fw-bold">Ver política</a>.
          </p>
        </div>
        <div class="col-md-4 text-center text-md-end">
          <button onclick="handleCookies('true')"
            class="btn btn-primary btn-sm px-4 text-uppercase small fw-bold">Aceptar</button>
          <button onclick="handleCookies('false')"
            class="btn btn-link text-body-secondary btn-sm text-decoration-none small">Rechazar</button>
        </div>
      </div>
    </div>
  </div>
<?php endif; ?>