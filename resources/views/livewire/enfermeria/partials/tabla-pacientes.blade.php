<div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
            <thead class="bg-slate-50 dark:bg-slate-900/50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                        Paciente
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                        TRIAGE
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                        Signos Vitales
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                        Ubicación
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                        Estado
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                        Tiempo
                    </th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                        Acciones
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-slate-800 divide-y divide-slate-200 dark:divide-slate-700">
                @forelse($pacientes as $paciente)
                    @php
                        $ultimoRegistro = $paciente->registrosSignosVitales->first();
                        $tiempoEspera = $paciente->fecha_admision->diffForHumans();
                    @endphp
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                        {{-- Paciente --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-10 w-10 flex-shrink-0">
                                    <div class="h-10 w-10 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center">
                                        <span class="text-sm font-medium text-slate-700 dark:text-slate-300">
                                            {{ strtoupper(substr($paciente->nombre, 0, 1) . substr($paciente->apellido_paterno, 0, 1)) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-slate-900 dark:text-slate-100">
                                        {{ $paciente->nombre_completo }}
                                    </div>
                                    <div class="text-xs text-slate-500 dark:text-slate-400">
                                        {{ $paciente->edad }} años • {{ $paciente->codigo_qr }}
                                    </div>
                                </div>
                            </div>
                        </td>

                        {{-- TRIAGE --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($ultimoRegistro && $ultimoRegistro->nivel_triage)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $ultimoRegistro->nivel_triage->getColor() }}-100 text-{{ $ultimoRegistro->nivel_triage->getColor() }}-800 dark:bg-{{ $ultimoRegistro->nivel_triage->getColor() }}-900/50 dark:text-{{ $ultimoRegistro->nivel_triage->getColor() }}-200">
                                    {{ $ultimoRegistro->nivel_triage->getLabel() }}
                                </span>
                                @if($ultimoRegistro->triage_override)
                                    <span class="ml-1 text-xs text-amber-600 dark:text-amber-400" title="TRIAGE modificado manualmente">⚠️</span>
                                @endif
                            @else
                                <span class="text-xs text-slate-400 dark:text-slate-500">Sin registro</span>
                            @endif
                        </td>

                        {{-- Signos Vitales --}}
                        <td class="px-6 py-4">
                            @if($ultimoRegistro)
                                <div class="text-xs space-y-0.5">
                                    @if($ultimoRegistro->presion_arterial)
                                        <div class="text-slate-700 dark:text-slate-300">PA: {{ $ultimoRegistro->presion_arterial }}</div>
                                    @endif
                                    @if($ultimoRegistro->frecuencia_cardiaca)
                                        <div class="text-slate-700 dark:text-slate-300">FC: {{ $ultimoRegistro->frecuencia_cardiaca }} lpm</div>
                                    @endif
                                    @if($ultimoRegistro->temperatura)
                                        <div class="text-slate-700 dark:text-slate-300">T: {{ $ultimoRegistro->temperatura }}°C</div>
                                    @endif
                                    @if($ultimoRegistro->saturacion_oxigeno)
                                        <div class="text-slate-700 dark:text-slate-300">SpO2: {{ $ultimoRegistro->saturacion_oxigeno }}%</div>
                                    @endif
                                </div>
                            @else
                                <span class="text-xs text-slate-400 dark:text-slate-500">Sin registro</span>
                            @endif
                        </td>

                        {{-- Ubicación --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($paciente->camaActual)
                                <div class="text-xs">
                                    <div class="font-medium text-slate-900 dark:text-slate-100">{{ $paciente->camaActual->cuarto->piso->area->nombre }}</div>
                                    <div class="text-slate-500 dark:text-slate-400">
                                        Piso {{ $paciente->camaActual->cuarto->piso->nombre }} •
                                        Cuarto {{ $paciente->camaActual->cuarto->numero }} •
                                        Cama {{ $paciente->camaActual->numero }}
                                    </div>
                                </div>
                            @else
                                <span class="text-xs text-slate-400 dark:text-slate-500">Sin cama asignada</span>
                            @endif
                        </td>

                        {{-- Estado --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $paciente->estado->getColor() }}-100 text-{{ $paciente->estado->getColor() }}-800 dark:bg-{{ $paciente->estado->getColor() }}-900/50 dark:text-{{ $paciente->estado->getColor() }}-200">
                                {{ $paciente->estado->getLabel() }}
                            </span>
                        </td>

                        {{-- Tiempo de espera --}}
                        <td class="px-6 py-4 whitespace-nowrap text-xs text-slate-500 dark:text-slate-400">
                            {{ $tiempoEspera }}
                        </td>

                        {{-- Acciones --}}
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('enfermeria.expediente', $paciente->id) }}"
                                class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                Ver expediente
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="h-12 w-12 text-slate-400 dark:text-slate-600 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                </svg>
                                <p class="text-sm text-slate-500 dark:text-slate-400">No se encontraron pacientes</p>
                                @if($search || $filtroTriage || ($filtroEstado && $filtroEstado !== 'activo'))
                                    <button wire:click="limpiarFiltros" type="button" class="mt-2 text-sm text-blue-600 hover:text-blue-500 dark:text-blue-400">
                                        Limpiar filtros
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Paginación --}}
    @if($pacientes->hasPages())
        <div class="bg-slate-50 dark:bg-slate-900/50 px-6 py-3 border-t border-slate-200 dark:border-slate-700">
            {{ $pacientes->links() }}
        </div>
    @endif
</div>
