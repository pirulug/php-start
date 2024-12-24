<?php $theme->blockStart("style"); ?>
<style>
  #animated-counter img {
    width: 20px;
    /* Ajusta el tamaño de los dígitos */
    height: auto;
    margin: 0 2px;
    /* Espaciado entre imágenes */
    display: inline-block;
  }

  #animated-counter img:hover {
    transform: scale(1.1);
    transition: transform 0.3s ease;
  }
</style>
<?php $theme->blockEnd("style"); ?>

<?php $theme->blockStart("script"); ?>
<script>
  // Datos generados por PHP
  const data = <?= json_encode($stats); ?>;
  const totalVisits = data.all_time.toString(); // Convertir el número total en string

  // Actualizar el contador con imágenes
  function updateCounter() {
    const counterContainer = document.getElementById('animated-counter');
    counterContainer.innerHTML = ''; // Limpiar el contenido actual

    // Dividir el número en dígitos individuales y crear las imágenes
    totalVisits.split('').forEach(digit => {
      const img = document.createElement('img');
      img.src = `<?= SITE_URL ?>/assets/img/numeros/${digit}.png`; // Ruta de las imágenes
      img.alt = digit; // Texto alternativo para accesibilidad
      img.style.transition = 'transform 0.3s ease'; // Transición animada
      counterContainer.appendChild(img);
    });

    // Aplicar una animación a los números
    const images = counterContainer.querySelectorAll('img');
    images.forEach((img, index) => {
      setTimeout(() => {
        img.style.transform = 'scale(1.2)';
        setTimeout(() => {
          img.style.transform = 'scale(1)';
        }, 200);
      }, index * 100); // Escalonar animaciones para cada número
    });
  }

  // Llamar a la función al cargar la página
  updateCounter();
</script>
<?php $theme->blockEnd("script"); ?>

<?php require __DIR__ . "/partials/top.partial.php"; ?>
<?php require __DIR__ . "/partials/navbar.partial.php"; ?>

<div class="container">
  <div class="d-flex justify-content-center align-items-center">
    <div class="text-center">
      <h1 class="display-1 fw-bold"><?= SITE_NAME ?></h1>
      <p class="lead">Un sitio php para poder comenzar.</p>
    </div>
  </div>
</div>

<?php require __DIR__ . "/partials/footer.partial.php"; ?>
<?php require __DIR__ . "/partials/bottom.partial.php"; ?>