<div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700 p-6 mb-6">
    <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-4">Datos Demográficos</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <div>
            <label for="nombre" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                Nombre <span class="text-red-500">*</span>
            </label>
            <input type="text" id="nombre" wire:model="nombre"
                class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-blue-500"
                placeholder="Ej: Juan">
            @error('nombre') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="apellido_paterno" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                Apellido Paterno <span class="text-red-500">*</span>
            </label>
            <input type="text" id="apellido_paterno" wire:model="apellido_paterno"
                class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-blue-500"
                placeholder="Ej: Pérez">
            @error('apellido_paterno') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="apellido_materno" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                Apellido Materno
            </label>
            <input type="text" id="apellido_materno" wire:model="apellido_materno"
                class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-blue-500"
                placeholder="Ej: García">
            @error('apellido_materno') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="sexo" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                Sexo <span class="text-red-500">*</span>
            </label>
            <select id="sexo" wire:model="sexo"
                class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-blue-500">
                <option value="">Seleccionar...</option>
                <option value="M">Masculino</option>
                <option value="F">Femenino</option>
                <option value="Otro">Otro</option>
            </select>
            @error('sexo') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="fecha_nacimiento" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                Fecha de Nacimiento <span class="text-red-500">*</span>
            </label>
            <input type="date" id="fecha_nacimiento" wire:model="fecha_nacimiento"
                class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-blue-500">
            @error('fecha_nacimiento') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="curp" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                CURP
            </label>
            <input type="text" id="curp" wire:model="curp" maxlength="18"
                class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 uppercase"
                placeholder="18 caracteres">
            @error('curp') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="telefono" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                Teléfono
            </label>
            <input type="tel" id="telefono" wire:model="telefono"
                class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-blue-500"
                placeholder="10 dígitos">
            @error('telefono') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="contacto_emergencia_nombre" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                Contacto de Emergencia
            </label>
            <input type="text" id="contacto_emergencia_nombre" wire:model="contacto_emergencia_nombre"
                class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-blue-500"
                placeholder="Nombre completo">
            @error('contacto_emergencia_nombre') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="contacto_emergencia_telefono" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                Teléfono de Emergencia
            </label>
            <input type="tel" id="contacto_emergencia_telefono" wire:model="contacto_emergencia_telefono"
                class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-blue-500"
                placeholder="10 dígitos">
            @error('contacto_emergencia_telefono') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
        <div>
            <label for="alergias" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                Alergias
            </label>
            <textarea id="alergias" wire:model="alergias" rows="2"
                class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-blue-500"
                placeholder="Listar alergias conocidas o escribir 'Ninguna'"></textarea>
            @error('alergias') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="antecedentes_medicos" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                Antecedentes Médicos
            </label>
            <textarea id="antecedentes_medicos" wire:model="antecedentes_medicos" rows="2"
                class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-blue-500"
                placeholder="Condiciones médicas, cirugías previas, etc."></textarea>
            @error('antecedentes_medicos') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>
    </div>

    <div class="mt-4">
        <label for="cama_id" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
            Asignar Cama (Opcional)
        </label>
        <select id="cama_id" wire:model="cama_id"
            class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-blue-500">
            <option value="">Sin asignación de cama</option>
            @foreach($camasDisponibles as $cama)
                <option value="{{ $cama->id }}">
                    {{ $cama->cuarto->piso->area->nombre }} - Piso {{ $cama->cuarto->piso->nombre }} - Cuarto {{ $cama->cuarto->numero }} - Cama {{ $cama->numero }}
                </option>
            @endforeach
        </select>
        @error('cama_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
    </div>
</div>
