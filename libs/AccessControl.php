<?php

class AccessControl {
  public function hasAccess($allowedRoles, $user_role) {
    return in_array($user_role, $allowedRoles);
  }
}
