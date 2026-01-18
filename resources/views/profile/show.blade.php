@extends('layouts.app')

@section('page-title', 'Mi Perfil')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Profile Card -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="h-32 bg-gray-800"></div>
                <div class="px-6 -mt-16 text-center">
                    <div class="h-32 w-32 bg-white rounded-full border-4 border-white mx-auto flex items-center justify-center shadow-lg">
                        <i class="fas fa-user-circle text-6xl text-gray-400"></i>
                    </div>
                    <div class="mt-4">
                        <h2 class="text-xl font-bold text-gray-800">{{ $user->name }}</h2>
                        <p class="text-gray-600">{{ $user->email }}</p>
                        <div class="mt-4 mb-6">
                            @if($user->isAdmin())
                                <span class="bg-gray-800 text-white px-3 py-1 rounded-full text-sm font-semibold">Administrador</span>
                            @elseif($user->isEditor())
                                <span class="bg-[#5483B3] text-white px-3 py-1 rounded-full text-sm font-semibold">Editor</span>
                            @else
                                <span class="bg-gray-500 text-white px-3 py-1 rounded-full text-sm font-semibold">Visor</span>
                            @endif
                        </div>
                    </div>
                    <div class="border-t border-gray-100 py-4 text-left">
                        <div class="flex items-center justify-between py-2">
                            <span class="text-gray-600 font-medium">Empresa</span>
                            <span class="text-gray-800 font-semibold">{{ $user->company ? $user->company->name : 'N/A' }}</span>
                        </div>
                        <div class="flex items-center justify-between py-2">
                            <span class="text-gray-600 font-medium">Miembro desde</span>
                            <span class="text-gray-800">{{ $user->created_at->format('M Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Profile Form -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-6 pb-2 border-b border-gray-200">Editar Información</h3>
                
                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nombre Completo</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-transparent outline-none transition-all">
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Correo Electrónico</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-transparent outline-none transition-all bg-gray-50" readonly title="Contacta al admin para cambiar tu correo">
                            <p class="text-xs text-gray-500 mt-1">El correo no se puede cambiar directamente.</p>
                        </div>
                    </div>

                    <div class="border-t border-gray-100 pt-6 mt-6">
                        <h4 class="text-lg font-semibold text-gray-800 mb-4">Cambiar Contraseña</h4>
                        <p class="text-sm text-gray-500 mb-4">Deja esto en blanco si no quieres cambiar tu contraseña.</p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Nueva Contraseña</label>
                                <input type="password" name="password" id="password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-transparent outline-none transition-all">
                                @error('password')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirmar Contraseña</label>
                                <input type="password" name="password_confirmation" id="password_confirmation" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-transparent outline-none transition-all">
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end pt-4">
                        <button type="submit" class="bg-gray-800 hover:bg-black text-white font-bold py-2 px-6 rounded-lg transition-colors shadow-lg shadow-gray-300 transform hover:-translate-y-0.5">
                            Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
