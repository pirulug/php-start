<?php

function site_logo($img_logo) {
  // return SITE_URL . "/uploads/site/" . $img_logo;
  $path = "/uploads/site/" . $img_logo;
  if (file_exists(BASE_DIR . $path)) {
    return SITE_URL . $path;
  } else {
    return SITE_URL . "/assets/images/default-logo.png";
  }
}

function site_favicon($img_favicon) {
  // return SITE_URL . "/uploads/site/favicons/" . $img_favicon;
  $path = "/uploads/site/favicons/" . $img_favicon;
  if (file_exists(BASE_DIR . $path)) {
    return SITE_URL . $path;
  } else {
    return SITE_URL . "/assets/images/default-favicon.png";
  }
}