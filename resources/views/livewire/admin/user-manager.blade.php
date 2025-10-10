<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                {{-- Header --}}
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100">
                        Gestión de Usuarios
                    </h2>
                    @if (!$showForm)
                        <button wire:click="$set('showForm', true)"
                                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                            + Nuevo Usuario
                        </button>
                    @endif
                </div>

                {{-- Mensajes flash --}}
                @if (session()->has('message'))
                    <div class="mb-4 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg text-green-800 dark:text-green-200">
                        {{ session('message') }}
                    </div>
                @endif

                {{-- Formulario --}}
                @if ($showForm)
                    <div class="mb-6 p-6 bg-slate-50 dark:bg-slate-700/50 rounded-lg border border-slate-200 dark:border-slate-600">
                        <h3 class="text-lg font-medium text-slate-900 dark:text-slate-100 mb-4">
                            {{ $editingUserId ? 'Editar Usuario' : 'Nuevo Usuario' }}
                        </h3>

                        <form wire:submit.prevent="{{ $editingUserId ? 'update' : 'create' }}">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                {{-- Nombre --}}
                                <div>
                                    <label for="name" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                                        Nombre Completo *
                                    </label>
                                    <input type="text" id="name" wire:model="name"
                                           class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400">
                                    @error('name') <span class="text-red-600 dark:text-red-400 text-sm">{{ $message }}</span> @enderror
                                </div>

                                {{-- Email --}}
                                <div>
                                    <label for="email" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                                        Correo Electrónico *
                                    </label>
                                    <input type="email" id="email" wire:model="email"
                                           class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400">
                                    @error('email') <span class="text-red-600 dark:text-red-400 text-sm">{{ $message }}</span> @enderror
                                </div>

                                {{-- Password --}}
                                <div>
                                    <label for="password" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                                        Contraseña {{ $editingUserId ? '(dejar en blanco para mantener)' : '*' }}
                                    </label>
                                    <input type="password" id="password" wire:model="password"
                                           class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400">
                                    @error('password') <span class="text-red-600 dark:text-red-400 text-sm">{{ $message }}</span> @enderror
                                </div>

                                {{-- Rol --}}
                                <div>
                                    <label for="role" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                                        Rol *
                                    </label>
                                    <select id="role" wire:model.live="role"
                                            class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400">
                                        <option value="">Seleccione un rol</option>
                                        <option value="admin">Administrador</option>
                                        <option value="coordinador">Coordinador</option>
                                        <option value="jefe_piso">Jefe de Piso</option>
                                        <option value="enfermero">Enfermero</option>
                                        <option value="jefe_capacitacion">Jefe de Capacitación</option>
                                    </select>
                                    @error('role') <span class="text-red-600 dark:text-red-400 text-sm">{{ $message }}</span> @enderror
                                </div>

                                {{-- Estado Activo --}}
                                <div class="flex items-center">
                                    <label class="flex items-center cursor-pointer">
                                        <input type="checkbox" wire:model="is_active"
                                               class="w-4 h-4 text-blue-600 border-slate-300 dark:border-slate-600 rounded focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400">
                                        <span class="ml-2 text-sm font-medium text-slate-700 dark:text-slate-300">Usuario Activo</span>
                                    </label>
                                </div>
                            </div>

                            {{-- Campos específicos para enfermeros --}}
                            @if ($role === 'enfermero')
                                <div class="border-t border-slate-300 dark:border-slate-600 pt-4 mt-4">
                                    <h4 class="text-md font-medium text-slate-900 dark:text-slate-100 mb-3">
                                        Datos de Enfermero
                                    </h4>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        {{-- Cédula Profesional --}}
                                        <div>
                                            <label for="cedula" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                                                Cédula Profesional *
                                            </label>
                                            <input type="text" id="cedula" wire:model="cedula_profesional"
                                                   class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400">
                                            @error('cedula_profesional') <span class="text-red-600 dark:text-red-400 text-sm">{{ $message }}</span> @enderror
                                        </div>

                                        {{-- Tipo de Asignación --}}
                                        <div>
                                            <label for="tipo_asignacion" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                                                Tipo de Asignación *
                                            </label>
                                            <select id="tipo_asignacion" wire:model.live="tipo_asignacion"
                                                    class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400">
                                                <option value="fijo">Fijo</option>
                                                <option value="rotativo">Rotativo</option>
                                            </select>
                                            @error('tipo_asignacion') <span class="text-red-600 dark:text-red-400 text-sm">{{ $message }}</span> @enderror
                                        </div>

                                        {{-- Área Fija (solo si es fijo) --}}
                                        @if ($tipo_asignacion === 'fijo')
                                            <div>
                                                <label for="area_fija" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                                                    Área Fija *
                                                </label>
                                                <select id="area_fija" wire:model="area_fija_id"
                                                        class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400">
                                                    <option value="">Seleccione un área</option>
                                                    @foreach ($areas as $area)
                                                        <option value="{{ $area->id }}">{{ $area->nombre }}</option>
                                                    @endforeach
                                                </select>
                                                @error('area_fija_id') <span class="text-red-600 dark:text-red-400 text-sm">{{ $message }}</span> @enderror
                                            </div>
                                        @endif

                                        {{-- Años de Experiencia --}}
                                        <div>
                                            <label for="experiencia" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                                                Años de Experiencia
                                            </label>
                                            <input type="number" id="experiencia" wire:model="anos_experiencia" min="0" max="50"
                                                   class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400">
                                            @error('anos_experiencia') <span class="text-red-600 dark:text-red-400 text-sm">{{ $message }}</span> @enderror
                                        </div>

                                        {{-- Especialidades --}}
                                        <div class="md:col-span-2">
                                            <label for="especialidades" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                                                Especialidades
                                            </label>
                                            <textarea id="especialidades" wire:model="especialidades" rows="2"
                                                      class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400"></textarea>
                                            @error('especialidades') <span class="text-red-600 dark:text-red-400 text-sm">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                </div>
                            @endif

                            {{-- Botones --}}
                            <div class="flex gap-2 mt-6">
                                <button type="submit"
                                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                                    {{ $editingUserId ? 'Actualizar' : 'Crear' }} Usuario
                                </button>
                                <button type="button" wire:click="cancelEdit"
                                        class="px-4 py-2 bg-slate-300 hover:bg-slate-400 dark:bg-slate-600 dark:hover:bg-slate-500 text-slate-700 dark:text-slate-100 rounded-lg transition">
                                    Cancelar
                                </button>
                            </div>
                        </form>
                    </div>
                @endif

                {{-- Tabla de usuarios --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                        <thead class="bg-slate-50 dark:bg-slate-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Nombre</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Rol</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Estado</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Datos Enfermero</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-slate-800 divide-y divide-slate-200 dark:divide-slate-700">
                            @forelse ($users as $user)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900 dark:text-slate-100">
                                        {{ $user->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600 dark:text-slate-400">
                                        {{ $user->email }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full
                                            @if($user->role->value === 'admin') bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300
                                            @elseif($user->role->value === 'coordinador') bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-300
                                            @elseif($user->role->value === 'jefe_piso') bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300
                                            @elseif($user->role->value === 'enfermero') bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300
                                            @else bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300
                                            @endif">
                                            {{ $user->role->label() }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <button wire:click="toggleActive({{ $user->id }})"
                                                class="px-2 py-1 text-xs font-semibold rounded-full transition
                                                    {{ $user->is_active
                                                        ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 hover:bg-green-200 dark:hover:bg-green-900/50'
                                                        : 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300 hover:bg-red-200 dark:hover:bg-red-900/50' }}">
                                            {{ $user->is_active ? 'Activo' : 'Inactivo' }}
                                        </button>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-400">
                                        @if($user->enfermero)
                                            <div class="space-y-1">
                                                <div><span class="font-medium">Cédula:</span> {{ $user->enfermero->cedula_profesional }}</div>
                                                <div><span class="font-medium">Tipo:</span> {{ $user->enfermero->tipo_asignacion->label() }}</div>
                                                @if($user->enfermero->areaFija)
                                                    <div><span class="font-medium">Área:</span> {{ $user->enfermero->areaFija->nombre }}</div>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-slate-400 dark:text-slate-600">N/A</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <button wire:click="edit({{ $user->id }})"
                                                class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300 mr-3">
                                            Editar
                                        </button>
                                        <button wire:click="delete({{ $user->id }})"
                                                wire:confirm="¿Está seguro de eliminar este usuario?"
                                                class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300">
                                            Eliminar
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-sm text-slate-500 dark:text-slate-400">
                                        No hay usuarios registrados.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Paginación --}}
                <div class="mt-4">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</div>