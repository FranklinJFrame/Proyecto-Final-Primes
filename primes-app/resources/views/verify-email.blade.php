<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificación de correo | TECNOBOX</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: #f4f7fa;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .container {
            text-align: center;
            background: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 8px 32px 0 rgba(31,38,135,0.10);
            max-width: 400px;
            width: 90%;
        }
        .logo {
            width: 160px;
            margin-bottom: 20px;
        }
        h1 {
            color: #2563eb;
            font-size: 1.5rem;
            margin-bottom: 15px;
        }
        p {
            color: #64748b;
            font-size: 1rem;
            margin-bottom: 20px;
        }
        .success {
            color: #10b981;
            font-weight: bold;
            margin-bottom: 25px;
        }
        .redirecting {
            color: #64748b;
            font-size: 0.95rem;
        }
        .footer {
            margin-top: 30px;
            color: #2563eb;
            font-size: 1.1rem;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="https://i0.wp.com/tiotecnobox.cl/wp-content/uploads/2023/09/logo_tecnobox_solo-1-removebg-preview.png" alt="TECNOBOX" class="logo">
        <h1>Correo verificado</h1>
        <div class="success">¡Tu correo ha sido verificado exitosamente!</div>
        <p>Redirigiendo a la página principal...</p>
        <div class="redirecting">(Si no se redirige automáticamente, haz clic <a href="/" style="color: #2563eb; text-decoration: none;">aquí</a>)</div>
        <div class="footer">TECNOBOX</div>
    </div>
    <script>
        // Redirigir automáticamente al homepage después de 3 segundos
        setTimeout(function() {
            window.location.href = '/';
        }, 3000);
    </script>
</body>
</html>
