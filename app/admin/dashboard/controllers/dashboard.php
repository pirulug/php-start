<?php

// Datos del dashboard
$count_user = $connect->query("SELECT count(*) as total FROM users WHERE user_id <> 1")
  ->fetch(PDO::FETCH_OBJ)->total;


