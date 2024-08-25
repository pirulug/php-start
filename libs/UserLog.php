<?php

class UserLog {
  private $pdo;

  public function __construct($connect) {
    $this->pdo = $connect;
  }

  public function logAction($userId, $action, $description = '') {
    $sql  = "INSERT INTO user_logs (user_id, action, description) VALUES (:user_id, :action, :description)";
    $stmt = $this->pdo->prepare($sql);

    $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
    $stmt->bindParam(':action', $action, PDO::PARAM_STR);
    $stmt->bindParam(':description', $description, PDO::PARAM_STR);

    return $stmt->execute();
  }

  // Función para obtener logs por usuario (opcional)
  public function getLogsByUser($userId) {
    $sql  = "SELECT user_logs.*, users.* 
        FROM user_logs 
        INNER JOIN users ON user_logs.user_id = users.user_id 
        WHERE user_logs.user_id = :user_id 
        ORDER BY user_logs.timestamp DESC
    ";
    $stmt = $this->pdo->prepare($sql);

    $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_OBJ);
  }
}