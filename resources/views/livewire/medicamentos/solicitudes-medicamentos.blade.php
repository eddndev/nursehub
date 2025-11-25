<div>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Solicitudes de Medicamentos</h2>
                <button wire:click="abrirModalNueva" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg shadow">
                    Nueva Solicitud
                </button>
            </div>

            <!-- Flash Messages -->
            @if (session()->has('message'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('message') }}</span>
                </div>
            @endif

            @if (session()->has('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <!-- Filters -->
            <div class="bg-white rounded-lg shadow p-4 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                        <select wire:model.live="estadoFiltro" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                            <option value="">Todos los estados</option>
                            @foreach($estados as $estado)
                                <option value="{{ $estado->value }}">{{ $estado->label() }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Prioridad</label>
                        <select wire:model.live="prioridadFiltro" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                            <option value="">Todas las prioridades</option>
                            @foreach($prioridades as $prioridad)
                                <option value="{{ $prioridad->value }}">{{ $prioridad->label() }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Fecha Desde</label>
                        <input type="date" wire:model.live="fechaDesde" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Fecha Hasta</label>
                        <input type="date" wire:model.live="fechaHasta" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Número</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Paciente</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Área</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prioridad</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Medicamentos</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($solicitudes as $solicitud)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $solicitud->numero_solicitud }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $solicitud->paciente->nombre }} {{ $solicitud->paciente->apellido }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $solicitud->area->nombre ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    @php
                                        $prioridadColors = [
                                            'urgente' => 'bg-red-100 text-red-800',
                                            'alta' => 'bg-orange-100 text-orange-800',
                                            'normal' => 'bg-blue-100 text-blue-800',
                                            'baja' => 'bg-gray-100 text-gray-800',
                                        ];
                                        $color = $prioridadColors[$solicitud->prioridad->value] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $color }}">
                                        {{ $solicitud->prioridad->label() }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    @php
                                        $estadoColors = [
                                            'pendiente' => 'bg-yellow-100 text-yellow-800',
                                            'aprobada' => 'bg-green-100 text-green-800',
                                            'despachada' => 'bg-blue-100 text-blue-800',
                                            'completada' => 'bg-indigo-100 text-indigo-800',
                                            'rechazada' => 'bg-red-100 text-red-800',
                                            'cancelada' => 'bg-gray-100 text-gray-800',
                                        ];
                                        $estadoColor = $estadoColors[$solicitud->estado->value] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $estadoColor }}">
                                        {{ $solicitud->estado->label() }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $solicitud->fecha_solicitud->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $solicitud->detalles->count() }} medicamento(s)
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button wire:click="verDetalle({{ $solicitud->id }})" class="text-blue-600 hover:text-blue-900 mr-3">
                                        Ver Detalle
                                    </button>
                                    @if(in_array($solicitud->estado->value, ['pendiente', 'aprobada']))
                                        <button wire:click="cancelarSolicitud({{ $solicitud->id }})" wire:confirm="¿Está seguro de cancelar esta solicitud?" class="text-red-600 hover:text-red-900">
                                            Cancelar
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                                    No se encontraron solicitudes
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $solicitudes->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Nueva Solicitud -->
    @if ($modalNueva)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full mx-4 max-h-[90vh] overflow-y-auto">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold text-gray-900">Nueva Solicitud de Medicamentos</h3>
                        <button wire:click="cerrarModal" class="text-gray-400 hover:text-gray-500">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <form wire:submit.prevent="crearSolicitud">
                        <!-- Información de la Solicitud -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Paciente *</label>
                                <select wire:model="paciente_id" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                                    <option value="">Seleccionar paciente</option>
                                    @foreach($pacientes as $paciente)
                                        <option value="{{ $paciente->id }}">{{ $paciente->nombre }} {{ $paciente->apellido }} - {{ $paciente->numero_identificacion }}</option>
                                    @endforeach
                                </select>
                                @error('paciente_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Área</label>
                                <select wire:model="area_id" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                                    <option value="">Almacén Central</option>
                                    @foreach($areas as $area)
                                        <option value="{{ $area->id }}">{{ $area->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Prioridad *</label>
                                <select wire:model="prioridad" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                                    @foreach($prioridades as $prioridad)
                                        <option value="{{ $prioridad->value }}">{{ $prioridad->label() }}</option>
                                    @endforeach
                                </select>
                                @error('prioridad') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Agregar Medicamentos -->
                        <div class="border-t border-gray-200 pt-4 mb-6">
                            <h4 class="text-md font-semibold text-gray-800 mb-3">Agregar Medicamentos</h4>

                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Medicamento</label>
                                    <select wire:model="medicamento_id_temp" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                                        <option value="">Seleccionar medicamento</option>
                                        @foreach($medicamentos as $medicamento)
                                            <option value="{{ $medicamento->id }}">{{ $medicamento->nombre_comercial }} - {{ $medicamento->nombre_generico }}</option>
                                        @endforeach
                                    </select>
                                    @error('medicamento_id_temp') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Cantidad</label>
                                    <input type="number" wire:model="cantidad_temp" min="1" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                                    @error('cantidad_temp') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">&nbsp;</label>
                                    <button type="button" wire:click="agregarMedicamento" class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-md">
                                        Agregar
                                    </button>
                                </div>
                            </div>

                            <div class="md:col-span-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Indicaciones Médicas</label>
                                <textarea wire:model="indicaciones_temp" rows="2" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" placeholder="Ej: Cada 8 horas por 7 días"></textarea>
                            </div>

                            <!-- Lista de Medicamentos Agregados -->
                            @if(count($medicamentosAgregados) > 0)
                                <div class="mt-4">
                                    <h5 class="text-sm font-semibold text-gray-700 mb-2">Medicamentos en la Solicitud:</h5>
                                    <div class="border rounded-md divide-y">
                                        @foreach($medicamentosAgregados as $index => $item)
                                            <div class="p-3 flex justify-between items-center">
                                                <div class="flex-1">
                                                    <p class="text-sm font-medium text-gray-900">{{ $item['medicamento_nombre'] }}</p>
                                                    <p class="text-xs text-gray-500">Cantidad: {{ $item['cantidad'] }}</p>
                                                    @if(!empty($item['indicaciones']))
                                                        <p class="text-xs text-gray-600 mt-1">{{ $item['indicaciones'] }}</p>
                                                    @endif
                                                </div>
                                                <button type="button" wire:click="quitarMedicamento({{ $index }})" class="text-red-600 hover:text-red-800 ml-4">
                                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <div class="mt-4 p-4 bg-gray-50 rounded-md text-center text-gray-500 text-sm">
                                    No hay medicamentos agregados. Agregue al menos uno para continuar.
                                </div>
                            @endif
                            @error('medicamentosAgregados') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Observaciones -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Observaciones</label>
                            <textarea wire:model="observaciones" rows="3" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" placeholder="Observaciones adicionales sobre la solicitud"></textarea>
                        </div>

                        <!-- Actions -->
                        <div class="flex justify-end space-x-3">
                            <button type="button" wire:click="cerrarModal" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded-lg">
                                Cancelar
                            </button>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg">
                                Crear Solicitud
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal Detalle Solicitud -->
    @if ($modalDetalle && $solicitudSeleccionada)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full mx-4 max-h-[90vh] overflow-y-auto">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold text-gray-900">Detalle de Solicitud: {{ $solicitudSeleccionada->numero_solicitud }}</h3>
                        <button wire:click="cerrarModal" class="text-gray-400 hover:text-gray-500">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Información General -->
                    <div class="bg-gray-50 rounded-lg p-4 mb-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Paciente</p>
                                <p class="text-sm text-gray-900">{{ $solicitudSeleccionada->paciente->nombre }} {{ $solicitudSeleccionada->paciente->apellido }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Solicitado por</p>
                                <p class="text-sm text-gray-900">{{ $solicitudSeleccionada->enfermero->user->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Área</p>
                                <p class="text-sm text-gray-900">{{ $solicitudSeleccionada->area->nombre ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Fecha de Solicitud</p>
                                <p class="text-sm text-gray-900">{{ $solicitudSeleccionada->fecha_solicitud->format('d/m/Y H:i') }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Prioridad</p>
                                @php
                                    $prioridadColors = [
                                        'urgente' => 'bg-red-100 text-red-800',
                                        'alta' => 'bg-orange-100 text-orange-800',
                                        'normal' => 'bg-blue-100 text-blue-800',
                                        'baja' => 'bg-gray-100 text-gray-800',
                                    ];
                                    $color = $prioridadColors[$solicitudSeleccionada->prioridad->value] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $color }}">
                                    {{ $solicitudSeleccionada->prioridad->label() }}
                                </span>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Estado</p>
                                @php
                                    $estadoColors = [
                                        'pendiente' => 'bg-yellow-100 text-yellow-800',
                                        'aprobada' => 'bg-green-100 text-green-800',
                                        'despachada' => 'bg-blue-100 text-blue-800',
                                        'completada' => 'bg-indigo-100 text-indigo-800',
                                        'rechazada' => 'bg-red-100 text-red-800',
                                        'cancelada' => 'bg-gray-100 text-gray-800',
                                    ];
                                    $estadoColor = $estadoColors[$solicitudSeleccionada->estado->value] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $estadoColor }}">
                                    {{ $solicitudSeleccionada->estado->label() }}
                                </span>
                            </div>
                        </div>

                        @if($solicitudSeleccionada->observaciones)
                            <div class="mt-4">
                                <p class="text-sm font-medium text-gray-500">Observaciones</p>
                                <p class="text-sm text-gray-900">{{ $solicitudSeleccionada->observaciones }}</p>
                            </div>
                        @endif
                    </div>

                    <!-- Medicamentos Solicitados -->
                    <div class="mb-4">
                        <h4 class="text-md font-semibold text-gray-800 mb-2">Medicamentos Solicitados</h4>
                        <div class="border rounded-lg overflow-hidden">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Medicamento</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Cantidad</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Indicaciones</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($solicitudSeleccionada->detalles as $detalle)
                                        <tr>
                                            <td class="px-4 py-2 text-sm text-gray-900">
                                                {{ $detalle->medicamento->nombre_comercial }}
                                                <span class="text-xs text-gray-500 block">{{ $detalle->medicamento->nombre_generico }}</span>
                                            </td>
                                            <td class="px-4 py-2 text-sm text-gray-900">{{ $detalle->cantidad_solicitada }}</td>
                                            <td class="px-4 py-2 text-sm text-gray-600">{{ $detalle->indicaciones_medicas ?? 'N/A' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Información de Aprobación/Despacho -->
                    @if($solicitudSeleccionada->aprobadoPor || $solicitudSeleccionada->despachadoPor)
                        <div class="bg-blue-50 rounded-lg p-4 mb-4">
                            <h4 class="text-md font-semibold text-gray-800 mb-2">Historial de Procesamiento</h4>

                            @if($solicitudSeleccionada->aprobadoPor)
                                <div class="mb-2">
                                    <p class="text-sm font-medium text-gray-700">Aprobado por:</p>
                                    <p class="text-sm text-gray-900">{{ $solicitudSeleccionada->aprobadoPor->name }} - {{ $solicitudSeleccionada->fecha_aprobacion?->format('d/m/Y H:i') }}</p>
                                </div>
                            @endif

                            @if($solicitudSeleccionada->despachadoPor)
                                <div>
                                    <p class="text-sm font-medium text-gray-700">Despachado por:</p>
                                    <p class="text-sm text-gray-900">{{ $solicitudSeleccionada->despachadoPor->name }} - {{ $solicitudSeleccionada->fecha_despacho?->format('d/m/Y H:i') }}</p>
                                </div>
                            @endif
                        </div>
                    @endif

                    <!-- Actions -->
                    <div class="flex justify-end space-x-3">
                        @if(in_array($solicitudSeleccionada->estado->value, ['pendiente', 'aprobada']))
                            <button wire:click="cancelarSolicitud({{ $solicitudSeleccionada->id }})" wire:confirm="¿Está seguro de cancelar esta solicitud?" class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg">
                                Cancelar Solicitud
                            </button>
                        @endif
                        <button wire:click="cerrarModal" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded-lg">
                            Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
