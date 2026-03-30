<?php

Router::route("mail")
  ->action(ajax_action("mail.mail"))
  ->register();

