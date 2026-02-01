<?php

Router::route("mail")
  ->action(BASE_DIR . "/app/ajax/mail/actions/mail.php")
  ->register();
