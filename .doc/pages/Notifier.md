# Notifier

La clase `Notifier` es un sistema centralizado para la gestión de notificaciones al usuario. Permite almacenar mensajes en la sesión para ser mostrados tras una redirección, soportando múltiples formatos de visualización como alertas de Bootstrap, notificaciones tipo Toast (ToastifyJS) y ventanas emergentes (SweetAlert2).

## Características Principales

- **Persistencia en Sesión**: Los mensajes se guardan en `$_SESSION` y se eliminan automáticamente tras ser mostrados (patrón Flash Messages).
- **Múltiples Formatos**: Soporte nativo para tres métodos de visualización distintos.
- **Interfaz Fluida**: Configuración encadenable de mensajes, tipos y métodos.
- **Modo de Consulta**: Capacidad para verificar si existen mensajes de cierto tipo antes de renderizar contenedores en la interfaz.

## Métodos de Configuración

### Definición del Mensaje y Tipo
- `message(string $text)`: Define el cuerpo del mensaje.
- `success(string $message = null)`: Establece el tipo como 'success'. Si se pasa el parámetro, también define el mensaje.
- `danger(string $message = null)`: Establece el tipo como 'danger'.
- `warning(string $message = null)`: Establece el tipo como 'warning'.
- `info(string $message = null)`: Establece el tipo como 'info'.

### Definición del Método de Visualización
- `bootstrap()`: El mensaje se mostrará como una alerta de Bootstrap (Predeterminado).
- `toast()`: El mensaje se mostrará usando ToastifyJS.
- `sweetalert()`: El mensaje se mostrará usando SweetAlert2.

### Acción de Registro
- `add()`: Registra el mensaje configurado en la sesión. Lanza una excepción si el mensaje está vacío.

---

## Modo de Consulta (Query Mode)

Permite verificar la existencia de mensajes sin consumirlos. Se activa mediante el método `can()`.

- `can()->any()`: Devuelve `true` si hay cualquier notificación pendiente.
- `can()->success()`: Devuelve `true` si hay notificaciones de éxito pendientes.
- `can()->bootstrap()->danger()`: Verifica específicamente si hay alertas de peligro para Bootstrap.

---

## Renderizado (Salida de Interfaz)

Estos métodos deben ser llamados en la vista o layout principal para mostrar las notificaciones acumuladas.

- `showBootstrap()`: Genera el HTML de las alertas de Bootstrap.
- `showToasts()`: Genera el bloque `<script>` para disparar los Toasts.
- `showSweetAlerts()`: Genera el bloque `<script>` para disparar los SweetAlerts.

---

## Ejemplos de Uso

### Integración Estándar (Bootstrap)
Se inicializa automáticamente en `core/bootstrap/base.php` como `$notifier`.

```php
// Ejemplo en un controlador tras guardar datos
$notifier->success('Los cambios han sido guardados correctamente.')
         ->bootstrap()
         ->add();

// Ejemplo en el Layout principal para mostrar alertas
$notifier->showBootstrap();
```

### Uso de Notificaciones Tipo Toast
```php
// Registro del mensaje
$notifier->info('Bienvenido de nuevo')
         ->toast()
         ->add();

// En el pie de página del Layout
$notifier->showToasts();
```

### Ventana Emergente (SweetAlert)
```php
$notifier->danger('No tienes permisos para realizar esta acción.')
         ->sweetalert()
         ->add();

$notifier->showSweetAlerts();
```

### Verificación Condicional
```php
if ($notifier->can()->any()) {
    // Código que solo se ejecuta si hay notificaciones pendientes
}
```

## Notas Técnicas

- La clase inicia la sesión automáticamente mediante `session_start()` si no se ha detectado una activa.
- Al renderizar los mensajes mediante los métodos `show*`, estos se eliminan de la sesión (`unset`) para asegurar que no se repitan en la siguiente carga de página.
- Para los métodos `toast()` y `sweetalert()`, es necesario que las librerías `ToastifyJS` y `SweetAlert2` estén cargadas en el front-end.