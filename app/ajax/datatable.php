<?php

// Ejemplo de uso con interfaz fluida
$result = (new DataTableServerSide($connect))
  ->select('
        u.user_id,
        u.user_login,
        u.user_nickname,
        u.user_email,
        u.user_status,
        u.user_image,
        r.role_name,
        u.user_created,
        u.user_last_login
    ')
  ->from('users u')
  ->joins('LEFT JOIN roles r ON u.role_id = r.role_id')
  ->where('u.user_deleted IS NULL')
  ->columns([
    'u.user_id',
    'u.user_login',
    'u.user_nickname',
    'u.user_email',
    'r.role_name',
    'u.user_status',
    'u.user_created'
  ])
  ->searchable([
    'u.user_login',
    'u.user_nickname',
    'u.user_email',
    'r.role_name'
  ])
  ->execute();

// Formateo de datos (igual que antes)
$data = [];

foreach ($result['rows'] as $row) {
  $status = $row->user_status
    ? '<span class="badge bg-success">Activo</span>'
    : '<span class="badge bg-danger">Inactivo</span>';

  $image = '<img src="' . APP_URL . '/storage/uploads/user/' . $row->user_image . '"
                 class="rounded-circle"
                 width="40"
                 height="40">';

  $actions = '
        <button class="btn btn-sm btn-primary edit-btn" data-id="' . $row->user_id . '">
            <i class="fas fa-edit"></i>
        </button>
        <button class="btn btn-sm btn-danger delete-btn" data-id="' . $row->user_id . '">
            <i class="fas fa-trash"></i>
        </button>
    ';

  $data[] = [
    'user_id'         => $row->user_id,
    'user_login'      => htmlspecialchars($row->user_login),
    'user_nickname'   => htmlspecialchars($row->user_nickname ?? ''),
    'user_email'      => htmlspecialchars($row->user_email),
    'role_name'       => htmlspecialchars($row->role_name ?? 'Sin rol'),
    'user_status'     => $status,
    'user_image'      => $image,
    'user_created'    => date('d/m/Y H:i', strtotime($row->user_created)),
    'user_last_login' => $row->user_last_login
      ? date('d/m/Y H:i', strtotime($row->user_last_login))
      : 'Nunca',
    'actions'         => $actions
  ];
}

echo json_encode([
  'draw'            => $result['draw'],
  'recordsTotal'    => $result['recordsTotal'],
  'recordsFiltered' => $result['recordsFiltered'],
  'data'            => $data
]);