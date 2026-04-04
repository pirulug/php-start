# Cipher

La clase `Cipher` es un componente especializado en la protección de información sensible mediante algoritmos de cifrado simétrico, gestión de hashes y seguridad de contraseñas. Además, proporciona utilidades para la ofuscación de identificadores numéricos en URLs.

## Cifrado Simétrico (Interfaz Fluida)

Para realizar operaciones de cifrado y descifrado, la clase requiere una configuración mínima mediante su interfaz encadenable.

### Métodos de Configuración
- `method(string $method)`: Define el algoritmo de cifrado (ej. `aes-256-cbc`). Valida que el método sea soportado por la librería OpenSSL del servidor.
- `secretkey(string $key)`: Establece la clave secreta. Internamente aplica un hash SHA-256 para asegurar una longitud consistente.
- `secretiv(string $iv)`: Establece el Vector de Inicialización (IV). Debe tener exactamente 16 bytes.

### Operaciones
- `encrypt(string $plainText)`: Cifra el texto y devuelve el resultado codificado en Base64.
- `decrypt(string $cipherText)`: Decodifica el Base64 y descifra el contenido. Devuelve `false` si el contenido es inválido.

---

## Ofuscación de Identificadores (Base Conversion)

Esta funcionalidad es útil para transformar IDs numéricos incrementales (Base 10) en cadenas alfanuméricas cortas, dificultando el "crawling" o adivinación de registros en las URLs de la aplicación.

### Modos de Alfabeto Disponibles
- `lowercase`: solo letras minúsculas (a-z).
- `uppercase`: solo letras mayúsculas (A-Z).
- `numbers`: solo números (0-9).
- `mixed`: combinación de letras minúsculas, mayúsculas y números.

### Métodos
- `b10ToBstr(int $number, string $mode = 'lowercase')`: Convierte un entero a una cadena según el alfabeto seleccionado.
- `bstrToB10(string $str, string $mode = 'lowercase')`: Revierte la cadena alfanumérica a su valor numérico original.

---

## Gestión de Hashes e Integridad

Permite generar y verificar firmas de datos para asegurar que no han sido alterados.

- `hash(string $value, string $algo = 'sha256')`: Genera un hash del valor proporcionado.
- `verifyHash(string $value, string $hash, string $algo = 'sha256')`: Compara de forma segura (contra ataques de temporización) si un valor coincide con un hash previo.

---

## Seguridad de Contraseñas

Encapsula las funciones nativas de PHP para el manejo seguro de contraseñas mediante `BCRYPT` o `ARGON2`.

- `password(string $password)`: Genera un hash de contraseña seguro utilizando el algoritmo predeterminado del sistema.
- `verifyPassword(string $password, string $hash)`: Verifica si una contraseña en texto plano coincide con su hash almacenado.
- `needsRehash(string $hash)`: Comprueba si un hash de contraseña existente debe ser regenerado para cumplir con los estándares de seguridad más recientes.

## Integración Estándar (Bootstrap)

La clase se inicializa automáticamente en `core/bootstrap/base.php` utilizando las constantes de configuración de seguridad del sistema.

```php
// Ejemplo de inicialización global
$cipher = (new Cipher())
  ->method(ENCRYPT_METHOD)
  ->secretkey(ENCRYPT_KEY)
  ->secretiv(ENCRYPT_IV);
```

---

## Ejemplos de Uso Manual
```php
$cipher = new Cipher();
$key = "mi_clave_secreta";
$iv  = "1234567890123456"; // Exactamente 16 caracteres

// Cifrar
$encrypted = $cipher->method('aes-256-cbc')
                    ->secretkey($key)
                    ->secretiv($iv)
                    ->encrypt("Información confidencial");

// Descifrar
$decrypted = $cipher->decrypt($encrypted);
```

### Ejemplo 2: Ofuscación de IDs para URLs
```php
$cipher = new Cipher();
$userId = 10050;

// Convertir ID a cadena corta (ej. para /profile/b2c)
$shortId = $cipher->b10ToBstr($userId, 'mixed');

// Recuperar ID original al recibir la petición
$originalId = $cipher->bstrToB10($shortId, 'mixed');
```

### Ejemplo 3: Manejo de Contraseñas
```php
$cipher = new Cipher();
$pass = "mi_password_seguro";

// Crear Hash
$hash = $cipher->password($pass);

// Verificar en Login
if ($cipher->verifyPassword($pass, $hash)) {
    // Éxito: El usuario puede entrar
}
```

## Consideraciones de Seguridad
- El Vector de Inicialización (IV) debe ser tratado con el mismo cuidado que la clave secreta o generado de forma aleatoria y almacenado junto al mensaje cifrado si se requiere máxima seguridad.
- Para la ofuscación de IDs, el alfabeto `mixed` ofrece la mayor densidad de información y es el más difícil de predecir.