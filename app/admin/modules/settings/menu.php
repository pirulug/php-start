<?php

Sidebar::group('Settings', 'settings', function ($group) {

  $group->item('General', admin_route('settings/general'))
    ->can('settings.new');

  $group->item('Social', admin_route('settings/social'))
    ->can('settings.social');

  $group->item('SEO & Tracking', admin_route('settings/seo'))
    ->can('settings.general');

  $group->item('Sistema', admin_route('settings/options'))
    ->can('settings.options');

  $group->item('Date & Time', admin_route('settings/date_time'))
    ->can('settings.date_time');

  $group->item('Captcha', admin_route('settings/captcha'))
    ->can('settings.captcha');

  $group->item('SMTP', admin_route('settings/smtp'))
    ->can('settings.smtp');

  $group->item('Brand', admin_route('settings/brand'))
    ->can('settings.brand');

  $group->item('Información', admin_route('settings/info'))
    ->can('settings.info');

  $group->item('robots.txt', admin_route('settings/robots'))
    ->can('settings.robots');

  $group->item('Sitemap.xml', admin_route('settings/sitemap'))
    ->can('settings.sitemap');

  $group->item('Backups', admin_route('settings/backups'))
    ->can('settings.backups');

});
