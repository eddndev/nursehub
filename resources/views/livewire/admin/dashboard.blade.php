<div>
    {{-- Page Header --}}
    <header class="mb-8">
        <div class="mx-auto max-w-7xl">
            <h1 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white">Dashboard</h1>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                Resumen general del sistema NurseHub
            </p>
        </div>
    </header>

    {{-- Main Stats Grid --}}
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 mb-8">
        {{-- Total de Áreas --}}
        <div class="overflow-hidden rounded-lg bg-white shadow dark:bg-gray-800">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="shrink-0">
                        <div class="rounded-md bg-blue-500 p-3">
                            <svg class="size-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="truncate text-sm font-medium text-gray-500 dark:text-gray-400">Áreas del Hospital</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $stats['total_areas'] }}</div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-5 py-3 dark:bg-gray-900/50">
                <div class="text-sm">
                    <a href="{{ route('admin.areas') }}" class="font-medium text-blue-600 hover:text-blue-500 dark:text-blue-400">Ver áreas →</a>
                </div>
            </div>
        </div>

        {{-- Total de Pisos --}}
        <div class="overflow-hidden rounded-lg bg-white shadow dark:bg-gray-800">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="shrink-0">
                        <div class="rounded-md bg-cyan-500 p-3">
                            <svg class="size-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.429 9.75 2.25 12l4.179 2.25m0-4.5 5.571 3 5.571-3m-11.142 0L2.25 7.5 12 2.25l9.75 5.25-4.179 2.25m0 0L21.75 12l-4.179 2.25m0 0 4.179 2.25L12 21.75 2.25 16.5l4.179-2.25m11.142 0-5.571 3-5.571-3" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="truncate text-sm font-medium text-gray-500 dark:text-gray-400">Pisos Totales</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $stats['total_pisos'] }}</div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-5 py-3 dark:bg-gray-900/50">
                <div class="text-sm">
                    <a href="{{ route('admin.pisos') }}" class="font-medium text-cyan-600 hover:text-cyan-500 dark:text-cyan-400">Ver pisos →</a>
                </div>
            </div>
        </div>

        {{-- Total de Cuartos --}}
        <div class="overflow-hidden rounded-lg bg-white shadow dark:bg-gray-800">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="shrink-0">
                        <div class="rounded-md bg-indigo-500 p-3">
                            <svg class="size-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 7.125C2.25 6.504 2.754 6 3.375 6h6c.621 0 1.125.504 1.125 1.125v3.75c0 .621-.504 1.125-1.125 1.125h-6a1.125 1.125 0 0 1-1.125-1.125v-3.75ZM14.25 8.625c0-.621.504-1.125 1.125-1.125h5.25c.621 0 1.125.504 1.125 1.125v8.25c0 .621-.504 1.125-1.125 1.125h-5.25a1.125 1.125 0 0 1-1.125-1.125v-8.25ZM3.75 16.125c0-.621.504-1.125 1.125-1.125h5.25c.621 0 1.125.504 1.125 1.125v2.25c0 .621-.504 1.125-1.125 1.125h-5.25a1.125 1.125 0 0 1-1.125-1.125v-2.25Z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="truncate text-sm font-medium text-gray-500 dark:text-gray-400">Cuartos Totales</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $stats['total_cuartos'] }}</div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-5 py-3 dark:bg-gray-900/50">
                <div class="text-sm">
                    <a href="{{ route('admin.cuartos') }}" class="font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400">Ver cuartos →</a>
                </div>
            </div>
        </div>

        {{-- Total de Camas --}}
        <div class="overflow-hidden rounded-lg bg-white shadow dark:bg-gray-800">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="shrink-0">
                        <div class="rounded-md bg-purple-500 p-3">
                            <svg class="size-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="truncate text-sm font-medium text-gray-500 dark:text-gray-400">Camas Totales</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $stats['total_camas'] }}</div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-5 py-3 dark:bg-gray-900/50">
                <div class="text-sm">
                    <a href="{{ route('admin.camas') }}" class="font-medium text-purple-600 hover:text-purple-500 dark:text-purple-400">Ver camas →</a>
                </div>
            </div>
        </div>
    </div>

    {{-- Bed Status and Staff Stats --}}
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2 mb-8">
        {{-- Estado de Camas --}}
        <div class="overflow-hidden rounded-lg bg-white shadow dark:bg-gray-800">
            <div class="border-b border-gray-200 px-5 py-4 dark:border-white/10">
                <h3 class="text-base font-semibold text-gray-900 dark:text-white">Estado de Camas</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Distribución actual de {{ $stats['total_camas'] }} camas</p>
            </div>
            <div class="p-5">
                {{-- Porcentaje de Ocupación --}}
                <div class="mb-6">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Ocupación General</span>
                        <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $stats['porcentaje_ocupacion'] }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                        <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $stats['porcentaje_ocupacion'] }}%"></div>
                    </div>
                </div>

                {{-- Estado de Camas --}}
                <dl class="space-y-4">
                    <div class="flex items-center justify-between">
                        <dt class="flex items-center text-sm font-medium text-gray-600 dark:text-gray-400">
                            <span class="inline-block size-3 rounded-full bg-green-500 mr-2"></span>
                            Libres
                        </dt>
                        <dd class="text-sm font-semibold text-gray-900 dark:text-white">{{ $stats['camas_libres'] }}</dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt class="flex items-center text-sm font-medium text-gray-600 dark:text-gray-400">
                            <span class="inline-block size-3 rounded-full bg-red-500 mr-2"></span>
                            Ocupadas
                        </dt>
                        <dd class="text-sm font-semibold text-gray-900 dark:text-white">{{ $stats['camas_ocupadas'] }}</dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt class="flex items-center text-sm font-medium text-gray-600 dark:text-gray-400">
                            <span class="inline-block size-3 rounded-full bg-yellow-500 mr-2"></span>
                            En Limpieza
                        </dt>
                        <dd class="text-sm font-semibold text-gray-900 dark:text-white">{{ $stats['camas_limpieza'] }}</dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt class="flex items-center text-sm font-medium text-gray-600 dark:text-gray-400">
                            <span class="inline-block size-3 rounded-full bg-gray-500 mr-2"></span>
                            En Mantenimiento
                        </dt>
                        <dd class="text-sm font-semibold text-gray-900 dark:text-white">{{ $stats['camas_mantenimiento'] }}</dd>
                    </div>
                </dl>
            </div>
            <div class="bg-gray-50 px-5 py-3 dark:bg-gray-900/50">
                <div class="text-sm">
                    <a href="{{ route('hospital.map') }}" class="font-medium text-blue-600 hover:text-blue-500 dark:text-blue-400">Ver mapa completo →</a>
                </div>
            </div>
        </div>

        {{-- Personal de Enfermería --}}
        <div class="overflow-hidden rounded-lg bg-white shadow dark:bg-gray-800">
            <div class="border-b border-gray-200 px-5 py-4 dark:border-white/10">
                <h3 class="text-base font-semibold text-gray-900 dark:text-white">Personal de Enfermería</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Distribución del personal activo</p>
            </div>
            <div class="p-5">
                <dl class="space-y-6">
                    <div>
                        <dt class="text-sm font-medium text-gray-600 dark:text-gray-400">Total de Usuarios Activos</dt>
                        <dd class="mt-1 text-3xl font-semibold text-gray-900 dark:text-white">{{ $stats['total_usuarios'] }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-600 dark:text-gray-400">Total de Enfermeros</dt>
                        <dd class="mt-1 text-3xl font-semibold text-gray-900 dark:text-white">{{ $stats['total_enfermeros'] }}</dd>
                    </div>
                    <div class="grid grid-cols-2 gap-4 pt-4 border-t border-gray-200 dark:border-white/10">
                        <div>
                            <dt class="text-sm font-medium text-gray-600 dark:text-gray-400">Enfermeros Fijos</dt>
                            <dd class="mt-1 text-2xl font-semibold text-blue-600 dark:text-blue-400">{{ $stats['enfermeros_fijos'] }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-600 dark:text-gray-400">Enfermeros Rotativos</dt>
                            <dd class="mt-1 text-2xl font-semibold text-cyan-600 dark:text-cyan-400">{{ $stats['enfermeros_rotativos'] }}</dd>
                        </div>
                    </div>
                </dl>
            </div>
            <div class="bg-gray-50 px-5 py-3 dark:bg-gray-900/50">
                <div class="text-sm">
                    <a href="#" class="font-medium text-blue-600 hover:text-blue-500 dark:text-blue-400">Ver personal →</a>
                </div>
            </div>
        </div>
    </div>

    {{-- Top Áreas --}}
    <div class="overflow-hidden rounded-lg bg-white shadow dark:bg-gray-800">
        <div class="border-b border-gray-200 px-5 py-4 dark:border-white/10">
            <h3 class="text-base font-semibold text-gray-900 dark:text-white">Áreas por Capacidad</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Top 5 áreas con mayor cantidad de pisos</p>
        </div>
        <div class="overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-white/10">
                <thead class="bg-gray-50 dark:bg-gray-900/50">
                    <tr>
                        <th scope="col" class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">Área</th>
                        <th scope="col" class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">Código</th>
                        <th scope="col" class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">Pisos</th>
                        <th scope="col" class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">Opera 24/7</th>
                        <th scope="col" class="px-5 py-3 text-right text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">Ratio</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white dark:divide-white/10 dark:bg-gray-800">
                    @foreach($stats['top_areas'] as $area)
                    <tr>
                        <td class="whitespace-nowrap px-5 py-4 text-sm font-medium text-gray-900 dark:text-white">{{ $area->nombre }}</td>
                        <td class="whitespace-nowrap px-5 py-4 text-sm text-gray-500 dark:text-gray-400">
                            <span class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-700/10 dark:bg-blue-400/10 dark:text-blue-400 dark:ring-blue-400/30">
                                {{ $area->codigo }}
                            </span>
                        </td>
                        <td class="whitespace-nowrap px-5 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $area->pisos_count }}</td>
                        <td class="whitespace-nowrap px-5 py-4 text-sm text-gray-500 dark:text-gray-400">
                            @if($area->opera_24_7)
                                <span class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20 dark:bg-green-500/10 dark:text-green-400 dark:ring-green-500/20">Sí</span>
                            @else
                                <span class="inline-flex items-center rounded-md bg-gray-50 px-2 py-1 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10 dark:bg-gray-400/10 dark:text-gray-400 dark:ring-gray-400/20">No</span>
                            @endif
                        </td>
                        <td class="whitespace-nowrap px-5 py-4 text-right text-sm text-gray-500 dark:text-gray-400">1:{{ $area->ratio_enfermero_paciente }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
