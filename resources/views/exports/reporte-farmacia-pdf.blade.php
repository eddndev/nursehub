<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Farmacia</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
        }
        .header p {
            margin: 5px 0 0 0;
            color: #666;
        }
        .info-box {
            background-color: #f5f5f5;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
        }
        .info-box table {
            width: 100%;
        }
        .info-box td {
            padding: 5px;
        }
        .info-box .label {
            font-weight: bold;
            color: #666;
        }
        .info-box .value {
            text-align: right;
            font-weight: bold;
            color: #333;
        }
        table.data {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table.data th {
            background-color: #374151;
            color: white;
            padding: 8px;
            text-align: left;
            font-size: 11px;
        }
        table.data td {
            border-bottom: 1px solid #ddd;
            padding: 8px;
            font-size: 11px;
        }
        table.data tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
        }
        .badge-red {
            background-color: #fee2e2;
            color: #991b1b;
        }
        .badge-green {
            background-color: #dcfce7;
            color: #166534;
        }
        .badge-yellow {
            background-color: #fef9c3;
            color: #854d0e;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Reporte de Farmacia</h1>
        <p>
            @switch($tipoReporte)
                @case('consumo') Consumo por Medicamento @break
                @case('costos') Costos por Área @break
                @case('desperdicios') Desperdicios y Mermas @break
                @case('controlados') Medicamentos Controlados @break
                @case('inventario') Estado de Inventario @break
                @case('movimientos') Historial de Movimientos @break
            @endswitch
        </p>
        <p>Período: {{ $fechaInicio }} al {{ $fechaFin }}</p>
    </div>

    <div class="info-box">
        <table>
            <tr>
                @if ($tipoReporte === 'consumo')
                    <td class="label">Total Medicamentos:</td>
                    <td class="value">{{ $totales['total_medicamentos'] ?? 0 }}</td>
                    <td class="label">Unidades Consumidas:</td>
                    <td class="value">{{ number_format($totales['total_unidades'] ?? 0) }}</td>
                    <td class="label">Costo Total:</td>
                    <td class="value">${{ number_format($totales['total_costo'] ?? 0, 2) }}</td>
                @elseif ($tipoReporte === 'costos')
                    <td class="label">Total Áreas:</td>
                    <td class="value">{{ $totales['total_areas'] ?? 0 }}</td>
                    <td class="label">Costo Total:</td>
                    <td class="value">${{ number_format($totales['total_costo'] ?? 0, 2) }}</td>
                    <td class="label">Total Unidades:</td>
                    <td class="value">{{ number_format($totales['total_unidades'] ?? 0) }}</td>
                @elseif ($tipoReporte === 'desperdicios')
                    <td class="label">Caducados:</td>
                    <td class="value">{{ $totales['total_caducados'] ?? 0 }}</td>
                    <td class="label">Mermas:</td>
                    <td class="value">{{ $totales['total_mermas'] ?? 0 }}</td>
                    <td class="label">Valor Perdido:</td>
                    <td class="value">${{ number_format($totales['total_valor_perdido'] ?? 0, 2) }}</td>
                @elseif ($tipoReporte === 'controlados')
                    <td class="label">Total Operaciones:</td>
                    <td class="value">{{ $totales['total_operaciones'] ?? 0 }}</td>
                    <td class="label">Salidas:</td>
                    <td class="value">{{ $totales['total_salidas'] ?? 0 }}</td>
                    <td class="label">Entradas:</td>
                    <td class="value">{{ $totales['total_entradas'] ?? 0 }}</td>
                @elseif ($tipoReporte === 'inventario')
                    <td class="label">Medicamentos:</td>
                    <td class="value">{{ $totales['total_medicamentos'] ?? 0 }}</td>
                    <td class="label">Valor Inventario:</td>
                    <td class="value">${{ number_format($totales['valor_total_inventario'] ?? 0, 2) }}</td>
                    <td class="label">Stock Bajo:</td>
                    <td class="value">{{ $totales['medicamentos_stock_bajo'] ?? 0 }}</td>
                @elseif ($tipoReporte === 'movimientos')
                    <td class="label">Total:</td>
                    <td class="value">{{ $totales['total_movimientos'] ?? 0 }}</td>
                    <td class="label">Entradas:</td>
                    <td class="value">{{ $totales['entradas'] ?? 0 }}</td>
                    <td class="label">Salidas:</td>
                    <td class="value">{{ $totales['salidas'] ?? 0 }}</td>
                @endif
            </tr>
        </table>
    </div>

    <table class="data">
        @if ($tipoReporte === 'consumo')
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Medicamento</th>
                    <th>Categoría</th>
                    <th class="text-right">Cantidad</th>
                    <th class="text-right">Costo Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($datos as $fila)
                    <tr>
                        <td>{{ $fila['codigo'] }}</td>
                        <td>{{ $fila['nombre'] }}</td>
                        <td>{{ $fila['categoria'] }}</td>
                        <td class="text-right">{{ number_format($fila['cantidad_total']) }}</td>
                        <td class="text-right">${{ number_format($fila['costo_total'], 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        @elseif ($tipoReporte === 'costos')
            <thead>
                <tr>
                    <th>Área</th>
                    <th class="text-right">Costo Total</th>
                    <th class="text-right">Unidades</th>
                    <th class="text-right">Costo Promedio</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($datos as $fila)
                    <tr>
                        <td>{{ $fila['area'] }}</td>
                        <td class="text-right">${{ number_format($fila['total_costo'], 2) }}</td>
                        <td class="text-right">{{ number_format($fila['total_unidades']) }}</td>
                        <td class="text-right">${{ number_format($fila['costo_promedio_unidad'], 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        @elseif ($tipoReporte === 'desperdicios')
            <thead>
                <tr>
                    <th>Tipo</th>
                    <th>Medicamento</th>
                    <th>Lote</th>
                    <th class="text-right">Cantidad</th>
                    <th class="text-right">Valor Perdido</th>
                    <th>Fecha</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($datos as $fila)
                    <tr>
                        <td><span class="badge {{ $fila['tipo'] === 'Caducado' ? 'badge-red' : 'badge-yellow' }}">{{ $fila['tipo'] }}</span></td>
                        <td>{{ $fila['medicamento'] }}</td>
                        <td>{{ $fila['lote'] }}</td>
                        <td class="text-right">{{ $fila['cantidad'] }}</td>
                        <td class="text-right">${{ number_format($fila['valor_perdido'], 2) }}</td>
                        <td>{{ $fila['fecha'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        @elseif ($tipoReporte === 'controlados')
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Medicamento</th>
                    <th>Operación</th>
                    <th class="text-right">Cantidad</th>
                    <th>Paciente</th>
                    <th>Receta</th>
                    <th>Usuario</th>
                    <th>Autorizado</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($datos as $fila)
                    <tr>
                        <td>{{ $fila['fecha'] }}</td>
                        <td>{{ $fila['medicamento'] }}</td>
                        <td><span class="badge {{ $fila['tipo_operacion'] === 'Salida' ? 'badge-red' : 'badge-green' }}">{{ $fila['tipo_operacion'] }}</span></td>
                        <td class="text-right">{{ $fila['cantidad'] }}</td>
                        <td>{{ $fila['paciente'] ?? '-' }}</td>
                        <td>{{ $fila['numero_receta'] ?? '-' }}</td>
                        <td>{{ $fila['usuario'] }}</td>
                        <td>{{ $fila['autorizado_por'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        @elseif ($tipoReporte === 'inventario')
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Medicamento</th>
                    <th class="text-right">Stock</th>
                    <th class="text-right">Mínimo</th>
                    <th class="text-right">Valor</th>
                    <th>Próx. Cad.</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($datos as $fila)
                    <tr>
                        <td>{{ $fila['codigo'] }} @if($fila['es_controlado'])<span class="badge badge-red">C</span>@endif</td>
                        <td>{{ $fila['nombre'] }}</td>
                        <td class="text-right">{{ number_format($fila['stock_total']) }}</td>
                        <td class="text-right">{{ $fila['stock_minimo'] }}</td>
                        <td class="text-right">${{ number_format($fila['valor_inventario'], 2) }}</td>
                        <td>{{ $fila['proxima_caducidad'] }}</td>
                        <td><span class="badge {{ $fila['estado_stock'] === 'bajo' ? 'badge-red' : 'badge-green' }}">{{ $fila['estado_stock'] === 'bajo' ? 'BAJO' : 'Normal' }}</span></td>
                    </tr>
                @endforeach
            </tbody>
        @elseif ($tipoReporte === 'movimientos')
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Tipo</th>
                    <th>Medicamento</th>
                    <th>Lote</th>
                    <th class="text-right">Cantidad</th>
                    <th>Referencia</th>
                    <th>Usuario</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($datos as $fila)
                    <tr>
                        <td>{{ $fila['fecha'] }}</td>
                        <td>{{ $fila['tipo'] }}</td>
                        <td>{{ $fila['medicamento'] }}</td>
                        <td>{{ $fila['lote'] }}</td>
                        <td class="text-right">{{ $fila['cantidad'] }}</td>
                        <td>{{ $fila['referencia'] ?? '-' }}</td>
                        <td>{{ $fila['usuario'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        @endif
    </table>

    <div class="footer">
        <p>Generado el {{ now()->format('d/m/Y H:i') }} | NurseHub - Sistema de Gestión Hospitalaria</p>
    </div>
</body>
</html>
