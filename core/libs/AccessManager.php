<?php
class AccessManager {
  protected $db;
  protected $user;

  public function __construct($db, $user) {
    $this->db   = $db;
    $this->user = $user;
  }

  /**
   * Verifica si el usuario actual es superadministrador según su nombre.
   */
  protected function is_superadmin() {
    if (!$this->user || !isset($this->user->user_name))
      return false;

    if (defined('SUPERADMIN_USERNAMES')) {
      $superadmins = array_map('strtolower', SUPERADMIN_USERNAMES);
      return in_array(strtolower($this->user->user_name), $superadmins);
    }

    return false;
  }

  /**
   * Verifica si el usuario tiene permiso para una clave.
   */
  public function check_access($key_name, $redirect = null) {
    // Superadmin tiene acceso total
    if ($this->is_superadmin())
      return true;

    // Usuario no logueado
    if (!$this->user) {
      if ($redirect)
        header("Location: {$redirect}");
      exit("Acceso denegado: sesión no válida.");
    }

    // Verifica permiso directo
    $stmt = $this->db->prepare("
      SELECT COUNT(*)
      FROM role_permissions rp
      INNER JOIN permissions p ON rp.permission_id = p.permission_id
      WHERE rp.role_id = :role_id AND p.permission_key_name = :key
    ");
    $stmt->bindParam(":role_id", $this->user->role_id, PDO::PARAM_INT);
    $stmt->bindParam(":key", $key_name, PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt->fetchColumn() == 0) {
      if ($redirect) {
        header("Location: {$redirect}");
        exit;
      }
      exit("Acceso denegado: no tienes permisos para acceder a esta sección.");
    }

    return true;
  }

  /**
   * Registra un permiso si no existe en la base de datos.
   */
  public function register_permission($key_name, $display_name, $description = null) {
    $stmt = $this->db->prepare("SELECT COUNT(*) FROM permissions WHERE permission_key_name = ?");
    $stmt->execute([$key_name]);
    $exists = $stmt->fetchColumn() > 0;

    $permission_group_id = 1;

    if (!$exists) {
      $stmt = $this->db->prepare("
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
  }

  /**
   * Alias directo para verificar acceso (sin registrar nada).
   */
  public function ensure_access($key_name, $display_name, $redirect = null) {
    $this->register_permission($key_name, $display_name);
    return $this->check_access($key_name, $redirect);
  }

  /**
   * Verifica si el usuario puede acceder a una sección (menú, etc.)
   */
  public function can_access($theme_path) {
    if ($this->is_superadmin())
      return true;

    $stmt = $this->db->prepare("
      SELECT COUNT(*)
      FROM role_permissions rp
      INNER JOIN permissions p ON rp.permission_id = p.permission_id
      WHERE rp.role_id = :role_id AND p.permission_key_name = :key
    ");
    $stmt->bindParam(":role_id", $this->user->role_id, PDO::PARAM_INT);
    $stmt->bindParam(":key", $theme_path, PDO::PARAM_STR);
    $stmt->execute();

    return $stmt->fetchColumn() > 0;
  }

  public function debug_permissions() {
    echo "<pre style='background:#1e1e1e;color:#b3ffb3;padding:12px;border-radius:10px;font-size:14px;line-height:1.4em'>";
    echo "🧠 Debug de permisos del usuario\n";
    echo "---------------------------------\n";

    if (!$this->user) {
      echo "⚠️ No hay usuario en sesión.\n";
      echo "</pre>";
      return;
    }

    $userName = htmlspecialchars($this->user->user_name ?? '(sin nombre)');
    $roleId   = htmlspecialchars($this->user->role_id ?? '?');

    echo "👤 Usuario: {$userName}\n";
    echo "🎭 Rol ID: {$roleId}\n";

    // SUPER ADMIN (rol_id = 1)
    if ($this->is_superadmin()) {
      echo "\n💥 Este usuario es SUPER ADMINISTRADOR.\n";
      echo "   → Tiene acceso TOTAL a todos los módulos, menús y permisos.\n";
      echo "   (No se requiere comprobación de permisos en la base de datos.)\n";
      echo "</pre>";
      return;
    }

    // Para roles normales
    $stmt = $this->db->prepare("
        SELECT p.permission_key_name, p.permission_name
        FROM role_permissions rp
        INNER JOIN permissions p ON rp.permission_id = p.permission_id
        WHERE rp.role_id = ?
        ORDER BY p.permission_key_name ASC
    ");
    $stmt->bindParam(1, $this->user->role_id, PDO::PARAM_INT);
    $stmt->execute();
    $permissions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "\n📋 Permisos cargados:\n\n";
    if ($permissions) {
      foreach ($permissions as $perm) {
        echo "   • " . htmlspecialchars($perm['permission_key_name']) .
          " (" . htmlspecialchars($perm['permission_name']) . ")\n";
      }
    } else {
      echo "❌ Este rol no tiene permisos asignados.\n";
    }

    echo "</pre>";
  }

}
