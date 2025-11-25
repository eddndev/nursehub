<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Relevo de Turno</h2>
                    <p class="mt-1 text-sm text-gray-600">
                        Registra novedades y cierra el turno para hacer relevo
                    </p>
                </div>
                @if ($this->turnoActual && $this->turnoActual->estado->value === 'activo')
                    <button wire:click="cerrarTurnoConRelevo"
                        wire:confirm="¿Estás seguro de cerrar el turno? Se liberarán todas las asignaciones activas."
                        type="button"
                        class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Cerrar Turno y Hacer Relevo
                    </button>
                @endif
            </div>
        </div>

        {{-- Selector de Área (solo si es coordinador o admin) --}}
        @if (in_array(auth()->user()->role->value, ['coordinador', 'admin']))
            <div class="mb-6 bg-white rounded-lg shadow p-4">
                <label for="areaId" class="block text-sm font-medium text-gray-700 mb-2">Seleccionar Área</label>
                <div class="flex gap-2">
                    <select wire:model="areaId" id="areaId"
                        class="flex-1 block rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="">Seleccione un área</option>
                        @foreach ($this->areas as $area)
                            <option value="{{ $area->id }}">{{ $area->nombre }}</option>
                        @endforeach
                    </select>
                    <button wire:click="cambiarArea" type="button"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Cambiar
                    </button>
                </div>
            </div>
        @endif

        @if ($this->turnoActual)
            {{-- Información del Turno Actual --}}
            <div class="mb-6 bg-white rounded-lg shadow overflow-hidden">
                <div class="bg-indigo-600 px-6 py-4">
                    <h3 class="text-lg font-semibold text-white">Turno Actual</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Área</p>
                            <p class="text-lg font-medium text-gray-900">{{ $this->turnoActual->area->nombre }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Tipo de Turno</p>
                            <p class="text-lg font-medium text-gray-900">{{ $this->turnoActual->tipo->getLabel() }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Fecha</p>
                            <p class="text-lg font-medium text-gray-900">{{ $this->turnoActual->fecha->format('d/m/Y') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Horario</p>
                            <p class="text-lg font-medium text-gray-900">
                                {{ $this->turnoActual->hora_inicio->format('H:i') }} - {{ $this->turnoActual->hora_fin->format('H:i') }}
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Jefe de Turno</p>
                            <p class="text-lg font-medium text-gray-900">{{ $this->turnoActual->jefeTurno->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Estado</p>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium"
                                style="background-color: {{ $this->turnoActual->estado->getColor() }}; color: white;">
                                {{ $this->turnoActual->estado->getLabel() }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Resumen de Asignaciones --}}
            <div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Enfermeros Activos</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $this->resumenAsignaciones['enfermeros_activos'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Pacientes Asignados</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $this->resumenAsignaciones['pacientes_asignados'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-purple-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Asignaciones</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $this->resumenAsignaciones['total_asignaciones'] }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Novedades del Turno Anterior --}}
            @if ($this->turnoAnterior && $this->turnoAnterior->novedades_relevo)
                <div class="mb-6 bg-blue-50 border border-blue-200 rounded-lg shadow-sm overflow-hidden">
                    <div class="bg-blue-100 px-6 py-3 border-b border-blue-200">
                        <div class="flex items-center">
                            <svg class="h-5 w-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <h3 class="text-base font-semibold text-blue-900">
                                Novedades del Turno Anterior ({{ $this->turnoAnterior->tipo->getLabel() }} - {{ $this->turnoAnterior->fecha->format('d/m/Y') }})
                            </h3>
                        </div>
                        <p class="text-xs text-blue-700 mt-1">
                            Registrado por: {{ $this->turnoAnterior->jefeTurno->name }}
                            @if ($this->turnoAnterior->cerrado_at)
                                | Cerrado: {{ $this->turnoAnterior->cerrado_at->format('d/m/Y H:i') }}
                            @endif
                        </p>
                    </div>
                    <div class="p-6">
                        <div class="prose max-w-none text-gray-700 whitespace-pre-line">
                            {{ $this->turnoAnterior->novedades_relevo }}
                        </div>
                    </div>
                </div>
            @endif

            {{-- Formulario de Novedades para el Siguiente Turno --}}
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Novedades para el Siguiente Turno</h3>
                    <p class="text-sm text-gray-600 mt-1">
                        Registra información importante que debe conocer el equipo del siguiente turno
                    </p>
                </div>
                <div class="p-6">
                    <form wire:submit.prevent="guardarNovedades">
                        <div class="mb-4">
                            <label for="novedadesRelevo" class="block text-sm font-medium text-gray-700 mb-2">
                                Novedades y Pendientes
                            </label>
                            <textarea
                                wire:model="novedadesRelevo"
                                id="novedadesRelevo"
                                rows="8"
                                placeholder="Ej:&#10;• Paciente en cama 301-A requiere control estricto de PA cada 2h&#10;• Pendiente de alta: Paciente en 205-B&#10;• Familiar del paciente en 118-C solicitó hablar con médico"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                @if($this->turnoActual->estado->value === 'cerrado') disabled @endif
                            ></textarea>
                            @error('novedadesRelevo')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        @if ($this->turnoActual->estado->value === 'activo')
                            <div class="flex justify-between items-center">
                                <p class="text-sm text-gray-600">
                                    <svg class="inline h-4 w-4 text-gray-400 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Puedes guardar novedades sin cerrar el turno
                                </p>
                                <button type="submit"
                                    class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                                    </svg>
                                    Guardar Novedades
                                </button>
                            </div>
                        @else
                            <div class="bg-gray-100 border border-gray-300 rounded-md p-4">
                                <p class="text-sm text-gray-700 flex items-center">
                                    <svg class="h-5 w-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                    Este turno ya está cerrado. No se pueden editar las novedades.
                                </p>
                            </div>
                        @endif
                    </form>
                </div>
            </div>

        @else
            {{-- No hay turno activo --}}
            <div class="text-center bg-white rounded-lg shadow p-12">
                <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900">No hay turno activo</h3>
                <p class="mt-2 text-sm text-gray-500">
                    @if ($this->areaId)
                        No existe un turno activo para el área seleccionada.
                    @else
                        Selecciona un área para ver el turno activo.
                    @endif
                </p>
                @if (in_array(auth()->user()->role->value, ['jefe_piso', 'coordinador', 'admin']))
                    <div class="mt-6">
                        <a href="{{ route('turnos.gestor', ['areaId' => $this->areaId]) }}"
                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Ir al Gestor de Turnos
                        </a>
                    </div>
                @endif
            </div>
        @endif
    </div>

    {{-- Scripts para eventos --}}
    <script>
        window.addEventListener('novedades-guardadas', event => {
            // Mostrar notificación de éxito
            console.log(event.detail.mensaje);
        });

        window.addEventListener('turno-cerrado', event => {
            // Mostrar notificación de éxito y recargar
            console.log(event.detail.mensaje);
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        });

        window.addEventListener('error', event => {
            // Mostrar notificación de error
            console.error(event.detail.mensaje);
            alert('Error: ' + event.detail.mensaje);
        });

        window.addEventListener('area-cambiada', event => {
            console.log('Área cambiada');
        });
    </script>
</div>
