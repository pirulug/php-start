# BarcCode

Generador de códigos de barras lineales (1D) en un solo archivo PHP. Licencia MIT.

Optimizado y modernizado para PHP 8.4+ con tipado estricto.

## Características

- Salida en formatos: **PNG, GIF, JPEG, SVG**
- Soporta múltiples simbologías:
  - UPC-A, UPC-E
  - EAN-13, EAN-8
  - Code 39, Code 93, Code 128
  - Codabar
  - ITF

---

## Uso como script directo (HTTP GET / POST)

```
barcode.php?f={format}&s={symbology}&d={data}&{options}
```

### Ejemplos

```
barcode.php?f=png&s=upc-e&d=06543217
barcode.php?f=svg&s=code-128&d=HELLO%20WORLD&sf=2
```

> ⚠️ Nota: Debes codificar caracteres no alfanuméricos con URL encoding  
> Ejemplo: `&` → `%26`, `?` → `%2F`

---

## Uso como librería en PHP

```php
include 'barcode_generator.php';

$generator = new BarcCode();

/* Salida directa como imagen */
header("Content-Type: image/$format");
$generator->output_image($format, $symbology, $data, $options);

/* Generar imagen bitmap (PNG) y enviar al navegador */
header('Content-Type: image/png');
$image = $generator->render_image($symbology, $data, $options);
imagepng($image);

/* Generar imagen bitmap y guardar en archivo */
$image = $generator->render_image($symbology, $data, $options);
imagepng($image, $filename);

/* Generar SVG y enviar al navegador */
header('Content-Type: image/svg+xml');
$svg = $generator->render_svg($symbology, $data, $options);
echo $svg;

/* Generar SVG y guardar en archivo */
$svg = $generator->render_svg($symbology, $data, $options);
file_put_contents($filename, $svg);
```

> ⚠️ Nota: En este modo **NO** debes usar URL encoding.

---

## Parámetros

### f — Formato de salida

- `png`
- `gif`
- `jpeg`
- `svg`

---

### s — Simbología

- `upc-a`
- `upc-e`
- `ean-8`
- `ean-13`
- `ean-13-pad`
- `ean-13-nopad`
- `ean-128`
- `code-39`
- `code-39-ascii`
- `code-93`
- `code-93-ascii`
- `code-128`
- `codabar`
- `itf`

---

### d — Datos

- Para UPC o EAN: usar `*` para dígito faltante
- Para Codabar: usar `A B C D` o `E N T *` como caracteres de inicio y fin

---

## Opciones de renderizado

### Dimensiones

- `w` → ancho de imagen (override de escala)
- `h` → alto de imagen (override de escala)

### Escalado

- `sf` → factor de escala general (default: 1)
- `sx` → escala horizontal (override de `sf`)
- `sy` → escala vertical (override de `sf`)

---

### Padding

- `p`  → padding general (default: 10)
- `pv` → padding vertical (default: `p`)
- `ph` → padding horizontal (default: `p`)
- `pt` → padding superior
- `pb` → padding inferior
- `pl` → padding izquierdo
- `pr` → padding derecho

---

### Colores (hex #RRGGBB)

- `bc` → color de fondo
- `cs` → color de espacios
- `cm` → color de módulos (barras)
- `tc` → color del texto

---

### Texto

- `tf` → fuente (solo SVG, default: monospace)
- `ts` → tamaño de texto
  - SVG: en puntos (default: 10)
  - PNG/GIF/JPEG: fuente GD (1–5, default: 1)
- `th` → distancia entre texto y código (default: 10)

---

### Módulos y espaciado

- `wq` → ancho de zona silenciosa (default: 1, usar 0 para eliminar)
- `wm` → ancho de módulos estrechos (default: 1)
- `ww` → ancho de módulos anchos (Code 39, Codabar, ITF) (default: 3)
- `wn` → espacio entre caracteres (Code 39, Codabar) (default: 1)

---