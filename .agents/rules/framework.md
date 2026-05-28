---
trigger: always_on
---

# Framework Architecture & Standards

Este documento define las reglas inquebrantables de la arquitectura Action-View-Layout del framework.

## 1. Variables Globales de Núcleo
El sistema inicializa automáticamente objetos esenciales que están disponibles en todos los `actions` y `views` sin necesidad de inyectarlos:

- **`$connect`**: Instancia de `PDO` para la base de datos.
- **`$config`**: Instancia de `SiteConfig` para opciones globales del sitio.
- **`$cipher`**: Instancia de `Cipher` para seguridad, hashes y ofuscación.
- **`$site_date`**: Instancia de `SiteDate` para manejo de fechas y zonas horarias.

## 2. Base de Datos y PDO (Seguridad Crítica)
- **Cero Consultas Directas**: **NUNCA** uses `query()`.
- **Blindaje Obligatorio**: Usa `prepare()` seguido de **`bindParam()`**.
- **Prohibido bindValue**: Usa siempre `bindParam()` con variables intermedias si es necesario.
- **Marcadores Únicos**: No reutilices marcadores (ej: `:id`) en la misma consulta; usa `:id1`, `:id2`.
- **Fetch Orientado a Objetos**: Usa siempre `PDO::FETCH_OBJ`.
  - Correcto: `$stmt->fetch(PDO::FETCH_OBJ)`
  - Prohibido: `PDO::FETCH_ASSOC`.

## 3. Flujo de Control y Redirecciones
Para mantener la integridad del ciclo de vida de la aplicación:

- **Redirecciones**: Usa siempre `header("Location: ...")`.
- **Terminación Obligatoria**: Toda redirección o detención del script debe ir seguida de `exit();`.
  ```php
  header("Location: " . admin_route("users"));
  exit();
  ```
- **Prohibición**: No uses funciones envolventes como `redirect()`.

## 4. Arquitectura Modular (Action-View-Layout)
El framework separa estrictamente la lógica, la presentación y la estructura:

- **Actions (`actions/`)**: Lógica de procesamiento, consultas DB y preparación de datos.
- **Views (`views/`)**: Plantillas PHP/HTML. No deben contener lógica compleja de negocio.
- **Layouts (`layouts/`)**: Estructuras maestras que envuelven las vistas.
- **Router (`router.php`)**: Define la resolución de rutas dentro del módulo.

## 5. Estructura de Directorios

### Contexto Admin (Ejemplo)
```text
app/admin/modules/{module}/
├── actions/     # Lógica (list.action.php, edit.action.php)
├── domains/     # Helpers específicos del dominio
├── endpoints/   # Salidas raw o JSON
├── scripts/     # JS específico del módulo
├── views/       # Plantillas (list.view.php, edit.view.php)
├── router.php   # Definición de rutas del módulo
└── sidebar.php  # Items de menú lateral
```

### Contexto Front (Ejemplo)
```text
app/front/modules/{module}/
├── actions/     # index.action.php
├── views/       # index.view.php
└── router.php   # Definición de rutas
```

## 6. Estructura de .view.php y Gestión de Bloques (Themplate)
Las vistas deben utilizar el sistema de bloques para inyectar contenido en el layout. La lógica interna de bloques está contenida en la clase de librería `Themplate` y expuesta mediante funciones globales con el prefijo `block_`.

### Funciones Disponibles:
- `block_start($name, $mode = "replace")`: Inicia la captura de un bloque de contenido (soporta anidación mediante una pila interna).
- `block_append($name)`: Captura contenido y lo anexa al final del bloque.
- `block_prepend($name)`: Captura contenido y lo pre-anexa al inicio del bloque.
- `block_end()`: Finaliza la captura del bloque actual.
- `block_define($name, $content, $mode = "replace")`: Define contenido directamente sin abrir buffers de salida.
- `block_clear($name)`: Limpia el contenido de un bloque.
- `block_get($name, $default = "")`: Obtiene el contenido de un bloque.
- `block_render($name, $default = "")`: Imprime directamente el bloque en pantalla.
- `block_has($name)`: Retorna true si el bloque tiene contenido no vacío.

### Ejemplo de Estructura de Vista:
```php
<?php block_start("title") ?>
  Título de la Página
<?php block_end() ?>

<?php block_start("breadcrumb"); ?>
<?php render_breadcrumb([
  ["label" => "Dashboard", "link" => admin_route("dashboard")],
  ["label" => "Módulo"]
]) ?>
<?php block_end(); ?>

<?php block_start("css") ?>
<style>/* Custom CSS */</style>
<?php block_end() ?>

<?php block_start("js") ?>
<script>/* Custom JS */</script>
<?php block_end() ?>

<div class="card bg-body">
  <div class="card-body">
    <h1>Título Principal</h1>
    <p>Contenido de la vista.</p>
  </div>
</div>
```

## 7. Uso de Cipher `$cipher`
- **Cifrar/Descifrar**: `$c = $cipher->encrypt("data")` / `$cipher->decrypt($c)`.
- **Ofuscar IDs**: 
  - `$short = $cipher->b10ToBstr($id, 'mixed')` (Número a código corto).
  - `$id = $cipher->bstrToB10($short, 'mixed')` (Código corto a número).
- **Contraseñas**: `$hash = $cipher->password("pass")` / `$cipher->verifyPassword("pass", $hash)`.

## 8. Configuración Global `$config`
Acceso simplificado a la tabla `options`:
- **General**: `$config->get('key', 'default')` o `$config->siteName()`.
- **Formatos**: `$config->dateFormat()`, `$config->timeFormat()`.
- **Multimedia**: `$config->logo()->dark`, `$config->favicon()`.
- **Modo Mantenimiento**: `$config->isMaintenanceMode()`.

## 9. Helpers de Estado e Identidad (is_*)
El framework proporciona funciones globales para verificar el estado de la sesión, permisos y navegación:

- **Sesión y Autenticación**:
  - `is_logged_in()`: Devuelve `true` si el usuario tiene una sesión activa y válida.
  - `is_session_active()`: Verifica si existe el marcador de sesión.
  - `user_session()`: Helper para la instancia global `$user_session`. Devuelve el objeto del usuario actual o `null`.

- **Roles y Permisos**:
  - `is_admin()`: Verifica si el usuario tiene acceso al panel administrativo (incluye chequeo de `can_user_login`).
  - `is_superadmin()`: Verifica si el usuario es uno de los IDs maestros definidos en `security.config.php`.
  - `is_user_role($role)`: Verifica si el usuario tiene un rol específico (por nombre o ID).
  - `is_user_permission_admin($permission)`: Verifica si el usuario tiene un permiso en el contexto admin.
  - `is_user_permission_front($permission)`: Verifica si el usuario tiene un permiso en el contexto front.

- **Navegación y Rutas**:
  - `is_active($path, $class = 'active')`: Compara la ruta actual con el `$path` proporcionado. Devuelve `$class` si coinciden.
    ```php
    <a href="..." class="list-group-item <?= is_active('account/profile') ?>">Inicio</a>
    ```
  - `route()`: Devuelve el array de datos de la ruta actual resuelta por el `Router`.

- **Generación de URLs y Assets**:
  - `admin_route($path, $params = [], $get = [])`: Genera una URL absoluta para el panel de administración.
  - `front_route($path, $params = [], $get = [])`: Genera una URL para el sitio público.
  - `api_route($path, $params = [], $get = [])`: Genera una URL para la API global.
  - `admin_modules_script($module, $file)`: Devuelve la etiqueta `<script>` completa para un script de módulo (`app/admin/modules/{module}/scripts/{file}.script.js`).
    ```php
    <?= admin_modules_script('account', 'profile') ?>
    ```

## 10. Helpers de Formateo
Funciones globales para estandarizar la visualización de datos basadas en la configuración del sitio:

- **Fechas y Horas**:
  - `format_date($date)`: Formato de fecha.
  - `format_time($date)`: Formato de hora.
  - `format_datetime($date)`: Fecha y hora combinadas.

- **Números y Moneda**:
  - `format_number($number)`: Formateo inteligente (separador de miles y decimales solo si existen).
  - `format_number_decimal($number, $decimals = null)`: Fuerza una cantidad específica de decimales.
  - `format_money($number, $symbol = null)`: Formatea como moneda con símbolo y posición configurada.

## 11. Sistema de Idiomas (I18N)
El framework implementa un sistema de traducción inspirado en WordPress, utilizando archivos PHP de retorno de array.

- **Funciones Globales**:
  - `__($text, $domain = 'default')`: Devuelve la traducción de un texto. Si no existe, devuelve el texto original.
  - `_e($text, $domain = 'default')`: Imprime directamente la traducción.
  - `get_locale()`: Devuelve el idioma actual del sitio (ej. `es`, `en`).

- **Estructura de Archivos**:
  - Global: `core/languages/{lang}.php`
  - Módulos: `app/{context}/modules/{module}/languages/{lang}.php`

- **Ejemplo de Uso**:
  ```php
  <h1><?= __('Welcome') ?></h1>
  <button><?php _e('Save', 'users') ?></button>
  ```

## 12. Helpers de Limpieza y Seguridad (Sanitización)
Para garantizar la integridad de los datos y prevenir ataques XSS, el framework proporciona funciones específicas según el contexto de uso:

- **Procesamiento de Datos (Actions)**:
  - `clear_input($data)`: Limpia cadenas de texto simples (inputs de formularios). Elimina etiquetas peligrosas y sanitiza el contenido.
  - `clear_textarea($data, $allowedTags = null)`: Limpia contenido HTML para editores de texto (WYSIWYG). Permite etiquetas seguras y elimina scripts/eventos.
  - `clear_image($file)`: Valida que un archivo subido sea una imagen real, segura y con extensión permitida.

- **Visualización Segura (Views)**:
  - `clear_html($data)`: Escapa caracteres especiales para renderizar texto de forma segura dentro de HTML (ej: en el atributo `value` de un input). Es un wrapper de `htmlspecialchars`.

- **Ejemplo de Uso**:
  ```php
  // En el Action
  $user_name = clear_input($_POST['user_name']);
  $bio = clear_textarea($_POST['user_bio']);

  // En la View
  <input value="<?= clear_html($user_name) ?>">
  <div><?= $bio ?></div> <!-- clear_textarea ya es seguro para imprimir -->
  ```

## 13. Notificaciones ($notifier)
- **Avisar al humano:** Usa siempre el objeto global `$notifier` para hablar con el usuario.
- **Tipos de grito:**
  - `$notifier->success("Bien!")` -> Verde (éxito).
  - `$notifier->danger("Mal!")` -> Rojo (error).
  - `$notifier->warning("Cuidado!")` -> Amarillo (aviso).
  - `$notifier->info("Dato!")` -> Azul (info).
- **Formas de mostrar:**
  - `->bootstrap()` -> Alerta clásica de Bootstrap (por defecto).
  - `->toast()` -> Notificación pequeña flotante (Toastify).
  - `->sweetalert()` -> Ventana emergente premium (SweetAlert2).
- **Cerrar trato:** **OBLIGATORIO** terminar con `->add()`.

## 14. Endpoints y Scripts (AJAX/Fetch)
El framework facilita la comunicación asíncrona mediante una estructura dedicada para lógica de cliente y respuestas raw (JSON, XML, etc.).

### Scripts del Módulo (`scripts/`)
Los archivos JavaScript específicos de un módulo se ubican en `app/{context}/modules/{module}/scripts/{file}.script.js`.
- **Carga en View**: Utiliza el helper `admin_modules_script` o `front_modules_script` dentro del bloque `js`.
- **Contexto de URL**: Para que el JS conozca las rutas del sistema, inyecta variables globales antes de cargar el archivo.
  ```php
  <?php block_start("js") ?>
  <script>
    const APP_ADMIN_URL = "<?= admin_route() ?>";
  </script>
  <?= admin_modules_script("users", "list") ?>
  <?php block_end() ?>
  ```

### Endpoints (`endpoints/`)
Son archivos PHP que procesan peticiones AJAX y devuelven datos sin layout. Se ubican en `app/{context}/modules/{module}/endpoints/{file}.endpoint.php`.
- **Registro en Router**: Se deben registrar en el `router.php` del módulo usando el método `endpoint()`.
  ```php
  Router::route("users/endpoint/list")
    ->endpoint("users@list")
    ->middleware("auth_admin")
    ->permission("users.list")
    ->register();
  ```
- **Consumo en JS**:
  ```javascript
  fetch(APP_ADMIN_URL + "/users/endpoint/list", {
    method: "GET",
    headers: { "Accept": "application/json" }
  })
  .then(response => response.json())
  .then(data => console.log(data));
  ```
