<div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700 p-6">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Signos Vitales Recientes</h2>
        @livewire('enfermeria.registro-signos-vitales', ['pacienteId' => $paciente->id], key('registro-signos-'.$paciente->id))
    </div>

    @if($ultimoRegistro)
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-8 gap-4 mb-6">
            {{-- Presión Arterial --}}
            @if($ultimoRegistro->presion_arterial)
            <div class="bg-slate-50 dark:bg-slate-900/50 rounded-lg p-4 border border-slate-200 dark:border-slate-700">
                <div class="flex items-center gap-2 mb-2">
                    <svg class="h-5 w-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                    <p class="text-xs font-medium text-slate-600 dark:text-slate-400">P/A</p>
                </div>
                <p class="text-2xl font-bold text-slate-900 dark:text-slate-100">{{ $ultimoRegistro->presion_arterial }}</p>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">mmHg</p>
            </div>
            @endif

            {{-- Frecuencia Cardíaca --}}
            @if($ultimoRegistro->frecuencia_cardiaca)
            <div class="bg-slate-50 dark:bg-slate-900/50 rounded-lg p-4 border border-slate-200 dark:border-slate-700">
                <div class="flex items-center gap-2 mb-2">
                    <svg class="h-5 w-5 text-pink-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    <p class="text-xs font-medium text-slate-600 dark:text-slate-400">FC</p>
                </div>
                <p class="text-2xl font-bold text-slate-900 dark:text-slate-100">{{ $ultimoRegistro->frecuencia_cardiaca }}</p>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">lpm</p>
            </div>
            @endif

            {{-- Frecuencia Respiratoria --}}
            @if($ultimoRegistro->frecuencia_respiratoria)
            <div class="bg-slate-50 dark:bg-slate-900/50 rounded-lg p-4 border border-slate-200 dark:border-slate-700">
                <div class="flex items-center gap-2 mb-2">
                    <svg class="h-5 w-5 text-cyan-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2 1m2-1l-2-1m2 1v2.5M14 4l-2-1-2 1M4 7l2-1M4 7l2 1M4 7v2.5M12 21l-2-1m2 1l2-1m-2 1v-2.5M6 18l-2-1v-2.5M18 18l2-1v-2.5" />
                    </svg>
                    <p class="text-xs font-medium text-slate-600 dark:text-slate-400">FR</p>
                </div>
                <p class="text-2xl font-bold text-slate-900 dark:text-slate-100">{{ $ultimoRegistro->frecuencia_respiratoria }}</p>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">rpm</p>
            </div>
            @endif

            {{-- Temperatura --}}
            @if($ultimoRegistro->temperatura)
            <div class="bg-slate-50 dark:bg-slate-900/50 rounded-lg p-4 border border-slate-200 dark:border-slate-700">
                <div class="flex items-center gap-2 mb-2">
                    <svg class="h-5 w-5 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    <p class="text-xs font-medium text-slate-600 dark:text-slate-400">Temp</p>
                </div>
                <p class="text-2xl font-bold text-slate-900 dark:text-slate-100">{{ $ultimoRegistro->temperatura }}</p>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">°C</p>
            </div>
            @endif

            {{-- SpO2 --}}
            @if($ultimoRegistro->saturacion_oxigeno)
            <div class="bg-slate-50 dark:bg-slate-900/50 rounded-lg p-4 border border-slate-200 dark:border-slate-700">
                <div class="flex items-center gap-2 mb-2">
                    <svg class="h-5 w-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z" />
                    </svg>
                    <p class="text-xs font-medium text-slate-600 dark:text-slate-400">SpO2</p>
                </div>
                <p class="text-2xl font-bold text-slate-900 dark:text-slate-100">{{ $ultimoRegistro->saturacion_oxigeno }}</p>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">%</p>
            </div>
            @endif

            {{-- Glucosa --}}
            @if($ultimoRegistro->glucosa)
            <div class="bg-slate-50 dark:bg-slate-900/50 rounded-lg p-4 border border-slate-200 dark:border-slate-700">
                <div class="flex items-center gap-2 mb-2">
                    <svg class="h-5 w-5 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                    </svg>
                    <p class="text-xs font-medium text-slate-600 dark:text-slate-400">Glucosa</p>
                </div>
                <p class="text-2xl font-bold text-slate-900 dark:text-slate-100">{{ $ultimoRegistro->glucosa }}</p>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">mg/dL</p>
            </div>
            @endif

            {{-- TRIAGE --}}
            @if($ultimoRegistro->nivel_triage)
            <div class="bg-slate-50 dark:bg-slate-900/50 rounded-lg p-4 border border-slate-200 dark:border-slate-700">
                <div class="flex items-center gap-2 mb-2">
                    <svg class="h-5 w-5 text-{{ $ultimoRegistro->nivel_triage->getColor() }}-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                    </svg>
                    <p class="text-xs font-medium text-slate-600 dark:text-slate-400">TRIAGE</p>
                </div>
                <p class="text-sm font-semibold">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $ultimoRegistro->nivel_triage->getColor() }}-100 text-{{ $ultimoRegistro->nivel_triage->getColor() }}-800 dark:bg-{{ $ultimoRegistro->nivel_triage->getColor() }}-900/50 dark:text-{{ $ultimoRegistro->nivel_triage->getColor() }}-200">
                        {{ $ultimoRegistro->nivel_triage->getLabel() }}
                    </span>
                </p>
                @if($ultimoRegistro->triage_override)
                <p class="text-xs text-amber-600 dark:text-amber-400 mt-1">⚠️ Manual</p>
                @endif
            </div>
            @endif
        </div>

        {{-- Info de registro --}}
        <div class="flex items-center justify-between text-sm">
            <div class="text-slate-600 dark:text-slate-400">
                Registrado por <span class="font-medium text-slate-900 dark:text-slate-100">{{ $ultimoRegistro->registradoPor->name }}</span>
            </div>
            <div class="text-slate-500 dark:text-slate-400">
                {{ $ultimoRegistro->fecha_registro->format('d/m/Y H:i') }} • {{ $ultimoRegistro->fecha_registro->diffForHumans() }}
            </div>
        </div>

        @if($ultimoRegistro->observaciones)
        <div class="mt-4 p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
            <p class="text-xs font-medium text-blue-800 dark:text-blue-200 mb-1">Observaciones</p>
            <p class="text-sm text-blue-700 dark:text-blue-300">{{ $ultimoRegistro->observaciones }}</p>
        </div>
        @endif
    @else
        <div class="text-center py-8">
            <svg class="h-12 w-12 text-slate-400 dark:text-slate-600 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            </svg>
            <p class="text-sm text-slate-500 dark:text-slate-400 mb-3">No hay registros de signos vitales</p>
        </div>
    @endif
</div>
