<?php

Router::route('captcha/img.webp')
  ->action(admin_action("captcha.img"))
  ->register();
