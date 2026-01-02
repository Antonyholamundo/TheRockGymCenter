@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Categorías de Productos</h1>
            <p class="text-gray-500 text-sm">Organiza el inventario por categorías</p>
        </div>
        <button type="button" class="bg-[#E31837] hover:bg-[#c41430] text-white px-4 py-2 rounded-lg shadow-md transition-all flex items-center gap-2"
            onclick="abrirModalNuevoCategoria()">
            <i class="fas fa-plus"></i> <span>Nueva Categoría</span>
        </button>
    </div>

    <!-- Mensajes -->
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded shadow-sm flex justify-between items-center" role="alert">
            <div>
                <p class="font-bold">¡Éxito!</p>
                <p>{{ session('success') }}</p>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded shadow-sm flex justify-between items-center" role="alert">
            <div>
                <p class="font-bold">Error</p>
                <p>{{ session('error') }}</p>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-50 text-red-600 p-4 rounded-lg border border-red-200 mb-4">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Tabla -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6">
            <div class="table-responsive">
                <table id="mitabla" class="table table-hover w-full pt-4 pb-4">
                    <thead class="bg-gray-50 text-gray-700 uppercase text-xs font-bold">
                        <tr>
                            <th class="py-3 px-4">Nombre</th>
                            <th class="py-3 px-4">Descripción</th>
                            <th class="py-3 px-4">Estado</th>
                            <th class="py-3 px-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @foreach ($categorias as $categoria)
                        <tr class="border-b hover:bg-gray-50 transition-colors">
                            <td class="py-3 px-4 font-medium text-gray-900">{{ $categoria->nombre }}</td>
                            <td class="py-3 px-4 text-gray-500">{{ Str::limit($categoria->descripcion, 50) }}</td>
                            <td class="py-3 px-4">
                                @if($categoria->estado == 'Activo')
                                    <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded-full border border-green-200">Activo</span>
                                @else
                                    <span class="bg-red-100 text-red-800 text-xs font-semibold px-2.5 py-0.5 rounded-full border border-red-200">Inactivo</span>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                <div class="flex gap-2">
                                    <button type="button" class="text-yellow-500 hover:text-yellow-600 transition-colors"
                                        onclick="editarCategoria({{ $categoria->id }}, '{{ $categoria->nombre }}', '{{ $categoria->descripcion }}', '{{ $categoria->estado }}')">
                                        <i class="fas fa-edit fa-lg"></i>
                                    </button>
                                    <button class="text-red-500 hover:text-red-700 transition-colors"
                                        onclick="eliminarCategoria({{ $categoria->id }})">
                                        <i class="fas fa-trash fa-lg"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Categoria -->
<div class="modal fade" id="modalCategoria" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-xl border-0 shadow-2xl overflow-hidden">
            <div class="modal-header bg-[#E31837] text-white px-6 py-4 border-0">
                <h5 class="modal-title font-bold text-lg" id="tituloModal">Registrar Categoría</h5>
                <button type="button" class="btn-close btn-close-white opacity-80 hover:opacity-100" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form id="formCategoria" method="POST" action="{{ route('categorias.store') }}">
                @csrf
                <input type="hidden" id="idCategoria" name="id">
                <input type="hidden" id="metodoForm" name="_method" value="POST">
                <div class="modal-body p-6 bg-gray-50">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label for="nombre" class="form-label font-semibold text-gray-700 text-sm">Nombre <span class="text-red-500">*</span></label>
                            <input type="text" class="form-control rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500" id="nombre" name="nombre" required>
                        </div>
                        <div class="col-md-6">
                            <label for="estado" class="form-label font-semibold text-gray-700 text-sm">Estado <span class="text-red-500">*</span></label>
                            <select class="form-select rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500" id="estado" name="estado" required>
                                <option value="">Seleccione...</option>
                                <option value="Activo">Activo</option>
                                <option value="Inactivo">Inactivo</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label for="descripcion" class="form-label font-semibold text-gray-700 text-sm">Descripción <span class="text-red-500">*</span></label>
                            <textarea class="form-control rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500" id="descripcion" name="descripcion" rows="3" required></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-white border-t border-gray-200 px-6 py-4">
                    <button type="button" class="btn btn-secondary text-gray-700 bg-gray-200 border-0 hover:bg-gray-300 font-medium rounded-lg px-4 py-2" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary bg-[#E31837] border-0 hover:bg-[#c41430] font-medium rounded-lg px-4 py-2" id="btnGuardar">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Eliminar -->
<div class="modal fade" id="modalEliminar" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-xl border-0 shadow-2xl overflow-hidden">
            <div class="modal-header bg-red-600 text-white border-0">
                <h5 class="modal-title font-bold">⚠️ Confirmar Eliminación</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-6 text-center">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4 text-red-600">
                    <i class="fas fa-trash-alt text-2xl"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-800 mb-2">¿Estás seguro?</h3>
                <p class="text-gray-500">Esta acción eliminará la categoría permanentemente.</p>
            </div>
            <div class="modal-footer bg-gray-50 border-t border-gray-200 justify-center">
                <button type="button" class="btn btn-secondary rounded-lg px-4 py-2" data-bs-dismiss="modal">Cancelar</button>
                <form id="formEliminar" method="POST" action="" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger bg-red-600 hover:bg-red-700 rounded-lg px-6 py-2">Sí, Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const modalCategoria = new bootstrap.Modal(document.getElementById('modalCategoria'));
    const modalEliminar = new bootstrap.Modal(document.getElementById('modalEliminar'));

    function abrirModalNuevoCategoria() {
        document.getElementById('formCategoria').reset();
        document.getElementById('idCategoria').value = '';
        document.getElementById('metodoForm').value = 'POST';
        document.getElementById('formCategoria').action = '{{ route("categorias.store") }}';
        document.getElementById('tituloModal').textContent = 'Registrar Categoría';
        document.getElementById('btnGuardar').textContent = 'Guardar';
        modalCategoria.show();
    }

    function editarCategoria(id, nombre, descripcion, estado) {
        document.getElementById('idCategoria').value = id;
        document.getElementById('nombre').value = nombre;
        document.getElementById('descripcion').value = descripcion;
        document.getElementById('estado').value = estado;
        document.getElementById('metodoForm').value = 'PUT';
        document.getElementById('formCategoria').action = `/categorias/${id}`;
        document.getElementById('tituloModal').textContent = 'Editar Categoría';
        document.getElementById('btnGuardar').textContent = 'Actualizar';
        modalCategoria.show();
    }

    function eliminarCategoria(id) {
        document.getElementById('formEliminar').action = `/categorias/${id}`;
        modalEliminar.show();
    }
</script>
@endpush