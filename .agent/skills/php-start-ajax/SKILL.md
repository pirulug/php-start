---
name: PHP-Start AJAX Standard
description: Reglas para la creación y seguridad de endpoints AJAX en el framework PHP-Start.
---

# PHP-Start AJAX Standard

Este documento define la arquitectura y los estándares obligatorios para el desarrollo de peticiones y respuestas AJAX dentro del sistema.

## 1. Arquitectura y Ubicación

Todas las peticiones AJAX se gestionan de forma modular en el directorio `app/ajax/`:

- `app/ajax/modules.php`: Listado de módulos habilitados para AJAX.
- `app/ajax/{modulo}/router.php`: Definición de rutas específicas del módulo.
- `app/ajax/{modulo}/actions/`: Archivos de lógica que procesan la petición.

### Jerarquía:
```text
app/ajax/
├── auth/
│   ├── actions/
│   │   └── reset.php
│   └── router.php
└── modules.php
```

## 2. Definición de Rutas (Contexto AJAX)

El sistema detecta automáticamente cuando una petición comienza con `/ajax` (`PATH_AJAX`) y carga el contexto apropiado. Al definir rutas dentro del módulo, **no se debe incluir** el prefijo `/ajax`.

### Registro de Ruta:
```php
Router::route('mi-ruta-ajax')
  ->action(ajax_action("modulo.archivo"))
  ->middleware('auth_ajax')
  ->register();
```

## 3. Seguridad y Control de Acceso

### Middleware `auth_ajax`
- Es el estándar para proteger rutas que requieren una sesión de usuario.
- Verifica si `$_SESSION['signin']` es verdadero.
- Si falla, retorna un JSON: `{"success": false, "message": "No logeado"}`.

### Middleware `permission`
- Se puede encadenar para restringir acciones a permisos específicos:
```php
Router::route('borrar-item')
  ->action(ajax_action("items.delete"))
  ->middleware('auth_ajax')
  ->permission('items.delete')
  ->register();
```

## 4. Estándares de Respuesta y Formato

- **Cabeceras**: El archivo `index.php` establece automáticamente `Content-Type: application/json` y cabeceras CORS básicas para el contexto AJAX.
- **Salida**: Se debe usar exclusivamente `json_encode()` para retornar datos.
- **Estructura Recomendada**:
  ```json
  {
    "success": true,
    "message": "Operación exitosa",
    "data": { ... }
  }
  ```
- **Prohibición**: Nunca enviar `echo` de fragmentos HTML o texto plano, a menos que el objetivo específico sea inyectar HTML (lo cual debe ser la excepción, no la regla).

## 5. Mejores Prácticas

- **Base de Datos**: Siempre usar `bindParam` con variables aisladas según `php-start-rules`.
- **Limpieza**: No es necesario incluir `header('Content-Type: ...')` manualmente dentro del action, el core ya lo hace.
- **Comentarios**: Mantener bloques de sección estandarizados para legibilidad.
