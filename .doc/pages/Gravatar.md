# Gravatar

La clase `Gravatar` es una utilidad diseñada para la obtención y renderizado de avatares de usuario a través del servicio global Gravatar, basándose en la dirección de correo electrónico del usuario.

## Características Principales

- **Interfaz Fluida**: Configuración encadenable de todos los parámetros de la petición.
- **Hashing Automático**: Gestiona internamente la conversión del correo electrónico a hash MD5, requisito del servicio.
- **Personalización Visual**: Permite definir tamaños, imágenes por defecto y clasificaciones de contenido (rating).
- **Inyección de Atributos**: Soporte para añadir cualquier atributo HTML (ID, clases, data-attrs) a la etiqueta de imagen generada.

## Métodos de Configuración (Fluent)

- `email(string $email)`: Define el correo electrónico. El valor se normaliza automáticamente (trim y minúsculas) antes de procesarse. **Obligatorio**.
- `size(int $size)`: Define el tamaño del avatar en píxeles (Eje: 150).
- `default(string $default)`: Define la imagen a mostrar si el correo no tiene un avatar asociado. Valores comunes: `mp` (silueta), `identicon`, `monsterid`, `wavatar`, `retro`.
- `rating(string $rating)`: Define la clasificación de contenido permitida. Valores: `g` (apto para todos), `pg`, `r`, `x`.
- `attrs(array $attributes)`: Recibe un arreglo asociativo de atributos para la etiqueta `<img>`.

---

## Generación de Resultados

### `url()`
Construye y devuelve la URL directa a la imagen en los servidores de Gravatar. Valida que el email haya sido proporcionado antes de generar la ruta.

### `image()`
Genera la etiqueta HTML `<img>` completa, incorporando la URL generada y todos los atributos definidos mediante el método `attrs()`.

---

## Ejemplos de Implementación

### 1. Obtención de URL Simple
```php
$gravatar = new Gravatar();
$url = $gravatar->email('ejemplo@correo.com')
                ->size(200)
                ->url();

echo "La ruta de tu avatar es: " . $url;
```

### 2. Renderizado de Imagen con Clases de Bootstrap
Este ejemplo muestra cómo generar un avatar circular y receptivo para un perfil de usuario.

```php
$gravatar = new Gravatar();

echo $gravatar->email($user_email)
              ->size(128)
              ->default('identicon')
              ->attrs([
                  'class' => 'rounded-circle img-thumbnail border shadow-sm',
                  'alt'   => 'Foto de perfil',
                  'id'    => 'user-avatar'
              ])
              ->image();
```

### 3. Configuración de Rating y Backup
Útil para aplicaciones que requieren controlar la sensibilidad del contenido visual.

```php
$avatar = new Gravatar();
$avatar->email('usuario@dominio.com')
       ->rating('g')         // Solo contenido apto para todo público
       ->default('monsterid') // Si no tiene, mostrar un monstruo aleatorio
       ->size(64);

echo $avatar->image();
```

## Notas Técnicas

- La clase lanza una `RuntimeException` si se intenta generar la URL o la imagen sin haber proporcionado un correo electrónico mediante el método `email()`.
- Se recomienda el uso del método `image()` cuando se desea mantener la lógica de presentación (clases CSS y atributos) encapsulada y reutilizable en diferentes partes de la aplicación.
- Recordar que el servicio Gravatar es público; asegúrese de que el entorno sea compatible con peticiones HTTPS para evitar advertencias de contenido mixto.