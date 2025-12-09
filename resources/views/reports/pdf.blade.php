<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Activos</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        h1 { text-align: center; color: #333; }
        .stats { margin: 20px 0; }
        .stat-box { display: inline-block; width: 30%; padding: 10px; margin: 5px; background: #f0f0f0; border-radius: 5px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background-color: #4a5568; color: white; padding: 10px; text-align: left; }
        td { padding: 8px; border-bottom: 1px solid #ddd; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .status-active { color: #10b981; font-weight: bold; }
        .status-maintenance { color: #f59e0b; font-weight: bold; }
        .status-decommissioned { color: #ef4444; font-weight: bold; }
    </style>
</head>
<body>
    <h1>Reporte de Activos ION</h1>
    <p style="text-align: center; color: #666;">Generado el: {{ date('Y-m-d H:i:s') }}</p>

    <div class="stats">
        <div class="stat-box">
            <strong>Total de Activos:</strong> {{ $stats['total_assets'] }}
        </div>
        <div class="stat-box">
            <strong>Valor Total:</strong> ${{ number_format($stats['total_value'], 2) }}
        </div>
        <div class="stat-box">
            <strong>Activos:</strong> {{ $stats['active'] }}
        </div>
        <div class="stat-box">
            <strong>Mantenimiento:</strong> {{ $stats['maintenance'] }}
        </div>
        <div class="stat-box">
            <strong>Dados de Baja:</strong> {{ $stats['decommissioned'] }}
        </div>
    </div>

    @php
        $customFields = \App\Models\CustomField::where('company_id', \Auth::user()->company_id)->get();
    @endphp

    <table>
        <thead>
            <tr>
                <th>ID Único</th>
                <th>Nombre</th>
                <th>Modelo</th>
                @if(\App\Helpers\FieldHelper::isVisible('municipality_plate'))
                <th>Placa Municipio</th>
                @endif
                @foreach($customFields as $field)
                    @if(\App\Helpers\FieldHelper::isVisible($field->name))
                    <th>{{ $field->label }}</th>
                    @endif
                @endforeach
                <th>Ubicación</th>
                <th>Categoría</th>
                <th>Proveedor</th>
                <th>Estado</th>
                <th>Cantidad</th>
                <th>Valor</th>
                <th>Fecha de Compra</th>
            </tr>
        </thead>
        <tbody>
            @foreach($assets as $asset)
            <tr>
                <td>{{ $asset->custom_id }}</td>
                <td>{{ $asset->name }}</td>
                <td>{{ $asset->model ?? '-' }}</td>
                @if(\App\Helpers\FieldHelper::isVisible('municipality_plate'))
                <td>{{ $asset->municipality_plate }}</td>
                @endif
                @foreach($customFields as $field)
                    @if(\App\Helpers\FieldHelper::isVisible($field->name))
                    <td>{{ $asset->custom_attributes[$field->name] ?? '-' }}</td>
                    @endif
                @endforeach
                <td>{{ $asset->location->name }}</td>
                <td>{{ $asset->subcategory->category->name }} / {{ $asset->subcategory->name }}</td>
                <td>{{ $asset->supplier->name ?? 'N/A' }}</td>
                <td class="status-{{ $asset->status }}">
                    {{ $asset->status == 'active' ? 'Activo' : ($asset->status == 'maintenance' ? 'Mantenimiento' : 'Dado de Baja') }}
                </td>
                <td>{{ $asset->quantity }}</td>
                <td>${{ number_format($asset->value, 2) }}</td>
                <td>{{ $asset->purchase_date ? $asset->purchase_date->format('Y-m-d') : 'N/A' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
