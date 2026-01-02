@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Welcome Header (Dashboard) -->
    <div class="bg-gradient-to-r from-gray-900 to-gray-800 rounded-2xl p-8 mb-8 text-white shadow-xl relative overflow-hidden">
        <div class="absolute right-0 top-0 h-full w-1/3 bg-[#E31837] transform skew-x-12 opacity-10 translate-x-10"></div>
        <div class="relative z-10">
            <h1 class="text-4xl font-bold mb-2">Bienvenido, {{ Auth::user()->name ?? 'Usuario' }} 👋</h1>
            <p class="text-gray-300 text-lg">Panel de Control General</p>
        </div>
    </div>

    <!-- Quick Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Card 1 -->
        <div class="bg-white rounded-xl shadow-sm hover:shadow-lg transition-all p-6 border-l-4 border-blue-500">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm text-gray-500 font-semibold uppercase">Usuarios Activos</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ number_format($usuariosActivos) }}</h3>
                </div>
                <div class="w-12 h-12 rounded-lg bg-blue-50 flex items-center justify-center text-blue-500">
                    <i class="fas fa-users text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Card 2 -->
        <div class="bg-white rounded-xl shadow-sm hover:shadow-lg transition-all p-6 border-l-4 border-green-500">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm text-gray-500 font-semibold uppercase">Ingresos Hoy</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-1">${{ number_format($ingresosHoy, 2) }}</h3>
                </div>
                <div class="w-12 h-12 rounded-lg bg-green-50 flex items-center justify-center text-green-500">
                    <i class="fas fa-dollar-sign text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Card 3 -->
        <div class="bg-white rounded-xl shadow-sm hover:shadow-lg transition-all p-6 border-l-4 border-purple-500">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm text-gray-500 font-semibold uppercase">Membresías</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ number_format($membresiasActivas) }}</h3>
                </div>
                <div class="w-12 h-12 rounded-lg bg-purple-50 flex items-center justify-center text-purple-500">
                    <i class="fas fa-id-card text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Card 4 -->
        <div class="bg-white rounded-xl shadow-sm hover:shadow-lg transition-all p-6 border-l-4 border-[#E31837]">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm text-gray-500 font-semibold uppercase">Productos Bajos</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ number_format($productosBajos) }}</h3>
                </div>
                <div class="w-12 h-12 rounded-lg bg-red-50 flex items-center justify-center text-[#E31837]">
                    <i class="fas fa-exclamation-triangle text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Background Image / Brand Section -->
    <div class="flex justify-center items-center opacity-20 mt-10">
        <img src="{{ asset('img/sin fondo 1.png') }}" alt="Logo Fondo" class="w-64 grayscale">
    </div>
</div>
@endsection