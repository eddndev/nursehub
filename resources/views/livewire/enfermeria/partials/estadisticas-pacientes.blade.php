<div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-7 gap-4 mb-6">
    {{-- Total de Pacientes --}}
    <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700 p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Total</p>
                <p class="text-2xl font-bold text-slate-900 dark:text-slate-100">{{ $stats['total'] }}</p>
            </div>
            <div class="h-12 w-12 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center">
                <svg class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
        </div>
    </div>

    {{-- Pacientes Activos --}}
    <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700 p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Activos</p>
                <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $stats['activos'] }}</p>
            </div>
            <div class="h-12 w-12 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center">
                <svg class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>
    </div>

    {{-- TRIAGE por nivel --}}
    @foreach(['rojo' => 'red', 'naranja' => 'orange', 'amarillo' => 'yellow', 'verde' => 'green', 'azul' => 'blue'] as $nivel => $color)
        <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-slate-600 dark:text-slate-400 uppercase">{{ ucfirst($nivel) }}</p>
                    <p class="text-2xl font-bold text-{{ $color }}-600 dark:text-{{ $color }}-400">{{ $stats['por_triage'][$nivel] }}</p>
                </div>
                <div class="h-10 w-10 bg-{{ $color }}-100 dark:bg-{{ $color }}-900/30 rounded-full"></div>
            </div>
        </div>
    @endforeach
</div>
