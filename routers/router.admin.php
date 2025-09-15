<?php
switch ($segments[1] ?? '') {
  case 'dashboard':
    $accessControl->check_access([1, 2], url_admin("logout"));
    include path_admin("dashboard");
    break;

  case 'login':
    include path_admin("login");
    break;

  case 'logout':
    include path_admin("logout");
    break;

  case 'users':
    $accessControl->check_access([1, 2], url_admin("logout"));
    include path_admin("users/list");
    break;

  case 'user':
    switch ($segments[2] ?? '') {
      case 'new':
        $accessControl->check_access([1, 2], url_admin("logout"));
        include path_admin("users/new");
        break;
      case 'edit':
        $accessControl->check_access([1, 2], url_admin("logout"));
        $id = $segments[3] ?? '';
        include path_admin("users/edit");
        break;
      case 'delete':
        $accessControl->check_access([1, 2], url_admin("logout"));
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
        $accessControl->check_access([1, 2], url_admin("logout"));
        include path_admin("settings/general");
        break;
      case 'smtp':
        $accessControl->check_access([1, 2], url_admin("logout"));
        include path_admin("settings/smtp");
        break;
      case 'brand':
        $accessControl->check_access([1, 2], url_admin("logout"));
        include path_admin("settings/brand");
        break;
      case 'info':
        $accessControl->check_access([1, 2], url_admin("logout"));
        include path_admin("settings/info");
        break;
      case 'statistics':
        $accessControl->check_access([1, 2], url_admin("logout"));
        include path_admin("settings/statistics");
        break;
      case 'robots':
        $accessControl->check_access([1, 2], url_admin("logout"));
        include path_admin("settings/robots");
        break;
      case 'sitemap':
        $accessControl->check_access([1, 2], url_admin("logout"));
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
        $accessControl->check_access([1, 2], url_admin("logout"));
        include path_admin("account/profile");
        break;
      case 'settings':
        $accessControl->check_access([1, 2], url_admin("logout"));
        include path_admin("account/settings");
        break;
      default:
        $accessControl->check_access([1, 2], url_admin("logout"));
        include path_admin("errors/404-alt");
        break;
    }
    break;

  default:
    header("Location: " . url_admin("login"));
    break;
}
