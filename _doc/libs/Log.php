<?php

class Log {
  private $pdo;
  private $baseDir;
  private $logFiles = [];

  public function __construct($pdo, $baseDir = __DIR__ . '/logs') {
    $this->pdo     = $pdo;
    $this->baseDir = rtrim($baseDir, '/');

    // Definir rutas por tipo
    $this->logFiles = [
      'actions' => $this->baseDir . '/actions.log',
      'errors'  => $this->baseDir . '/errors.log',
      'debug'   => $this->baseDir . '/debug.log',
    ];
  }

  /**
   * Registra una acción de usuario en la base de datos y archivo
   */
  public function logUser($userId, $action, $description = '') {
    $timestamp = date("Y-m-d H:i:s");

    try {
      $sql  = "INSERT INTO user_logs (user_id, user_log_action, user_log_description, user_log_timestamp) 
              VALUES (:user_id, :action, :description, :timestamp)";
      $stmt = $this->pdo->prepare($sql);
      $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
      $stmt->bindParam(':action', $action, PDO::PARAM_STR);
      $stmt->bindParam(':description', $description, PDO::PARAM_STR);
      $stmt->bindParam(':timestamp', $timestamp, PDO::PARAM_STR);
      $stmt->execute();
    } catch (PDOException $e) {
      // Si falla la DB, registra el error en el archivo correspondiente
      $this->log('error', "Error al registrar acción (User ID: $userId): " . $e->getMessage());
    }

    // También se guarda en el archivo 'actions.log'
    $logMessage = "[$timestamp] [User ID: $userId] [Action: $action] $description";
    $this->writeToFile('actions', $logMessage);
  }

  /**
   * Registra un log en archivo según tipo (info, error, debug, etc.)
   */
  public function log($type, $message, $context = '') {
    $timestamp = date("Y-m-d H:i:s");
    $type      = strtolower($type);

    if (!isset($this->logFiles[$type])) {
      // Si no está definido, crea un nuevo archivo para ese tipo
      $this->logFiles[$type] = $this->baseDir . '/' . $type . '.log';
    }

    $logMessage = "[$timestamp] [" . strtoupper($type) . "] $message";
    if ($context) {
      $logMessage .= " | Context: $context";
    }

    $this->writeToFile($type, $logMessage);
  }

  /**
   * Escribe un mensaje a un archivo de log según el tipo
   */
  private function writeToFile($type, $message) {
    $filePath  = $this->logFiles[$type] ?? $this->baseDir . "/$type.log";
    $directory = dirname($filePath);

    if (!is_dir($directory)) {
      mkdir($directory, 0777, true);
    }

    file_put_contents($filePath, $message . PHP_EOL, FILE_APPEND);
  }

  /**
   * Obtiene logs del usuario desde la base de datos
   */
  public function getLogsByUser($userId) {
    try {
      $sql  = "SELECT user_logs.*, users.user_name, users.user_email
              FROM user_logs 
              INNER JOIN users ON user_logs.user_id = users.user_id 
              WHERE user_logs.user_id = :user_id 
              ORDER BY user_logs.user_log_timestamp DESC LIMIT 10";
      $stmt = $this->pdo->prepare($sql);
      $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
      $stmt->execute();

      return $stmt->fetchAll(PDO::FETCH_OBJ);
    } catch (PDOException $e) {
      $this->log('error', 'Error en getLogsByUser: ' . $e->getMessage());
      return [];
    }
  }
}
