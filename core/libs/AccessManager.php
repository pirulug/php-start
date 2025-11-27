<?php

class AccessManager {
  protected $db;
  protected $user;

  public function __construct($db, $user) {
    $this->db   = $db;
    $this->user = $user;
  }

  /**
   * Registra el permiso si no existe.
   * Retorna $this para permitir encadenamiento.
   */
  public function register_permission($key_name, $display_name, $description = null): self {
    $stmt = $this->db->prepare("SELECT COUNT(*) FROM permissions WHERE permission_key_name = ?");
    $stmt->execute([$key_name]);
    $exists = $stmt->fetchColumn() > 0;

    if (!$exists) {
      $permission_group_id = 1;
      $stmt                = $this->db->prepare("
                INSERT INTO permissions (permission_name, permission_key_name, permission_description, permission_group_id)
                VALUES (?, ?, ?, ?)
            ");
      $stmt->execute([
        $display_name,
        $key_name,
        $description ?? "Permiso generado automáticamente para {$display_name}",
        $permission_group_id
      ]);
    }

    // Importante: devolvemos la instancia para seguir llamando métodos
    return $this;
  }

  /**
   * Verifica el acceso.
   * Retorna un Objeto con ->success y ->message.
   */
  public function check_access($key_name): object {
    // 1. Superadmin
    if ($this->is_superadmin()) {
      return (object) ['success' => true, 'message' => 'Acceso concedido (Superadmin)'];
    }

    // 2. Usuario no logueado
    if (!$this->user) {
      return (object) ['success' => false, 'message' => 'Acceso denegado: Sesión no válida'];
    }

    // 3. Verificación en BD
    $stmt = $this->db->prepare("
            SELECT COUNT(*)
            FROM role_permissions rp
            INNER JOIN permissions p ON rp.permission_id = p.permission_id
            WHERE rp.role_id = :role_id AND p.permission_key_name = :key
        ");

    $stmt->bindParam(":role_id", $this->user->role_id, PDO::PARAM_INT);
    $stmt->bindParam(":key", $key_name, PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt->fetchColumn() > 0) {
      return (object) ['success' => true, 'message' => 'Acceso permitido'];
    }

    return (object) [
      'success' => false, 
      'message' => 'Acceso denegado: No tienes permisos para esta sección',
      'key_name' => $key_name
    ];
  }

  // --- Métodos Auxiliares ---

  protected function is_superadmin() {
    if (!$this->user || empty($this->user->user_login))
      return false;
    if (!defined('SUPERADMIN_USERNAMES') || !is_array(SUPERADMIN_USERNAMES))
      return false;

    $superadmins = array_map('strtolower', SUPERADMIN_USERNAMES);
    return in_array(strtolower($this->user->user_login), $superadmins);
  }

  public function can_access($theme_path): bool {
    return $this->check_access($theme_path)->success;
  }

  public function can_login($role_id = null, $user_login = null, $permission_name = "login-access"): object {
    // Lógica de superadmin para login
    if ($user_login && defined('SUPERADMIN_USERNAMES')) {
      $superadmins = array_map('strtolower', SUPERADMIN_USERNAMES);
      if (in_array(strtolower($user_login), $superadmins)) {
        return (object) ['success' => true, 'message' => 'Login permitido (Superadmin)'];
      }
    }
    if ($this->is_superadmin()) {
      return (object) ['success' => true, 'message' => 'Login permitido'];
    }

    $role_id = $role_id ?? ($this->user->role_id ?? null);

    if (!$role_id) {
      return (object) ['success' => false, 'message' => 'Rol no definido'];
    }

    $stmt = $this->db->prepare("
            SELECT COUNT(*) 
            FROM role_permissions rp
            INNER JOIN permissions p ON rp.permission_id = p.permission_id
            WHERE rp.role_id = :role_id
            AND p.permission_key_name = :permission_name
        ");
    $stmt->bindParam(":role_id", $role_id, PDO::PARAM_INT);
    $stmt->bindParam(":permission_name", $permission_name, PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt->fetchColumn() > 0) {
      return (object) ['success' => true, 'message' => 'Login permitido'];
    }

    return (object) ['success' => false, 'message' => 'Tu rol no tiene permisos para iniciar sesión'];
  }
}