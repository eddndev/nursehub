<div class="p-6">
    {{-- Header --}}
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Aprobaciones y Certificaciones</h1>
                <p class="text-gray-600 mt-1">{{ $this->actividad->titulo }}</p>
            </div>
            <a href="{{ route('capacitacion.inscripciones', $actividadId) }}"
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                Volver a Inscripciones
            </a>
        </div>
    </div>

    {{-- Información del criterio de aprobación --}}
    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
        <div class="flex items-center">
            <div class="flex-1">
                <p class="text-sm font-medium text-blue-800">Criterio de Aprobación</p>
                <p class="text-sm text-blue-700">
                    Porcentaje de asistencia mínimo: {{ $this->actividad->porcentaje_asistencia_minimo }}%
                    @if($this->actividad->requiere_evaluacion)
                        <span class="mx-2">|</span>
                        <span class="font-semibold text-blue-900">Requiere Evaluación</span>
                        @if($this->actividad->calificacion_minima_aprobacion)
                            <span class="mx-1">-</span>
                            Calificación mínima: {{ $this->actividad->calificacion_minima_aprobacion }}%
                        @endif
                    @endif
                </p>
            </div>
        </div>
    </div>

    {{-- Estadísticas --}}
    <div class="grid grid-cols-1 md:grid-cols-6 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-sm font-medium text-gray-500">Total</div>
            <div class="text-2xl font-bold text-gray-900">{{ $this->estadisticas['total'] }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-sm font-medium text-gray-500">Pendientes</div>
            <div class="text-2xl font-bold text-yellow-600">{{ $this->estadisticas['pendientes'] }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-sm font-medium text-gray-500">Aprobadas</div>
            <div class="text-2xl font-bold text-green-600">{{ $this->estadisticas['aprobadas'] }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-sm font-medium text-gray-500">Reprobadas</div>
            <div class="text-2xl font-bold text-red-600">{{ $this->estadisticas['reprobadas'] }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-sm font-medium text-gray-500">Cumplen Criterio</div>
            <div class="text-2xl font-bold text-blue-600">{{ $this->estadisticas['cumplenCriterio'] }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-sm font-medium text-gray-500">Certificaciones</div>
            <div class="text-2xl font-bold text-purple-600">{{ $this->estadisticas['certificacionesGeneradas'] }}</div>
        </div>
    </div>

    {{-- Controles y filtros --}}
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <input type="text" wire:model.live="busqueda" placeholder="Buscar enfermero..."
                    class="w-full px-3 py-2 border border-gray-300 rounded-md">
            </div>
            <div>
                <select wire:model.live="filtroEstado" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    <option value="">Todos los estados</option>
                    <option value="pendiente">Pendientes</option>
                    <option value="aprobada">Aprobadas</option>
                    <option value="reprobada">Reprobadas</option>
                </select>
            </div>
            <div>
                <select wire:model.live="filtroCumpleCriterio" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    <option value="">Todos</option>
                    <option value="cumple">Cumple criterio</option>
                    <option value="no_cumple">No cumple</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button wire:click="limpiarFiltros"
                    class="flex-1 px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                    Limpiar
                </button>
                <button wire:click="seleccionarTodasQueCumplen"
                    class="flex-1 px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">
                    Selec. ✓
                </button>
            </div>
            <div>
                <button wire:click="abrirModalAprobarMasivo"
                    @if(empty($inscripcionesSeleccionadas)) disabled @endif
                    class="w-full px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-md hover:bg-green-700 disabled:bg-gray-300 disabled:cursor-not-allowed">
                    Aprobar ({{ count($inscripcionesSeleccionadas) }})
                </button>
            </div>
        </div>
    </div>

    {{-- Lista de inscripciones --}}
    <div class="bg-white rounded-lg shadow">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase w-12">
                            <input type="checkbox"
                                @if(count($inscripcionesSeleccionadas) === count($this->inscripciones)) checked @endif
                                class="rounded">
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Enfermero</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">% Asistencia</th>
                        @if($this->actividad->requiere_evaluacion)
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Calificación</th>
                        @endif
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cumple</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Certificación</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($this->inscripciones as $inscripcion)
                        @php
                            $cumpleCriterio = $inscripcion->cumpleAsistenciaMinima();
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-4">
                                @if($inscripcion->estado->value === 'pendiente' && $cumpleCriterio)
                                    <input type="checkbox"
                                        wire:click="toggleInscripcionSeleccionada({{ $inscripcion->id }})"
                                        @if(in_array($inscripcion->id, $inscripcionesSeleccionadas)) checked @endif
                                        class="rounded">
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $inscripcion->enfermero->user->name }}</div>
                                <div class="text-sm text-gray-500">{{ $inscripcion->enfermero->area->nombre ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-2xl font-bold {{ $cumpleCriterio ? 'text-green-600' : 'text-red-600' }}">
                                    {{ number_format($inscripcion->porcentaje_asistencia, 1) }}%
                                </span>
                            </td>
                            @if($this->actividad->requiere_evaluacion)
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($inscripcion->calificacion_evaluacion !== null)
                                        <span class="text-xl font-bold {{ $inscripcion->cumpleCalificacionMinima() ? 'text-green-600' : 'text-red-600' }}">
                                            {{ number_format($inscripcion->calificacion_evaluacion, 1) }}%
                                        </span>
                                    @else
                                        <span class="text-gray-400 text-sm">Sin evaluar</span>
                                    @endif
                                </td>
                            @endif
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($cumpleCriterio)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        ✓ Sí
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        ✗ No
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium text-white"
                                    style="background-color: {{ $inscripcion->estado->getColor() }};">
                                    {{ $inscripcion->estado->getLabel() }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if($inscripcion->certificacion)
                                    <button wire:click="abrirModalDetallesCertificacion({{ $inscripcion->certificacion->id }})"
                                        class="text-purple-600 hover:text-purple-900 font-medium">
                                        {{ $inscripcion->certificacion->numero_certificado }}
                                    </button>
                                @else
                                    <span class="text-gray-400">Sin certificación</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                @if($inscripcion->estado->value === 'pendiente')
                                    @if($cumpleCriterio)
                                        <button wire:click="abrirModalAprobar({{ $inscripcion->id }})"
                                            class="text-green-600 hover:text-green-900 mr-3">Aprobar</button>
                                    @endif
                                    <button wire:click="abrirModalReprobar({{ $inscripcion->id }})"
                                        class="text-red-600 hover:text-red-900">Reprobar</button>
                                @elseif($inscripcion->estado->value === 'aprobada' && $inscripcion->certificacion)
                                    <button wire:click="regenerarCertificacion({{ $inscripcion->certificacion->id }})"
                                        class="text-blue-600 hover:text-blue-900">Regenerar PDF</button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                                No hay inscripciones
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-gray-200">
            {{ $this->inscripciones->links() }}
        </div>
    </div>

    {{-- MODALES (versión compacta) --}}

    {{-- Modal Aprobar Individual --}}
    @if($modalAprobar)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-lg w-full max-h-[90vh] overflow-y-auto">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Aprobar Inscripción</h3>
                </div>

                <form wire:submit="aprobarInscripcion" class="px-6 py-4 space-y-4">
                    {{-- Sección de Evaluación (solo si la actividad lo requiere) --}}
                    @if($this->actividad->requiere_evaluacion)
                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-r-md">
                            <p class="text-sm font-medium text-yellow-800 mb-3">Esta actividad requiere evaluación</p>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Calificación *</label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <input type="number" wire:model="calificacionEvaluacion" required
                                            min="0" max="100" step="0.1"
                                            class="block w-full px-3 py-2 border border-gray-300 rounded-md pr-12 focus:ring-yellow-500 focus:border-yellow-500"
                                            placeholder="0-100">
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 sm:text-sm">%</span>
                                        </div>
                                    </div>
                                    @if($this->actividad->calificacion_minima_aprobacion)
                                        <p class="text-xs text-yellow-700 mt-1">
                                            Mínimo aprobatorio: {{ $this->actividad->calificacion_minima_aprobacion }}%
                                        </p>
                                    @endif
                                    @error('calificacionEvaluacion') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="mt-3">
                                <label class="block text-sm font-medium text-gray-700">Retroalimentación</label>
                                <textarea wire:model="retroalimentacion" rows="2"
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-yellow-500 focus:border-yellow-500"
                                    placeholder="Comentarios sobre el desempeño del participante"></textarea>
                            </div>
                        </div>
                    @else
                        <div class="text-sm text-gray-500 bg-gray-50 p-3 rounded-md">
                            Esta actividad no requiere calificación de evaluación.
                        </div>
                    @endif

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Meses de Vigencia *</label>
                            <input type="number" wire:model="mesesVigencia" required min="1" max="120"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md">
                            <p class="text-xs text-gray-500 mt-1">0 = Sin vencimiento</p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Competencias Desarrolladas</label>
                        <textarea wire:model="competenciasDesarrolladas" rows="2"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md"
                            placeholder="Competencias adquiridas por el participante"></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Observaciones Generales</label>
                        <textarea wire:model="observacionesAprobacion" rows="2"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md"
                            placeholder="Notas adicionales de aprobación"></textarea>
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t">
                        <button type="button" wire:click="$set('modalAprobar', false)"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                            Cancelar
                        </button>
                        <button type="submit" wire:loading.attr="disabled"
                            class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-md hover:bg-green-700 disabled:opacity-50 flex items-center">
                            <span wire:loading.remove wire:target="aprobarInscripcion">Aprobar y Generar Certificación</span>
                            <span wire:loading wire:target="aprobarInscripcion" class="flex items-center">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Procesando...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Modal Aprobar Masivo --}}
    @if($modalAprobarMasivo)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Aprobación Masiva</h3>
                    <p class="text-sm text-gray-600 mt-1">{{ count($inscripcionesSeleccionadas) }} inscripciones seleccionadas</p>
                </div>

                <form wire:submit="aprobarMasivo" class="px-6 py-4 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Meses de Vigencia *</label>
                        <input type="number" wire:model="mesesVigencia" required min="1" max="120"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Competencias (todas)</label>
                        <textarea wire:model="competenciasDesarrolladas" rows="2"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Observaciones</label>
                        <textarea wire:model="observacionesAprobacion" rows="2"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md"></textarea>
                    </div>

                    {{-- Información sobre procesamiento en segundo plano --}}
                    @if(count($inscripcionesSeleccionadas) > 10)
                        <div class="bg-blue-50 border-l-4 border-blue-400 p-3 rounded-r-md">
                            <p class="text-sm text-blue-700">
                                <strong>Nota:</strong> Se procesarán {{ count($inscripcionesSeleccionadas) }} inscripciones en segundo plano.
                                Las certificaciones se generarán automáticamente y recibirá una notificación cuando el proceso termine.
                            </p>
                        </div>
                    @endif

                    <div class="flex justify-end gap-3 pt-4 border-t">
                        <button type="button" wire:click="$set('modalAprobarMasivo', false)"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                            Cancelar
                        </button>
                        <button type="submit" wire:loading.attr="disabled"
                            class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-md hover:bg-green-700 disabled:opacity-50 flex items-center">
                            <span wire:loading.remove wire:target="aprobarMasivo">
                                Aprobar y Generar {{ count($inscripcionesSeleccionadas) }} Certificaciones
                            </span>
                            <span wire:loading wire:target="aprobarMasivo" class="flex items-center">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Procesando...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Modal Reprobar --}}
    @if($modalReprobar)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Reprobar Inscripción</h3>
                </div>

                <form wire:submit="reprobarInscripcion" class="px-6 py-4 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Motivo de Reprobación *</label>
                        <textarea wire:model="motivoReprobacion" rows="4" required
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md"
                            placeholder="Explique el motivo (mínimo 10 caracteres)"></textarea>
                        @error('motivoReprobacion') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t">
                        <button type="button" wire:click="$set('modalReprobar', false)"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                            Cancelar
                        </button>
                        <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700">
                            Confirmar Reprobación
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Modal Detalles Certificación --}}
    @if($modalDetallesCertificacion && $certificacionSeleccionada)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Detalles de Certificación</h3>
                </div>

                <div class="px-6 py-4 space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Número de Certificado</label>
                            <p class="mt-1 text-sm font-mono text-gray-900">{{ $certificacionSeleccionada->numero_certificado }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Fecha de Emisión</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $certificacionSeleccionada->fecha_emision->format('d/m/Y') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Enfermero</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $certificacionSeleccionada->enfermero->user->name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Actividad</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $certificacionSeleccionada->actividad->titulo }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Horas Certificadas</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $certificacionSeleccionada->horas_certificadas }} horas</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">% Asistencia</label>
                            <p class="mt-1 text-sm font-bold text-green-600">{{ number_format($certificacionSeleccionada->porcentaje_asistencia, 1) }}%</p>
                        </div>
                        @if($certificacionSeleccionada->calificacion_obtenida)
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Calificación</label>
                                <p class="mt-1 text-sm font-bold text-blue-600">{{ number_format($certificacionSeleccionada->calificacion_obtenida, 1) }}%</p>
                            </div>
                        @endif
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Vigencia</label>
                            <p class="mt-1 text-sm text-gray-900">
                                @if($certificacionSeleccionada->fecha_vigencia_fin)
                                    Hasta {{ $certificacionSeleccionada->fecha_vigencia_fin->format('d/m/Y') }}
                                    @if($certificacionSeleccionada->estaVigente())
                                        <span class="text-green-600">(Vigente)</span>
                                    @else
                                        <span class="text-red-600">(Vencida)</span>
                                    @endif
                                @else
                                    Sin vencimiento
                                @endif
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Emitido Por</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $certificacionSeleccionada->emitidoPor->name }}</p>
                        </div>
                        @if($certificacionSeleccionada->competencias_desarrolladas)
                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-500">Competencias Desarrolladas</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $certificacionSeleccionada->competencias_desarrolladas }}</p>
                            </div>
                        @endif
                    </div>

                    <div class="border-t pt-4">
                        <label class="block text-sm font-medium text-gray-500 mb-2">Hash de Verificación</label>
                        <code class="block p-2 bg-gray-100 rounded text-xs break-all">{{ $certificacionSeleccionada->hash_verificacion }}</code>
                    </div>
                </div>

                <div class="px-6 py-4 bg-gray-50 flex justify-end gap-3">
                    <button wire:click="$set('modalDetallesCertificacion', false)"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                        Cerrar
                    </button>
                    <button wire:click="regenerarCertificacion({{ $certificacionSeleccionada->id }})"
                        class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">
                        Regenerar PDF
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
