<div class="p-6">
    {{-- Header con información de la sesión --}}
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Control de Asistencia - Sesión #{{ $this->sesion->numero_sesion }}</h1>
                <p class="text-gray-600 mt-1">
                    {{ $this->actividad->titulo }} | {{ $this->sesion->fecha->format('d/m/Y') }}
                    @if($this->sesion->hora_inicio)
                        {{ $this->sesion->hora_inicio }} - {{ $this->sesion->hora_fin }}
                    @endif
                </p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('capacitacion.inscripciones', $actividadId) }}"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                    Volver a Inscripciones
                </a>
                <button wire:click="abrirModalDetallesSesion"
                    class="px-4 py-2 text-sm font-medium text-blue-700 bg-blue-100 rounded-md hover:bg-blue-200">
                    Info de Sesión
                </button>
            </div>
        </div>
    </div>

    {{-- Alerta de sesión ya registrada --}}
    @if($this->sesion->asistencia_registrada)
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
            <div class="flex items-center">
                <div class="flex-1">
                    <p class="text-sm font-medium text-yellow-800">
                        Esta sesión ya tiene asistencia registrada
                    </p>
                    <p class="text-sm text-yellow-700">
                        Registrada por {{ $this->sesion->registradaPor->name ?? 'Usuario' }}
                        el {{ $this->sesion->registrada_at?->format('d/m/Y H:i') }}
                    </p>
                </div>
                <div class="text-sm text-yellow-700">
                    Puedes modificar y guardar nuevamente
                </div>
            </div>
        </div>
    @endif

    {{-- Estadísticas en tiempo real --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-sm font-medium text-gray-500">Total Inscritos</div>
            <div class="text-2xl font-bold text-gray-900">{{ count($this->asistencias) }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-sm font-medium text-gray-500">Presentes</div>
            <div class="text-2xl font-bold text-green-600">{{ $totalPresentes }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-sm font-medium text-gray-500">Ausentes</div>
            <div class="text-2xl font-bold text-red-600">{{ $totalAusentes }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-sm font-medium text-gray-500">% Asistencia</div>
            <div class="text-2xl font-bold text-blue-600">
                {{ count($this->asistencias) > 0 ? round(($totalPresentes / count($this->asistencias)) * 100, 1) : 0 }}%
            </div>
        </div>
    </div>

    {{-- Controles y filtros --}}
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="md:col-span-2">
                <input type="text" wire:model.live="busqueda" placeholder="Buscar enfermero..."
                    class="w-full px-3 py-2 border border-gray-300 rounded-md">
            </div>
            <div class="flex items-center">
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" wire:model.live="mostrarSoloAusentes" class="rounded mr-2">
                    <span class="text-sm text-gray-700">Solo ausentes</span>
                </label>
            </div>
            <div class="flex gap-2">
                <button wire:click="marcarTodosPresentes"
                    class="flex-1 px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-md hover:bg-green-700">
                    Todos ✓
                </button>
                <button wire:click="marcarTodosAusentes"
                    class="flex-1 px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700">
                    Todos ✗
                </button>
            </div>
        </div>
    </div>

    {{-- Lista de asistencia --}}
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase w-16">Asistencia</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Enfermero</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Área</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipo Inscripción</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Hora Entrada</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($this->inscripciones as $inscripcion)
                        @php
                            $asistencia = $this->asistencias[$inscripcion->id] ?? null;
                            $presente = $asistencia ? $asistencia['presente'] : false;
                        @endphp
                        <tr class="hover:bg-gray-50 {{ $presente ? 'bg-green-50' : 'bg-red-50' }}">
                            <td class="px-6 py-4 text-center">
                                <input type="checkbox"
                                    wire:click="toggleAsistencia({{ $inscripcion->id }})"
                                    @if($presente) checked @endif
                                    class="w-6 h-6 rounded {{ $presente ? 'text-green-600' : 'text-red-600' }}">
                            </td>
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
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if($asistencia && $asistencia['hora_entrada'])
                                    <span class="font-mono">{{ \Carbon\Carbon::parse($asistencia['hora_entrada'])->format('H:i') }}</span>
                                @else
                                    <span class="text-gray-400">--:--</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($presente)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        ✓ Presente
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        ✗ Ausente
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                No hay inscritos para esta actividad
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Botón de guardar --}}
    <div class="flex justify-end gap-3">
        <a href="{{ route('capacitacion.inscripciones', $actividadId) }}"
            class="px-6 py-3 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
            Cancelar
        </a>
        <button wire:click="abrirModalConfirmarGuardar"
            class="px-6 py-3 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">
            💾 Guardar Asistencias
        </button>
    </div>

    {{-- Modal Confirmar Guardar --}}
    @if($modalConfirmarGuardar)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Confirmar Registro de Asistencia</h3>
                </div>

                <div class="px-6 py-4">
                    <p class="text-sm text-gray-600 mb-4">
                        Estás a punto de guardar la asistencia para esta sesión con los siguientes datos:
                    </p>

                    <div class="bg-gray-50 rounded-lg p-4 space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Presentes:</span>
                            <span class="font-medium text-green-600">{{ $totalPresentes }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Ausentes:</span>
                            <span class="font-medium text-red-600">{{ $totalAusentes }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Total:</span>
                            <span class="font-medium text-gray-900">{{ count($this->asistencias) }}</span>
                        </div>
                    </div>

                    <p class="text-sm text-gray-600 mt-4">
                        Los porcentajes de asistencia de cada inscripción se recalcularán automáticamente.
                    </p>
                </div>

                <div class="px-6 py-4 bg-gray-50 flex justify-end gap-3">
                    <button wire:click="$set('modalConfirmarGuardar', false)"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                        Cancelar
                    </button>
                    <button wire:click="guardarAsistencias"
                        class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">
                        Confirmar y Guardar
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Modal Detalles de Sesión --}}
    @if($modalDetallesSesion)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Detalles de la Sesión</h3>
                </div>

                <div class="px-6 py-4 space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Actividad</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $this->actividad->titulo }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Número de Sesión</label>
                            <p class="mt-1 text-sm text-gray-900">#{{ $this->sesion->numero_sesion }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Fecha</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $this->sesion->fecha->format('d/m/Y') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Horario</label>
                            <p class="mt-1 text-sm text-gray-900">
                                @if($this->sesion->hora_inicio)
                                    {{ $this->sesion->hora_inicio }} - {{ $this->sesion->hora_fin }}
                                @else
                                    No especificado
                                @endif
                            </p>
                        </div>
                        @if($this->sesion->titulo)
                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-500">Título</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $this->sesion->titulo }}</p>
                            </div>
                        @endif
                        @if($this->sesion->descripcion)
                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-500">Descripción</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $this->sesion->descripcion }}</p>
                            </div>
                        @endif
                        @if($this->sesion->ubicacion)
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Ubicación</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $this->sesion->ubicacion }}</p>
                            </div>
                        @endif
                        @if($this->sesion->instructor_nombre)
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Instructor</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $this->sesion->instructor_nombre }}</p>
                            </div>
                        @endif
                    </div>

                    @if($this->sesion->asistencia_registrada)
                        <div class="border-t pt-4">
                            <h4 class="text-sm font-medium text-gray-900 mb-2">Estadísticas de Asistencia</h4>
                            <div class="grid grid-cols-3 gap-4">
                                <div class="bg-gray-50 rounded-lg p-3 text-center">
                                    <div class="text-2xl font-bold text-gray-900">{{ $this->sesion->total_asistentes }}</div>
                                    <div class="text-xs text-gray-500">Presentes</div>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-3 text-center">
                                    <div class="text-2xl font-bold text-gray-900">{{ $this->sesion->total_inscritos }}</div>
                                    <div class="text-xs text-gray-500">Total</div>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-3 text-center">
                                    <div class="text-2xl font-bold text-blue-600">{{ $this->sesion->porcentaje_asistencia }}%</div>
                                    <div class="text-xs text-gray-500">Asistencia</div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="px-6 py-4 bg-gray-50 flex justify-end">
                    <button wire:click="$set('modalDetallesSesion', false)"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
