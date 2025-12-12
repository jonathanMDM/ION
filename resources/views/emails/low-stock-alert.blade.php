<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alerta de Stock Bajo</title>
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
            background: linear-gradient(135deg, #ef4444 0%, #f97316 100%);
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
        .alert-icon {
            font-size: 48px;
            margin-bottom: 10px;
        }
        .greeting {
            font-size: 16px;
            margin-bottom: 20px;
        }
        .message {
            background-color: #fef2f2;
            border-left: 4px solid #ef4444;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th {
            background-color: #f3f4f6;
            padding: 12px;
            text-align: left;
            font-weight: 600;
            font-size: 12px;
            text-transform: uppercase;
            color: #6b7280;
            border-bottom: 2px solid #e5e7eb;
        }
        td {
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
        }
        .asset-name {
            font-weight: 600;
            color: #111827;
        }
        .asset-category {
            font-size: 12px;
            color: #6b7280;
        }
        .quantity-critical {
            background-color: #fee2e2;
            color: #991b1b;
            padding: 4px 8px;
            border-radius: 12px;
            font-weight: 600;
            display: inline-block;
        }
        .quantity-zero {
            background-color: #dc2626;
            color: white;
            padding: 4px 8px;
            border-radius: 12px;
            font-weight: 600;
            display: inline-block;
        }
        .cta-button {
            display: inline-block;
            background-color: #ef4444;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            margin: 20px 0;
        }
        .cta-button:hover {
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
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="alert-icon">⚠️</div>
            <h1>Alerta de Stock Bajo</h1>
        </div>

        <p class="greeting">Hola <strong>{{ $userName }}</strong>,</p>

        <div class="message">
            <p style="margin: 0;">
                <strong>{{ $lowStockAssets->count() }}</strong> activo(s) en tu inventario 
                {{ $lowStockAssets->count() == 1 ? 'ha alcanzado' : 'han alcanzado' }} 
                el nivel mínimo de stock configurado.
            </p>
        </div>

        <p>A continuación, los detalles de los activos que requieren atención:</p>

        <table>
            <thead>
                <tr>
                    <th>Activo</th>
                    <th style="text-align: center;">Stock Actual</th>
                    <th style="text-align: center;">Mínimo</th>
                </tr>
            </thead>
            <tbody>
                @foreach($lowStockAssets as $asset)
                <tr>
                    <td>
                        <div class="asset-name">{{ $asset->name }}</div>
                        <div class="asset-category">
                            {{ $asset->subcategory->category->name }} / {{ $asset->subcategory->name }}
                        </div>
                        <div class="asset-category">
                            📍 {{ $asset->location->name }}
                        </div>
                    </td>
                    <td style="text-align: center;">
                        @if($asset->quantity == 0)
                            <span class="quantity-zero">{{ $asset->quantity }}</span>
                        @else
                            <span class="quantity-critical">{{ $asset->quantity }}</span>
                        @endif
                    </td>
                    <td style="text-align: center;">
                        {{ $asset->minimum_quantity }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div style="text-align: center;">
            <a href="{{ route('assets.index') }}" class="cta-button">
                Ver Inventario Completo
            </a>
        </div>

        <p style="margin-top: 20px; font-size: 14px; color: #6b7280;">
            <strong>Recomendación:</strong> Considera realizar un pedido de reabastecimiento 
            para estos activos lo antes posible para evitar interrupciones en las operaciones.
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
