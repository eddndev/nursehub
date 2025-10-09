<div class="px-4 sm:px-6 lg:px-8">
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-base font-semibold text-gray-900 dark:text-white">Áreas del Hospital</h1>
            <p class="mt-2 text-sm text-gray-700 dark:text-gray-400">Gestiona las áreas del hospital, incluyendo configuración de ratios y certificaciones.</p>
        </div>
        <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
            <button wire:click="create" type="button" class="block rounded-md bg-medical-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-medical-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-medical-600">
                Agregar área
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
                    {{ $isEditing ? 'Editar Área' : 'Nueva Área' }}
                </h3>

                <form wire:submit.prevent="{{ $isEditing ? 'update' : 'store' }}" class="space-y-6">
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        {{-- Nombre --}}
                        <div>
                            <label for="nombre" class="block text-sm font-medium text-gray-900 dark:text-white">Nombre</label>
                            <input wire:model="nombre" type="text" id="nombre" class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 dark:text-white dark:bg-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-medical-600 sm:text-sm">
                            @error('nombre') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                        </div>

                        {{-- Código --}}
                        <div>
                            <label for="codigo" class="block text-sm font-medium text-gray-900 dark:text-white">Código</label>
                            <input wire:model="codigo" type="text" id="codigo" maxlength="10" class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 dark:text-white dark:bg-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-medical-600 sm:text-sm">
                            @error('codigo') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                        </div>

                        {{-- Descripción --}}
                        <div class="sm:col-span-2">
                            <label for="descripcion" class="block text-sm font-medium text-gray-900 dark:text-white">Descripción</label>
                            <textarea wire:model="descripcion" id="descripcion" rows="3" class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 dark:text-white dark:bg-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-medical-600 sm:text-sm"></textarea>
                            @error('descripcion') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                        </div>

                        {{-- Ratio Enfermero-Paciente --}}
                        <div>
                            <label for="ratio_enfermero_paciente" class="block text-sm font-medium text-gray-900 dark:text-white">Ratio Enfermero:Paciente</label>
                            <input wire:model="ratio_enfermero_paciente" type="number" step="0.01" min="0.01" max="99.99" id="ratio_enfermero_paciente" class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 dark:text-white dark:bg-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-medical-600 sm:text-sm">
                            @error('ratio_enfermero_paciente') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                        </div>

                        {{-- Checkboxes --}}
                        <div class="space-y-4">
                            <div class="flex items-center">
                                <input wire:model="opera_24_7" id="opera_24_7" type="checkbox" class="h-4 w-4 rounded border-gray-300 dark:border-gray-600 text-medical-600 focus:ring-medical-600">
                                <label for="opera_24_7" class="ml-3 block text-sm font-medium text-gray-900 dark:text-white">Opera 24/7</label>
                            </div>

                            <div class="flex items-center">
                                <input wire:model="requiere_certificacion" id="requiere_certificacion" type="checkbox" class="h-4 w-4 rounded border-gray-300 dark:border-gray-600 text-medical-600 focus:ring-medical-600">
                                <label for="requiere_certificacion" class="ml-3 block text-sm font-medium text-gray-900 dark:text-white">Requiere Certificación</label>
                            </div>
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

    {{-- Tabla de áreas --}}
    <div class="mt-8 flow-root">
        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-300 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 dark:text-white sm:pl-6">Nombre</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">Código</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">Opera 24/7</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">Ratio</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">Certificación</th>
                                <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                    <span class="sr-only">Acciones</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-900">
                            @forelse ($areas as $area)
                                <tr>
                                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 dark:text-white sm:pl-6">
                                        {{ $area->nombre }}
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">
                                        <span class="inline-flex items-center rounded-md bg-medical-50 dark:bg-medical-900/20 px-2 py-1 text-xs font-medium text-medical-700 dark:text-medical-400 ring-1 ring-inset ring-medical-700/10 dark:ring-medical-400/20">
                                            {{ $area->codigo }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">
                                        @if ($area->opera_24_7)
                                            <span class="inline-flex items-center rounded-md bg-green-50 dark:bg-green-900/20 px-2 py-1 text-xs font-medium text-green-700 dark:text-green-400">Sí</span>
                                        @else
                                            <span class="inline-flex items-center rounded-md bg-gray-50 dark:bg-gray-800 px-2 py-1 text-xs font-medium text-gray-600 dark:text-gray-400">No</span>
                                        @endif
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">
                                        1:{{ number_format($area->ratio_enfermero_paciente, 2) }}
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">
                                        @if ($area->requiere_certificacion)
                                            <span class="inline-flex items-center rounded-md bg-yellow-50 dark:bg-yellow-900/20 px-2 py-1 text-xs font-medium text-yellow-700 dark:text-yellow-400">Requerida</span>
                                        @else
                                            <span class="inline-flex items-center rounded-md bg-gray-50 dark:bg-gray-800 px-2 py-1 text-xs font-medium text-gray-600 dark:text-gray-400">No requerida</span>
                                        @endif
                                    </td>
                                    <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                        <button wire:click="edit({{ $area->id }})" class="text-medical-600 hover:text-medical-900 dark:text-medical-400 dark:hover:text-medical-300 mr-4">
                                            Editar
                                        </button>
                                        <button wire:click="delete({{ $area->id }})" wire:confirm="¿Estás seguro de eliminar esta área?" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                            Eliminar
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-3 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                                        No hay áreas registradas. Crea una nueva área para comenzar.
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
        {{ $areas->links() }}
    </div>
</div>
