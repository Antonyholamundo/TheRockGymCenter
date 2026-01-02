<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Usuarios - The Rock Gym Center</title>
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
            color: #6c757d;
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
        <p>Reporte de Usuarios</p>
        <p>Generado el: {{ date('d/m/Y H:i:s') }}</p>
    </div>

    <div class="summary">
        <div class="summary-item">
            <h4>Total Usuarios</h4>
            <div class="amount">{{ $totalUsuarios }}</div>
        </div>
        <div class="summary-item">
            <h4>Usuarios Activos</h4>
            <div class="amount">{{ $usuariosActivos }}</div>
        </div>
        <div class="summary-item">
            <h4>Usuarios Inactivos</h4>
            <div class="amount">{{ $usuariosInactivos }}</div>
        </div>
    </div>

    @if($usuarios->count() > 0)
    <table>
        <thead>
            <tr>
                <th>Cédula</th>
                <th>Nombres</th>
                <th>Apellidos</th>
                <th>Email</th>
                <th>Teléfono</th>
                <th>Fecha Nacimiento</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($usuarios as $usuario)
            <tr>
                <td>{{ $usuario->cedula }}</td>
                <td>{{ $usuario->nombres }}</td>
                <td>{{ $usuario->apellidos }}</td>
                <td>{{ $usuario->email }}</td>
                <td>{{ $usuario->telefono }}</td>
                <td>{{ $usuario->fecha_nacimiento ? date('d/m/Y', strtotime($usuario->fecha_nacimiento)) : 'No especificada' }}</td>
                <td class="{{ $usuario->estado == 'Activo' ? 'status-active' : 'status-inactive' }}">
                    {{ $usuario->estado }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div style="text-align: center; padding: 40px; color: #666;">
        <h3>No se encontraron usuarios</h3>
    </div>
    @endif

    <div class="footer">
        <p>Este reporte fue generado automáticamente por el sistema de The Rock Gym Center</p>
        <p>Página 1 de 1</p>
    </div>
</body>
</html> 