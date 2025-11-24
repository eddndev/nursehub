<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Header --}}
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Mis Pacientes Asignados</h2>
                    <p class="mt-1 text-sm text-gray-600">
                        Vista de pacientes bajo tu cuidado en el turno actual
                    </p>
                </div>
                <button wire:click="refrescarAsignaciones" type="button"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Refrescar
                </button>
            </div>
        </div>

        @if ($this->turnoActual)
            {{-- Información del Turno --}}
            <div class="mb-6 bg-gradient-to-r from-indigo-50 to-blue-50 border border-indigo-200 rounded-lg p-6 shadow">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Información del Turno</h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                            <div>
                                <span class="font-medium text-gray-700">Área:</span>
                                <p class="text-gray-900">{{ $this->turnoActual->area->nombre }}</p>
                            </div>
                            <div>
                                <span class="font-medium text-gray-700">Tipo:</span>
                                <p>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                        style="background-color: {{ $this->turnoActual->tipo->getColor() }}20; color: {{ $this->turnoActual->tipo->getColor() }};">
                                        {{ $this->turnoActual->tipo->getLabel() }}
                                    </span>
                                </p>
                            </div>
                            <div>
                                <span class="font-medium text-gray-700">Horario:</span>
                                <p class="text-gray-900">{{ $this->turnoActual->hora_inicio }} - {{ $this->turnoActual->hora_fin }}</p>
                            </div>
                            <div>
                                <span class="font-medium text-gray-700">Jefe de Turno:</span>
                                <p class="text-gray-900">{{ $this->turnoActual->jefeTurno->name }}</p>
                            </div>
                        </div>

                        @if ($this->turnoActual->novedades_relevo)
                            <div class="mt-4 pt-4 border-t border-indigo-200">
                                <span class="font-medium text-gray-700">Novedades del Relevo:</span>
                                <p class="mt-1 text-sm text-gray-700 bg-white rounded p-3">
                                    {{ $this->turnoActual->novedades_relevo }}
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Estadísticas Rápidas --}}
            <div class="mb-6 grid grid-cols-2 md:grid-cols-6 gap-4">
                <div class="bg-white rounded-lg shadow p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded-md bg-blue-500 p-3">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-500">Total</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $this->estadisticas['total_pacientes'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded-md bg-red-500 p-3">
                                <span class="text-white font-bold text-lg">I</span>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-500">Rojo</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $this->estadisticas['con_triage_rojo'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded-md bg-orange-500 p-3">
                                <span class="text-white font-bold text-lg">II</span>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-500">Naranja</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $this->estadisticas['con_triage_naranja'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded-md bg-yellow-500 p-3">
                                <span class="text-white font-bold text-lg">III</span>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-500">Amarillo</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $this->estadisticas['con_triage_amarillo'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded-md bg-green-500 p-3">
                                <span class="text-white font-bold text-lg">IV</span>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-500">Verde</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $this->estadisticas['con_triage_verde'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded-md bg-blue-400 p-3">
                                <span class="text-white font-bold text-lg">V</span>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-500">Azul</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $this->estadisticas['con_triage_azul'] }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Grid de Pacientes --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($this->pacientesAsignados as $paciente)
                    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow overflow-hidden">
                        {{-- Header con Triage --}}
                        <div class="p-4 border-l-4" style="border-color: {{ $paciente->nivel_triage ?
                            match($paciente->nivel_triage->getPrioridad()) {
                                1 => '#DC2626',
                                2 => '#EA580C',
                                3 => '#EAB308',
                                4 => '#16A34A',
                                5 => '#3B82F6',
                                default => '#6B7280'
                            } : '#6B7280' }};">

                            <div class="flex items-start justify-between mb-3">
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-gray-900">
                                        {{ $paciente->nombre_completo }}
                                    </h3>
                                    <p class="text-sm text-gray-600">
                                        {{ $paciente->edad }} años | {{ $paciente->sexo }}
                                    </p>
                                </div>
                                @if ($paciente->nivel_triage)
                                    <span class="inline-flex items-center justify-center h-8 w-8 rounded-full text-white font-bold text-sm"
                                        style="background-color: {{ match($paciente->nivel_triage->getPrioridad()) {
                                            1 => '#DC2626',
                                            2 => '#EA580C',
                                            3 => '#EAB308',
                                            4 => '#16A34A',
                                            5 => '#3B82F6',
                                            default => '#6B7280'
                                        } }};">
                                        {{ $paciente->nivel_triage->getPrioridad() }}
                                    </span>
                                @endif
                            </div>

                            {{-- Ubicación --}}
                            <div class="mb-3 flex items-center text-sm text-gray-600">
                                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                @if ($paciente->camaActual)
                                    Cama {{ $paciente->camaActual->cuarto->numero }}-{{ $paciente->camaActual->numero }}
                                    | {{ $paciente->camaActual->cuarto->piso->nombre }}
                                @else
                                    Sin cama asignada
                                @endif
                            </div>

                            {{-- Signos Vitales --}}
                            @if ($paciente->ultimo_registro_signos)
                                <div class="mb-3 bg-gray-50 rounded p-3">
                                    <p class="text-xs font-medium text-gray-700 mb-2">Signos Vitales Recientes:</p>
                                    <div class="grid grid-cols-2 gap-2 text-xs">
                                        <div>
                                            <span class="text-gray-600">FC:</span>
                                            <span class="font-medium text-gray-900">{{ $paciente->ultimo_registro_signos->frecuencia_cardiaca }} lpm</span>
                                        </div>
                                        <div>
                                            <span class="text-gray-600">FR:</span>
                                            <span class="font-medium text-gray-900">{{ $paciente->ultimo_registro_signos->frecuencia_respiratoria }} rpm</span>
                                        </div>
                                        <div>
                                            <span class="text-gray-600">PA:</span>
                                            <span class="font-medium text-gray-900">
                                                {{ $paciente->ultimo_registro_signos->presion_arterial_sistolica }}/{{ $paciente->ultimo_registro_signos->presion_arterial_diastolica }}
                                            </span>
                                        </div>
                                        <div>
                                            <span class="text-gray-600">Temp:</span>
                                            <span class="font-medium text-gray-900">{{ $paciente->ultimo_registro_signos->temperatura }}°C</span>
                                        </div>
                                        @if ($paciente->ultimo_registro_signos->saturacion_oxigeno)
                                            <div>
                                                <span class="text-gray-600">SpO2:</span>
                                                <span class="font-medium text-gray-900">{{ $paciente->ultimo_registro_signos->saturacion_oxigeno }}%</span>
                                            </div>
                                        @endif
                                    </div>
                                    <p class="mt-2 text-xs text-gray-500">
                                        Registrado: {{ $paciente->ultimo_registro_signos->fecha_registro->diffForHumans() }}
                                    </p>
                                </div>
                            @else
                                <div class="mb-3 bg-yellow-50 border border-yellow-200 rounded p-3">
                                    <p class="text-xs text-yellow-800">
                                        No hay signos vitales registrados
                                    </p>
                                </div>
                            @endif

                            {{-- Tiempo de Asignación --}}
                            <div class="mb-3 text-xs text-gray-500">
                                <svg class="h-3 w-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Asignado hace {{ $paciente->asignacion->fecha_hora_asignacion->diffForHumans() }}
                            </div>

                            {{-- Botón Ver Expediente --}}
                            <button wire:click="verExpediente({{ $paciente->id }})" type="button"
                                class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Ver Expediente
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full">
                        <div class="text-center bg-white rounded-lg shadow p-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No tienes pacientes asignados</h3>
                            <p class="mt-1 text-sm text-gray-500">
                                No hay pacientes asignados a ti en el turno actual.
                            </p>
                        </div>
                    </div>
                @endforelse
            </div>
        @else
            {{-- Sin Turno Activo --}}
            <div class="text-center bg-white rounded-lg shadow p-12">
                <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900">No hay turno activo</h3>
                <p class="mt-2 text-sm text-gray-500">
                    No tienes un turno activo en este momento. Contacta a tu jefe de piso para más información.
                </p>
            </div>
        @endif
    </div>

    {{-- Notifications --}}
    <script>
        window.addEventListener('asignaciones-refrescadas', event => {
            // Se podría mostrar una notificación aquí
            console.log('Asignaciones refrescadas');
        });
    </script>
</div>
