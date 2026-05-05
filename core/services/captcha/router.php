<?php

Router::route('captcha/img.webp')
  ->action(BASE_DIR . '/core/services/captcha/captcha.action.php')
  ->register();