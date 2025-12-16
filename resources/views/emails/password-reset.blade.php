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
            border-radius: 5px 5px 0 0;
        }
        .content {
            background-color: #f9fafb;
            padding: 30px;
            border: 1px solid #e5e7eb;
        }
        .password-box {
            background-color: #dbeafe;
            border-left: 4px solid #3b82f6;
            padding: 15px;
            margin: 20px 0;
            font-size: 18px;
            font-weight: bold;
            text-align: center;
            letter-spacing: 2px;
        }
        .warning {
            background-color: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 15px;
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
            <h1 style="margin: 0; font-family: 'Orbitron', sans-serif;">ION</h1>
            <p style="margin: 5px 0 0 0;">Sistema de Inventario</p>
        </div>
        
        <div class="content">
            <h2>Recuperación de Contraseña</h2>
            
            <p>Hola <strong>{{ $user->name }}</strong>,</p>
            
            <p>Has solicitado recuperar tu contraseña. A continuación encontrarás tu contraseña temporal:</p>
            
            <div class="password-box">
                {{ $temporaryPassword }}
            </div>
            
            <div class="warning">
                <strong>⚠️ Importante:</strong>
                <ul style="margin: 10px 0;">
                    <li>Esta es una contraseña temporal</li>
                    <li>Al iniciar sesión, se te pedirá que cambies tu contraseña</li>
                    <li>Por seguridad, elige una contraseña segura y única</li>
                    <li>No compartas esta contraseña con nadie</li>
                </ul>
            </div>
            
            <p>Si no solicitaste este cambio, por favor contacta al administrador del sistema inmediatamente.</p>
            
            <p>Saludos,<br>
            <strong>Equipo ION Inventory</strong></p>
        </div>
        
        <div class="footer">
            <p>Este es un correo automático, por favor no respondas a este mensaje.</p>
            <p>&copy; {{ date('Y') }} ION Inventory. Todos los derechos reservados.</p>
        </div>
    </div>
</body>
</html>
