<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido a ION Inventory</title>
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
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
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
        .credentials-box {
            background-color: #f9fafb;
            border-left: 4px solid #4f46e5;
            padding: 20px;
            border-radius: 6px;
            margin: 20px 0;
        }
        .credentials-box h3 {
            margin: 0 0 15px 0;
            color: #4f46e5;
            font-size: 16px;
        }
        .credential-item {
            margin: 10px 0;
            padding: 10px;
            background-color: #ffffff;
            border-radius: 4px;
        }
        .credential-label {
            font-weight: 600;
            color: #6b7280;
            font-size: 12px;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        .credential-value {
            font-size: 16px;
            color: #111827;
            font-family: 'Courier New', monospace;
            word-break: break-all;
        }
        .button {
            display: inline-block;
            background-color: #4f46e5;
            color: white !important;
            padding: 14px 28px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            margin: 20px 0;
            text-align: center;
        }
        .button:hover {
            background-color: #4338ca;
        }
        .warning-box {
            background-color: #fff7ed;
            border-left: 4px solid #f59e0b;
            padding: 15px;
            border-radius: 6px;
            margin: 20px 0;
        }
        .warning-box p {
            margin: 0;
            font-size: 14px;
            color: #92400e;
        }
        .info-box {
            background-color: #eff6ff;
            padding: 15px;
            border-radius: 6px;
            margin: 20px 0;
            border-left: 4px solid #3b82f6;
        }
        .info-box h4 {
            margin: 0 0 10px 0;
            color: #1e40af;
            font-size: 14px;
        }
        .info-box ul {
            margin: 0;
            padding-left: 20px;
            font-size: 13px;
            color: #1e40af;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            font-size: 12px;
            color: #6b7280;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div style="margin-bottom: 15px;">
                <img src="{{ asset('img/logo-horizontal.png') }}" alt="ION Inventory" style="height: 40px; width: auto;">
            </div>
            <div class="icon">🎉</div>
            <h1>¡Bienvenido a ION Inventory!</h1>
            <p style="margin: 5px 0 0 0; font-size: 14px; opacity: 0.9;">{{ $company->name }}</p>
        </div>

        <p style="font-size: 16px;">Hola <strong>{{ $user->name }}</strong>,</p>

        <p>¡Nos complace darte la bienvenida a ION Inventory! Tu cuenta de administrador ha sido creada exitosamente para <strong>{{ $company->name }}</strong>.</p>

        <div class="credentials-box">
            <h3>🔑 Tus Credenciales de Acceso</h3>
            
            <div class="credential-item">
                <div class="credential-label">Usuario (Email)</div>
                <div class="credential-value">{{ $user->email }}</div>
            </div>

            <div class="credential-item">
                <div class="credential-label">Contraseña Temporal</div>
                <div class="credential-value">{{ $temporaryPassword }}</div>
            </div>

            <div class="credential-item">
                <div class="credential-label">URL de Acceso</div>
                <div class="credential-value">{{ $loginUrl }}</div>
            </div>
        </div>

        <div class="warning-box">
            <p><strong>⚠️ Importante:</strong> Por seguridad, deberás cambiar tu contraseña al iniciar sesión por primera vez.</p>
        </div>

        <div style="text-align: center;">
            <a href="{{ $loginUrl }}" class="button">
                Iniciar Sesión Ahora
            </a>
        </div>

        <div class="info-box">
            <h4>📋 Próximos Pasos:</h4>
            <ul>
                <li>Inicia sesión con las credenciales proporcionadas</li>
                <li>Cambia tu contraseña temporal por una segura</li>
                <li>Explora el sistema y configura tu inventario</li>
                <li>Agrega usuarios adicionales si es necesario</li>
            </ul>
        </div>

        <p>Si tienes alguna pregunta o necesitas ayuda, no dudes en contactarnos.</p>

        <p style="margin-top: 30px;">
            Saludos cordiales,<br>
            <strong>El equipo de ION Inventory</strong>
        </p>

        <div class="footer">
            <p>
                Este es un mensaje automático del sistema ION Inventory.<br>
                Por favor, no respondas a este correo.
            </p>
            <p style="margin-top: 10px;">
                © {{ date('Y') }} ION Inventory. Todos los derechos reservados.
            </p>
        </div>
    </div>
</body>
</html>
