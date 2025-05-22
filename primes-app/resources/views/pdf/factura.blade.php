<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Factura #{{ str_pad($pedido->id, 8, '0', STR_PAD_LEFT) }}</title>
    <style>
        body {
            font-family: 'Helvetica', Arial, sans-serif;
            color: #222;
            background: #f6f8fa;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 900px;
            margin: 32px auto;
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 2px 12px #e0e7ef;
            padding: 40px 48px 32px 48px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 3px solid #2563eb;
            padding-bottom: 18px;
            margin-bottom: 32px;
        }
        .empresa {
            font-size: 2.1rem;
            color: #2563eb;
            font-weight: bold;
            margin-bottom: 6px;
        }
        .empresa-info {
            color: #222;
            font-size: 1.1rem;
            margin-bottom: 2px;
        }
        .factura-title {
            font-size: 1.3rem;
            color: #2563eb;
            font-weight: bold;
            margin-bottom: 8px;
            text-align: right;
        }
        .factura-num {
            color: #444;
            font-size: 1rem;
            text-align: right;
        }
        .section-title {
            font-size: 1.1rem;
            color: #2563eb;
            font-weight: bold;
            margin-bottom: 8px;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
        }
        .info-table td {
            padding: 4px 0;
        }
        .products-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
        }
        .products-table th {
            background: #e8f0fe;
            color: #2563eb;
            font-weight: bold;
            padding: 10px 8px;
            border-bottom: 2px solid #2563eb;
            font-size: 1rem;
        }
        .products-table td {
            padding: 10px 8px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 0.98rem;
        }
        .products-table td img {
            width: 48px;
            height: 48px;
            object-fit: contain;
            border-radius: 6px;
            border: 1px solid #e0e7ef;
            background: #f9fafb;
        }
        .totals {
            width: 320px;
            float: right;
            margin-top: 12px;
        }
        .totals td {
            padding: 7px 0;
            font-size: 1rem;
        }
        .totals .label {
            color: #555;
        }
        .totals .value {
            font-weight: bold;
            color: #2563eb;
        }
        .totals .total-row {
            font-size: 1.15rem;
            border-top: 2px solid #2563eb;
        }
        .footer {
            text-align: center;
            color: #888;
            font-size: 0.98rem;
            margin-top: 48px;
            border-top: 1px solid #e0e7ef;
            padding-top: 18px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div>
                <div class="empresa">TECNOBOX</div>
                <div class="empresa-info">Gurabo, Tigaiga, Plaza Alfa</div>
                <div class="empresa-info">Santiago, Rep. Dominicana</div>
                <div class="empresa-info">RNC: 123456789</div>
            </div>
            <div style="text-align:right;">
                <div class="factura-title">Factura Electrónica</div>
                <div class="factura-num">N° {{ str_pad($pedido->id, 8, '0', STR_PAD_LEFT) }}</div>
                <div class="empresa-info">Fecha: {{ $pedido->created_at->format('d/m/Y') }}</div>
            </div>
        </div>
        <div style="margin-bottom: 24px;">
            <div class="section-title">Datos del Cliente</div>
            <table class="info-table">
                <tr><td><b>Nombre:</b></td><td>{{ $pedido->nombre }} {{ $pedido->apellido }}</td></tr>
                <tr><td><b>Dirección:</b></td><td>{{ $pedido->direccion_calle }}, {{ $pedido->ciudad }}, {{ $pedido->estado_direccion }}, {{ $pedido->codigo_postal }}</td></tr>
                <tr><td><b>Teléfono:</b></td><td>{{ $pedido->telefono }}</td></tr>
                <tr><td><b>Método de pago:</b></td><td>
                    @php
                        $metodo = [
                            'stripe' => 'Tarjeta de crédito',
                            'tarjeta' => 'Tarjeta de crédito',
                            'paypal' => 'PayPal',
                            'pce' => 'Pago contra entrega',
                        ][$pedido->metodo_pago] ?? ucfirst($pedido->metodo_pago);
                    @endphp
                    {{ $metodo }}
                </td></tr>
            </table>
        </div>
        <div style="margin-bottom: 24px;">
            <div class="section-title">Detalle de Productos</div>
            <table class="products-table">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio unitario</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pedido->productos as $item)
                        <tr>
                            <td>{{ $item->producto->nombre ?? 'Producto eliminado' }}</td>
                            <td>{{ $item->cantidad }}</td>
                            <td>RD$ {{ number_format($item->precio_unitario, 2) }}</td>
                            <td>RD$ {{ number_format($item->precio_total, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <table class="totals">
            <tr><td class="label">Subtotal</td><td class="value">RD$ {{ number_format($pedido->productos->sum('precio_total') - $pedido->costo_envio - round(($pedido->productos->sum('precio_total') - $pedido->costo_envio) * 0.18, 2), 2) }}</td></tr>
            <tr><td class="label">ITBIS (18%)</td><td class="value">RD$ {{ number_format(round(($pedido->productos->sum('precio_total') - $pedido->costo_envio) * 0.18, 2), 2) }}</td></tr>
            <tr><td class="label">Envío</td><td class="value">RD$ {{ number_format($pedido->costo_envio, 2) }}</td></tr>
            <tr class="total-row"><td class="label">Total</td><td class="value">RD$ {{ number_format($pedido->productos->sum('precio_total'), 2) }}</td></tr>
        </table>
        <div class="footer">
            Gracias por su compra en TECNOBOX. Si tiene alguna pregunta, contáctenos.<br>
            <b>www.tecnobox.com.do</b>
        </div>
    </div>
</body>
</html>