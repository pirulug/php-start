<?php

class Log {
  private $pdo;
  private $logFilePath;

  public function __construct($connect, $logFilePath = 'log/actions.log') {
    $this->pdo         = $connect;
    $this->logFilePath = $logFilePath;
  }

  public function logAction($userId, $action, $description = '') {
    $timestamp = date("Y-m-d H:i:s");

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

    $logMessage = "[$timestamp] [User ID: $userId] [Action: $action] $description" . PHP_EOL;
    $this->writeToFile($logMessage);

    return true;
  }

  public function getLogsByUser($userId) {
    try {
      $sql  = "SELECT user_logs.*, users.user_name, users.user_email
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

  private function writeToFile($message) {
    $directory = dirname($this->logFilePath);
    if (!is_dir($directory)) {
      mkdir($directory, 0777, true);
    }

    if (!file_exists($this->logFilePath)) {
      file_put_contents($this->logFilePath, "");
    }

    file_put_contents($this->logFilePath, $message, FILE_APPEND);
  }
}
