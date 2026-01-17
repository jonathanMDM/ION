<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Activos - {{ date('Y-m-d') }}</title>
    <style>
        @media print {
            @page {
                size: landscape;
                margin: 1cm;
            }
            .no-print {
                display: none;
            }
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 9px;
            margin: 20px;
        }
        
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 5px;
            font-size: 16px;
        }
        
        .header-info {
            text-align: center;
            margin-bottom: 15px;
            color: #666;
        }
        
        .stats {
            display: flex;
            justify-content: space-around;
            margin-bottom: 15px;
            padding: 8px;
            background: #f5f5f5;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        
        .stat-item {
            text-align: center;
            padding: 0 5px;
        }
        
        .stat-value {
            font-size: 14px;
            font-weight: bold;
            color: #333;
        }
        
        .stat-label {
            font-size: 8px;
            color: #666;
            text-transform: uppercase;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        
        th {
            background-color: #333;
            color: white;
            padding: 4px;
            text-align: left;
            font-size: 8px;
            text-transform: uppercase;
        }
        
        td {
            padding: 3px 4px;
            border-bottom: 1px solid #ddd;
            font-size: 8px;
        }
        
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .status-active { color: green; font-weight: bold; }
        .status-maintenance { color: orange; font-weight: bold; }
        .status-decommissioned { color: red; font-weight: bold; }
        
        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 10px 20px;
            background: #4F46E5;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        .print-button:hover {
            background: #4338CA;
        }

        .currency {
            font-family: 'Courier New', Courier, monospace;
            text-align: right;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <button class="print-button no-print" onclick="window.print()">
        🖨️ Imprimir / Guardar como PDF
    </button>
    
    <h1>Reporte Consolidado de Activos</h1>
    <div class="header-info">
        <strong>{{ Auth::user()->company->name ?? 'ION Inventory' }}</strong><br>
        Generado por: {{ Auth::user()->name }} | Fecha: {{ date('d/m/Y H:i') }}
    </div>
    
    <div class="stats">
        <div class="stat-item">
            <div class="stat-value">{{ number_format($stats['total_assets']) }}</div>
            <div class="stat-label">Total Activos</div>
        </div>
        <div class="stat-item">
            <div class="stat-value" style="color: green;">${{ number_format($stats['total_purchase_price'], 2) }}</div>
            <div class="stat-label">Val. Compra Total</div>
        </div>
        @if(auth()->user()->company->hasModule('depreciation'))
        <div class="stat-item">
            <div class="stat-value" style="color: blue;">${{ number_format($stats['total_current_value'], 2) }}</div>
            <div class="stat-label">Val. Libros Total</div>
        </div>
        @endif
        <div class="stat-item">
            <div class="stat-value">{{ $stats['active'] }}</div>
            <div class="stat-label">Activos Ok</div>
        </div>
        <div class="stat-item">
            <div class="stat-value">{{ $stats['maintenance'] }}</div>
            <div class="stat-label">En Taller</div>
        </div>
    </div>
    
    @php
        $customFields = \App\Models\CustomField::where('company_id', auth()->user()->company_id)->get();
    @endphp

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Activo</th>
                <th>Ubicación</th>
                @if(auth()->user()->company->hasModule('cost_centers'))
                <th>Centro Costo</th>
                @endif
                @if(\App\Helpers\FieldHelper::isVisible('municipality_plate'))
                <th>Placa M.</th>
                @endif
                @foreach($customFields as $field)
                    @if(\App\Helpers\FieldHelper::isVisible($field->name))
                    <th>{{ $field->label }}</th>
                    @endif
                @endforeach
                <th>Cant.</th>
                <th>Estado</th>
                <th style="text-align: right">P. Compra</th>
                @if(auth()->user()->company->hasModule('depreciation'))
                <th style="text-align: right">V. Actual</th>
                @endif
                <th>Compra</th>
            </tr>
        </thead>
        <tbody>
            @foreach($assets as $asset)
            <tr>
                <td style="font-family: monospace">{{ $asset->custom_id }}</td>
                <td><strong>{{ $asset->name }}</strong></td>
                <td>{{ $asset->location->name ?? 'N/A' }}</td>
                @if(auth()->user()->company->hasModule('cost_centers'))
                <td>{{ $asset->costCenter->name ?? 'N/A' }}</td>
                @endif
                @if(\App\Helpers\FieldHelper::isVisible('municipality_plate'))
                <td>{{ $asset->municipality_plate ?? '-' }}</td>
                @endif
                @foreach($customFields as $field)
                    @if(\App\Helpers\FieldHelper::isVisible($field->name))
                    <td>{{ $asset->custom_attributes[$field->name] ?? '-' }}</td>
                    @endif
                @endforeach
                <td style="text-align: center">{{ $asset->quantity }}</td>
                <td class="status-{{ $asset->status }}">{{ ucfirst($asset->status) }}</td>
                <td class="currency">${{ number_format($asset->purchase_price, 2) }}</td>
                @if(auth()->user()->company->hasModule('depreciation'))
                <td class="currency" style="color: #4F46E5">${{ number_format($asset->value, 2) }}</td>
                @endif
                <td>{{ $asset->purchase_date ? $asset->purchase_date->format('d/m/Y') : 'N/A' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div style="margin-top: 20px; text-align: right; color: #999; font-size: 7px;">
        ION Inventory Management System - Trazabilidad y Control de Activos
    </div>

    <script>
        window.onload = function() {
            setTimeout(function() {
                // window.print();
            }, 1000);
        };
    </script>
</body>
</html>
