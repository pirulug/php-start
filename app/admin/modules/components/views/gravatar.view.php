<?php start_block("title") ?>
  Gravatar Demonstration
<?php end_block() ?>

<?php start_block('breadcrumb'); ?>
<?php render_breadcrumb([
  ['label' => 'Dashboard', 'link' => admin_route('dashboard')],
  ['label' => 'Componentes', 'link' => '#'],
  ['label' => 'Gravatar']
]) ?>
<?php end_block(); ?>

<?php start_block("css") ?>
  <link rel="stylesheet" href="<?= APP_URL ?>/static/plugins/prismjs/prismjs.css">
  <style>
    pre[class*="language-"] {
      border-radius: 8px;
      margin: 0;
    }
  </style>
<?php end_block() ?>

<?php start_block("js") ?>
  <script src="<?= APP_URL ?>/static/plugins/prismjs/prismjs.js"></script>
<?php end_block() ?>

<div class="row">
  <div class="col-md-4">
    <div class="card mb-3">
      <div class="card-header">
        <h5 class="card-title mb-0">Probar Gravatar</h5>
      </div>
      <div class="card-body">
        <form method="get">
          <div class="mb-3">
            <label class="form-label">Correo Electrónico</label>
            <input type="email" name="email" class="form-control" placeholder="ejemplo@correo.com" value="<?= htmlspecialchars($_GET['email'] ?? 'demo@pirulug.com') ?>" required>
            <div class="form-text">Ingresa un correo para ver su avatar.</div>
          </div>
          <div class="mb-3">
            <label class="form-label">Tamaño (px)</label>
            <input type="number" name="size" class="form-control" value="<?= htmlspecialchars($_GET['size'] ?? '150') ?>" min="1" max="2048">
          </div>
          <div class="d-grid">
            <button type="submit" class="btn btn-primary text-uppercase small fw-bold">
              <i class="fa-solid fa-sync me-2"></i> Actualizar Vista
            </button>
          </div>
        </form>
      </div>
    </div>

    <div class="card mb-3">
      <div class="card-header">
        <h5 class="card-title mb-0">Resultado</h5>
      </div>
      <div class="card-body text-center">
        <?php
        $email = $_GET['email'] ?? 'demo@pirulug.com';
        $size  = (int)($_GET['size'] ?? 150);
        
        // Uso estático simplificado
        echo Gravatar::email($email)
          ->size($size)
          ->attrs(['class' => 'img-fluid rounded-circle border p-1 bg-body', 'alt' => 'Avatar de prueba'])
          ->image();
        ?>
        <div class="mt-3">
          <code class="small"><?= htmlspecialchars(Gravatar::email($email)->size($size)->url()) ?></code>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-8">
    <div class="card">
      <div class="card-header">
        <h5 class="card-title mb-0">Estilos por Defecto (Fallbacks)</h5>
      </div>
      <div class="card-body">
        <p class="text-body opacity-75">Cuando el correo no tiene un avatar configurado en Gravatar, puedes elegir diferentes estilos generados automáticamente.</p>
        
        <div class="row g-3">
          <?php
          $fallbacks = [
            'mp'         => 'Mystery Person',
            'identicon'  => 'Identicon',
            'monsterid'  => 'MonsterID',
            'wavatar'    => 'Wavatar',
            'retro'      => 'Retro',
            'robohash'   => 'Robohash',
            'blank'      => 'Blank'
          ];

          foreach ($fallbacks as $key => $label):
          ?>
            <div class="col-md-4 col-6 text-center">
              <div class="p-3 border rounded bg-body">
                <?php
                // Uso estático en bucle
                echo Gravatar::email('nonexistent@pirulug.com')
                  ->size(80)
                  ->default($key)
                  ->attrs(['class' => 'rounded mb-2', 'title' => $label])
                  ->image();
                ?>
                <div class="fw-bold small text-uppercase"><?= $label ?></div>
                <code class="x-small"><?= $key ?></code>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

    <div class="card mt-3">
      <div class="card-header">
        <h5 class="card-title mb-0">Uso en Código</h5>
      </div>
      <div class="card-body p-0">
        <pre class="language-php"><code>// 1. Uso estático fluido (Recomendado)
echo Gravatar::email('usuario@correo.com')
             ->size(200)
             ->default('robohash')
             ->image();

// 2. Obtener solo la URL
$url = Gravatar::email('usuario@correo.com')->url();

// 3. Renderizado automático (Mágico)
$avatar = Gravatar::email('usuario@correo.com')->size(50);
echo $avatar; // Imprime la etiqueta &lt;img&gt; directamente</code></pre>
      </div>
    </div>
  </div>
</div>
