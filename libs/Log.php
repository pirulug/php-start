<?php

class Log {
  private $pdo;
  private $logFilePath;

  public function __construct($connect, $logFilePath = 'log/actions.log') {
    $this->pdo         = $connect;
    $this->logFilePath = $logFilePath;
  }

  // Método para registrar una acción en la base de datos y en el archivo de log
  public function logAction($userId, $action, $description = '') {
    $timestamp = date("Y-m-d H:i:s");

    // Registrar en la base de datos
    try {
      $sql  = "INSERT INTO user_logs (user_id, action, description, timestamp) VALUES (:user_id, :action, :description, :timestamp)";
      $stmt = $this->pdo->prepare($sql);
      $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
      $stmt->bindParam(':action', $action, PDO::PARAM_STR);
      $stmt->bindParam(':description', $description, PDO::PARAM_STR);
      $stmt->bindParam(':timestamp', $timestamp, PDO::PARAM_STR);
      $stmt->execute();
    } catch (PDOException $e) {
      error_log("Error en logAction (DB): " . $e->getMessage());
    }

    // Registrar en el archivo de log
    $logMessage = "[$timestamp] [User ID: $userId] [Action: $action] $description" . PHP_EOL;
    $this->writeToFile($logMessage);

    return true;
  }

  // Método para obtener logs de un usuario específico
  public function getLogsByUser($userId) {
    try {
      $sql  = "SELECT user_logs.*, users.user_name 
                    FROM user_logs 
                    INNER JOIN users ON user_logs.user_id = users.user_id 
                    WHERE user_logs.user_id = :user_id 
                    ORDER BY user_logs.timestamp DESC";
      $stmt = $this->pdo->prepare($sql);
      $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
      $stmt->execute();

      return $stmt->fetchAll(PDO::FETCH_OBJ);
    } catch (PDOException $e) {
      error_log("Error en getLogsByUser: " . $e->getMessage());
      return [];
    }
  }

  // Método para escribir en el archivo de log
  private function writeToFile($message) {
    // Asegurarse de que la carpeta exista
    $directory = dirname($this->logFilePath);
    if (!is_dir($directory)) {
      mkdir($directory, 0777, true); // Crea la carpeta con permisos recursivos si no existe
    }

    // Crear el archivo de log si no existe
    if (!file_exists($this->logFilePath)) {
      file_put_contents($this->logFilePath, ""); // Crea el archivo vacío
    }

    // Escribir en el archivo de log
    file_put_contents($this->logFilePath, $message, FILE_APPEND);
  }
}
