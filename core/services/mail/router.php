<?php

Router::route("mail")
  ->action(BASE_DIR . '/core/services/mail/mail.action.php')
  ->register();

Router::route("mail/process")
  ->endpoint(BASE_DIR . '/core/services/mail/mail.endpoint.php')
  ->register();