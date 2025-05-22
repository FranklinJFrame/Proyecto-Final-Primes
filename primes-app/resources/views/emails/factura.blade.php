<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Factura de tu compra en TECNOBOX</title>
</head>
<body style="font-family: Arial, sans-serif; background: #f4f7fa; color: #222; margin:0; padding:0;">
    <div style="max-width: 600px; margin: 40px auto; background: #fff; border-radius: 12px; box-shadow: 0 2px 8px #e0e7ef; padding: 32px;">
        <h2 style="color: #2563eb;">¡Gracias por tu compra en TECNOBOX!</h2>
        <p>Hola {{ $pedido->nombre }} {{ $pedido->apellido }},</p>
        <p>Adjuntamos la factura de tu pedido <b>#{{ str_pad($pedido->id, 8, '0', STR_PAD_LEFT) }}</b> realizado el {{ $pedido->created_at->format('d/m/Y') }}.</p>
        <p>Si tienes alguna pregunta, puedes responder a este correo o contactarnos a través de nuestra web.</p>
        <br>
        <p style="color: #888; font-size: 0.95rem;">Este correo fue generado automáticamente. No es necesario responder.</p>
        <div style="margin-top: 32px; color: #2563eb; font-weight: bold;">TECNOBOX</div>
    </div>
</body>
</html> 