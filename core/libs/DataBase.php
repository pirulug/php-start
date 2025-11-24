<?php

class DataBase {

  /**
   * Arreglo de configuración de la conexión.
   * Contiene host, nombre de la base de datos, usuario, contraseña y charset.
   */
  private array $config = [
    'db_host'     => null,
    'db_name'     => null,
    'db_user'     => null,
    'db_password' => null,
    'db_charset'  => 'utf8mb4'
  ];

  /**
   * Instancia de PDO que almacena la conexión activa.
   */
  private ?PDO $connection = null;

  /**
   * Constructor vacío para uso exclusivo con el patrón Fluido/Builder.
   */
  public function __construct() {
  }

  /**
   * Establece el host del servidor MySQL.
   *
   * @param string $value Nombre o IP del servidor.
   * @return self
   */
  public function host(string $value): self {
    $this->config['db_host'] = trim($value);
    return $this;
  }

  /**
   * Establece el nombre de la base de datos.
   *
   * @param string $value Nombre de la base de datos.
   * @return self
   */
  public function name(string $value): self {
    $this->config['db_name'] = trim($value);
    return $this;
  }

  /**
   * Establece el nombre del usuario de la base de datos.
   *
   * @param string $value Usuario autorizado.
   * @return self
   */
  public function user(string $value): self {
    $this->config['db_user'] = trim($value);
    return $this;
  }

  /**
   * Establece la contraseña del usuario de la base de datos.
   *
   * @param string $value Contraseña del usuario.
   * @return self
   */
  public function password(string $value): self {
    $this->config['db_password'] = trim($value);
    return $this;
  }

  /**
   * Establece el conjunto de caracteres para la conexión.
   *
   * @param string $value Charset a utilizar, por defecto utf8mb4.
   * @return self
   */
  public function charset(string $value): self {
    $this->config['db_charset'] = trim($value);
    return $this;
  }

  /**
   * Crea la conexión PDO utilizando los parámetros configurados.
   * 
   * Lanza una excepción si los datos mínimos no están completos.
   * Solo establece la conexión una vez (previene reconexiones innecesarias).
   *
   * @return self
   */
  public function connect(): self {
    if ($this->connection instanceof PDO) {
      return $this;
    }

    $host    = $this->config['db_host'];
    $dbname  = $this->config['db_name'];
    $user    = $this->config['db_user'];
    $pass    = $this->config['db_password'];
    $charset = $this->config['db_charset'];

    if (!$host || !$dbname || !$user) {
      throw new RuntimeException("Los parámetros de conexión no están completos.");
    }

    $dsn = "mysql:host={$host};dbname={$dbname};charset={$charset}";

    try {
      $this->connection = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false
      ]);
    } catch (PDOException $e) {
      throw new RuntimeException("Error de conexión: " . $e->getMessage());
    }

    return $this;
  }

  /**
   * Devuelve la conexión PDO activa.
   * 
   * Si aún no existe conexión, intenta crearla automáticamente.
   *
   * @return PDO
   */
  public function getConnection(): PDO {
    if (!$this->connection) {
      $this->connect();
    }
    return $this->connection;
  }
}
