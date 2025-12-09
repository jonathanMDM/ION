<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #1f2937;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background-color: #f9fafb;
            padding: 30px;
            border: 1px solid #e5e7eb;
        }
        .success-icon {
            text-align: center;
            font-size: 48px;
            color: #10b981;
            margin: 20px 0;
        }
        .info-box {
            background-color: white;
            border-left: 4px solid #10b981;
            padding: 15px;
            margin: 15px 0;
        }
        .info-row {
            margin: 8px 0;
        }
        .label {
            font-weight: bold;
            color: #1f2937;
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #1f2937;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            padding: 20px;
            color: #6b7280;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="margin: 0; font-size: 24px;">Solicitud Recibida</h1>
            <p style="margin: 5px 0 0 0; font-size: 14px;">ION Inventory - Centro de Soporte</p>
        </div>
        
        <div class="content">
            <div class="success-icon">✓</div>
            
            <h2 style="text-align: center; color: #1f2937;">¡Gracias por contactarnos!</h2>
            
            <p>Hola <strong>{{ $user_name }}</strong>,</p>
            
            <p>Hemos recibido tu solicitud de soporte y nuestro equipo la está revisando. Te responderemos lo antes posible.</p>
            
            <div class="info-box">
                <h3 style="margin-top: 0; color: #1f2937;">Resumen de tu Solicitud</h3>
                <div class="info-row">
                    <span class="label">Asunto:</span> {{ $subject }}
                </div>
                <div class="info-row">
                    <span class="label">Categoría:</span> {{ $category }}
                </div>
                <div class="info-row">
                    <span class="label">Fecha de Envío:</span> {{ $submitted_at }}
                </div>
            </div>

            <p><strong>¿Qué sigue?</strong></p>
            <ul>
                <li>Nuestro equipo revisará tu solicitud en las próximas 24-48 horas hábiles.</li>
                <li>Recibirás una respuesta en tu correo electrónico: <strong>{{ $user_email }}</strong></li>
                <li>Si tu consulta es urgente, puedes contactarnos directamente en support@ioninventory.com</li>
            </ul>

            <div style="text-align: center;">
                <p>Mientras tanto, puedes:</p>
                <a href="{{ url('/dashboard') }}" class="button">Volver al Dashboard</a>
            </div>
        </div>

        <div class="footer">
            <p>Si no solicitaste este soporte, por favor ignora este mensaje.</p>
            <p>&copy; {{ date('Y') }} ION Inventory. Todos los derechos reservados.</p>
            <p>Desarrollado por <a href="https://SoftDeveloper.com" style="color: #6b7280;">SoftDeveloper.com</a></p>
        </div>
    </div>
</body>
</html>
