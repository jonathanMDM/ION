<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #f8f9fa; padding: 10px; text-align: center; border-bottom: 2px solid #007bff; }
        .content { padding: 20px 0; }
        .asset-list { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .asset-list th, .asset-list td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .asset-list th { background-color: #f2f2f2; }
        .footer { margin-top: 20px; font-size: 12px; color: #666; text-align: center; }
        .urgent { color: #dc3545; font-weight: bold; }
        .warning { color: #ffc107; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Alerta de Mantenimiento</h2>
        </div>
        <div class="content">
            <p>Hola,</p>
            <p>Los siguientes activos requieren mantenimiento próximamente o ya están vencidos:</p>

            <table class="asset-list">
                <thead>
                    <tr>
                        <th>Activo</th>
                        <th>Ubicación</th>
                        <th>Fecha Mantenimiento</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($assets as $asset)
                    <tr>
                        <td>{{ $asset->name }} ({{ $asset->custom_id }})</td>
                        <td>{{ $asset->location->name }}</td>
                        <td>{{ $asset->next_maintenance_date->format('d/m/Y') }}</td>
                        <td>
                            @if($asset->next_maintenance_date->isPast())
                                <span class="urgent">Vencido</span>
                            @else
                                <span class="warning">En {{ $asset->next_maintenance_date->diffInDays(now()) }} días</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <p>Por favor, programe los mantenimientos necesarios.</p>
            <p>
                <a href="{{ route('assets.index') }}">Ir al Inventario</a>
            </p>
        </div>
        <div class="footer">
            <p>Este es un mensaje automático del sistema ION Inventory.</p>
        </div>
    </div>
</body>
</html>
