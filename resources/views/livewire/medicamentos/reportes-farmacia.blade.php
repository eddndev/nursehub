<div class="p-6">
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Reportes de Farmacia</h1>
                <p class="text-gray-600 mt-1">Análisis y estadísticas del módulo de medicamentos</p>
            </div>
            <div class="flex space-x-2">
                <button wire:click="exportarExcel" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                    <i class="fas fa-file-excel mr-2"></i>Excel
                </button>
                <button wire:click="exportarPdf" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                    <i class="fas fa-file-pdf mr-2"></i>PDF
                </button>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de Reporte</label>
                <select wire:model.live="tipoReporte" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    <option value="consumo">Consumo por Medicamento</option>
                    <option value="costos">Costos por Área</option>
                    <option value="desperdicios">Desperdicios y Mermas</option>
                    <option value="controlados">Medicamentos Controlados</option>
                    <option value="inventario">Estado de Inventario</option>
                    <option value="movimientos">Historial de Movimientos</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Fecha Inicio</label>
                <input type="date" wire:model.live="fechaInicio" class="w-full px-3 py-2 border border-gray-300 rounded-md">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Fecha Fin</label>
                <input type="date" wire:model.live="fechaFin" class="w-full px-3 py-2 border border-gray-300 rounded-md">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Área</label>
                <select wire:model.live="areaFiltro" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    <option value="">Todas las áreas</option>
                    @foreach ($areas as $area)
                        <option value="{{ $area->id }}">{{ $area->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
                <button wire:click="generarReporte" class="w-full px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    <i class="fas fa-sync-alt mr-2"></i>Actualizar
                </button>
            </div>
        </div>
    </div>

    <!-- Tarjetas de Resumen -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        @if ($tipoReporte === 'consumo')
            <div class="bg-white rounded-lg shadow p-4">
                <div class="text-sm font-medium text-gray-500">Medicamentos</div>
                <div class="text-2xl font-bold text-gray-900">{{ $totales['total_medicamentos'] ?? 0 }}</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <div class="text-sm font-medium text-gray-500">Unidades Consumidas</div>
                <div class="text-2xl font-bold text-blue-600">{{ number_format($totales['total_unidades'] ?? 0) }}</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <div class="text-sm font-medium text-gray-500">Costo Total</div>
                <div class="text-2xl font-bold text-green-600">${{ number_format($totales['total_costo'] ?? 0, 2) }}</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <div class="text-sm font-medium text-gray-500">Movimientos</div>
                <div class="text-2xl font-bold text-gray-900">{{ $totales['total_movimientos'] ?? 0 }}</div>
            </div>
        @elseif ($tipoReporte === 'costos')
            <div class="bg-white rounded-lg shadow p-4">
                <div class="text-sm font-medium text-gray-500">Áreas</div>
                <div class="text-2xl font-bold text-gray-900">{{ $totales['total_areas'] ?? 0 }}</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <div class="text-sm font-medium text-gray-500">Costo Total</div>
                <div class="text-2xl font-bold text-green-600">${{ number_format($totales['total_costo'] ?? 0, 2) }}</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <div class="text-sm font-medium text-gray-500">Unidades</div>
                <div class="text-2xl font-bold text-blue-600">{{ number_format($totales['total_unidades'] ?? 0) }}</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <div class="text-sm font-medium text-gray-500">Promedio por Área</div>
                <div class="text-2xl font-bold text-gray-900">
                    ${{ number_format(($totales['total_areas'] ?? 0) > 0 ? ($totales['total_costo'] ?? 0) / $totales['total_areas'] : 0, 2) }}
                </div>
            </div>
        @elseif ($tipoReporte === 'desperdicios')
            <div class="bg-white rounded-lg shadow p-4">
                <div class="text-sm font-medium text-gray-500">Caducados</div>
                <div class="text-2xl font-bold text-red-600">{{ $totales['total_caducados'] ?? 0 }}</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <div class="text-sm font-medium text-gray-500">Mermas</div>
                <div class="text-2xl font-bold text-orange-600">{{ $totales['total_mermas'] ?? 0 }}</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <div class="text-sm font-medium text-gray-500">Valor Perdido</div>
                <div class="text-2xl font-bold text-red-600">${{ number_format($totales['total_valor_perdido'] ?? 0, 2) }}</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <div class="text-sm font-medium text-gray-500">Valor por Caducidad</div>
                <div class="text-2xl font-bold text-gray-900">${{ number_format($totales['valor_perdido_caducados'] ?? 0, 2) }}</div>
            </div>
        @elseif ($tipoReporte === 'controlados')
            <div class="bg-white rounded-lg shadow p-4">
                <div class="text-sm font-medium text-gray-500">Operaciones</div>
                <div class="text-2xl font-bold text-gray-900">{{ $totales['total_operaciones'] ?? 0 }}</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <div class="text-sm font-medium text-gray-500">Salidas</div>
                <div class="text-2xl font-bold text-red-600">{{ $totales['total_salidas'] ?? 0 }}</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <div class="text-sm font-medium text-gray-500">Entradas</div>
                <div class="text-2xl font-bold text-green-600">{{ $totales['total_entradas'] ?? 0 }}</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <div class="text-sm font-medium text-gray-500">Medicamentos</div>
                <div class="text-2xl font-bold text-gray-900">{{ $totales['medicamentos_distintos'] ?? 0 }}</div>
            </div>
        @elseif ($tipoReporte === 'inventario')
            <div class="bg-white rounded-lg shadow p-4">
                <div class="text-sm font-medium text-gray-500">Medicamentos</div>
                <div class="text-2xl font-bold text-gray-900">{{ $totales['total_medicamentos'] ?? 0 }}</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <div class="text-sm font-medium text-gray-500">Unidades en Stock</div>
                <div class="text-2xl font-bold text-blue-600">{{ number_format($totales['total_unidades'] ?? 0) }}</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <div class="text-sm font-medium text-gray-500">Valor del Inventario</div>
                <div class="text-2xl font-bold text-green-600">${{ number_format($totales['valor_total_inventario'] ?? 0, 2) }}</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <div class="text-sm font-medium text-gray-500">Stock Bajo</div>
                <div class="text-2xl font-bold text-orange-600">{{ $totales['medicamentos_stock_bajo'] ?? 0 }}</div>
            </div>
        @elseif ($tipoReporte === 'movimientos')
            <div class="bg-white rounded-lg shadow p-4">
                <div class="text-sm font-medium text-gray-500">Total Movimientos</div>
                <div class="text-2xl font-bold text-gray-900">{{ $totales['total_movimientos'] ?? 0 }}</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <div class="text-sm font-medium text-gray-500">Entradas</div>
                <div class="text-2xl font-bold text-green-600">{{ $totales['entradas'] ?? 0 }}</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <div class="text-sm font-medium text-gray-500">Salidas</div>
                <div class="text-2xl font-bold text-red-600">{{ $totales['salidas'] ?? 0 }}</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <div class="text-sm font-medium text-gray-500">Transferencias</div>
                <div class="text-2xl font-bold text-blue-600">{{ $totales['transferencias'] ?? 0 }}</div>
            </div>
        @endif
    </div>

    <!-- Gráfico -->
    @if (!empty($chartData['data']))
        <div class="bg-white rounded-lg shadow p-4 mb-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">
                @switch($tipoReporte)
                    @case('consumo') Top 10 Medicamentos más Consumidos @break
                    @case('costos') Distribución de Costos por Área @break
                    @case('desperdicios') Desperdicios por Tipo @break
                    @case('controlados') Consumo de Controlados @break
                    @case('inventario') Estado del Stock @break
                    @case('movimientos') Movimientos por Tipo @break
                @endswitch
            </h3>
            <div class="h-64">
                <canvas id="reporteChart"></canvas>
            </div>
        </div>
    @endif

    <!-- Tabla de Datos -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b">
            <h3 class="text-lg font-medium text-gray-900">Detalle del Reporte</h3>
        </div>
        <div class="overflow-x-auto">
            @if ($tipoReporte === 'consumo')
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Código</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Medicamento</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Categoría</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Cantidad</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Costo Total</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Movimientos</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($datosReporte as $fila)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $fila['codigo'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $fila['nombre'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $fila['categoria'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">{{ number_format($fila['cantidad_total']) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">${{ number_format($fila['costo_total'], 2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">{{ $fila['movimientos'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-500">No hay datos para mostrar</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            @elseif ($tipoReporte === 'costos')
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Área</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Costo Total</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Unidades</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Costo Promedio</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Movimientos</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($datosReporte as $fila)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $fila['area'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">${{ number_format($fila['total_costo'], 2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">{{ number_format($fila['total_unidades']) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">${{ number_format($fila['costo_promedio_unidad'], 2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">{{ $fila['movimientos'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-500">No hay datos para mostrar</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            @elseif ($tipoReporte === 'desperdicios')
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Medicamento</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Lote</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Cantidad</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Valor Perdido</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Motivo</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($datosReporte as $fila)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs rounded-full {{ $fila['tipo'] === 'Caducado' ? 'bg-red-100 text-red-800' : 'bg-orange-100 text-orange-800' }}">
                                        {{ $fila['tipo'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $fila['medicamento'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $fila['lote'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">{{ $fila['cantidad'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600 text-right">${{ number_format($fila['valor_perdido'], 2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $fila['fecha'] }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate">{{ $fila['motivo'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-gray-500">No hay desperdicios en el período</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            @elseif ($tipoReporte === 'controlados')
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Medicamento</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Operación</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Cantidad</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Paciente</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Receta</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Usuario</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Autorizado por</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($datosReporte as $fila)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $fila['fecha'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $fila['medicamento'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="px-2 py-1 text-xs rounded-full {{ $fila['tipo_operacion'] === 'Salida' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                        {{ $fila['tipo_operacion'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">{{ $fila['cantidad'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $fila['paciente'] ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $fila['numero_receta'] ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $fila['usuario'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $fila['autorizado_por'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center text-gray-500">No hay registros de controlados</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            @elseif ($tipoReporte === 'inventario')
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Código</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Medicamento</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Stock</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Mínimo</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Valor</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Lotes</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Próx. Caducidad</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Estado</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($datosReporte as $fila)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $fila['codigo'] }}
                                    @if ($fila['es_controlado'])
                                        <span class="ml-1 px-1 py-0.5 text-xs bg-red-100 text-red-800 rounded">C</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $fila['nombre'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">{{ number_format($fila['stock_total']) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">{{ $fila['stock_minimo'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">${{ number_format($fila['valor_inventario'], 2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">{{ $fila['lotes'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm {{ $fila['dias_para_caducar'] <= 30 ? 'text-red-600' : 'text-gray-500' }}">
                                    {{ $fila['proxima_caducidad'] }}
                                    @if ($fila['dias_para_caducar'] <= 30)
                                        <span class="text-xs">({{ $fila['dias_para_caducar'] }}d)</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="px-2 py-1 text-xs rounded-full {{ $fila['estado_stock'] === 'bajo' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                        {{ $fila['estado_stock'] === 'bajo' ? 'Bajo' : 'Normal' }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center text-gray-500">No hay inventario</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            @elseif ($tipoReporte === 'movimientos')
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Medicamento</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Lote</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Cantidad</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Referencia</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Usuario</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($datosReporte as $fila)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $fila['fecha'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs rounded-full
                                        @if(str_contains(strtolower($fila['tipo']), 'entrada')) bg-green-100 text-green-800
                                        @elseif(str_contains(strtolower($fila['tipo']), 'salida')) bg-red-100 text-red-800
                                        @elseif(str_contains(strtolower($fila['tipo']), 'transferencia')) bg-blue-100 text-blue-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ $fila['tipo'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $fila['medicamento'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $fila['lote'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">{{ $fila['cantidad'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $fila['referencia'] ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $fila['usuario'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-gray-500">No hay movimientos</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('livewire:navigated', initChart);
    document.addEventListener('livewire:init', initChart);

    function initChart() {
        const chartData = @json($chartData);
        const ctx = document.getElementById('reporteChart');

        if (ctx && chartData.labels && chartData.data) {
            new Chart(ctx, {
                type: '{{ in_array($tipoReporte, ["desperdicios", "inventario"]) ? "pie" : "bar" }}',
                data: {
                    labels: chartData.labels,
                    datasets: [{
                        label: 'Cantidad',
                        data: chartData.data,
                        backgroundColor: [
                            '#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6',
                            '#EC4899', '#06B6D4', '#84CC16', '#F97316', '#6366F1'
                        ],
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                }
            });
        }
    }
</script>
@endpush
