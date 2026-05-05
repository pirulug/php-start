<?php

/**
 * DataBase
 *
 * Clase encargada de la gestión y conexión a la base de datos.
 * Proporciona una capa de abstracción para el acceso a datos,
 * ejecución de consultas y manejo de transacciones de forma segura.
 *
 * @author Pirulug
 * @link   https://github.com/pirulug
 */
class DataBase {
  // --------------------------------------------------------------------------
  // PROPIEDADES Y CONFIGURACIÓN
  // --------------------------------------------------------------------------

  private array $config = [
    'db_host'     => null,
    'db_name'     => null,
    'db_user'     => null,
    'db_password' => null,
    'db_charset'  => 'utf8mb4'
  ];

  private ?PDO $connection = null;

  /**
   * Constructor de la clase.
   */
  public function __construct() {
  }

  // --------------------------------------------------------------------------
  // SECCIÓN: CONFIGURACIÓN FLUENTE
  // --------------------------------------------------------------------------

  /**
   * Define el host de la base de datos.
   *
   * @param string $value Host (ej: localhost).
   * @return self Instancia para encadenamiento.
   */
  public function host(string $value): self {
    $this->config['db_host'] = trim($value);
    return $this;
  }

  /**
   * Define el nombre de la base de datos.
   *
   * @param string $value Nombre de la DB.
   * @return self Instancia para encadenamiento.
   */
  public function name(string $value): self {
    $this->config['db_name'] = trim($value);
    return $this;
  }

  /**
   * Define el usuario de la base de datos.
   *
   * @param string $value Usuario.
   * @return self Instancia para encadenamiento.
   */
  public function user(string $value): self {
    $this->config['db_user'] = trim($value);
    return $this;
  }

  /**
   * Define la contraseña de la base de datos.
   *
   * @param string $value Password.
   * @return self Instancia para encadenamiento.
   */
  public function password(string $value): self {
    $this->config['db_password'] = trim($value);
    return $this;
  }

  /**
   * Define el juego de caracteres de la conexión.
   *
   * @param string $value Charset (ej: utf8mb4).
   * @return self Instancia para encadenamiento.
   */
  public function charset(string $value): self {
    $this->config['db_charset'] = trim($value);
    return $this;
  }

  // --------------------------------------------------------------------------
  // SECCIÓN: GESTIÓN DE CONEXIÓN
  // --------------------------------------------------------------------------

  /**
   * Establece la conexión con el servidor de base de datos usando PDO.
   * Configura el modo de obtención de datos a objetos (FETCH_OBJ) por defecto.
   *
   * @return self Instancia.
   * @throws RuntimeException Si los parámetros están incompletos o falla la conexión.
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
      throw new RuntimeException('Los parámetros de conexión no están completos.');
    }

    $dsn = "mysql:host={$host};dbname={$dbname};charset={$charset}";

    try {
      $this->connection = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
        PDO::ATTR_EMULATE_PREPARES   => false
      ]);
    } catch (PDOException $e) {
      throw new RuntimeException('Error de conexión: ' . $e->getMessage());
    }

    return $this;
  }

  /**
   * Obtiene la instancia activa de PDO. Si no existe, intenta conectar primero.
   *
   * @return PDO Objeto de conexión PDO.
   */
  public function getConnection(): PDO {
    if (!$this->connection) {
      $this->connect();
    }
    return $this->connection;
  }
}