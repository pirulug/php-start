<?php

function check_access($connect) {
  $sentence = $connect->query("SELECT * FROM users WHERE user_name = '" . $_SESSION['user_name'] . "' AND user_status = 1 LIMIT 1");
  $row      = $sentence->fetch(PDO::FETCH_ASSOC);
  return $row;
}

function get_user_session_information($connect) {
  $sentence = $connect->query("SELECT * FROM users WHERE user_name = '" . $_SESSION['user_name'] . "' LIMIT 1");
  $sentence = $sentence->fetch(PDO::FETCH_OBJ);
  return ($sentence) ? $sentence : false;
}