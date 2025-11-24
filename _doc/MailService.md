# Documentación de la clase `MailService`

La clase **MailService** proporciona un sistema completo para enviar correos mediante PHPMailer, con un enfoque fluido (métodos encadenados) para configurar tanto la conexión SMTP como los datos del correo a enviar.

## Propósito de la clase

Ofrecer una interfaz sencilla y flexible para:

*   Configurar credenciales SMTP.
*   Inicializar PHPMailer de forma segura.
*   Construir correos electrónicos mediante métodos encadenados.
*   Enviar correos con o sin adjuntos.

- - -

## Configuración SMTP

### host(string $host)

Establece el servidor SMTP utilizado para enviar correos.

### email(string $email)

Define el correo del remitente y usuario SMTP.

### password(string $password)

Establece la contraseña asociada al correo SMTP.

### port(int $port)

Configura el puerto del servidor SMTP (por ejemplo, 587 o 465).

### encryption(string $enc)

Especifica el tipo de cifrado (TLS, SSL). Si no se indica, se utiliza STARTTLS por defecto.

### init()

Inicializa PHPMailer con la configuración proporcionada. Valida que los datos mínimos estén completos (host, email, password, port).

- - -

## Métodos Encadenados (Fluent API)

### to(string $to)

Define el destinatario del correo.

### subject(string $subject)

Especifica el asunto del correo.

### body(string $body)

Establece el cuerpo del correo en formato HTML.

### attach(string $filePath)

Agrega un archivo adjunto si existe físicamente en el servidor.

- - -

## Envío del correo

### send(?string $to = null, ?string $subject = null, ?string $body = null, array $attachments = \[\])

Envía el correo utilizando PHPMailer. Soporta dos modos:

*   **Modo directo**: pasando parámetros al método.
*   **Modo fluido**: usando los valores previamente definidos con los métodos encadenados.

Verifica que el servicio esté inicializado y que existan datos mínimos (destinatario, asunto y cuerpo).

En caso de éxito devuelve:
```json
{
  "success": true,
  "message": "Correo enviado correctamente"
}
```

En caso de error devuelve:

```json
{
  "success": false,
  "message": "Mensaje de error"
}
```

- - -

## Ejemplo de uso (Fluido / Builder)
```php
(new MailService())
    ->host('smtp.servidor.com')
    ->email('mi\_correo@dominio.com')
    ->password('clave123')
    ->port(587)
    ->encryption('tls')
    ->init()
    ->to('destino@correo.com')
    ->subject('Prueba de correo')
    ->body('<h1>Hola</h1> Este es un mensaje de prueba.')
    ->attach('/ruta/archivo.pdf')
    ->send();
```
