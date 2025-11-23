<div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700 p-4 mb-6">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        {{-- Búsqueda --}}
        <div class="md:col-span-2">
            <label for="search" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                Buscar Paciente
            </label>
            <div class="relative">
                <input type="text" id="search" wire:model.live.debounce.300ms="search"
                    class="w-full pl-10 pr-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-blue-500"
                    placeholder="Nombre, CURP o Código QR...">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Filtro TRIAGE --}}
        <div>
            <label for="filtroTriage" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                Nivel TRIAGE
            </label>
            <select id="filtroTriage" wire:model.live="filtroTriage"
                class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-blue-500">
                <option value="">Todos los niveles</option>
                @foreach($nivelesTriage as $nivel)
                    <option value="{{ $nivel->value }}">{{ $nivel->getLabel() }}</option>
                @endforeach
            </select>
        </div>

        {{-- Filtro Estado --}}
        <div>
            <label for="filtroEstado" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                Estado
            </label>
            <select id="filtroEstado" wire:model.live="filtroEstado"
                class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-blue-500">
                <option value="todos">Todos</option>
                @foreach($estadosPaciente as $estado)
                    <option value="{{ $estado->value }}">{{ $estado->getLabel() }}</option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- Botón limpiar filtros --}}
    @if($search || $filtroTriage || ($filtroEstado && $filtroEstado !== 'activo'))
        <div class="mt-3 flex justify-end">
            <button wire:click="limpiarFiltros" type="button"
                class="inline-flex items-center px-3 py-1.5 text-sm text-slate-700 dark:text-slate-300 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 rounded-md transition-colors">
                <svg class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                Limpiar filtros
            </button>
        </div>
    @endif
</div>
