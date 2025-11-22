<div class="p-6">
    {{-- Page Header --}}
    <header class="mb-8">
        <div class="mx-auto max-w-7xl">
            <h1 class="text-3xl font-bold tracking-tight text-slate-900 dark:text-white">Dashboard del Administrador</h1>
            <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">
                Resumen general del sistema NurseHub
            </p>
        </div>
    </header>

    {{-- Main Stats Grid --}}
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 mb-8">
        {{-- Total de Áreas --}}
        <div class="overflow-hidden rounded-lg bg-white shadow dark:bg-slate-800 border border-slate-200 dark:border-slate-700">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="shrink-0">
                        <div class="rounded-md bg-blue-600 p-3">
                            <svg class="size-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="truncate text-sm font-medium text-slate-500 dark:text-slate-400">Áreas del Hospital</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-slate-900 dark:text-white">{{ $stats['total_areas'] }}</div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-slate-50 px-5 py-3 dark:bg-slate-900/50">
                <div class="text-sm">
                    <a href="{{ route('admin.areas') }}" class="font-medium text-blue-600 hover:text-blue-500 dark:text-blue-400">Ver áreas →</a>
                </div>
            </div>
        </div>

        {{-- Total de Pisos --}}
        <div class="overflow-hidden rounded-lg bg-white shadow dark:bg-slate-800 border border-slate-200 dark:border-slate-700">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="shrink-0">
                        <div class="rounded-md bg-cyan-600 p-3">
                            <svg class="size-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.429 9.75 2.25 12l4.179 2.25m0-4.5 5.571 3 5.571-3m-11.142 0L2.25 7.5 12 2.25l9.75 5.25-4.179 2.25m0 0L21.75 12l-4.179 2.25m0 0 4.179 2.25L12 21.75 2.25 16.5l4.179-2.25m11.142 0-5.571 3-5.571-3" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="truncate text-sm font-medium text-slate-500 dark:text-slate-400">Pisos Totales</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-slate-900 dark:text-white">{{ $stats['total_pisos'] }}</div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-slate-50 px-5 py-3 dark:bg-slate-900/50">
                <div class="text-sm">
                    <a href="{{ route('admin.pisos') }}" class="font-medium text-cyan-600 hover:text-cyan-500 dark:text-cyan-400">Ver pisos →</a>
                </div>
            </div>
        </div>

        {{-- Total de Camas --}}
        <div class="overflow-hidden rounded-lg bg-white shadow dark:bg-slate-800 border border-slate-200 dark:border-slate-700">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="shrink-0">
                        <div class="rounded-md bg-purple-600 p-3">
                            <svg class="size-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="truncate text-sm font-medium text-slate-500 dark:text-slate-400">Camas Totales</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-slate-900 dark:text-white">{{ $stats['total_camas'] }}</div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-slate-50 px-5 py-3 dark:bg-slate-900/50">
                <div class="text-sm">
                    <a href="{{ route('admin.camas') }}" class="font-medium text-purple-600 hover:text-purple-500 dark:text-purple-400">Ver camas →</a>
                </div>
            </div>
        </div>

        {{-- Total de Usuarios --}}
        <div class="overflow-hidden rounded-lg bg-white shadow dark:bg-slate-800 border border-slate-200 dark:border-slate-700">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="shrink-0">
                        <div class="rounded-md bg-green-600 p-3">
                            <svg class="size-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="truncate text-sm font-medium text-slate-500 dark:text-slate-400">Usuarios Totales</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-slate-900 dark:text-white">{{ $stats['total_usuarios'] }}</div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-slate-50 px-5 py-3 dark:bg-slate-900/50">
                <div class="text-sm">
                    <a href="{{ route('admin.users') }}" class="font-medium text-green-600 hover:text-green-500 dark:text-green-400">Ver usuarios →</a>
                </div>
            </div>
        </div>
    </div>

    {{-- Bed Status and Staff Stats --}}
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2 mb-8">
        {{-- Estado de Camas --}}
        <div class="overflow-hidden rounded-lg bg-white shadow dark:bg-slate-800 border border-slate-200 dark:border-slate-700">
            <div class="border-b border-slate-200 px-5 py-4 dark:border-slate-700">
                <h3 class="text-base font-semibold text-slate-900 dark:text-white">Estado de Camas</h3>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Distribución actual de {{ $stats['total_camas'] }} camas</p>
            </div>
            <div class="p-5">
                {{-- Porcentaje de Ocupación --}}
                <div class="mb-6">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Ocupación General</span>
                        <span class="text-sm font-semibold text-slate-900 dark:text-white">{{ $stats['porcentaje_ocupacion'] }}%</span>
                    </div>
                    <div class="w-full bg-slate-200 rounded-full h-2.5 dark:bg-slate-700">
                        <div class="bg-blue-600 h-2.5 rounded-full transition-all" style="width: {{ $stats['porcentaje_ocupacion'] }}%"></div>
                    </div>
                </div>

                {{-- Distribución de Camas --}}
                <dl class="space-y-4">
                    <div class="flex items-center justify-between">
                        <dt class="flex items-center text-sm font-medium text-slate-600 dark:text-slate-400">
                            <span class="inline-block size-3 rounded-full bg-green-500 mr-2"></span>
                            Libres
                        </dt>
                        <dd class="text-sm font-semibold text-slate-900 dark:text-white">{{ $stats['camas_libres'] }}</dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt class="flex items-center text-sm font-medium text-slate-600 dark:text-slate-400">
                            <span class="inline-block size-3 rounded-full bg-red-500 mr-2"></span>
                            Ocupadas
                        </dt>
                        <dd class="text-sm font-semibold text-slate-900 dark:text-white">{{ $stats['camas_ocupadas'] }}</dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt class="flex items-center text-sm font-medium text-slate-600 dark:text-slate-400">
                            <span class="inline-block size-3 rounded-full bg-yellow-500 mr-2"></span>
                            En Limpieza
                        </dt>
                        <dd class="text-sm font-semibold text-slate-900 dark:text-white">{{ $stats['camas_limpieza'] }}</dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt class="flex items-center text-sm font-medium text-slate-600 dark:text-slate-400">
                            <span class="inline-block size-3 rounded-full bg-slate-500 mr-2"></span>
                            En Mantenimiento
                        </dt>
                        <dd class="text-sm font-semibold text-slate-900 dark:text-white">{{ $stats['camas_mantenimiento'] }}</dd>
                    </div>
                </dl>
            </div>
            <div class="bg-slate-50 px-5 py-3 dark:bg-slate-900/50">
                <div class="text-sm">
                    <a href="{{ route('hospital.map') }}" class="font-medium text-blue-600 hover:text-blue-500 dark:text-blue-400">Ver mapa completo →</a>
                </div>
            </div>
        </div>

        {{-- Personal de Enfermería --}}
        <div class="overflow-hidden rounded-lg bg-white shadow dark:bg-slate-800 border border-slate-200 dark:border-slate-700">
            <div class="border-b border-slate-200 px-5 py-4 dark:border-slate-700">
                <h3 class="text-base font-semibold text-slate-900 dark:text-white">Personal de Enfermería</h3>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Estadísticas del personal registrado</p>
            </div>
            <div class="p-5">
                <dl class="space-y-6">
                    <div>
                        <dt class="text-sm font-medium text-slate-600 dark:text-slate-400">Total de Usuarios</dt>
                        <dd class="mt-1 flex items-baseline gap-x-2">
                            <span class="text-3xl font-semibold text-slate-900 dark:text-white">{{ $stats['total_usuarios'] }}</span>
                            <span class="text-sm text-slate-500 dark:text-slate-400">
                                ({{ $stats['usuarios_activos'] }} activos)
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-slate-600 dark:text-slate-400">Total de Enfermeros</dt>
                        <dd class="mt-1 text-3xl font-semibold text-slate-900 dark:text-white">{{ $stats['total_enfermeros'] }}</dd>
                    </div>
                    <div class="grid grid-cols-2 gap-4 pt-4 border-t border-slate-200 dark:border-slate-700">
                        <div>
                            <dt class="text-sm font-medium text-slate-600 dark:text-slate-400">Enfermeros Fijos</dt>
                            <dd class="mt-1 text-2xl font-semibold text-blue-600 dark:text-blue-400">{{ $stats['enfermeros_fijos'] }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-slate-600 dark:text-slate-400">Enfermeros Rotativos</dt>
                            <dd class="mt-1 text-2xl font-semibold text-cyan-600 dark:text-cyan-400">{{ $stats['enfermeros_rotativos'] }}</dd>
                        </div>
                    </div>
                </dl>
            </div>
            <div class="bg-slate-50 px-5 py-3 dark:bg-slate-900/50">
                <div class="text-sm">
                    <a href="{{ route('admin.users') }}" class="font-medium text-blue-600 hover:text-blue-500 dark:text-blue-400">Ver personal →</a>
                </div>
            </div>
        </div>
    </div>

    {{-- Bottom Section: Top Areas and Recent Users --}}
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        {{-- Top Áreas por Capacidad --}}
        <div class="overflow-hidden rounded-lg bg-white shadow dark:bg-slate-800 border border-slate-200 dark:border-slate-700">
            <div class="border-b border-slate-200 px-5 py-4 dark:border-slate-700">
                <h3 class="text-base font-semibold text-slate-900 dark:text-white">Áreas por Capacidad</h3>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Top 5 áreas con mayor cantidad de camas</p>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                    <thead class="bg-slate-50 dark:bg-slate-900/50">
                        <tr>
                            <th scope="col" class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400">Área</th>
                            <th scope="col" class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400">Código</th>
                            <th scope="col" class="px-5 py-3 text-center text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400">Camas</th>
                            <th scope="col" class="px-5 py-3 text-center text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400">24/7</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white dark:divide-slate-700 dark:bg-slate-800">
                        @forelse($stats['top_areas'] as $area)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-900/50 transition-colors">
                            <td class="whitespace-nowrap px-5 py-4 text-sm font-medium text-slate-900 dark:text-white">{{ $area->nombre }}</td>
                            <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-500 dark:text-slate-400">
                                <span class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-700/10 dark:bg-blue-400/10 dark:text-blue-400 dark:ring-blue-400/30">
                                    {{ $area->codigo }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-5 py-4 text-sm text-center font-semibold text-slate-700 dark:text-slate-300">{{ $area->camas_count }}</td>
                            <td class="whitespace-nowrap px-5 py-4 text-sm text-center">
                                @if($area->opera_24_7)
                                    <span class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20 dark:bg-green-500/10 dark:text-green-400 dark:ring-green-500/20">Sí</span>
                                @else
                                    <span class="inline-flex items-center rounded-md bg-slate-50 px-2 py-1 text-xs font-medium text-slate-600 ring-1 ring-inset ring-slate-500/10 dark:bg-slate-400/10 dark:text-slate-400 dark:ring-slate-400/20">No</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-5 py-8 text-center text-sm text-slate-500 dark:text-slate-400">
                                No hay áreas registradas
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Últimos Usuarios Registrados --}}
        <div class="overflow-hidden rounded-lg bg-white shadow dark:bg-slate-800 border border-slate-200 dark:border-slate-700">
            <div class="border-b border-slate-200 px-5 py-4 dark:border-slate-700">
                <h3 class="text-base font-semibold text-slate-900 dark:text-white">Últimos Usuarios Registrados</h3>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">5 usuarios más recientes en el sistema</p>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                    <thead class="bg-slate-50 dark:bg-slate-900/50">
                        <tr>
                            <th scope="col" class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400">Usuario</th>
                            <th scope="col" class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400">Rol</th>
                            <th scope="col" class="px-5 py-3 text-center text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400">Estado</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white dark:divide-slate-700 dark:bg-slate-800">
                        @forelse($stats['ultimos_usuarios'] as $usuario)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-900/50 transition-colors">
                            <td class="px-5 py-4">
                                <div class="flex items-center">
                                    <div class="shrink-0">
                                        <div class="h-10 w-10 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center">
                                            <span class="text-sm font-medium text-slate-600 dark:text-slate-300">
                                                {{ strtoupper(substr($usuario->name, 0, 1)) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-slate-900 dark:text-white">{{ $usuario->name }}</div>
                                        <div class="text-sm text-slate-500 dark:text-slate-400">{{ $usuario->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="whitespace-nowrap px-5 py-4 text-sm">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($usuario->role->value === 'admin') bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400
                                    @elseif($usuario->role->value === 'coordinador') bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400
                                    @elseif($usuario->role->value === 'jefe_piso') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400
                                    @elseif($usuario->role->value === 'enfermero') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400
                                    @else bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400
                                    @endif">
                                    {{ $usuario->role->label() }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-5 py-4 text-sm text-center">
                                @if($usuario->is_active)
                                    <span class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20 dark:bg-green-500/10 dark:text-green-400 dark:ring-green-500/20">Activo</span>
                                @else
                                    <span class="inline-flex items-center rounded-md bg-red-50 px-2 py-1 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/20 dark:bg-red-500/10 dark:text-red-400 dark:ring-red-500/20">Inactivo</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-5 py-8 text-center text-sm text-slate-500 dark:text-slate-400">
                                No hay usuarios registrados
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="bg-slate-50 px-5 py-3 dark:bg-slate-900/50">
                <div class="text-sm">
                    <a href="{{ route('admin.users') }}" class="font-medium text-blue-600 hover:text-blue-500 dark:text-blue-400">Ver todos los usuarios →</a>
                </div>
            </div>
        </div>
    </div>
</div>
