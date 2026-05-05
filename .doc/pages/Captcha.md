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

---

## Captcha (Generador Local)

La clase `Captcha` reside en `core/system/captcha/` y se encarga de crear imágenes dinámicas con ruido visual. Es cargada automáticamente por el sistema cuando es necesaria.

### Personalización de la Imagen
- `width(int $width)` / `height(int $height)`: Define las dimensiones de la imagen.
- `codeLength(int $length)`: Define la cantidad de caracteres en el código.
- `background(string $type)`: Define el patrón de ruido visual (`grid`, `lines` o `dots`).

---

## Arquitectura de Sistema

El sistema de CAPTCHA está totalmente integrado en el núcleo modular del framework.

### 1. Ruta de Imagen (Vanilla)
El modo `vanilla` utiliza una ruta de sistema pre-registrada que genera la imagen en formato WebP:
- **URL**: `/captcha/img.webp`
- **Ubicación**: `core/system/captcha/captcha.action.php`

### 2. Validación
La validación puede hacerse mediante el manager (recomendado) o directamente usando la clase de sistema.

```php
// Ejemplo con CaptchaManager
$manager = new CaptchaManager();
$manager->type('vanilla');

if (!$manager->validate($_POST)) {
  echo "CAPTCHA Incorrecto";
}
```

---

## Estructura de Archivos

- **`core/system/captcha/captcha.action.php`**: Lógica de generación de imagen y respuesta HTTP.
- **`core/system/captcha/router.php`**: Registro de la ruta `/captcha/img.webp`.
- **`core/system/captcha/captcha.system.php`**: Clase principal de generación (`Captcha`).
- **`core/libs/CaptchaManager.php`**: Orquestador multi-proveedor.

---

## Consideraciones Técnicas
- **Formato WebP**: El sistema genera imágenes WebP por defecto para mayor rendimiento.
- **Sesiones**: Requiere que la sesión PHP esté activa para almacenar y comparar el código generado.
- **Seguridad**: Una vez validado correctamente, el código se elimina de la sesión para evitar ataques de repeticion.