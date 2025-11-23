<div class="mb-6">
    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center gap-4">
            <div class="h-16 w-16 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white text-2xl font-bold shadow-lg">
                {{ strtoupper(substr($paciente->nombre, 0, 1) . substr($paciente->apellido_paterno, 0, 1)) }}
            </div>
            <div>
                <h1 class="text-2xl font-bold text-slate-900 dark:text-slate-100">{{ $paciente->nombre_completo }}</h1>
                <div class="flex items-center gap-3 mt-1">
                    <span class="text-sm text-slate-600 dark:text-slate-400">{{ $paciente->edad }} años • {{ $paciente->sexo }}</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $paciente->estado->getColor() }}-100 text-{{ $paciente->estado->getColor() }}-800 dark:bg-{{ $paciente->estado->getColor() }}-900/50 dark:text-{{ $paciente->estado->getColor() }}-200">
                        {{ $paciente->estado->getLabel() }}
                    </span>
                </div>
            </div>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('enfermeria.pacientes') }}"
                class="inline-flex items-center px-4 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 text-sm font-medium rounded-md hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Volver a lista
            </a>
        </div>
    </div>
</div>
