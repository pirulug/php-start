# Logger

La clase `Logger` es un sistema de registro de eventos diseñado para capturar y organizar logs de la aplicación de forma estructurada. Permite clasificar mensajes por niveles de severidad y dividirlos en subcarpetas según el dominio o funcionalidad afectada.

## Características Principales

- **Niveles de Log Estándar**: Soporte para `INFO`, `WARNING`, `ERROR` y `DEBUG`.
- **Organización por Directorios**: Creación automática de subcarpetas para depurar diferentes secciones del sistema.
- **Información de Contexto**: Permite adjuntar datos adicionales (como objetos, arreglos o excepciones) que se guardan en formato JSON.
- **Captura Automática de Metadatos**: Registra fecha, hora, nivel, dirección IP del usuario y la ruta (URI) donde ocurrió el evento.
- **Rotación Diaria**: Crea un archivo de log independiente para cada día siguiendo el formato `YYYY-MM-DD.log`.

---

## Métodos de Configuración

### Definición del Nivel de Log
- `info(string $message)`: Registra un mensaje informativo sobre el flujo normal del sistema.
- `warning(string $message)`: Registra una advertencia sobre una situación potencialmente problemática.
- `error(string $message)`: Registra un error que afecta pero no detiene la ejecución total.
- `debug(string $message)`: Registra información detallada para propósitos de depuración técnica.

### Organización de Archivos
- `file(string $scope)`: Define una subcarpeta específica dentro del directorio base de logs. Útil para separar logs de `auth`, `payment`, `api`, etc.

### Gestión de Contexto
- `with(string $key, mixed $value)`: Adjunta datos adicionales al registro actual. Si se pasa una excepción (`Throwable`), se extrae automáticamente la clase, mensaje, archivo y línea.

---

## Ejecución del Registro

- `write()`: Escribe la línea de log en el archivo correspondiente y reinicia el estado interno de la instancia para el siguiente registro.

---

## Ejemplos de Uso

### Integración Estándar (Bootstrap)
Se inicializa automáticamente en `core/bootstrap/base.php` bajo la variable `$log`.

```php
// Uso básico en el inicio del sistema
$log = new Logger(BASE_DIR . '/storage/logs');
```

### Log de Información General
```php
$log->info('Usuario ha iniciado sesión.')
    ->with('user_id', 123)
    ->write();
```

### Log de Error con Excepción
```php
try {
    // Código propenso a errores
} catch (Exception $e) {
    $log->error('Fallo en la operación de base de datos')
        ->file('db_errors')
        ->with('exception', $e)
        ->write();
}
```

### Depuración con Datos Complejos
```php
$log->debug('Respuesta de la API externa recibida.')
    ->file('api_logs')
    ->with('payload', ['status' => 'ok', 'code' => 200])
    ->write();
```

## Formato del Archivo de Log

Las líneas generadas en los archivos `.log` siguen la siguiente estructura:

`[FECHA/HORA] [NIVEL] [IP] [RUTA] [MENSAJE] [CONTEXTO_JSON]`

**Ejemplo de salida:**
`[2026-04-04 10:29:13] [ERROR] [192.168.1.1] [/admin/users] Fallo en la operación {"db_id": 45, "error": "Integrity violation"} `

## Notas Técnicas

- La clase crea automáticamente los directorios necesarios si no existen mediante `mkdir()` con permisos `0755`.
- Se utiliza el modo `FILE_APPEND | LOCK_EX` para evitar condiciones de carrera cuando varios procesos intentan escribir al mismo archivo simultáneamente.
- El método `currentRoute()` detecta automáticamente si la ejecución se realiza desde la línea de comandos (CLI) o vía servidor web.