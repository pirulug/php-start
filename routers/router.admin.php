<?php
switch ($segments[1] ?? '') {
  case 'dashboard':
    $theme_title = 'Dashboard';
    $theme_path = 'dashboard';
    $accessManager->ensure_access($theme_path, $theme_title, url_admin("logout"));
    include path_admin("dashboard");
    break;

  case 'login':
    include path_admin("login");
    break;

  case 'logout':
    include path_admin("logout");
    break;

  case 'test':
    include path_admin("test");
    break;

  case 'users':
    $theme_title = 'Lista de usuarios';
    $theme_path = 'user-list';
    $accessManager->ensure_access($theme_path, $theme_title, url_admin("logout"));
    include path_admin("users/list");
    break;

  case 'user':
    switch ($segments[2] ?? '') {
      case 'new':
        $theme_title = 'Nuevo usuario';
        $theme_path = 'user-new';
        $accessManager->ensure_access($theme_path, $theme_title, url_admin("logout"));
        include path_admin("users/new");
        break;
      case 'edit':
        $theme_title = 'Editar usuario';
        $theme_path = 'user-edit';
        $id = $segments[3] ?? '';
        $accessManager->ensure_access($theme_path, $theme_title, url_admin("logout"));
        include path_admin("users/edit");
        break;
      case 'delete':
        $theme_title = 'Eliminar usuario';
        $theme_path = 'user-delete';
        $accessManager->ensure_access($theme_path, $theme_title, url_admin("logout"));
        $id = $segments[3] ?? '';
        include path_admin("users/delete");
        break;
      default:
        include path_admin("errors/404-alt");
        break;
    }
    break;

  case "settings":
    switch ($segments[2] ?? '') {
      case 'general':
        $theme_title = 'General';
        $theme_path = 'general';
        $accessManager->ensure_access($theme_path, $theme_title, url_admin("logout"));
        include path_admin("settings/general");
        break;
      case 'smtp':
        $theme_title = 'Smtp';
        $theme_path = 'smtp';
        $accessManager->ensure_access($theme_path, $theme_title, url_admin("logout"));
        include path_admin("settings/smtp");
        break;
      case 'brand':
        $theme_title = 'brand';
        $theme_path = 'brand';
        $accessManager->ensure_access($theme_path, $theme_title, url_admin("logout"));
        include path_admin("settings/brand");
        break;
      case 'info':
        $theme_title = 'Infomacion del servidor';
        $theme_path = 'info';
        $accessManager->ensure_access($theme_path, $theme_title, url_admin("logout"));
        include path_admin("settings/info");
        break;
      case 'statistics':
        $theme_title = 'statistics';
        $theme_path = 'statistics';
        $accessManager->ensure_access($theme_path, $theme_title, url_admin("logout"));
        include path_admin("settings/statistics");
        break;
      case 'robots':
        $theme_title = 'Robots';
        $theme_path = 'robots';
        $accessManager->ensure_access($theme_path, $theme_title, url_admin("logout"));
        include path_admin("settings/robots");
        break;
      case 'sitemap':
        $theme_title = 'Sitemap';
        $theme_path = 'sitemap';
        $accessManager->ensure_access($theme_path, $theme_title, url_admin("logout"));
        include path_admin("settings/sitemap");
        break;
      default:
        include path_admin("errors/404-alt");
        break;
    }
    break;

  // Account
  case "account":
    switch ($segments[2] ?? '') {
      case 'profile':
        $theme_title = 'Perfil';
        $theme_path = 'account-profile';
        $accessManager->ensure_access($theme_path, $theme_title, url_admin("logout"));
        include path_admin("account/profile");
        break;
      case 'settings':
        $theme_title = 'Configuracion';
        $theme_path = 'account-settings';
        $accessManager->ensure_access($theme_path, $theme_title, url_admin("logout"));
        include path_admin("account/settings");
        break;
      default:
        include path_admin("errors/404-alt");
        break;
    }
    break;

  default:
    header("Location: " . url_admin("login"));
    break;
}
