<?php

// Datos simulados para la factura (Factura Electrónica SUNAT)
$factura = [
    'empresa' => [
        'razon_social' => 'EMPRESA DEMO S.A.C.',
        'ruc' => '20123456789',
        'direccion' => 'Av. Los Incas 123 - Lima - Lima',
        'telefono' => '(01) 555-1234',
        'email' => 'ventas@empresademo.com'
    ],
    'comprobante' => [
        'tipo' => 'FACTURA ELECTRÓNICA',
        'serie' => 'F001',
        'correlativo' => '0001234',
        'fecha_emision' => date('d/m/Y'),
        'hora_emision' => date('H:i:s'),
        'moneda' => 'PEN'
    ],
    'cliente' => [
        'razon_social' => 'CLIENTE DE PRUEBA EIRL',
        'ruc' => '20987654321',
        'direccion' => 'Calle Las Begonias 456 - San Isidro'
    ],
    'items' => [
        [
            'cantidad' => 2,
            'unidad' => 'NIU',
            'descripcion' => 'LAPTOP LENOVO THINKPAD T14',
            'valor_unitario' => 3500.00
        ],
        [
            'cantidad' => 5,
            'unidad' => 'NIU',
            'descripcion' => 'MOUSE INALÁMBRICO LOGITECH',
            'valor_unitario' => 85.00
        ],
        [
            'cantidad' => 1,
            'unidad' => 'ZZ',
            'descripcion' => 'SERVICIO DE INSTALACIÓN Y CONFIGURACIÓN',
            'valor_unitario' => 250.00
        ]
    ]
];

// Cálculos
$total_operaciones_gravadas = 0;
foreach ($factura['items'] as &$item) {
    $item['importe'] = $item['cantidad'] * $item['valor_unitario'];
    $total_operaciones_gravadas += $item['importe'];
}
unset($item);

$igv = $total_operaciones_gravadas * 0.18;
$importe_total = $total_operaciones_gravadas + $igv;

$factura['totales'] = [
    'op_gravadas' => $total_operaciones_gravadas,
    'igv' => $igv,
    'total' => $importe_total
];
