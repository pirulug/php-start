<?php

function is_logged_in(): bool {
  return isset($_SESSION['signin']) && $_SESSION['signin'] === true;
}
