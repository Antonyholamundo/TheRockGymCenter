@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <div class="flex items-center gap-3 mb-6">
        <i class="fas fa-file-pdf text-[#E31837] text-3xl"></i>
        <h2 class="text-2xl font-bold text-gray-800">Generador de Reportes</h2>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Reporte de Ventas -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow">
            <div class="bg-green-600 text-white px-6 py-4">
                <h5 class="font-bold flex items-center gap-2">
                    <i class="fas fa-shopping-cart"></i> Reporte de Ventas
                </h5>
            </div>
            <div class="p-6">
                <form action="{{ route('reportes.ventas') }}" method="GET" class="space-y-4">
                    <div>
                        <label for="fecha_inicio" class="block text-sm font-medium text-gray-700 mb-1">Fecha Inicio</label>
                        <input type="date" class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring focus:ring-green-200" id="fecha_inicio" name="fecha_inicio">
                    </div>
                    <div>
                        <label for="fecha_fin" class="block text-sm font-medium text-gray-700 mb-1">Fecha Fin</label>
                        <input type="date" class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring focus:ring-green-200" id="fecha_fin" name="fecha_fin">
                    </div>
                    <div>
                        <label for="estado" class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                        <select class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring focus:ring-green-200" id="estado" name="estado">
                            <option value="">Todos</option>
                            <option value="Pagado">Pagado</option>
                            <option value="Pendiente">Pendiente</option>
                        </select>
                    </div>
                    
                    <div class="flex gap-2 pt-2">
                        <button type="submit" class="flex-1 bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-lg transition-colors flex items-center justify-center gap-2">
                            <i class="fas fa-download"></i> PDF
                        </button>
                        <button type="button" onclick="submitCsvVentas()" class="flex-1 border border-green-600 text-green-600 hover:bg-green-50 py-2 px-4 rounded-lg transition-colors flex items-center justify-center gap-2">
                            <i class="fas fa-file-csv"></i> CSV
                        </button>
                    </div>
                </form>
                <form id="form-csv-ventas" action="{{ route('reportes.ventas.csv') }}" method="GET" class="hidden">
                    <input type="hidden" name="fecha_inicio" id="csv_fecha_inicio">
                    <input type="hidden" name="fecha_fin" id="csv_fecha_fin">
                    <input type="hidden" name="estado" id="csv_estado">
                </form>
            </div>
        </div>

        <!-- Reporte de Ingresos -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow">
            <div class="bg-blue-500 text-white px-6 py-4">
                <h5 class="font-bold flex items-center gap-2">
                    <i class="fas fa-money-bill"></i> Reporte de Ingresos
                </h5>
            </div>
            <div class="p-6">
                <form action="{{ route('reportes.ingresos') }}" method="GET" class="space-y-4">
                    <div>
                        <label for="fecha_inicio_ingresos" class="block text-sm font-medium text-gray-700 mb-1">Fecha Inicio</label>
                        <input type="date" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200" id="fecha_inicio_ingresos" name="fecha_inicio">
                    </div>
                    <div>
                        <label for="fecha_fin_ingresos" class="block text-sm font-medium text-gray-700 mb-1">Fecha Fin</label>
                        <input type="date" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200" id="fecha_fin_ingresos" name="fecha_fin">
                    </div>
                    
                    <div class="flex gap-2 pt-2">
                        <button type="submit" class="flex-1 bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-lg transition-colors flex items-center justify-center gap-2">
                            <i class="fas fa-download"></i> PDF
                        </button>
                        <button type="button" onclick="submitCsvIngresos()" class="flex-1 border border-blue-500 text-blue-500 hover:bg-blue-50 py-2 px-4 rounded-lg transition-colors flex items-center justify-center gap-2">
                            <i class="fas fa-file-csv"></i> CSV
                        </button>
                    </div>
                </form>
                 <form id="form-csv-ingresos" action="{{ route('reportes.ingresos.csv') }}" method="GET" class="hidden">
                    <input type="hidden" name="fecha_inicio" id="csv_fecha_inicio_ingresos">
                    <input type="hidden" name="fecha_fin" id="csv_fecha_fin_ingresos">
                 </form>
            </div>
        </div>

        <!-- Reporte de Inventario -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow">
            <div class="bg-yellow-500 text-white px-6 py-4">
                <h5 class="font-bold flex items-center gap-2">
                    <i class="fas fa-boxes"></i> Reporte de Inventario
                </h5>
            </div>
            <div class="p-6">
                <form action="{{ route('reportes.inventario') }}" method="GET" class="space-y-4">
                    <div>
                        <label for="categoria" class="block text-sm font-medium text-gray-700 mb-1">Categoría</label>
                        <select class="w-full rounded-lg border-gray-300 focus:border-yellow-500 focus:ring focus:ring-yellow-200" id="categoria" name="categoria">
                            <option value="">Todas las categorías</option>
                            @foreach(\App\Models\Categorias::all() as $categoria)
                                <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="estado_producto" class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                        <select class="w-full rounded-lg border-gray-300 focus:border-yellow-500 focus:ring focus:ring-yellow-200" id="estado_producto" name="estado">
                            <option value="">Todos</option>
                            <option value="Activo">Activo</option>
                            <option value="Inactivo">Inactivo</option>
                        </select>
                    </div>

                    <div class="flex gap-2 pt-2">
                        <button type="submit" class="flex-1 bg-yellow-500 hover:bg-yellow-600 text-white py-2 px-4 rounded-lg transition-colors flex items-center justify-center gap-2">
                            <i class="fas fa-download"></i> PDF
                        </button>
                        <button type="button" onclick="submitCsvInventario()" class="flex-1 border border-yellow-500 text-yellow-600 hover:bg-yellow-50 py-2 px-4 rounded-lg transition-colors flex items-center justify-center gap-2">
                            <i class="fas fa-file-csv"></i> CSV
                        </button>
                    </div>
                </form>
                <form id="form-csv-inventario" action="{{ route('reportes.inventario.csv') }}" method="GET" class="hidden">
                    <input type="hidden" name="categoria" id="csv_categoria">
                    <input type="hidden" name="estado" id="csv_estado_producto">
                </form>
            </div>
        </div>

        <!-- Reporte de Usuarios -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow">
            <div class="bg-gray-600 text-white px-6 py-4">
                <h5 class="font-bold flex items-center gap-2">
                    <i class="fas fa-users"></i> Reporte de Usuarios
                </h5>
            </div>
            <div class="p-6">
                <form action="{{ route('reportes.usuarios') }}" method="GET" class="space-y-4">
                    <div>
                        <label for="estado_usuario" class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                        <select class="w-full rounded-lg border-gray-300 focus:border-gray-500 focus:ring focus:ring-gray-200" id="estado_usuario" name="estado">
                            <option value="">Todos</option>
                            <option value="Activo">Activo</option>
                            <option value="Inactivo">Inactivo</option>
                        </select>
                    </div>

                    <div class="flex gap-2 pt-2">
                        <button type="submit" class="flex-1 bg-gray-600 hover:bg-gray-700 text-white py-2 px-4 rounded-lg transition-colors flex items-center justify-center gap-2">
                            <i class="fas fa-download"></i> PDF
                        </button>
                        <button type="button" onclick="submitCsvUsuarios()" class="flex-1 border border-gray-600 text-gray-600 hover:bg-gray-50 py-2 px-4 rounded-lg transition-colors flex items-center justify-center gap-2">
                            <i class="fas fa-file-csv"></i> CSV
                        </button>
                    </div>
                </form>
                <form id="form-csv-usuarios" action="{{ route('reportes.usuarios.csv') }}" method="GET" class="hidden">
                     <input type="hidden" name="estado" id="csv_estado_usuario">
                </form>
            </div>
        </div>

        <!-- Reporte de Membresías -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow">
            <div class="bg-[#E31837] text-white px-6 py-4">
                <h5 class="font-bold flex items-center gap-2">
                    <i class="fas fa-dumbbell"></i> Reporte de Membresías
                </h5>
            </div>
            <div class="p-6">
                <form action="{{ route('reportes.membresias') }}" method="GET" class="space-y-4">
                    <div>
                        <label for="estado_membresia" class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                        <select class="w-full rounded-lg border-gray-300 focus:border-[#E31837] focus:ring focus:ring-[#E31837]/20" id="estado_membresia" name="estado">
                            <option value="">Todas</option>
                            <option value="Activo">Activo</option>
                            <option value="Inactivo">Inactivo</option>
                        </select>
                    </div>

                    <div class="flex gap-2 pt-2">
                        <button type="submit" class="flex-1 bg-[#E31837] hover:bg-[#c41430] text-white py-2 px-4 rounded-lg transition-colors flex items-center justify-center gap-2">
                            <i class="fas fa-download"></i> PDF
                        </button>
                        <button type="button" onclick="submitCsvMembresias()" class="flex-1 border border-[#E31837] text-[#E31837] hover:bg-[#E31837]/10 py-2 px-4 rounded-lg transition-colors flex items-center justify-center gap-2">
                            <i class="fas fa-file-csv"></i> CSV
                        </button>
                    </div>
                </form>
                <form id="form-csv-membresias" action="{{ route('reportes.membresias.csv') }}" method="GET" class="hidden">
                    <input type="hidden" name="estado" id="csv_estado_membresia">
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Set current date as default for date inputs
        const today = new Date().toISOString().split('T')[0];
        document.querySelectorAll('input[type="date"]').forEach(input => {
            if (!input.value) {
                input.value = today;
            }
        });
    });

    // Functions to handle CSV submission with current filter values
    function submitCsvVentas() {
        document.getElementById('csv_fecha_inicio').value = document.getElementById('fecha_inicio').value;
        document.getElementById('csv_fecha_fin').value = document.getElementById('fecha_fin').value;
        document.getElementById('csv_estado').value = document.getElementById('estado').value;
        document.getElementById('form-csv-ventas').submit();
    }

    function submitCsvIngresos() {
        document.getElementById('csv_fecha_inicio_ingresos').value = document.getElementById('fecha_inicio_ingresos').value;
        document.getElementById('csv_fecha_fin_ingresos').value = document.getElementById('fecha_fin_ingresos').value;
        document.getElementById('form-csv-ingresos').submit();
    }

    function submitCsvInventario() {
        document.getElementById('csv_categoria').value = document.getElementById('categoria').value;
        document.getElementById('csv_estado_producto').value = document.getElementById('estado_producto').value;
        document.getElementById('form-csv-inventario').submit();
    }

    function submitCsvUsuarios() {
        document.getElementById('csv_estado_usuario').value = document.getElementById('estado_usuario').value;
        document.getElementById('form-csv-usuarios').submit();
    }

    function submitCsvMembresias() {
        document.getElementById('csv_estado_membresia').value = document.getElementById('estado_membresia').value;
        document.getElementById('form-csv-membresias').submit();
    }
</script>
@endpush