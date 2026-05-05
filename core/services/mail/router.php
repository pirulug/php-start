<?php

Router::route("mail")
  ->action(BASE_DIR . '/core/services/mail/mail.action.php')
  ->register();