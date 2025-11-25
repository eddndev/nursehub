<div class="p-6">
    {{-- Header --}}
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Reportes y Análisis de Capacitación</h1>
        <p class="text-gray-600 mt-1">Métricas, estadísticas y análisis del programa de capacitación</p>
    </div>

    {{-- Filtros de Fechas y Acciones --}}
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Fecha Inicio</label>
                <input type="date" wire:model.live="fechaInicio" class="w-full px-3 py-2 border border-gray-300 rounded-md">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Fecha Fin</label>
                <input type="date" wire:model.live="fechaFin" class="w-full px-3 py-2 border border-gray-300 rounded-md">
            </div>
            <div class="md:col-span-4 flex items-end gap-2">
                <button wire:click="limpiarFiltros" wire:loading.attr="disabled" class="flex-1 px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 disabled:opacity-50">
                    <span wire:loading.remove wire:target="limpiarFiltros">Limpiar</span>
                    <span wire:loading wire:target="limpiarFiltros">Limpiando...</span>
                </button>
                <button wire:click="actualizarDatos" wire:loading.attr="disabled" class="flex-1 px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 disabled:opacity-50">
                    <span wire:loading.remove wire:target="actualizarDatos">Actualizar</span>
                    <span wire:loading wire:target="actualizarDatos">
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Actualizando...
                    </span>
                </button>
                <button wire:click="exportarExcel" wire:loading.attr="disabled" class="flex-1 px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-md hover:bg-green-700 disabled:opacity-50">
                    <span wire:loading.remove wire:target="exportarExcel">Exportar Excel</span>
                    <span wire:loading wire:target="exportarExcel">
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Generando...
                    </span>
                </button>
                <button wire:click="exportarPDF" wire:loading.attr="disabled" class="flex-1 px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700 disabled:opacity-50">
                    <span wire:loading.remove wire:target="exportarPDF">Exportar PDF</span>
                    <span wire:loading wire:target="exportarPDF">
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Generando...
                    </span>
                </button>
            </div>
        </div>
    </div>

    {{-- Loading Overlay Global --}}
    <div wire:loading.flex wire:target="cambiarTipoReporte, fechaInicio, fechaFin" class="fixed inset-0 bg-gray-900/50 z-50 items-center justify-center">
        <div class="bg-white rounded-lg p-6 shadow-xl flex items-center gap-4">
            <svg class="animate-spin h-8 w-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span class="text-gray-700 font-medium">Cargando datos...</span>
        </div>
    </div>

    {{-- Navegación de Reportes --}}
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="border-b border-gray-200">
            <nav class="flex -mb-px">
                <button wire:click="cambiarTipoReporte('general')"
                    class="px-6 py-3 text-sm font-medium border-b-2 transition-colors {{ $tipoReporte === 'general' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Vista General
                </button>
                <button wire:click="cambiarTipoReporte('por-area')"
                    class="px-6 py-3 text-sm font-medium border-b-2 transition-colors {{ $tipoReporte === 'por-area' ? 'border-green-500 text-green-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Por Área
                </button>
                <button wire:click="cambiarTipoReporte('top-enfermeros')"
                    class="px-6 py-3 text-sm font-medium border-b-2 transition-colors {{ $tipoReporte === 'top-enfermeros' ? 'border-purple-500 text-purple-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Top Enfermeros
                </button>
                <button wire:click="cambiarTipoReporte('actividades-populares')"
                    class="px-6 py-3 text-sm font-medium border-b-2 transition-colors {{ $tipoReporte === 'actividades-populares' ? 'border-orange-500 text-orange-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Actividades Populares
                </button>
            </nav>
        </div>
    </div>

    {{-- Vista General --}}
    @if($tipoReporte === 'general')
        {{-- Métricas Principales --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
                <div class="flex items-center justify-between mb-4">
                    <div class="text-sm font-medium opacity-90">Total Actividades</div>
                    <svg class="w-8 h-8 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
                <div class="text-4xl font-bold mb-2">{{ $this->estadisticasGenerales['totalActividades'] }}</div>
                <div class="text-xs opacity-75">
                    Publicadas: {{ $this->estadisticasGenerales['actividadesPublicadas'] }} |
                    Finalizadas: {{ $this->estadisticasGenerales['actividadesFinalizadas'] }}
                </div>
            </div>

            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
                <div class="flex items-center justify-between mb-4">
                    <div class="text-sm font-medium opacity-90">Total Inscripciones</div>
                    <svg class="w-8 h-8 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div class="text-4xl font-bold mb-2">{{ $this->estadisticasGenerales['totalInscripciones'] }}</div>
                <div class="text-xs opacity-75">
                    Aprobadas: {{ $this->estadisticasGenerales['inscripcionesAprobadas'] }} |
                    Pendientes: {{ $this->estadisticasGenerales['inscripcionesPendientes'] }}
                </div>
            </div>

            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
                <div class="flex items-center justify-between mb-4">
                    <div class="text-sm font-medium opacity-90">Certificaciones</div>
                    <svg class="w-8 h-8 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                    </svg>
                </div>
                <div class="text-4xl font-bold mb-2">{{ $this->estadisticasGenerales['certificacionesGeneradas'] }}</div>
                <div class="text-xs opacity-75">
                    Vigentes: {{ $this->estadisticasGenerales['certificacionesVigentes'] }}
                </div>
            </div>

            <div class="bg-gradient-to-br from-teal-500 to-teal-600 rounded-lg shadow-lg p-6 text-white">
                <div class="flex items-center justify-between mb-4">
                    <div class="text-sm font-medium opacity-90">Horas Totales</div>
                    <svg class="w-8 h-8 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="text-4xl font-bold mb-2">{{ number_format($this->estadisticasGenerales['horasTotalesCertificadas'], 0) }}</div>
                <div class="text-xs opacity-75">
                    Horas certificadas
                </div>
            </div>
        </div>

        {{-- Indicadores Clave --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow p-4">
                <div class="text-xs font-medium text-gray-500 uppercase mb-2">Enfermeros Capacitados</div>
                <div class="text-2xl font-bold text-gray-900">{{ $this->estadisticasGenerales['enfermerosCapacitados'] }}</div>
                <div class="text-sm text-gray-600">de {{ $this->estadisticasGenerales['totalEnfermeros'] }} totales</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <div class="text-xs font-medium text-gray-500 uppercase mb-2">% Participación</div>
                <div class="text-2xl font-bold text-blue-600">{{ number_format($this->estadisticasGenerales['porcentajeParticipacion'], 1) }}%</div>
                <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $this->estadisticasGenerales['porcentajeParticipacion'] }}%"></div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <div class="text-xs font-medium text-gray-500 uppercase mb-2">Tasa de Aprobación</div>
                <div class="text-2xl font-bold text-green-600">{{ number_format($this->estadisticasGenerales['tasaAprobacion'], 1) }}%</div>
                <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                    <div class="bg-green-600 h-2 rounded-full" style="width: {{ $this->estadisticasGenerales['tasaAprobacion'] }}%"></div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <div class="text-xs font-medium text-gray-500 uppercase mb-2">Promedio Asistencia</div>
                <div class="text-2xl font-bold text-purple-600">{{ number_format($this->estadisticasGenerales['promedioAsistencia'] ?? 0, 1) }}%</div>
                <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                    <div class="bg-purple-600 h-2 rounded-full" style="width: {{ $this->estadisticasGenerales['promedioAsistencia'] ?? 0 }}%"></div>
                </div>
            </div>
        </div>

        {{-- Gráficos Visuales --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            {{-- Gráfico de Pastel - Distribución por Estado de Inscripciones --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Distribución de Inscripciones</h3>
                <div class="h-64">
                    <canvas id="chartDistribucionInscripciones"></canvas>
                </div>
            </div>

            {{-- Gráfico de Barras - Actividades por Tipo --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Actividades por Tipo</h3>
                <div class="h-64">
                    <canvas id="chartActividadesPorTipo"></canvas>
                </div>
            </div>
        </div>

        {{-- Reporte por Tipo de Actividad --}}
        <div class="bg-white rounded-lg shadow mb-6">
            <div class="p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Reporte por Tipo de Actividad</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipo</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Actividades</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Inscripciones</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Aprobadas</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Certificaciones</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Horas Totales</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Horas Certificadas</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($this->reportePorTipoActividad as $reporte)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="font-medium text-gray-900">{{ $reporte['tipo'] }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="text-sm text-gray-900">{{ $reporte['total_actividades'] }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="text-sm text-gray-900">{{ $reporte['total_inscripciones'] }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="text-sm font-medium text-green-600">{{ $reporte['inscripciones_aprobadas'] }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="text-sm font-medium text-purple-600">{{ $reporte['certificaciones'] }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="text-sm text-gray-900">{{ $reporte['horas_totales'] }}h</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="text-sm font-medium text-teal-600">{{ $reporte['horas_certificadas'] }}h</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    {{-- Vista Por Área --}}
    @if($tipoReporte === 'por-area')
        <div class="bg-white rounded-lg shadow">
            <div class="p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Reporte de Capacitación por Área</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Área</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Actividades</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Total Enfermeros</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Capacitados</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Inscripciones</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Aprobadas</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Certificaciones</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Horas</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($this->reportePorArea as $reporte)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="font-medium text-gray-900">{{ $reporte['area'] }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="text-sm text-gray-900">{{ $reporte['total_actividades'] }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="text-sm text-gray-900">{{ $reporte['total_enfermeros'] }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="text-sm font-medium text-blue-600">{{ $reporte['enfermeros_capacitados'] }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="text-sm text-gray-900">{{ $reporte['total_inscripciones'] }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="text-sm font-medium text-green-600">{{ $reporte['inscripciones_aprobadas'] }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="text-sm font-medium text-purple-600">{{ $reporte['certificaciones'] }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="text-sm font-medium text-teal-600">{{ $reporte['horas_certificadas'] }}h</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                                        No hay datos disponibles para el período seleccionado
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    {{-- Vista Top Enfermeros --}}
    @if($tipoReporte === 'top-enfermeros')
        <div class="bg-white rounded-lg shadow">
            <div class="p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Top 10 Enfermeros Más Capacitados</h2>
                <div class="space-y-3">
                    @forelse($this->topEnfermerosCapacitados as $index => $enfermero)
                        <div class="flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-white rounded-lg border border-gray-200 hover:shadow-md transition-shadow">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-bold text-lg">
                                    {{ $index + 1 }}
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $enfermero->user->name }}</p>
                                    <p class="text-sm text-gray-600">{{ $enfermero->area->nombre }}</p>
                                </div>
                            </div>
                            <div class="flex gap-6 text-center">
                                <div>
                                    <div class="text-2xl font-bold text-blue-600">{{ $enfermero->inscripciones_count }}</div>
                                    <div class="text-xs text-gray-500 uppercase">Inscripciones</div>
                                </div>
                                <div>
                                    <div class="text-2xl font-bold text-purple-600">{{ $enfermero->certificaciones_count }}</div>
                                    <div class="text-xs text-gray-500 uppercase">Certificaciones</div>
                                </div>
                                <div>
                                    <div class="text-2xl font-bold text-teal-600">{{ number_format($enfermero->horas_totales ?? 0, 0) }}</div>
                                    <div class="text-xs text-gray-500 uppercase">Horas</div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12 text-gray-500">
                            No hay datos disponibles para el período seleccionado
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    @endif

    {{-- Vista Actividades Populares --}}
    @if($tipoReporte === 'actividades-populares')
        <div class="bg-white rounded-lg shadow">
            <div class="p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Top 10 Actividades Más Populares</h2>
                <div class="space-y-3">
                    @forelse($this->actividadesMasPopulares as $index => $actividad)
                        <div class="flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-white rounded-lg border border-gray-200 hover:shadow-md transition-shadow">
                            <div class="flex items-center gap-4 flex-1">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-orange-500 to-pink-600 flex items-center justify-center text-white font-bold text-lg">
                                    {{ $index + 1 }}
                                </div>
                                <div class="flex-1">
                                    <p class="font-semibold text-gray-900">{{ $actividad->titulo }}</p>
                                    <div class="flex items-center gap-3 mt-1">
                                        <span class="px-2 py-0.5 text-xs font-semibold rounded-full {{ $actividad->tipo->getColor() }}">
                                            {{ $actividad->tipo->getLabel() }}
                                        </span>
                                        <span class="text-sm text-gray-600">{{ $actividad->area->nombre ?? 'Todas las áreas' }}</span>
                                        <span class="text-sm text-gray-600">{{ $actividad->duracion_horas }}h</span>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center">
                                <div class="text-3xl font-bold text-orange-600">{{ $actividad->inscripciones_count }}</div>
                                <div class="text-xs text-gray-500 uppercase">Inscritos</div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12 text-gray-500">
                            No hay datos disponibles para el período seleccionado
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    @endif

    {{-- Scripts para notificaciones y gráficos --}}
    @script
    <script>
        // Variables para mantener referencias a los gráficos
        let chartDistribucion = null;
        let chartActividades = null;

        // Función para inicializar gráficos
        function initCharts() {
            // Solo inicializar si estamos en la vista general
            const canvasDistribucion = document.getElementById('chartDistribucionInscripciones');
            const canvasActividades = document.getElementById('chartActividadesPorTipo');

            if (!canvasDistribucion || !canvasActividades || !window.Chart) return;

            // Destruir gráficos existentes si hay
            if (chartDistribucion) {
                chartDistribucion.destroy();
                chartDistribucion = null;
            }
            if (chartActividades) {
                chartActividades.destroy();
                chartActividades = null;
            }

            // Datos desde el componente Livewire
            const estadisticas = @json($this->estadisticasGenerales);
            const reporteTipo = @json($this->reportePorTipoActividad);

            // Gráfico de Pastel - Distribución de Inscripciones
            chartDistribucion = new Chart(canvasDistribucion, {
                type: 'doughnut',
                data: {
                    labels: ['Aprobadas', 'Pendientes', 'Reprobadas'],
                    datasets: [{
                        data: [
                            estadisticas.inscripcionesAprobadas || 0,
                            estadisticas.inscripcionesPendientes || 0,
                            estadisticas.inscripcionesReprobadas || 0
                        ],
                        backgroundColor: [
                            'rgba(34, 197, 94, 0.8)',
                            'rgba(234, 179, 8, 0.8)',
                            'rgba(239, 68, 68, 0.8)'
                        ],
                        borderColor: [
                            'rgba(34, 197, 94, 1)',
                            'rgba(234, 179, 8, 1)',
                            'rgba(239, 68, 68, 1)'
                        ],
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                usePointStyle: true
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const value = context.raw;
                                    const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                    return `${context.label}: ${value} (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });

            // Gráfico de Barras - Actividades por Tipo
            const tiposLabels = reporteTipo.map(r => r.tipo);
            const actividadesData = reporteTipo.map(r => r.total_actividades);
            const inscripcionesData = reporteTipo.map(r => r.total_inscripciones);

            chartActividades = new Chart(canvasActividades, {
                type: 'bar',
                data: {
                    labels: tiposLabels,
                    datasets: [
                        {
                            label: 'Actividades',
                            data: actividadesData,
                            backgroundColor: 'rgba(59, 130, 246, 0.8)',
                            borderColor: 'rgba(59, 130, 246, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Inscripciones',
                            data: inscripcionesData,
                            backgroundColor: 'rgba(168, 85, 247, 0.8)',
                            borderColor: 'rgba(168, 85, 247, 1)',
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                usePointStyle: true
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });
        }

        // Inicializar gráficos cuando el DOM esté listo
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(initCharts, 100);
        });

        // Reinicializar gráficos cuando Livewire actualice el componente
        Livewire.hook('morph.updated', ({ el, component }) => {
            setTimeout(initCharts, 100);
        });

        // Eventos de notificaciones
        $wire.on('info', (event) => {
            if (window.Swal) {
                Swal.fire({
                    icon: 'info',
                    title: 'Información',
                    text: event.mensaje,
                    timer: 3000,
                    showConfirmButton: false
                });
            } else {
                alert(event.mensaje);
            }
        });

        $wire.on('success', (event) => {
            if (window.Swal) {
                Swal.fire({
                    icon: 'success',
                    title: 'Éxito',
                    text: event.mensaje,
                    timer: 3000,
                    showConfirmButton: false
                });
            } else {
                alert(event.mensaje);
            }
        });

        $wire.on('error', (event) => {
            if (window.Swal) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: event.mensaje
                });
            } else {
                alert('Error: ' + event.mensaje);
            }
        });
    </script>
    @endscript
</div>
