# Guia de Atribucion accept en Inputs de Archivo

El atributo accept en un <input type="file"> permite definir que tipos de archivos el navegador debe permitir seleccionar al usuario. Este atributo mejora la experiencia de usuario (UX) al filtrar automaticamente la ventana de seleccion de archivos.

> [!IMPORTANT]
> El atributo accept es una sugerencia para el navegador. Un usuario experto puede saltarse esta restriccion. Siempre debes validar los archivos en el servidor (PHP) por seguridad.

---

## 1. Categorias Globales (Wildcards)

La forma mas sencilla de permitir grupos enteros de formatos multimedia es mediante comodines.

| Selector | Descripcion | Formatos Comunes |
| :--- | :--- | :--- |
| `image/*` | Todas las imagenes | .jpg, .png, .webp, .svg, .gif |
| `video/*` | Todos los videos | .mp4, .webm, .avi, .mov |
| `audio/*` | Todos los audios | .mp3, .wav, .ogg, .aac |

---

## 2. Referencia de Tipos MIME y Extensiones

Para una seleccion mas precisa, se recomienda usar tanto la extension como el tipo MIME para garantizar compatibilidad entre sistemas.

### Documentos y Oficina
| Formato | Extension | Tipo MIME |
| :--- | :--- | :--- |
| **PDF** | .pdf | application/pdf |
| **Word** | .doc, .docx | application/msword, application/vnd.openxmlformats-officedocument.wordprocessingml.document |
| **Excel** | .xls, .xlsx | application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet |
| **Texto Plano** | .txt | text/plain |
| **CSV** | .csv | text/csv |

### Recursos Visuales y Diseño
| Formato | Extension | Tipo MIME |
| :--- | :--- | :--- |
| **WebP** | .webp | image/webp |
| **JPEG** | .jpg, .jpeg | image/jpeg |
| **PNG** | .png | image/png |
| **SVG** | .svg | image/svg+xml |
| **Photoshop**| .psd | image/vnd.adobe.photoshop |
| **Illustrator**| .ai | application/postscript |

### Comprimidos y Desarrollo
| Formato | Extension | Tipo MIME |
| :--- | :--- | :--- |
| **ZIP** | .zip | application/zip, application/x-zip-compressed |
| **RAR** | .rar | application/x-rar-compressed |
| **JSON** | .json | application/json |
| **XML** | .xml | application/xml, text/xml |
| **SQL** | .sql | application/sql, text/plain |

---

## 3. Ejemplos de Combinaciones Maestras (Copy-Paste)

Aqui tienes las combinaciones mas frecuentes listas para usar en tus proyectos.

### Perfil de Usuario (Foto de perfil)
Permite todos los formatos de imagen comunes, optimizado para web.
```html
<input type="file" accept="image/jpeg, image/png, image/webp, .jpg, .jpeg, .png, .webp">
```

### Documentacion Legal y Contratos
Acepta PDF y formatos de Word para documentos que requieren edicion o lectura.
```html
<input type="file" accept=".pdf, .doc, .docx, application/pdf, application/msword, application/vnd.openxmlformats-officedocument.wordprocessingml.document">
```

### Importacion de Datos (Excel/CSV)
Ideal para procesos de importacion masiva de datos.
```html
<input type="file" accept=".csv, .xls, .xlsx, text/csv, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
```

### Galeria Multimedia Completa
Permite subir imagenes, videos y audios en un mismo selector.
```html
<input type="file" accept="image/*, video/*, audio/*">
```

### Entrega de Proyectos (Comprimidos)
Util para recibir paquetes de archivos o respaldos.
```html
<input type="file" accept=".zip, .rar, .7z, application/zip, application/x-zip-compressed, application/x-rar-compressed, application/x-7z-compressed">
```

### Archivos de Programacion y Configuracion
Para subida de scripts, configuraciones JSON o dumps de base de datos SQL.
```html
<input type="file" accept=".json, .xml, .sql, .txt, application/json, text/xml, application/sql">
```

### Recursos de Diseño Grafico
Acepta formatos de imagen estandar y archivos fuente de diseño profesional.
```html
<input type="file" accept="image/*, .psd, .ai, .eps, image/vnd.adobe.photoshop, application/postscript">
```

---

## 4. Consejos Pro de Implementacion

> [!TIP]
> **Deteccion de Camara en Moviles**: Si usas image/* solo, muchos navegadores moviles ofreceran "Tomar foto" o "Galeria". Si quieres forzar la camara para capturar documentos o DNI, puedes usar:
> ```html
> <input type="file" accept="image/*" capture="environment">
> ```

> [!TIP]
> **Atributo Multiple**: Para permitir la seleccion de varios archivos a la vez en cualquiera de los ejemplos anteriores:
> ```html
> <input type="file" accept=".pdf, .docx" multiple>
> ```

---

## Resumen Final de Mejores Practicas

1.  **Redundancia es Clave**: Pon siempre la extension (.ext) seguida del tipo MIME (type/subtype). Algunos navegadores procesan mejor uno que otro.
2.  **Orden Jerarquico**: Lista los formatos mas comunes primero.
3.  **Espacios**: Puedes poner espacios despues de las comas para mejorar la legibilidad del codigo HTML; los navegadores lo ignoran.