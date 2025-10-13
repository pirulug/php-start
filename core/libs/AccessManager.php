<?php
/**
 * AccessManager
 * Clase para manejar roles y permisos dinámicos en el sistema.
 */

class AccessManager {
  protected $db;
  protected $user;

  public function __construct($db, $user) {
    $this->db   = $db;
    $this->user = $user;
  }

  /**
   * Registra un permiso si no existe en la base de datos.
   */
  public function register_permission($key_name, $display_name, $description = null) {
    $stmt = $this->db->prepare("SELECT COUNT(*) FROM permissions WHERE permission_key_name = ?");
    $stmt->execute([$key_name]);
    $exists = $stmt->fetchColumn() > 0;

    if (!$exists) {
      $stmt = $this->db->prepare("
                INSERT INTO permissions (permission_name, permission_key_name, permission_description)
                VALUES (?, ?, ?)
            ");
      $stmt->execute([
        $display_name,
        $key_name,
        $description ?? "Permiso generado automáticamente para {$display_name}"
      ]);
    }
  }

  /**
   * Verifica si el usuario tiene permiso para acceder a una clave dada.
   */
  public function check_access($key_name, $redirect = null) {
    // Si el usuario es admin absoluto (rol_id = 1), tiene acceso total
    if ($this->user && isset($this->user->role_id) && (int) $this->user->role_id === 1) {
      return true;
    }

    // Si no hay usuario, se redirige (por seguridad)
    if (!$this->user) {
      if ($redirect) {
        header("Location: {$redirect}");
        exit;
      }
      exit("Acceso denegado: sesión no válida.");
    }

    // Comprobación normal de permisos
    $stmt = $this->db->prepare("
        SELECT COUNT(*) 
        FROM role_permissions rp
        INNER JOIN permissions p ON rp.permission_id = p.permission_id
        WHERE rp.role_id = ? 
        AND p.permission_key_name = ?
    ");
    $stmt->execute([$this->user->role_id, $key_name]);
    $hasAccess = $stmt->fetchColumn() > 0;

    if (!$hasAccess) {
      if ($redirect) {
        header("Location: {$redirect}");
        exit;
      } else {
        // Si prefieres puedes mostrar un error 403
        // include path_admin("errors/403");
        exit;
      }
    }

    return true;
  }

  /**
   * Combina registrar y verificar acceso automáticamente.
   */
  public function ensure_access($key_name, $display_name, $redirect = null) {
    $this->register_permission($key_name, $display_name);
    $this->check_access($key_name, $redirect);
  }
}
