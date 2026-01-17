<!-- Financial Information Section -->
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold text-gray-800">
            <i class="fas fa-dollar-sign text-indigo-600 mr-2"></i>Información Financiera
        </h3>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Valor de Compra -->
        <div class="bg-gray-50 rounded-lg p-4">
            <p class="text-sm text-gray-500 mb-1">Valor de Compra</p>
            <p class="text-2xl font-bold text-gray-900">${{ number_format($asset->purchase_price ?? 0, 0) }}</p>
            <p class="text-xs text-gray-500 mt-1">{{ $asset->purchase_date ? $asset->purchase_date->format('d/m/Y') : 'N/A' }}</p>
        </div>

        <!-- Valor en Libros -->
        <div class="bg-indigo-50 rounded-lg p-4">
            <p class="text-sm text-indigo-600 mb-1">Valor en Libros</p>
            <p class="text-2xl font-bold text-indigo-900">${{ number_format($asset->book_value ?? 0, 0) }}</p>
            @if($asset->depreciation_method !== 'none' && $asset->purchase_price > 0)
                <p class="text-xs text-indigo-600 mt-1">
                    {{ number_format($asset->depreciation_percentage, 1) }}% depreciado
                </p>
            @endif
        </div>

        <!-- Costos Totales -->
        <div class="bg-yellow-50 rounded-lg p-4">
            <p class="text-sm text-yellow-600 mb-1">Costos Acumulados</p>
            <p class="text-2xl font-bold text-yellow-900">${{ number_format($asset->total_costs ?? 0, 0) }}</p>
            <p class="text-xs text-yellow-600 mt-1">{{ $asset->costs->count() }} registros</p>
        </div>
    </div>

    <!-- Depreciación Details -->
    @if($asset->depreciation_method !== 'none')
    <div class="mt-6 border-t pt-6">
        <h4 class="text-md font-semibold text-gray-800 mb-4">Depreciación</h4>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <p class="text-xs text-gray-500">Método</p>
                <p class="text-sm font-semibold text-gray-900">
                    @switch($asset->depreciation_method)
                        @case('straight_line')
                            Línea Recta
                            @break
                        @case('declining_balance')
                            Saldo Decreciente
                            @break
                        @case('units_of_production')
                            Unidades de Producción
                            @break
                        @default
                            N/A
                    @endswitch
                </p>
            </div>
            <div>
                <p class="text-xs text-gray-500">Vida Útil</p>
                <p class="text-sm font-semibold text-gray-900">{{ $asset->useful_life_years ?? 'N/A' }} años</p>
            </div>
            <div>
                <p class="text-xs text-gray-500">Valor Salvamento</p>
                <p class="text-sm font-semibold text-gray-900">${{ number_format($asset->salvage_value ?? 0, 0) }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500">Depreciación Anual</p>
                <p class="text-sm font-semibold text-gray-900">${{ number_format($asset->calculateAnnualDepreciation(), 0) }}</p>
            </div>
        </div>

        <!-- Depreciation Progress Bar -->
        @if($asset->purchase_price > 0)
        <div class="mt-4">
            <div class="flex justify-between text-sm mb-2">
                <span class="text-gray-600">Progreso de Depreciación</span>
                <span class="font-semibold text-gray-900">{{ number_format($asset->depreciation_percentage, 1) }}%</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-3">
                <div class="bg-gradient-to-r from-green-500 to-red-500 h-3 rounded-full transition-all duration-500" 
                     style="width: {{ min($asset->depreciation_percentage, 100) }}%"></div>
            </div>
            @if($asset->isFullyDepreciated())
                <p class="text-xs text-red-600 mt-2">
                    <i class="fas fa-exclamation-circle mr-1"></i>Activo totalmente depreciado
                </p>
            @endif
        </div>
        @endif
    </div>
    @endif

    <!-- Centro de Costo -->
    @if($asset->costCenter)
    <div class="mt-6 border-t pt-6">
        <h4 class="text-md font-semibold text-gray-800 mb-3">Centro de Costo</h4>
        <div class="flex items-center justify-between bg-gray-50 rounded-lg p-4">
            <div>
                <p class="text-sm font-semibold text-gray-900">{{ $asset->costCenter->name }}</p>
                <p class="text-xs text-gray-500">Código: {{ $asset->costCenter->code }}</p>
            </div>
            <a href="{{ route('cost-centers.show', $asset->costCenter) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                Ver detalles <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
    </div>
    @endif
</div>

<!-- Costs Section -->
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold text-gray-800">
            <i class="fas fa-receipt text-indigo-600 mr-2"></i>Costos Asociados
        </h3>
        <a href="{{ route('assets.costs.create', $asset) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded text-sm font-bold">
            <i class="fas fa-plus mr-2"></i>Registrar Costo
        </a>
    </div>

    @if($asset->costs->isEmpty())
        <div class="text-center py-8">
            <i class="fas fa-receipt text-4xl text-gray-300 mb-3"></i>
            <p class="text-gray-500">No hay costos registrados para este activo</p>
            <a href="{{ route('assets.costs.create', $asset) }}" class="inline-block mt-4 text-indigo-600 hover:text-indigo-900 font-medium">
                Registrar primer costo <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipo</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Descripción</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Proveedor</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Monto</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($asset->costs->take(5) as $cost)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                            {{ $cost->date->format('d/m/Y') }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                {{ $cost->cost_type === 'maintenance' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $cost->cost_type === 'repair' ? 'bg-red-100 text-red-800' : '' }}
                                {{ $cost->cost_type === 'insurance' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $cost->cost_type === 'spare_parts' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $cost->cost_type === 'upgrade' ? 'bg-purple-100 text-purple-800' : '' }}
                                {{ $cost->cost_type === 'other' ? 'bg-gray-100 text-gray-800' : '' }}">
                                {{ $cost->formatted_cost_type }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-900 max-w-xs truncate">
                            {{ $cost->description }}
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-500">
                            {{ $cost->vendor ?? 'N/A' }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm font-semibold text-gray-900">
                            ${{ number_format($cost->amount, 0) }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-center text-sm">
                            @if($cost->document_path)
                                <a href="{{ route('assets.costs.download', [$asset, $cost]) }}" class="text-blue-600 hover:text-blue-900 mr-2" title="Descargar documento">
                                    <i class="fas fa-download"></i>
                                </a>
                            @endif
                            <form action="{{ route('assets.costs.destroy', [$asset, $cost]) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar este costo?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($asset->costs->count() > 5)
            <div class="mt-4 text-center">
                <p class="text-sm text-gray-500">Mostrando 5 de {{ $asset->costs->count() }} costos</p>
            </div>
        @endif

        <div class="mt-4 bg-gray-50 rounded-lg p-4 flex justify-between items-center">
            <span class="text-sm font-medium text-gray-700">Total de Costos:</span>
            <span class="text-xl font-bold text-gray-900">${{ number_format($asset->total_costs, 0) }}</span>
        </div>
    @endif
</div>
