<div class="p-6">
    <div class="mb-6 flex items-center justify-between">
        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100">Gestión de Cuartos</h2>
        <button
            wire:click="$toggle('showForm')"
            class="px-4 py-2 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors dark:bg-blue-600 dark:hover:bg-blue-700"
        >
            {{ $showForm ? 'Cancelar' : '+ Nuevo Cuarto' }}
        </button>
    </div>

    @if (session()->has('message'))
        <div class="mb-4 bg-green-50 border-l-4 border-green-500 p-4 dark:bg-green-900/20 dark:border-green-500">
            <p class="text-green-800 dark:text-green-400">{{ session('message') }}</p>
        </div>
    @endif

    @if ($showForm)
        <div class="mb-6 bg-white dark:bg-slate-900 rounded-lg border border-slate-200 dark:border-slate-700 p-6">
            <h3 class="text-lg font-semibold mb-4 text-slate-900 dark:text-slate-100">
                {{ $editingCuartoId ? 'Editar Cuarto' : 'Nuevo Cuarto' }}
            </h3>
            <form wire:submit="save" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Piso -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                            Piso *
                        </label>
                        <select
                            wire:model="piso_id"
                            class="w-full px-4 py-2 border border-slate-300 rounded-md text-slate-900 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-900 dark:border-slate-600 dark:text-slate-100 transition-all"
                        >
                            <option value="">Seleccionar piso...</option>
                            @foreach($pisos as $piso)
                                <option value="{{ $piso->id }}">
                                    Piso {{ $piso->numero_piso }} - {{ $piso->area->nombre }}
                                    @if($piso->especialidad)
                                        ({{ $piso->especialidad }})
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        @error('piso_id')
                            <span class="text-red-600 text-xs">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Número de Cuarto -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                            Número de Cuarto *
                        </label>
                        <input
                            type="text"
                            wire:model="numero_cuarto"
                            placeholder="Ej: 301"
                            class="w-full px-4 py-2 border border-slate-300 rounded-md text-slate-900 placeholder:text-slate-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-900 dark:border-slate-600 dark:text-slate-100 dark:placeholder:text-slate-500 transition-all"
                        >
                        @error('numero_cuarto')
                            <span class="text-red-600 text-xs">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Tipo -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                            Tipo de Cuarto *
                        </label>
                        <select
                            wire:model="tipo"
                            class="w-full px-4 py-2 border border-slate-300 rounded-md text-slate-900 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-900 dark:border-slate-600 dark:text-slate-100 transition-all"
                        >
                            <option value="individual">Individual (1 cama)</option>
                            <option value="doble">Doble (2 camas)</option>
                            <option value="multiple">Múltiple (4-6 camas)</option>
                        </select>
                        @error('tipo')
                            <span class="text-red-600 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="flex gap-3">
                    <button
                        type="submit"
                        class="px-4 py-2 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 transition-colors"
                    >
                        {{ $editingCuartoId ? 'Actualizar' : 'Crear' }} Cuarto
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

    <!-- Tabla de Cuartos -->
    <div class="bg-white dark:bg-slate-900 rounded-lg border border-slate-200 dark:border-slate-700 overflow-hidden">
        <table class="w-full">
            <thead class="bg-slate-50 dark:bg-slate-800">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Número</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Piso</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Área</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Tipo</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Camas</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                @forelse($cuartos as $cuarto)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="font-mono text-sm font-semibold text-slate-900 dark:text-slate-100">
                                {{ $cuarto->numero_cuarto }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                                Piso {{ $cuarto->piso->numero_piso }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-slate-700 dark:text-slate-300">
                                {{ $cuarto->piso->area->nombre }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $tipoColors = [
                                    'individual' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
                                    'doble' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
                                    'multiple' => 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400',
                                ];
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $tipoColors[$cuarto->tipo] }}">
                                {{ ucfirst($cuarto->tipo) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a
                                href="{{ route('admin.camas', ['cuarto_id' => $cuarto->id]) }}"
                                class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 font-medium"
                            >
                                Ver camas ({{ $cuarto->camas->count() }})
                            </a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                            <button
                                wire:click="edit({{ $cuarto->id }})"
                                class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300"
                            >
                                Editar
                            </button>
                            <button
                                wire:click="delete({{ $cuarto->id }})"
                                wire:confirm="¿Estás seguro de eliminar este cuarto? Se eliminarán todas sus camas."
                                class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
                            >
                                Eliminar
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-slate-500 dark:text-slate-400">
                            No hay cuartos registrados. Crea uno nuevo para comenzar.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginación -->
    <div class="mt-4">
        {{ $cuartos->links() }}
    </div>
</div>