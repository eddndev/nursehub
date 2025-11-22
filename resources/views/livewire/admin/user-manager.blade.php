<div class="p-6">
    {{-- Header --}}
    <div class="mb-6 flex items-center justify-between">
        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100">Gestión de Usuarios</h2>
        <button
            wire:click="showCreateForm"
            class="px-4 py-2 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors dark:bg-blue-600 dark:hover:bg-blue-700"
        >
            {{ $showForm ? 'Cancelar' : '+ Nuevo Usuario' }}
        </button>
    </div>

    {{-- Mensajes flash --}}
    @if (session()->has('message'))
        <div class="mb-4 bg-green-50 border-l-4 border-green-500 p-4 dark:bg-green-900/20 dark:border-green-500">
            <p class="text-green-800 dark:text-green-400">{{ session('message') }}</p>
        </div>
    @endif

    {{-- Formulario --}}
    @if ($showForm)
        <div class="mb-6 bg-white dark:bg-slate-900 rounded-lg border border-slate-200 dark:border-slate-700 p-6">
            <h3 class="text-lg font-semibold mb-4 text-slate-900 dark:text-slate-100">
                {{ $editingUserId ? 'Editar Usuario' : 'Nuevo Usuario' }}
            </h3>

            <form wire:submit.prevent="{{ $editingUserId ? 'update' : 'create' }}" class="space-y-4">
                {{-- Datos de Usuario --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Nombre --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                            Nombre Completo *
                        </label>
                        <input
                            type="text"
                            wire:model="name"
                            placeholder="Ej: Juan Pérez López"
                            class="w-full px-4 py-2 border border-slate-300 rounded-md text-slate-900 placeholder:text-slate-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-900 dark:border-slate-600 dark:text-slate-100 dark:placeholder:text-slate-500 transition-all"
                        >
                        @error('name')
                            <span class="text-red-600 text-xs">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                            Correo Electrónico *
                        </label>
                        <input
                            type="email"
                            wire:model="email"
                            placeholder="Ej: juan@nursehub.com"
                            class="w-full px-4 py-2 border border-slate-300 rounded-md text-slate-900 placeholder:text-slate-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-900 dark:border-slate-600 dark:text-slate-100 dark:placeholder:text-slate-500 transition-all"
                        >
                        @error('email')
                            <span class="text-red-600 text-xs">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                            Contraseña {{ $editingUserId ? '(dejar en blanco para mantener)' : '*' }}
                        </label>
                        <input
                            type="password"
                            wire:model="password"
                            placeholder="{{ $editingUserId ? 'Dejar en blanco para no cambiar' : 'Mínimo 8 caracteres' }}"
                            class="w-full px-4 py-2 border border-slate-300 rounded-md text-slate-900 placeholder:text-slate-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-900 dark:border-slate-600 dark:text-slate-100 dark:placeholder:text-slate-500 transition-all"
                        >
                        @error('password')
                            <span class="text-red-600 text-xs">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Rol --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                            Rol del Sistema *
                        </label>
                        <select
                            wire:model.live="role"
                            class="w-full px-4 py-2 border border-slate-300 rounded-md text-slate-900 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-900 dark:border-slate-600 dark:text-slate-100 transition-all"
                        >
                            <option value="">Seleccione un rol</option>
                            <option value="admin">Administrador</option>
                            <option value="coordinador">Coordinador General</option>
                            <option value="jefe_piso">Jefe de Piso/Área</option>
                            <option value="enfermero">Enfermero</option>
                            <option value="jefe_capacitacion">Jefe de Capacitación</option>
                        </select>
                        @error('role')
                            <span class="text-red-600 text-xs">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Estado Activo --}}
                    <div class="md:col-span-2">
                        <div class="flex items-center">
                            <input
                                wire:model="is_active"
                                id="is_active"
                                type="checkbox"
                                class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500 dark:border-slate-600 dark:bg-slate-900"
                            >
                            <label for="is_active" class="ml-2 text-sm text-slate-700 dark:text-slate-300">
                                Usuario Activo (puede iniciar sesión en el sistema)
                            </label>
                        </div>
                    </div>
                </div>

                {{-- Campos específicos para enfermeros --}}
                @if ($role === 'enfermero')
                    <div class="border-t border-slate-300 dark:border-slate-600 pt-4 mt-4">
                        <h4 class="text-md font-semibold text-slate-900 dark:text-slate-100 mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Datos Profesionales de Enfermería
                        </h4>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- Cédula Profesional --}}
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                                    Cédula Profesional *
                                </label>
                                <input
                                    type="text"
                                    wire:model="cedula_profesional"
                                    placeholder="Ej: 12345678"
                                    maxlength="50"
                                    class="w-full px-4 py-2 border border-slate-300 rounded-md text-slate-900 placeholder:text-slate-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-900 dark:border-slate-600 dark:text-slate-100 dark:placeholder:text-slate-500 transition-all"
                                >
                                @error('cedula_profesional')
                                    <span class="text-red-600 text-xs">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Tipo de Asignación --}}
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                                    Tipo de Asignación *
                                </label>
                                <select
                                    wire:model.live="tipo_asignacion"
                                    class="w-full px-4 py-2 border border-slate-300 rounded-md text-slate-900 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-900 dark:border-slate-600 dark:text-slate-100 transition-all"
                                >
                                    <option value="fijo">Fijo - Asignado permanentemente a un área</option>
                                    <option value="rotativo">Rotativo - Rota entre diferentes áreas</option>
                                </select>
                                @error('tipo_asignacion')
                                    <span class="text-red-600 text-xs">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Área Fija (solo si es fijo) --}}
                            @if ($tipo_asignacion === 'fijo')
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                                        Área Fija de Asignación *
                                    </label>
                                    <select
                                        wire:model="area_fija_id"
                                        class="w-full px-4 py-2 border border-slate-300 rounded-md text-slate-900 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-900 dark:border-slate-600 dark:text-slate-100 transition-all"
                                    >
                                        <option value="">Seleccione un área</option>
                                        @foreach ($areas as $area)
                                            <option value="{{ $area->id }}">{{ $area->nombre }} ({{ $area->codigo }})</option>
                                        @endforeach
                                    </select>
                                    @error('area_fija_id')
                                        <span class="text-red-600 text-xs">{{ $message }}</span>
                                    @enderror
                                </div>
                            @endif

                            {{-- Años de Experiencia --}}
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                                    Años de Experiencia
                                </label>
                                <input
                                    type="number"
                                    wire:model="anos_experiencia"
                                    min="0"
                                    max="50"
                                    placeholder="0"
                                    class="w-full px-4 py-2 border border-slate-300 rounded-md text-slate-900 placeholder:text-slate-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-900 dark:border-slate-600 dark:text-slate-100 dark:placeholder:text-slate-500 transition-all"
                                >
                                @error('anos_experiencia')
                                    <span class="text-red-600 text-xs">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Especialidades --}}
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                                    Especialidades y Certificaciones
                                </label>
                                <textarea
                                    wire:model="especialidades"
                                    rows="2"
                                    placeholder="Ej: Pediatría, Manejo de UCI, Reanimación Cardiopulmonar..."
                                    class="w-full px-4 py-2 border border-slate-300 rounded-md text-slate-900 placeholder:text-slate-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-900 dark:border-slate-600 dark:text-slate-100 dark:placeholder:text-slate-500 transition-all"
                                ></textarea>
                                @error('especialidades')
                                    <span class="text-red-600 text-xs">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Botones --}}
                <div class="flex gap-3 mt-6">
                    <button
                        type="submit"
                        class="px-4 py-2 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 transition-colors"
                    >
                        {{ $editingUserId ? 'Actualizar' : 'Crear' }} Usuario
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

    {{-- Filtros y Búsqueda --}}
    @if (!$showForm)
        <div class="mb-4 bg-white dark:bg-slate-900 rounded-lg border border-slate-200 dark:border-slate-700 p-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                {{-- Búsqueda --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                        Buscar por nombre o email
                    </label>
                    <input
                        type="text"
                        wire:model.live.debounce.300ms="search"
                        placeholder="Escriba para buscar..."
                        class="w-full px-4 py-2 border border-slate-300 rounded-md text-slate-900 placeholder:text-slate-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-900 dark:border-slate-600 dark:text-slate-100 dark:placeholder:text-slate-500 transition-all"
                    >
                </div>

                {{-- Filtro por Rol --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                        Filtrar por Rol
                    </label>
                    <select
                        wire:model.live="filterRole"
                        class="w-full px-4 py-2 border border-slate-300 rounded-md text-slate-900 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-900 dark:border-slate-600 dark:text-slate-100 transition-all"
                    >
                        <option value="">Todos los roles</option>
                        <option value="admin">Administrador</option>
                        <option value="coordinador">Coordinador</option>
                        <option value="jefe_piso">Jefe de Piso</option>
                        <option value="enfermero">Enfermero</option>
                        <option value="jefe_capacitacion">Jefe de Capacitación</option>
                    </select>
                </div>

                {{-- Filtro por Estado --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                        Filtrar por Estado
                    </label>
                    <select
                        wire:model.live="filterStatus"
                        class="w-full px-4 py-2 border border-slate-300 rounded-md text-slate-900 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-900 dark:border-slate-600 dark:text-slate-100 transition-all"
                    >
                        <option value="">Todos</option>
                        <option value="active">Activos</option>
                        <option value="inactive">Inactivos</option>
                    </select>
                </div>
            </div>

            {{-- Limpiar Filtros --}}
            @if ($search || $filterRole || $filterStatus)
                <div class="mt-3">
                    <button
                        wire:click="clearFilters"
                        class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300"
                    >
                        ✕ Limpiar filtros
                    </button>
                </div>
            @endif
        </div>
    @endif

    {{-- Tabla de Usuarios --}}
    <div class="bg-white dark:bg-slate-900 rounded-lg border border-slate-200 dark:border-slate-700 overflow-hidden">
        <table class="w-full">
            <thead class="bg-slate-50 dark:bg-slate-800">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Usuario</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Rol</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Estado</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Datos de Enfermería</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                @forelse($users as $user)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                        {{-- Usuario --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-semibold text-slate-900 dark:text-slate-100">
                                {{ $user->name }}
                            </span>
                        </td>

                        {{-- Email --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-slate-700 dark:text-slate-300">
                                {{ $user->email }}
                            </span>
                        </td>

                        {{-- Rol --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($user->role->value === 'admin') bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400
                                @elseif($user->role->value === 'coordinador') bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400
                                @elseif($user->role->value === 'jefe_piso') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400
                                @elseif($user->role->value === 'enfermero') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400
                                @else bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400
                                @endif">
                                {{ $user->role->label() }}
                            </span>
                        </td>

                        {{-- Estado --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <button
                                wire:click="toggleActive({{ $user->id }})"
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium transition-colors
                                    {{ $user->is_active
                                        ? 'bg-green-100 text-green-800 hover:bg-green-200 dark:bg-green-900/30 dark:text-green-400 dark:hover:bg-green-900/50'
                                        : 'bg-red-100 text-red-800 hover:bg-red-200 dark:bg-red-900/30 dark:text-red-400 dark:hover:bg-red-900/50' }}"
                            >
                                {{ $user->is_active ? 'Activo' : 'Inactivo' }}
                            </button>
                        </td>

                        {{-- Datos de Enfermería --}}
                        <td class="px-6 py-4">
                            @if($user->enfermero)
                                <div class="text-xs space-y-1">
                                    <div>
                                        <span class="font-medium text-slate-700 dark:text-slate-400">Cédula:</span>
                                        <span class="text-slate-600 dark:text-slate-300">{{ $user->enfermero->cedula_profesional }}</span>
                                    </div>
                                    <div>
                                        <span class="font-medium text-slate-700 dark:text-slate-400">Tipo:</span>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                            {{ $user->enfermero->tipo_asignacion->value === 'fijo'
                                                ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400'
                                                : 'bg-cyan-100 text-cyan-800 dark:bg-cyan-900/30 dark:text-cyan-400' }}">
                                            {{ $user->enfermero->tipo_asignacion->label() }}
                                        </span>
                                    </div>
                                    @if($user->enfermero->areaFija)
                                        <div>
                                            <span class="font-medium text-slate-700 dark:text-slate-400">Área Fija:</span>
                                            <span class="text-slate-600 dark:text-slate-300">{{ $user->enfermero->areaFija->nombre }}</span>
                                        </div>
                                    @endif
                                    <div>
                                        <span class="font-medium text-slate-700 dark:text-slate-400">Experiencia:</span>
                                        <span class="text-slate-600 dark:text-slate-300">{{ $user->enfermero->anos_experiencia }} años</span>
                                    </div>
                                </div>
                            @else
                                <span class="text-xs text-slate-400 dark:text-slate-600">N/A</span>
                            @endif
                        </td>

                        {{-- Acciones --}}
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                            <button
                                wire:click="edit({{ $user->id }})"
                                class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300"
                            >
                                Editar
                            </button>
                            <button
                                wire:click="delete({{ $user->id }})"
                                wire:confirm="¿Está seguro de eliminar este usuario? {{ $user->enfermero ? 'Se eliminará también su perfil de enfermero.' : '' }}"
                                class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
                            >
                                Eliminar
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-slate-500 dark:text-slate-400">
                            @if ($search || $filterRole || $filterStatus)
                                <div class="text-sm">
                                    <p class="font-medium">No se encontraron resultados</p>
                                    <p class="text-xs mt-1">Intente ajustar sus filtros de búsqueda</p>
                                </div>
                            @else
                                <div class="text-sm">
                                    <p class="font-medium">No hay usuarios registrados</p>
                                    <p class="text-xs mt-1">Crea el primer usuario para comenzar</p>
                                </div>
                            @endif
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
