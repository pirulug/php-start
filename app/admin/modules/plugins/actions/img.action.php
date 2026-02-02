<?php

$captcha = (new Captcha())
  ->width(250)
  ->height(80)
  ->codeLength(5)
  ->sessionKey('fluid_captcha')
  ->background('lines')
  ->font("static/assets/fonts/captcha.ttf")
  ->number();

header('Content-Type: image/png');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');

$captcha->generate();