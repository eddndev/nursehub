<div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700 p-6">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Información del Paciente</h2>
        <button type="button" class="text-sm text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300">
            Editar
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        {{-- Información Personal --}}
        <div class="space-y-4">
            <h3 class="text-sm font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Datos Personales</h3>

            <div>
                <p class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Nombre Completo</p>
                <p class="text-sm text-slate-900 dark:text-slate-100 mt-1">{{ $paciente->nombre_completo }}</p>
            </div>

            <div>
                <p class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Fecha de Nacimiento</p>
                <p class="text-sm text-slate-900 dark:text-slate-100 mt-1">{{ $paciente->fecha_nacimiento->format('d/m/Y') }} ({{ $paciente->edad }} años)</p>
            </div>

            <div>
                <p class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Sexo</p>
                <p class="text-sm text-slate-900 dark:text-slate-100 mt-1">
                    @if($paciente->sexo === 'M') Masculino
                    @elseif($paciente->sexo === 'F') Femenino
                    @else Otro
                    @endif
                </p>
            </div>

            @if($paciente->curp)
            <div>
                <p class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">CURP</p>
                <p class="text-sm text-slate-900 dark:text-slate-100 mt-1 font-mono">{{ $paciente->curp }}</p>
            </div>
            @endif
        </div>

        {{-- Contacto --}}
        <div class="space-y-4">
            <h3 class="text-sm font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Contacto</h3>

            @if($paciente->telefono)
            <div>
                <p class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Teléfono</p>
                <p class="text-sm text-slate-900 dark:text-slate-100 mt-1">{{ $paciente->telefono }}</p>
            </div>
            @endif

            @if($paciente->contacto_emergencia_nombre)
            <div>
                <p class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Contacto de Emergencia</p>
                <p class="text-sm text-slate-900 dark:text-slate-100 mt-1">{{ $paciente->contacto_emergencia_nombre }}</p>
                @if($paciente->contacto_emergencia_telefono)
                <p class="text-sm text-slate-600 dark:text-slate-400 mt-0.5">{{ $paciente->contacto_emergencia_telefono }}</p>
                @endif
            </div>
            @endif
        </div>

        {{-- Admisión e Identificación --}}
        <div class="space-y-4">
            <h3 class="text-sm font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Admisión</h3>

            <div>
                <p class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Código QR</p>
                <p class="text-sm text-slate-900 dark:text-slate-100 mt-1 font-mono">{{ $paciente->codigo_qr }}</p>
            </div>

            <div>
                <p class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Fecha de Admisión</p>
                <p class="text-sm text-slate-900 dark:text-slate-100 mt-1">{{ $paciente->fecha_admision->format('d/m/Y H:i') }}</p>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ $paciente->fecha_admision->diffForHumans() }}</p>
            </div>

            <div>
                <p class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Admitido Por</p>
                <p class="text-sm text-slate-900 dark:text-slate-100 mt-1">{{ $paciente->admitidoPor->name }}</p>
            </div>

            @if($paciente->camaActual)
            <div>
                <p class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Ubicación Actual</p>
                <p class="text-sm text-slate-900 dark:text-slate-100 mt-1">
                    {{ $paciente->camaActual->cuarto->piso->area->nombre }}
                </p>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                    Piso {{ $paciente->camaActual->cuarto->piso->nombre }} •
                    Cuarto {{ $paciente->camaActual->cuarto->numero }} •
                    Cama {{ $paciente->camaActual->numero }}
                </p>
            </div>
            @endif
        </div>
    </div>

    {{-- Alergias y Antecedentes --}}
    @if($paciente->alergias || $paciente->antecedentes_medicos)
    <div class="mt-6 pt-6 border-t border-slate-200 dark:border-slate-700 grid grid-cols-1 md:grid-cols-2 gap-6">
        @if($paciente->alergias)
        <div class="bg-red-50 dark:bg-red-900/20 border-2 border-red-300 dark:border-red-800 rounded-lg p-4">
            <div class="flex items-start gap-2">
                <svg class="h-5 w-5 text-red-600 dark:text-red-400 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <div class="flex-1">
                    <p class="text-sm font-semibold text-red-800 dark:text-red-200 mb-1">Alergias Conocidas</p>
                    <p class="text-sm text-red-700 dark:text-red-300">{{ $paciente->alergias }}</p>
                </div>
            </div>
        </div>
        @endif

        @if($paciente->antecedentes_medicos)
        <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-300 dark:border-amber-800 rounded-lg p-4">
            <div class="flex items-start gap-2">
                <svg class="h-5 w-5 text-amber-600 dark:text-amber-400 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <div class="flex-1">
                    <p class="text-sm font-semibold text-amber-800 dark:text-amber-200 mb-1">Antecedentes Médicos</p>
                    <p class="text-sm text-amber-700 dark:text-amber-300">{{ $paciente->antecedentes_medicos }}</p>
                </div>
            </div>
        </div>
        @endif
    </div>
    @endif
</div>
