<div class="p-6">
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Gestor de Inventario</h1>
                <p class="text-gray-600 mt-1">Control de stock, lotes y movimientos de medicamentos</p>
            </div>
            <button wire:click="abrirModalEntrada" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                <i class="fas fa-box mr-2"></i>Registrar Entrada
            </button>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-md flex justify-between items-center">
            <span>{{ session('message') }}</span>
            <button onclick="this.parentElement.remove()" class="text-green-700 hover:text-green-900">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-md flex justify-between items-center">
            <span>{{ session('error') }}</span>
            <button onclick="this.parentElement.remove()" class="text-red-700 hover:text-red-900">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-sm font-medium text-gray-500">Alertas Totales</div>
            <div class="text-2xl font-bold text-gray-900">{{ $resumenAlertas['total_alertas'] }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-sm font-medium text-gray-500">Próximos a Caducar</div>
            <div class="text-2xl font-bold text-yellow-600">{{ $resumenAlertas['proximos_caducar']['count'] }}</div>
            <div class="text-xs text-gray-500 mt-1">{{ $resumenAlertas['proximos_caducar']['urgentes'] }} urgentes</div>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-sm font-medium text-gray-500">Stock Bajo</div>
            <div class="text-2xl font-bold text-orange-600">{{ $resumenAlertas['stock_bajo']['count'] }}</div>
            <div class="text-xs text-gray-500 mt-1">{{ $resumenAlertas['stock_bajo']['criticos'] }} críticos</div>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-sm font-medium text-gray-500">Caducados</div>
            <div class="text-2xl font-bold text-red-600">{{ $resumenAlertas['caducados']['count'] }}</div>
            <div class="text-xs text-gray-500 mt-1">${{ number_format($resumenAlertas['caducados']['valor_perdido'], 2) }} perdidos</div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div class="md:col-span-2">
                <input type="text" wire:model.live="busqueda" placeholder="Buscar medicamento..."
                    class="w-full px-3 py-2 border border-gray-300 rounded-md">
            </div>
            <div>
                <select wire:model.live="areaFiltro" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    <option value="">Todas las áreas</option>
                    <option value="almacen">Almacén General</option>
                    @foreach ($areas as $area)
                        <option value="{{ $area->id }}">{{ $area->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <select wire:model.live="estadoFiltro" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    <option value="">Todos los estados</option>
                    @foreach ($estados as $estado)
                        <option value="{{ $estado->value }}">{{ $estado->getLabel() }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <label class="flex items-center space-x-1">
                    <input type="checkbox" wire:model.live="soloStockBajo" class="rounded">
                    <span class="text-xs text-gray-700">Stock Bajo</span>
                </label>
                <label class="flex items-center space-x-1">
                    <input type="checkbox" wire:model.live="soloProximoCaducar" class="rounded">
                    <span class="text-xs text-gray-700">Prox. Caducar</span>
                </label>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Medicamento</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Lote</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Área</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Stock</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Caducidad</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Estado</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($inventario as $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $item->medicamento->nombre_comercial }}</div>
                            <div class="text-xs text-gray-500">{{ $item->medicamento->codigo_medicamento }}</div>
                            @if ($item->medicamento->es_controlado)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800 mt-1">
                                    <i class="fas fa-lock mr-1"></i> Controlado
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $item->lote }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $item->area ? $item->area->nombre : 'Almacén General' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <div class="text-sm font-medium text-gray-900">{{ $item->cantidad_actual }}</div>
                            @if ($item->estaBajoMinimo())
                                <span class="text-xs text-red-600">Min: {{ $item->stock_minimo }}</span>
                            @else
                                <span class="text-xs text-gray-400">Min: {{ $item->stock_minimo }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $item->fecha_caducidad->format('d/m/Y') }}</div>
                            @if ($item->estaCercaCaducidad())
                                @php
                                    $dias = now()->diffInDays($item->fecha_caducidad);
                                @endphp
                                <span class="text-xs text-yellow-600">{{ $dias }} días restantes</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                style="background-color: {{ $item->estado->getColor() }}20; color: {{ $item->estado->getColor() }};">
                                {{ $item->estado->getLabel() }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <button wire:click="abrirModalAjuste({{ $item->id }})"
                                class="text-blue-600 hover:text-blue-900 mr-2"
                                title="Ajustar">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button wire:click="abrirModalTransferencia({{ $item->id }})"
                                class="text-green-600 hover:text-green-900 mr-2"
                                title="Transferir">
                                <i class="fas fa-exchange-alt"></i>
                            </button>
                            @if ($item->fecha_caducidad < now() && $item->estado !== \App\Enums\EstadoInventarioMedicamento::CADUCADO)
                                <button wire:click="marcarCaducado({{ $item->id }})"
                                    class="text-red-600 hover:text-red-900"
                                    title="Marcar como caducado">
                                    <i class="fas fa-ban"></i>
                                </button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                            <i class="fas fa-boxes text-4xl mb-2"></i>
                            <p>No se encontró inventario</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4 border-t">
            {{ $inventario->links() }}
        </div>
    </div>

    @if ($modalEntrada)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4">
                <div class="px-6 py-4 border-b flex justify-between items-center">
                    <h3 class="text-lg font-medium">Registrar Entrada de Inventario</h3>
                    <button wire:click="cerrarModal" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Medicamento *</label>
                        <select wire:model="medicamento_id" class="w-full px-3 py-2 border border-gray-300 rounded-md @error('medicamento_id') border-red-500 @enderror">
                            <option value="">Seleccionar medicamento...</option>
                            @foreach ($medicamentos as $med)
                                <option value="{{ $med->id }}">{{ $med->nombre_comercial }} - {{ $med->presentacion }}</option>
                            @endforeach
                        </select>
                        @error('medicamento_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Área de Destino</label>
                        <select wire:model="area_id" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                            <option value="">Almacén General</option>
                            @foreach ($areas as $area)
                                <option value="{{ $area->id }}">{{ $area->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Lote *</label>
                            <input type="text" wire:model="lote" class="w-full px-3 py-2 border border-gray-300 rounded-md @error('lote') border-red-500 @enderror" placeholder="Ej: L2025001">
                            @error('lote') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Fecha de Caducidad *</label>
                            <input type="date" wire:model="fecha_caducidad" class="w-full px-3 py-2 border border-gray-300 rounded-md @error('fecha_caducidad') border-red-500 @enderror">
                            @error('fecha_caducidad') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Cantidad *</label>
                            <input type="number" wire:model="cantidad" class="w-full px-3 py-2 border border-gray-300 rounded-md @error('cantidad') border-red-500 @enderror">
                            @error('cantidad') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Stock Mínimo *</label>
                            <input type="number" wire:model="stock_minimo" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Stock Máximo</label>
                            <input type="number" wire:model="stock_maximo" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Costo Unitario *</label>
                            <input type="number" step="0.01" wire:model="costo_unitario" class="w-full px-3 py-2 border border-gray-300 rounded-md @error('costo_unitario') border-red-500 @enderror">
                            @error('costo_unitario') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Referencia (Factura)</label>
                            <input type="text" wire:model="referencia" class="w-full px-3 py-2 border border-gray-300 rounded-md" placeholder="Ej: FAC-2025-001">
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4 border-t bg-gray-50 flex justify-end space-x-3">
                    <button wire:click="cerrarModal" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        Cancelar
                    </button>
                    <button wire:click="registrarEntrada" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Registrar Entrada
                    </button>
                </div>
            </div>
        </div>
    @endif

    @if ($modalAjuste)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
                <div class="px-6 py-4 border-b flex justify-between items-center">
                    <h3 class="text-lg font-medium">Ajustar Inventario</h3>
                    <button wire:click="cerrarModal" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nueva Cantidad *</label>
                        <input type="number" wire:model="cantidad_movimiento" class="w-full px-3 py-2 border border-gray-300 rounded-md @error('cantidad_movimiento') border-red-500 @enderror">
                        @error('cantidad_movimiento') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Motivo del Ajuste *</label>
                        <textarea wire:model="motivo" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md @error('motivo') border-red-500 @enderror" placeholder="Ej: Conteo físico, merma, corrección de error..."></textarea>
                        @error('motivo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="px-6 py-4 border-t bg-gray-50 flex justify-end space-x-3">
                    <button wire:click="cerrarModal" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        Cancelar
                    </button>
                    <button wire:click="registrarAjuste" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Guardar Ajuste
                    </button>
                </div>
            </div>
        </div>
    @endif

    @if ($modalTransferencia)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
                <div class="px-6 py-4 border-b flex justify-between items-center">
                    <h3 class="text-lg font-medium">Transferir Stock</h3>
                    <button wire:click="cerrarModal" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Cantidad a Transferir *</label>
                        <input type="number" wire:model="cantidad_movimiento" class="w-full px-3 py-2 border border-gray-300 rounded-md @error('cantidad_movimiento') border-red-500 @enderror">
                        @error('cantidad_movimiento') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Área Destino *</label>
                        <select wire:model="area_destino_id" class="w-full px-3 py-2 border border-gray-300 rounded-md @error('area_destino_id') border-red-500 @enderror">
                            <option value="">Seleccionar área...</option>
                            @foreach ($areas as $area)
                                <option value="{{ $area->id }}">{{ $area->nombre }}</option>
                            @endforeach
                        </select>
                        @error('area_destino_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Motivo</label>
                        <textarea wire:model="motivo" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-md" placeholder="Motivo de la transferencia..."></textarea>
                    </div>
                </div>

                <div class="px-6 py-4 border-t bg-gray-50 flex justify-end space-x-3">
                    <button wire:click="cerrarModal" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        Cancelar
                    </button>
                    <button wire:click="registrarTransferencia" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Transferir
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
