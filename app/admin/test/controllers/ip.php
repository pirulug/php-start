<?php
set_time_limit(0);
ini_set('memory_limit', '1024M');
ob_implicit_flush(true);
ob_end_flush();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  if (!isset($_FILES['csvfile']) || $_FILES['csvfile']['error'] !== UPLOAD_ERR_OK) {
    die("No se subió un archivo válido.");
  }

  $tmpPath            = $_FILES['csvfile']['tmp_name'];
  $hasHeader          = isset($_POST['has_header']);
  $requestedDelimiter = $_POST['delimiter'] ?? '';
  $mode               = $_POST['mode'] ?? 'insert'; // insert | update

  // === Detectar delimitador ===
  function detect_delimiter($filePath, $requested = '') {
    if ($requested)
      return $requested === '\t' ? "\t" : $requested;
    $fh   = fopen($filePath, 'r');
    $line = fgets($fh, 4096);
    fclose($fh);
    $line     = preg_replace('/^\xEF\xBB\xBF/', '', $line);
    $delims   = [",", ";", "\t", "|"];
    $best     = ",";
    $maxCount = 0;
    foreach ($delims as $d) {
      $c = substr_count($line, $d);
      if ($c > $maxCount) {
        $maxCount = $c;
        $best     = $d;
      }
    }
    return $best;
  }

  $delimiter = detect_delimiter($tmpPath, $requestedDelimiter);

  // === Función auxiliar ===
  function remove_bom($s) {
    return preg_replace('/^\xEF\xBB\xBF/', '', $s);
  }

  $fh = fopen($tmpPath, 'r');
  if (!$fh)
    die("No se pudo abrir el archivo CSV.");

  if ($hasHeader)
    fgets($fh); // Saltar encabezado

  $rows      = $inserted = $updated = $failed = 0;
  $batch     = [];
  $batchSize = 1000;

  while (($line = fgets($fh)) !== false) {
    if (trim($line) === '')
      continue;

    $cols = str_getcsv($line, $delimiter, '"', '\\');
    if ($rows === 0 && isset($cols[0]))
      $cols[0] = remove_bom($cols[0]);

    if (count($cols) < 10) {
      $failed++;
      $rows++;
      continue;
    }

    $cols = array_slice($cols, 0, 10);
    $cols = array_map('trim', $cols);

    $ipFrom = preg_replace('/[^0-9]/', '', $cols[0]);
    $ipTo   = preg_replace('/[^0-9]/', '', $cols[1]);

    if ($ipFrom === '' || $ipTo === '') {
      $failed++;
      $rows++;
      continue;
    }

    $batch[] = [
      'ip_from' => $ipFrom,
      'ip_to'   => $ipTo,
      'code'    => $cols[2],
      'name'    => $cols[3],
      'region'  => $cols[4],
      'city'    => $cols[5],
      'lat'     => is_numeric($cols[6]) ? $cols[6] : null,
      'lon'     => is_numeric($cols[7]) ? $cols[7] : null,
      'zip'     => $cols[8],
      'tz'      => $cols[9]
    ];

    $rows++;

    // Procesar lote
    if (count($batch) >= $batchSize) {
      process_batch($batch, $mode, $connect, $inserted, $updated, $failed);
      $batch = [];
      echo "Procesadas: $rows | Insertadas: $inserted | Actualizadas: $updated | Fallidas: $failed<br>";
      @ob_flush();
      @flush();
    }
  }

  if (!empty($batch)) {
    process_batch($batch, $mode, $connect, $inserted, $updated, $failed);
  }

  fclose($fh);

  echo "<h3>Importación completada</h3>";
  echo "Filas leídas: $rows<br>";
  echo "Insertadas: $inserted<br>";
  echo "Actualizadas: $updated<br>";
  echo "Fallidas: $failed<br>";
  echo "Delimitador detectado: '" . ($delimiter === "\t" ? "\\t" : $delimiter) . "'<br>";
}

/**
 * Procesar lote de datos
 */
function process_batch(array $batch, string $mode, PDO $connect, int &$inserted, int &$updated, int &$failed) {
  try {
    if ($mode === 'insert') {
      // === INSERT MÚLTIPLE ===
      $values = [];
      foreach ($batch as $r) {
        $values[] = '(' . implode(',', [
          $r['ip_from'],
          $r['ip_to'],
          $connect->quote($r['code']),
          $connect->quote($r['name']),
          $connect->quote($r['region']),
          $connect->quote($r['city']),
          $r['lat'] ?? 'NULL',
          $r['lon'] ?? 'NULL',
          $connect->quote($r['zip']),
          $connect->quote($r['tz'])
        ]) . ')';
      }

      $sql = "INSERT INTO iplocation (
        iplo_ip_from, iplo_ip_to, iplo_country_code, iplo_country_name,
        iplo_region_name, iplo_city_name, iplo_latitude, iplo_longitude,
        iplo_zipcode, iplo_timezone
      ) VALUES " . implode(',', $values);

      $connect->exec($sql);
      $inserted += count($batch);
    } else {
      // === ACTUALIZACIÓN POR RANGO ===
      $updateStmt = $connect->prepare("
        UPDATE iplocation SET
          iplo_country_code = :code,
          iplo_country_name = :name,
          iplo_region_name  = :region,
          iplo_city_name    = :city,
          iplo_latitude     = :lat,
          iplo_longitude    = :lon,
          iplo_zipcode      = :zip,
          iplo_timezone     = :tz
        WHERE iplo_ip_from = :ip_from AND iplo_ip_to = :ip_to
      ");

      foreach ($batch as $r) {
        $updateStmt->execute([
          ':ip_from' => $r['ip_from'],
          ':ip_to'   => $r['ip_to'],
          ':code'    => $r['code'],
          ':name'    => $r['name'],
          ':region'  => $r['region'],
          ':city'    => $r['city'],
          ':lat'     => $r['lat'],
          ':lon'     => $r['lon'],
          ':zip'     => $r['zip'],
          ':tz'      => $r['tz']
        ]);

        if ($updateStmt->rowCount() > 0) {
          $updated++;
        } else {
          $failed++;
        }
      }
    }
  } catch (Exception $e) {
    $failed += count($batch);
    echo "<div style='color:red;'>Error SQL: " . htmlspecialchars($e->getMessage()) . "</div>";
  }
}