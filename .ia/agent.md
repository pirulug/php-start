# PHP-Start Agent Guide 🤖

Este documento sirve como la fuente de verdad absoluta para cualquier IA o Agente que trabaje en el proyecto **PHP-Start**. Sigue estas reglas para mantener la integridad, seguridad y estética del framework.

---

## 🏗️ 1. Arquitectura Base (Action-View)

El framework utiliza un patrón estricto de separación entre lógica y renderizado.

- **Actions (`.action.php`):** Contienen la lógica de negocio, procesamiento de POSTs, validaciones y acceso a DB.
- **Views (`.view.php`):** Contienen únicamente HTML y lógica de presentación simple (bucles, condicionales de vista).
- **Controlador Transparente:** El `index.php` (Front Controller) orquestra la llamada al `.action.php` y este a su vez carga el `.view.php` correspondiente.

> [!IMPORTANT]
> **Nunca** realices consultas SQL pesadas ni lógica de negocio compleja dentro de un `.view.php`.

---

## 🛠️ 2. Workflow Obligatorio (CLI Comander)

Antes de escribir código para un nuevo módulo, **siempre** debes sugerir o utilizar las herramientas de consola:

```bash
# Crear un CRUD completo (list, new, edit, delete + router + menu)
php comander/modules.php create [plural_name] [singular_name] --context=[admin|home|api|ajax]

# Ejemplo:
php comander/modules.php create products product --context=admin
```

Otras herramientas:
- `php comander/reset-cache.php`: Limpia la caché de rutas (`storage/cache/`).
- `php comander/reset-logos.php`: Restablece configuración visual por defecto.

---

## 🛡️ 3. Reglas de Código y Seguridad Inquebrantables

### Base de Datos (PDO)
- La conexión global es `$connect`.
- **PROHIBIDO:** Usar `$connect->query()` con variables externas.
- **OBLIGATORIO:** Usar `$connect->prepare()` y `bindParam()` o `bindValue()`.
- **OBLIGATORIO:** Siempre usar `PDO::FETCH_OBJ`. **Nunca** uses arrays asociativos.

```php
$stmt = $connect->prepare("SELECT * FROM users WHERE user_id = :id");
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_OBJ);
```

### Manejo de Variables e IDs
- Captura de POST: `$var = trim($_POST['campo'] ?? '');`.
- **Cifrado de IDs:** Los IDs que viajan por la URL deben estar cifrados con `$cipher`.
  - **En Vista:** `admin_route("modulo/edit", [$cipher->encrypt($id)])`
  - **En Action:** `$id = $cipher->decrypt($args['id']);`

### Anti-XSS
- El framework incluye una clase `AntiXSS`. Úsala al imprimir contenido que no sea de confianza en las vistas.

---

## 🔔 4. Sistema de Notificaciones (`$notifier`)

Toda interacción de éxito o error con el usuario debe pasar por el objeto `$notifier`.

```php
// Error
$notifier->message("Campo requerido.")->danger()->bootstrap()->add();

// Éxito
$notifier->message("Guardado correctamente.")->success()->bootstrap()->add();

// Validación final antes de acción crítica
if (!$notifier->can()->danger()) {
    // Procede a insertar/actualizar
}
```

---

## 🎨 5. UI/UX: Bootstrap 5 y Dark Mode

### Restricciones Críticas
Para asegurar la compatibilidad con el **Dark Mode**, tienes **PROHIBIDO** usar estas clases de Bootstrap:
- ❌ `.shadow` (usa bordes nativos).
- ❌ `.shadow-sm` (usa bordes nativos).
- ❌ `.bg-white` o `.bg-light`.
- ❌ `.text-white` o `.text-light`.
- ❌ `.border-0` en elementos `.card`.
- ❌ `.btn-group` en columnas de acción de tablas (usa botones individuales).

### Bloques de Template
Cada vista debe definir sus bloques obligatorios:
```php
<?php start_block("title") ?> Mi Título <?php end_block() ?>
<?php start_block("css") ?> <!-- Estilos específicos --> <?php end_block() ?>
<?php start_block("js") ?> <!-- Scripts de inicialización --> <?php end_block() ?>
```

---

## 🧩 6. Catálogo de Componentes Core

- **`ActionBtn::`**: Generador de botones estandarizados para tablas.
  - `::edit()`, `::delete()`, `::view()`, `::active()`, `::deactivate()`.
- **`PaginatorPlus`**: Manejo de paginación fluida en `list.action.php`.
- **`UploadImage`**: Procesamiento de imágenes con conversión automática a WebP y redimensionamiento.
  - Se vincula con el plugin `DropImg` en el frontend.

---

## 📂 7. Estructura de Directorios

- `/app`: Lógica por contexto (admin, api, ajax, home).
- `/core`: Núcleo del framework, funciones, libs y middlewares.
- `/static`: Assets públicos (CSS, JS, Plugins).
- `/storage`: Logs, caché y uploads (ignorado por Git).
- `/comander`: Herramientas de desarrollo.
- `/db`: Scrips sql.
- `/.doc`: Documentación técnica detallada.
- **Ignorar siempre la carpeta `/install`** (solo para despliegue inicial).

---

> [!TIP]
> Si tienes dudas técnicas, consulta los archivos en `/.doc/` o las habilidades específicas en `.ia/skill/`.
