<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Comprobante Kiki</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      font-size: 13px;
      color: #333;
      margin: 0;
      padding: 10px;
      background-color: #fff;
    }
    .container {
      width: 100%;
      margin: 0;
    }
    
    /* -----------------------------------------------------------------------------
    // ESTILOS: TIPO FACTURA A4 (PREDETERMINADO)
    // ----------------------------------------------------------------------------- */
    .header {
      width: 100%;
      display: table;
      margin-bottom: 20px;
    }
    .header-col {
      display: table-cell;
      vertical-align: top;
    }
    .logo-container {
      width: 50%;
    }
    .logo-container img {
      max-width: 200px;
      max-height: 80px;
    }
    .company-details {
      margin-top: 10px;
      font-size: 12px;
      color: #555;
    }
    .invoice-box-container {
      width: 50%;
      text-align: right;
    }
    .invoice-box {
      border: 2px solid #ff0055;
      border-radius: 8px;
      padding: 15px;
      text-align: center;
      display: inline-block;
      min-width: 250px;
      background-color: #fff5f7;
    }
    .invoice-box h2 {
      margin: 0 0 10px 0;
      font-size: 18px;
      text-transform: uppercase;
      font-weight: bold;
      color: #ff0055;
    }
    .invoice-box p {
      margin: 5px 0;
      font-size: 16px;
      font-weight: bold;
      color: #333;
    }
    .client-details {
      border: 1px solid #ffccd8;
      border-radius: 5px;
      padding: 15px;
      margin-bottom: 20px;
    }
    .client-details table {
      width: 100%;
    }
    .client-details td {
      padding: 3px 0;
      vertical-align: top;
    }
    .client-details td.label {
      width: 120px;
      font-weight: bold;
      color: #ff0055;
    }
    .items-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 20px;
    }
    .items-table th, .items-table td {
      border: 1px solid #ffccd8;
      padding: 10px 8px;
      text-align: center;
    }
    .items-table th {
      background-color: #ff0055;
      color: #fff;
      font-weight: bold;
      text-transform: uppercase;
      font-size: 12px;
      border-color: #ff0055;
    }
    .items-table td.desc {
      text-align: left;
    }
    .items-table td.right {
      text-align: right;
    }
    .totals-wrapper {
      width: 100%;
      display: table;
    }
    .totals-left {
      display: table-cell;
      width: 60%;
      vertical-align: top;
      font-size: 11px;
      color: #555;
    }
    .totals-right {
      display: table-cell;
      width: 40%;
    }
    .totals-table {
      width: 100%;
      border-collapse: collapse;
    }
    .totals-table td {
      padding: 5px 8px;
      border: 1px solid #ffccd8;
      text-align: right;
    }
    .totals-table td.label {
      font-weight: bold;
      background-color: #fff5f7;
      color: #ff0055;
      border-color: #ffccd8;
    }
    .footer {
      margin-top: 40px;
      text-align: center;
      font-size: 11px;
      color: #777;
    }
    .qr-placeholder {
      margin-top: 15px;
      width: 100px;
      height: 100px;
      border: 1px solid #ffccd8;
      display: inline-block;
      line-height: 100px;
      text-align: center;
      color: #999;
      overflow:hidden;
    }
    .qr-placeholder img{
      object-fit: cover;
      object-position: center;
      width: 100%;
      height: 100%;
    }

    /* -----------------------------------------------------------------------------
    // ESTILOS: TIPO TICKET 80MM
    // ----------------------------------------------------------------------------- */
    .ticket-body {
      font-family: "Courier New", Courier, monospace;
      font-size: 11px;
      color: #000;
      padding: 5mm 3mm;
    }
    .ticket-container {
      width: 100%;
    }
    .ticket-header {
      text-align: center;
      margin-bottom: 5px;
    }
    .ticket-header h2 {
      margin: 0;
      font-size: 14px;
      font-weight: bold;
      color: #ff0055;
    }
    .ticket-header p {
      margin: 2px 0;
      font-size: 10px;
    }
    .divider {
      border-top: 1px dashed #000;
      margin: 5px 0;
    }
    .ticket-info {
      width: 100%;
      margin-bottom: 5px;
    }
    .ticket-info td {
      padding: 1px 0;
      vertical-align: top;
      font-size: 10px;
    }
    .ticket-info td.lbl {
      font-weight: bold;
      width: 45px;
    }
    .ticket-table {
      width: 100%;
      border-collapse: collapse;
      margin: 5px 0;
    }
    .ticket-table th {
      border-bottom: 1px solid #000;
      font-weight: bold;
      text-align: left;
      font-size: 10px;
      padding-bottom: 2px;
    }
    .ticket-table td {
      padding: 3px 0;
      vertical-align: top;
      font-size: 10px;
    }
    .text-right {
      text-align: right;
    }
    .ticket-totals {
      width: 100%;
      margin-top: 5px;
    }
    .ticket-totals td {
      padding: 1px 0;
      font-size: 10px;
    }
    .ticket-totals td.lbl {
      text-align: right;
      font-weight: bold;
      padding-right: 10px;
    }
    .ticket-qr {
      text-align: center;
      margin: 10px 0;
    }
    .ticket-qr img {
      width: 40mm;
      height: 40mm;
    }
    .ticket-footer {
      text-align: center;
      font-size: 9px;
      margin-top: 5px;
    }
  </style>
</head>
<body>
  <div class="container">
    <?php echo $content; ?>
  </div>
</body>
</html>
