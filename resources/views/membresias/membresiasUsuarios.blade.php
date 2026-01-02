@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Asignación de Membresías</h1>
            <p class="text-gray-500 text-sm">Gestiona las membresías activas de los usuarios</p>
        </div>
        <button type="button" class="bg-[#E31837] hover:bg-[#c41430] text-white px-4 py-2 rounded-lg shadow-md transition-all flex items-center gap-2"
            data-bs-toggle="modal" data-bs-target="#modalMembresiaUsuario" onclick="limpiarModalMembresiaUsuario()">
            <i class="fas fa-plus"></i> <span>Nueva Asignación</span>
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
                            <th class="py-3 px-4">Membresía</th>
                            <th class="py-3 px-4">Precio</th>
                            <th class="py-3 px-4">Fecha Pago</th>
                            <th class="py-3 px-4">Vigencia</th>
                            <th class="py-3 px-4">Estado</th>
                            <th class="py-3 px-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @foreach($membresiasUsuarios as $membresiaUsuario)
                        <tr class="border-b hover:bg-gray-50 transition-colors">
                            <td class="py-3 px-4 font-medium text-gray-900">{{ $membresiaUsuario->usuario }}</td>
                            <td class="py-3 px-4">
                                <span class="bg-gray-100 text-gray-700 text-xs font-semibold px-2 py-1 rounded border border-gray-200">
                                    {{ $membresiaUsuario->membresia }}
                                </span>
                            </td>
                            <td class="py-3 px-4 font-bold text-gray-800">${{ number_format($membresiaUsuario->precio, 2) }}</td>
                            <td class="py-3 px-4">{{ $membresiaUsuario->fecha_pago }}</td>
                            <td class="py-3 px-4 text-xs">
                                <div class="flex flex-col">
                                    <span class="text-green-600">INICIO: {{ $membresiaUsuario->fecha_inicio }}</span>
                                    <span class="text-red-500">FIN: {{ $membresiaUsuario->fecha_fin }}</span>
                                </div>
                            </td>
                            <td class="py-3 px-4">
                                @if($membresiaUsuario->estado == 'Activo')
                                    <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded-full border border-green-200">Activo</span>
                                @else
                                    <span class="bg-red-100 text-red-800 text-xs font-semibold px-2.5 py-0.5 rounded-full border border-red-200">Inactivo</span>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                <div class="flex gap-2">
                                    <button type="button" class="text-yellow-500 hover:text-yellow-600 transition-colors"
                                        data-bs-toggle="modal" data-bs-target="#modalMembresiaUsuario"
                                        onclick="llenarModalEditarMembresiaUsuario(
                                            '{{ $membresiaUsuario->id }}',
                                            '{{ $membresiaUsuario->usuario }}',
                                            '{{ $membresiaUsuario->membresia_id }}',
                                            '{{ $membresiaUsuario->precio }}',
                                            '{{ $membresiaUsuario->fecha_pago }}',
                                            '{{ $membresiaUsuario->fecha_inicio }}',
                                            '{{ $membresiaUsuario->fecha_fin }}',
                                            '{{ $membresiaUsuario->estado }}'
                                        )">
                                        <i class="fas fa-edit fa-lg"></i>
                                    </button>
                                    <form action="{{ route('membresias_usuarios.destroy', $membresiaUsuario->id) }}" method="POST" class="form-eliminar inline">
                                        @csrf @method('DELETE')
                                        <button class="text-red-500 hover:text-red-700 transition-colors btn-eliminar" type="button" data-id="{{ $membresiaUsuario->id }}">
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

<!-- Modal Membresia Usuario -->
<div class="modal fade" id="modalMembresiaUsuario" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-xl border-0 shadow-2xl overflow-hidden">
            <div class="modal-header bg-[#E31837] text-white px-6 py-4 border-0">
                <h5 class="modal-title font-bold text-lg" id="tituloModalMembresiaUsuario">Registrar Membresía Usuario</h5>
                <button type="button" class="btn-close btn-close-white opacity-80 hover:opacity-100" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form id="formMembresiaUsuario" method="POST" action="{{ route('membresias_usuarios.store') }}">
                @csrf
                <div class="modal-body p-6 bg-gray-50">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label for="usuario" class="form-label font-semibold text-gray-700 text-sm">Usuario <span class="text-red-500">*</span></label>
                            <select class="form-select rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500" id="usuario" name="usuario" required>
                                <option value="">Seleccione un usuario</option>
                                @foreach($usuarios as $user)
                                    <option value="{{ $user->nombres }} {{ $user->apellidos }}">{{ $user->nombres }} {{ $user->apellidos }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="membresia_id" class="form-label font-semibold text-gray-700 text-sm">Membresía <span class="text-red-500">*</span></label>
                            <select class="form-select rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500" id="membresia_id" name="membresia_id" required>
                                <option value="">Seleccione una membresía</option>
                                @foreach($membresias as $membresia)
                                    <option value="{{ $membresia->id }}" data-precio="{{ $membresia->precio }}">{{ $membresia->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="precio" class="form-label font-semibold text-gray-700 text-sm">Precio <span class="text-red-500">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-gray-100 border-gray-300">$</span>
                                <input type="number" step="0.01" class="form-control rounded-r-lg border-gray-300 focus:border-red-500 focus:ring-red-500" id="precio" name="precio" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="fecha_pago" class="form-label font-semibold text-gray-700 text-sm">Fecha de Pago <span class="text-red-500">*</span></label>
                            <input type="date" class="form-control rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500" id="fecha_pago" name="fecha_pago" required>
                        </div>
                        <div class="col-md-6">
                            <label for="fecha_inicio" class="form-label font-semibold text-gray-700 text-sm">Fecha de Inicio <span class="text-red-500">*</span></label>
                            <input type="date" class="form-control rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500" id="fecha_inicio" name="fecha_inicio" required>
                        </div>
                        <div class="col-md-6">
                            <label for="fecha_fin" class="form-label font-semibold text-gray-700 text-sm">Fecha de Fin <span class="text-red-500">*</span></label>
                            <input type="date" class="form-control rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500" id="fecha_fin" name="fecha_fin" required>
                        </div>
                        <div class="col-md-6">
                            <label for="estado" class="form-label font-semibold text-gray-700 text-sm">Estado <span class="text-red-500">*</span></label>
                            <select class="form-select rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500" id="estado" name="estado" required>
                                <option value="">Seleccione estado</option>
                                <option value="Activo">Activo</option>
                                <option value="Inactivo">Inactivo</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-white border-t border-gray-200 px-6 py-4">
                    <button type="button" class="btn btn-secondary text-gray-700 bg-gray-200 border-0 hover:bg-gray-300 font-medium rounded-lg px-4 py-2" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary bg-[#E31837] border-0 hover:bg-[#c41430] font-medium rounded-lg px-4 py-2" id="btnGuardarMembresiaUsuario">Guardar</button>
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
                <p class="text-gray-500">Esta acción eliminará la asignación permanentemente.</p>
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
    // Initialize Select2
    $(document).ready(function() {
        $('#usuario').select2({
            theme: 'bootstrap-5',
            dropdownParent: $('#modalMembresiaUsuario'),
            placeholder: 'Seleccione un usuario',
            language: {
                noResults: function() { return "No se encontraron resultados"; },
                searching: function() { return "Buscando..."; }
            }
        });
        
        // Fix for Select2 inside bootstrap modal
        $.fn.modal.Constructor.prototype.enforceFocus = function() {};
    });

    // Autofill Price Logic
    document.getElementById('membresia_id').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const precio = selectedOption.getAttribute('data-precio');
        if (precio) {
            document.getElementById('precio').value = precio;
        }
    });
    
    // Auto Calculate End Date (1 Month)
    document.getElementById('fecha_inicio').addEventListener('change', function() {
        const startDate = new Date(this.value);
        if (!isNaN(startDate.getTime())) {
            // Add 1 month
            const endDate = new Date(startDate);
            endDate.setMonth(endDate.getMonth() + 1);
            
            // Format to YYYY-MM-DD
            const year = endDate.getFullYear();
            const month = String(endDate.getMonth() + 1).padStart(2, '0');
            const day = String(endDate.getDate()).padStart(2, '0');
            
            document.getElementById('fecha_fin').value = `${year}-${month}-${day}`;
        }
    });

    function llenarModalEditarMembresiaUsuario(id, usuario, membresia_id, precio, fecha_pago, fecha_inicio, fecha_fin, estado) {
        const form = document.getElementById('formMembresiaUsuario');
        form.action = '/membresias_usuarios/' + id;
        form.querySelector('input[name="_method"]')?.remove();
        let input = document.createElement('input');
        input.type = 'hidden'; input.name = '_method'; input.value = 'PUT';
        form.appendChild(input);

        // Select2 trigger change properly - Handle standard name finding
        // Since value is "Name Lastname", we set it directly. 
        // Note: The previous logic relied on text matching value.
        $('#usuario').val(usuario).trigger('change');

        document.getElementById('membresia_id').value = membresia_id;
        document.getElementById('precio').value = precio;
        document.getElementById('fecha_pago').value = fecha_pago;
        document.getElementById('fecha_inicio').value = fecha_inicio;
        document.getElementById('fecha_fin').value = fecha_fin;
        document.getElementById('estado').value = estado;

        document.getElementById('tituloModalMembresiaUsuario').textContent = 'Editar Membresía Usuario';
        document.getElementById('btnGuardarMembresiaUsuario').textContent = 'Actualizar';
    }

    function limpiarModalMembresiaUsuario() {
        const form = document.getElementById('formMembresiaUsuario');
        form.reset();
        
        // Reset Select2
        $('#usuario').val(null).trigger('change');
        
        form.action = "{{ route('membresias_usuarios.store') }}";
        form.querySelector('input[name="_method"]')?.remove();
        document.getElementById('tituloModalMembresiaUsuario').textContent = 'Registrar Membresía Usuario';
        document.getElementById('btnGuardarMembresiaUsuario').textContent = 'Guardar';
    }

    let formAEliminar = null;
    
    // Event Delegation: Listen on document for clicks on .btn-eliminar or its children
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.btn-eliminar');
        if (btn) {
            formAEliminar = btn.closest('form');
            const modal = new bootstrap.Modal(document.getElementById('modalConfirmarEliminar'));
            modal.show();
        }
    });

    document.getElementById('btnConfirmarEliminar').addEventListener('click', function() {
        if (formAEliminar) {
            formAEliminar.submit();
        }
    });
</script>
@endpush