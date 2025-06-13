<?php
$brand      = $connect->query("SELECT * FROM brand")->fetch(PDO::FETCH_OBJ);
$st_favicon = json_decode($brand->st_favicon, true);

$brd_android_chrome_192x192 = $st_favicon["android-chrome-192x192"];
$brd_android_chrome_512x512 = $st_favicon["android-chrome-512x512"];
$brd_apple_touch_icon       = $st_favicon["apple-touch-icon"];
$brd_favicon_16x16          = $st_favicon["favicon-16x16"];
$brd_favicon_32x32          = $st_favicon["favicon-32x32"];
$brd_favicon                = $st_favicon["favicon"];
$brd_webmanifest            = $st_favicon["webmanifest"];

$st_darklogo  = $brand->st_darklogo;
$st_whitelogo = $brand->st_whitelogo;
$st_og_image  = $brand->st_og_image;
