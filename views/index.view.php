<?php $theme->blockStart("style"); ?>
<style>
  #animated-counter img {
    width: 20px;
    height: auto;
    margin: 0 2px;
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
  const data = <?= json_encode($stats); ?>;
  const totalVisits = data.all_time.toString(); 

  function updateCounter() {
    const counterContainer = document.getElementById('animated-counter');
    counterContainer.innerHTML = ''; 

    totalVisits.split('').forEach(digit => {
      const img = document.createElement('img');
      img.src = `<?= SITE_URL ?>/assets/img/numeros/${digit}.png`; 
      img.alt = digit; 
      img.style.transition = 'transform 0.3s ease'; 
      counterContainer.appendChild(img);
    });

    const images = counterContainer.querySelectorAll('img');
    images.forEach((img, index) => {
      setTimeout(() => {
        img.style.transform = 'scale(1.2)';
        setTimeout(() => {
          img.style.transform = 'scale(1)';
        }, 200);
      }, index * 100);
    });
  }

  updateCounter();
</script>
<?php $theme->blockEnd("script"); ?>

<?php require __DIR__ . "/_partials/top.partial.php"; ?>
<?php require __DIR__ . "/_partials/navbar.partial.php"; ?>

<div class="container">
  <div class="d-flex justify-content-center align-items-center">
    <div class="text-center">
      <h1 class="display-1 fw-bold"><?= SITE_NAME ?></h1>
      <p class="lead">Un sitio php para poder comenzar.</p>
    </div>
  </div>
</div>

<?php require __DIR__ . "/_partials/footer.partial.php"; ?>
<?php require __DIR__ . "/_partials/bottom.partial.php"; ?>