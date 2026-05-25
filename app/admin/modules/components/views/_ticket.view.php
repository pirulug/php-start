<?php
// Datos de la factura reutilizados para el comprobante de ticket
?>
<div class="ticket-body">
  <div class="ticket-header">
    <?php 
    $logoType = $config->get('logo_type', 'images');
    if ($logoType == 'images'): 
      $logoImg = !empty($config->logo()->light) ? $config->logo()->light : $config->logo()->dark;
      if (!empty($logoImg)):
    ?>
        <img src="<?= APP_URL . $logoImg ?>" alt="<?= clear_html($config->siteName()) ?>" style="max-width: 100%; max-height: 40px; margin-bottom: 5px;">
      <?php else: ?>
        <h2><?= clear_html($config->siteName()) ?></h2>
      <?php endif; ?>
    <?php elseif ($logoType == 'text'): ?>
      <div style="text-align: center; margin-bottom: 5px; line-height: 18px;">
        <?php
        $iconSource = $config->get('logo_icon_source', 'class');
        if ($iconSource === 'image' && $config->get('logo_icon_file')):
          ?>
          <img src="<?= storage_uploads($config->get('logo_icon_file'), "site") ?>" alt="Icon"
            style="height: 18px; width: auto; vertical-align: middle; margin-right: 6px; display: inline-block;">
        <?php endif; ?>
        <h2 style="margin:0; color:#ff0055; display: inline-block; vertical-align: middle;"><?= clear_html($config->siteName()) ?></h2>
      </div>
    <?php endif; ?>
    <p>RUC: <?= clear_html($factura['empresa']['ruc']) ?></p>
    <p><?= clear_html($factura['empresa']['direccion']) ?></p>
    <p>Telf: <?= clear_html($factura['empresa']['telefono']) ?></p>
  </div>

  <div class="divider"></div>

  <div class="ticket-header" style="text-align: left; font-weight: bold; font-size: 11px;">
    <?= clear_html($factura['comprobante']['tipo']) ?><br>
    SERIE-NRO: <?= clear_html($factura['comprobante']['serie']) ?>-<?= clear_html($factura['comprobante']['correlativo']) ?>
  </div>

  <div class="divider"></div>

  <table class="ticket-info">
    <tr>
      <td class="lbl">FECHA:</td>
      <td><?= clear_html($factura['comprobante']['fecha_emision']) ?> <?= clear_html($factura['comprobante']['hora_emision']) ?></td>
    </tr>
    <tr>
      <td class="lbl">CLIENTE:</td>
      <td><?= clear_html($factura['cliente']['razon_social']) ?></td>
    </tr>
    <tr>
      <td class="lbl">RUC/DNI:</td>
      <td><?= clear_html($factura['cliente']['ruc']) ?></td>
    </tr>
    <tr>
      <td class="lbl">DIR:</td>
      <td><?= clear_html($factura['cliente']['direccion']) ?></td>
    </tr>
  </table>

  <div class="divider"></div>

  <table class="ticket-table">
    <thead>
      <tr>
        <th style="width: 10%;">CANT</th>
        <th style="width: 60%;">DESCRIPCIÓN</th>
        <th style="width: 30%; text-align: right;">IMPORTE</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($factura['items'] as $item): ?>
        <tr>
          <td><?= $item['cantidad'] ?></td>
          <td>
            <?= clear_html($item['descripcion']) ?><br>
            <small>1 x <?= format_number_decimal($item['valor_unitario'], 2) ?></small>
          </td>
          <td class="text-right"><?= format_number_decimal($item['importe'], 2) ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <div class="divider"></div>

  <table class="ticket-totals">
    <tr>
      <td class="lbl">GRAVADA</td>
      <td class="text-right"><?= clear_html($factura['comprobante']['moneda']) ?> <?= format_number_decimal($factura['totales']['op_gravadas'], 2) ?></td>
    </tr>
    <tr>
      <td class="lbl">I.G.V. (18%)</td>
      <td class="text-right"><?= clear_html($factura['comprobante']['moneda']) ?> <?= format_number_decimal($factura['totales']['igv'], 2) ?></td>
    </tr>
    <tr style="font-weight: bold; font-size: 11px;">
      <td class="lbl">TOTAL</td>
      <td class="text-right"><?= clear_html($factura['comprobante']['moneda']) ?> <?= format_number_decimal($factura['totales']['total'], 2) ?></td>
    </tr>
  </table>

  <div class="divider"></div>

  <div class="ticket-qr">
    <img src="<?= APP_URL."/service/qrcode/render?d=https://github.com/pirulug/php-start&s=qrh&sf=6&md=1&fc=000000&bc=ffffff" ?>" alt="QR Code">
  </div>

  <div class="ticket-footer">
    ¡Gracias por su compra!<br>
    Representación impresa de la Factura Electrónica.
  </div>
</div>
