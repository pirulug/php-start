<?php

// $dt = new DataTableServer($connect);

// $dt->from('users u')
//   ->join('INNER JOIN roles r ON r.role_id = u.role_id')
//   ->columns([
//     ['db' => 'u.user_id', 'dt' => 'id'],
//     ['db' => 'u.user_name', 'dt' => 'username'],
//     ['db' => 'u.user_email', 'dt' => 'email'],
//     ['db' => 'u.user_first_name', 'dt' => 'first_name'],
//     ['db' => 'u.user_last_name', 'dt' => 'last_name'],
//     ['db' => 'r.role_name', 'dt' => 'role'],
//     ['db' => 'u.user_status', 'dt' => 'status'],
//     ['db' => 'u.user_created', 'dt' => 'created'],
//   ])
//   ->render('status', function ($val) {
//     return $val == 1
//       ? '<span class="badge bg-success">Activo</span>'
//       : '<span class="badge bg-secondary">Inactivo</span>';
//   })
//   ->render('username', function ($val, $row) {
//     return "<strong>{$val}</strong><br><small>{$row['email']}</small>";
//   })
//   ->generate();