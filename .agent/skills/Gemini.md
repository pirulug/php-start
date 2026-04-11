- Las consultas simpre tienen que ser FETCH_OBJ
- Las Consultas tienen que ser bimparam
- La coneccion es ta en la varible $connect
- Obtener los datos del formulario no lo sanitices

$user_login  = $_POST['user_login'];
$user_email  = $_POST['user_email'];

- las notificaciones

$notifier
    ->message("Texto ...")
    ->success()
    ->bootstrap()
    ->add();

$notifier
    ->message("Texto ...")
    ->danger()
    ->bootstrap()
    ->add();

- Si no hay mensajes de error, proceder con la inserción

if (!$notifier->can()->danger()) {

}

- mi proyecto esta dividida en dos archivos el .view y el .action mi miniframework lo que hace es llamar al view por el acction

- para subir las imagenes para nuevo

php:
if (!empty($_FILES['user_image']) && $_FILES['user_image']['size'] > 0) {
    if (!$notifier->can()->danger()) {

      $upload_path = BASE_DIR . '/storage/uploads/user/';

      $user_image = (new UploadImage())
        ->file($_FILES['user_image'])
        ->dir($upload_path)
        ->convertTo("webp")
        ->width(100)
        ->height(100)
        ->upload();

      if (!$user_image['success']) {
        $notifier
          ->message($user_image['message'])
          ->danger()
          ->bootstrap()
          ->add();
      } else {
        $user_image = $user_image['file_name'];
      }
    } else {
      $user_image = "default.webp";
    }
  } else {
    $user_image = "default.webp";
  }
html
<link rel="stylesheet" href="<?= APP_URL . "/static/plugins/dropzone/dropimg.css" ?>">
<script src="<?= APP_URL . "/static/plugins/dropzone/dropimg.js" ?>"></script>
<script>
  // Inicializar plugin de imagen
  DropImg.init();
</script>
<div>
  <label class="form-label  mb-2">Imagen de Perfil</label>
  <div
    class="p-4 rounded bg-secondary bg-opacity-10 d-flex justify-content-center align-items-center flex-column">
    <input type="file" id="user_image" name="user_image" data-dropimg data-width="100" data-height="100"
      accept=".jpg,.jpeg,.png,.gif,.webp">
  </div>
</div>

- para editar todo lo mismo pero cambia 
<div>
  <label class="form-label  mb-2">Avatar</label>
  <div
    class="p-4 rounded bg-secondary bg-opacity-10 d-flex justify-content-center align-items-center flex-column">
    <input type="file" id="user_image" name="user_image" data-dropimg data-width="100" data-height="100"
      data-default="<?= APP_URL ?>/storage/uploads/user/<?= $user->user_image ?>"
      accept=".jpg,.jpeg,.png,.gif,.webp">
  </div>
</div>

ejemplo de .action

<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  // 1. Recibir las variables del formulario

  $category_name        = trim($_POST['category_name'] ?? '');
  $category_description = trim($_POST['category_description'] ?? '');
  $category_status      = isset($_POST['category_status']) ? (int) $_POST['category_status'] : 1;


  // 2. Validaciones usando el sistema $notifier

  if (strlen($category_name) < 3) {

    $notifier
      ->message("El nombre de la categoría debe tener al menos 3 caracteres.")
      ->danger()
      ->bootstrap()
      ->add();

  } else {

    // Validar si el nombre de la categoría ya existe para evitar duplicados
    try {
      $stmt_check = $connect->prepare("SELECT category_id FROM categories WHERE category_name = :name LIMIT 1");
      $stmt_check->bindParam(':name', $category_name);
      $stmt_check->execute();

      if ($stmt_check->fetch(PDO::FETCH_OBJ)) {
        $notifier
          ->message("Ya existe una categoría registrada con ese nombre.")
          ->danger()
          ->bootstrap()
          ->add();
      }

    } catch (PDOException $e) {
      $notifier->message("Error al validar la categoría: " . $e->getMessage())->danger()->bootstrap()->add();
    }

  }

  if (!in_array($category_status, [0, 1])) {
    $notifier
      ->message("Debes seleccionar un estado válido.")
      ->danger()
      ->bootstrap()
      ->add();
  }

  // 3. Si no hay mensajes de error, proceder con la inserción

  if (!$notifier->can()->danger()) {

    try {
      $sql = "INSERT INTO categories (category_name, category_description, category_status)
                    VALUES (:name, :description, :status)";

      $stmt = $connect->prepare($sql);
      $stmt->bindParam(':name', $category_name);
      $stmt->bindParam(':description', $category_description);
      $stmt->bindParam(':status', $category_status, PDO::PARAM_INT);

      if ($stmt->execute()) {
        $notifier
          ->message("La nueva categoría se insertó correctamente.")
          ->success()
          ->bootstrap()
          ->add();
        // Redirigir al listado usando tu función admin_route si es necesario
        header("Location: " . admin_route("categories"));
        exit();
      } else {
        $notifier
          ->message("Hubo un error al intentar insertar la nueva categoría.")
          ->danger()
          ->bootstrap()
          ->add();
      }
    } catch (PDOException $e) {
      $notifier
        ->message("Error de Base de Datos: " . $e->getMessage())
        ->danger()
        ->bootstrap()
        ->add();
    }
  }
}

Ejemplo del .view

<?php start_block("title") ?>

Nueva Categoría

<?php end_block() ?>

<?php start_block("css") ?>

<style>
  /* Estilos opcionales específicos para la vista de categorías si los necesitas */
</style>

<?php end_block() ?>

<?php start_block("js") ?>

<script>

</script>

<?php end_block() ?>

<form action="" method="POST" autocomplete="off">
<!-- Contenido del formulario -->

<!-- Esta parte no puedes modificar los estilos solo los textos -->
<div class="bg-body p-3 rounded text-end">
  <a href="<?= admin_route("branches") ?>" class="btn btn-secondary">
    Cancelar
  </a>
  <button type="submit" class="btn btn-primary">
    <i class="fas fa-save me-2"></i> Guardar
  </button>
</div>
</form>

- Recuerda siempre usar para los estilos los de bootstrap 5 
- Los estilos tienen que ser compatible con dark / light
- Clases de bootstrap que no debes usar .shadow .bg-white .bg-light .text-white .text-light
- Para los .card no use .border-0
- Para el .view.php no uses .container ni .container-fluid que el layout ya tiene su contenedor

- Manejo de rutas

 * admin_route("branches/edit", [$id]);             // /admin/branches/edit/1
 * admin_route("branches", [], ['status' => 1]);    // /admin/branches?status=1
 * admin_route("branches/view", [1], ['p' => 2]);   // /admin/branches/view/1?p=2


- Para los botones de editar no uses btn-goup

- Boton para eliminar

<?= ActionBtn::delete(admin_route("user/delete", [$cipher->encrypt($user->user_id)]))
  ->can('users.delete')
  ->saTitle('¿Eliminar a ' . $user->user_login . '?')
  ->saText('No podrás recuperar sus datos.') ?>

- Boton para editar

<?= ActionBtn::edit(admin_route("user/edit", [$cipher->encrypt($user->user_id)]))
  ->can('users.edit') ?>

- Boton para ver

<?= ActionBtn::view(admin_route("user/view", [$cipher->encrypt($user->user_id)]))
  ->can('users.view') ?>

- Boton para Desacticar / Activar

<?php if ($user->user_status == 1): ?>
  <?= ActionBtn::deactivate(admin_route("user/deactivate", [$cipher->encrypt($user->user_id)]))
    ->can('users.deactivate') ?>
<?php else: ?>
  <?= ActionBtn::active(admin_route("user/deactivate", [$cipher->encrypt($user->user_id)]))
    ->can('users.deactivate') ?>
<?php endif; ?>

- para mandar el argumento cifrado

$cipher->encrypt($user->user_id)

- para recibir el argumento desifrado

$cipher->decrypt($args['id'])

- Los parametros se resiben con la varible $args tanto para eliminar y editar el ['id'] el nombre se obtine de el router Router::route('branche/edit/{id}')

$id = $args['id'];

- Ejemplo de rutas 

<?php

Router::route('users')
  ->action(admin_action("users.list"))
  ->view(admin_view("users.list"))
  ->layout(admin_layout())
  ->middleware('auth_admin')
  ->permission('users.list')
  ->register();

Router::route('user/new')
  ->action(admin_action("users.new"))
  ->view(admin_view("users.new"))
  ->layout(admin_layout())
  ->middleware('auth_admin')
  ->permission('users.new')
  ->register();

Router::route('user/edit/{id}')
  ->action(admin_action("users.edit"))
  ->view(admin_view("users.edit"))
  ->layout(admin_layout())
  ->middleware('auth_admin')
  ->permission('users.edit')
  ->register();

Router::route('user/delete/{id}')
  ->action(admin_action("users.delete"))
  ->middleware('auth_admin')
  ->permission('users.delete')
  ->register();

- Bien ahora dame los siguientes archivos

category.new.action.php
category.new.view.php

para eso te paso el siguiente tabla .sql

CREATE TABLE ... (
  ..
)