<?php

Router::route("datatable")
  ->action(ajax_action("datatable.datatable"))
  ->middleware("auth_ajax")
  ->register();