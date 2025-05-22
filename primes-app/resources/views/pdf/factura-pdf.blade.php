<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Factura TECNOBOX</title>
    <style>
        body { font-family: 'DejaVu Sans', Arial, sans-serif; background: #fff; color: #222; margin: 0; padding: 0; }
        .container { max-width: 800px; margin: 0 auto; background: #fff; border-radius: 12px; box-shadow: 0 2px 8px #e0e7ef; padding: 32px; }
        .header { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #e0e7ef; padding-bottom: 16px; margin-bottom: 24px; }
        .logo { height: 48px; }
        .title { font-size: 2rem; color: #2563eb; font-weight: bold; }
        .section { margin-bottom: 24px; }
        .section-title { font-size: 1.1rem; color: #2563eb; font-weight: bold; margin-bottom: 8px; }
        .info-table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        .info-table td { padding: 4px 0; }
        .products-table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        .products-table th, .products-table td { border: 1px solid #e0e7ef; padding: 8px; text-align: left; }
        .products-table th { background: #f1f5f9; color: #2563eb; font-weight: bold; }
        .products-table td img { width: 48px; height: 48px; object-fit: contain; border-radius: 6px; border: 1px solid #e0e7ef; background: #f9fafb; }
        .totals { width: 100%; margin-top: 16px; }
        .totals td { padding: 6px 0; }
        .totals .label { color: #555; }
        .totals .value { font-weight: bold; color: #2563eb; }
        .totals .total-row { font-size: 1.2rem; border-top: 2px solid #e0e7ef; }
        .footer { text-align: center; color: #888; font-size: 0.9rem; margin-top: 32px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ public_path('logo-tecnobox.png') }}" class="logo" alt="TECNOBOX">
            <div class="title">Factura</div>
        </div>
        <div class="section">
            <div class="section-title">Datos del Cliente</div>
            <table class="info-table">
                <tr><td><b>Nombre:</b></td><td>{{ $pedido->nombre }} {{ $pedido->apellido }}</td></tr>
                <tr><td><b>Dirección:</b></td><td>{{ $pedido->direccion_calle }}, {{ $pedido->ciudad }}, {{ $pedido->estado_direccion }}, {{ $pedido->codigo_postal }}</td></tr>
                <tr><td><b>Teléfono:</b></td><td>{{ $pedido->telefono }}</td></tr>
            </table>
        </div>
        <div class="section">
            <div class="section-title">Datos de la Orden</div>
            <table class="info-table">
                <tr><td><b>N° Pedido:</b></td><td>#{{ $pedido->id }}</td></tr>
                <tr><td><b>Fecha:</b></td><td>{{ $pedido->created_at->format('d/m/Y H:i') }}</td></tr>
                <tr><td><b>Método de pago:</b></td><td>{{ strtoupper($pedido->metodo_pago) }} @if($pedido->metodo_pago === 'stripe' || $pedido->metodo_pago === 'tarjeta') (**** **** **** {{ $pedido->ultimos4 ?? '1234' }}) @endif</td></tr>
                <tr><td><b>Estado:</b></td><td>{{ ucfirst($pedido->estado_pago) }}</td></tr>
            </table>
        </div>
        <div class="section">
            <div class="section-title">Detalle de Productos</div>
            <table class="products-table">
                <thead>
                    <tr>
                        <th>Imagen</th>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio unitario</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pedido->productos as $item)
                        <tr>
                            <td>
                                <img src="{{ url('storage', $item->producto->imagenes[0]) }}" >
                            </td>
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