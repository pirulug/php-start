---
name: PHP-Start API Standards
description: Reglas estrictas para la creación, seguridad y consumo de la API dentro del framework PHP-Start.
---

# PHP-Start API Standards

Este documento define la arquitectura y los estándares obligatorios para el desarrollo de endpoints de API en el framework PHP-Start.

## 1. Estructura de Archivos y Carpetas

Toda la API reside en el directorio `app/api/` y sigue una estructura modular:

- `app/api/modules.php`: Registro de módulos activos.
- `app/api/{modulo}/`: Carpeta principal del módulo.
- `app/api/{modulo}/router.php`: Definición de rutas del módulo.
- `app/api/{modulo}/actions/`: Implementación de la lógica (JSON).

### Ejemplo de Estructura:
```text
app/api/
├── users/
│   ├── actions/
│   │   └── users.php
│   └── router.php
└── modules.php
```

## 2. Definición de Rutas

Las rutas deben registrarse usando la clase `Router` dentro del contexto de la API. El prefijo `/api` es manejado automáticamente por el core.

### Estándar de Router:
```php
Router::route('mi-endpoint')
  ->action(api_action("modulo.archivo"))
  ->middleware('auth_api')
  ->register();
```

## 3. Seguridad y Autenticación

### API Keys
- El acceso es **exclusivamente** mediante una API Key válida y activa.
- La llave debe enviarse como parámetro GET: `?api_key=TU_LLAVE`.
- **Formato**: 32 caracteres hexadecimales (generados con `bin2hex(random_bytes(16))`).

### Middleware `auth_api`
- Es **obligatorio** para cualquier endpoint que no sea público.
- Valida la existencia y estado (`status = 1`) de la llave.
- Actualiza automáticamente la columna `api_key_last_used`.
- Si la validación falla, retorna un JSON 401: `{"success": false, "message": "Acceso no autorizado"}`.

## 4. Estándares de Acción (Lógica)

### Respuestas JSON
- El framework (`index.php`) envía automáticamente las cabeceras `application/json`.
- Nunca se debe usar `echo` para HTML; siempre usar `json_encode()`.
- Campos prohibidos: Nunca devolver contraseñas, tokens de sesión o datos sensibles.

### Seguridad PDO
- Seguir estrictamente la regla de `php-start-rules`.
- Usar `bindParam` con variables aisladas.
- Usar `PDO::FETCH_OBJ`.

### Comentarios
- Usar bloques de comentarios estandarizados según `php-start-comments`.

## 5. UI y UX de Gestión

- Al mostrar API Keys en la interfaz (Admin/Home), incluir siempre el botón de copiar.
- **Clipboard Fallback**: Implementar siempre el fallback para entornos no-HTTPS:
```javascript
if (navigator.clipboard && window.isSecureContext) {
  navigator.clipboard.writeText(text).then(success);
} else {
  // Método textarea tradicional...
}
```

## 6. Ejemplo de Implementación Completa

### Action (`app/api/test/actions/list.php`):
```php
<?php
/**
 * =========================================================
 * ACTION: API TEST LIST
 * =========================================================
 */

$sql = "SELECT id, name FROM table ORDER BY id DESC";
$stmt = $connect->prepare($sql);
$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_OBJ);

echo json_encode([
  "success" => true,
  "data" => $data
]);
exit();
```
