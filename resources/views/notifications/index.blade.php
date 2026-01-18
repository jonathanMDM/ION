@extends('layouts.app')

@section('page-title', 'Notificaciones')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden transition-colors">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
                <i class="fas fa-bell mr-2"></i>
                Notificaciones
            </h2>
            @if($notifications->total() > 0)
            <form action="{{ route('notifications.mark-all-as-read') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="text-sm text-[#5483B3] hover:text-indigo-800 font-medium">
                    <i class="fas fa-check-double mr-1"></i>
                    Marcar todas como leídas
                </button>
            </form>
            @endif
        </div>

        @if($notifications->total() > 0)
        <div class="divide-y divide-gray-200 dark:divide-gray-700">
            @foreach($notifications as $notification)
            <div class="px-6 py-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors {{ $notification->read_at ? 'bg-white dark:bg-gray-800' : 'bg-blue-50 dark:bg-blue-900/10' }}">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        @if($notification->type === 'low_stock_alert')
                            <div class="w-10 h-10 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center">
                                <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-400"></i>
                            </div>
                        @elseif($notification->type === 'asset_assigned')
                            <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center">
                                <i class="fas fa-box-open text-blue-600 dark:text-blue-400"></i>
                            </div>
                        @elseif($notification->type === 'maintenance_reminder')
                            <div class="w-10 h-10 bg-yellow-100 dark:bg-yellow-900/30 rounded-full flex items-center justify-center">
                                <i class="fas fa-tools text-yellow-600 dark:text-yellow-400"></i>
                            </div>
                        @else
                            <div class="w-10 h-10 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center">
                                <i class="fas fa-bell text-gray-600 dark:text-gray-400"></i>
                            </div>
                        @endif
                    </div>
                    <div class="ml-4 flex-1">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">{{ $notification->title }}</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $notification->message }}</p>
                                <p class="text-xs text-gray-400 dark:text-gray-500 mt-2">
                                    <i class="fas fa-clock mr-1"></i>
                                    {{ $notification->created_at->diffForHumans() }}
                                </p>
                            </div>
                            <div class="flex items-center space-x-2 ml-4">
                                @if(!$notification->read_at)
                                <form action="{{ route('notifications.mark-as-read', $notification->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-xs text-[#5483B3] dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300" title="Marcar como leída">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                                @endif
                                @if(isset($notification->data['action_url']))
                                <a href="{{ $notification->data['action_url'] }}" class="text-xs text-gray-600 hover:text-gray-800" title="Ver detalles">
                                    <i class="fas fa-external-link-alt"></i>
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 transition-colors">
            {{ $notifications->links() }}
        </div>
        @else
        <div class="flex flex-col items-center justify-center py-12 text-gray-500 dark:text-gray-400 transition-colors">
            <i class="fas fa-bell-slash text-6xl mb-4 opacity-50"></i>
            <h3 class="text-lg font-semibold mb-2 text-gray-800 dark:text-white">No tienes notificaciones</h3>
            <p class="text-sm">Cuando recibas notificaciones, aparecerán aquí</p>
        </div>
        @endif
    </div>
</div>
@endsection
