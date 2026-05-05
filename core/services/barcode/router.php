<?php

Router::route('service/barcode/render')
  ->action(BASE_DIR . '/core/services/barcode/barcode.action.php')
  ->register();