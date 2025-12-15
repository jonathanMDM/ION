<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contraseña Cambiada</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background-color: #ffffff;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
            padding: 20px;
            border-radius: 8px 8px 0 0;
            margin: -30px -30px 20px -30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .icon {
            font-size: 48px;
            margin-bottom: 10px;
        }
        .alert-box {
            background-color: #fef2f2;
            border-left: 4px solid #ef4444;
            padding: 15px;
            border-radius: 6px;
            margin: 20px 0;
        }
        .alert-box h3 {
            margin: 0 0 10px 0;
            color: #991b1b;
            font-size: 16px;
        }
        .info-table {
            width: 100%;
            margin: 20px 0;
            border-collapse: collapse;
        }
        .info-table td {
            padding: 10px;
            border-bottom: 1px solid #e5e7eb;
        }
        .info-table td:first-child {
            font-weight: 600;
            color: #6b7280;
            width: 40%;
        }
        .info-table td:last-child {
            color: #111827;
        }
        .button {
            display: inline-block;
            background-color: #ef4444;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            margin: 20px 0;
            text-align: center;
        }
        .button:hover {
            background-color: #dc2626;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            font-size: 12px;
            color: #6b7280;
            text-align: center;
        }
        .security-tip {
            background-color: #eff6ff;
            padding: 15px;
            border-radius: 6px;
            margin: 20px 0;
            border-left: 4px solid #3b82f6;
        }
        .security-tip h4 {
            margin: 0 0 10px 0;
            color: #1e40af;
            font-size: 14px;
        }
        .security-tip ul {
            margin: 0;
            padding-left: 20px;
            font-size: 13px;
            color: #1e40af;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="icon">🔐</div>
            <h1>Contraseña Cambiada</h1>
        </div>

        <p style="font-size: 16px;">Hola <strong>{{ $userName }}</strong>,</p>

        <div class="alert-box">
            <h3>⚠️ Tu contraseña ha sido cambiada exitosamente</h3>
            <p style="margin: 0; font-size: 14px;">
                Se ha realizado un cambio de contraseña en tu cuenta de ION Inventory.
            </p>
        </div>

        <p>Si fuiste tú quien realizó este cambio, puedes ignorar este mensaje. Tu cuenta está segura.</p>

        <h3 style="color: #111827; margin-top: 25px;">Detalles del Cambio:</h3>
        
        <table class="info-table">
            <tr>
                <td>📅 Fecha y Hora:</td>
                <td>{{ $changedAt }}</td>
            </tr>
            <tr>
                <td>🌐 Dirección IP:</td>
                <td>{{ $ipAddress }}</td>
            </tr>
            <tr>
                <td>💻 Navegador:</td>
                <td>{{ $userAgent }}</td>
            </tr>
        </table>

        <div class="alert-box" style="background-color: #fff7ed; border-left-color: #f59e0b;">
            <h3 style="color: #92400e;">⚠️ ¿No fuiste tú?</h3>
            <p style="margin: 0; font-size: 14px; color: #92400e;">
                Si <strong>NO</strong> realizaste este cambio, tu cuenta podría estar comprometida. 
                Contacta inmediatamente con el administrador del sistema.
            </p>
        </div>

        <div style="text-align: center;">
            <a href="{{ route('login') }}" class="button">
                Iniciar Sesión
            </a>
        </div>

        <div class="security-tip">
            <h4>🛡️ Consejos de Seguridad:</h4>
            <ul>
                <li>Usa contraseñas únicas y complejas</li>
                <li>No compartas tu contraseña con nadie</li>
                <li>Cambia tu contraseña regularmente</li>
                <li>Habilita la autenticación de dos factores si está disponible</li>
            </ul>
        </div>

        <div class="footer">
            <p>
                Este es un mensaje automático de seguridad del sistema ION Inventory.<br>
                Por favor, no respondas a este correo.
            </p>
            <p style="margin-top: 10px;">
                © {{ date('Y') }} ION Inventory. Todos los derechos reservados.
            </p>
        </div>
    </div>
</body>
</html>
