<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Header --}}
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Gestor de Turnos y Asignaciones</h2>
            <p class="mt-1 text-sm text-gray-600">Gestiona turnos y asigna pacientes a enfermeros</p>
        </div>

        {{-- Selector de Área --}}
        <div class="mb-6 bg-white shadow sm:rounded-lg p-4">
            <label for="area" class="block text-sm font-medium text-gray-700 mb-2">Área:</label>
            <select wire:model.live="areaId" wire:change="cambiarArea($event.target.value)" id="area"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                @foreach ($this->areas as $area)
                    <option value="{{ $area->id }}">{{ $area->nombre }}</option>
                @endforeach
            </select>
        </div>

        {{-- Información del Turno Actual --}}
        @if ($this->turnoActual)
            <div class="mb-6 bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg p-6 shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Turno Activo</h3>
                        <div class="mt-2 grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="font-medium text-gray-700">Tipo:</span>
                                <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                    style="background-color: {{ $this->turnoActual->tipo->getColor() }}20; color: {{ $this->turnoActual->tipo->getColor() }};">
                                    {{ $this->turnoActual->tipo->getLabel() }}
                                </span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-700">Horario:</span>
                                <span class="ml-2 text-gray-900">{{ $this->turnoActual->hora_inicio }} - {{ $this->turnoActual->hora_fin }}</span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-700">Fecha:</span>
                                <span class="ml-2 text-gray-900">{{ $this->turnoActual->fecha->format('d/m/Y') }}</span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-700">Jefe de Turno:</span>
                                <span class="ml-2 text-gray-900">{{ $this->turnoActual->jefeTurno->name }}</span>
                            </div>
                        </div>
                    </div>
                    <button wire:click="abrirModalCerrarTurno" type="button"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        Cerrar Turno
                    </button>
                </div>
            </div>
        @else
            <div class="mb-6 bg-yellow-50 border border-yellow-200 rounded-lg p-6 shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">No hay turno activo</h3>
                        <p class="mt-1 text-sm text-gray-600">Crea un nuevo turno para comenzar a asignar pacientes</p>
                    </div>
                    <button wire:click="abrirModalCrearTurno" type="button"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Crear Turno
                    </button>
                </div>
            </div>
        @endif

        @if ($this->turnoActual)
            {{-- Grid: Enfermeros y Pacientes --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                {{-- Panel de Enfermeros --}}
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            Enfermeros Disponibles
                        </h3>
                        <p class="mt-1 text-sm text-gray-500">
                            {{ $this->enfermeros->count() }} enfermeros
                        </p>
                    </div>
                    <div class="px-4 py-5 sm:p-6">
                        <ul role="list" class="divide-y divide-gray-200">
                            @forelse ($this->enfermeros as $enfermero)
                                <li class="py-4">
                                    <div class="flex items-center space-x-4">
                                        <div class="flex-shrink-0">
                                            <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                                <span class="text-indigo-600 font-medium text-sm">
                                                    {{ strtoupper(substr($enfermero->user->name, 0, 2)) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 truncate">
                                                {{ $enfermero->user->name }}
                                            </p>
                                            <p class="text-sm text-gray-500 truncate">
                                                {{ $enfermero->cedula_profesional }}
                                            </p>
                                        </div>
                                        <div>
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ $enfermero->pacientes_asignados }} pacientes
                                            </span>
                                        </div>
                                    </div>
                                </li>
                            @empty
                                <li class="py-4 text-center text-gray-500 text-sm">
                                    No hay enfermeros disponibles
                                </li>
                            @endforelse
                        </ul>
                    </div>
                </div>

                {{-- Panel de Pacientes --}}
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            Pacientes del Área
                        </h3>
                        <p class="mt-1 text-sm text-gray-500">
                            {{ $this->pacientes->count() }} pacientes activos
                        </p>
                    </div>
                    <div class="px-4 py-5 sm:p-6">
                        <ul role="list" class="divide-y divide-gray-200">
                            @forelse ($this->pacientes as $paciente)
                                <li class="py-4">
                                    <div class="flex items-center justify-between">
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 truncate">
                                                {{ $paciente->nombre_completo }}
                                            </p>
                                            <p class="text-sm text-gray-500">
                                                @if ($paciente->camaActual)
                                                    Cama: {{ $paciente->camaActual->cuarto->numero }}-{{ $paciente->camaActual->numero }} |
                                                @endif
                                                Edad: {{ $paciente->edad }} años
                                            </p>
                                            @if ($paciente->asignacionActual)
                                                <p class="text-xs text-green-600 mt-1">
                                                    Asignado a: {{ $paciente->asignacionActual->enfermero->user->name }}
                                                </p>
                                            @endif
                                        </div>
                                        <div>
                                            @if ($paciente->asignacionActual)
                                                <button wire:click="abrirModalReasignar({{ $paciente->asignacionActual->id }})" type="button"
                                                    class="inline-flex items-center px-3 py-1.5 border border-gray-300 text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                    Reasignar
                                                </button>
                                            @else
                                                <button wire:click="abrirModalAsignar({{ $paciente->id }})" type="button"
                                                    class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                    Asignar
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </li>
                            @empty
                                <li class="py-4 text-center text-gray-500 text-sm">
                                    No hay pacientes en esta área
                                </li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Asignaciones Actuales --}}
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        Asignaciones Actuales
                    </h3>
                    <p class="mt-1 text-sm text-gray-500">
                        Asignaciones activas agrupadas por enfermero
                    </p>
                </div>
                <div class="px-4 py-5 sm:p-6">
                    @forelse ($this->asignaciones as $enfermeroId => $asignacionesEnfermero)
                        <div class="mb-6 last:mb-0">
                            <h4 class="text-md font-semibold text-gray-900 mb-3">
                                {{ $asignacionesEnfermero->first()->enfermero->user->name }}
                                <span class="ml-2 text-sm font-normal text-gray-500">
                                    ({{ $asignacionesEnfermero->count() }} pacientes)
                                </span>
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach ($asignacionesEnfermero as $asignacion)
                                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                                        <div class="flex items-start justify-between">
                                            <div class="flex-1">
                                                <p class="text-sm font-medium text-gray-900">
                                                    {{ $asignacion->paciente->nombre_completo }}
                                                </p>
                                                @if ($asignacion->paciente->camaActual)
                                                    <p class="text-xs text-gray-500 mt-1">
                                                        Cama: {{ $asignacion->paciente->camaActual->cuarto->numero }}-{{ $asignacion->paciente->camaActual->numero }}
                                                    </p>
                                                @endif
                                                <p class="text-xs text-gray-400 mt-1">
                                                    Asignado: {{ $asignacion->fecha_hora_asignacion->format('H:i') }}
                                                </p>
                                            </div>
                                            <button wire:click="abrirModalLiberar({{ $asignacion->id }})" type="button"
                                                class="ml-2 text-gray-400 hover:text-red-600">
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-gray-500 text-sm py-4">
                            No hay asignaciones activas en este turno
                        </p>
                    @endforelse
                </div>
            </div>
        @endif
    </div>

    {{-- Modal Crear Turno --}}
    @if ($mostrarModalCrearTurno)
        <div class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" wire:click="cerrarModalCrearTurno"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Crear Nuevo Turno</h3>
                        <div class="space-y-4">
                            <div>
                                <label for="fecha" class="block text-sm font-medium text-gray-700">Fecha</label>
                                <input type="date" wire:model="fecha" id="fecha"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                @error('fecha') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="tipo" class="block text-sm font-medium text-gray-700">Tipo de Turno</label>
                                <select wire:model="tipo" id="tipo"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="">Seleccione un tipo</option>
                                    @foreach ($this->tiposTurno as $tipoTurno)
                                        <option value="{{ $tipoTurno->value }}">
                                            {{ $tipoTurno->getLabel() }} ({{ $tipoTurno->getHoraInicio() }} - {{ $tipoTurno->getHoraFin() }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('tipo') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button wire:click="crearTurno" type="button"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Crear Turno
                        </button>
                        <button wire:click="cerrarModalCrearTurno" type="button"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancelar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Modal Asignar Paciente --}}
    @if ($mostrarModalAsignar)
        <div class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" wire:click="cerrarModalAsignar"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Asignar Paciente</h3>
                        <div>
                            <label for="enfermeroSeleccionado" class="block text-sm font-medium text-gray-700">Enfermero</label>
                            <select wire:model="enfermeroSeleccionado" id="enfermeroSeleccionado"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="">Seleccione un enfermero</option>
                                @foreach ($this->enfermeros as $enfermero)
                                    <option value="{{ $enfermero->id }}">
                                        {{ $enfermero->user->name }} ({{ $enfermero->pacientes_asignados }} pacientes)
                                    </option>
                                @endforeach
                            </select>
                            @error('enfermeroSeleccionado') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button wire:click="asignarPaciente" type="button"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Asignar
                        </button>
                        <button wire:click="cerrarModalAsignar" type="button"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancelar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Modal Reasignar --}}
    @if ($mostrarModalReasignar)
        <div class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" wire:click="cerrarModalReasignar"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Reasignar Paciente</h3>
                        <div>
                            <label for="nuevoEnfermero" class="block text-sm font-medium text-gray-700">Nuevo Enfermero</label>
                            <select wire:model="nuevoEnfermero" id="nuevoEnfermero"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="">Seleccione un enfermero</option>
                                @foreach ($this->enfermeros as $enfermero)
                                    <option value="{{ $enfermero->id }}">
                                        {{ $enfermero->user->name }} ({{ $enfermero->pacientes_asignados }} pacientes)
                                    </option>
                                @endforeach
                            </select>
                            @error('nuevoEnfermero') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button wire:click="reasignarPaciente" type="button"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Reasignar
                        </button>
                        <button wire:click="cerrarModalReasignar" type="button"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancelar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Modal Liberar Asignación --}}
    @if ($mostrarModalLiberar)
        <div class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" wire:click="cerrarModalLiberar"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Liberar Asignación</h3>
                        <div>
                            <label for="motivoLiberacion" class="block text-sm font-medium text-gray-700">Motivo</label>
                            <textarea wire:model="motivoLiberacion" id="motivoLiberacion" rows="3"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                placeholder="Indique el motivo de la liberación"></textarea>
                            @error('motivoLiberacion') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button wire:click="liberarAsignacion" type="button"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Liberar
                        </button>
                        <button wire:click="cerrarModalLiberar" type="button"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancelar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Modal Cerrar Turno --}}
    @if ($mostrarModalCerrarTurno)
        <div class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" wire:click="cerrarModalCerrarTurno"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Cerrar Turno</h3>
                        <div>
                            <label for="novedadesRelevo" class="block text-sm font-medium text-gray-700">Novedades para el Relevo</label>
                            <textarea wire:model="novedadesRelevo" id="novedadesRelevo" rows="4"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                placeholder="Indique las novedades importantes para el siguiente turno..."></textarea>
                            @error('novedadesRelevo') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="mt-3 text-sm text-gray-500">
                            <p>Al cerrar el turno, todas las asignaciones activas permanecerán registradas para el historial.</p>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button wire:click="cerrarTurno" type="button"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Cerrar Turno
                        </button>
                        <button wire:click="cerrarModalCerrarTurno" type="button"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancelar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Notifications --}}
    <script>
        window.addEventListener('turno-creado', event => {
            alert(event.detail.mensaje);
        });
        window.addEventListener('paciente-asignado', event => {
            alert(event.detail.mensaje);
        });
        window.addEventListener('paciente-reasignado', event => {
            alert(event.detail.mensaje);
        });
        window.addEventListener('asignacion-liberada', event => {
            alert(event.detail.mensaje);
        });
        window.addEventListener('turno-cerrado', event => {
            alert(event.detail.mensaje);
        });
        window.addEventListener('error', event => {
            alert('Error: ' + event.detail.mensaje);
        });
    </script>
</div>
