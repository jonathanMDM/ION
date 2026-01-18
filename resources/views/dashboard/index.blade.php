@extends('layouts.app')

@section('page-title', 'Dashboard')

@section('content')
<div class="mb-8">
    <h2 class="text-3xl font-bold text-gray-800">Dashboard General</h2>
    <p class="text-gray-600">Resumen del estado del inventario</p>
</div>

<!-- KPIs -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Assets -->
    <div class="bg-white rounded-lg shadow p-6 border-l-4 border-gray-500">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-gray-200 text-gray-600 mr-4">
                <i class="fas fa-boxes text-2xl"></i>
            </div>
            <div>
                <p class="text-gray-500 text-sm font-medium">Total Activos</p>
                <p class="text-2xl font-bold text-gray-800">{{ number_format($totalAssets) }}</p>
            </div>
        </div>
    </div>

    <!-- Total Value -->
    <div class="bg-white rounded-lg shadow p-6 border-l-4 border-[#5483B3]/500">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-100 text-green-500 mr-4">
                <i class="fas fa-dollar-sign text-2xl"></i>
            </div>
            <div>
                <p class="text-gray-500 text-sm font-medium">Valor Total</p>
                <p class="text-2xl font-bold text-gray-800">${{ number_format($totalValue, 2) }}</p>
            </div>
        </div>
    </div>

    <!-- Assigned -->
    <div class="bg-white rounded-lg shadow p-6 border-l-4 border-purple-500">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-purple-100 text-purple-500 mr-4">
                <i class="fas fa-user-check text-2xl"></i>
            </div>
            <div>
                <p class="text-gray-500 text-sm font-medium">Asignados</p>
                <p class="text-2xl font-bold text-gray-800">{{ number_format($assignedAssets) }}</p>
            </div>
        </div>
    </div>

    <!-- Maintenance Pending -->
    <div class="bg-white rounded-lg shadow p-6 border-l-4 border-red-500">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-red-100 text-red-500 mr-4">
                <i class="fas fa-tools text-2xl"></i>
            </div>
            <div>
                <p class="text-gray-500 text-sm font-medium">Mantenimiento (7 días)</p>
                <p class="text-2xl font-bold text-gray-800">{{ number_format($maintenancePending) }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row 1 -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Activos por Categoría</h3>
        <div class="relative h-64">
            <canvas id="categoryChart"></canvas>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Estado de Activos</h3>
        <div class="relative h-64">
            <canvas id="statusChart"></canvas>
        </div>
    </div>
</div>

<!-- Charts Row 2 -->
<div class="grid grid-cols-1 mb-8">
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Valor por Ubicación</h3>
        <div class="relative h-64">
            <canvas id="locationChart"></canvas>
        </div>
    </div>
</div>

<!-- Recent Activity Tables -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Recent Assets -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h3 class="text-lg font-bold text-gray-800">Últimos Activos Agregados</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <tbody class="divide-y divide-gray-200">
                    @foreach($recentAssets as $asset)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    @if($asset->image)
                                        <img class="h-10 w-10 rounded-full object-cover" src="{{ asset('storage/'.$asset->image) }}" alt="">
                                    @else
                                        <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-500">
                                            <i class="fas fa-cube"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $asset->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $asset->custom_id }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-[#C1E8FF] text-[#052659]">
                                {{ $asset->created_at->diffForHumans() }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-6 py-3 bg-gray-50 text-right">
            <a href="{{ route('assets.index') }}" class="text-sm font-medium text-gray-800 hover:text-gray-600">Ver todos &rarr;</a>
        </div>
    </div>

    <!-- Recent Maintenance -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h3 class="text-lg font-bold text-gray-800">Mantenimientos Recientes</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <tbody class="divide-y divide-gray-200">
                    @foreach($recentMaintenances as $maintenance)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $maintenance->asset->name }}</div>
                            <div class="text-sm text-gray-500">{{ Str::limit($maintenance->description, 30) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="text-gray-900">${{ number_format($maintenance->cost, 2) }}</div>
                            <div class="text-gray-500">{{ $maintenance->date->format('d/m/Y') }}</div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-6 py-3 bg-gray-50 text-right">
            <a href="{{ route('maintenances.index') }}" class="text-sm font-medium text-gray-800 hover:text-gray-600">Ver todos &rarr;</a>
        </div>
    </div>
</div>

<!-- Chart.js Integration -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Category Chart
        const categoryData = {!! json_encode($assetsByCategory->pluck('assets_count')) !!};
        const categoryLabels = {!! json_encode($assetsByCategory->pluck('name')) !!};
        
        const ctxCategory = document.getElementById('categoryChart');
        if (ctxCategory) {
            const ctx = ctxCategory.getContext('2d');
            
            if (categoryData.length > 0 && categoryData.some(v => v > 0)) {
                new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: categoryLabels,
                        datasets: [{
                            data: categoryData,
                            backgroundColor: [
                                '#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6', '#EC4899', '#6366F1'
                            ],
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { position: 'right' }
                        }
                    }
                });
            } else {
                ctx.font = '14px sans-serif';
                ctx.fillStyle = '#9CA3AF';
                ctx.textAlign = 'center';
                ctx.fillText('No hay datos disponibles', ctxCategory.width / 2, ctxCategory.height / 2);
            }
        }

        // Status Chart
        const statusData = {!! json_encode($assetsByStatus->pluck('total')) !!};
        const statusLabels = {!! json_encode($assetsByStatus->pluck('status')->map(fn($s) => ucfirst($s))) !!};
        
        const ctxStatus = document.getElementById('statusChart');
        if (ctxStatus) {
            const ctx = ctxStatus.getContext('2d');
            
            if (statusData.length > 0 && statusData.some(v => v > 0)) {
                new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: statusLabels,
                        datasets: [{
                            data: statusData,
                            backgroundColor: [
                                '#10B981', // Active - Green
                                '#F59E0B', // Maintenance - Yellow
                                '#EF4444'  // Decommissioned - Red
                            ],
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { position: 'right' }
                        }
                    }
                });
            } else {
                ctx.font = '14px sans-serif';
                ctx.fillStyle = '#9CA3AF';
                ctx.textAlign = 'center';
                ctx.fillText('No hay datos disponibles', ctxStatus.width / 2, ctxStatus.height / 2);
            }
        }

        // Location Chart
        const locationData = {!! json_encode($valueByLocation->pluck('assets_sum_value')) !!};
        const locationLabels = {!! json_encode($valueByLocation->pluck('name')) !!};
        
        const ctxLocation = document.getElementById('locationChart');
        if (ctxLocation) {
            const ctx = ctxLocation.getContext('2d');
            
            if (locationData.length > 0 && locationData.some(v => v > 0)) {
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: locationLabels,
                        datasets: [{
                            label: 'Valor Total ($)',
                            data: locationData,
                            backgroundColor: '#3B82F6',
                            borderRadius: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: { beginAtZero: true }
                        }
                    }
                });
            } else {
                ctx.font = '14px sans-serif';
                ctx.fillStyle = '#9CA3AF';
                ctx.textAlign = 'center';
                ctx.fillText('No hay datos disponibles', ctxLocation.width / 2, ctxLocation.height / 2);
            }
        }
    });
</script>
@endsection
