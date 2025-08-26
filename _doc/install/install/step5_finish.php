<?php
$successMessage = "";
$errorMessage   = [];

// Incluir encabezado
include 'includes/header.php';

// Verificar que la configuración de la base de datos esté en sesión
if (isset($_SESSION['db_host'], $_SESSION['db_name'], $_SESSION['db_user'], $_SESSION['db_pass'], $_SESSION['site_name'], $_SESSION['site_url'])) {
  // Obtener datos de sesión
  $host      = $_SESSION['db_host'];
  $db_name   = $_SESSION['db_name'];
  $user      = $_SESSION['db_user'];
  $password  = $_SESSION['db_pass'];
  $site_name = $_SESSION['site_name'];
  $site_url  = $_SESSION['site_url'];

  // Verificar que todos los datos estén completos y detallados
  if (empty($host)) {
    $errorMessage[] = "El campo 'Servidor de base de datos (DB_HOST)' está vacío. Por favor, ingresa la dirección del servidor.";
  }
  if (empty($db_name)) {
    $errorMessage[] = "El campo 'Nombre de la base de datos (DB_NAME)' está vacío. Por favor, ingresa el nombre de la base de datos.";
  }
  if (empty($user)) {
    $errorMessage[] = "El campo 'Usuario de la base de datos (DB_USER)' está vacío. Por favor, ingresa el nombre de usuario.";
  }
  if (empty($site_name)) {
    $errorMessage[] = "El campo 'URL del sitio (SITE_NAME)' está vacío. Por favor, ingresa la URL donde estará alojado el sitio.";
  }
  if (empty($site_url)) {
    $errorMessage[] = "El campo 'URL del sitio (SITE_URL)' está vacío. Por favor, ingresa la URL donde estará alojado el sitio.";
  }

  if (empty($errorMessage)) {
    $secret_key = generarCadenaAleatoria("mixto", true, 24, true);
    $secret_iv  = generarCadenaAleatoria("numeros", false, 24);

    // Crear el archivo config.php
    $configTemplate = file_get_contents('config.tpl');
    $configContent  = str_replace(
      [
        '<DB_HOST>',
        '<DB_USER>',
        '<DB_PASSWORD>',
        '<DB_NAME>',
        '<SITE_NAME>',
        '<SITE_URL>',
        '<SECRET_KEY>',
        '<SECRET_IV>',
      ],
      [
        $host,
        $user,
        $password,
        $db_name,
        $site_name,
        $site_url,
        $secret_key,
        $secret_iv,
      ],

      $configTemplate
    );

    try {
      // Intentar escribir el archivo de configuración
      if (file_put_contents('../config.php', $configContent) === false) {
        throw new Exception("No se pudo crear el archivo de configuración. Verifica los permisos de escritura en la carpeta.");
      }

      // Conectar a la base de datos
      $pdo = new PDO("mysql:host=$host;dbname=$db_name", $user, $password);
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      // Ejecutar el script SQL
      $sql = file_get_contents('db.sql');
      $pdo->exec($sql);

      // Insertar el usuario administrador
      $adminEmail  = $_SESSION['admin_email'];
      $adminUser   = $_SESSION['admin_user'];
      $adminPass   = $_SESSION['admin_pass'];
      $adminRole   = "1";
      $adminStatus = "1";

      if (empty($adminEmail)) {
        $errorMessage[] = "El campo 'Correo electrónico del administrador' está vacío. Por favor, ingresa un correo electrónico válido.";
      }
      if (empty($adminUser)) {
        $errorMessage[] = "El campo 'Nombre de usuario del administrador' está vacío. Por favor, ingresa un nombre de usuario.";
      }
      if (empty($adminPass)) {
        $errorMessage[] = "El campo 'Contraseña del administrador' está vacío. Por favor, ingresa una contraseña.";
      } else {
        $adminPass = $encryption->encrypt($adminPass, $secret_key, $secret_iv);
      }

      if (empty($errorMessage)) {
        $stmt = $pdo->prepare("INSERT INTO users (user_name, user_email, user_password, user_role,user_status) VALUES (:user_name, :user_email, :user_password, :user_role,:user_status)");

        // Vincular los parámetros
        $stmt->bindParam(':user_name', $adminUser, PDO::PARAM_STR);
        $stmt->bindParam(':user_email', $adminEmail, PDO::PARAM_STR);
        $stmt->bindParam(':user_password', $adminPass, PDO::PARAM_STR);
        $stmt->bindParam(':user_role', $adminRole, PDO::PARAM_STR);
        $stmt->bindParam(':user_status', $adminStatus, PDO::PARAM_STR);

        // Ejecutar la consulta
        $stmt->execute();

        // Actualizar la tabla de opciones
        $stmt = $pdo->prepare("UPDATE options SET option_value = :site_name WHERE option_key = 'site_name'");
        $stmt->bindParam(':site_name', $site_name, PDO::PARAM_STR);
        $stmt->execute();

        // Actualizar la URL del sitio
        $stmt = $pdo->prepare("UPDATE options SET option_value = :site_url WHERE option_key = 'site_url'");
        $stmt->bindParam(':site_url', $site_url, PDO::PARAM_STR);
        $stmt->execute();

        // Actualizar favicon
        $stmt    = $pdo->prepare("UPDATE options SET option_value = :favicon WHERE option_key = 'favicon'");
        $favicon = json_encode(
          [
            "android-chrome-192x192" => "android-chrome-192x192.png",
            "android-chrome-512x512" => "android-chrome-512x512.png",
            "apple-touch-icon"       => "apple-touch-icon.png",
            "favicon-16x16"          => "favicon-16x16.png",
            "favicon-32x32"          => "favicon-32x32.png",
            "favicon.ico"            => "favicon.ico",
            "webmanifest"            => "site.webmanifest"
          ]
        );
        $stmt->bindParam(':favicon', $favicon, PDO::PARAM_STR);
        $stmt->execute();

        // Actualizar White Logo
        $stmt      = $pdo->prepare("UPDATE options SET option_value = :white_logo WHERE option_key = 'white_logo'");
        $whiteLogo = "st_logo_light.webp";
        $stmt->bindParam(':white_logo', $whiteLogo, PDO::PARAM_STR);
        $stmt->execute();

        // Actualizar Dark Logo
        $stmt     = $pdo->prepare("UPDATE options SET option_value = :dark_logo WHERE option_key = 'dark_logo'");
        $darkLogo = "st_logo_dark.webp";
        $stmt->bindParam(':dark_logo', $darkLogo, PDO::PARAM_STR);
        $stmt->execute();

        // Actualizar OG Image
        $stmt    = $pdo->prepare("UPDATE options SET option_value = :og_image WHERE option_key = 'og_image'");
        $ogImage = "og_image.webp";
        $stmt->bindParam(':og_image', $ogImage, PDO::PARAM_STR);
        $stmt->execute();

        // Eliminar carpeta ../uploads/site si existe
        $uploadsSiteDir = '../uploads/site';
        if (is_dir($uploadsSiteDir)) {
          $files = glob($uploadsSiteDir . '/*');
          foreach ($files as $file) {
            if (is_file($file)) {
              unlink($file); // Eliminar archivo
            } elseif (is_dir($file)) {
              // Si hay subdirectorios, eliminarlos también
              $subfiles = glob($file . '/*');
              foreach ($subfiles as $subfile) {
                if (is_file($subfile)) {
                  unlink($subfile);
                }
              }
              rmdir($file); // Eliminar subdirectorio vacío
            }
          }
          rmdir($uploadsSiteDir); // Eliminar la carpeta principal
        }

        // Copiar archivos desde img/sites a ../uploads/site
        $sourceDir = 'images/site';
        $destDir   = '../uploads/site';

        // Crear carpeta destino si no existe
        if (!is_dir($destDir)) {
          mkdir($destDir, 0755, true);
        }

        // Lista inicial del directorio
        $queue = [[$sourceDir, $destDir]];

        while (!empty($queue)) {
          list($currentSource, $currentDest) = array_shift($queue);

          // Leer archivos y carpetas dentro del directorio actual
          $items = scandir($currentSource);

          foreach ($items as $item) {
            if ($item === '.' || $item === '..')
              continue;

            $sourcePath = $currentSource . '/' . $item;
            $destPath   = $currentDest . '/' . $item;

            if (is_dir($sourcePath)) {
              if (!is_dir($destPath)) {
                mkdir($destPath, 0755, true); // Crear subcarpeta
              }
              // Agregar subcarpeta a la cola para copiar su contenido
              $queue[] = [$sourcePath, $destPath];
            } elseif (is_file($sourcePath)) {
              copy($sourcePath, $destPath); // Copiar archivo
            }
          }
        }


        $successMessage = "La base de datos se ha configurado correctamente y el usuario administrador ha sido creado.";
      }
    } catch (PDOException $e) {
      $errorMessage[] = "Hubo un problema al conectar con la base de datos. Por favor, revisa los datos de conexión e inténtalo de nuevo. Detalles del error: " . $e->getMessage();
    } catch (Exception $e) {
      $errorMessage[] = $e->getMessage();
    }
  }

  // Limpiar la sesión si no hay errores
  if (empty($errorMessage)) {
    session_unset();
  }
} else {
  $errorMessage[] = "No se encontraron los datos de configuración de la base de datos. Por favor, regresa al paso anterior e ingresa la información necesaria.";
  // header("Location: index.php?step=3");
  exit();
}
?>

<div class="">
  <h2 class="text-center">Instalación Completa</h2>
  <?php if (!empty($errorMessage)): ?>
    <div class="alert alert-danger">
      <ul class="m-0">
        <?php foreach ($errorMessage as $message): ?>
          <li><?php echo $message; ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php else: ?>
    <p class="alert alert-success"><?php echo $successMessage; ?></p>
    <p class="h5 text-success">¡Felicidades! La instalación se ha completado. Ahora puedes comenzar a usar la aplicación.
    </p>
    <a href="../" class="btn btn-primary">Ir al sitio</a>
  <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>