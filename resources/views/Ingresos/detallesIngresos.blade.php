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
            <h2 class="text-2xl font-bold text-gray-800">LISTADO DE DETALLE-INGRESO</h2>
            <button type="button" 
                class="bg-[#E31837] hover:bg-[#c41430] text-white px-4 py-2 rounded-lg shadow-md transition-all flex items-center gap-2"
                data-bs-toggle="modal" data-bs-target="#modalDein" onclick="limpiarModalDein()">
                <i class="fas fa-plus"></i>
                <span>Nuevo Detalle-Ingreso</span>
            </button>
        </div>

        <div class="bg-white rounded-xl shadow-sm overflow-hidden p-6 border border-gray-100">
            <div class="table-responsive">
                <table id="mitabla" class="table table-hover w-full whitespace-nowrap">
                    <thead class="bg-gray-50 text-gray-700">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Ingreso</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Producto</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Cantidad</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($detalles_ingresos as $detalle)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3 text-sm text-gray-700">{{ $detalle->ingreso }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700">{{ $detalle->producto->nombre }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700">{{ $detalle->cantidad }}</td>
                            <td class="px-4 py-3 text-sm space-x-2">
                                <form action="{{ route('detalles_ingresos.destroy', $detalle->id) }}" method="POST" style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button class="text-red-600 hover:text-red-900 transition-colors" type="submit" onclick="return confirm('¿Estás seguro de eliminar este detalle?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                <button type="button" class="text-yellow-600 hover:text-yellow-900 transition-colors"
                                    data-bs-toggle="modal" data-bs-target="#modalDein"
                                    onclick="llenarModalEditarDein(
                                        '{{ $detalle->id }}',
                                        '{{ $detalle->ingreso }}',
                                        '{{ $detalle->producto_id }}',
                                        '{{ $detalle->cantidad }}'
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
    <div class="modal fade" id="modalDein" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content rounded-xl shadow-lg border-0">
                <form id="formDein" method="POST" action="{{ route('detalles_ingresos.store') }}">
                    @csrf
                    <div class="modal-header bg-[#E31837] text-white rounded-t-xl">
                        <h5 class="modal-title font-bold" id="tituloModalDein">Registrar Detalle-Ingreso</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-6">
                        <div class="mb-3">
                            <label for="ingreso" class="form-label font-medium text-gray-700">Ingreso *</label>
                            <input type="text" class="form-control rounded-lg border-gray-300 focus:border-[#E31837] focus:ring focus:ring-[#E31837]/20" id="ingreso" name="ingreso" required>
                        </div>
                        <div class="mb-3">
                            <label for="producto_id" class="form-label font-medium text-gray-700">Producto *</label>
                            <select class="form-select rounded-lg border-gray-300 focus:border-[#E31837] focus:ring focus:ring-[#E31837]/20" name="producto_id" id="producto_id" required>
                                <option value="">Seleccione un producto</option>
                                @foreach($productos as $producto)
                                    <option value="{{ $producto->id }}">{{ $producto->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="cantidad" class="form-label font-medium text-gray-700">Cantidad *</label>
                            <input type="number" class="form-control rounded-lg border-gray-300 focus:border-[#E31837] focus:ring focus:ring-[#E31837]/20" id="cantidad" name="cantidad" required min="1">
                        </div>
                    </div>
                    <div class="modal-footer border-t border-gray-100 bg-gray-50 rounded-b-xl">
                        <button type="button" class="btn btn-secondary rounded-lg px-4 py-2" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn text-white rounded-lg px-4 py-2" id="btnGuardarDein" style="background-color: #E31837;">Guardar</button>
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

    function llenarModalEditarDein(id, ingreso, producto_id, cantidad) {
        const form = document.getElementById('formDein');
        form.action = '/detalles_ingresos/' + id;
        form.querySelector('input[name="_method"]')?.remove();
        let input = document.createElement('input');
        input.type = 'hidden'; input.name = '_method'; input.value = 'PUT';
        form.appendChild(input);

        document.getElementById('ingreso').value = ingreso;
        document.getElementById('producto_id').value = producto_id;
        document.getElementById('cantidad').value = cantidad;

        document.getElementById('tituloModalDein').textContent = 'Editar Detalle-Ingreso';
        document.getElementById('btnGuardarDein').textContent = 'Actualizar';
    }

    function limpiarModalDein() {
        const form = document.getElementById('formDein');
        form.reset();
        form.action = "{{ route('detalles_ingresos.store') }}";
        form.querySelector('input[name="_method"]')?.remove();
        document.getElementById('tituloModalDein').textContent = 'Registrar Detalle-Ingreso';
        document.getElementById('btnGuardarDein').textContent = 'Guardar';
    }
</script>
@endpush