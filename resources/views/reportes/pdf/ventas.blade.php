<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Ventas - The Rock Gym Center</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #333;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #333;
            margin: 0;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .filters {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .filters h3 {
            margin: 0 0 10px 0;
            color: #333;
        }
        .filters p {
            margin: 5px 0;
        }
        .summary {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        .summary-item {
            background-color: #e9ecef;
            padding: 15px;
            border-radius: 5px;
            text-align: center;
            min-width: 150px;
            margin: 5px;
        }
        .summary-item h4 {
            margin: 0 0 10px 0;
            color: #333;
        }
        .summary-item .amount {
            font-size: 20px;
            font-weight: bold;
            color: #28a745;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #333;
            color: white;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .status-paid {
            color: #28a745;
            font-weight: bold;
        }
        .status-pending {
            color: #ffc107;
            font-weight: bold;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            color: #666;
            font-size: 12px;
        }
        .page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>THE ROCK GYM CENTER</h1>
        <p>Reporte de Ventas</p>
        <p>Generado el: {{ date('d/m/Y H:i:s') }}</p>
    </div>

    @if($fechaInicio || $fechaFin)
    <div class="filters">
        <h3>Filtros Aplicados:</h3>
        @if($fechaInicio)
            <p><strong>Fecha Inicio:</strong> {{ date('d/m/Y', strtotime($fechaInicio)) }}</p>
        @endif
        @if($fechaFin)
            <p><strong>Fecha Fin:</strong> {{ date('d/m/Y', strtotime($fechaFin)) }}</p>
        @endif
    </div>
    @endif

    <div class="summary">
        <div class="summary-item">
            <h4>Total Ventas</h4>
            <div class="amount">${{ number_format($totalVentas, 2) }}</div>
        </div>
        <div class="summary-item">
            <h4>Ventas Pagadas</h4>
            <div class="amount">${{ number_format($ventasPagadas, 2) }}</div>
        </div>
        <div class="summary-item">
            <h4>Ventas Pendientes</h4>
            <div class="amount">${{ number_format($ventasPendientes, 2) }}</div>
        </div>
        <div class="summary-item">
            <h4>Cantidad de Ventas</h4>
            <div class="amount">{{ $ventas->count() }}</div>
        </div>
    </div>

    @if($ventas->count() > 0)
    <table>
        <thead>
            <tr>
                <th>Cliente</th>
                <th>Vendedor</th>
                <th>Producto</th>
                <th>Precio</th>
                <th>Fecha de Venta</th>
                <th>Estado</th>
                <th>Fecha de Pago</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ventas as $venta)
            <tr>
                <td>{{ $venta->cliente }}</td>
                <td>{{ $venta->vendedor }}</td>
                <td>{{ $venta->producto }}</td>
                <td>${{ number_format($venta->precio, 2) }}</td>
                <td>{{ date('d/m/Y', strtotime($venta->fecha_venta)) }}</td>
                <td class="{{ $venta->pagado ? 'status-paid' : 'status-pending' }}">
                    {{ $venta->pagado ? 'Pagado' : 'Pendiente' }}
                </td>
                <td>
                    @if($venta->fecha_pago)
                        {{ date('d/m/Y', strtotime($venta->fecha_pago)) }}
                    @else
                        -
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div style="text-align: center; padding: 40px; color: #666;">
        <h3>No se encontraron ventas con los filtros aplicados</h3>
    </div>
    @endif

    <div class="footer">
        <p>Este reporte fue generado automáticamente por el sistema de The Rock Gym Center</p>
        <p>Página 1 de 1</p>
    </div>
</body>
</html> 