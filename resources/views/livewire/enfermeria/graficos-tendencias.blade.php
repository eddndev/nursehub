<div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700 p-6">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Tendencias de Signos Vitales</h2>

        {{-- Selector de período --}}
        <div class="flex items-center gap-2">
            <span class="text-sm text-slate-600 dark:text-slate-400">Período:</span>
            <div class="inline-flex rounded-md shadow-sm" role="group">
                <button type="button" wire:click="cambiarPeriodo('24h')"
                    class="px-3 py-1.5 text-sm font-medium rounded-l-md transition-colors
                        {{ $periodo === '24h'
                            ? 'bg-blue-600 text-white'
                            : 'bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-300 dark:border-slate-600 hover:bg-slate-50 dark:hover:bg-slate-700' }}">
                    24h
                </button>
                <button type="button" wire:click="cambiarPeriodo('7d')"
                    class="px-3 py-1.5 text-sm font-medium border-l-0 transition-colors
                        {{ $periodo === '7d'
                            ? 'bg-blue-600 text-white'
                            : 'bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-300 dark:border-slate-600 hover:bg-slate-50 dark:hover:bg-slate-700' }}">
                    7 días
                </button>
                <button type="button" wire:click="cambiarPeriodo('30d')"
                    class="px-3 py-1.5 text-sm font-medium border-l-0 transition-colors
                        {{ $periodo === '30d'
                            ? 'bg-blue-600 text-white'
                            : 'bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-300 dark:border-slate-600 hover:bg-slate-50 dark:hover:bg-slate-700' }}">
                    30 días
                </button>
                <button type="button" wire:click="cambiarPeriodo('todo')"
                    class="px-3 py-1.5 text-sm font-medium rounded-r-md border-l-0 transition-colors
                        {{ $periodo === 'todo'
                            ? 'bg-blue-600 text-white'
                            : 'bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-300 dark:border-slate-600 hover:bg-slate-50 dark:hover:bg-slate-700' }}">
                    Todo
                </button>
            </div>
        </div>
    </div>

    @if(count($datosGraficos['labels']) > 0)
        {{-- Estadísticas resumidas --}}
        @if($datosGraficos['estadisticas'])
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-6">
            {{-- Presión Arterial --}}
            @if(!empty($datosGraficos['estadisticas']['presion_arterial']))
            <div class="bg-red-50 dark:bg-red-900/20 rounded-lg p-3 border border-red-200 dark:border-red-800">
                <p class="text-xs font-medium text-red-800 dark:text-red-200 mb-1">P/A Promedio</p>
                <p class="text-lg font-bold text-red-900 dark:text-red-100">
                    {{ $datosGraficos['estadisticas']['presion_arterial']['promedio_sistolica'] }}/{{ $datosGraficos['estadisticas']['presion_arterial']['promedio_diastolica'] }}
                </p>
                <p class="text-xs text-red-600 dark:text-red-400">mmHg</p>
            </div>
            @endif

            {{-- Frecuencia Cardíaca --}}
            @if(!empty($datosGraficos['estadisticas']['frecuencia_cardiaca']))
            <div class="bg-pink-50 dark:bg-pink-900/20 rounded-lg p-3 border border-pink-200 dark:border-pink-800">
                <p class="text-xs font-medium text-pink-800 dark:text-pink-200 mb-1">FC Promedio</p>
                <p class="text-lg font-bold text-pink-900 dark:text-pink-100">
                    {{ $datosGraficos['estadisticas']['frecuencia_cardiaca']['promedio'] }}
                </p>
                <p class="text-xs text-pink-600 dark:text-pink-400">lpm</p>
            </div>
            @endif

            {{-- Frecuencia Respiratoria --}}
            @if(!empty($datosGraficos['estadisticas']['frecuencia_respiratoria']))
            <div class="bg-cyan-50 dark:bg-cyan-900/20 rounded-lg p-3 border border-cyan-200 dark:border-cyan-800">
                <p class="text-xs font-medium text-cyan-800 dark:text-cyan-200 mb-1">FR Promedio</p>
                <p class="text-lg font-bold text-cyan-900 dark:text-cyan-100">
                    {{ $datosGraficos['estadisticas']['frecuencia_respiratoria']['promedio'] }}
                </p>
                <p class="text-xs text-cyan-600 dark:text-cyan-400">rpm</p>
            </div>
            @endif

            {{-- Temperatura --}}
            @if(!empty($datosGraficos['estadisticas']['temperatura']))
            <div class="bg-orange-50 dark:bg-orange-900/20 rounded-lg p-3 border border-orange-200 dark:border-orange-800">
                <p class="text-xs font-medium text-orange-800 dark:text-orange-200 mb-1">Temp Promedio</p>
                <p class="text-lg font-bold text-orange-900 dark:text-orange-100">
                    {{ $datosGraficos['estadisticas']['temperatura']['promedio'] }}
                </p>
                <p class="text-xs text-orange-600 dark:text-orange-400">°C</p>
            </div>
            @endif

            {{-- SpO2 --}}
            @if(!empty($datosGraficos['estadisticas']['saturacion_oxigeno']))
            <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-3 border border-blue-200 dark:border-blue-800">
                <p class="text-xs font-medium text-blue-800 dark:text-blue-200 mb-1">SpO2 Promedio</p>
                <p class="text-lg font-bold text-blue-900 dark:text-blue-100">
                    {{ $datosGraficos['estadisticas']['saturacion_oxigeno']['promedio'] }}
                </p>
                <p class="text-xs text-blue-600 dark:text-blue-400">%</p>
            </div>
            @endif

            {{-- Glucosa --}}
            @if(!empty($datosGraficos['estadisticas']['glucosa']))
            <div class="bg-purple-50 dark:bg-purple-900/20 rounded-lg p-3 border border-purple-200 dark:border-purple-800">
                <p class="text-xs font-medium text-purple-800 dark:text-purple-200 mb-1">Glucosa Promedio</p>
                <p class="text-lg font-bold text-purple-900 dark:text-purple-100">
                    {{ $datosGraficos['estadisticas']['glucosa']['promedio'] }}
                </p>
                <p class="text-xs text-purple-600 dark:text-purple-400">mg/dL</p>
            </div>
            @endif
        </div>
        @endif

        {{-- Gráficos --}}
        <div class="space-y-6">
            {{-- Presión Arterial --}}
            @if(array_filter($datosGraficos['presion_sistolica']))
            <div class="border border-slate-200 dark:border-slate-700 rounded-lg p-4">
                <h3 class="text-sm font-semibold text-slate-900 dark:text-slate-100 mb-3 flex items-center gap-2">
                    <svg class="h-4 w-4 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                    Presión Arterial
                </h3>
                <canvas id="chart-presion" wire:ignore></canvas>
            </div>
            @endif

            {{-- Frecuencia Cardíaca --}}
            @if(array_filter($datosGraficos['frecuencia_cardiaca']))
            <div class="border border-slate-200 dark:border-slate-700 rounded-lg p-4">
                <h3 class="text-sm font-semibold text-slate-900 dark:text-slate-100 mb-3 flex items-center gap-2">
                    <svg class="h-4 w-4 text-pink-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    Frecuencia Cardíaca
                </h3>
                <canvas id="chart-fc" wire:ignore></canvas>
            </div>
            @endif

            {{-- Temperatura --}}
            @if(array_filter($datosGraficos['temperatura']))
            <div class="border border-slate-200 dark:border-slate-700 rounded-lg p-4">
                <h3 class="text-sm font-semibold text-slate-900 dark:text-slate-100 mb-3 flex items-center gap-2">
                    <svg class="h-4 w-4 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    Temperatura
                </h3>
                <canvas id="chart-temperatura" wire:ignore></canvas>
            </div>
            @endif

            {{-- SpO2 --}}
            @if(array_filter($datosGraficos['saturacion_oxigeno']))
            <div class="border border-slate-200 dark:border-slate-700 rounded-lg p-4">
                <h3 class="text-sm font-semibold text-slate-900 dark:text-slate-100 mb-3 flex items-center gap-2">
                    <svg class="h-4 w-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z" />
                    </svg>
                    Saturación de Oxígeno
                </h3>
                <canvas id="chart-spo2" wire:ignore></canvas>
            </div>
            @endif

            {{-- Glucosa --}}
            @if(array_filter($datosGraficos['glucosa']))
            <div class="border border-slate-200 dark:border-slate-700 rounded-lg p-4">
                <h3 class="text-sm font-semibold text-slate-900 dark:text-slate-100 mb-3 flex items-center gap-2">
                    <svg class="h-4 w-4 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                    </svg>
                    Glucosa
                </h3>
                <canvas id="chart-glucosa" wire:ignore></canvas>
            </div>
            @endif

            {{-- TRIAGE Timeline --}}
            @if(array_filter($datosGraficos['triage_colors']))
            <div class="border border-slate-200 dark:border-slate-700 rounded-lg p-4">
                <h3 class="text-sm font-semibold text-slate-900 dark:text-slate-100 mb-3 flex items-center gap-2">
                    <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                    </svg>
                    Evolución del TRIAGE
                </h3>
                <canvas id="chart-triage" wire:ignore></canvas>
            </div>
            @endif
        </div>

        {{-- Información de registros --}}
        <div class="mt-4 text-sm text-slate-600 dark:text-slate-400 text-center">
            Mostrando {{ $datosGraficos['estadisticas']['total_registros'] ?? 0 }} registro(s) en el período seleccionado
        </div>
    @else
        <div class="text-center py-12">
            <svg class="h-16 w-16 text-slate-400 dark:text-slate-600 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            </svg>
            <p class="text-sm text-slate-500 dark:text-slate-400">
                No hay registros de signos vitales en el período seleccionado
            </p>
            <p class="text-xs text-slate-400 dark:text-slate-500 mt-2">
                Los gráficos aparecerán cuando haya datos disponibles
            </p>
        </div>
    @endif
</div>

@script
<script>
    let charts = {};

    function initCharts() {
        // Destruir gráficos existentes
        Object.values(charts).forEach(chart => chart?.destroy());
        charts = {};

        const isDark = document.documentElement.classList.contains('dark');
        const textColor = isDark ? '#e2e8f0' : '#334155';
        const gridColor = isDark ? '#334155' : '#e2e8f0';

        const datosGraficos = @json($datosGraficos);

        // Configuración común
        const commonOptions = {
            responsive: true,
            maintainAspectRatio: true,
            aspectRatio: 2.5,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            plugins: {
                legend: {
                    labels: {
                        color: textColor,
                        font: { size: 11 }
                    }
                },
                tooltip: {
                    backgroundColor: isDark ? '#1e293b' : '#ffffff',
                    titleColor: textColor,
                    bodyColor: textColor,
                    borderColor: gridColor,
                    borderWidth: 1,
                }
            },
            scales: {
                x: {
                    ticks: { color: textColor, font: { size: 10 } },
                    grid: { color: gridColor }
                },
                y: {
                    ticks: { color: textColor, font: { size: 10 } },
                    grid: { color: gridColor }
                }
            }
        };

        // Presión Arterial
        if (datosGraficos.presion_sistolica.some(v => v !== null)) {
            const ctx = document.getElementById('chart-presion');
            if (ctx) {
                charts.presion = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: datosGraficos.labels,
                        datasets: [
                            {
                                label: 'Sistólica',
                                data: datosGraficos.presion_sistolica,
                                borderColor: '#ef4444',
                                backgroundColor: 'rgba(239, 68, 68, 0.1)',
                                tension: 0.3,
                                fill: true,
                            },
                            {
                                label: 'Diastólica',
                                data: datosGraficos.presion_diastolica,
                                borderColor: '#dc2626',
                                backgroundColor: 'rgba(220, 38, 38, 0.1)',
                                tension: 0.3,
                                fill: true,
                            }
                        ]
                    },
                    options: {
                        ...commonOptions,
                        scales: {
                            ...commonOptions.scales,
                            y: {
                                ...commonOptions.scales.y,
                                min: 40,
                                max: 200,
                            }
                        }
                    }
                });
            }
        }

        // Frecuencia Cardíaca
        if (datosGraficos.frecuencia_cardiaca.some(v => v !== null)) {
            const ctx = document.getElementById('chart-fc');
            if (ctx) {
                charts.fc = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: datosGraficos.labels,
                        datasets: [{
                            label: 'Frecuencia Cardíaca (lpm)',
                            data: datosGraficos.frecuencia_cardiaca,
                            borderColor: '#ec4899',
                            backgroundColor: 'rgba(236, 72, 153, 0.1)',
                            tension: 0.3,
                            fill: true,
                        }]
                    },
                    options: commonOptions
                });
            }
        }

        // Temperatura
        if (datosGraficos.temperatura.some(v => v !== null)) {
            const ctx = document.getElementById('chart-temperatura');
            if (ctx) {
                charts.temperatura = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: datosGraficos.labels,
                        datasets: [{
                            label: 'Temperatura (°C)',
                            data: datosGraficos.temperatura,
                            borderColor: '#f97316',
                            backgroundColor: 'rgba(249, 115, 22, 0.1)',
                            tension: 0.3,
                            fill: true,
                        }]
                    },
                    options: {
                        ...commonOptions,
                        scales: {
                            ...commonOptions.scales,
                            y: {
                                ...commonOptions.scales.y,
                                min: 35,
                                max: 42,
                            }
                        }
                    }
                });
            }
        }

        // SpO2
        if (datosGraficos.saturacion_oxigeno.some(v => v !== null)) {
            const ctx = document.getElementById('chart-spo2');
            if (ctx) {
                charts.spo2 = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: datosGraficos.labels,
                        datasets: [{
                            label: 'SpO2 (%)',
                            data: datosGraficos.saturacion_oxigeno,
                            borderColor: '#3b82f6',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            tension: 0.3,
                            fill: true,
                        }]
                    },
                    options: {
                        ...commonOptions,
                        scales: {
                            ...commonOptions.scales,
                            y: {
                                ...commonOptions.scales.y,
                                min: 85,
                                max: 100,
                            }
                        }
                    }
                });
            }
        }

        // Glucosa
        if (datosGraficos.glucosa.some(v => v !== null)) {
            const ctx = document.getElementById('chart-glucosa');
            if (ctx) {
                charts.glucosa = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: datosGraficos.labels,
                        datasets: [{
                            label: 'Glucosa (mg/dL)',
                            data: datosGraficos.glucosa,
                            borderColor: '#a855f7',
                            backgroundColor: 'rgba(168, 85, 247, 0.1)',
                            tension: 0.3,
                            fill: true,
                        }]
                    },
                    options: commonOptions
                });
            }
        }

        // TRIAGE
        if (datosGraficos.triage_colors.some(v => v !== null)) {
            const ctx = document.getElementById('chart-triage');
            if (ctx) {
                // Convertir niveles TRIAGE a números para graficar
                const triageValues = datosGraficos.triage_labels.map(label => {
                    switch(label) {
                        case 'Rojo - Resucitación': return 5;
                        case 'Naranja - Emergencia': return 4;
                        case 'Amarillo - Urgente': return 3;
                        case 'Verde - Menos Urgente': return 2;
                        case 'Azul - No Urgente': return 1;
                        default: return 0;
                    }
                });

                charts.triage = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: datosGraficos.labels,
                        datasets: [{
                            label: 'Nivel TRIAGE',
                            data: triageValues,
                            backgroundColor: datosGraficos.triage_colors,
                            borderColor: datosGraficos.triage_colors,
                            borderWidth: 1,
                        }]
                    },
                    options: {
                        ...commonOptions,
                        scales: {
                            ...commonOptions.scales,
                            y: {
                                ...commonOptions.scales.y,
                                min: 0,
                                max: 6,
                                ticks: {
                                    ...commonOptions.scales.y.ticks,
                                    stepSize: 1,
                                    callback: function(value) {
                                        const labels = ['', 'Azul', 'Verde', 'Amarillo', 'Naranja', 'Rojo'];
                                        return labels[value] || '';
                                    }
                                }
                            }
                        },
                        plugins: {
                            ...commonOptions.plugins,
                            tooltip: {
                                ...commonOptions.plugins.tooltip,
                                callbacks: {
                                    label: function(context) {
                                        return datosGraficos.triage_labels[context.dataIndex];
                                    }
                                }
                            }
                        }
                    }
                });
            }
        }
    }

    // Inicializar gráficos cuando el componente se monta
    $wire.on('$refresh', () => {
        setTimeout(initCharts, 100);
    });

    // Inicializar al cargar
    setTimeout(initCharts, 100);
</script>
@endscript
