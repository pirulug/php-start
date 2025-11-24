# Documentación de la clase `DataBase`

La clase **DataBase** permite gestionar una conexión a MySQL mediante PDO utilizando un estilo de configuración fluido (builder), proporcionando un sistema limpio y flexible para crear la conexión.

## Propósito

Proveer una forma segura, limpia y desacoplada de construir una conexión PDO utilizando métodos encadenados.

- - -

## Métodos

### 1\. `host(string $value): self`

Establece el host del servidor MySQL.

*   **Parámetro:** Nombre/IP del servidor.
*   **Retorna:** La misma instancia para permitir encadenamiento.

### 2\. `name(string $value): self`

Indica el nombre de la base de datos que se desea utilizar.

*   **Parámetro:** Nombre de la base de datos.
*   **Retorna:** Instancia de la clase.

### 3\. `user(string $value): self`

Define el usuario autorizado para la conexión.

### 4\. `password(string $value): self`

Define la contraseña del usuario de la base de datos.

### 5\. `charset(string $value): self`

Especifica el conjunto de caracteres utilizado en la conexión.

Por defecto se utiliza `utf8mb4`.

### 6\. `connect(): self`

Crea la conexión PDO utilizando los parámetros configurados. Este método:

*   Valida que los datos mínimos estén completos (host, nombre de BD y usuario).
*   Configura PDO con buenas prácticas:
    *   Modo de errores con excepciones.
    *   Fetch por defecto como arreglo asociativo.
    *   Desactiva emulación de prepares.
*   Evita reconectar si ya existe una conexión activa.

### 7\. `getConnection(): PDO`

Devuelve la instancia activa de PDO. Si no existe, crea automáticamente la conexión llamando a `connect()`.

- - -

## Ejemplo de uso fluido

```php
$db = (new DataBase())
    ->host('localhost')
    ->name('test_db')
    ->user('root')
    ->password('password123')
    ->charset('utf8mb4')
    ->connect();

$connect = $db->getConnection();
```