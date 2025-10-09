<div class="p-6">
    <!-- Header -->
    <div class="mb-6">
        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100">Mapa del Hospital</h2>
        <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">
            Visualización completa de la estructura hospitalaria con estados en tiempo real
        </p>
    </div>

    <!-- Estadísticas Generales -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <!-- Total de Áreas -->
        <div class="bg-white dark:bg-slate-900 rounded-lg border border-slate-200 dark:border-slate-700 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Total Áreas</p>
                    <p class="text-2xl font-semibold text-slate-900 dark:text-slate-100">{{ $estadisticas['total_areas'] }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total de Camas -->
        <div class="bg-white dark:bg-slate-900 rounded-lg border border-slate-200 dark:border-slate-700 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Total Camas</p>
                    <p class="text-2xl font-semibold text-slate-900 dark:text-slate-100">{{ $estadisticas['total_camas'] }}</p>
                </div>
                <div class="w-12 h-12 bg-cyan-100 dark:bg-cyan-900/30 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-cyan-600 dark:text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Camas Libres -->
        <div class="bg-white dark:bg-slate-900 rounded-lg border border-slate-200 dark:border-slate-700 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Camas Libres</p>
                    <p class="text-2xl font-semibold text-green-600 dark:text-green-400">{{ $estadisticas['camas_libres'] }}</p>
                    <p class="text-xs text-slate-500 dark:text-slate-500">{{ $estadisticas['porcentaje_disponibilidad'] }}% disponible</p>
                </div>
                <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Camas Ocupadas -->
        <div class="bg-white dark:bg-slate-900 rounded-lg border border-slate-200 dark:border-slate-700 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Camas Ocupadas</p>
                    <p class="text-2xl font-semibold text-red-600 dark:text-red-400">{{ $estadisticas['camas_ocupadas'] }}</p>
                    <p class="text-xs text-slate-500 dark:text-slate-500">{{ $estadisticas['porcentaje_ocupacion'] }}% ocupación</p>
                </div>
                <div class="w-12 h-12 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-white dark:bg-slate-900 rounded-lg border border-slate-200 dark:border-slate-700 p-4 mb-6">
        <h3 class="text-lg font-semibold mb-4 text-slate-900 dark:text-slate-100">Filtros</h3>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Filtro por Área -->
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Área</label>
                <select wire:model="filtroArea" class="w-full px-4 py-2 border border-slate-300 rounded-md text-slate-900 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-900 dark:border-slate-600 dark:text-slate-100">
                    <option value="">Todas las áreas</option>
                    @foreach($todasAreas as $area)
                        <option value="{{ $area->id }}">{{ $area->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Filtro por Estado -->
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Estado de Cama</label>
                <select wire:model="filtroEstado" class="w-full px-4 py-2 border border-slate-300 rounded-md text-slate-900 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-900 dark:border-slate-600 dark:text-slate-100">
                    <option value="">Todos los estados</option>
                    <option value="libre">Libre</option>
                    <option value="ocupada">Ocupada</option>
                    <option value="en_limpieza">En Limpieza</option>
                    <option value="en_mantenimiento">En Mantenimiento</option>
                </select>
            </div>

            <!-- Solo Disponibles -->
            <div class="flex items-end">
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" wire:model="soloDisponibles" class="w-4 h-4 text-blue-600 border-slate-300 rounded focus:ring-blue-500 dark:border-slate-600 dark:bg-slate-900">
                    <span class="ml-2 text-sm text-slate-700 dark:text-slate-300">Solo disponibles</span>
                </label>
            </div>

            <!-- Botones -->
            <div class="flex items-end gap-2">
                <button wire:click="aplicarFiltros" class="px-4 py-2 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 transition-colors">
                    Aplicar
                </button>
                <button wire:click="limpiarFiltros" class="px-4 py-2 bg-slate-200 text-slate-900 font-medium rounded-md hover:bg-slate-300 dark:bg-slate-800 dark:text-white dark:hover:bg-slate-700 transition-colors">
                    Limpiar
                </button>
            </div>
        </div>
    </div>

    <!-- Mapa del Hospital (Accordion) -->
    <div class="space-y-4">
        @forelse($areas as $area)
            <div x-data="{ openArea: false }" class="bg-white dark:bg-slate-900 rounded-lg border border-slate-200 dark:border-slate-700 overflow-hidden">
                <!-- Área Header -->
                <button
                    @click="openArea = !openArea"
                    class="w-full px-6 py-4 flex items-center justify-between bg-slate-50 dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors"
                >
                    <div class="flex items-center gap-4">
                        <svg class="w-5 h-5 text-slate-400 transition-transform" :class="{ 'rotate-90': openArea }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                        <div class="text-left">
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">{{ $area->nombre }}</h3>
                            <p class="text-sm text-slate-600 dark:text-slate-400">
                                {{ $area->pisos->count() }} pisos • {{ $area->pisos->sum(fn($p) => $p->cuartos->count()) }} cuartos
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                            {{ $area->codigo }}
                        </span>
                    </div>
                </button>

                <!-- Área Content (Pisos) -->
                <div x-show="openArea" x-collapse class="border-t border-slate-200 dark:border-slate-700">
                    <div class="p-6 space-y-4">
                        @foreach($area->pisos as $piso)
                            <div x-data="{ openPiso: false }" class="border border-slate-200 dark:border-slate-700 rounded-lg overflow-hidden">
                                <!-- Piso Header -->
                                <button
                                    @click="openPiso = !openPiso"
                                    class="w-full px-4 py-3 flex items-center justify-between bg-slate-50 dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors"
                                >
                                    <div class="flex items-center gap-3">
                                        <svg class="w-4 h-4 text-slate-400 transition-transform" :class="{ 'rotate-90': openPiso }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                        <span class="font-medium text-slate-900 dark:text-slate-100">
                                            Piso {{ $piso->numero_piso }}
                                            @if($piso->especialidad)
                                                - {{ $piso->especialidad }}
                                            @endif
                                        </span>
                                    </div>
                                    <span class="text-sm text-slate-600 dark:text-slate-400">
                                        {{ $piso->cuartos->count() }} cuartos
                                    </span>
                                </button>

                                <!-- Piso Content (Cuartos) -->
                                <div x-show="openPiso" x-collapse class="bg-white dark:bg-slate-900 p-4">
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                        @foreach($piso->cuartos as $cuarto)
                                            <div class="border border-slate-200 dark:border-slate-700 rounded-lg p-4">
                                                <!-- Cuarto Header -->
                                                <div class="flex items-center justify-between mb-3">
                                                    <span class="font-mono font-semibold text-slate-900 dark:text-slate-100">
                                                        Cuarto {{ $cuarto->numero_cuarto }}
                                                    </span>
                                                    @php
                                                        $tipoColors = [
                                                            'individual' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
                                                            'doble' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
                                                            'multiple' => 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400',
                                                        ];
                                                    @endphp
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $tipoColors[$cuarto->tipo] }}">
                                                        {{ ucfirst($cuarto->tipo) }}
                                                    </span>
                                                </div>

                                                <!-- Camas -->
                                                <div class="space-y-2">
                                                    @forelse($cuarto->camas as $cama)
                                                        @php
                                                            $estadoColors = [
                                                                'libre' => 'bg-green-100 text-green-800 border-green-300 dark:bg-green-900/30 dark:text-green-400 dark:border-green-700',
                                                                'ocupada' => 'bg-red-100 text-red-800 border-red-300 dark:bg-red-900/30 dark:text-red-400 dark:border-red-700',
                                                                'en_limpieza' => 'bg-yellow-100 text-yellow-800 border-yellow-300 dark:bg-yellow-900/30 dark:text-yellow-400 dark:border-yellow-700',
                                                                'en_mantenimiento' => 'bg-slate-100 text-slate-800 border-slate-300 dark:bg-slate-700 dark:text-slate-400 dark:border-slate-600',
                                                            ];
                                                        @endphp
                                                        <div class="flex items-center justify-between px-3 py-2 border rounded {{ $estadoColors[$cama->estado->value] }}">
                                                            <span class="font-mono text-sm font-medium">Cama {{ $cama->numero_cama }}</span>
                                                            <span class="text-xs font-medium">{{ $cama->estado->label() }}</span>
                                                        </div>
                                                    @empty
                                                        <p class="text-sm text-slate-500 dark:text-slate-500 italic">Sin camas</p>
                                                    @endforelse
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white dark:bg-slate-900 rounded-lg border border-slate-200 dark:border-slate-700 p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-slate-900 dark:text-slate-100">No se encontraron resultados</h3>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                    Intenta ajustar los filtros para ver más resultados.
                </p>
            </div>
        @endforelse
    </div>
</div>