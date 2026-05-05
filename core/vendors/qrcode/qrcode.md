# QRCode Generator

Generador de códigos QR (2D) en un solo archivo PHP. Licencia MIT.

Optimizado y modernizado para PHP 8.5 con tipado estricto.

## Características

- Salida directa en **PNG** usando la librería GD
- Soporta niveles de corrección de errores:
  - L, M, Q, H
- Configuración flexible de tamaño, padding y estilo

---

## Uso como script directo (HTTP GET / POST)

```
qrcode.php?s={symbology}&d={data}&{options}
```

### Ejemplo

```
qrcode.php?s=qrh&d=HELLO%20WORLD&sf=8&md=0.8
```

> ⚠️ Nota: Debes codificar caracteres no alfanuméricos con URL encoding  
> Ejemplo: `&` → `%26`, `?` → `%2F`

---

## Uso como librería en PHP

```php
include 'qrcode_generator.php';

// Instanciar con datos y opciones
$qr = new QRCode('HELLO WORLD', ['s' => 'qrh', 'sf' => 8]);

/* Salida directa como PNG */
$qr->output_image();

/* Generar imagen y guardar en archivo */
$image = $qr->render_image();
imagepng($image, 'my_qr_code.png');
```

> ⚠️ Nota: En este modo **NO** debes usar URL encoding.

---

## Parámetros

### s — Nivel de corrección / simbología

- `qrl` → Nivel L (~7% corrección)
- `qrm` → Nivel M (~15% corrección)
- `qrq` → Nivel Q (~25% corrección)
- `qrh` → Nivel H (~30% corrección)

---

### d — Datos

- Texto o contenido a codificar
- Para soporte Kanji: usar encoding **Shift-JIS**

---

## Opciones de renderizado

### Dimensiones

- `w` → ancho de imagen (override de escala)
- `h` → alto de imagen (override de escala)

---

### Escalado

- `sf` → factor de escala (default: 4)
- `sx` → escala horizontal (override de `sf`)
- `sy` → escala vertical (override de `sf`)

---

### Padding (Quiet Zone)

- `p` → padding general (default: 0)
- `pv` → padding vertical (default: `p`)
- `ph` → padding horizontal (default: `p`)
- `pt` → padding superior
- `pb` → padding inferior
- `pl` → padding izquierdo
- `pr` → padding derecho

---

### Colores (hex RRGGBB)

- `bc` → color de fondo (ej: FFFFFF)
- `fc` → color de módulos/cuadrados (ej: 000000)

---

### Estilo

- `md` → densidad de módulos (0 a 1, default: 1)  
  Permite generar QR con estilo de puntos (dots)

---
