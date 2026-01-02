<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte General - The Rock Gym Center</title>
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
            margin-bottom: 30px;
            flex-wrap: wrap;
        }
        .summary-item {
            background-color: #e9ecef;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            min-width: 180px;
            margin: 5px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .summary-item h4 {
            margin: 0 0 15px 0;
            color: #333;
            font-size: 14px;
        }
        .summary-item .amount {
            font-size: 24px;
            font-weight: bold;
        }
        .summary-item.ventas .amount {
            color: #28a745;
        }
        .summary-item.ingresos .amount {
            color: #17a2b8;
        }
        .summary-item.productos .amount {
            color: #ffc107;
        }
        .summary-item.usuarios .amount {
            color: #6c757d;
        }
        .summary-item.membresias .amount {
            color: #dc3545;
        }
        .summary-item.financiero .amount {
            color: #6610f2;
        }
        .section {
            margin-bottom: 30px;
        }
        .section h3 {
            color: #333;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .metrics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .metric-card {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #333;
        }
        .metric-card h4 {
            margin: 0 0 10px 0;
            color: #333;
        }
        .metric-value {
            font-size: 28px;
            font-weight: bold;
            color: #333;
        }
        .metric-description {
            color: #666;
            font-size: 14px;
            margin-top: 5px;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            color: #666;
            font-size: 12px;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>THE ROCK GYM CENTER</h1>
        <p>Reporte General del Sistema</p>
        <p>Generado el: {{ date('d/m/Y H:i:s') }}</p>
    </div>

    <div class="summary">
        <div class="summary-item ventas">
            <h4>Total Ventas</h4>
            <div class="amount">{{ $totalVentas }}</div>
        </div>
        <div class="summary-item ingresos">
            <h4>Total Ingresos</h4>
            <div class="amount">{{ $totalIngresos }}</div>
        </div>
        <div class="summary-item productos">
            <h4>Total Productos</h4>
            <div class="amount">{{ $totalProductos }}</div>
        </div>
        <div class="summary-item usuarios">
            <h4>Total Usuarios</h4>
            <div class="amount">{{ $totalUsuarios }}</div>
        </div>
        <div class="summary-item membresias">
            <h4>Total Membresías</h4>
            <div class="amount">{{ $totalMembresias }}</div>
        </div>
    </div>

    <div class="section">
        <h3>Métricas Financieras</h3>
        <div class="metrics-grid">
            <div class="metric-card">
                <h4>Ingresos por Ventas</h4>
                <div class="metric-value">${{ number_format($ingresosVentas, 2) }}</div>
                <div class="metric-description">Total de ingresos generados por ventas de productos</div>
            </div>
            <div class="metric-card">
                <h4>Ingresos por Membresías</h4>
                <div class="metric-value">${{ number_format($ingresosMembresias, 2) }}</div>
                <div class="metric-description">Total de ingresos generados por membresías</div>
            </div>
            <div class="metric-card">
                <h4>Valor del Inventario</h4>
                <div class="metric-value">${{ number_format($valorInventario, 2) }}</div>
                <div class="metric-description">Valor total del inventario actual</div>
            </div>
            <div class="metric-card">
                <h4>Ingresos Totales</h4>
                <div class="metric-value">${{ number_format($ingresosVentas + $ingresosMembresias, 2) }}</div>
                <div class="metric-description">Suma de ingresos por ventas y membresías</div>
            </div>
        </div>
    </div>

    <div class="section">
        <h3>Resumen de Actividad</h3>
        <div class="metrics-grid">
            <div class="metric-card">
                <h4>Productos en Inventario</h4>
                <div class="metric-value">{{ $totalProductos }}</div>
                <div class="metric-description">Cantidad total de productos registrados</div>
            </div>
            <div class="metric-card">
                <h4>Usuarios Registrados</h4>
                <div class="metric-value">{{ $totalUsuarios }}</div>
                <div class="metric-description">Total de usuarios en el sistema</div>
            </div>
            <div class="metric-card">
                <h4>Membresías Activas</h4>
                <div class="metric-value">{{ $totalMembresias }}</div>
                <div class="metric-description">Total de membresías de usuarios</div>
            </div>
            <div class="metric-card">
                <h4>Registros de Ingreso</h4>
                <div class="metric-value">{{ $totalIngresos }}</div>
                <div class="metric-description">Total de registros de entrada al gimnasio</div>
            </div>
        </div>
    </div>

    <div class="section">
        <h3>Análisis de Rendimiento</h3>
        <div class="metrics-grid">
            <div class="metric-card">
                <h4>Promedio de Ventas</h4>
                <div class="metric-value">${{ $totalVentas > 0 ? number_format($ingresosVentas / $totalVentas, 2) : '0.00' }}</div>
                <div class="metric-description">Promedio por venta</div>
            </div>
            <div class="metric-card">
                <h4>Promedio de Membresías</h4>
                <div class="metric-value">${{ $totalMembresias > 0 ? number_format($ingresosMembresias / $totalMembresias, 2) : '0.00' }}</div>
                <div class="metric-description">Promedio por membresía</div>
            </div>
            <div class="metric-card">
                <h4>Valor Promedio Producto</h4>
                <div class="metric-value">${{ $totalProductos > 0 ? number_format($valorInventario / $totalProductos, 2) : '0.00' }}</div>
                <div class="metric-description">Valor promedio por producto en inventario</div>
            </div>
            <div class="metric-card">
                <h4>Ingresos por Usuario</h4>
                <div class="metric-value">${{ $totalUsuarios > 0 ? number_format(($ingresosVentas + $ingresosMembresias) / $totalUsuarios, 2) : '0.00' }}</div>
                <div class="metric-description">Ingresos promedio por usuario</div>
            </div>
        </div>
    </div>

    <div class="footer">
        <p><strong>THE ROCK GYM CENTER</strong></p>
        <p>Este reporte fue generado automáticamente por el sistema</p>
        <p>Fecha de generación: {{ date('d/m/Y H:i:s') }}</p>
        <p>Página 1 de 1</p>
    </div>
</body>
</html> 