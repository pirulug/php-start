# Gestión de Correo Electrónico (Mail)

El sistema de correo proporciona una interfaz unificada para el envío de mensajes a través de SMTP, integrando una utilidad de servidor (PHP) y una librería de cliente (JavaScript).

---

## Componente de Sistema (PHP)

El núcleo del sistema de correo reside en `core/system/mail/`. Gracias al **Autoloader** global, la lógica de correo solo se carga en memoria cuando se utiliza el componente por primera vez.

### Clase `Mail`

Esta clase es el "punto de acceso" recomendado para realizar envíos desde el backend. Implementa **Lazy Loading**, por lo que la librería pesada de envío solo se carga al llamar a `init()`.

#### `Mail::init(?array $configOverride = null)`
Inicializa una instancia de `MailService` pre-configurada con los ajustes del sitio (base de datos).
- **$configOverride**: (Opcional) Permite sobrescribir ajustes como el host, puerto o credenciales para un envío específico.

**Ejemplo de uso:**
```php
// Envío simple usando los ajustes globales
$mail = Mail::init();
$mail->send('destino@ejemplo.com', 'Hola Mundo', 'Contenido del mensaje');

// Envío con configuración personalizada
$mail = Mail::init([
  'name' => 'Soporte Técnico',
  'email' => 'soporte@miempresa.com'
]);
$mail->send('cliente@dominio.com', 'Ticket #123', 'Su mensaje ha sido recibido.');
```

---

## Librería de Cliente (JavaScript)

Para facilitar el envío de correos desde el frontend (ej. formularios de contacto, botones de prueba), el sistema incluye una librería ligera basada en `fetch`.

**Archivo:** `static/assets/js/mail.js`

### `Mail.send({ to, subject, body })`
Envía una petición POST al endpoint del sistema `/mail` para procesar el envío.

**Ejemplo de uso:**
```javascript
Mail.send({
  to: "usuario@empresa.com",
  subject: "Asunto del Correo",
  body: "Contenido del mensaje"
}).then(data => {
  if (data.success) {
    console.log("Correo enviado!");
  } else {
    console.error("Error:", data.message);
  }
});
```

---

## Configuración y Administración

Las credenciales de acceso SMTP se gestionan desde el panel administrativo:
- **Ruta**: Ajustes > Correo (SMTP)
- **Parámetros**: Host, Puerto, Email, Contraseña y Tipo de Encriptación (TLS/SSL).

---

## Estructura de Archivos

- **`core/system/mail/mail.helper.php`**: El ayudante (Helper) cargado automáticamente por el sistema. Proporciona la clase `Mail`.
- **`core/system/mail/mail.system.php`**: La lógica pesada del núcleo (`MailService`). Se carga bajo demanda (*Lazy Load*).
- **`core/system/mail/mail.action.php`**: Endpoint AJAX que procesa las peticiones de envío.
- **`core/system/mail/router.php`**: Registro de la ruta `/mail` en el núcleo del sistema.
- **`static/assets/js/mail.js`**: Librería JavaScript para consumo del API.