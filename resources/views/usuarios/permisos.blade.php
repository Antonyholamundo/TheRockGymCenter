@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Gestión de Permisos</h1>
            <p class="text-gray-500 text-sm">Asigna permisos y roles a usuarios</p>
        </div>
        <button type="button" class="bg-[#E31837] hover:bg-[#c41430] text-white px-4 py-2 rounded-lg shadow-md transition-all flex items-center gap-2"
            data-bs-toggle="modal" data-bs-target="#modalPermiso"
            onclick="limpiarModalPermiso()">
            <i class="fas fa-plus"></i> <span>Nuevo Permiso</span>
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

    @if($errors->any())
        <div class="bg-red-50 text-red-600 p-4 rounded-lg border border-red-200 mb-4">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
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
                            <th class="py-3 px-4">Usuario</th>
                            <th class="py-3 px-4">Rol Asignado</th>
                            <th class="py-3 px-4">Fecha Asignación</th>
                            <th class="py-3 px-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @foreach($permisos as $permiso)
                        <tr class="border-b hover:bg-gray-50 transition-colors">
                            <td class="py-3 px-4 font-medium text-gray-900">{{ $permiso->usuario->nombres }}</td>
                            <td class="py-3 px-4">
                                <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded-full border border-blue-200">
                                    {{ $permiso->rol }}
                                </span>
                            </td>
                            <td class="py-3 px-4">{{ $permiso->fecha_asignacion }}</td>
                            <td class="py-3 px-4">
                                <div class="flex gap-2">
                                    <button type="button" class="text-yellow-500 hover:text-yellow-600 transition-colors"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalPermiso"
                                        onclick="llenarModalEditarPermiso(
                                            '{{ $permiso->id }}',
                                            '{{ $permiso->usuario_id }}',
                                            '{{ $permiso->rol }}',
                                            '{{ $permiso->fecha_asignacion }}'
                                        )">
                                        <i class="fas fa-edit fa-lg"></i>
                                    </button>
                                    <form action="{{ route('permisos.destroy', $permiso->id) }}" method="POST" class="form-eliminar inline">
                                        @csrf @method('DELETE')
                                        <button class="text-red-500 hover:text-red-700 transition-colors btn-eliminar" type="button" data-id="{{ $permiso->id }}">
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

<!-- Modal Permiso -->
<div class="modal fade" id="modalPermiso" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-xl border-0 shadow-2xl overflow-hidden">
            <div class="modal-header bg-[#E31837] text-white px-6 py-4 border-0">
                <h5 class="modal-title font-bold text-lg" id="tituloModalPermiso">Registrar Permiso</h5>
                <button type="button" class="btn-close btn-close-white opacity-80 hover:opacity-100" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form id="formPermiso" method="POST" action="{{ route('permisos.store') }}">
                @csrf
                <div class="modal-body p-6 bg-gray-50">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label font-semibold text-gray-700 text-sm">Usuario <span class="text-red-500">*</span></label>
                            <select name="usuario_id" id="usuario_id" class="form-select rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500" required>
                                <option value="">Seleccione un usuario</option>
                                @foreach($usuarios as $usuario)
                                    <option value="{{ $usuario->id }}">{{ $usuario->nombres }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label font-semibold text-gray-700 text-sm">Rol <span class="text-red-500">*</span></label>
                            <select name="rol" id="rol" class="form-select rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500" required>
                                <option value="">Seleccione un Rol</option>
                                <option value="Administrador">Administrador</option>
                                <option value="Entrenador">Entrenador</option>
                                <option value="Cliente">Cliente</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label font-semibold text-gray-700 text-sm">Fecha de Asignación <span class="text-red-500">*</span></label>
                            <input type="date" class="form-control rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500" name="fecha_asignacion" id="fecha_asignacion" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-white border-t border-gray-200 px-6 py-4">
                    <button type="button" class="btn btn-secondary text-gray-700 bg-gray-200 border-0 hover:bg-gray-300 font-medium rounded-lg px-4 py-2" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary bg-[#E31837] border-0 hover:bg-[#c41430] font-medium rounded-lg px-4 py-2" id="btnGuardarPermiso">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Eliminar -->
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
                <p class="text-gray-500">Esta acción eliminará el permiso permanentemente.</p>
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
    function llenarModalEditarPermiso(id, usuario_id, rol, fecha_asignacion) {
        const form = document.getElementById('formPermiso');
        form.action = '/permisos/' + id;
        form.querySelector('input[name="_method"]')?.remove();
        let input = document.createElement('input');
        input.type = 'hidden'; input.name = '_method'; input.value = 'PUT';
        form.appendChild(input);
        document.getElementById('usuario_id').value = usuario_id;
        document.getElementById('rol').value = rol;
        document.getElementById('fecha_asignacion').value = fecha_asignacion;

        document.getElementById('tituloModalPermiso').textContent = 'Editar Permiso';
        document.getElementById('btnGuardarPermiso').textContent = 'Actualizar';
    }

    function limpiarModalPermiso() {
        const form = document.getElementById('formPermiso');
        form.reset();
        form.action = "{{ route('permisos.store') }}";
        form.querySelector('input[name="_method"]')?.remove();
        document.getElementById('tituloModalPermiso').textContent = 'Registrar Permiso';
        document.getElementById('btnGuardarPermiso').textContent = 'Guardar';
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
