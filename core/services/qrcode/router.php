<?php

Router::route('service/qrcode/render')
  ->action(BASE_DIR . '/core/services/qrcode/qrcode.action.php')
  ->register();
