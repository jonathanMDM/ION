<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resumen Semanal</title>
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
        .week-period {
            background-color: #f3f4f6;
            padding: 10px;
            border-radius: 6px;
            text-align: center;
            margin-bottom: 20px;
            font-size: 14px;
            color: #6b7280;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin: 20px 0;
        }
        .stat-card {
            background-color: #f9fafb;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #4f46e5;
        }
        .stat-value {
            font-size: 28px;
            font-weight: bold;
            color: #4f46e5;
            margin: 5px 0;
        }
        .stat-label {
            font-size: 12px;
            color: #6b7280;
            text-transform: uppercase;
        }
        .section-title {
            font-size: 18px;
            font-weight: bold;
            color: #111827;
            margin: 25px 0 15px 0;
            padding-bottom: 10px;
            border-bottom: 2px solid #e5e7eb;
        }
        .asset-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .asset-item {
            background-color: #f9fafb;
            padding: 12px;
            margin-bottom: 8px;
            border-radius: 6px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .asset-name {
            font-weight: 600;
            color: #111827;
        }
        .asset-category {
            font-size: 12px;
            color: #6b7280;
        }
        .badge {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
        }
        .badge-success {
            background-color: #d1fae5;
            color: #065f46;
        }
        .badge-warning {
            background-color: #fef3c7;
            color: #92400e;
        }
        .badge-danger {
            background-color: #fee2e2;
            color: #991b1b;
        }
        .cta-button {
            display: inline-block;
            background-color: #4f46e5;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            margin: 20px 0;
            text-align: center;
        }
        .cta-button:hover {
            background-color: #4338ca;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            font-size: 12px;
            color: #6b7280;
            text-align: center;
        }
        .empty-state {
            text-align: center;
            padding: 20px;
            color: #9ca3af;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div style="font-size: 48px; margin-bottom: 10px;">📊</div>
            <h1>Resumen Semanal</h1>
            <p style="margin: 5px 0 0 0; font-size: 14px; opacity: 0.9;">{{ $companyName }}</p>
        </div>

        <p style="font-size: 16px;">Hola <strong>{{ $userName }}</strong>,</p>

        <div class="week-period">
            📅 Período: <strong>{{ $weekStart }}</strong> - <strong>{{ $weekEnd }}</strong>
        </div>

        <p>Aquí está tu resumen semanal de actividad en el inventario:</p>

        <!-- Statistics Grid -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-label">Total Activos</div>
                <div class="stat-value">{{ $stats['total_assets'] }}</div>
            </div>
            <div class="stat-card" style="border-left-color: #10b981;">
                <div class="stat-label">Nuevos esta semana</div>
                <div class="stat-value" style="color: #10b981;">+{{ $stats['new_assets'] }}</div>
            </div>
            <div class="stat-card" style="border-left-color: #f59e0b;">
                <div class="stat-label">Stock Bajo</div>
                <div class="stat-value" style="color: #f59e0b;">{{ $stats['low_stock_count'] }}</div>
            </div>
            <div class="stat-card" style="border-left-color: #ef4444;">
                <div class="stat-label">Mantenimientos</div>
                <div class="stat-value" style="color: #ef4444;">{{ $stats['maintenances_count'] }}</div>
            </div>
        </div>

        <!-- Recent Assets -->
        @if($recentAssets->count() > 0)
        <h2 class="section-title">📦 Activos Agregados Esta Semana</h2>
        <ul class="asset-list">
            @foreach($recentAssets as $asset)
            <li class="asset-item">
                <div>
                    <div class="asset-name">{{ $asset->name }}</div>
                    <div class="asset-category">{{ $asset->subcategory->category->name }} / {{ $asset->subcategory->name }}</div>
                </div>
                <span class="badge badge-success">Nuevo</span>
            </li>
            @endforeach
        </ul>
        @endif

        <!-- Low Stock Assets -->
        @if($lowStockAssets->count() > 0)
        <h2 class="section-title">⚠️ Activos con Stock Bajo</h2>
        <ul class="asset-list">
            @foreach($lowStockAssets as $asset)
            <li class="asset-item">
                <div>
                    <div class="asset-name">{{ $asset->name }}</div>
                    <div class="asset-category">Stock: {{ $asset->quantity }} / Mínimo: {{ $asset->minimum_quantity }}</div>
                </div>
                <span class="badge badge-danger">Crítico</span>
            </li>
            @endforeach
        </ul>
        @else
        <h2 class="section-title">⚠️ Activos con Stock Bajo</h2>
        <div class="empty-state">
            <p>✅ ¡Excelente! No hay activos con stock bajo esta semana.</p>
        </div>
        @endif

        <!-- Upcoming Maintenances -->
        @if($upcomingMaintenances->count() > 0)
        <h2 class="section-title">🔧 Mantenimientos Próximos</h2>
        <ul class="asset-list">
            @foreach($upcomingMaintenances as $maintenance)
            <li class="asset-item">
                <div>
                    <div class="asset-name">{{ $maintenance->asset->name }}</div>
                    <div class="asset-category">Programado: {{ \Carbon\Carbon::parse($maintenance->date)->format('d/m/Y') }}</div>
                </div>
                <span class="badge badge-warning">Próximo</span>
            </li>
            @endforeach
        </ul>
        @endif

        <div style="text-align: center;">
            <a href="{{ route('dashboard') }}" class="cta-button">
                Ver Dashboard Completo
            </a>
        </div>

        <div style="background-color: #eff6ff; padding: 15px; border-radius: 6px; margin-top: 20px;">
            <p style="margin: 0; font-size: 14px; color: #1e40af;">
                <strong>💡 Consejo:</strong> Mantén tu inventario actualizado para obtener reportes más precisos cada semana.
            </p>
        </div>

        <div class="footer">
            <p>
                Este es un mensaje automático del sistema ION Inventory.<br>
                Puedes desactivar estos correos desde tu configuración de preferencias.
            </p>
            <p style="margin-top: 10px;">
                © {{ date('Y') }} ION Inventory. Todos los derechos reservados.
            </p>
        </div>
    </div>
</body>
</html>
