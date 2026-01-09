<?php

/**
 * DataBase
 *
 * Clase encargada de la gestión y conexión a la base de datos.
 * Proporciona una capa de abstracción para el acceso a datos,
 * ejecución de consultas y manejo de transacciones de forma segura.
 *
 * Facilita la integración con PDO, la configuración de credenciales
 * y el control centralizado de errores de base de datos.
 *
 * @author Pirulug
 * @link   https://github.com/pirulug
 */
class DataBase {

  private array $config = [
    'db_host'     => null,
    'db_name'     => null,
    'db_user'     => null,
    'db_password' => null,
    'db_charset'  => 'utf8mb4'
  ];

  private ?PDO $connection = null;

  public function __construct() {
  }

  public function host(string $value): self {
    $this->config['db_host'] = trim($value);
    return $this;
  }

  public function name(string $value): self {
    $this->config['db_name'] = trim($value);
    return $this;
  }

  public function user(string $value): self {
    $this->config['db_user'] = trim($value);
    return $this;
  }

  public function password(string $value): self {
    $this->config['db_password'] = trim($value);
    return $this;
  }

  public function charset(string $value): self {
    $this->config['db_charset'] = trim($value);
    return $this;
  }

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
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false
      ]);
    } catch (PDOException $e) {
      throw new RuntimeException('Error de conexión: ' . $e->getMessage());
    }

    return $this;
  }

  public function getConnection(): PDO {
    if (!$this->connection) {
      $this->connect();
    }
    return $this->connection;
  }
}
