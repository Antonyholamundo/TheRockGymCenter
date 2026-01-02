@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Breadcrumb / Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Gestión de Usuarios</h1>
            <p class="text-gray-500 text-sm">Administra los usuarios del sistema</p>
        </div>
        <button type="button" class="bg-[#E31837] hover:bg-[#c41430] text-white px-4 py-2 rounded-lg shadow-md transition-all flex items-center gap-2"
            data-bs-toggle="modal" data-bs-target="#modalUsuario"
            onclick="limpiarModalUsuario()">
            <i class="fas fa-plus"></i> <span>Nuevo Usuario</span>
        </button>
    </div>

    <!-- Mensajes de éxito/error -->
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

    <!-- Tabla Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6">
            <div class="table-responsive">
                <table id="mitabla" class="table table-hover w-full pt-4 pb-4">
                    <thead class="bg-gray-50 text-gray-700 uppercase text-xs font-bold">
                        <tr>
                            <th class="py-3 px-4">Cedula</th>
                            <th class="py-3 px-4">Nombres</th>
                            <th class="py-3 px-4">Apellidos</th>
                            <th class="py-3 px-4">Fecha Nacimiento</th>
                            <th class="py-3 px-4">Email</th>
                            <th class="py-3 px-4">Teléfono</th>
                            <th class="py-3 px-4">Estado</th>
                            <th class="py-3 px-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @foreach ($usuarios as $usuario)
                        <tr class="border-b hover:bg-gray-50 transition-colors">
                            <td class="py-3 px-4">{{ $usuario->cedula }}</td>
                            <td class="py-3 px-4 font-medium text-gray-900">{{ $usuario->nombres }}</td>
                            <td class="py-3 px-4">{{ $usuario->apellidos }}</td>
                            <td class="py-3 px-4">{{ $usuario->fecha_nacimiento }}</td>
                            <td class="py-3 px-4 text-blue-600">{{ $usuario->email }}</td>
                            <td class="py-3 px-4">{{ $usuario->telefono }}</td>
                            <td class="py-3 px-4">
                                @if($usuario->estado == 'Activo')
                                    <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded-full border border-green-200">Activo</span>
                                @else
                                    <span class="bg-red-100 text-red-800 text-xs font-semibold px-2.5 py-0.5 rounded-full border border-red-200">Inactivo</span>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                <div class="flex gap-2">
                                     <button type="button" class="text-yellow-500 hover:text-yellow-600 transition-colors" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#modalUsuario"
                                        title="Editar"
                                        onclick="llenarModalEditar(
                                            '{{ $usuario->id }}',
                                            '{{ $usuario->cedula }}',
                                            '{{ $usuario->nombres }}',
                                            '{{ $usuario->apellidos }}',
                                            '{{ $usuario->fecha_nacimiento }}',
                                            '{{ $usuario->email }}',
                                            '{{ $usuario->telefono }}',
                                            '{{ $usuario->estado }}'
                                        )">
                                        <i class="fas fa-edit fa-lg"></i>
                                    </button>

                                    <form action="{{ route('usuarios.destroy', $usuario->id) }}" method="POST" class="form-eliminar inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-500 hover:text-red-700 transition-colors btn-eliminar" type="button" data-id="{{ $usuario->id }}" title="Eliminar">
                                            <i class="fas fa-trash fa-lg"></i>
                                        </button>
                                    </form>
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

<!-- Modal para Nuevo Usuario -->
<div class="modal fade" id="modalUsuario" tabindex="-1" aria-labelledby="tituloModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-xl border-0 shadow-2xl overflow-hidden">
            <div class="modal-header bg-[#E31837] text-white px-6 py-4 border-0">
                <h5 class="modal-title font-bold text-lg" id="tituloModal">Registrar Usuario</h5>
                <button type="button" class="btn-close btn-close-white opacity-80 hover:opacity-100" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form id="formUsuario" method="POST" action="{{ route('usuarios.store') }}">
                @csrf
                <div class="modal-body p-6 bg-gray-50">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label for="cedula" class="form-label font-semibold text-gray-700 text-sm">Cédula <span class="text-red-500">*</span></label>
                            <input type="text" class="form-control rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500" id="cedula" name="cedula" required oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                        </div>
                        <div class="col-md-6">
                            <label for="nombres" class="form-label font-semibold text-gray-700 text-sm">Nombres <span class="text-red-500">*</span></label>
                            <input type="text" class="form-control rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500" id="nombres" name="nombres" required>
                        </div>
                        <div class="col-md-6">
                            <label for="apellidos" class="form-label font-semibold text-gray-700 text-sm">Apellidos <span class="text-red-500">*</span></label>
                            <input type="text" class="form-control rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500" id="apellidos" name="apellidos" required>
                        </div>
                        <div class="col-md-6">
                            <label for="fecha_nacimiento" class="form-label font-semibold text-gray-700 text-sm">Fecha de Nacimiento <span class="text-red-500">*</span></label>
                            <input type="date" class="form-control rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500" id="fecha_nacimiento" name="fecha_nacimiento" required>
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label font-semibold text-gray-700 text-sm">Email <span class="text-red-500">*</span></label>
                            <input type="email" class="form-control rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500" id="email" name="email" required>
                        </div>
                        <div class="col-md-6">
                            <label for="telefono" class="form-label font-semibold text-gray-700 text-sm">Teléfono <span class="text-red-500">*</span></label>
                            <input type="text" class="form-control rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500" id="telefono" name="telefono" required>
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
                    <button type="submit" class="btn btn-primary bg-[#E31837] border-0 hover:bg-[#c41430] font-medium rounded-lg px-4 py-2" id="btnGuardar">Guardar Usuario</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de confirmación para eliminar -->
<div class="modal fade" id="modalConfirmarEliminar" tabindex="-1">
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
                <p class="text-gray-500">Esta acción eliminará permanentemente al usuario. No se puede deshacer.</p>
            </div>
            <div class="modal-footer bg-gray-50 border-t border-gray-200 justify-center">
                <button type="button" class="btn btn-secondary rounded-lg px-4 py-2" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger bg-red-600 hover:bg-red-700 rounded-lg px-6 py-2" id="btnConfirmarEliminar">Sí, Eliminar</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function llenarModalEditar(id, cedula, nombres, apellidos, fecha_nacimiento, email, telefono, estado) {
    document.getElementById('formUsuario').action = '/usuarios/' + id;
    document.getElementById('formUsuario').querySelector('input[name="_method"]')?.remove();
    // Agrega el método PUT para editar
    let input = document.createElement('input');
    input.type = 'hidden';
    input.name = '_method';
    input.value = 'PUT';
    document.getElementById('formUsuario').appendChild(input);

    document.getElementById('cedula').value = cedula;
    document.getElementById('nombres').value = nombres;
    document.getElementById('apellidos').value = apellidos;
    document.getElementById('fecha_nacimiento').value = fecha_nacimiento;
    document.getElementById('email').value = email;
    document.getElementById('telefono').value = telefono;
    document.getElementById('estado').value = estado;
    document.getElementById('tituloModal').textContent = 'Editar Usuario';
    document.getElementById('btnGuardar').textContent = 'Actualizar Usuario';
}

function limpiarModalUsuario() {
    document.getElementById('formUsuario').reset();
    document.getElementById('formUsuario').action = "{{ route('usuarios.store') }}";
    document.getElementById('formUsuario').querySelector('input[name="_method"]')?.remove();
    document.getElementById('tituloModal').textContent = 'Registrar Usuario';
    document.getElementById('btnGuardar').textContent = 'Guardar Usuario';
}

let formAEliminar = null;
document.querySelectorAll('.btn-eliminar').forEach(btn => {
    btn.addEventListener('click', function() {
        formAEliminar = this.closest('form');
        const modal = new bootstrap.Modal(document.getElementById('modalConfirmarEliminar'));
        modal.show();
    });
});
document.getElementById('btnConfirmarEliminar').addEventListener('click', function() {
    if (formAEliminar) {
        formAEliminar.submit();
    }
});
</script>
@endpush