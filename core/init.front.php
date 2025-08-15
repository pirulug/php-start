<?php
require_once __DIR__ . '/init.global.php';

// =============================================================================
// Visit Counter
// =============================================================================
$visitCounter = new VisitCounter($connect);

// =============================================================================
// OPTIONS
// =============================================================================
$siteOptions = new SiteOptions($connect);

$st_android_chrome_192x192 = $siteOptions->getFavicon('android-chrome-192x192');
$st_android_chrome_512x512 = $siteOptions->getFavicon('android-chrome-512x512');
$st_apple_touch_icon       = $siteOptions->getFavicon('apple-touch-icon');
$st_favicon_16x16          = $siteOptions->getFavicon('favicon-16x16');
$st_favicon_32x32          = $siteOptions->getFavicon('favicon-32x32');
$st_favicon                = $siteOptions->getFavicon('favicon.ico');
$st_webmanifest            = $siteOptions->getFavicon('webmanifest');

$st_darklogo  = $siteOptions->getDarkLogo();
$st_whitelogo = $siteOptions->getWhiteLogo();
$st_og_image  = $siteOptions->getOgImage();

// =============================================================================
// URL Helper
// =============================================================================
$static_url = new StaticUrl(SITE_URL);
$site_url   = new SiteUrl(SITE_URL);