<?php

function get_user_session_information($connect, $user_id) {
  $sql  = "SELECT * FROM users WHERE user_id = $user_id";
  $stmt = $connect->query($sql);
  $stmt->execute();

  return $stmt->fetch(PDO::FETCH_OBJ);
}