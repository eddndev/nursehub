<div class="p-6">
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Administración de Medicamentos</h1>
                <p class="text-gray-600 mt-1">Registra la administración de medicamentos a pacientes</p>
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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Panel izquierdo: Búsqueda de pacientes -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow p-4">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Seleccionar Paciente</h3>

                <div class="mb-4">
                    <input type="text" wire:model.live="busquedaPaciente"
                        placeholder="Buscar por nombre o identificación..."
                        class="w-full px-3 py-2 border border-gray-300 rounded-md">
                </div>

                @if ($pacienteSeleccionado)
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                        <div class="flex justify-between items-start">
                            <div>
                                <h4 class="font-medium text-blue-900">
                                    {{ $pacienteSeleccionado->nombre }} {{ $pacienteSeleccionado->apellido }}
                                </h4>
                                <p class="text-sm text-blue-700">ID: {{ $pacienteSeleccionado->numero_identificacion }}</p>
                                @if ($pacienteSeleccionado->alergias && $pacienteSeleccionado->alergias->count() > 0)
                                    <div class="mt-2">
                                        <span class="text-xs font-medium text-red-600">
                                            <i class="fas fa-exclamation-triangle mr-1"></i>
                                            Alergias: {{ $pacienteSeleccionado->alergias->pluck('alergeno')->join(', ') }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                            <button wire:click="limpiarPaciente" class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                @endif

                <div class="space-y-2 max-h-96 overflow-y-auto">
                    @forelse ($pacientes as $paciente)
                        <div wire:click="seleccionarPaciente({{ $paciente->id }})"
                            class="p-3 border rounded-md cursor-pointer hover:bg-gray-50 transition
                                {{ $pacienteId == $paciente->id ? 'border-blue-500 bg-blue-50' : 'border-gray-200' }}">
                            <div class="text-sm font-medium text-gray-900">
                                {{ $paciente->nombre }} {{ $paciente->apellido }}
                            </div>
                            <div class="text-xs text-gray-500">{{ $paciente->numero_identificacion }}</div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-4">No se encontraron pacientes</p>
                    @endforelse
                </div>

                <div class="mt-4">
                    {{ $pacientes->links() }}
                </div>
            </div>
        </div>

        <!-- Panel derecho: Acciones y historial -->
        <div class="lg:col-span-2">
            @if ($pacienteSeleccionado)
                <!-- Acciones rápidas -->
                <div class="bg-white rounded-lg shadow p-4 mb-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Acciones</h3>
                        <div class="space-x-2">
                            <button wire:click="abrirModalAdministrar"
                                class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                                <i class="fas fa-syringe mr-2"></i>Administrar Medicamento
                            </button>
                            <button wire:click="abrirHistorial"
                                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                <i class="fas fa-history mr-2"></i>Ver Historial Completo
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Solicitudes despachadas pendientes de administrar -->
                @if ($solicitudesPendientes->count() > 0)
                    <div class="bg-white rounded-lg shadow p-4 mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">
                            <i class="fas fa-pills text-green-600 mr-2"></i>
                            Medicamentos Despachados Pendientes
                        </h3>
                        <div class="space-y-3">
                            @foreach ($solicitudesPendientes as $solicitud)
                                <div class="border rounded-lg p-3">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-sm font-medium text-gray-700">
                                            {{ $solicitud->numero_solicitud }}
                                        </span>
                                        <span class="text-xs text-gray-500">
                                            {{ $solicitud->fecha_despacho?->format('d/m/Y H:i') }}
                                        </span>
                                    </div>
                                    <div class="space-y-2">
                                        @foreach ($solicitud->detalles as $detalle)
                                            <div class="flex justify-between items-center bg-gray-50 rounded p-2">
                                                <div>
                                                    <span class="text-sm font-medium text-gray-900">
                                                        {{ $detalle->medicamento->nombre_comercial }}
                                                    </span>
                                                    <span class="text-xs text-gray-500 ml-2">
                                                        x{{ $detalle->cantidad_despachada }}
                                                    </span>
                                                </div>
                                                <button wire:click="abrirModalAdministrar({{ $detalle->medicamento_id }}, {{ $solicitud->id }})"
                                                    class="px-3 py-1 bg-green-100 text-green-700 rounded text-xs hover:bg-green-200">
                                                    <i class="fas fa-syringe mr-1"></i>Administrar
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Historial reciente -->
                <div class="bg-white rounded-lg shadow p-4">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        <i class="fas fa-history text-blue-600 mr-2"></i>
                        Administraciones Recientes
                    </h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Fecha/Hora</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Medicamento</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Dosis</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Vía</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Enfermero</th>
                                    <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Reacción</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($historialAdministraciones as $admin)
                                    <tr class="{{ $admin->tuvo_reaccion_adversa ? 'bg-red-50' : '' }}">
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                            {{ $admin->fecha_hora_administracion->format('d/m/Y H:i') }}
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $admin->medicamento->nombre_comercial }}
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                {{ $admin->medicamento->nombre_generico }}
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                            {{ $admin->dosis_administrada }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                            {{ $admin->via_administracion }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                            {{ $admin->enfermero->user->name ?? 'N/A' }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-center">
                                            @if ($admin->tuvo_reaccion_adversa)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800"
                                                    title="{{ $admin->descripcion_reaccion }}">
                                                    <i class="fas fa-exclamation-triangle mr-1"></i> Sí
                                                </span>
                                            @else
                                                <span class="text-xs text-gray-400">No</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                            No hay administraciones registradas para este paciente
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <div class="bg-white rounded-lg shadow p-8 text-center">
                    <i class="fas fa-user-nurse text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Seleccione un paciente</h3>
                    <p class="text-gray-500">Busque y seleccione un paciente para ver sus medicamentos y administrar nuevos.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Modal Administrar Medicamento -->
    @if ($modalAdministrar)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
                <div class="px-6 py-4 border-b flex justify-between items-center">
                    <h3 class="text-lg font-medium">Registrar Administración de Medicamento</h3>
                    <button wire:click="cerrarModal" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <div class="p-6">
                    <!-- Info del paciente -->
                    @if ($pacienteSeleccionado)
                        <div class="bg-blue-50 rounded-lg p-3 mb-4">
                            <p class="text-sm font-medium text-blue-900">
                                Paciente: {{ $pacienteSeleccionado->nombre }} {{ $pacienteSeleccionado->apellido }}
                            </p>
                            <p class="text-xs text-blue-700">ID: {{ $pacienteSeleccionado->numero_identificacion }}</p>
                        </div>
                    @endif

                    <!-- Alertas de alergia -->
                    @if (count($alertasAlergia) > 0)
                        <div class="mb-4 p-4 bg-red-100 border border-red-400 rounded-lg">
                            <h4 class="text-sm font-bold text-red-800 mb-2">
                                <i class="fas fa-exclamation-circle mr-2"></i>ALERTA DE ALERGIA
                            </h4>
                            @foreach ($alertasAlergia as $alerta)
                                <p class="text-sm text-red-700">{{ $alerta['mensaje'] }}</p>
                            @endforeach
                            <p class="text-xs text-red-600 mt-2 font-medium">
                                La administración está BLOQUEADA. Contacte al médico.
                            </p>
                        </div>
                    @endif

                    <!-- Alertas de interacción -->
                    @if (count($alertasInteraccion) > 0)
                        <div class="mb-4 p-4 bg-yellow-50 border border-yellow-400 rounded-lg">
                            <h4 class="text-sm font-bold text-yellow-800 mb-2">
                                <i class="fas fa-exclamation-triangle mr-2"></i>Interacciones Medicamentosas Detectadas
                            </h4>
                            @foreach ($alertasInteraccion as $interaccion)
                                <div class="mb-2 p-2 bg-white rounded border {{ $interaccion['severidad'] === 'contraindicada' ? 'border-red-300' : 'border-yellow-300' }}">
                                    <p class="text-sm {{ $interaccion['severidad'] === 'contraindicada' ? 'text-red-700' : 'text-yellow-700' }}">
                                        <strong>{{ ucfirst($interaccion['severidad']) }}:</strong> {{ $interaccion['descripcion'] }}
                                    </p>
                                    @if ($interaccion['recomendacion'])
                                        <p class="text-xs text-gray-600 mt-1">
                                            <i class="fas fa-lightbulb mr-1"></i>{{ $interaccion['recomendacion'] }}
                                        </p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Medicamento *</label>
                            <select wire:model.live="medicamento_id"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md @error('medicamento_id') border-red-500 @enderror"
                                {{ $bloqueoAdministracion ? 'disabled' : '' }}>
                                <option value="">Seleccionar medicamento...</option>
                                @foreach ($medicamentosDisponibles as $med)
                                    <option value="{{ $med->id }}">
                                        {{ $med->nombre_comercial }} - {{ $med->concentracion }}
                                        @if ($med->es_controlado) [CONTROLADO] @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('medicamento_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Dosis Administrada *</label>
                                <input type="text" wire:model="dosis_administrada"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md @error('dosis_administrada') border-red-500 @enderror"
                                    placeholder="Ej: 500mg" {{ $bloqueoAdministracion ? 'disabled' : '' }}>
                                @error('dosis_administrada') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Vía de Administración *</label>
                                <select wire:model="via_administracion"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md @error('via_administracion') border-red-500 @enderror"
                                    {{ $bloqueoAdministracion ? 'disabled' : '' }}>
                                    <option value="">Seleccionar...</option>
                                    <option value="oral">Oral</option>
                                    <option value="intravenosa">Intravenosa</option>
                                    <option value="intramuscular">Intramuscular</option>
                                    <option value="subcutanea">Subcutánea</option>
                                    <option value="topica">Tópica</option>
                                    <option value="rectal">Rectal</option>
                                    <option value="inhalatoria">Inhalatoria</option>
                                    <option value="oftalmica">Oftálmica</option>
                                    <option value="otica">Ótica</option>
                                </select>
                                @error('via_administracion') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Observaciones</label>
                            <textarea wire:model="observaciones" rows="2"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md"
                                placeholder="Observaciones de la administración..."
                                {{ $bloqueoAdministracion ? 'disabled' : '' }}></textarea>
                        </div>

                        <div class="border-t pt-4">
                            <label class="flex items-center">
                                <input type="checkbox" wire:model.live="tuvo_reaccion_adversa" class="rounded"
                                    {{ $bloqueoAdministracion ? 'disabled' : '' }}>
                                <span class="ml-2 text-sm text-gray-700">¿Hubo reacción adversa?</span>
                            </label>
                        </div>

                        @if ($tuvo_reaccion_adversa)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Descripción de la Reacción *</label>
                                <textarea wire:model="descripcion_reaccion" rows="3"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md"
                                    placeholder="Describa la reacción adversa observada..."></textarea>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="px-6 py-4 border-t bg-gray-50 flex justify-end space-x-3">
                    <button wire:click="cerrarModal" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        Cancelar
                    </button>
                    <button wire:click="registrarAdministracion"
                        class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 {{ $bloqueoAdministracion ? 'opacity-50 cursor-not-allowed' : '' }}"
                        {{ $bloqueoAdministracion ? 'disabled' : '' }}>
                        <i class="fas fa-check mr-2"></i>Registrar Administración
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal Historial Completo -->
    @if ($modalHistorial && $pacienteSeleccionado)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl max-w-5xl w-full mx-4 max-h-[90vh] overflow-y-auto">
                <div class="px-6 py-4 border-b flex justify-between items-center">
                    <h3 class="text-lg font-medium">
                        Historial de Medicamentos - {{ $pacienteSeleccionado->nombre }} {{ $pacienteSeleccionado->apellido }}
                    </h3>
                    <button wire:click="cerrarModal" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Fecha/Hora</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Medicamento</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Dosis</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Vía</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Enfermero</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Observaciones</th>
                                    <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Reacción</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($historialAdministraciones as $admin)
                                    <tr class="{{ $admin->tuvo_reaccion_adversa ? 'bg-red-50' : '' }}">
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                            {{ $admin->fecha_hora_administracion->format('d/m/Y H:i') }}
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $admin->medicamento->nombre_comercial }}
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                {{ $admin->medicamento->nombre_generico }}
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                            {{ $admin->dosis_administrada }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                            {{ $admin->via_administracion }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                            {{ $admin->enfermero->user->name ?? 'N/A' }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-500 max-w-xs truncate">
                                            {{ $admin->observaciones ?? '-' }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-center">
                                            @if ($admin->tuvo_reaccion_adversa)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800"
                                                    title="{{ $admin->descripcion_reaccion }}">
                                                    <i class="fas fa-exclamation-triangle mr-1"></i> Sí
                                                </span>
                                            @else
                                                <span class="text-xs text-gray-400">No</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                                            No hay administraciones registradas
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="px-6 py-4 border-t bg-gray-50 flex justify-end">
                    <button wire:click="cerrarModal" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
