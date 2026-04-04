# Documentación de accept en `<input type="file">`

El atributo accept le dice al navegador qué tipos de archivos puede seleccionar el usuario.
Puedes usar tres formas:

Extensiones de archivo → .jpg, .pdf, .docx

Tipos MIME completos → image/png, application/pdf

Categorías MIME → image/*, video/*, audio/*

## 1. Categorías generales

image/* → todas las imágenes (jpg, png, gif, webp, svg, etc.)

video/* → todos los videos (mp4, webm, avi, etc.)

audio/* → todos los audios (mp3, wav, ogg, etc.)

## 2. Tipos MIME comunes

### 📄 Documentos

- application/pdf → PDF
- application/msword → Word (.doc)
- application/vnd.openxmlformats-officedocument.wordprocessingml.document → Word moderno (.docx)
- application/vnd.ms-excel → Excel (.xls)
- application/vnd.openxmlformats-officedocument.spreadsheetml.sheet → Excel moderno (.xlsx)
- application/vnd.ms-powerpoint → PowerPoint (.ppt)
- application/vnd.openxmlformats-officedocument.presentationml.presentation → PowerPoint moderno (.pptx)
- text/plain → TXT
- text/csv → CSV
- application/rtf → RTF

### 🖼️ Imágenes

- image/jpeg → JPG/JPEG
- image/png → PNG
- image/gif → GIF
- image/webp → WebP
- image/svg+xml → SVG
- image/bmp → BMP
- image/tiff → TIFF
- image/heif → HEIF
- image/heic → HEIC (formato de iPhone)

### 🎵 Audio

- audio/mpeg → MP3
- audio/wav → WAV
- audio/ogg → OGG
- audio/aac → AAC
- audio/flac → FLAC

### 🎬 Video

- video/mp4 → MP4
- video/webm → WebM
- video/ogg → OGV
- video/x-msvideo → AVI
- video/quicktime → MOV

### 3. Uso combinado

Puedes combinar varios tipos separados por comas:

```html
<!-- Solo PDFs y Word -->
<input type="file" accept=".pdf,application/pdf,.doc,.docx">

<!-- Solo imágenes JPG y PNG -->
<input type="file" accept=".jpg,.jpeg,.png">

<!-- Todos los videos y audios -->
<input type="file" accept="video/*,audio/*">
```

## 4. Recomendación práctica

Para imágenes: `accept="image/*"`

Para PDFs: `accept=".pdf,application/pdf"`

Para documentos de oficina: `accept=".doc,.docx,.xls,.xlsx,.ppt,.pptx,.pdf"`

Para medios (multimedia): `accept="image/*,video/*,audio/*"`