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
        .info-box {
            background-color: white;
            border-left: 4px solid #1f2937;
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
        .message-box {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border: 1px solid #e5e7eb;
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
            <h1 style="margin: 0; font-size: 24px;">Nueva Solicitud de Soporte</h1>
            <p style="margin: 5px 0 0 0; font-size: 14px;">ION Inventory</p>
        </div>
        
        <div class="content">
            <p>Se ha recibido una nueva solicitud de soporte:</p>
            
            <div class="info-box">
                <div class="info-row">
                    <span class="label">Asunto:</span> {{ $subject }}
                </div>
                <div class="info-row">
                    <span class="label">Categoría:</span> {{ $category }}
                </div>
                <div class="info-row">
                    <span class="label">Fecha:</span> {{ $submitted_at }}
                </div>
            </div>

            <div class="info-box">
                <h3 style="margin-top: 0; color: #1f2937;">Información del Usuario</h3>
                <div class="info-row">
                    <span class="label">Nombre:</span> {{ $user_name }}
                </div>
                <div class="info-row">
                    <span class="label">Email:</span> <a href="mailto:{{ $user_email }}">{{ $user_email }}</a>
                </div>
                <div class="info-row">
                    <span class="label">Empresa:</span> {{ $company_name }}
                </div>
            </div>

            <div class="message-box">
                <h3 style="margin-top: 0; color: #1f2937;">Mensaje:</h3>
                <p style="white-space: pre-wrap;">{{ $message }}</p>
            </div>

            @if(isset($attachment_name))
            <div class="info-box">
                <div class="info-row">
                    <span class="label">Archivo Adjunto:</span> {{ $attachment_name }}
                </div>
            </div>
            @endif
        </div>

        <div class="footer">
            <p>Este es un mensaje automático del sistema ION Inventory.</p>
            <p>&copy; {{ date('Y') }} ION Inventory. Todos los derechos reservados.</p>
        </div>
    </div>
</body>
</html>
