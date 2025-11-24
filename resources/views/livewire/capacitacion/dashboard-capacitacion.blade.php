<div class="p-6">
    {{-- Header --}}
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Mi Dashboard de Capacitación</h1>
        <p class="text-gray-600 mt-1">Explora actividades disponibles, gestiona tus inscripciones y descarga tus certificaciones</p>
    </div>

    {{-- Estadísticas --}}
    <div class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-7 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-xs font-medium text-gray-500 uppercase">Disponibles</div>
            <div class="text-2xl font-bold text-blue-600">{{ $this->estadisticas['actividadesDisponibles'] }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-xs font-medium text-gray-500 uppercase">Mis Inscripciones</div>
            <div class="text-2xl font-bold text-gray-900">{{ $this->estadisticas['totalInscripciones'] }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-xs font-medium text-gray-500 uppercase">Aprobadas</div>
            <div class="text-2xl font-bold text-green-600">{{ $this->estadisticas['inscripcionesAprobadas'] }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-xs font-medium text-gray-500 uppercase">Pendientes</div>
            <div class="text-2xl font-bold text-yellow-600">{{ $this->estadisticas['inscripcionesPendientes'] }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-xs font-medium text-gray-500 uppercase">Certificaciones</div>
            <div class="text-2xl font-bold text-purple-600">{{ $this->estadisticas['certificacionesTotales'] }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-xs font-medium text-gray-500 uppercase">Vigentes</div>
            <div class="text-2xl font-bold text-indigo-600">{{ $this->estadisticas['certificacionesVigentes'] }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-xs font-medium text-gray-500 uppercase">Horas Totales</div>
            <div class="text-2xl font-bold text-teal-600">{{ $this->estadisticas['horasAcumuladas'] }}</div>
        </div>
    </div>

    {{-- Navegación de pestañas --}}
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="border-b border-gray-200">
            <nav class="flex -mb-px">
                <button wire:click="cambiarVista('disponibles')"
                    class="px-6 py-3 text-sm font-medium border-b-2 transition-colors {{ $vistaActual === 'disponibles' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Actividades Disponibles ({{ $this->estadisticas['actividadesDisponibles'] }})
                </button>
                <button wire:click="cambiarVista('mis-inscripciones')"
                    class="px-6 py-3 text-sm font-medium border-b-2 transition-colors {{ $vistaActual === 'mis-inscripciones' ? 'border-green-500 text-green-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Mis Inscripciones ({{ $this->estadisticas['totalInscripciones'] }})
                </button>
                <button wire:click="cambiarVista('mis-certificaciones')"
                    class="px-6 py-3 text-sm font-medium border-b-2 transition-colors {{ $vistaActual === 'mis-certificaciones' ? 'border-purple-500 text-purple-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Mis Certificaciones ({{ $this->estadisticas['certificacionesTotales'] }})
                </button>
            </nav>
        </div>
    </div>

    {{-- Filtros --}}
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="md:col-span-2">
                <input type="text" wire:model.live="busqueda" placeholder="Buscar..."
                    class="w-full px-3 py-2 border border-gray-300 rounded-md">
            </div>

            @if($vistaActual === 'disponibles')
                <div>
                    <select wire:model.live="filtroTipo" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                        <option value="">Todos los tipos</option>
                        @foreach(\App\Enums\TipoActividad::cases() as $tipo)
                            <option value="{{ $tipo->value }}">{{ $tipo->getLabel() }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            @if($vistaActual === 'mis-inscripciones')
                <div>
                    <select wire:model.live="filtroEstadoActividad" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                        <option value="">Todas las actividades</option>
                        <option value="en_curso">En curso</option>
                        <option value="finalizada">Finalizadas</option>
                    </select>
                </div>
            @endif

            <div>
                <button wire:click="limpiarFiltros" class="w-full px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                    Limpiar Filtros
                </button>
            </div>
        </div>
    </div>

    {{-- Vista de Actividades Disponibles --}}
    @if($vistaActual === 'disponibles')
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($this->actividadesDisponibles as $actividad)
                <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow">
                    <div class="p-5">
                        <div class="flex items-start justify-between mb-3">
                            <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $actividad->tipo->getColor() }}">
                                {{ $actividad->tipo->getLabel() }}
                            </span>
                            <span class="text-xs text-gray-500">{{ $actividad->duracion_horas }}h</span>
                        </div>

                        <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $actividad->titulo }}</h3>
                        <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ $actividad->descripcion }}</p>

                        <div class="space-y-2 mb-4">
                            <div class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                {{ $actividad->fecha_inicio->format('d/m/Y') }} - {{ $actividad->fecha_fin->format('d/m/Y') }}
                            </div>
                            <div class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                {{ $actividad->cupos_disponibles }} / {{ $actividad->cupo_maximo }} cupos disponibles
                            </div>
                            <div class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                {{ $actividad->area->nombre ?? 'Todas las áreas' }}
                            </div>
                        </div>

                        <div class="flex gap-2">
                            <button wire:click="abrirModalDetallesActividad({{ $actividad->id }})"
                                class="flex-1 px-4 py-2 text-sm font-medium text-blue-600 border border-blue-600 rounded-md hover:bg-blue-50">
                                Ver Detalles
                            </button>
                            <button wire:click="abrirModalInscribirse({{ $actividad->id }})"
                                class="flex-1 px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-md hover:bg-green-700"
                                @if(!$actividad->tieneCapoDisponible()) disabled @endif>
                                Inscribirme
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No hay actividades disponibles</h3>
                    <p class="mt-1 text-sm text-gray-500">No se encontraron actividades con los filtros aplicados</p>
                </div>
            @endforelse
        </div>
        <div class="mt-6">
            {{ $this->actividadesDisponibles->links() }}
        </div>
    @endif

    {{-- Vista de Mis Inscripciones --}}
    @if($vistaActual === 'mis-inscripciones')
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($this->misInscripciones as $inscripcion)
                @php
                    $actividad = $inscripcion->actividad;
                    $cumpleCriterio = $inscripcion->cumpleAsistenciaMinima();
                @endphp
                <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow">
                    <div class="p-5">
                        <div class="flex items-start justify-between mb-3">
                            <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $inscripcion->estado->getColor() }}">
                                {{ $inscripcion->estado->getLabel() }}
                            </span>
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-700">
                                {{ $inscripcion->tipo->getLabel() }}
                            </span>
                        </div>

                        <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $actividad->titulo }}</h3>

                        <div class="space-y-2 mb-4">
                            <div class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                {{ $actividad->fecha_inicio->format('d/m/Y') }} - {{ $actividad->fecha_fin->format('d/m/Y') }}
                            </div>
                            <div class="flex items-center text-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="font-bold {{ $cumpleCriterio ? 'text-green-600' : 'text-red-600' }}">
                                    Asistencia: {{ number_format($inscripcion->porcentaje_asistencia, 1) }}%
                                </span>
                            </div>
                            <div class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ $actividad->duracion_horas }} horas
                            </div>
                            @if($inscripcion->calificacion_final)
                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                    </svg>
                                    Calificación: {{ $inscripcion->calificacion_final }}
                                </div>
                            @endif
                        </div>

                        <div class="flex gap-2">
                            <button wire:click="abrirModalDetallesInscripcion({{ $inscripcion->id }})"
                                class="flex-1 px-4 py-2 text-sm font-medium text-blue-600 border border-blue-600 rounded-md hover:bg-blue-50">
                                Ver Detalles
                            </button>
                            @if($inscripcion->estado === \App\Enums\EstadoInscripcion::PENDIENTE)
                                <button wire:click="cancelarInscripcion({{ $inscripcion->id }})"
                                    wire:confirm="¿Estás seguro de cancelar esta inscripción?"
                                    class="flex-1 px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700">
                                    Cancelar
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No tienes inscripciones</h3>
                    <p class="mt-1 text-sm text-gray-500">Inscríbete en actividades disponibles para comenzar</p>
                </div>
            @endforelse
        </div>
        <div class="mt-6">
            {{ $this->misInscripciones->links() }}
        </div>
    @endif

    {{-- Vista de Mis Certificaciones --}}
    @if($vistaActual === 'mis-certificaciones')
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($this->misCertificaciones as $certificacion)
                @php
                    $esVigente = !$certificacion->fecha_vigencia_fin || $certificacion->fecha_vigencia_fin > now();
                @endphp
                <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow border-t-4 {{ $esVigente ? 'border-green-500' : 'border-gray-400' }}">
                    <div class="p-5">
                        <div class="flex items-start justify-between mb-3">
                            <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $esVigente ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $esVigente ? 'Vigente' : 'Vencida' }}
                            </span>
                            <span class="text-xs text-gray-500">{{ $certificacion->horas_certificadas }}h</span>
                        </div>

                        <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $certificacion->inscripcion->actividad->titulo }}</h3>
                        <p class="text-sm font-mono text-blue-600 mb-3">{{ $certificacion->numero_certificado }}</p>

                        <div class="space-y-2 mb-4">
                            <div class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Emitido: {{ $certificacion->fecha_emision->format('d/m/Y') }}
                            </div>
                            @if($certificacion->fecha_vigencia_fin)
                                <div class="flex items-center text-sm {{ $esVigente ? 'text-green-600' : 'text-red-600' }}">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Vence: {{ $certificacion->fecha_vigencia_fin->format('d/m/Y') }}
                                </div>
                            @endif
                            <div class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Asistencia: {{ number_format($certificacion->porcentaje_asistencia, 1) }}%
                            </div>
                            @if($certificacion->calificacion_obtenida)
                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                    </svg>
                                    Calificación: {{ $certificacion->calificacion_obtenida }}
                                </div>
                            @endif
                        </div>

                        <div class="flex gap-2">
                            <button wire:click="abrirModalDetallesCertificacion({{ $certificacion->id }})"
                                class="flex-1 px-4 py-2 text-sm font-medium text-blue-600 border border-blue-600 rounded-md hover:bg-blue-50">
                                Ver Detalles
                            </button>
                            <a href="{{ route('capacitacion.certificacion.pdf', $certificacion->id) }}" target="_blank"
                                class="flex-1 px-4 py-2 text-sm font-medium text-white bg-purple-600 rounded-md hover:bg-purple-700 text-center">
                                Descargar PDF
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No tienes certificaciones</h3>
                    <p class="mt-1 text-sm text-gray-500">Completa actividades de capacitación para obtener certificaciones</p>
                </div>
            @endforelse
        </div>
        <div class="mt-6">
            {{ $this->misCertificaciones->links() }}
        </div>
    @endif

    {{-- Modal Detalles Actividad --}}
    @if($modalDetallesActividad && $actividadSeleccionada)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <h2 class="text-2xl font-bold text-gray-900">{{ $actividadSeleccionada->titulo }}</h2>
                        <button wire:click="$set('modalDetallesActividad', false)" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $actividadSeleccionada->tipo->getColor() }}">
                                {{ $actividadSeleccionada->tipo->getLabel() }}
                            </span>
                        </div>

                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Descripción</h3>
                            <p class="text-gray-900">{{ $actividadSeleccionada->descripcion }}</p>
                        </div>

                        @if($actividadSeleccionada->objetivos)
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 mb-1">Objetivos</h3>
                                <p class="text-gray-900">{{ $actividadSeleccionada->objetivos }}</p>
                            </div>
                        @endif

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 mb-1">Duración</h3>
                                <p class="text-gray-900">{{ $actividadSeleccionada->duracion_horas }} horas</p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 mb-1">Cupos</h3>
                                <p class="text-gray-900">{{ $actividadSeleccionada->cupos_disponibles }} / {{ $actividadSeleccionada->cupo_maximo }} disponibles</p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 mb-1">Fecha Inicio</h3>
                                <p class="text-gray-900">{{ $actividadSeleccionada->fecha_inicio->format('d/m/Y H:i') }}</p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 mb-1">Fecha Fin</h3>
                                <p class="text-gray-900">{{ $actividadSeleccionada->fecha_fin->format('d/m/Y H:i') }}</p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 mb-1">Asistencia Mínima</h3>
                                <p class="text-gray-900">{{ $actividadSeleccionada->porcentaje_asistencia_minimo }}%</p>
                            </div>
                            @if($actividadSeleccionada->calificacion_minima)
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500 mb-1">Calificación Mínima</h3>
                                    <p class="text-gray-900">{{ $actividadSeleccionada->calificacion_minima }}</p>
                                </div>
                            @endif
                        </div>

                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-2">Sesiones ({{ $actividadSeleccionada->sesiones->count() }})</h3>
                            <div class="space-y-2">
                                @foreach($actividadSeleccionada->sesiones->sortBy('numero_sesion') as $sesion)
                                    <div class="bg-gray-50 p-3 rounded-md">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <p class="font-medium text-gray-900">Sesión {{ $sesion->numero_sesion }}: {{ $sesion->titulo }}</p>
                                                <p class="text-sm text-gray-600">{{ $sesion->fecha->format('d/m/Y') }} | {{ $sesion->hora_inicio }} - {{ $sesion->hora_fin }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 flex gap-3">
                        <button wire:click="$set('modalDetallesActividad', false)"
                            class="flex-1 px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                            Cerrar
                        </button>
                        <button wire:click="abrirModalInscribirse({{ $actividadSeleccionada->id }})"
                            class="flex-1 px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-md hover:bg-green-700"
                            @if(!$actividadSeleccionada->tieneCapoDisponible()) disabled @endif>
                            Inscribirme
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Modal Inscribirse --}}
    @if($modalInscribirse)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
                <div class="p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Confirmar Inscripción</h2>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Observaciones (opcional)</label>
                        <textarea wire:model="observacionesInscripcion" rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md"
                            placeholder="Añade cualquier comentario..."></textarea>
                        @error('observacionesInscripcion') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-4">
                        <p class="text-sm text-blue-800">
                            Tu inscripción quedará pendiente de aprobación por el coordinador de capacitación.
                        </p>
                    </div>

                    <div class="flex gap-3">
                        <button wire:click="$set('modalInscribirse', false)"
                            class="flex-1 px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                            Cancelar
                        </button>
                        <button wire:click="inscribirse"
                            class="flex-1 px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-md hover:bg-green-700">
                            Confirmar Inscripción
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Modal Detalles Inscripción --}}
    @if($modalDetallesInscripcion && $inscripcionSeleccionada)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl max-w-3xl w-full mx-4 max-h-[90vh] overflow-y-auto">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <h2 class="text-2xl font-bold text-gray-900">Detalles de Inscripción</h2>
                        <button wire:click="$set('modalDetallesInscripcion', false)" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">{{ $inscripcionSeleccionada->actividad->titulo }}</h3>
                            <p class="text-sm text-gray-600">{{ $inscripcionSeleccionada->actividad->tipo->getLabel() }}</p>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 mb-1">Estado</h3>
                                <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $inscripcionSeleccionada->estado->getColor() }}">
                                    {{ $inscripcionSeleccionada->estado->getLabel() }}
                                </span>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 mb-1">Tipo</h3>
                                <p class="text-gray-900">{{ $inscripcionSeleccionada->tipo->getLabel() }}</p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 mb-1">Asistencia</h3>
                                <p class="text-xl font-bold {{ $inscripcionSeleccionada->cumpleAsistenciaMinima() ? 'text-green-600' : 'text-red-600' }}">
                                    {{ number_format($inscripcionSeleccionada->porcentaje_asistencia, 1) }}%
                                </p>
                            </div>
                            @if($inscripcionSeleccionada->calificacion_final)
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500 mb-1">Calificación Final</h3>
                                    <p class="text-xl font-bold text-gray-900">{{ $inscripcionSeleccionada->calificacion_final }}</p>
                                </div>
                            @endif
                        </div>

                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-2">Asistencias por Sesión</h3>
                            <div class="space-y-2">
                                @foreach($inscripcionSeleccionada->actividad->sesiones->sortBy('numero_sesion') as $sesion)
                                    @php
                                        $asistencia = $inscripcionSeleccionada->asistencias->firstWhere('sesion_id', $sesion->id);
                                    @endphp
                                    <div class="flex items-center justify-between bg-gray-50 p-3 rounded-md">
                                        <div>
                                            <p class="font-medium text-gray-900">Sesión {{ $sesion->numero_sesion }}: {{ $sesion->titulo }}</p>
                                            <p class="text-sm text-gray-600">{{ $sesion->fecha->format('d/m/Y') }}</p>
                                        </div>
                                        <div>
                                            @if($asistencia)
                                                @if($asistencia->presente)
                                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Presente</span>
                                                @else
                                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Ausente</span>
                                                @endif
                                            @else
                                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Sin registrar</span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        @if($inscripcionSeleccionada->observaciones)
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 mb-1">Observaciones</h3>
                                <p class="text-gray-900">{{ $inscripcionSeleccionada->observaciones }}</p>
                            </div>
                        @endif
                    </div>

                    <div class="mt-6">
                        <button wire:click="$set('modalDetallesInscripcion', false)"
                            class="w-full px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                            Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Modal Detalles Certificación --}}
    @if($modalDetallesCertificacion && $certificacionSeleccionada)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <h2 class="text-2xl font-bold text-gray-900">Certificación</h2>
                        <button wire:click="$set('modalDetallesCertificacion', false)" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <div class="space-y-4">
                        <div class="bg-gradient-to-r from-purple-50 to-blue-50 p-4 rounded-lg border border-purple-200">
                            <p class="text-sm text-gray-600 mb-1">Número de Certificado</p>
                            <p class="text-2xl font-bold text-purple-900 font-mono">{{ $certificacionSeleccionada->numero_certificado }}</p>
                        </div>

                        <div>
                            <h3 class="text-lg font-bold text-gray-900">{{ $certificacionSeleccionada->inscripcion->actividad->titulo }}</h3>
                            <p class="text-sm text-gray-600">{{ $certificacionSeleccionada->inscripcion->actividad->tipo->getLabel() }}</p>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 mb-1">Horas Certificadas</h3>
                                <p class="text-xl font-bold text-gray-900">{{ $certificacionSeleccionada->horas_certificadas }}h</p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 mb-1">Asistencia</h3>
                                <p class="text-xl font-bold text-green-600">{{ number_format($certificacionSeleccionada->porcentaje_asistencia, 1) }}%</p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 mb-1">Fecha de Emisión</h3>
                                <p class="text-gray-900">{{ $certificacionSeleccionada->fecha_emision->format('d/m/Y') }}</p>
                            </div>
                            @if($certificacionSeleccionada->fecha_vigencia_fin)
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500 mb-1">Vigencia</h3>
                                    <p class="text-gray-900">Hasta {{ $certificacionSeleccionada->fecha_vigencia_fin->format('d/m/Y') }}</p>
                                </div>
                            @endif
                            @if($certificacionSeleccionada->calificacion_obtenida)
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500 mb-1">Calificación Obtenida</h3>
                                    <p class="text-xl font-bold text-gray-900">{{ $certificacionSeleccionada->calificacion_obtenida }}</p>
                                </div>
                            @endif
                        </div>

                        @if($certificacionSeleccionada->competencias_desarrolladas)
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 mb-1">Competencias Desarrolladas</h3>
                                <p class="text-gray-900">{{ $certificacionSeleccionada->competencias_desarrolladas }}</p>
                            </div>
                        @endif

                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Hash de Verificación</h3>
                            <p class="text-xs font-mono text-gray-600 break-all bg-gray-50 p-2 rounded">{{ $certificacionSeleccionada->hash_verificacion }}</p>
                        </div>

                        @if($certificacionSeleccionada->emitidoPor)
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 mb-1">Emitido por</h3>
                                <p class="text-gray-900">{{ $certificacionSeleccionada->emitidoPor->name }}</p>
                            </div>
                        @endif
                    </div>

                    <div class="mt-6 flex gap-3">
                        <button wire:click="$set('modalDetallesCertificacion', false)"
                            class="flex-1 px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                            Cerrar
                        </button>
                        <a href="{{ route('capacitacion.certificacion.pdf', $certificacionSeleccionada->id) }}" target="_blank"
                            class="flex-1 px-4 py-2 text-sm font-medium text-white bg-purple-600 rounded-md hover:bg-purple-700 text-center">
                            Descargar PDF
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Scripts para notificaciones --}}
    @script
    <script>
        $wire.on('inscripcion-exitosa', (event) => {
            alert(event.mensaje);
        });

        $wire.on('inscripcion-cancelada', (event) => {
            alert(event.mensaje);
        });

        $wire.on('error', (event) => {
            alert('Error: ' + event.mensaje);
        });
    </script>
    @endscript
</div>
