<div class="p-6">
    {{-- Header con información de la actividad --}}
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Inscripciones - {{ $this->actividad->titulo }}</h1>
                <p class="text-gray-600 mt-1">{{ $this->actividad->tipo->getLabel() }} | {{ $this->actividad->fecha_inicio->format('d/m/Y') }} - {{ $this->actividad->fecha_fin->format('d/m/Y') }}</p>
            </div>
            <a href="{{ route('capacitacion.actividades') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                Volver a Actividades
            </a>
        </div>
    </div>

    {{-- Información de cupos y sesiones --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <div class="bg-blue-50 border-l-4 border-blue-400 p-4">
            <div class="flex items-center">
                <div class="flex-1">
                    <p class="text-sm font-medium text-blue-800">Cupos: {{ $this->actividad->total_inscritos }} / {{ $this->actividad->cupo_maximo }}</p>
                    <p class="text-sm text-blue-700">Disponibles: {{ $this->actividad->cupos_disponibles }}</p>
                </div>
                <div class="text-sm text-blue-700">
                    <span class="font-medium">Estado:</span> {{ $this->actividad->estado->getLabel() }}
                </div>
            </div>
        </div>

        <div class="bg-purple-50 border-l-4 border-purple-400 p-4">
            <div class="flex items-center">
                <div class="flex-1">
                    <p class="text-sm font-medium text-purple-800">Sesiones de la Actividad</p>
                    <p class="text-sm text-purple-700">Total: {{ $this->actividad->sesiones->count() }} sesiones programadas</p>
                </div>
                <div>
                    <button wire:click="$set('modalSesiones', true)"
                        class="px-3 py-1 text-sm font-medium text-purple-700 bg-purple-100 rounded-md hover:bg-purple-200">
                        Ver Sesiones
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Estadísticas --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-sm font-medium text-gray-500">Total Inscripciones</div>
            <div class="text-2xl font-bold text-gray-900">{{ $this->estadisticas['total'] }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-sm font-medium text-gray-500">Aprobadas</div>
            <div class="text-2xl font-bold text-green-600">{{ $this->estadisticas['aprobadas'] }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-sm font-medium text-gray-500">Pendientes</div>
            <div class="text-2xl font-bold text-yellow-600">{{ $this->estadisticas['pendientes'] }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-sm font-medium text-gray-500">Rechazadas</div>
            <div class="text-2xl font-bold text-red-600">{{ $this->estadisticas['rechazadas'] }}</div>
        </div>
    </div>

    {{-- Acceso a Aprobaciones --}}
    <div class="bg-gradient-to-r from-indigo-50 to-purple-50 border-l-4 border-indigo-500 rounded-lg shadow p-4 mb-6">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <h3 class="text-lg font-semibold text-indigo-900 mb-1">Gestor de Aprobaciones</h3>
                <p class="text-sm text-indigo-700">Aprobar/reprobar inscripciones y generar certificaciones automáticas</p>
            </div>
            <a href="{{ route('capacitacion.aprobaciones', $actividadId) }}"
                class="px-6 py-3 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 shadow-md hover:shadow-lg transition-all duration-200 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Ir a Aprobaciones
            </a>
        </div>
    </div>

    {{-- Filtros y acciones --}}
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="md:col-span-2">
                <input type="text" wire:model.live="busqueda" placeholder="Buscar por nombre o email..."
                    class="w-full px-3 py-2 border border-gray-300 rounded-md">
            </div>
            <div>
                <select wire:model.live="filtroEstado" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    <option value="">Todos los estados</option>
                    @foreach(\App\Enums\EstadoInscripcion::cases() as $estado)
                        <option value="{{ $estado->value }}">{{ $estado->getLabel() }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <button wire:click="limpiarFiltros" class="flex-1 px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                    Limpiar
                </button>
                <button wire:click="abrirModalInscribir" class="flex-1 px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-md hover:bg-green-700"
                    @if(!$this->actividad->puedeInscribirse()) disabled @endif>
                    + Inscribir
                </button>
                <button wire:click="abrirModalInscribirMultiple" class="flex-1 px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700"
                    @if(!$this->actividad->puedeInscribirse()) disabled @endif>
                    + Masivo
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Enfermero</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Área</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Asistencia</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($this->inscripciones as $inscripcion)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $inscripcion->enfermero->user->name }}</div>
                                <div class="text-sm text-gray-500">{{ $inscripcion->enfermero->user->email }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $inscripcion->enfermero->area->nombre ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                    style="background-color: {{ $inscripcion->tipo->getColor() }}20; color: {{ $inscripcion->tipo->getColor() }};">
                                    {{ $inscripcion->tipo->getLabel() }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium text-white"
                                    style="background-color: {{ $inscripcion->estado->getColor() }};">
                                    {{ $inscripcion->estado->getLabel() }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if($inscripcion->porcentaje_asistencia > 0)
                                    <div class="flex items-center">
                                        <span class="font-medium {{ $inscripcion->cumpleAsistenciaMinima() ? 'text-green-600' : 'text-red-600' }}">
                                            {{ number_format($inscripcion->porcentaje_asistencia, 1) }}%
                                        </span>
                                    </div>
                                @else
                                    <span class="text-gray-400">Sin registros</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $inscripcion->created_at->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button wire:click="abrirModalDetalles({{ $inscripcion->id }})"
                                    class="text-blue-600 hover:text-blue-900 mr-3">Ver</button>

                                @if($inscripcion->estado->value === 'pendiente')
                                    <button wire:click="aprobarInscripcion({{ $inscripcion->id }})"
                                        class="text-green-600 hover:text-green-900 mr-3">Aprobar</button>
                                @endif

                                @if(in_array($inscripcion->estado->value, ['pendiente', 'aprobada']))
                                    <button wire:click="abrirModalCancelar({{ $inscripcion->id }})"
                                        class="text-red-600 hover:text-red-900">Cancelar</button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                                No hay inscripciones registradas
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

    {{-- Modal Inscribir Individual --}}
    @if($modalInscribir)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Inscribir Enfermero</h3>
                </div>

                <form wire:submit="inscribirEnfermero" class="px-6 py-4 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Enfermero *</label>
                        <select wire:model="enfermeroSeleccionado" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md">
                            <option value="">Seleccionar enfermero...</option>
                            @foreach($this->enfermerosDisponibles as $enfermero)
                                <option value="{{ $enfermero->id }}">
                                    {{ $enfermero->user->name }} - {{ $enfermero->area->nombre ?? 'Sin área' }}
                                </option>
                            @endforeach
                        </select>
                        @error('enfermeroSeleccionado') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tipo de Inscripción *</label>
                        <select wire:model="tipoInscripcion" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md">
                            @foreach(\App\Enums\TipoInscripcion::cases() as $tipo)
                                <option value="{{ $tipo->value }}">{{ $tipo->getLabel() }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Observaciones</label>
                        <textarea wire:model="observaciones" rows="3" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md"></textarea>
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t">
                        <button type="button" wire:click="$set('modalInscribir', false)"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                            Cancelar
                        </button>
                        <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-md hover:bg-green-700">
                            Inscribir
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Modal Inscribir Múltiple --}}
    @if($modalInscribirMultiple)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Inscripción Masiva</h3>
                    <p class="text-sm text-gray-600 mt-1">Seleccionados: {{ count($enfermerosSeleccionados) }}</p>
                </div>

                <form wire:submit="inscribirMultiples" class="px-6 py-4 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Filtrar por área</label>
                        <select wire:model.live="filtroArea" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                            <option value="">Todas las áreas</option>
                            @foreach(\App\Models\Area::orderBy('nombre')->get() as $area)
                                <option value="{{ $area->id }}">{{ $area->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="max-h-96 overflow-y-auto border border-gray-200 rounded-md">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50 sticky top-0">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">
                                        <input type="checkbox"
                                            @if(count($enfermerosSeleccionados) === count($this->enfermerosDisponibles)) checked @endif
                                            wire:click="$set('enfermerosSeleccionados', {{ count($enfermerosSeleccionados) === count($this->enfermerosDisponibles) ? '[]' : json_encode($this->enfermerosDisponibles->pluck('id')->toArray()) }})"
                                            class="rounded">
                                    </th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Enfermero</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Área</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($this->enfermerosDisponibles as $enfermero)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-2">
                                            <input type="checkbox"
                                                wire:click="toggleEnfermeroSeleccionado({{ $enfermero->id }})"
                                                @if(in_array($enfermero->id, $enfermerosSeleccionados)) checked @endif
                                                class="rounded">
                                        </td>
                                        <td class="px-4 py-2 text-sm">{{ $enfermero->user->name }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-500">{{ $enfermero->area->nombre ?? 'N/A' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tipo de Inscripción *</label>
                        <select wire:model="tipoInscripcion" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md">
                            @foreach(\App\Enums\TipoInscripcion::cases() as $tipo)
                                <option value="{{ $tipo->value }}">{{ $tipo->getLabel() }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Observaciones</label>
                        <textarea wire:model="observaciones" rows="2" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md"></textarea>
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t">
                        <button type="button" wire:click="$set('modalInscribirMultiple', false)"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                            Cancelar
                        </button>
                        <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">
                            Inscribir {{ count($enfermerosSeleccionados) }} Enfermeros
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Modal Cancelar Inscripción --}}
    @if($modalCancelar)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Cancelar Inscripción</h3>
                </div>

                <form wire:submit="cancelarInscripcion" class="px-6 py-4 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Motivo de Cancelación *</label>
                        <textarea wire:model="motivoCancelacion" rows="4" required
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md"
                            placeholder="Explique el motivo de la cancelación (mínimo 10 caracteres)"></textarea>
                        @error('motivoCancelacion') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t">
                        <button type="button" wire:click="$set('modalCancelar', false)"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                            Volver
                        </button>
                        <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700">
                            Confirmar Cancelación
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Modal Detalles --}}
    @if($modalDetalles && $inscripcionSeleccionada)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-3xl w-full max-h-[90vh] overflow-y-auto">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Detalles de Inscripción</h3>
                </div>

                <div class="px-6 py-4 space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Enfermero</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $inscripcionSeleccionada->enfermero->user->name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Área</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $inscripcionSeleccionada->enfermero->area->nombre ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Tipo de Inscripción</label>
                            <p class="mt-1"><span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                style="background-color: {{ $inscripcionSeleccionada->tipo->getColor() }}20; color: {{ $inscripcionSeleccionada->tipo->getColor() }};">
                                {{ $inscripcionSeleccionada->tipo->getLabel() }}
                            </span></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Estado</label>
                            <p class="mt-1"><span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium text-white"
                                style="background-color: {{ $inscripcionSeleccionada->estado->getColor() }};">
                                {{ $inscripcionSeleccionada->estado->getLabel() }}
                            </span></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Fecha de Inscripción</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $inscripcionSeleccionada->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Inscrito Por</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $inscripcionSeleccionada->inscritoPor->name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">% Asistencia</label>
                            <p class="mt-1 text-sm font-medium {{ $inscripcionSeleccionada->cumpleAsistenciaMinima() ? 'text-green-600' : 'text-red-600' }}">
                                {{ number_format($inscripcionSeleccionada->porcentaje_asistencia, 1) }}%
                                @if($inscripcionSeleccionada->cumpleAsistenciaMinima())
                                    (Cumple)
                                @else
                                    (No cumple - Mínimo: {{ $inscripcionSeleccionada->actividad->porcentaje_asistencia_minimo }}%)
                                @endif
                            </p>
                        </div>
                    </div>

                    @if($inscripcionSeleccionada->observaciones)
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Observaciones</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $inscripcionSeleccionada->observaciones }}</p>
                        </div>
                    @endif

                    <div class="flex justify-end pt-4 border-t">
                        <button wire:click="$set('modalDetalles', false)"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                            Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Modal Sesiones --}}
    @if($modalSesiones ?? false)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Sesiones de la Actividad</h3>
                    <p class="text-sm text-gray-600 mt-1">{{ $this->actividad->titulo }}</p>
                </div>

                <div class="px-6 py-4">
                    @if($this->actividad->sesiones->count() > 0)
                        <div class="space-y-3">
                            @foreach($this->actividad->sesiones->sortBy('numero_sesion') as $sesion)
                                <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50">
                                    <div class="flex items-center justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-3">
                                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-purple-100 text-purple-700 font-bold text-sm">
                                                    {{ $sesion->numero_sesion }}
                                                </span>
                                                <div>
                                                    <h4 class="text-sm font-medium text-gray-900">
                                                        {{ $sesion->titulo ?? 'Sesión ' . $sesion->numero_sesion }}
                                                    </h4>
                                                    <p class="text-sm text-gray-500">
                                                        {{ $sesion->fecha->format('d/m/Y') }}
                                                        @if($sesion->hora_inicio)
                                                            | {{ $sesion->hora_inicio }} - {{ $sesion->hora_fin }}
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>
                                            @if($sesion->asistencia_registrada)
                                                <div class="mt-2 flex items-center gap-4 text-xs text-gray-600">
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-green-100 text-green-700">
                                                        ✓ Asistencia registrada
                                                    </span>
                                                    <span>{{ $sesion->total_asistentes }}/{{ $sesion->total_inscritos }} presentes</span>
                                                    <span class="font-medium text-blue-600">{{ $sesion->porcentaje_asistencia }}%</span>
                                                </div>
                                            @else
                                                <div class="mt-2">
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-yellow-100 text-yellow-700">
                                                        ⏳ Pendiente de registro
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <a href="{{ route('capacitacion.asistencia', ['actividadId' => $actividadId, 'sesionId' => $sesion->id]) }}"
                                                class="px-4 py-2 text-sm font-medium text-white bg-purple-600 rounded-md hover:bg-purple-700">
                                                {{ $sesion->asistencia_registrada ? 'Ver/Editar' : 'Registrar' }} Asistencia
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <p class="text-sm text-gray-500">No hay sesiones programadas para esta actividad.</p>
                            <p class="text-xs text-gray-400 mt-1">Las sesiones deben ser creadas desde el Gestor de Actividades.</p>
                        </div>
                    @endif
                </div>

                <div class="px-6 py-4 bg-gray-50 flex justify-end">
                    <button wire:click="$set('modalSesiones', false)"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
