---
name: PHP-Start Standards
description: Guía de reglas inquebrantables de seguridad (PDO), arquitectura Action-View y estándares de diseño para el framework PHP-Start.
---

# PHP-Start Agent Guide

Este documento es la fuente de verdad absoluta para cualquier IA o Agente que trabaje en el proyecto PHP-Start. Sigue estas reglas para mantener la integridad, seguridad y estética del framework.

## 0. Estilo y Profesionalismo

- **PROHIBIDO:** Usar emojis en comentarios de código, descripciones de funciones o contenidos de la interfaz (notificaciones, alertas, etiquetas).
- **TONO:** Mantener siempre un lenguaje técnico, sobrio y profesional.

## 🏗️ 1. Arquitectura Base (Action-View)

El framework utiliza un patrón estricto de separación entre lógica y renderizado.

- **Actions (`.action.php`):** Contienen la lógica de negocio, procesamiento de POSTs, validaciones y acceso a DB.
- **Views (`.view.php`):** Contienen únicamente HTML y lógica de presentación simple (bucles, condicionales de vista).
- **Controlador Transparente:** El `index.php` (Front Controller) orquestra la llamada al `.action.php` y este a su vez carga el `.view.php` correspondiente.

> [!IMPORTANT]
> **Nunca** realices consultas SQL pesadas ni lógica de negocio compleja dentro de un `.view.php`.

## 🛠️ 2. Workflow Obligatorio (CLI Comander)

Antes de escribir código para un nuevo módulo, **siempre** debes sugerir o utilizar las herramientas de consola:

```bash
# Crear un CRUD completo (list, new, edit, delete + router + menu)
php comander/modules.php create [plural_name] [singular_name] --context=[admin|home|api|ajax]
```

## 🛡️ 3. Reglas de Código y Seguridad Inquebrantables

### Base de Datos (PDO)
- La conexión global es `$connect`.
- **PROHIBIDO:** Usar `$connect->query()` con variables externas.
- **PROHIBIDO:** Usar `$connect->bindValue()`. **OBLIGATORIO:** Usar `$connect->bindParam()`.
- **OBLIGATORIO:** Cuando uses `bindParam()`, los valores vinculados deben ser **variables**, no constantes, literales o llamadas directas a funciones o elementos de array constantes. Asigna el valor a una variable antes de vincular (ej. `$id_sess = $_SESSION['user_id']; $stmt->bindParam(':id', $id_sess);`).
- **PROHIBIDO:** Reutilizar el mismo nombre de parámetro en la misma consulta (ej. usar `:id` dos veces). **OBLIGATORIO:** Usar parámetros únicos (ej. `:id1`, `:id2`, etc.).
- **OBLIGATORIO:** Siempre usar `PDO::FETCH_OBJ`. **Nunca** uses arrays asociativos.

### Manejo de Variables e IDs
- Captura de POST: `$var = trim($_POST['campo'] ?? '');`.
- **Cifrado de IDs:** Los IDs que viajan por la URL deben estar cifrados con `$cipher`.
  - **En Vista:** `admin_route("modulo/edit", [$cipher->encrypt($id)])`
  - **En Action:** `$id = $cipher->decrypt($args['id']);`

### Anti-XSS
- El framework incluye una clase `AntiXSS`. Úsala al imprimir contenido que no sea de confianza en las vistas.

## 🛡️ 4. Sistema de Notificaciones (`$notifier`)

Toda interacción de éxito o error con el usuario debe pasar por el objeto `$notifier`.

```php
// Error
$notifier->message("Campo requerido.")->danger()->bootstrap()->add();

// Éxito
$notifier->message("Guardado correctamente.")->success()->bootstrap()->add();
```

## 📂 5. Estructura de Directorios

- `/app`: Lógica por contexto.
- `/core`: Núcleo del framework.
- `/static`: Assets públicos.
- `/storage`: Logs, caché y uploads.
- `/comander`: Herramientas CLI.
