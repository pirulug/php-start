<div class="card">
  <div class="card-body">

    <h2>PHP</h2>
    <div class="table-responsive">
      <table class="table table-striped">
        <tr>
          <th>Versión de PHP</th>
          <td><?php echo phpversion(); ?></td>
          <td><?php echo getStatusIcon($isPhpVersionOk); ?></td>
        </tr>
        <tr>
          <th>Extensiones Requeridas</th>
          <td>
            <?php
            echo implode(', ', $requiredExtensions);
            if (!empty($missingExtensions)) {
              echo " (Faltan: " . implode(', ', $missingExtensions) . ")";
            }
            ?>
          </td>
          <td><?php echo getStatusIcon(empty($missingExtensions)); ?></td>
        </tr>
        <tr>
          <th>Límite de Memoria PHP</th>
          <td><?php echo ini_get('memory_limit'); ?></td>
          <td><?php echo getStatusIcon($isMemoryLimitOk); ?></td>
        </tr>
        <tr>
          <th>Tamaño Máximo de Subida</th>
          <td><?php echo ini_get('upload_max_filesize'); ?></td>
          <td><?php echo getStatusIcon(true); ?></td> <!-- Puedes añadir validaciones si lo deseas -->
        </tr>
        <tr>
          <th>Tiempo Máximo de Ejecución</th>
          <td><?php echo $executionTime . " segundos"; ?></td>
          <td><?php echo getStatusIcon($isExecutionTimeOk); ?></td>
        </tr>
        <tr>
          <th>Dirección IP del Servidor</th>
          <td><?php echo $_SERVER['SERVER_ADDR']; ?></td>
          <td><?php echo getStatusIcon(true); ?></td> <!-- Siempre correcta si se obtiene -->
        </tr>

        <tr>
          <th>Versión de OpenSSL</th>
          <td><?php echo OPENSSL_VERSION_TEXT; ?></td>
          <td><?php echo getStatusIcon(true); ?></td> <!-- Siempre correcta si se obtiene -->
        </tr>
        <tr>
          <th>Zona Horaria</th>
          <td><?php echo date_default_timezone_get(); ?></td>
          <td><?php echo getStatusIcon(true); ?></td> <!-- Siempre correcta si se obtiene -->
        </tr>
      </table>
    </div>

    <h2>Servidor</h2>
    <div class="table-responsive">
      <table class="table table-striped">
        <tr>
          <th>Software</th>
          <td><?php echo $_SERVER['SERVER_SOFTWARE']; ?></td>
          <td></td>
        </tr>
        <tr>
          <th>Nombre del Host</th>
          <td><?php echo gethostname(); ?></td>
          <td></td>
        </tr>
        <tr>
          <th>Puerto</th>
          <td><?php echo $_SERVER['SERVER_PORT']; ?></td>
          <td></td>
        </tr>
        <tr>
          <th>Espacio de Almacenamiento</th>
          <td>
            <?php
            $freeSpace  = round(disk_free_space("/") / 1024 / 1024);
            $totalSpace = round(disk_total_space("/") / 1024 / 1024);
            echo "$freeSpace MB libre de $totalSpace MB";
            ?>
          </td>
          <td><?php echo getStatusIcon(true); ?></td> <!-- Siempre correcta si se obtiene -->
        </tr>
      </table>

    </div>

    <h2>Sistema Operativo</h2>

    <div class="table-responsive">
      <table class="table table-striped">
        <tr>
          <th>Nombre</th>
          <td><?php echo php_uname('s'); ?></td>
        </tr>
        <tr>
          <th>Versión</th>
          <td><?php echo php_uname('r'); ?></td>
        </tr>
        <tr>
          <th>Arquitectura</th>
          <td><?php echo php_uname('m'); ?></td>
        </tr>
      </table>
    </div>

    <h2>Base de datos</h2>
    <div class="table-responsive">
      <table class="table table-striped">
        <tr>
          <th>Nombre</th>
          <td><?php echo $dbType; ?></td>
          <td></td>
        </tr>
        <tr>
          <th>Versión</th>
          <td>
            <?php echo $dbVersion; ?>
            <?= $dbVersion <= $minDbVersion ? "Tiene que ser la version " . $minDbVersion : "" ?>
          </td>
          <td><?php echo getStatusIcon($isDbVersionOk); ?></td>
        </tr>
      </table>
    </div>
  </div>
</div>