<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Ingresos - The Rock Gym Center</title>
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
            color: #17a2b8;
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
        <p>Reporte de Ingresos</p>
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
            <h4>Total de Ingresos</h4>
            <div class="amount">{{ $totalIngresos }}</div>
        </div>
    </div>

    @if($ingresos->count() > 0)
    <table>
        <thead>
            <tr>
                <th>Cédula</th>
                <th>Fecha</th>
                <th>Detalles</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ingresos as $ingreso)
            <tr>
                <td>{{ $ingreso->cedula }}</td>
                <td>{{ date('d/m/Y H:i', strtotime($ingreso->fecha)) }}</td>
                <td>{{ $ingreso->detalles ?? 'Sin detalles' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div style="text-align: center; padding: 40px; color: #666;">
        <h3>No se encontraron ingresos con los filtros aplicados</h3>
    </div>
    @endif

    <div class="footer">
        <p>Este reporte fue generado automáticamente por el sistema de The Rock Gym Center</p>
        <p>Página 1 de 1</p>
    </div>
</body>
</html> 