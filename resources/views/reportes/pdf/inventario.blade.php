<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Inventario - The Rock Gym Center</title>
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
            color: #ffc107;
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
        .stock-low {
            color: #dc3545;
            font-weight: bold;
        }
        .stock-medium {
            color: #ffc107;
            font-weight: bold;
        }
        .stock-high {
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
        <p>Reporte de Inventario</p>
        <p>Generado el: {{ date('d/m/Y H:i:s') }}</p>
    </div>



    @if($productos->count() > 0)
    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Categoría</th>
                <th>Precio</th>
                <th>Stock</th>
                <th>Valor en Stock</th>
                <th>Estado</th>
                <th>Descripción</th>
            </tr>
        </thead>
        <tbody>
            @foreach($productos as $producto)
            <tr>
                <td>{{ $producto->nombre }}</td>
                <td>{{ $producto->categoria ? $producto->categoria->nombre : 'Sin categoría' }}</td>
                <td>${{ number_format($producto->precio, 2) }}</td>
                <td class="{{ $producto->stock <= 5 ? 'stock-low' : ($producto->stock <= 15 ? 'stock-medium' : 'stock-high') }}">
                    {{ $producto->stock }}
                </td>
                <td>${{ number_format($producto->precio * $producto->stock, 2) }}</td>
                <td class="{{ $producto->estado == 'Activo' ? 'status-active' : 'status-inactive' }}">
                    {{ $producto->estado }}
                </td>
                <td>{{ Str::limit($producto->descripcion, 50) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div style="text-align: center; padding: 40px; color: #666;">
        <h3>No se encontraron productos en el inventario</h3>
    </div>
    @endif

    <div class="footer">
        <p>Este reporte fue generado automáticamente por el sistema de The Rock Gym Center</p>
        <p>Página 1 de 1</p>
    </div>
</body>
</html> 