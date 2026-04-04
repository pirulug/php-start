# LoginRateLimiter

Clase de seguridad diseñada para mitigar ataques de fuerza bruta y diccionarios mediante el control de intentos de inicio de sesión fallidos. Implementa bloqueos temporales por IP y por cuenta de usuario.

## Características

- **Protección Dual**: Monitorea intentos tanto por la IP del atacante como por el nombre de usuario (para evitar el bloqueo de cuentas legítimas desde IPs rotativas).
- **Bloqueo Progresivo**: Incrementa el tiempo de espera tras cada fallo consecutivo.
- **Persistencia en BD**: Utiliza la tabla `user_access` para mantener el estado de los intentos incluso si el atacante reinicia su sesión de navegador.
- **Mensajes Amigables**: Genera automáticamente el tiempo de espera restante para mostrar al usuario.

## Métodos Principales

### `__construct(PDO $connect)`
Inicializa la clase y detecta automáticamente la IP del cliente.

---

### `fromPost(string $username): self`
Establece el nombre de usuario o login que se está intentando validar.

---

### `resolveUser(): self`
Busca si el nombre de usuario existe en la base de datos para aplicar reglas de bloqueo específicas de cuenta.

---

### `load(): self`
Carga el registro de acceso actual (si existe) desde la base de datos basado en la combinación de IP y Usuario.

---

### `isBlocked(): bool`
Verifica si el acceso actual se encuentra bajo un periodo de bloqueo activo.

---

### `failed(): self`
Registra un intento de inicio de sesión fallido. Incrementa el contador y, si se supera el umbral, establece un tiempo de bloqueo.

---

### `success(): void`
Limpia el registro de intentos fallidos tras un inicio de sesión exitoso.

---

### `getBlockedMessage(): string`
Retorna un mensaje de error legible que indica el tiempo restante de bloqueo (ej: "Intenta nuevamente en 2 min 15 seg").

## Ejemplo de Uso

### Implementación en un Formulario de Login

```php
$limiter = (new LoginRateLimiter($connect))
    ->fromPost($_POST['user'])
    ->resolveUser()
    ->load();

// 1. Verificar si ya está bloqueado
if ($limiter->isBlocked()) {
    die($limiter->getBlockedMessage());
}

// 2. Validar credenciales (lógica de tu aplicación)
$isValid = validate_user($_POST['user'], $_POST['pass']);

if (!$isValid) {
    // 3. Registrar fallo y bloquear si es necesario
    $limiter->failed();
    die("Credenciales inválidas. " . $limiter->getBlockedMessage());
}

// 4. Limpiar al tener éxito
$limiter->success();
echo "Bienvenido!";
```

## Reglas de Bloqueo Predefinidas

- **Por IP (Usuario inexistente)**: Bloqueo tras 3 intentos. Tiempo de espera aleatorio entre 1 y 3 minutos.
- **Por Cuenta (Usuario válido)**: Bloqueo tras 5 intentos. El tiempo de espera aumenta progresivamente según el número de reincidencias.
- **Fuerza Bruta Extrema**: Si se alcanzan 15 intentos fallidos, se puede invocar `blockIpPermanently()`.

## Tabla de Base de Datos (`user_access`)

La clase requiere la tabla `user_access` con los campos:
- `access_ip`: IP del cliente.
- `user_id`: ID del usuario (nullable).
- `access_attempts`: Contador de fallos.
- `access_blocked_until`: Timestamp del fin del bloqueo.
