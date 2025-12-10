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
            font-size: 12px;
            margin: 20px;
        }
        
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 10px;
        }
        
        .header-info {
            text-align: center;
            margin-bottom: 20px;
            color: #666;
        }
        
        .stats {
            display: flex;
            justify-content: space-around;
            margin-bottom: 20px;
            padding: 15px;
            background: #f5f5f5;
            border-radius: 5px;
        }
        
        .stat-item {
            text-align: center;
        }
        
        .stat-value {
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }
        
        .stat-label {
            font-size: 11px;
            color: #666;
            text-transform: uppercase;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        th {
            background-color: #333;
            color: white;
            padding: 8px;
            text-align: left;
            font-size: 11px;
            text-transform: uppercase;
        }
        
        td {
            padding: 6px 8px;
            border-bottom: 1px solid #ddd;
            font-size: 11px;
        }
        
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .status-active {
            color: green;
            font-weight: bold;
        }
        
        .status-maintenance {
            color: orange;
            font-weight: bold;
        }
        
        .status-decommissioned {
            color: red;
            font-weight: bold;
        }
        
        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 10px 20px;
            background: #333;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }
        
        .print-button:hover {
            background: #555;
        }
    </style>
</head>
<body>
    <button class="print-button no-print" onclick="window.print()">
        🖨️ Imprimir / Guardar como PDF
    </button>
    
    <h1>Reporte de Activos</h1>
    <div class="header-info">
        <strong>{{ Auth::user()->company->name ?? 'ION Inventory' }}</strong><br>
        Fecha de generación: {{ date('d/m/Y H:i') }}
    </div>
    
    <div class="stats">
        <div class="stat-item">
            <div class="stat-value">{{ $stats['total_assets'] }}</div>
            <div class="stat-label">Total Activos</div>
        </div>
        <div class="stat-item">
            <div class="stat-value">${{ number_format($stats['total_value'], 2) }}</div>
            <div class="stat-label">Valor Total</div>
        </div>
        <div class="stat-item">
            <div class="stat-value">{{ $stats['active'] }}</div>
            <div class="stat-label">Activos</div>
        </div>
        <div class="stat-item">
            <div class="stat-value">{{ $stats['maintenance'] }}</div>
            <div class="stat-label">Mantenimiento</div>
        </div>
        <div class="stat-item">
            <div class="stat-value">{{ $stats['decommissioned'] }}</div>
            <div class="stat-label">Dados de Baja</div>
        </div>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Categoría</th>
                <th>Ubicación</th>
                <th>Estado</th>
                <th>Cantidad</th>
                <th>Valor</th>
                <th>Proveedor</th>
                <th>Fecha Compra</th>
            </tr>
        </thead>
        <tbody>
            @foreach($assets as $asset)
            <tr>
                <td>{{ $asset->custom_id }}</td>
                <td>{{ $asset->name }}</td>
                <td>{{ $asset->subcategory->category->name ?? 'N/A' }} / {{ $asset->subcategory->name ?? 'N/A' }}</td>
                <td>{{ $asset->location->name ?? 'N/A' }}</td>
                <td class="status-{{ $asset->status }}">{{ ucfirst($asset->status) }}</td>
                <td>{{ $asset->quantity }}</td>
                <td>${{ number_format($asset->value, 2) }}</td>
                <td>{{ $asset->supplier->name ?? 'N/A' }}</td>
                <td>{{ $asset->purchase_date ? $asset->purchase_date->format('d/m/Y') : 'N/A' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <script>
        // Auto-open print dialog after page loads
        window.onload = function() {
            setTimeout(function() {
                // Uncomment to auto-open print dialog
                // window.print();
            }, 500);
        };
    </script>
</body>
</html>
