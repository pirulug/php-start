<?php
class DataBase {
  private $connection;

  public function __construct($host, $name, $user, $pass) {
    $this->connect_mysql($host, $name, $user, $pass);
  }

  private function connect_mysql($host, $name, $user, $pass) {
    try {
      $this->connection = new PDO(
        "mysql:host=$host;dbname=$name;charset=utf8",
        $user,
        $pass
      );
      $this->connection->exec("SET CHARACTER SET utf8");
    } catch (PDOException $e) {
      die("Error de conexión: " . $e->getMessage());
    }
  }

  public function getConnection() {
    return $this->connection;
  }
}
