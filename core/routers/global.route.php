<?php

Router::route('captcha/img.webp')
  ->action(core_action("captcha"))
  ->register();
