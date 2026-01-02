@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Inventario de Productos</h1>
            <p class="text-gray-500 text-sm">Gestiona el catálogo de productos y stock</p>
        </div>
        <button type="button" class="bg-[#E31837] hover:bg-[#c41430] text-white px-4 py-2 rounded-lg shadow-md transition-all flex items-center gap-2"
            onclick="abrirModalNuevoProducto()">
            <i class="fas fa-plus"></i> <span>Nuevo Producto</span>
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
                            <th class="py-3 px-4">Precio</th>
                            <th class="py-3 px-4">Stock</th>
                            <th class="py-3 px-4">Categoría</th>
                            <th class="py-3 px-4">Descripción</th>
                            <th class="py-3 px-4">Estado</th>
                            <th class="py-3 px-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @foreach ($productos as $producto)
                        <tr class="border-b hover:bg-gray-50 transition-colors">
                            <td class="py-3 px-4 font-medium text-gray-900">{{ $producto->nombre }}</td>
                            <td class="py-3 px-4 font-bold text-gray-800">${{ number_format($producto->precio, 2) }}</td>
                            <td class="py-3 px-4">
                                <span class="{{ $producto->stock < 10 ? 'text-red-600 font-bold' : 'text-green-600' }}">
                                    {{ $producto->stock }}
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                <span class="bg-gray-100 text-gray-700 text-xs font-semibold px-2 py-1 rounded border border-gray-200">
                                    {{ $producto->categoria ? $producto->categoria->nombre : 'Sin categoría' }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-gray-500">{{ Str::limit($producto->descripcion, 30) }}</td>
                            <td class="py-3 px-4">
                                @if($producto->estado == 'Activo')
                                    <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded-full border border-green-200">Activo</span>
                                @else
                                    <span class="bg-red-100 text-red-800 text-xs font-semibold px-2.5 py-0.5 rounded-full border border-red-200">Inactivo</span>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                <div class="flex gap-2">
                                    <button type="button" class="text-yellow-500 hover:text-yellow-600 transition-colors"
                                        onclick="editarProducto({{ $producto->id }}, '{{ $producto->nombre }}', '{{ $producto->precio }}', {{ $producto->stock }}, {{ $producto->categoria_id }}, '{{ $producto->descripcion }}', '{{ $producto->estado }}')">
                                        <i class="fas fa-edit fa-lg"></i>
                                    </button>
                                    <button class="text-red-500 hover:text-red-700 transition-colors"
                                        onclick="eliminarProducto({{ $producto->id }})">
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

<!-- Modal Producto -->
<div class="modal fade" id="modalProducto" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-xl border-0 shadow-2xl overflow-hidden">
            <div class="modal-header bg-[#E31837] text-white px-6 py-4 border-0">
                <h5 class="modal-title font-bold text-lg" id="tituloModal">Registrar Producto</h5>
                <button type="button" class="btn-close btn-close-white opacity-80 hover:opacity-100" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form id="formProducto" method="POST" action="{{ route('productos.store') }}">
                @csrf
                <input type="hidden" id="idProducto" name="id">
                <input type="hidden" id="metodoForm" name="_method" value="POST">
                <div class="modal-body p-6 bg-gray-50">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label for="nombre" class="form-label font-semibold text-gray-700 text-sm">Nombre <span class="text-red-500">*</span></label>
                            <input type="text" class="form-control rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500" id="nombre" name="nombre" required>
                        </div>
                        <div class="col-md-6">
                            <label for="categoria_id" class="form-label font-semibold text-gray-700 text-sm">Categoría <span class="text-red-500">*</span></label>
                            <select class="form-select rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500" id="categoria_id" name="categoria_id" required>
                                <option value="">Seleccione una categoría</option>
                                @foreach($categorias as $categoria)
                                    <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="precio" class="form-label font-semibold text-gray-700 text-sm">Precio <span class="text-red-500">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-gray-100 border-gray-300">$</span>
                                <input type="number" step="0.01" min="0" class="form-control rounded-r-lg border-gray-300 focus:border-red-500 focus:ring-red-500" id="precio" name="precio" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="stock" class="form-label font-semibold text-gray-700 text-sm">Stock <span class="text-red-500">*</span></label>
                            <input type="number" min="0" class="form-control rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500" id="stock" name="stock" required>
                        </div>
                        <div class="col-12">
                            <label for="descripcion" class="form-label font-semibold text-gray-700 text-sm">Descripción <span class="text-red-500">*</span></label>
                            <textarea class="form-control rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500" id="descripcion" name="descripcion" rows="3" required></textarea>
                        </div>
                        <div class="col-md-6">
                            <label for="estado" class="form-label font-semibold text-gray-700 text-sm">Estado <span class="text-red-500">*</span></label>
                            <select class="form-select rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500" id="estado" name="estado" required>
                                <option value="">Seleccione...</option>
                                <option value="Activo">Activo</option>
                                <option value="Inactivo">Inactivo</option>
                            </select>
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
                <p class="text-gray-500">Esta acción eliminará el producto permanentemente.</p>
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
    const modalProducto = new bootstrap.Modal(document.getElementById('modalProducto'));
    const modalEliminar = new bootstrap.Modal(document.getElementById('modalEliminar'));

    function abrirModalNuevoProducto() {
        document.getElementById('formProducto').reset();
        document.getElementById('idProducto').value = '';
        document.getElementById('metodoForm').value = 'POST';
        document.getElementById('formProducto').action = '{{ route("productos.store") }}';
        document.getElementById('tituloModal').textContent = 'Registrar Producto';
        document.getElementById('btnGuardar').textContent = 'Guardar';
        modalProducto.show();
    }

    function editarProducto(id, nombre, precio, stock, categoria_id, descripcion, estado) {
        document.getElementById('idProducto').value = id;
        document.getElementById('nombre').value = nombre;
        document.getElementById('precio').value = precio;
        document.getElementById('stock').value = stock;
        document.getElementById('categoria_id').value = categoria_id;
        document.getElementById('descripcion').value = descripcion;
        document.getElementById('estado').value = estado;
        document.getElementById('metodoForm').value = 'PUT';
        document.getElementById('formProducto').action = `/productos/${id}`;
        document.getElementById('tituloModal').textContent = 'Editar Producto';
        document.getElementById('btnGuardar').textContent = 'Actualizar';
        modalProducto.show();
    }

    function eliminarProducto(id) {
        document.getElementById('formEliminar').action = `/productos/${id}`;
        modalEliminar.show();
    }
</script>
@endpush