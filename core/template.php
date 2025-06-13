<?php
$theme = new TemplateEngine();

if (isset($_SESSION["user_name"])) {
  $user_session = get_user_session_information($connect);
}
