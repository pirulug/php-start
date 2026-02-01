<?php

$analytics = new Analytics($connect);

$processed = $analytics->resolveUnknownCountries(50);

echo json_encode([
  'success' => true,
  'updated' => $processed
]);
