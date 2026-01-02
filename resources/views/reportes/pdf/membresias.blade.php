<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Membresías - The Rock Gym Center</title>
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
            color: #dc3545;
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
        .status-active {
            color: #28a745;
            font-weight: bold;
        }
        .status-inactive {
            color: #dc3545;
            font-weight: bold;
        }
        .expired {
            color: #dc3545;
            font-weight: bold;
        }
        .active {
            color: #28a745;
            font-weight: bold;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            color: #666;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>THE ROCK GYM CENTER</h1>
        <p>Reporte de Membresías</p>
        <p>Generado el: {{ date('d/m/Y H:i:s') }}</p>
    </div>

    <div class="summary">
        <div class="summary-item">
            <h4>Total Membresías</h4>
            <div class="amount">{{ $totalMembresias }}</div>
        </div>
        <div class="summary-item">
            <h4>Membresías Activas</h4>
            <div class="amount">{{ $membresiasActivas }}</div>
        </div>
        <div class="summary-item">
            <h4>Membresías Inactivas</h4>
            <div class="amount">{{ $membresiasInactivas }}</div>
        </div>
        <div class="summary-item">
            <h4>Total Ingresos</h4>
            <div class="amount">${{ number_format($totalIngresos, 2) }}</div>
        </div>
    </div>

    @if($membresias->count() > 0)
    <table>
        <thead>
            <tr>
                <th>Usuario</th>
                <th>Membresía</th>
                <th>Precio</th>
                <th>Fecha de Pago</th>
                <th>Fecha Inicio</th>
                <th>Fecha Fin</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($membresias as $membresia)
            <tr>
                <td>{{ $membresia->usuario }}</td>
                <td>{{ $membresia->membresia }}</td>
                <td>${{ number_format($membresia->precio, 2) }}</td>
                <td>{{ date('d/m/Y', strtotime($membresia->fecha_pago)) }}</td>
                <td>{{ date('d/m/Y', strtotime($membresia->fecha_inicio)) }}</td>
                <td class="{{ strtotime($membresia->fecha_fin) < time() ? 'expired' : 'active' }}">
                    {{ date('d/m/Y', strtotime($membresia->fecha_fin)) }}
                </td>
                <td class="{{ $membresia->estado == 'Activo' ? 'status-active' : 'status-inactive' }}">
                    {{ $membresia->estado }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div style="text-align: center; padding: 40px; color: #666;">
        <h3>No se encontraron membresías</h3>
    </div>
    @endif

    <div class="footer">
        <p>Este reporte fue generado automáticamente por el sistema de The Rock Gym Center</p>
        <p>Página 1 de 1</p>
    </div>
</body>
</html> 