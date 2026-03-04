<?php start_block('title'); ?>
Home
<?php end_block(); ?>

<div class="container my-3">
  <div class="card">
    <div class="card-body">
      <h5 class="card-title">Card title</h5>
      <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card
        content.</p>
      <a href="#" class="btn btn-primary">Go somewhere</a>
    </div>
  </div>
</div>

<?php
$cookie_handled = isset($_COOKIE['cookies_accepted']);
?>

<?php if (!$cookie_handled): ?>
  <div id="cookie-banner" class="fixed-bottom p-4 bg-body" style="z-index: 1050;">
    <div class="container">
      <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">

        <div class="text-center text-md-start">
          <p class="mb-0">
            <strong>🍪 Tu privacidad es importante</strong><br>
            Usamos cookies para mejorar tu experiencia y analizar nuestro tráfico.
            <a href="/politicas-de-privacidad" class="text-info text-decoration-none">Leer más sobre nuestra política</a>.
          </p>
        </div>

        <div class="d-flex gap-2 w-100 w-md-auto justify-content-end">
          <button onclick="handleCookies('false')" class="btn btn-outline-secondary px-4">Rechazar</button>
          <button onclick="handleCookies('true')" class="btn btn-primary px-4">Aceptar todas</button>
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

    banner.style.opacity = '0';
    setTimeout(() => {
      banner.style.display = "none";
    }, 300); 
  }
</script>

<style>
  #cookie-banner {
    transition: opacity 0.3s ease-in-out;
  }
</style>