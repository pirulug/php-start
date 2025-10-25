<nav class="sidebar js-sidebar" id="sidebar">
  <div class="sidebar-content js-simplebar">
    <a class="sidebar-brand" href="#">
      <span class="sidebar-brand-text align-middle"><?= SITE_NAME ?></span>
    </a>
    <ul class="sidebar-nav">
      <?php foreach ($routes as $key => $item): ?>
        <?php
        // Mostrar solo rutas con ícono y acceso
        if (empty($item['icon']) || !$accessManager->can_access($item['path']))
          continue;
        ?>

        <?php if (!empty($item['collapsed'])): ?>
          <?php
          // Determinar si la ruta actual está activa
          $active = isset($template['path']) &&
            in_array($template['path'], array_column($item['items'], 'path'));
          ?>
          <li class="sidebar-item <?= $active ? 'active' : '' ?>">
            <a class="sidebar-link <?= $active ? '' : 'collapsed' ?>" data-bs-target="#<?= $item['path'] ?>"
              data-bs-toggle="collapse">
              <i class="align-middle" data-feather="<?= $item['icon'] ?>"></i>
              <span class="align-middle"><?= $item['title'] ?></span>
            </a>
            <ul class="sidebar-dropdown list-unstyled collapse <?= $active ? 'show' : '' ?>" id="<?= $item['path'] ?>"
              data-bs-parent="#sidebar">
              <?php foreach ($item['items'] as $link => $subItem): ?>
                <?php
                // Saltar los items ocultos
                if (!empty($subItem['hidden']))
                  continue;
                if (!$accessManager->can_access($subItem['path']))
                  continue;
                ?>
                <li class="sidebar-item <?= $template['path'] == $subItem['path'] ? 'active' : '' ?>">
                  <a class="sidebar-link" href="<?= SITE_URL_ADMIN . "/" . $link ?>">
                    <?= $subItem['title'] ?>
                  </a>
                </li>
              <?php endforeach; ?>
            </ul>
          </li>
        <?php else: ?>
          <li class="sidebar-item <?= $template['path'] == $item['path'] ? 'active' : '' ?>">
            <a class="sidebar-link" href="<?= SITE_URL_ADMIN . "/" . $item['link'] ?>">
              <i class="align-middle" data-feather="<?= $item['icon'] ?>"></i>
              <span class="align-middle"><?= $item['title'] ?></span>
            </a>
          </li>
        <?php endif; ?>
      <?php endforeach; ?>
    </ul>
  </div>
</nav>