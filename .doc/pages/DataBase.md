# DataBase

La clase `DataBase` actúa como una capa de abstracción para la gestión de conexiones a bases de datos MySQL utilizando la extensión PDO (PHP Data Objects). Proporciona una interfaz fluida para configurar las credenciales y centralizar el control de la conexión dentro de la aplicación.

## Características Principales

- **Interfaz Fluida**: Configuración encadenada de parámetros de conexión.
- **Conexión Bajo Demanda (Lazy Loading)**: Conecta automáticamente al intentar obtener la instancia PDO si aún no se ha establecido.
- **Configuración Optimizada**: Establece por defecto el modo de errores por excepción y el modo de obtención de datos asociativo.
- **Seguridad**: Desactiva la emulación de sentencias preparadas para aprovechar las capacidades nativas del servidor de base de datos.

## Métodos de Configuración (Fluent)

Estos métodos permiten definir los parámetros necesarios para la conexión antes de invocar el método `connect()`.

- `host(string $value)`: Define la dirección del servidor de base de datos (ej. `localhost` o `127.0.0.1`).
- `name(string $value)`: Define el nombre de la base de datos a la que se desea conectar.
- `user(string $value)`: Nombre del usuario con permisos de acceso.
- `password(string $value)`: Contraseña del usuario.
- `charset(string $value)`: Define el juego de caracteres de la conexión (Predeterminado: `utf8mb4`).

## Manejo de la Conexión

### `connect()`
Valida que los parámetros obligatorios (host, dbname, user) existan e intenta establecer la conexión mediante un DSN de MySQL. 

**Excepciones:**
Lanza una `RuntimeException` si faltan parámetros o si la conexión falla (capturando y encapsulando el mensaje original de `PDOException`).

### `getConnection()`
Devuelve la instancia activa de `PDO`. Si la conexión aún no existe, llama internamente al método `connect()` antes de devolver el objeto.

**Atributos predeterminados de PDO:**
- `ATTR_ERRMODE`: Configurado en `ERRMODE_EXCEPTION` para capturar errores como excepciones de PHP.
- `ATTR_DEFAULT_FETCH_MODE`: Configurado en `FETCH_ASSOC` para obtener resultados como arreglos asociativos por defecto.
- `ATTR_EMULATE_PREPARES`: Desactivado (`false`) para mayor seguridad y rendimiento.

## Integración Estándar (Bootstrap)

En el archivo `core/bootstrap/base.php`, la clase se inicializa utilizando las constantes globales definidas en la configuración del sistema. Este es el método recomendado para asegurar la consistencia en toda la aplicación.

```php
// Ejemplo de inicialización en el arranque del sistema
$db = (new DataBase())
  ->host(DB_HOST)
  ->name(DB_NAME)
  ->user(DB_USER)
  ->password(DB_PASS);

// Obtención de la instancia PDO para su uso global
$connect = $db->getConnection();
```

---

## Ejemplo de Implementación Manual

```php
$db = new DataBase();

try {
    // Configuración y conexión
    $pdo = $db->host('localhost')
              ->name('mi_base_de_datos')
              ->user('admin')
              ->password('secreto123')
              ->getConnection();

    // ejecución de una consulta preparada
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
    $stmt->execute(['id' => 1]);
    $user = $stmt->fetch();

    if ($user) {
        // Procesar usuario
    }

} catch (RuntimeException $e) {
    // Manejo de errores de conexión o configuración
    error_log($e->getMessage());
}
```

## Notas Técnicas

- La instancia de PDO se almacena de forma privada en la propiedad `$connection`, asegurando que no se creen múltiples conexiones innecesarias si se llama varias veces a `getConnection()`.
- Se recomienda el uso de `utf8mb4` como charset para asegurar la compatibilidad completa con todos los caracteres unicode, incluyendo emojis (aunque no se utilicen en esta documentación).