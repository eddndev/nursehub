<div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700 p-6">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Historial del Paciente</h2>
        <span class="text-xs text-slate-500 dark:text-slate-400">Últimos 20 eventos</span>
    </div>

    <div class="flow-root">
        <ul role="list" class="-mb-8">
            @forelse($paciente->historial()->orderBy('fecha_evento', 'desc')->take(20)->get() as $evento)
            <li>
                <div class="relative pb-8">
                    @if(!$loop->last)
                    <span class="absolute left-4 top-4 -ml-px h-full w-0.5 bg-slate-200 dark:bg-slate-700" aria-hidden="true"></span>
                    @endif
                    <div class="relative flex space-x-3">
                        <div>
                            <span class="h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white dark:ring-slate-800
                                @if(str_contains(strtolower($evento->tipo_evento), 'signos') || str_contains(strtolower($evento->tipo_evento), 'vital'))
                                    bg-blue-100 dark:bg-blue-900/30
                                @elseif(str_contains(strtolower($evento->tipo_evento), 'admision') || str_contains(strtolower($evento->tipo_evento), 'alta'))
                                    bg-green-100 dark:bg-green-900/30
                                @elseif(str_contains(strtolower($evento->tipo_evento), 'traslado') || str_contains(strtolower($evento->tipo_evento), 'ubicacion'))
                                    bg-purple-100 dark:bg-purple-900/30
                                @elseif(str_contains(strtolower($evento->tipo_evento), 'medicamento') || str_contains(strtolower($evento->tipo_evento), 'tratamiento'))
                                    bg-orange-100 dark:bg-orange-900/30
                                @else
                                    bg-slate-100 dark:bg-slate-900/30
                                @endif
                            ">
                                @if(str_contains(strtolower($evento->tipo_evento), 'signos') || str_contains(strtolower($evento->tipo_evento), 'vital'))
                                    <svg class="h-5 w-5 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                @elseif(str_contains(strtolower($evento->tipo_evento), 'admision') || str_contains(strtolower($evento->tipo_evento), 'alta'))
                                    <svg class="h-5 w-5 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                @elseif(str_contains(strtolower($evento->tipo_evento), 'traslado') || str_contains(strtolower($evento->tipo_evento), 'ubicacion'))
                                    <svg class="h-5 w-5 text-purple-600 dark:text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                @elseif(str_contains(strtolower($evento->tipo_evento), 'medicamento') || str_contains(strtolower($evento->tipo_evento), 'tratamiento'))
                                    <svg class="h-5 w-5 text-orange-600 dark:text-orange-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                    </svg>
                                @else
                                    <svg class="h-5 w-5 text-slate-600 dark:text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                @endif
                            </span>
                        </div>
                        <div class="flex min-w-0 flex-1 justify-between space-x-4 pt-1.5">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-slate-900 dark:text-slate-100">
                                    {{ $evento->tipo_evento }}
                                </p>
                                <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">
                                    {{ $evento->descripcion }}
                                </p>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                                    Por {{ $evento->usuario->name }}
                                </p>
                            </div>
                            <div class="whitespace-nowrap text-right text-sm">
                                <time datetime="{{ $evento->fecha_evento }}" class="text-slate-500 dark:text-slate-400">
                                    {{ $evento->fecha_evento->format('d/m/Y H:i') }}
                                </time>
                                <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">
                                    {{ $evento->fecha_evento->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </li>
            @empty
            <li class="text-center py-8">
                <svg class="h-12 w-12 text-slate-400 dark:text-slate-600 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <p class="text-sm text-slate-500 dark:text-slate-400">No hay registros en el historial</p>
                <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">Los eventos del paciente aparecerán aquí</p>
            </li>
            @endforelse
        </ul>
    </div>
</div>
