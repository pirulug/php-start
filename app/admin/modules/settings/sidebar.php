<?php

$settings = Sidebar::group("Settings", "settings");

$settings->item("General", admin_route("settings/general"))
  ->can("settings.general");

$settings->item('Brand', admin_route('settings/brand'))
  ->can("settings.brand");

$settings->item("Formats", admin_route("settings/formats"))
  ->can("settings.formats");

$settings->item('Social', admin_route('settings/social'))
  ->can("settings.social");

$settings->item('Robots', admin_route('settings/robots'))
  ->can("settings.robots");

$settings->item('Sitemap', admin_route('settings/sitemap'))
  ->can("settings.sitemap");

$settings->item('Backup', admin_route('settings/backup'))
  ->can("settings.backup");

$settings->item('Info', admin_route('settings/info'))
  ->can("settings.info");

$settings->item('Captcha', admin_route('settings/captcha'))
  ->can("settings.captcha");

$settings->item('Smtp', admin_route('settings/smtp'))
  ->can("settings.smtp");
