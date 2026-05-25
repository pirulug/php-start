
    <!-- HEADER -->
    <div class="header">
      <div class="header-col logo-container">
        <?php 
        $logoType = $config->get('logo_type', 'images');
        if ($logoType == 'images'): 
          // En PDF/Print se prefiere usar la imagen del logo
          $logoImg = !empty($config->logo()->light) ? $config->logo()->light : $config->logo()->dark;
          if (!empty($logoImg)):
        ?>
            <img src="<?= APP_URL . $logoImg ?>" alt="<?= clear_html($config->siteName()) ?>" style="margin-bottom: 10px;">
          <?php else: ?>
            <h1 style="margin:0; color:#ff0055;"><?= clear_html($config->siteName()) ?></h1>
          <?php endif; ?>
        <?php elseif ($logoType == 'text'): ?>
          <div style="margin-bottom: 10px; line-height: 24px;">
            <?php
            $iconSource = $config->get('logo_icon_source', 'class');
            if ($iconSource === 'image' && $config->get('logo_icon_file')):
              ?>
              <img src="<?= storage_uploads($config->get('logo_icon_file'), "site") ?>" alt="Icon"
                style="height: 24px; width: auto; vertical-align: middle; margin-right: 8px; display: inline-block;">
            <?php endif; ?>
            <h1 style="margin:0; color:#ff0055; font-size: 24px; display: inline-block; vertical-align: middle;"><?= clear_html($config->siteName()) ?></h1>
          </div>
        <?php endif; ?>
        <div class="company-details">
          <p style="margin: 3px 0;"><?= clear_html($factura['empresa']['direccion']) ?></p>
          <p style="margin: 3px 0;">Tel: <?= clear_html($factura['empresa']['telefono']) ?></p>
          <p style="margin: 3px 0;">Email: <?= clear_html($factura['empresa']['email']) ?></p>
        </div>
      </div>
      <div class="header-col invoice-box-container">
        <div class="invoice-box">
          <p>R.U.C. <?= clear_html($factura['empresa']['ruc']) ?></p>
          <h2><?= clear_html($factura['comprobante']['tipo']) ?></h2>
          <p><?= clear_html($factura['comprobante']['serie']) ?> - <?= clear_html($factura['comprobante']['correlativo']) ?></p>
        </div>
      </div>
    </div>

    <!-- CLIENTE -->
    <div class="client-details">
      <table>
        <tr>
          <td class="label">Señor(es):</td>
          <td><?= clear_html($factura['cliente']['razon_social']) ?></td>
          <td class="label" style="width:100px;">Fecha Emisión:</td>
          <td><?= clear_html($factura['comprobante']['fecha_emision']) ?></td>
        </tr>
        <tr>
          <td class="label">R.U.C.:</td>
          <td><?= clear_html($factura['cliente']['ruc']) ?></td>
          <td class="label">Hora Emisión:</td>
          <td><?= clear_html($factura['comprobante']['hora_emision']) ?></td>
        </tr>
        <tr>
          <td class="label">Dirección:</td>
          <td><?= clear_html($factura['cliente']['direccion']) ?></td>
          <td class="label">Moneda:</td>
          <td><?= clear_html($factura['comprobante']['moneda']) ?></td>
        </tr>
      </table>
    </div>

    <!-- ITEMS -->
    <table class="items-table">
      <thead>
        <tr>
          <th style="width: 10%;">Cant.</th>
          <th style="width: 10%;">Und.</th>
          <th style="width: 50%;">Descripción</th>
          <th style="width: 15%;">V. Unitario</th>
          <th style="width: 15%;">Importe</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($factura['items'] as $item): ?>
        <tr>
          <td><?= format_number($item['cantidad']) ?></td>
          <td><?= clear_html($item['unidad']) ?></td>
          <td class="desc"><?= clear_html($item['descripcion']) ?></td>
          <td class="right"><?= format_number_decimal($item['valor_unitario'], 2) ?></td>
          <td class="right"><?= format_number_decimal($item['importe'], 2) ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <!-- TOTALES -->
    <div class="totals-wrapper">
      <div class="totals-left">
        <p style="margin-top:0;"><strong>SON:</strong> CATORCE MIL SETECIENTOS CINCUENTA Y 00/100 SOLES (Ejemplo en texto)</p>
        
        <div class="qr-placeholder">
          <img src="<?= APP_URL."/service/qrcode/render?d=https://github.com/pirulug/php-start&s=qrh&sf=6&md=1&fc=000000&bc=ffffff" ?>" alt="QR Code">
        </div>
        
        <p style="margin-top: 15px;">
          Representación impresa de la Factura Electrónica.<br>
          Puede ser consultada en www.sunat.gob.pe
        </p>
      </div>
      <div class="totals-right">
        <table class="totals-table">
          <tr>
            <td class="label">OP. GRAVADAS</td>
            <td><?= clear_html($factura['comprobante']['moneda']) ?> <?= format_number_decimal($factura['totales']['op_gravadas'], 2) ?></td>
          </tr>
          <tr>
            <td class="label">OP. EXONERADAS</td>
            <td><?= clear_html($factura['comprobante']['moneda']) ?> 0.00</td>
          </tr>
          <tr>
            <td class="label">OP. INAFECTAS</td>
            <td><?= clear_html($factura['comprobante']['moneda']) ?> 0.00</td>
          </tr>
          <tr>
            <td class="label">I.G.V. (18%)</td>
            <td><?= clear_html($factura['comprobante']['moneda']) ?> <?= format_number_decimal($factura['totales']['igv'], 2) ?></td>
          </tr>
          <tr>
            <td class="label" style="font-size: 14px;">IMPORTE TOTAL</td>
            <td style="font-size: 14px; font-weight: bold;"><?= clear_html($factura['comprobante']['moneda']) ?> <?= format_number_decimal($factura['totales']['total'], 2) ?></td>
          </tr>
        </table>
      </div>
    </div>
    
