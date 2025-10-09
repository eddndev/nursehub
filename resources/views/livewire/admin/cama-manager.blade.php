<div class="p-6">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100">Gestión de Camas</h2>
            @if($cuarto)
                <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">
                    Cuarto {{ $cuarto->numero_cuarto }} - Piso {{ $cuarto->piso->numero_piso }} - {{ $cuarto->piso->area->nombre }}
                    <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-slate-100 text-slate-800 dark:bg-slate-800 dark:text-slate-300">
                        {{ ucfirst($cuarto->tipo) }}
                    </span>
                </p>
            @endif
        </div>
        <div class="flex gap-2">
            @if($cuarto)
                <a
                    href="{{ route('admin.cuartos') }}"
                    class="px-4 py-2 bg-slate-200 text-slate-900 font-medium rounded-md hover:bg-slate-300 dark:bg-slate-800 dark:text-white dark:hover:bg-slate-700 transition-colors"
                >
                    ← Volver a Cuartos
                </a>
            @endif
            <button
                wire:click="$toggle('showForm')"
                class="px-4 py-2 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors dark:bg-blue-600 dark:hover:bg-blue-700"
            >
                {{ $showForm ? 'Cancelar' : '+ Nueva Cama' }}
            </button>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="mb-4 bg-green-50 border-l-4 border-green-500 p-4 dark:bg-green-900/20 dark:border-green-500">
            <p class="text-green-800 dark:text-green-400">{{ session('message') }}</p>
        </div>
    @endif

    @if ($showForm)
        <div class="mb-6 bg-white dark:bg-slate-900 rounded-lg border border-slate-200 dark:border-slate-700 p-6">
            <h3 class="text-lg font-semibold mb-4 text-slate-900 dark:text-slate-100">
                {{ $editingCamaId ? 'Editar Cama' : 'Nueva Cama' }}
            </h3>
            <form wire:submit="save" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Cuarto -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                            Cuarto *
                        </label>
                        <select
                            wire:model="cuarto_id"
                            {{ $filterCuartoId ? 'disabled' : '' }}
                            class="w-full px-4 py-2 border border-slate-300 rounded-md text-slate-900 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-900 dark:border-slate-600 dark:text-slate-100 transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            <option value="">Seleccionar cuarto...</option>
                            @foreach($cuartos as $cuartoOption)
                                <option value="{{ $cuartoOption->id }}">
                                    Cuarto {{ $cuartoOption->numero_cuarto }} - Piso {{ $cuartoOption->piso->numero_piso }} - {{ $cuartoOption->piso->area->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('cuarto_id')
                            <span class="text-red-600 text-xs">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Número de Cama -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                            Número de Cama *
                        </label>
                        <input
                            type="text"
                            wire:model="numero_cama"
                            placeholder="Ej: A, B, 1, 2..."
                            class="w-full px-4 py-2 border border-slate-300 rounded-md text-slate-900 placeholder:text-slate-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-900 dark:border-slate-600 dark:text-slate-100 dark:placeholder:text-slate-500 transition-all"
                        >
                        @error('numero_cama')
                            <span class="text-red-600 text-xs">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Estado -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                            Estado *
                        </label>
                        <select
                            wire:model="estado"
                            class="w-full px-4 py-2 border border-slate-300 rounded-md text-slate-900 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-900 dark:border-slate-600 dark:text-slate-100 transition-all"
                        >
                            @foreach($estados as $estadoOption)
                                <option value="{{ $estadoOption->value }}">
                                    {{ $estadoOption->label() }}
                                </option>
                            @endforeach
                        </select>
                        @error('estado')
                            <span class="text-red-600 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="flex gap-3">
                    <button
                        type="submit"
                        class="px-4 py-2 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 transition-colors"
                    >
                        {{ $editingCamaId ? 'Actualizar' : 'Crear' }} Cama
                    </button>
                    <button
                        type="button"
                        wire:click="cancelEdit"
                        class="px-4 py-2 bg-slate-200 text-slate-900 font-medium rounded-md hover:bg-slate-300 dark:bg-slate-800 dark:text-white dark:hover:bg-slate-700 transition-colors"
                    >
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    @endif

    <!-- Tabla de Camas -->
    <div class="bg-white dark:bg-slate-900 rounded-lg border border-slate-200 dark:border-slate-700 overflow-hidden">
        <table class="w-full">
            <thead class="bg-slate-50 dark:bg-slate-800">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Número</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Cuarto</th>
                    @if(!$filterCuartoId)
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Piso</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Área</th>
                    @endif
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Estado</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                @forelse($camas as $cama)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="font-mono text-sm font-semibold text-slate-900 dark:text-slate-100">
                                {{ $cama->numero_cama }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-slate-700 dark:text-slate-300">
                                {{ $cama->cuarto->numero_cuarto }}
                            </span>
                        </td>
                        @if(!$filterCuartoId)
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                                    Piso {{ $cama->cuarto->piso->numero_piso }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-slate-700 dark:text-slate-300">
                                    {{ $cama->cuarto->piso->area->nombre }}
                                </span>
                            </td>
                        @endif
                        <td class="px-6 py-4 whitespace-nowrap">
                            <select
                                wire:change="updateEstado({{ $cama->id }}, $event.target.value)"
                                class="text-xs font-medium rounded-full px-2.5 py-0.5 border-0 focus:ring-2 focus:ring-blue-500 {{ 'bg-' . $cama->estado->color() . '-100 text-' . $cama->estado->color() . '-800 dark:bg-' . $cama->estado->color() . '-900/30 dark:text-' . $cama->estado->color() . '-400' }}"
                            >
                                @foreach($estados as $estadoOption)
                                    <option value="{{ $estadoOption->value }}" {{ $cama->estado === $estadoOption ? 'selected' : '' }}>
                                        {{ $estadoOption->label() }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                            <button
                                wire:click="edit({{ $cama->id }})"
                                class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300"
                            >
                                Editar
                            </button>
                            <button
                                wire:click="delete({{ $cama->id }})"
                                wire:confirm="¿Estás seguro de eliminar esta cama?"
                                class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
                            >
                                Eliminar
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ $filterCuartoId ? 4 : 6 }}" class="px-6 py-4 text-center text-slate-500 dark:text-slate-400">
                            No hay camas registradas. Crea una nueva para comenzar.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginación -->
    <div class="mt-4">
        {{ $camas->links() }}
    </div>
</div>