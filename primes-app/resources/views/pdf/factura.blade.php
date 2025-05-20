<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Factura #{{ str_pad($pedido->id, 8, '0', STR_PAD_LEFT) }}</title>
    <style>
        body {
            font-family: 'Helvetica', sans-serif;
            color: #333;
            line-height: 1.6;
        }
        .container {
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #3b82f6;
            padding-bottom: 20px;
        }
        .header img {
            max-width: 200px;
            margin-bottom: 10px;
        }
        .invoice-details {
            margin-bottom: 30px;
        }
        .invoice-details table {
            width: 100%;
        }
        .invoice-details td {
            padding: 5px;
            vertical-align: top;
        }
        .customer-details {
            margin-bottom: 30px;
            padding: 15px;
            background-color: #f8fafc;
            border-radius: 5px;
        }
        .products-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .products-table th {
            background-color: #3b82f6;
            color: white;
            padding: 10px;
            text-align: left;
        }
        .products-table td {
            padding: 10px;
            border-bottom: 1px solid #e5e7eb;
        }
        .products-table img {
            max-width: 50px;
            height: auto;
        }
        .totals {
            float: right;
            width: 300px;
        }
        .totals table {
            width: 100%;
        }
        .totals td {
            padding: 5px;
        }
        .totals .total {
            font-weight: bold;
            font-size: 1.2em;
            border-top: 2px solid #3b82f6;
        }
        .footer {
            text-align: center;
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            color: #6b7280;
            font-size: 0.9em;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>TECNOBOX</h1>
            <p>Factura #{{ str_pad($pedido->id, 8, '0', STR_PAD_LEFT) }}</p>
        </div>

        <div class="invoice-details">
            <table>
                <tr>
                    <td width="50%">
                        <strong>Fecha de emisión:</strong><br>
                        {{ $pedido->created_at->format('d/m/Y') }}
                    </td>
                    <td width="50%">
                        <strong>Estado del pedido:</strong><br>
                        {{ ucfirst($pedido->estado) }}
                    </td>
                </tr>
            </table>
        </div>

        <div class="customer-details">
            <h3>Información del cliente</h3>
            <p>
                <strong>{{ $pedido->user->name }}</strong><br>
                {{ $pedido->direccion->direccion_calle }}<br>
                {{ $pedido->direccion->ciudad }}, {{ $pedido->direccion->estado }}<br>
                {{ $pedido->direccion->codigo_postal }}<br>
                Tel: {{ $pedido->direccion->telefono }}
            </p>
        </div>

        <table class="products-table">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio unitario</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pedido->productos as $item)
                    <tr>
                        <td>
                            {{ $item->producto->nombre }}
                        </td>
                        <td>{{ $item->cantidad }}</td>
                        <td>RD$ {{ number_format($item->precio_unitario, 2) }}</td>
                        <td>RD$ {{ number_format($item->precio_total, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="totals">
            <table>
                <tr>
                    <td>Subtotal:</td>
                    <td align="right">RD$ {{ number_format($subtotal, 2) }}</td>
                </tr>
                <tr>
                    <td>ITBIS (18%):</td>
                    <td align="right">RD$ {{ number_format($impuestos, 2) }}</td>
                </tr>
                <tr>
                    <td>Envío:</td>
                    <td align="right">RD$ {{ number_format($envio, 2) }}</td>
                </tr>
                <tr class="total">
                    <td>Total:</td>
                    <td align="right">RD$ {{ number_format($total, 2) }}</td>
                </tr>
            </table>
        </div>

        <div class="footer">
            <p>Gracias por tu compra en TECNOBOX</p>
            <p>Para cualquier consulta, contáctanos en support@tecnobox.com</p>
        </div>
    </div>
</body>
</html> 