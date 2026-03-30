<?php
require_once __DIR__ . '/core/bootstrap.php';
$stmt = $connect->query("SHOW TABLES");
$tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
echo implode("\n", $tables);
