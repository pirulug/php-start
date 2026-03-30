<?php start_block('title'); ?>
¡Bienvenido a <?= $config->get('site_name', 'PHP-Start') ?>!
<?php end_block(); ?>

<?php start_block('css'); ?>
<style>
  .hero-section {
    padding: 100px 0;
    background: linear-gradient(135deg, rgba(13, 110, 253, 0.05) 0%, rgba(13, 202, 240, 0.05) 100%);
    border-bottom: 1px solid rgba(0,0,0,0.05);
  }
  .feature-icon {
    width: 64px;
    height: 64px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 16px;
    margin-bottom: 1.5rem;
  }
  .card-feature {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
  }
  .card-feature:hover {
    transform: translateY(-10px);
  }
</style>
<?php end_block(); ?>

<div class="hero-section">
  <div class="container text-center">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <h1 class="display-3 fw-bold mb-4">Potencia tu proyecto con <span class="text-primary"><?= $config->get('site_name', 'PHP-Start') ?></span></h1>
        <p class="lead text-muted mb-5">Un framework minimalista, rápido y seguro diseñado para que te enfoques en crear, no en configurar. La base perfecta para tu próxima gran idea.</p>
        
        <div class="d-flex flex-column flex-sm-row justify-content-center gap-3">
          <a href="<?= home_route('signup') ?>" class="btn btn-primary btn-lg px-5 py-3 fw-bold rounded-pill">
            <i class="fa-solid fa-rocket me-2"></i> Empezar Ahora
          </a>
          <a href="<?= home_route('signin') ?>" class="btn btn-outline-primary btn-lg px-5 py-3 fw-bold rounded-pill">
            Inicia Sesión
          </a>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="container py-5">
  <div class="row g-4 py-5 text-center">
    
    <div class="col-md-4">
      <div class="card h-100 p-4 card-feature rounded-4">
        <div class="feature-icon bg-primary bg-opacity-10 text-primary mx-auto">
          <i class="fa-solid fa-bolt fs-2"></i>
        </div>
        <h3 class="fw-bold h4">Velocidad Extrema</h3>
        <p class="text-muted small">Optimizado para tiempos de carga mínimos y una respuesta instantánea en cada interacción.</p>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card h-100 p-4 card-feature rounded-4">
        <div class="feature-icon bg-success bg-opacity-10 text-success mx-auto">
          <i class="fa-solid fa-shield-halved fs-2"></i>
        </div>
        <h3 class="fw-bold h4">Seguridad Robusta</h3>
        <p class="text-muted small">Protección nativa contra XSS, CSRF e inyección SQL con un sistema de cifrado avanzado.</p>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card h-100 p-4 card-feature rounded-4">
        <div class="feature-icon bg-info bg-opacity-10 text-info mx-auto">
          <i class="fa-solid fa-code fs-2"></i>
        </div>
        <h3 class="fw-bold h4">Arquitectura Limpia</h3>
        <p class="text-muted small">Patrón Action-View estricto que mantiene tu código organizado y fácil de mantener.</p>
      </div>
    </div>

  </div>
</div>

<?php if (!isset($_COOKIE['cookies_accepted'])): ?>
  <div id="cookie-banner" class="fixed-bottom p-3 mb-3 mx-auto bg-body rounded-4 border" style="z-index: 1050; max-width: 900px; width: 95%;">
    <div class="container-fluid">
      <div class="row align-items-center g-3">
        <div class="col-md-8 text-center text-md-start">
          <p class="mb-0 small">
            <strong>🍪 Tu privacidad es importante</strong>. Usamos cookies para mejorar tu experiencia. 
            <a href="#" class="text-primary text-decoration-none fw-bold">Ver política</a>.
          </p>
        </div>
        <div class="col-md-4 text-center text-md-end">
          <button onclick="handleCookies('true')" class="btn btn-primary btn-sm px-4 rounded-pill fw-bold">Aceptar todas</button>
          <button onclick="handleCookies('false')" class="btn btn-link text-muted btn-sm text-decoration-none">Rechazar</button>
        </div>
      </div>
    </div>
  </div>
<?php endif; ?>

<script>
  function handleCookies(accepted) {
    const date = new Date();
    date.setTime(date.getTime() + (365 * 24 * 60 * 60 * 1000));
    document.cookie = "cookies_accepted=" + accepted + "; expires=" + date.toUTCString() + "; path=/";
    
    const banner = document.getElementById("cookie-banner");
    banner.classList.add('animate__animated', 'animate__fadeOutDown');
    setTimeout(() => { banner.style.display = "none"; }, 500);
  }
</script>