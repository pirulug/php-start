# DropImg

**DropImg** es una librería ligera y moderna diseñada para transformar inputs de archivos estándar en zonas de carga interactivas con previsualización en tiempo real. Está optimizada para integrarse perfectamente con el diseño premium del framework.

## Características principales
- **Previsualización instantánea**: Ver la imagen antes de subirla.
- **Validación integrada**: Control de peso máximo y extensiones permitidas.
- **Modos de aspecto**: Soporte para formatos cuadrados, rectangulares y circulares.
- **Recomendaciones inteligentes**: Muestra automáticamente dimensiones y formatos recomendados al usuario.
- **Fallback seguro**: Si ocurre un error en la inicialización, el input vuelve a su estado estándar de HTML.

---

## Instalación

Para utilizar DropImg, debes incluir los archivos CSS y JS en tu layout o vista:

### CSS
```php
<?= static_libs_css("dropzone", "dropimg.css") ?>
```

### JS
```php
<?= static_libs_js("dropzone", "dropimg.js") ?>
```

> [!NOTE]
> La librería se inicializa automáticamente al cargar el DOM. Si inyectas contenido dinámicamente (vía AJAX), puedes reinicializarla usando `DropImg.init()`.

---

## Configuración (Atributos Data)

El comportamiento de DropImg se controla mediante atributos `data-*` aplicados directamente al elemento `<input type="file">`.

| Atributo | Descripción | Valor por defecto |
| :--- | :--- | :--- |
| `data-dropimg` | **Requerido**. Identifica el input para ser transformado. | - |
| `data-width` | Ancho base para el cálculo de aspecto y recomendación. | `300` |
| `data-height` | Alto base para el cálculo de aspecto y recomendación. | `200` |
| `data-aspect` | Define la forma de la zona. Valores: `square`, `circle`. | `square` |
| `data-default` | URL de la imagen que se mostrará inicialmente. | `null` |
| `data-max-size` | Peso máximo permitido en Megabytes (MB). | `2` |
| `data-no-recommend`| Si se incluye, oculta el texto de recomendación inferior. | - |
| `accept` | Extensiones permitidas (estándar HTML). | `image/*` |

---

## Ejemplos de Uso

### 1. Logo Estándar (Rectangular)
Ideal para logos o banners con dimensiones específicas.

```html
<div class="mb-3">
  <label class="form-label">Logo del Sitio</label>
  <input 
    type="file" 
    name="site_logo" 
    data-dropimg 
    data-width="400" 
    data-height="120"
    data-default="<?= storage_uploads('logo.png', 'site') ?>"
    accept=".png,.jpg,.webp"
  >
</div>
```

### 2. Foto de Perfil (Circular)
Usando `data-aspect="circle"`. En este modo, el ancho se usa como diámetro.

```html
<div class="mb-3">
  <label class="form-label">Foto de Perfil</label>
  <input 
    type="file" 
    name="user_avatar" 
    data-dropimg 
    data-width="150" 
    data-aspect="circle"
    data-default="<?= user_session()->avatar ?>"
    accept="image/*"
  >
</div>
```

### 3. Validación Estricta
Limitando el peso a 1MB y ocultando las recomendaciones.

```html
<input 
  type="file" 
  name="doc_image" 
  data-dropimg 
  data-max-size="1"
  data-no-recommend
  required
>
```