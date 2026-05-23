<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="theme-color" content="#ff0055">
  <?php render_seo_meta("index, follow"); ?>

  <?php if ($config->favicon()): ?>
      <link rel="apple-touch-icon" sizes="180x180"
        href="<?= APP_URL ?>/storage/uploads/site/favicons/<?= $config->favicon()->{'apple-touch-icon'} ?>">
      <link rel="icon" type="image/png" sizes="32x32"
        href="<?= APP_URL ?>/storage/uploads/site/favicons/<?= $config->favicon()->{'favicon-32x32'} ?>">
      <link rel="icon" type="image/png" sizes="16x16"
        href="<?= APP_URL ?>/storage/uploads/site/favicons/<?= $config->favicon()->{'favicon-16x16'} ?>">
      <link rel="manifest" href="<?= APP_URL ?>/storage/uploads/site/favicons/<?= $config->favicon()->{'webmanifest'} ?>">
  <?php else: ?>
      <link rel="shortcut icon" href="<?= APP_URL ?>/static/assets/img/favicon/favicon.ico" type="image/x-icon">
  <?php endif; ?>

  <script>
    (function () {
      const storedTheme = localStorage.getItem('theme');
      const prefersDarkScheme = window.matchMedia('(prefers-color-scheme: dark)').matches;
      const theme = storedTheme || (prefersDarkScheme ? 'dark' : 'light');
      document.documentElement.setAttribute('data-bs-theme', theme);
    })();

    const APP_URL = "<?= APP_URL ?>";
  </script>

  <?php if (!is_logged_in() && isset($_COOKIE[COOKIE_PREFIX . 'auth'])): ?>
      <script>
        // AutoLogin
        (function() {
          fetch(APP_URL + "/auth/check-autologin", {
            method: "POST",
            headers: { "X-Requested-With": "XMLHttpRequest" }
          })
          .then(r => r.json())
          .then(res => {
            if (res.success && res.data.logged) {
              window.location.reload();
            }
          });
        })();
      </script>
  <?php endif; ?>

  <!-- CSS -->
  <?= static_assets_css("piruui.css") ?>
  <?= static_assets_css("bootstrapicons.css") ?>
  <?= static_assets_css("fontawesome.css") ?>

  <?= static_libs_css("toastifyjs", "toastifyjs.css") ?>

  <?= get_block('css'); ?>

  <?php
  $announcement_active = $config->get("announcement_active") === "true";
  $announcement_text   = $config->get("announcement_text", "");
  $announcement_type   = $config->get("announcement_type", "primary");
  $announcement_hash   = !empty($announcement_text) ? substr(md5($announcement_text), 0, 8) : "";
  $announcement_cookie = "announcement_closed_" . $announcement_hash;

  $show_announcement = $announcement_active && !empty($announcement_text) && !isset($_COOKIE[$announcement_cookie]);
  ?>
  <?php if ($show_announcement): ?>
      <style>
        .piru-announcement-bar {
          position: relative;
          width: 100%;
          z-index: 1050;
          transition: all 0.3s ease;
          font-family: inherit;
        }
        .piru-announcement-bar a {
          font-weight: 700;
          text-decoration: underline;
          color: inherit;
        }
        .piru-announcement-bar a:hover {
          opacity: 0.9;
        }
        .announcement-close {
          position: absolute;
          right: 15px;
          top: 50%;
          transform: translateY(-50%);
          background: transparent;
          border: none;
          color: inherit;
          font-size: 1.25rem;
          cursor: pointer;
          opacity: 0.7;
          transition: opacity 0.2s ease, transform 0.2s ease;
          display: flex;
          align-items: center;
          justify-content: center;
          padding: 5px;
        }
        .announcement-close:hover {
          opacity: 1;
          transform: translateY(-50%) scale(1.1);
        }
        @media (max-width: 576px) {
          .piru-announcement-bar .container {
            padding-right: 45px;
          }
        }
      </style>
  <?php endif; ?>
</head>

<body>
  <?php if ($config->get("loader_front") === "true"): ?>
      <?php require_once BASE_DIR . "/app/front/layouts/partials/loader.php"; ?>
  <?php endif; ?>

  <div class="piru-wrapper">
    <header class="piru-nav-wrapper">
      <?php if ($show_announcement): ?>
          <div class="piru-announcement-bar text-bg-<?= clear_html($announcement_type) ?>" id="announcementBar" data-announcement-hash="<?= clear_html($announcement_hash) ?>">
            <div class="container position-relative py-2">
              <div class="d-flex align-items-center justify-content-center gap-3 flex-wrap text-center">
                <div class="fw-bold small">
                  <?= $announcement_text ?>
                </div>
              </div>
              <button class="announcement-close" id="closeAnnouncement" type="button" aria-label="Cerrar aviso">
                <i class="bi bi-x-lg"></i>
              </button>
            </div>
          </div>
          <script>
            document.addEventListener("DOMContentLoaded", () => {
              const bar = document.getElementById("announcementBar");
              const closeBtn = document.getElementById("closeAnnouncement");
              if (bar && closeBtn) {
                closeBtn.addEventListener("click", () => {
                  const hash = bar.getAttribute("data-announcement-hash");
                  const d = new Date();
                  d.setTime(d.getTime() + (7 * 24 * 60 * 60 * 1000)); // 7 días
                  document.cookie = "announcement_closed_" + hash + "=1; expires=" + d.toUTCString() + "; path=/; SameSite=Lax";
                  
                  // Ocultar la barra con animación suave
                  bar.style.transition = "opacity 0.3s ease, transform 0.3s ease, margin-top 0.3s ease";
                  bar.style.opacity = "0";
                  bar.style.transform = "translateY(-100%)";
                  setTimeout(() => {
                    bar.style.display = "none";
                  }, 300);
                });
              }
            });
          </script>
      <?php endif; ?>
      <nav class="piru-nav-main">
        <div class="piru-nav-container">
          <a class="piru-nav-brand" href="<?= front_route() ?>">
            <?php $logoType = $config->get('logo_type', 'images'); ?>
            <?php if ($logoType == 'images'): ?>
              <div class="piru-nav-logo-images">
                <img class="piru-logo logo-light" src="<?= storage_uploads($config->logo()->dark, "site") ?>"
                  alt="<?= $config->siteName() ?>">
                <img class="piru-logo logo-dark" src="<?= storage_uploads($config->logo()->light, "site") ?>"
                  alt="<?= $config->siteName() ?>">
              </div>
            <?php elseif ($logoType == 'text'): ?>
              <div class="piru-nav-logo-text">
                <?php
                $iconSource = $config->get('logo_icon_source', 'class');
                $iconColor = $config->get('logo_icon_color', '');
                $colorStyle = $iconColor ? ' style="color: ' . clear_html($iconColor) . ' !important;"' : '';
                
                if ($iconSource === 'none'):
                  // Sin icono, no se renderiza nada
                elseif ($iconSource === 'svg_raw' && $config->get('logo_icon_svg')):
                  echo $config->get('logo_icon_svg');
                elseif ($iconSource === 'image' && $config->get('logo_icon_file')):
                  ?>
                  <img src="<?= storage_uploads($config->get('logo_icon_file'), "site") ?>" alt="Icon" style="height: 24px; width: auto; object-fit: contain;">
                <?php else: ?>
                  <i class="<?= clear_html($config->get('logo_icon_class', 'bi bi-lightning-charge-fill')) ?>"<?= $colorStyle ?>></i>
                <?php endif; ?>
                <span><?= $config->siteName() ?></span>
              </div>
            <?php endif; ?>
          </a>
          <ul class="piru-nav-menu">
            <li class="piru-nav-dropdown has-megamenu">
              <a class="piru-nav-link">
                <span>Explore</span>
                <i class="bi bi-chevron-down"></i>
              </a>
              <div class="piru-dropdown-panel piru-megamenu">
                <div class="piru-megamenu-grid">
                  <div class="piru-megamenu-column">
                    <h6>Media & Content</h6>
                    <a class="piru-dropdown-item" href="./movies/movies.html">
                      <span>Movies</span>
                    </a>
                    <a
                      class="piru-dropdown-item"
                      href="./movies/series-details.html">
                      <span>Series</span>
                    </a>
                    <a class="piru-dropdown-item" href="./blog/blog.html">
                      <span>Blog</span>
                    </a>
                    <a class="piru-dropdown-item" href="./colors.html">
                      <span>Color Palette</span>
                    </a>
                  </div>
                  <div class="piru-megamenu-column">
                    <h6>UI Components</h6>
                    <a class="piru-dropdown-item" href="./index.html#buttons">
                      <span>Buttons</span>
                    </a>
                    <a class="piru-dropdown-item" href="./index.html#forms">
                      <span>Forms</span>
                    </a>
                    <a class="piru-dropdown-item" href="#">
                      <span>Modals & Tabs</span>
                    </a>
                    <a class="piru-dropdown-item" href="#">
                      <span>Alerts & Badges</span>
                    </a>
                  </div>
                  <div class="piru-megamenu-column">
                    <h6>Resources & Shop</h6>
                    <a class="piru-dropdown-item" href="<?= front_route("docs") ?>">
                      <span>Documentation</span>
                    </a>
                    <a class="piru-dropdown-item" href="#">
                      <span>License</span>
                    </a>
                    <hr class="my-3 opacity-10" />
                    <a
                      class="piru-dropdown-item featured-item"
                      href="./shop/index.html">
                      <div class="d-flex flex-column">
                        <div class="fw-bold mb-1">Visit Our Shop</div>
                        <div class="small opacity-75">
                          Premium templates & UI kits
                        </div>
                      </div>
                    </a>
                  </div>
                </div>
              </div>
            </li>
            <li class="piru-nav-dropdown">
              <a class="piru-nav-link">
                <span>Shop</span>
                <i class="bi bi-chevron-down"></i>
              </a>
              <div class="piru-dropdown-panel">
                <a class="piru-dropdown-item" href="./shop/index.html">
                  <span>Catalog</span>
                </a>
                <a class="piru-dropdown-item" href="./shop/cart.html">
                  <span>Cart</span>
                </a>
                <a class="piru-dropdown-item" href="./shop/checkout.html">
                  <span>Checkout</span>
                </a>
              </div>
            </li>
            <li>
              <a class="piru-nav-link" href="./colors.html">
                <span>Colors</span>
              </a>
            </li>
            <li>
              <a
                class="piru-nav-link"
                href="./pages/sticky-footer-navbar.html">
                <span>Sticky Footer Navbar</span>
              </a>
            </li>
            <li class="piru-mobile-auth">
              <div class="d-grid gap-2">
                <?php if (!is_logged_in()): ?>
                    <a class="btn btn-primary py-3 fw-bold" href="<?= front_route("signup") ?>">
                      Registrarse
                    </a>
                    <a class="btn btn-link text-body fw-bold" href="<?= front_route("signin") ?>">
                      Iniciar Sesión
                    </a>
                <?php else: ?>
                    <?php if (is_admin()): ?>
                        <a class="btn btn-outline-primary py-3 fw-bold" href="<?= admin_route("dashboard") ?>">
                          Dashboard
                        </a>
                    <?php endif; ?>
                    <a class="btn btn-primary py-3 fw-bold" href="<?= front_route("account/profile") ?>">
                      Mi Perfil
                    </a>
                    <a class="btn btn-link text-danger fw-bold" href="<?= front_route("signout") ?>">
                      Cerrar Sesión
                    </a>
                <?php endif; ?>
              </div>
            </li>
          </ul>
          <div class="piru-nav-actions">
            <div class="dropdown">
              <button class="piru-action-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Language">
                <i class="bi bi-globe"></i>
                <span class="d-none d-sm-inline-block ms-1"><?= strtoupper(get_locale()) ?></span>
              </button>
              <ul class="dropdown-menu dropdown-menu-end">
                <li>
                  <a class="dropdown-item d-flex align-items-center gap-2" href="<?= front_route("lang/es") ?>">
                    Español
                    <?php if (get_locale() === "es"): ?>
                        <i class="fa-solid fa-check ms-auto text-success small"></i>
                    <?php endif; ?>
                  </a>
                </li>
                <li>
                  <a class="dropdown-item d-flex align-items-center gap-2" href="<?= front_route("lang/en") ?>">
                    English
                    <?php if (get_locale() === "en"): ?>
                        <i class="fa-solid fa-check ms-auto text-success small"></i>
                    <?php endif; ?>
                  </a>
                </li>
              </ul>
            </div>
            <button
              class="piru-action-btn"
              id="openSearch"
              type="button"
              title="Search">
              <i class="bi bi-search"></i>
            </button>
            <button
              class="piru-action-btn desktop-only"
              id="bd-theme-toggle"
              type="button"
              title="Theme">
              <span class="theme-icon-active"><i class="bi bi-sun"></i></span>
            </button>
            <div class="desktop-auth">
              <?php if (!is_logged_in()): ?>
                  <a class="btn btn-sm btn-link text-body text-decoration-none fw-bold" href="<?= front_route("signin") ?>">
                    Entrar
                  </a>
                  <a class="btn btn-sm btn-primary px-3 fw-bold" href="<?= front_route("signup") ?>">
                    Registrarse
                  </a>
              <?php else: ?>
                  <div class="d-flex align-items-center gap-3">
                    <?php if (is_admin()): ?>
                        <a class="btn btn-sm btn-outline-primary px-3 fw-bold" href="<?= admin_route("dashboard") ?>"
                          title="Administración">
                          <i class="bi bi-speedometer2"></i>
                        </a>
                    <?php endif; ?>
                    <a class="btn btn-sm btn-primary px-3 fw-bold" href="<?= front_route("account/profile") ?>"
                      title="Mi Perfil">
                      <i class="bi bi-user me-1"></i> Perfil
                    </a>
                    <a class="btn btn-sm btn-link text-danger p-0" href="<?= front_route("signout") ?>" title="Salir">
                      <i class="bi bi-box-arrow-right fs-5"></i>
                    </a>
                  </div>
              <?php endif; ?>
            </div>
            <button
              class="piru-action-btn mobile-only"
              id="bd-theme-toggle-mobile"
              type="button">
              <span class="theme-icon-active"><i class="bi bi-sun"></i></span>
            </button>
            <button class="piru-nav-toggle" aria-label="Menu">
              <span></span>
              <span></span>
              <span></span>
            </button>
          </div>
        </div>
      </nav>
      <div class="piru-search-overlay" id="piruSearchOverlay">
        <div class="piru-nav-container">
          <div class="d-flex align-items-center gap-3 w-100">
            <i class="bi bi-search text-primary h4 mb-0"></i>
            <input
              class="form-control form-control-lg bg-transparent border-0 ps-0 shadow-none"
              id="piruSearchInput"
              type="text"
              placeholder="Search..." />
            <button class="btn btn-link text-body p-0" id="closeSearch">
              <i class="bi bi-x-lg h4"></i>
            </button>
          </div>
        </div>
      </div>
    </header>

    <main class="piru-main-content">
      <?= $notifier->showBootstrap(); ?>
      <?= $content ?>
    </main>
    <footer class="footer py-5 mt-3 bg-body border-top">
      <div class="container text-center">
        <p class="mb-0">
          Copyright &copy; <?= date("Y") ?>
          <a href="<?= $config->siteUrl() ?>" target="_blank" rel="noopener noreferrer" class="text-decoration-none fw-bold">
            <?= $config->siteName() ?>
          </a>.
          Todos los derechos reservados.
        </p>

        <div id="animated-counter" class="mt-2 text-secondary small"></div>

        <div class="mt-3 d-flex justify-content-center gap-3 fs-5 align-items-center">
          <?php
          $socialList   = $config->social();
          $defaultIcons = [
            'facebook'  => 'fa-brands fa-facebook',
            'twitter'   => 'fa-brands fa-x-twitter',
            'instagram' => 'fa-brands fa-instagram',
            'youtube'   => 'fa-brands fa-youtube',
            'linkedin'  => 'fa-brands fa-linkedin',
            'tiktok'    => 'fa-brands fa-tiktok',
            'github'    => 'fa-brands fa-github',
            'whatsapp'  => 'fa-brands fa-whatsapp',
            'telegram'  => 'fa-brands fa-telegram',
          ];

          foreach ($socialList as $item):
            if (empty($item->url))
              continue;

            $iconClass  = $item->icon ?: ($defaultIcons[strtolower($item->name)] ?? null);
            $extFavicon = null;

            if (!$iconClass) {
              $domain = parse_url($item->url, PHP_URL_HOST);
              if ($domain) {
                $extFavicon = "https://www.google.com/s2/favicons?domain={$domain}&sz=64";
              }
            }
            ?>
              <a href="<?= $item->url ?>" target="_blank" class="text-secondary opacity-75 hover-opacity-100 transition-all" title="<?= ucfirst($item->name) ?>">
                <?php if ($iconClass): ?>
                    <i class="<?= $iconClass ?>"></i>
                <?php elseif ($extFavicon): ?>
                    <img src="<?= $extFavicon ?>" alt="<?= $item->name ?>" style="width: 1.2rem; height: 1.2rem; object-fit: contain; border-radius: 2px; filter: grayscale(1) opacity(0.75);">
                <?php else: ?>
                    <i class="fa-solid fa-link"></i>
                <?php endif; ?>
              </a>
          <?php endforeach; ?>
        </div>

        <div class="mt-3 small d-flex justify-content-center gap-3 flex-wrap">
          <?php
          // Siguiendo regla: Cero Consultas Directas (prepare + execute)
          $footer_legal_stmt = $connect->prepare("SELECT post_title as policy_title, post_slug as policy_slug FROM posts WHERE post_type = :type AND post_status = :status ORDER BY post_id ASC");
          $p_type            = 'policy';
          $p_status          = 1;
          $footer_legal_stmt->bindParam(':type', $p_type);
          $footer_legal_stmt->bindParam(':status', $p_status);
          $footer_legal_stmt->execute();
          $footer_legal_pages = $footer_legal_stmt->fetchAll(PDO::FETCH_OBJ);

          foreach ($footer_legal_pages as $lp):
            ?>
              <a href="<?= front_route($lp->policy_slug) ?>" class="text-secondary text-decoration-none small opacity-75 hover-opacity-100 transition-all">
                <?= $lp->policy_title ?>
              </a>
          <?php endforeach; ?>
        </div>
      </div>
    </footer>
  </div>

  <!-- JS -->
  <?= static_libs_js("toastifyjs", "toastifyjs.js") ?>
  <?= static_assets_js("piruui.js") ?>


  <?= get_block('js'); ?>

  <?= $notifier->showToasts(); ?>

  <script>
    // Procesar cola de correos de forma asíncrona al cargar la página
    window.addEventListener("DOMContentLoaded", () => {
      setTimeout(() => {
        fetch(APP_URL + "/mail/process").catch((err) => console.error("Error al procesar la cola de correos:", err));
      }, 1000);
    });
  </script>
</body>

</html>