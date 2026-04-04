# Gestión de CAPTCHA

El sistema de seguridad contra interacciones automatizadas se basa en dos componentes principales: `CaptchaManager` (el orquestador de nivel superior) y `Captcha` (el generador local de imágenes). Esta arquitectura permite alternar fácilmente entre soluciones propias y servicios de terceros como Google o Cloudflare.

---

## CaptchaManager

La clase `CaptchaManager` actúa como un administrador centralizado que permite configurar, renderizar y validar diferentes tipos de CAPTCHA mediante una interfaz fluida.

### Tipos de CAPTCHA Soportados
- `vanilla`: Generación de imagen local mediante la librería GD.
- `recaptcha`: Integración con Google reCAPTCHA v2.
- `cloudflare`: Integración con Cloudflare Turnstile (alternativa de privacidad).

### Métodos de Configuración (Fluent)
- `enabled(bool $value)`: Activa o desactiva el sistema por completo.
- `type(string $type)`: Define el proveedor a utilizar (`vanilla`, `recaptcha` o `cloudflare`).
- `google_recaptcha_site_key(string $key)`: Configura la clave de sitio para Google.
- `google_recaptcha_secret_key(string $key)`: Configura la clave secreta para Google.
- `cloudflare_site_key(string $key)`: Configura la clave de sitio para Cloudflare.
- `cloudflare_secret_key(string $key)`: Configura la clave secreta para Cloudflare.

### Métodos Principales
- `render()`: Devuelve el código HTML y los scripts necesarios para mostrar el CAPTCHA en el formulario.
- `validate(array $post)`: Recibe el arreglo `$_POST` y valida la respuesta según el proveedor seleccionado. Si el sistema está desactivado, siempre devuelve `true`.

---

## Captcha (Generador Local)

La clase `Captcha` se encarga de crear imágenes dinámicas con ruido visual para evitar que software de OCR lea el código.

### Personalización de la Imagen
- `width(int $width)` / `height(int $height)`: Define las dimensiones de la imagen generada.
- `codeLength(int $length)`: Define la cantidad de caracteres en el código.
- `font(string $path)`: Permite especificar una fuente TrueType (TTF).
- `background(string $type)`: Define el patrón de ruido visual (`grid`, `lines` o `dots`).
- `sessionKey(string $key)`: Permite cambiar la clave de sesión donde se almacena el código.

### Tipos de Caracteres
- `number()`: Solo números (excluyendo 0 y 1 para evitar confusión visual).
- `letter()`: Solo letras mayúsculas (excluyendo I y O).
- `alphanumeric()`: Combinación de ambos (predeterminado).

---

## Ejemplos de Implementación

### 1. Configuración de Google reCAPTCHA v2
```php
$manager = new CaptchaManager();
$manager->type('recaptcha')
        ->google_recaptcha_site_key('TU_SITE_KEY')
        ->google_recaptcha_secret_key('TU_SECRET_KEY');

// En la vista del formulario
echo $manager->render();

// En el controlador que recibe el POST
if (!$manager->validate($_POST)) {
    die("Error: El CAPTCHA no es válido.");
}
```

### 2. Configuración de Cloudflare Turnstile
```php
$manager = new CaptchaManager();
$manager->type('cloudflare')
        ->cloudflare_site_key('TU_SITE_KEY')
        ->cloudflare_secret_key('TU_SECRET_KEY');

echo $manager->render();
```

### 3. Uso de CAPTCHA Local (Vanilla)
El modo `vanilla` utiliza una ruta interna (`plugins/img`) que llama directamente a `Captcha::generate()`.
```php
$manager = new CaptchaManager();
$manager->type('vanilla');

echo $manager->render();

// Validación manual si no se usa el manager
if (!Captcha::validate($_POST['captcha'])) {
    die("Código incorrecto.");
}
```

### 4. Personalización Avanzada del CAPTCHA Local
Si deseas generar una imagen de CAPTCHA con parámetros específicos fuera del manager:
```php
$captcha = new Captcha();
$captcha->width(250)
        ->height(100)
        ->codeLength(5)
        ->background('dots')
        ->number()
        ->generate(); // Este método finaliza la ejecución y envía la imagen al navegador
```

## Consideraciones Técnicas
- **Sesiones**: Tanto el generador local como el validador requieren que la sesión de PHP esté activa. La clase maneja automáticamente `session_start()` si no se ha iniciado.
- **Librería GD**: El modo `vanilla` requiere la extensión `gd` de PHP habilitada en el servidor.
- **Seguridad**: Una vez validado un CAPTCHA local con éxito, el sistema elimina el código de la sesión para evitar ataques de reutilización ("replay attacks").
