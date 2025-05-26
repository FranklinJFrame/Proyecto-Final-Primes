<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $titulo }}</title>
</head>
<body style="font-family: Arial, sans-serif; background: #f8fafc; color: #222; padding: 2rem;">
    <div style="max-width: 600px; margin: 0 auto; background: #fff; border-radius: 8px; box-shadow: 0 2px 8px #0001; padding: 2rem;">
        <h2 style="color: #2563eb;">{{ $titulo }}</h2>
        <p>Hola <strong>{{ $devolucion->user->name }}</strong>,</p>
        <p>{!! nl2br(e($mensaje)) !!}</p>
        <hr style="margin: 2rem 0;">
        <p><strong>ID de Solicitud:</strong> #{{ str_pad($devolucion->id, 6, '0', STR_PAD_LEFT) }}</p>
        <p><strong>Estado actual:</strong> {{ ucfirst($devolucion->estado) }}</p>
        <p style="color: #64748b; font-size: 0.95em;">Gracias por confiar en nosotros.<br>Equipo de Atención al Cliente</p>
    </div>
</body>
</html> 