<div class="p-6">
    {{-- Header --}}
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Calendario de Capacitaciones</h1>
        <p class="text-gray-600 mt-1">Vista del personal en capacitación y sesiones programadas</p>
    </div>

    {{-- Estadísticas del Mes --}}
    <div class="grid grid-cols-1 md:grid-cols-6 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-xs font-medium text-gray-500 uppercase">Actividades</div>
            <div class="text-2xl font-bold text-blue-600">{{ $this->estadisticasMes['totalActividades'] }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-xs font-medium text-gray-500 uppercase">Sesiones</div>
            <div class="text-2xl font-bold text-purple-600">{{ $this->estadisticasMes['totalSesiones'] }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-xs font-medium text-gray-500 uppercase">En Curso</div>
            <div class="text-2xl font-bold text-green-600">{{ $this->estadisticasMes['actividadesEnCurso'] }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-xs font-medium text-gray-500 uppercase">Hoy</div>
            <div class="text-2xl font-bold text-orange-600">{{ $this->estadisticasMes['sesionesHoy'] }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-xs font-medium text-gray-500 uppercase">Enfermeros</div>
            <div class="text-2xl font-bold text-indigo-600">{{ $this->estadisticasMes['enfermerosCapacitandose'] }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-xs font-medium text-gray-500 uppercase">Horas Totales</div>
            <div class="text-2xl font-bold text-teal-600">{{ $this->estadisticasMes['horasTotales'] }}</div>
        </div>
    </div>

    {{-- Controles y Filtros --}}
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Área</label>
                <select wire:model.live="filtroArea" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    <option value="">Todas las áreas</option>
                    @foreach($this->areasDisponibles as $area)
                        <option value="{{ $area->id }}">{{ $area->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Enfermero</label>
                <select wire:model.live="filtroEnfermero" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    <option value="">Todos los enfermeros</option>
                    @foreach($this->enfermerosArea as $enfermero)
                        <option value="{{ $enfermero->id }}">{{ $enfermero->user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-2 flex items-end gap-2">
                <button wire:click="limpiarFiltros" class="flex-1 px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                    Limpiar Filtros
                </button>
                <button wire:click="cambiarVista('mes')" class="px-4 py-2 text-sm font-medium {{ $vistaCalendario === 'mes' ? 'text-white bg-blue-600' : 'text-gray-700 bg-gray-100' }} rounded-md hover:opacity-80">
                    Mes
                </button>
                <button wire:click="cambiarVista('lista')" class="px-4 py-2 text-sm font-medium {{ $vistaCalendario === 'lista' ? 'text-white bg-blue-600' : 'text-gray-700 bg-gray-100' }} rounded-md hover:opacity-80">
                    Lista
                </button>
            </div>
        </div>
    </div>

    {{-- Vista de Calendario --}}
    @if($vistaCalendario === 'mes')
        {{-- Navegación del Mes --}}
        <div class="bg-white rounded-lg shadow mb-6">
            <div class="flex items-center justify-between p-4 border-b">
                <button wire:click="mesAnterior" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                    ‹ Anterior
                </button>
                <h2 class="text-xl font-bold text-gray-900">
                    {{ \Carbon\Carbon::create($añoActual, $mesActual)->locale('es')->translatedFormat('F Y') }}
                </h2>
                <div class="flex gap-2">
                    <button wire:click="irAHoy" class="px-4 py-2 text-sm font-medium text-blue-600 bg-blue-50 rounded-md hover:bg-blue-100">
                        Hoy
                    </button>
                    <button wire:click="mesSiguiente" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                        Siguiente ›
                    </button>
                </div>
            </div>

            {{-- Días de la Semana --}}
            <div class="grid grid-cols-7 border-b">
                @foreach(['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'] as $dia)
                    <div class="p-3 text-center text-sm font-semibold text-gray-700 border-r last:border-r-0">
                        {{ $dia }}
                    </div>
                @endforeach
            </div>

            {{-- Grid del Calendario --}}
            <div class="grid grid-cols-7">
                @php
                    $primerDia = \Carbon\Carbon::create($añoActual, $mesActual, 1);
                    $diaSemanaInicio = $primerDia->dayOfWeek;

                    // Rellenar días vacíos al inicio
                    for ($i = 0; $i < $diaSemanaInicio; $i++) {
                        echo '<div class="min-h-[120px] bg-gray-50 border-r border-b"></div>';
                    }
                @endphp

                @foreach($this->diasDelMes as $dia)
                    @php
                        $fechaStr = $dia['fecha']->format('Y-m-d');
                        $sesionesDelDia = $this->sesionesDelMes->get($fechaStr, collect());
                        $tieneSesiones = $sesionesDelDia->isNotEmpty();
                    @endphp
                    <div class="min-h-[120px] p-2 border-r border-b hover:bg-gray-50 {{ $dia['esHoy'] ? 'bg-blue-50' : '' }} cursor-pointer"
                         wire:click="abrirModalDetallesDia('{{ $fechaStr }}')">
                        <div class="flex justify-between items-start mb-2">
                            <span class="text-sm font-medium {{ $dia['esHoy'] ? 'text-blue-600 font-bold' : 'text-gray-900' }}">
                                {{ $dia['fecha']->day }}
                            </span>
                            @if($tieneSesiones)
                                <span class="px-2 py-0.5 text-xs font-semibold bg-purple-100 text-purple-800 rounded-full">
                                    {{ $sesionesDelDia->count() }}
                                </span>
                            @endif
                        </div>

                        @if($tieneSesiones)
                            <div class="space-y-1">
                                @foreach($sesionesDelDia->take(2) as $sesion)
                                    <div class="text-xs p-1 rounded {{ $sesion->asistencia_registrada ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}"
                                         wire:click.stop="abrirModalDetallesSesion({{ $sesion->id }})">
                                        <div class="font-medium truncate">{{ $sesion->actividad->titulo }}</div>
                                        <div class="text-xs">{{ $sesion->hora_inicio }}</div>
                                    </div>
                                @endforeach
                                @if($sesionesDelDia->count() > 2)
                                    <div class="text-xs text-gray-500 text-center">
                                        +{{ $sesionesDelDia->count() - 2 }} más
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                @endforeach

                @php
                    // Rellenar días vacíos al final
                    $ultimoDia = \Carbon\Carbon::create($añoActual, $mesActual)->endOfMonth();
                    $diaSemanaFin = $ultimoDia->dayOfWeek;
                    $diasRestantes = 6 - $diaSemanaFin;

                    for ($i = 0; $i < $diasRestantes; $i++) {
                        echo '<div class="min-h-[120px] bg-gray-50 border-r border-b"></div>';
                    }
                @endphp
            </div>
        </div>
    @endif

    {{-- Vista de Lista --}}
    @if($vistaCalendario === 'lista')
        <div class="bg-white rounded-lg shadow">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Sesión</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actividad</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Horario</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Área</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Inscritos</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Asistencia</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($this->sesionesDelMes->flatten()->sortBy('fecha') as $sesion)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $sesion->fecha->format('d/m/Y') }}</div>
                                    <div class="text-sm text-gray-500">{{ $sesion->fecha->locale('es')->translatedFormat('l') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">Sesión {{ $sesion->numero_sesion }}</div>
                                    <div class="text-sm text-gray-500">{{ $sesion->titulo }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $sesion->actividad->titulo }}</div>
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $sesion->actividad->tipo->getColor() }}">
                                        {{ $sesion->actividad->tipo->getLabel() }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $sesion->hora_inicio }} - {{ $sesion->hora_fin }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $sesion->actividad->area->nombre ?? 'Todas' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $sesion->actividad->inscripciones->whereIn('estado', ['pendiente', 'aprobada'])->count() }} enfermeros
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($sesion->asistencia_registrada)
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            Registrada
                                        </span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Pendiente
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button wire:click="abrirModalDetallesSesion({{ $sesion->id }})"
                                        class="text-blue-600 hover:text-blue-900 mr-3">
                                        Ver Detalles
                                    </button>
                                    @if(auth()->user()->hasAnyRole(['coordinador', 'admin']))
                                        <a href="{{ route('capacitacion.asistencia', ['actividadId' => $sesion->actividad_id, 'sesionId' => $sesion->id]) }}"
                                            class="text-green-600 hover:text-green-900">
                                            {{ $sesion->asistencia_registrada ? 'Editar' : 'Registrar' }} Asistencia
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">No hay sesiones</h3>
                                    <p class="mt-1 text-sm text-gray-500">No hay sesiones programadas para este mes</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    {{-- Modal Detalles del Día --}}
    @if($modalDetallesDia && $fechaSeleccionada)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl max-w-3xl w-full mx-4 max-h-[90vh] overflow-y-auto">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">
                                {{ \Carbon\Carbon::parse($fechaSeleccionada)->locale('es')->translatedFormat('l, d \d\e F \d\e Y') }}
                            </h2>
                            <p class="text-sm text-gray-600 mt-1">{{ $sesionesDelDia->count() }} sesiones programadas</p>
                        </div>
                        <button wire:click="$set('modalDetallesDia', false)" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    @if($sesionesDelDia->isNotEmpty())
                        <div class="space-y-4">
                            @foreach($sesionesDelDia->sortBy('hora_inicio') as $sesion)
                                <div class="border rounded-lg p-4 hover:bg-gray-50 cursor-pointer"
                                     wire:click="abrirModalDetallesSesion({{ $sesion->id }})">
                                    <div class="flex items-start justify-between mb-2">
                                        <div>
                                            <h3 class="text-lg font-bold text-gray-900">{{ $sesion->actividad->titulo }}</h3>
                                            <p class="text-sm text-gray-600">Sesión {{ $sesion->numero_sesion }}: {{ $sesion->titulo }}</p>
                                        </div>
                                        @if($sesion->asistencia_registrada)
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                Asistencia Registrada
                                            </span>
                                        @else
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                Pendiente
                                            </span>
                                        @endif
                                    </div>

                                    <div class="grid grid-cols-2 gap-4 mb-3">
                                        <div class="flex items-center text-sm text-gray-600">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            {{ $sesion->hora_inicio }} - {{ $sesion->hora_fin }}
                                        </div>
                                        <div class="flex items-center text-sm text-gray-600">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                            </svg>
                                            {{ $sesion->actividad->area->nombre ?? 'Todas las áreas' }}
                                        </div>
                                        @if($sesion->ubicacion)
                                            <div class="flex items-center text-sm text-gray-600">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                </svg>
                                                {{ $sesion->ubicacion }}
                                            </div>
                                        @endif
                                        <div class="flex items-center text-sm text-gray-600">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                            </svg>
                                            {{ $sesion->actividad->inscripciones->whereIn('estado', ['pendiente', 'aprobada'])->count() }} inscritos
                                        </div>
                                    </div>

                                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $sesion->actividad->tipo->getColor() }}">
                                        {{ $sesion->actividad->tipo->getLabel() }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No hay sesiones</h3>
                            <p class="mt-1 text-sm text-gray-500">No hay sesiones programadas para este día</p>
                        </div>
                    @endif

                    <div class="mt-6">
                        <button wire:click="$set('modalDetallesDia', false)"
                            class="w-full px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                            Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Modal Detalles Sesión --}}
    @if($modalDetallesSesion && $sesionSeleccionada)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl max-w-3xl w-full mx-4 max-h-[90vh] overflow-y-auto">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">{{ $sesionSeleccionada->actividad->titulo }}</h2>
                            <p class="text-sm text-gray-600">Sesión {{ $sesionSeleccionada->numero_sesion }}: {{ $sesionSeleccionada->titulo }}</p>
                        </div>
                        <button wire:click="$set('modalDetallesSesion', false)" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 mb-1">Fecha</h3>
                                <p class="text-gray-900">{{ $sesionSeleccionada->fecha->format('d/m/Y') }}</p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 mb-1">Horario</h3>
                                <p class="text-gray-900">{{ $sesionSeleccionada->hora_inicio }} - {{ $sesionSeleccionada->hora_fin }}</p>
                            </div>
                            @if($sesionSeleccionada->ubicacion)
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500 mb-1">Ubicación</h3>
                                    <p class="text-gray-900">{{ $sesionSeleccionada->ubicacion }}</p>
                                </div>
                            @endif
                            @if($sesionSeleccionada->instructor)
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500 mb-1">Instructor</h3>
                                    <p class="text-gray-900">{{ $sesionSeleccionada->instructor }}</p>
                                </div>
                            @endif
                        </div>

                        @if($sesionSeleccionada->descripcion)
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 mb-1">Descripción</h3>
                                <p class="text-gray-900">{{ $sesionSeleccionada->descripcion }}</p>
                            </div>
                        @endif

                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-2">Personal Inscrito ({{ $sesionSeleccionada->actividad->inscripciones->whereIn('estado', ['pendiente', 'aprobada'])->count() }})</h3>
                            <div class="border rounded-lg overflow-hidden">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Enfermero</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Área</th>
                                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Asistencia</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($sesionSeleccionada->actividad->inscripciones->whereIn('estado', ['pendiente', 'aprobada']) as $inscripcion)
                                            @php
                                                $asistencia = $sesionSeleccionada->asistencias->firstWhere('inscripcion_id', $inscripcion->id);
                                            @endphp
                                            <tr>
                                                <td class="px-4 py-2 text-sm text-gray-900">{{ $inscripcion->enfermero->user->name }}</td>
                                                <td class="px-4 py-2 text-sm text-gray-600">{{ $inscripcion->enfermero->area->nombre }}</td>
                                                <td class="px-4 py-2 text-center">
                                                    @if($asistencia)
                                                        @if($asistencia->presente)
                                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Presente</span>
                                                        @else
                                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Ausente</span>
                                                        @endif
                                                    @else
                                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Sin registrar</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 flex gap-3">
                        <button wire:click="$set('modalDetallesSesion', false)"
                            class="flex-1 px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                            Cerrar
                        </button>
                        @if(auth()->user()->hasAnyRole(['coordinador', 'admin']))
                            <a href="{{ route('capacitacion.asistencia', ['actividadId' => $sesionSeleccionada->actividad_id, 'sesionId' => $sesionSeleccionada->id]) }}"
                                class="flex-1 px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-md hover:bg-green-700 text-center">
                                {{ $sesionSeleccionada->asistencia_registrada ? 'Editar' : 'Registrar' }} Asistencia
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
