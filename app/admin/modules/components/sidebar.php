<?php

Sidebar::group('Componentes', 'package', function ($group) {

  $group->item('Captcha', admin_route('components/captcha'))
    ->can('components.captcha');

  $group->item('SweetAlert2', admin_route('components/sweetalert'))
    ->can('components.sweetalert');

  $group->item('Gravatar', admin_route('components/gravatar'))
    ->can('components.gravatar');

  $group->item('Barcodes', admin_route('components/barcode'))
    ->can('components.barcode');

  $group->item('QR', admin_route('components/qrcode'))
    ->can('components.qrcode');

  $group->item('FPDF (PDF)', admin_route('components/fpdf'))
    ->can('components.fpdf');

  $group->item('Kiki (Automatización)', admin_route('components/kiki'))
    ->can('components.fpdf'); // Compartimos el permiso de fpdf por simplicidad

  $group->item('Osamu (Editor)', admin_route('components/osamu'))
    ->can('components.sweetalert');
});