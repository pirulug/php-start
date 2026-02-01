<?php

Router::route("datatable")
  ->action(BASE_DIR . "/app/ajax/datatable/actions/datatable.php")
  ->middleware("auth_ajax")
  ->register();