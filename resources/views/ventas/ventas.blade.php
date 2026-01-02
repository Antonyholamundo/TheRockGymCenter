@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded shadow-sm" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded shadow-sm" role="alert">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">LISTADO DE VENTAS</h2>
            <button type="button" 
                class="bg-[#E31837] hover:bg-[#c41430] text-white px-4 py-2 rounded-lg shadow-md transition-all flex items-center gap-2"
                data-bs-toggle="modal" data-bs-target="#modalVenta" onclick="limpiarModalVenta()">
                <i class="fas fa-plus"></i>
                <span>Nueva Venta</span>
            </button>
        </div>

        <div class="bg-white rounded-xl shadow-sm overflow-hidden p-6 border border-gray-100">
            <div class="table-responsive">
                <table id="mitabla" class="table table-hover w-full whitespace-nowrap">
                    <thead class="bg-gray-50 text-gray-700">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Cliente</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Vendedor</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Producto</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Cant</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Precio Total</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Fecha Venta</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Estado</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Fecha Pago</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($ventas as $venta)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3 text-sm text-gray-700">{{ $venta->cliente }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700">{{ $venta->vendedor }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700">{{ $venta->producto->nombre ?? 'N/A' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700 font-bold">{{ $venta->cantidad }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700">${{ number_format($venta->precio, 2) }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700">{{ $venta->fecha_venta }}</td>
                            <td class="px-4 py-3 text-sm">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $venta->id_rol == 6 ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                     <span class="{{ $venta->pagado == 'Pagado' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} px-2 py-1 rounded-full text-xs">
                                        {{ $venta->pagado }}
                                     </span>
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700">{{ $venta->fecha_pago ? $venta->fecha_pago : 'N/A' }}</td>
                            <td class="px-4 py-3 text-sm space-x-2">
                                <form action="{{ route('ventas.destroy', $venta->id) }}" method="POST" style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button class="text-red-600 hover:text-red-900 transition-colors btn-eliminar" type="button">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                <button type="button" class="text-yellow-600 hover:text-yellow-900 transition-colors"
                                    data-bs-toggle="modal" data-bs-target="#modalVenta"
                                    onclick="llenarModalEditarVenta(
                                        '{{ $venta->id }}',
                                        '{{ $venta->cliente }}',
                                        '{{ $venta->vendedor }}',
                                        '{{ $venta->producto_id }}',
                                        '{{ $venta->precio }}',
                                        '{{ $venta->fecha_venta }}',
                                        '{{ $venta->pagado }}',
                                        '{{ $venta->fecha_pago ? $venta->fecha_pago : '' }}'
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
    <div class="modal fade" id="modalVenta" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content rounded-xl shadow-lg border-0">
                <form id="formVenta" method="POST" action="{{ route('ventas.store') }}">
                    @csrf
                    <div class="modal-header bg-[#E31837] text-white rounded-t-xl">
                        <h5 class="modal-title font-bold" id="tituloModalVenta">Registrar Venta</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-6">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="cliente" class="form-label font-medium text-gray-700">Cliente *</label>
                                <label for="cliente" class="form-label font-medium text-gray-700">Cliente *</label>
                                <select class="form-select rounded-lg border-gray-300 focus:border-[#E31837] focus:ring focus:ring-[#E31837]/20" id="cliente" name="cliente" required>
                                    <option value="">Seleccione un cliente</option>
                                    @foreach($usuarios as $usuario)
                                        <option value="{{ $usuario->nombres }} {{ $usuario->apellidos }}">{{ $usuario->nombres }} {{ $usuario->apellidos }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="vendedor" class="form-label font-medium text-gray-700">Vendedor *</label>
                                <input type="text" class="form-control rounded-lg border-gray-300 focus:border-[#E31837] focus:ring focus:ring-[#E31837]/20" id="vendedor" name="vendedor" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-5 mb-3">
                                <label for="producto_id" class="form-label font-medium text-gray-700">Producto *</label>
                                <select class="form-select rounded-lg border-gray-300 focus:border-[#E31837] focus:ring focus:ring-[#E31837]/20" id="producto_id" name="producto_id" required onchange="actualizarPrecio()">
                                    <option value="">Seleccione un producto</option>
                                    @foreach($productos as $producto)
                                        <option value="{{ $producto->id }}" data-precio="{{ $producto->precio }}">{{ $producto->nombre }} (Stock: {{ $producto->stock }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 mb-3">
                                <label for="cantidad" class="form-label font-medium text-gray-700">Cant *</label>
                                <input type="number" class="form-control rounded-lg border-gray-300 focus:border-[#E31837] focus:ring focus:ring-[#E31837]/20" id="cantidad" name="cantidad" value="1" required min="1" onchange="actualizarPrecio()">
                            </div>
                            <div class="col-md-5 mb-3">
                                <label for="precio" class="form-label font-medium text-gray-700">Precio Total *</label>
                                <input type="number" step="0.01" class="form-control rounded-lg border-gray-300 focus:border-[#E31837] focus:ring focus:ring-[#E31837]/20" id="precio" name="precio" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="fecha_venta" class="form-label font-medium text-gray-700">Fecha de Venta *</label>
                                <input type="date" class="form-control rounded-lg border-gray-300 focus:border-[#E31837] focus:ring focus:ring-[#E31837]/20" id="fecha_venta" name="fecha_venta" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="pagado" class="form-label font-medium text-gray-700">Estado de Pago *</label>
                                <select class="form-select rounded-lg border-gray-300 focus:border-[#E31837] focus:ring focus:ring-[#E31837]/20" id="pagado" name="pagado" required>
                                    <option value="">Seleccione estado</option>
                                    <option value="Pagado">Pagado</option>
                                    <option value="Pendiente">Pendiente</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="fecha_pago" class="form-label font-medium text-gray-700">Fecha de Pago</label>
                                <input type="date" class="form-control rounded-lg border-gray-300 focus:border-[#E31837] focus:ring focus:ring-[#E31837]/20" id="fecha_pago" name="fecha_pago">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-t border-gray-100 bg-gray-50 rounded-b-xl">
                        <button type="button" class="btn btn-secondary rounded-lg px-4 py-2" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn text-white rounded-lg px-4 py-2" id="btnGuardarVenta" style="background-color: #E31837;">Guardar</button>
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
                    <p class="text-gray-500">Esta acción eliminará la venta permanentemente.</p>
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
    // DataTable configuration
    new DataTable('#mitabla', {
        language: { url: 'https://cdn.datatables.net/plug-ins/2.2.1/i18n/es-ES.json' },
        columnDefs: [
            { className: "dt-center", targets: "_all" }
        ],
        responsive: true
    });

    // Initialize Select2
    $(document).ready(function() {
        $('#cliente').select2({
            theme: 'bootstrap-5',
            dropdownParent: $('#modalVenta'),
            placeholder: 'Seleccione un cliente',
            language: {
                noResults: function() { return "No se encontraron resultados"; },
                searching: function() { return "Buscando..."; }
            }
        });
        
        $('#producto_id').select2({
            theme: 'bootstrap-5',
            dropdownParent: $('#modalVenta'),
            placeholder: 'Seleccione un producto',
            language: {
                noResults: function() { return "No se encontraron resultados"; },
                searching: function() { return "Buscando..."; }
            }
        });
        
        // Fix for Select2 inside bootstrap modal (focus issue)
        $.fn.modal.Constructor.prototype.enforceFocus = function() {};
    });

    function llenarModalEditarVenta(id, cliente, vendedor, producto_id, precio, fecha_venta, pagado, fecha_pago) {
        const form = document.getElementById('formVenta');
        form.action = '/ventas/' + id;
        
        // Remove existing method field if any
        form.querySelector('input[name="_method"]')?.remove();
        
        // Add PUT method field
        let input = document.createElement('input');
        input.type = 'hidden'; 
        input.name = '_method'; 
        input.value = 'PUT';
        form.appendChild(input);

        // Populate fields
        // For Select2, we must trigger 'change' event to update UI
        $('#cliente').val(cliente).trigger('change');
        $('#producto_id').val(producto_id).trigger('change');
        
        document.getElementById('vendedor').value = vendedor;
        // document.getElementById('producto_id').value = producto_id; // Handled by jQuery above
        document.getElementById('precio').value = precio;
        document.getElementById('fecha_venta').value = fecha_venta;
        document.getElementById('pagado').value = pagado;
        document.getElementById('fecha_pago').value = fecha_pago;

        // Update UI
        document.getElementById('tituloModalVenta').textContent = 'Editar Venta';
        document.getElementById('btnGuardarVenta').textContent = 'Actualizar';
        
        // Trigger generic change event in case of listeners
        document.getElementById('pagado').dispatchEvent(new Event('change'));
    }

    function actualizarPrecio() {
        const select = document.getElementById('producto_id');
        const cantidad = document.getElementById('cantidad').value;
        const selectedOption = select.options[select.selectedIndex];
        const precioUnitario = selectedOption.getAttribute('data-precio');
        
        if (precioUnitario && cantidad) {
            const precioTotal = (parseFloat(precioUnitario) * parseInt(cantidad)).toFixed(2);
            document.getElementById('precio').value = precioTotal;
        }
    }

    function limpiarModalVenta() {
        const form = document.getElementById('formVenta');
        form.reset();
        form.action = "{{ route('ventas.store') }}";
        form.querySelector('input[name="_method"]')?.remove();
        
        document.getElementById('tituloModalVenta').textContent = 'Registrar Venta';
        document.getElementById('btnGuardarVenta').textContent = 'Guardar';
        
        // Reset specific visibility if needed
        document.getElementById('fecha_pago').required = false;
        
        // Reset Select2
        $('#cliente').val(null).trigger('change');
        $('#producto_id').val(null).trigger('change');
    }

    // Toggle Payment Date Requirement
    document.getElementById('pagado').addEventListener('change', function() {
        const fechaPagoField = document.getElementById('fecha_pago');
        if (this.value === 'Pagado') {
            fechaPagoField.required = true;
        } else {
            fechaPagoField.required = false;
        }
    });

    let formAEliminar = null;
    
    // Event Delegation for Delete Button
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