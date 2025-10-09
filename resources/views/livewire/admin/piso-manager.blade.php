<div class="px-4 sm:px-6 lg:px-8">
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-base font-semibold text-gray-900 dark:text-white">Pisos del Hospital</h1>
            <p class="mt-2 text-sm text-gray-700 dark:text-gray-400">Gestiona los pisos del hospital asignados a cada área.</p>
        </div>
        <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
            <button wire:click="create" type="button" class="block rounded-md bg-medical-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-medical-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-medical-600">
                Agregar piso
            </button>
        </div>
    </div>

    {{-- Mensaje de éxito --}}
    @if (session()->has('message'))
        <div class="mt-4 rounded-md bg-green-50 dark:bg-green-900/20 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800 dark:text-green-200">{{ session('message') }}</p>
                </div>
            </div>
        </div>
    @endif

    {{-- Formulario de creación/edición --}}
    @if ($showForm)
        <div class="mt-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-4">
                    {{ $isEditing ? 'Editar Piso' : 'Nuevo Piso' }}
                </h3>

                <form wire:submit.prevent="{{ $isEditing ? 'update' : 'store' }}" class="space-y-6">
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        {{-- Nombre --}}
                        <div>
                            <label for="nombre" class="block text-sm font-medium text-gray-900 dark:text-white">Nombre</label>
                            <input wire:model="nombre" type="text" id="nombre" class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 dark:text-white dark:bg-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-medical-600 sm:text-sm">
                            @error('nombre') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                        </div>

                        {{-- Número de Piso --}}
                        <div>
                            <label for="numero_piso" class="block text-sm font-medium text-gray-900 dark:text-white">Número de Piso</label>
                            <input wire:model="numero_piso" type="number" min="1" max="50" id="numero_piso" class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 dark:text-white dark:bg-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-medical-600 sm:text-sm">
                            @error('numero_piso') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                        </div>

                        {{-- Área --}}
                        <div>
                            <label for="area_id" class="block text-sm font-medium text-gray-900 dark:text-white">Área</label>
                            <select wire:model="area_id" id="area_id" class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 dark:text-white dark:bg-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 focus:ring-2 focus:ring-inset focus:ring-medical-600 sm:text-sm">
                                <option value="">Selecciona un área</option>
                                @foreach ($areas as $area)
                                    <option value="{{ $area->id }}">{{ $area->nombre }} ({{ $area->codigo }})</option>
                                @endforeach
                            </select>
                            @error('area_id') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                        </div>

                        {{-- Especialidad --}}
                        <div>
                            <label for="especialidad" class="block text-sm font-medium text-gray-900 dark:text-white">Especialidad</label>
                            <input wire:model="especialidad" type="text" id="especialidad" class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 dark:text-white dark:bg-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-medical-600 sm:text-sm">
                            @error('especialidad') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Botones --}}
                    <div class="flex justify-end gap-3">
                        <button wire:click="cancel" type="button" class="rounded-md bg-white dark:bg-gray-700 px-3 py-2 text-sm font-semibold text-gray-900 dark:text-white shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600">
                            Cancelar
                        </button>
                        <button type="submit" class="rounded-md bg-medical-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-medical-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-medical-600">
                            {{ $isEditing ? 'Actualizar' : 'Guardar' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Tabla de pisos --}}
    <div class="mt-8 flow-root">
        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-300 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 dark:text-white sm:pl-6">Nombre</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">Número</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">Área</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">Especialidad</th>
                                <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                    <span class="sr-only">Acciones</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-900">
                            @forelse ($pisos as $piso)
                                <tr>
                                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 dark:text-white sm:pl-6">
                                        {{ $piso->nombre }}
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">
                                        <span class="inline-flex items-center rounded-md bg-blue-50 dark:bg-blue-900/20 px-2 py-1 text-xs font-medium text-blue-700 dark:text-blue-400 ring-1 ring-inset ring-blue-700/10 dark:ring-blue-400/20">
                                            Piso {{ $piso->numero_piso }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">
                                        <span class="inline-flex items-center rounded-md bg-medical-50 dark:bg-medical-900/20 px-2 py-1 text-xs font-medium text-medical-700 dark:text-medical-400 ring-1 ring-inset ring-medical-700/10 dark:ring-medical-400/20">
                                            {{ $piso->area->nombre }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">
                                        {{ $piso->especialidad ?? '-' }}
                                    </td>
                                    <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                        <button wire:click="edit({{ $piso->id }})" class="text-medical-600 hover:text-medical-900 dark:text-medical-400 dark:hover:text-medical-300 mr-4">
                                            Editar
                                        </button>
                                        <button wire:click="delete({{ $piso->id }})" wire:confirm="¿Estás seguro de eliminar este piso?" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                            Eliminar
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-3 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                                        No hay pisos registrados. Crea un nuevo piso para comenzar.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Paginación --}}
    <div class="mt-4">
        {{ $pisos->links() }}
    </div>
</div>
