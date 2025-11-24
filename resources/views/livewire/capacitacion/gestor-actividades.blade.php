<div class="p-6">
    {{-- Header --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Gestor de Actividades de Capacitación</h1>
        <p class="text-gray-600 mt-1">Administra el catálogo de actividades de capacitación del hospital</p>
    </div>

    {{-- Estadísticas --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-sm font-medium text-gray-500">Total Actividades</div>
            <div class="text-2xl font-bold text-gray-900">{{ $this->estadisticas['total'] }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-sm font-medium text-gray-500">Actividades Activas</div>
            <div class="text-2xl font-bold text-green-600">{{ $this->estadisticas['activas'] }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-sm font-medium text-gray-500">Inscripciones Abiertas</div>
            <div class="text-2xl font-bold text-blue-600">{{ $this->estadisticas['inscripciones_abiertas'] }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-sm font-medium text-gray-500">Próximas</div>
            <div class="text-2xl font-bold text-purple-600">{{ $this->estadisticas['proximas'] }}</div>
        </div>
    </div>

    {{-- Filtros y búsqueda --}}
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div class="md:col-span-2">
                <input type="text" wire:model.live="busqueda" placeholder="Buscar por título, descripción o instructor..."
                    class="w-full px-3 py-2 border border-gray-300 rounded-md">
            </div>
            <div>
                <select wire:model.live="filtroEstado" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    <option value="">Todos los estados</option>
                    @foreach(\App\Enums\EstadoActividad::cases() as $estado)
                        <option value="{{ $estado->value }}">{{ $estado->getLabel() }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <select wire:model.live="filtroTipo" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    <option value="">Todos los tipos</option>
                    @foreach(\App\Enums\TipoActividad::cases() as $tipo)
                        <option value="{{ $tipo->value }}">{{ $tipo->getLabel() }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <button wire:click="limpiarFiltros" class="flex-1 px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                    Limpiar
                </button>
                <button wire:click="abrirModalCrear" class="flex-1 px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">
                    + Nueva
                </button>
            </div>
        </div>
    </div>

    {{-- Lista de actividades --}}
    <div class="bg-white rounded-lg shadow">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actividad</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fechas</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cupos</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Inscritos</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($this->actividades as $actividad)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $actividad->titulo }}</div>
                                <div class="text-sm text-gray-500">{{ Str::limit($actividad->descripcion, 60) }}</div>
                                @if($actividad->instructor_nombre)
                                    <div class="text-xs text-gray-400 mt-1">Instructor: {{ $actividad->instructor_nombre }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                    style="background-color: {{ $actividad->tipo->getColor() }}20; color: {{ $actividad->tipo->getColor() }};">
                                    {{ $actividad->tipo->getIcon() }} {{ $actividad->tipo->getLabel() }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium text-white"
                                    style="background-color: {{ $actividad->estado->getColor() }};">
                                    {{ $actividad->estado->getIcon() }} {{ $actividad->estado->getLabel() }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div>{{ $actividad->fecha_inicio->format('d/m/Y') }}</div>
                                <div class="text-xs text-gray-400">{{ $actividad->duracion_horas }}h</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $actividad->cupo_minimo }}-{{ $actividad->cupo_maximo }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-medium text-gray-900">{{ $actividad->inscripciones_count }}</span>
                                <span class="text-sm text-gray-500">/ {{ $actividad->cupo_maximo }}</span>
                                @if($actividad->inscripciones_count >= $actividad->cupo_maximo)
                                    <span class="ml-1 text-xs text-red-600 font-medium">Lleno</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('capacitacion.inscripciones', $actividad->id) }}"
                                    class="text-green-600 hover:text-green-900 mr-3">Inscripciones</a>
                                <button wire:click="abrirModalDetalles({{ $actividad->id }})"
                                    class="text-blue-600 hover:text-blue-900 mr-3">Ver</button>
                                <button wire:click="abrirModalEditar({{ $actividad->id }})"
                                    class="text-indigo-600 hover:text-indigo-900 mr-3">Editar</button>
                                <button wire:click="abrirModalEliminar({{ $actividad->id }})"
                                    class="text-red-600 hover:text-red-900">Eliminar</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                                No se encontraron actividades de capacitación
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-gray-200">
            {{ $this->actividades->links() }}
        </div>
    </div>

    {{-- Modal Crear --}}
    @if($modalCrear)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Nueva Actividad de Capacitación</h3>
                </div>

                <form wire:submit="crearActividad" class="px-6 py-4 space-y-4">
                    {{-- Información básica --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Título *</label>
                            <input type="text" wire:model="titulo" required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md">
                            @error('titulo') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Descripción *</label>
                            <textarea wire:model="descripcion" rows="3" required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md"></textarea>
                            @error('descripcion') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tipo *</label>
                            <select wire:model="tipo" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md">
                                <option value="">Seleccionar...</option>
                                @foreach(\App\Enums\TipoActividad::cases() as $t)
                                    <option value="{{ $t->value }}">{{ $t->getLabel() }}</option>
                                @endforeach
                            </select>
                            @error('tipo') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Modalidad *</label>
                            <select wire:model="modalidad" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md">
                                <option value="presencial">Presencial</option>
                                <option value="virtual">Virtual</option>
                                <option value="hibrida">Híbrida</option>
                            </select>
                            @error('modalidad') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>

                        @if(in_array($modalidad, ['presencial', 'hibrida']))
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Ubicación</label>
                                <input type="text" wire:model="ubicacion" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md">
                            </div>
                        @endif

                        @if(in_array($modalidad, ['virtual', 'hibrida']))
                            <div>
                                <label class="block text-sm font-medium text-gray-700">URL Virtual</label>
                                <input type="url" wire:model="url_virtual" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md">
                            </div>
                        @endif

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Duración (horas) *</label>
                            <input type="number" wire:model="duracion_horas" min="1" required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md">
                            @error('duracion_horas') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Área</label>
                            <select wire:model="area_id" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md">
                                <option value="">Sin área específica</option>
                                @foreach($this->areas as $area)
                                    <option value="{{ $area->id }}">{{ $area->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Cupo Mínimo *</label>
                            <input type="number" wire:model="cupo_minimo" min="1" required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Cupo Máximo *</label>
                            <input type="number" wire:model="cupo_maximo" min="1" required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Fecha Inicio *</label>
                            <input type="date" wire:model="fecha_inicio" required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Fecha Fin *</label>
                            <input type="date" wire:model="fecha_fin" required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Hora Inicio</label>
                            <input type="time" wire:model="hora_inicio"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Hora Fin</label>
                            <input type="time" wire:model="hora_fin"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Límite Inscripción</label>
                            <input type="date" wire:model="fecha_limite_inscripcion"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Instructor</label>
                            <input type="text" wire:model="instructor_nombre"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Credenciales Instructor</label>
                            <input type="text" wire:model="instructor_credenciales"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">% Asistencia Mínimo *</label>
                            <input type="number" wire:model="porcentaje_asistencia_minimo" min="0" max="100" step="0.01" required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Calificación Mínima</label>
                            <input type="number" wire:model="calificacion_minima_aprobacion" min="0" max="100" step="0.01"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md">
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" wire:model="otorga_certificado" id="otorga_certificado"
                                class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                            <label for="otorga_certificado" class="ml-2 block text-sm text-gray-700">
                                Otorga Certificado
                            </label>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t">
                        <button type="button" wire:click="$set('modalCrear', false)"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                            Cancelar
                        </button>
                        <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">
                            Crear Actividad
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Modal Editar (similar structure, omitted for brevity) --}}
    {{-- Modal Eliminar --}}
    @if($modalEliminar)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
                <div class="px-6 py-4">
                    <h3 class="text-lg font-medium text-gray-900">Confirmar Eliminación</h3>
                    <p class="mt-2 text-sm text-gray-500">
                        ¿Está seguro de eliminar esta actividad? Esta acción no se puede deshacer.
                    </p>
                </div>
                <div class="px-6 py-4 bg-gray-50 flex justify-end gap-3">
                    <button wire:click="$set('modalEliminar', false)"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                        Cancelar
                    </button>
                    <button wire:click="eliminarActividad"
                        class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700">
                        Eliminar
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Modal Detalles --}}
    @if($modalDetalles && $actividadSeleccionada)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-3xl w-full max-h-[90vh] overflow-y-auto">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900">Detalles de la Actividad</h3>
                    <button wire:click="$set('modalDetalles', false)" class="text-gray-400 hover:text-gray-500">
                        <span class="text-2xl">&times;</span>
                    </button>
                </div>

                <div class="px-6 py-4 space-y-4">
                    <div>
                        <h4 class="text-xl font-bold text-gray-900">{{ $actividadSeleccionada->titulo }}</h4>
                        <div class="flex gap-2 mt-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                style="background-color: {{ $actividadSeleccionada->tipo->getColor() }}20; color: {{ $actividadSeleccionada->tipo->getColor() }};">
                                {{ $actividadSeleccionada->tipo->getIcon() }} {{ $actividadSeleccionada->tipo->getLabel() }}
                            </span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium text-white"
                                style="background-color: {{ $actividadSeleccionada->estado->getColor() }};">
                                {{ $actividadSeleccionada->estado->getIcon() }} {{ $actividadSeleccionada->estado->getLabel() }}
                            </span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="font-medium text-gray-700">Modalidad:</span>
                            <span class="text-gray-900">{{ ucfirst($actividadSeleccionada->modalidad) }}</span>
                        </div>
                        <div>
                            <span class="font-medium text-gray-700">Duración:</span>
                            <span class="text-gray-900">{{ $actividadSeleccionada->duracion_horas }} horas</span>
                        </div>
                        <div>
                            <span class="font-medium text-gray-700">Cupos:</span>
                            <span class="text-gray-900">{{ $actividadSeleccionada->cupo_minimo }}-{{ $actividadSeleccionada->cupo_maximo }}</span>
                        </div>
                        <div>
                            <span class="font-medium text-gray-700">Inscritos:</span>
                            <span class="text-gray-900">{{ $actividadSeleccionada->inscripciones->count() }}</span>
                        </div>
                        <div>
                            <span class="font-medium text-gray-700">Fecha Inicio:</span>
                            <span class="text-gray-900">{{ $actividadSeleccionada->fecha_inicio->format('d/m/Y') }}</span>
                        </div>
                        <div>
                            <span class="font-medium text-gray-700">Fecha Fin:</span>
                            <span class="text-gray-900">{{ $actividadSeleccionada->fecha_fin->format('d/m/Y') }}</span>
                        </div>
                    </div>

                    <div>
                        <h5 class="font-medium text-gray-700 mb-1">Descripción</h5>
                        <p class="text-gray-900 text-sm">{{ $actividadSeleccionada->descripcion }}</p>
                    </div>

                    @if($actividadSeleccionada->instructor_nombre)
                        <div>
                            <h5 class="font-medium text-gray-700 mb-1">Instructor</h5>
                            <p class="text-gray-900 text-sm">{{ $actividadSeleccionada->instructor_nombre }}</p>
                            @if($actividadSeleccionada->instructor_credenciales)
                                <p class="text-gray-600 text-xs">{{ $actividadSeleccionada->instructor_credenciales }}</p>
                            @endif
                        </div>
                    @endif
                </div>

                <div class="px-6 py-4 bg-gray-50 flex justify-end">
                    <button wire:click="$set('modalDetalles', false)"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
