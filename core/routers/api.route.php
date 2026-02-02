<?php

Router::prefix(PATH_API, CTX_API, function () {

  loadApiRoutes();

});