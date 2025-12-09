@extends('layouts.app')

@section('page-title', 'Notificaciones')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <h1 class="text-2xl font-bold text-gray-800">Tus Notificaciones</h1>
        
        <div class="flex gap-3 w-full md:w-auto">
            @if(Auth::user()->notifications()->unread()->count() > 0)
            <form action="{{ route('notifications.read-all') }}" method="POST" class="w-full md:w-auto">
                @csrf
                <button type="submit" class="w-full md:w-auto bg-gray-800 hover:bg-gray-900 text-white font-bold py-2 px-4 rounded shadow transition duration-200 flex justify-center items-center">
                    <i class="fas fa-check-double mr-2"></i> Marcar todas como leídas
                </button>
            </form>
            @endif
        </div>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="border-b border-gray-200 bg-gray-50 px-4 py-3 flex gap-4">
            <a href="{{ route('notifications.index') }}" class="px-3 py-1 rounded-full text-sm font-medium {{ !request('filter') ? 'bg-gray-800 text-white' : 'text-gray-600 hover:bg-gray-200' }}">
                Todas
            </a>
            <a href="{{ route('notifications.index', ['filter' => 'unread']) }}" class="px-3 py-1 rounded-full text-sm font-medium {{ request('filter') === 'unread' ? 'bg-gray-800 text-white' : 'text-gray-600 hover:bg-gray-200' }}">
                No leídas
            </a>
        </div>

        @if($notifications->count() > 0)
            <div class="divide-y divide-gray-200">
                @foreach($notifications as $notification)
                    <div class="p-4 hover:bg-gray-50 transition duration-150 ease-in-out {{ $notification->read_at ? 'opacity-75' : 'bg-blue-50' }}">
                        <div class="flex items-start justify-between">
                            <div class="flex items-start flex-1 cursor-pointer" 
                                 onclick="document.getElementById('read-form-{{ $notification->id }}').submit();">
                                <div class="flex-shrink-0 pt-1">
                                    <i class="fas fa-{{ $notification->type === 'support_resolved' ? 'check-circle text-green-500' : ($notification->type === 'support_admin_note' ? 'comment text-blue-500' : 'info-circle text-gray-500') }} text-xl"></i>
                                </div>
                                <div class="ml-4 flex-1">
                                    <div class="flex justify-between items-center">
                                        <p class="text-sm font-medium text-gray-900">
                                            {{ $notification->title }}
                                            @if(!$notification->read_at)
                                                <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                    Nueva
                                                </span>
                                            @endif
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            {{ $notification->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                    <p class="mt-1 text-sm text-gray-600">{{ $notification->message }}</p>
                                </div>
                            </div>
                            
                            <div class="ml-4 flex items-center gap-2">
                                @if(!$notification->read_at)
                                <form id="read-form-{{ $notification->id }}" action="{{ route('notifications.read', $notification) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-gray-400 hover:text-blue-600" title="Marcar como leída">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                                @else
                                    <!-- Hidden form for click functionality on the row -->
                                    <form id="read-form-{{ $notification->id }}" action="{{ route('notifications.read', $notification) }}" method="POST" class="hidden">
                                        @csrf
                                    </form>
                                @endif
                                
                                <form action="{{ route('notifications.destroy', $notification) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar esta notificación?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-gray-400 hover:text-red-600" title="Eliminar">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="px-4 py-3 border-t border-gray-200">
                {{ $notifications->appends(request()->query())->links() }}
            </div>
        @else
            <div class="p-12 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                    <i class="fas fa-bell-slash text-gray-400 text-2xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900">No tienes notificaciones</h3>
                <p class="mt-1 text-gray-500">Te avisaremos cuando haya actualizaciones importantes.</p>
            </div>
        @endif
    </div>
</div>
@endsection
