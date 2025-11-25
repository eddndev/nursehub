<div class="p-6">
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Despacho de Farmacia</h1>
                <p class="text-gray-600 mt-1">Gestiona las solicitudes de medicamentos pendientes de despacho</p>
            </div>
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

    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                <select wire:model.live="estadoFiltro" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    <option value="">Todos los estados</option>
                    <option value="pendiente">Pendiente</option>
                    <option value="aprobada">Aprobada</option>
                    <option value="despachada">Despachada</option>
                    <option value="rechazada">Rechazada</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Prioridad</label>
                <select wire:model.live="prioridadFiltro" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    <option value="">Todas las prioridades</option>
                    <option value="stat">STAT (Inmediato)</option>
                    <option value="urgente">Urgente</option>
                    <option value="normal">Normal</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Fecha Desde</label>
                <input type="date" wire:model.live="fechaDesde" class="w-full px-3 py-2 border border-gray-300 rounded-md">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Fecha Hasta</label>
                <input type="date" wire:model.live="fechaHasta" class="w-full px-3 py-2 border border-gray-300 rounded-md">
            </div>
        </div>
    </div>

    <!-- Tabla de Solicitudes -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Solicitud</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Paciente</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Solicitante</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Área</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Prioridad</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Estado</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Items</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($solicitudes as $solicitud)
                    @php
                        $tieneControlados = $solicitud->detalles->contains(fn($d) => $d->medicamento->es_controlado);
                    @endphp
                    <tr class="hover:bg-gray-50 {{ $solicitud->prioridad->value === 'stat' ? 'bg-red-50' : '' }}">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $solicitud->numero_solicitud }}</div>
                            <div class="text-xs text-gray-500">{{ $solicitud->fecha_solicitud->format('d/m/Y H:i') }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">
                                {{ $solicitud->paciente->nombre }} {{ $solicitud->paciente->apellido }}
                            </div>
                            <div class="text-xs text-gray-500">{{ $solicitud->paciente->numero_identificacion }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $solicitud->enfermero->user->name ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $solicitud->area->nombre ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @php
                                $prioridadClasses = [
                                    'stat' => 'bg-red-100 text-red-800',
                                    'urgente' => 'bg-orange-100 text-orange-800',
                                    'normal' => 'bg-blue-100 text-blue-800',
                                ];
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $prioridadClasses[$solicitud->prioridad->value] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ $solicitud->prioridad->getLabel() }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @php
                                $estadoClasses = [
                                    'pendiente' => 'bg-yellow-100 text-yellow-800',
                                    'aprobada' => 'bg-green-100 text-green-800',
                                    'despachada' => 'bg-blue-100 text-blue-800',
                                    'rechazada' => 'bg-red-100 text-red-800',
                                    'cancelada' => 'bg-gray-100 text-gray-800',
                                ];
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $estadoClasses[$solicitud->estado->value] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ $solicitud->estado->getLabel() }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="text-sm text-gray-900">{{ $solicitud->detalles->count() }}</span>
                            @if ($tieneControlados)
                                <span class="inline-flex items-center ml-1 px-1.5 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                                    <i class="fas fa-lock text-xs"></i>
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            @if ($solicitud->estado->value === 'pendiente')
                                <button wire:click="revisarSolicitud({{ $solicitud->id }})"
                                    class="text-blue-600 hover:text-blue-900 mr-2" title="Revisar">
                                    <i class="fas fa-eye"></i> Revisar
                                </button>
                            @elseif ($solicitud->estado->value === 'aprobada')
                                <button wire:click="abrirModalDespacho({{ $solicitud->id }})"
                                    class="text-green-600 hover:text-green-900" title="Despachar">
                                    <i class="fas fa-truck"></i> Despachar
                                </button>
                            @else
                                <button wire:click="revisarSolicitud({{ $solicitud->id }})"
                                    class="text-gray-600 hover:text-gray-900" title="Ver detalle">
                                    <i class="fas fa-eye"></i>
                                </button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                            <i class="fas fa-clipboard-list text-4xl mb-2"></i>
                            <p>No se encontraron solicitudes</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4 border-t">
            {{ $solicitudes->links() }}
        </div>
    </div>

    <!-- Modal Revisar Solicitud -->
    @if ($modalRevisar && $solicitudSeleccionada)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full mx-4 max-h-[90vh] overflow-y-auto">
                <div class="px-6 py-4 border-b flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-medium">Revisar Solicitud {{ $solicitudSeleccionada->numero_solicitud }}</h3>
                        <p class="text-sm text-gray-500">{{ $solicitudSeleccionada->fecha_solicitud->format('d/m/Y H:i') }}</p>
                    </div>
                    <button wire:click="cerrarModal" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <div class="p-6">
                    <!-- Información del Paciente y Solicitante -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="text-sm font-medium text-gray-500 mb-2">Paciente</h4>
                            <p class="text-sm font-medium text-gray-900">
                                {{ $solicitudSeleccionada->paciente->nombre }} {{ $solicitudSeleccionada->paciente->apellido }}
                            </p>
                            <p class="text-xs text-gray-500">ID: {{ $solicitudSeleccionada->paciente->numero_identificacion }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="text-sm font-medium text-gray-500 mb-2">Solicitante</h4>
                            <p class="text-sm font-medium text-gray-900">{{ $solicitudSeleccionada->enfermero->user->name ?? 'N/A' }}</p>
                            <p class="text-xs text-gray-500">Área: {{ $solicitudSeleccionada->area->nombre ?? 'N/A' }}</p>
                        </div>
                    </div>

                    <!-- Prioridad -->
                    <div class="mb-4">
                        @php
                            $prioridadClasses = [
                                'stat' => 'bg-red-100 text-red-800 border-red-300',
                                'urgente' => 'bg-orange-100 text-orange-800 border-orange-300',
                                'normal' => 'bg-blue-100 text-blue-800 border-blue-300',
                            ];
                        @endphp
                        <span class="inline-flex items-center px-3 py-1 rounded-md text-sm font-medium border {{ $prioridadClasses[$solicitudSeleccionada->prioridad->value] ?? 'bg-gray-100 text-gray-800' }}">
                            Prioridad: {{ $solicitudSeleccionada->prioridad->getLabel() }}
                        </span>
                    </div>

                    <!-- Alertas de Interacciones -->
                    @if (count($interaccionesMedicamentosas) > 0)
                        <div class="mb-4 p-4 bg-yellow-50 border border-yellow-300 rounded-lg">
                            <h4 class="text-sm font-medium text-yellow-800 mb-2">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                Alertas de Interacciones Medicamentosas
                            </h4>
                            <ul class="text-sm text-yellow-700 list-disc list-inside">
                                @foreach ($interaccionesMedicamentosas as $interaccion)
                                    <li>{{ $interaccion->descripcion ?? 'Interacción detectada' }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Medicamentos Solicitados -->
                    <div class="mb-6">
                        <h4 class="text-sm font-medium text-gray-700 mb-3">Medicamentos Solicitados</h4>
                        <div class="border rounded-lg overflow-hidden">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Medicamento</th>
                                        <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Cantidad</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Indicaciones</th>
                                        <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Tipo</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($solicitudSeleccionada->detalles as $detalle)
                                        <tr>
                                            <td class="px-4 py-3">
                                                <div class="text-sm font-medium text-gray-900">{{ $detalle->medicamento->nombre_comercial }}</div>
                                                <div class="text-xs text-gray-500">{{ $detalle->medicamento->nombre_generico }}</div>
                                                <div class="text-xs text-gray-400">{{ $detalle->medicamento->presentacion }} - {{ $detalle->medicamento->concentracion }}</div>
                                            </td>
                                            <td class="px-4 py-3 text-center text-sm text-gray-900">
                                                {{ $detalle->cantidad_solicitada }}
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-600">
                                                {{ $detalle->indicaciones_medicas ?? '-' }}
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                @if ($detalle->medicamento->es_controlado)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                                                        <i class="fas fa-lock mr-1"></i> Controlado
                                                    </span>
                                                @else
                                                    <span class="text-xs text-gray-400">Normal</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Observaciones -->
                    @if ($solicitudSeleccionada->observaciones)
                        <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                            <h4 class="text-sm font-medium text-gray-700 mb-1">Observaciones</h4>
                            <p class="text-sm text-gray-600">{{ $solicitudSeleccionada->observaciones }}</p>
                        </div>
                    @endif

                    <!-- Motivo de Rechazo (solo si estado es pendiente) -->
                    @if ($solicitudSeleccionada->estado->value === 'pendiente')
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Motivo de Rechazo (si aplica)</label>
                            <textarea wire:model="motivo_rechazo" rows="2"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md @error('motivo_rechazo') border-red-500 @enderror"
                                placeholder="Ingrese el motivo si va a rechazar la solicitud..."></textarea>
                            @error('motivo_rechazo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    @endif
                </div>

                <div class="px-6 py-4 border-t bg-gray-50 flex justify-end space-x-3">
                    <button wire:click="cerrarModal" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        Cerrar
                    </button>
                    @if ($solicitudSeleccionada->estado->value === 'pendiente')
                        <button wire:click="rechazarSolicitud" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                            <i class="fas fa-times mr-2"></i>Rechazar
                        </button>
                        <button wire:click="aprobarSolicitud" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                            <i class="fas fa-check mr-2"></i>Aprobar
                        </button>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <!-- Modal Despacho -->
    @if ($modalDespacho && $solicitudSeleccionada)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl max-w-5xl w-full mx-4 max-h-[90vh] overflow-y-auto">
                <div class="px-6 py-4 border-b flex justify-between items-center bg-green-50">
                    <div>
                        <h3 class="text-lg font-medium text-green-800">
                            <i class="fas fa-truck mr-2"></i>Despachar Solicitud {{ $solicitudSeleccionada->numero_solicitud }}
                        </h3>
                        <p class="text-sm text-green-600">
                            Paciente: {{ $solicitudSeleccionada->paciente->nombre }} {{ $solicitudSeleccionada->paciente->apellido }}
                        </p>
                    </div>
                    <button wire:click="cerrarModal" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <div class="p-6">
                    <!-- Información de la solicitud -->
                    <div class="grid grid-cols-3 gap-4 mb-6 text-sm">
                        <div class="bg-gray-50 rounded p-3">
                            <span class="text-gray-500">Área destino:</span>
                            <span class="font-medium ml-1">{{ $solicitudSeleccionada->area->nombre ?? 'N/A' }}</span>
                        </div>
                        <div class="bg-gray-50 rounded p-3">
                            <span class="text-gray-500">Solicitante:</span>
                            <span class="font-medium ml-1">{{ $solicitudSeleccionada->enfermero->user->name ?? 'N/A' }}</span>
                        </div>
                        <div class="bg-gray-50 rounded p-3">
                            <span class="text-gray-500">Prioridad:</span>
                            <span class="font-medium ml-1">{{ $solicitudSeleccionada->prioridad->getLabel() }}</span>
                        </div>
                    </div>

                    <!-- Tabla de despacho -->
                    <div class="mb-6">
                        <h4 class="text-sm font-medium text-gray-700 mb-3">Selección de Lotes (FIFO - Primero en Caducar)</h4>
                        <div class="border rounded-lg overflow-hidden">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Medicamento</th>
                                        <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Solicitado</th>
                                        <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Disponible</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Lote a Despachar</th>
                                        <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Cantidad</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($detallesDespacho as $index => $detalle)
                                        <tr class="{{ $detalle['stock_disponible'] < $detalle['cantidad_solicitada'] ? 'bg-yellow-50' : '' }}">
                                            <td class="px-4 py-3">
                                                <div class="text-sm font-medium text-gray-900">{{ $detalle['medicamento_nombre'] }}</div>
                                                @if ($detalle['stock_disponible'] < $detalle['cantidad_solicitada'])
                                                    <span class="text-xs text-yellow-600">
                                                        <i class="fas fa-exclamation-triangle mr-1"></i>Stock insuficiente
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 text-center text-sm font-medium text-gray-900">
                                                {{ $detalle['cantidad_solicitada'] }}
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <span class="text-sm {{ $detalle['stock_disponible'] < $detalle['cantidad_solicitada'] ? 'text-yellow-600 font-medium' : 'text-gray-900' }}">
                                                    {{ $detalle['stock_disponible'] }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3">
                                                <select wire:model="detallesDespacho.{{ $index }}.inventario_id"
                                                    class="w-full px-2 py-1 text-sm border border-gray-300 rounded-md">
                                                    <option value="">Seleccionar lote...</option>
                                                    @foreach ($detalle['inventarios_disponibles'] as $inv)
                                                        <option value="{{ $inv->id }}">
                                                            Lote: {{ $inv->lote }} | Stock: {{ $inv->cantidad_actual }} | Cad: {{ $inv->fecha_caducidad->format('d/m/Y') }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <input type="number"
                                                    wire:model="detallesDespacho.{{ $index }}.cantidad_despachada"
                                                    min="0"
                                                    max="{{ $detalle['stock_disponible'] }}"
                                                    class="w-20 px-2 py-1 text-sm text-center border border-gray-300 rounded-md">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Observaciones de despacho -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Observaciones del Despacho</label>
                        <textarea wire:model="observaciones_despacho" rows="2"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md"
                            placeholder="Observaciones adicionales del despacho..."></textarea>
                    </div>

                    <!-- Sección para medicamentos controlados -->
                    @if ($tieneControlados)
                        <div class="p-4 bg-red-50 border border-red-300 rounded-lg mb-4">
                            <h4 class="text-sm font-medium text-red-800 mb-2">
                                <i class="fas fa-lock mr-2"></i>Medicamentos Controlados - Información Requerida
                            </h4>
                            <p class="text-sm text-red-700 mb-4">
                                Esta solicitud contiene medicamentos controlados. Se requiere número de receta y doble verificación.
                            </p>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-red-800 mb-1">Número de Receta Médica *</label>
                                    <input type="text" wire:model="numero_receta"
                                        class="w-full px-3 py-2 border border-red-300 rounded-md bg-white @error('numero_receta') border-red-500 @enderror"
                                        placeholder="Ej: REC-2025-001234">
                                    @error('numero_receta') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-red-800 mb-1">Justificación Médica *</label>
                                    <input type="text" wire:model="justificacion_controlado"
                                        class="w-full px-3 py-2 border border-red-300 rounded-md bg-white @error('justificacion_controlado') border-red-500 @enderror"
                                        placeholder="Indicación médica para el uso">
                                    @error('justificacion_controlado') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            @if ($verificacionCompletada)
                                <div class="mt-3 p-2 bg-green-100 border border-green-300 rounded text-green-800 text-sm">
                                    <i class="fas fa-check-circle mr-1"></i> Verificación completada
                                </div>
                            @endif
                        </div>
                    @endif
                </div>

                <div class="px-6 py-4 border-t bg-gray-50 flex justify-end space-x-3">
                    <button wire:click="cerrarModal" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        Cancelar
                    </button>
                    @if ($tieneControlados)
                        <button wire:click="abrirDobleVerificacion" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                            <i class="fas fa-user-shield mr-2"></i>Verificar y Despachar
                        </button>
                    @else
                        <button wire:click="despacharSolicitud" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                            <i class="fas fa-check mr-2"></i>Confirmar Despacho
                        </button>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <!-- Modal Doble Verificación para Controlados -->
    @if ($modalDobleVerificacion)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
                <div class="px-6 py-4 border-b bg-red-50 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-medium text-red-800">
                            <i class="fas fa-user-shield mr-2"></i>Doble Verificación Requerida
                        </h3>
                        <p class="text-sm text-red-600">Medicamentos controlados</p>
                    </div>
                    <button wire:click="cerrarModal" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <div class="p-6">
                    <div class="mb-4 p-3 bg-yellow-50 border border-yellow-300 rounded-lg">
                        <p class="text-sm text-yellow-800">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            Por normativa, el despacho de medicamentos controlados requiere la verificación de un segundo usuario autorizado.
                        </p>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Correo del Verificador *
                            </label>
                            <input type="email" wire:model="verificadorEmail"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md @error('verificadorEmail') border-red-500 @enderror"
                                placeholder="correo@hospital.com">
                            @error('verificadorEmail') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Contraseña del Verificador *
                            </label>
                            <input type="password" wire:model="verificadorPassword"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md @error('verificadorPassword') border-red-500 @enderror"
                                placeholder="••••••••">
                            @error('verificadorPassword') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="text-xs text-gray-500">
                            <i class="fas fa-info-circle mr-1"></i>
                            El verificador debe ser un usuario diferente con rol de farmacéutico o superior.
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4 border-t bg-gray-50 flex justify-end space-x-3">
                    <button wire:click="$set('modalDobleVerificacion', false)"
                        class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        Cancelar
                    </button>
                    <button wire:click="verificarSegundoUsuario"
                        class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                        <i class="fas fa-check-double mr-2"></i>Verificar y Continuar
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
