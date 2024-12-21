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

function setPageMetaData($page_title_suffix = "", $request = "") {
  global $settings;
  $page_title       = $page_title_suffix == "" ? $settings->st_sitename : $page_title_suffix . " | " . $settings->st_sitename;
  $page_description = $settings->st_description;
  $page_keywords    = $settings->st_keywords;

  $og_title       = $page_title;
  $og_description = $page_description;
  $og_image       = SITE_URL . "/assets/img/logo-vertical.png";
  $og_url         = SITE_URL . "/" . $request;

  return compact('page_title', 'page_description', 'page_keywords', 'og_title', 'og_description', 'og_image', 'og_url');
}