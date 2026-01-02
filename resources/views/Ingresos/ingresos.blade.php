@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded shadow-sm flex justify-between items-center" role="alert">
                <p>{{ session('success') }}</p>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded shadow-sm flex justify-between items-center" role="alert">
                <ul class="list-disc pl-5 mb-0">
                    @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">LISTADO DE INGRESOS</h2>
            <button type="button" 
                class="bg-[#E31837] hover:bg-[#c41430] text-white px-4 py-2 rounded-lg shadow-md transition-all flex items-center gap-2"
                data-bs-toggle="modal" data-bs-target="#modalIngreso" onclick="limpiarModalIngreso()">
                <i class="fas fa-plus"></i>
                <span>Nuevo Ingreso</span>
            </button>
        </div>

        <div class="bg-white rounded-xl shadow-sm overflow-hidden p-6 border border-gray-100">
            <div class="table-responsive">
                <table id="mitabla" class="table table-hover w-full whitespace-nowrap">
                    <thead class="bg-gray-50 text-gray-700">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Cédula</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Fecha de Ingreso</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Detalles</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($ingresos as $ingreso)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3 text-sm text-gray-700">{{ $ingreso->cedula }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700">{{ $ingreso->fecha }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700">{{ $ingreso->detalles }}</td>
                            <td class="px-4 py-3 text-sm space-x-2">
                                <form action="{{ route('ingresos.destroy', $ingreso->id) }}" method="POST" style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button class="text-red-600 hover:text-red-900 transition-colors" type="submit" onclick="return confirm('¿Estás seguro de eliminar este ingreso?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                <button type="button" class="text-yellow-600 hover:text-yellow-900 transition-colors"
                                    data-bs-toggle="modal" data-bs-target="#modalIngreso"
                                    onclick="llenarModalEditarIngreso(
                                        '{{ $ingreso->id }}',
                                        '{{ $ingreso->cedula }}',
                                        '{{ $ingreso->fecha}}',
                                        '{{ $ingreso->detalles }}'
                                    )">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modalIngreso" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content rounded-xl shadow-lg border-0">
                <form id="formIngreso" method="POST" action="{{ route('ingresos.store') }}">
                    @csrf
                    <div class="modal-header bg-[#E31837] text-white rounded-t-xl">
                        <h5 class="modal-title font-bold" id="tituloModalIngreso">Registrar Ingreso</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body p-6">
                        <div class="mb-3">
                            <label for="cedula" class="form-label font-medium text-gray-700">Cédula *</label>
                            <input type="text" class="form-control rounded-lg border-gray-300 focus:border-[#E31837] focus:ring focus:ring-[#E31837]/20" id="cedula" name="cedula" required>
                        </div>
                        <div class="mb-3">
                            <label for="fecha" class="form-label font-medium text-gray-700">Fecha de Ingreso *</label>
                            <input type="date" class="form-control rounded-lg border-gray-300 focus:border-[#E31837] focus:ring focus:ring-[#E31837]/20" id="fecha" name="fecha" required>
                        </div>
                        <div class="mb-3">
                            <label for="detalles" class="form-label font-medium text-gray-700">Detalles</label>
                            <textarea class="form-control rounded-lg border-gray-300 focus:border-[#E31837] focus:ring focus:ring-[#E31837]/20" id="detalles" name="detalles" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-t border-gray-100 bg-gray-50 rounded-b-xl">
                        <button type="button" class="btn btn-secondary rounded-lg px-4 py-2" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn text-white rounded-lg px-4 py-2" id="btnGuardarIngreso" style="background-color: #E31837;">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    new DataTable('#mitabla', {
        language: { url: 'https://cdn.datatables.net/plug-ins/2.2.1/i18n/es-ES.json' },
        columnDefs: [
            { className: "dt-center", targets: "_all" }
        ],
        responsive: true
    });

    function llenarModalEditarIngreso(id, cedula, fecha, detalles) {
        const form = document.getElementById('formIngreso');
        form.action = '/ingresos/' + id;
        form.querySelector('input[name="_method"]')?.remove();
        let input = document.createElement('input');
        input.type = 'hidden'; input.name = '_method'; input.value = 'PUT';
        form.appendChild(input);

        document.getElementById('cedula').value = cedula;
        document.getElementById('fecha').value = fecha;
        document.getElementById('detalles').value = detalles;

        document.getElementById('tituloModalIngreso').textContent = 'Editar Ingreso';
        document.getElementById('btnGuardarIngreso').textContent = 'Actualizar';
    }

    function limpiarModalIngreso() {
        const form = document.getElementById('formIngreso');
        form.reset();
        form.action = "{{ route('ingresos.store') }}";
        form.querySelector('input[name="_method"]')?.remove();
        document.getElementById('tituloModalIngreso').textContent = 'Registrar Ingreso';
        document.getElementById('btnGuardarIngreso').textContent = 'Guardar';
    }
</script>
@endpush