<?php

$type = $route['params']['type'] ?? $_GET['type'] ?? null;

if ($type !== null) {
  require_once BASE_DIR . '/core/vendors/fpdf/fpdf.php';

  // Desactivar visualización de errores para evitar corromper el stream binario
  ini_set('display_errors', 0);
  error_reporting(E_ALL & ~E_DEPRECATED);

  // Helper para compatibilidad con PHP 8.2+ (utf8_decode deprecado)
  if (!function_exists('fpdf_text')) {
    function fpdf_text($text) {
      return mb_convert_encoding($text, 'ISO-8859-1', 'UTF-8');
    }
  }

  // Limpiar cualquier salida previa
  if (ob_get_length()) {
    ob_end_clean();
  }

  if ($type === 'ticket') {
    // TICKET 80mm
    $pdf = new FPDF('P', 'mm', [80, 200]);
    $pdf->SetMargins(4, 4, 4);
    $pdf->AddPage();
    $pdf->SetTitle(fpdf_text('Ticket de Venta'));

    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 8, fpdf_text('NOMBRE DE LA TIENDA'), 0, 1, 'C');
    
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(0, 4, fpdf_text('RUC: 12345678901'), 0, 1, 'C');
    $pdf->Cell(0, 4, fpdf_text('Dirección Av. Principal 123'), 0, 1, 'C');
    $pdf->Cell(0, 4, fpdf_text('Tel: 987 654 321'), 0, 1, 'C');
    
    $pdf->Ln(2);
    $pdf->Cell(0, 0, '', 'T', 1);
    $pdf->Ln(2);
    
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(0, 5, fpdf_text('TICKET: T001-000452'), 0, 1, 'L');
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(0, 4, fpdf_text('Fecha: ') . date('d/m/Y H:i'), 0, 1, 'L');
    $pdf->Cell(0, 4, fpdf_text('Cliente: Público General'), 0, 1, 'L');

    $pdf->Ln(2);
    $pdf->Cell(0, 0, '', 'T', 1);
    $pdf->Ln(2);

    // Tabla de productos
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(40, 5, fpdf_text('DESCRIPCIÓN'), 0, 0, 'L');
    $pdf->Cell(10, 5, 'CANT', 0, 0, 'C');
    $pdf->Cell(22, 5, 'TOTAL', 0, 1, 'R');
    $pdf->SetFont('Arial', '', 8);
    
    $items = [
      ['Prod 1 con tildes áéíóú', 2, 25.00],
      ['Producto de ejemplo B', 1, 15.50],
      ['Otro artículo más', 3, 45.00],
    ];

    $total = 0;
    foreach($items as $item) {
      $pdf->Cell(40, 5, fpdf_text(substr($item[0], 0, 22)), 0, 0, 'L');
      $pdf->Cell(10, 5, $item[1], 0, 0, 'C');
      $pdf->Cell(22, 5, number_format($item[1] * $item[2], 2), 0, 1, 'R');
      $total += ($item[1] * $item[2]);
    }

    $pdf->Ln(2);
    $pdf->Cell(0, 0, '', 'T', 1);
    $pdf->Ln(2);

    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(50, 6, 'TOTAL A PAGAR:', 0, 0, 'R');
    $pdf->Cell(22, 6, 'S/ ' . number_format($total, 2), 0, 1, 'R');

    $pdf->Ln(5);
    $pdf->SetFont('Arial', 'I', 8);
    $pdf->Cell(0, 5, fpdf_text('¡Gracias por su compra!'), 0, 1, 'C');
    $pdf->Cell(0, 5, fpdf_text('Visite nuestro sitio web'), 0, 1, 'C');

  } else {
    // FACTURA A4
    $pdf = new FPDF('P', 'mm', 'A4');
    $pdf->AddPage();
    $pdf->SetTitle(fpdf_text('Factura Electrónica'));

    // Header / Logo
    $pdf->SetFont('Arial', 'B', 20);
    $pdf->SetTextColor(44, 62, 80);
    $pdf->Cell(100, 10, fpdf_text('MI EMPRESA S.A.C.'), 0, 0, 'L');
    
    // Recuadro de RUC
    $pdf->SetFillColor(245, 245, 245);
    $pdf->Rect(130, 10, 70, 30, 'DF');
    $pdf->SetXY(130, 15);
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(70, 5, fpdf_text('FACTURA ELECTRÓNICA'), 0, 1, 'C');
    $pdf->Cell(130);
    $pdf->Cell(70, 5, fpdf_text('RUC: 20450987123'), 0, 1, 'C');
    $pdf->Cell(130);
    $pdf->Cell(70, 8, fpdf_text('F001 - 00000128'), 0, 1, 'C');

    $pdf->SetXY(10, 25);
    $pdf->SetFont('Arial', '', 10);
    $pdf->SetTextColor(100, 100, 100);
    $pdf->Cell(100, 5, fpdf_text('Dirección: Calle Las Orquídeas 456, Lima'), 0, 1, 'L');
    $pdf->Cell(100, 5, fpdf_text('Teléfono: (01) 444-5566 | email@empresa.com'), 0, 1, 'L');

    $pdf->Ln(15);
    
    // Datos del Cliente
    $pdf->SetFillColor(230, 230, 230);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->Cell(0, 8, fpdf_text(' DATOS DEL CLIENTE'), 0, 1, 'L', true);
    $pdf->Ln(2);
    
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(30, 6, fpdf_text('Señor(es):'), 0, 0);
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(100, 6, fpdf_text('CLIENTE DE PRUEBA ÁÉÍÓÚ'), 0, 1);
    
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(30, 6, fpdf_text('RUC / DNI:'), 0, 0);
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(100, 6, '10776655443', 0, 1);
    
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(30, 6, fpdf_text('Dirección:'), 0, 0);
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(100, 6, fpdf_text('Av. Los Helechos 789, San Isidro'), 0, 1);

    $pdf->Ln(10);

    // Tabla de Items
    $pdf->SetFillColor(44, 62, 80);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(20, 10, 'CANT.', 1, 0, 'C', true);
    $pdf->Cell(110, 10, fpdf_text('DESCRIPCIÓN'), 1, 0, 'C', true);
    $pdf->Cell(30, 10, 'P. UNIT.', 1, 0, 'C', true);
    $pdf->Cell(30, 10, 'IMPORTE', 1, 1, 'C', true);

    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('Arial', '', 10);
    
    $data = [
      [5, 'Licencia de Software Anual', 150.00],
      [1, 'Mantenimiento de Servidor', 250.00],
      [10, 'Cable de Red Cat6 1mt', 5.50],
      [2, 'Teclado Mecánico RGB', 85.00],
    ];

    $subtotal = 0;
    foreach($data as $row) {
      $pdf->Cell(20, 8, $row[0], 1, 0, 'C');
      $pdf->Cell(110, 8, fpdf_text('  ' . $row[1]), 1, 0, 'L');
      $pdf->Cell(30, 8, number_format($row[2], 2), 1, 0, 'R');
      $pdf->Cell(30, 8, number_format($row[0] * $row[2], 2), 1, 1, 'R');
      $subtotal += ($row[0] * $row[2]);
    }

    // Totales
    $igv = $subtotal * 0.18;
    $total = $subtotal + $igv;

    $pdf->Ln(5);
    $pdf->Cell(130);
    $pdf->Cell(30, 8, 'SUBTOTAL:', 0, 0, 'R');
    $pdf->Cell(30, 8, number_format($subtotal, 2), 1, 1, 'R');
    $pdf->Cell(130);
    $pdf->Cell(30, 8, 'IGV (18%):', 0, 0, 'R');
    $pdf->Cell(30, 8, number_format($igv, 2), 1, 1, 'R');
    $pdf->Cell(130);
    $pdf->SetFont('Arial', 'B', 11);
    $pdf->Cell(30, 10, 'TOTAL:', 0, 0, 'R');
    $pdf->Cell(30, 10, 'S/ ' . number_format($total, 2), 1, 1, 'R');
  }

  if (ob_get_length()) {
    ob_end_clean();
  }

  $pdf->Output('I', 'reporte.pdf');
  exit();
}
