<div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700 p-6 mb-6">
    <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-4">Signos Vitales Iniciales (Opcional)</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div>
            <label for="presion_arterial_sistolica" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                P/A Sistólica (mmHg)
            </label>
            <input type="number" step="0.01" id="presion_arterial_sistolica" wire:model.live="presion_arterial_sistolica"
                class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-blue-500"
                placeholder="120">
            @error('presion_arterial_sistolica') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="presion_arterial_diastolica" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                P/A Diastólica (mmHg)
            </label>
            <input type="number" step="0.01" id="presion_arterial_diastolica" wire:model.live="presion_arterial_diastolica"
                class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-blue-500"
                placeholder="80">
            @error('presion_arterial_diastolica') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="frecuencia_cardiaca" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                FC (lpm)
            </label>
            <input type="number" id="frecuencia_cardiaca" wire:model.live="frecuencia_cardiaca"
                class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-blue-500"
                placeholder="70">
            @error('frecuencia_cardiaca') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="frecuencia_respiratoria" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                FR (rpm)
            </label>
            <input type="number" id="frecuencia_respiratoria" wire:model.live="frecuencia_respiratoria"
                class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-blue-500"
                placeholder="16">
            @error('frecuencia_respiratoria') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="temperatura" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                Temperatura (°C)
            </label>
            <input type="number" step="0.01" id="temperatura" wire:model.live="temperatura"
                class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-blue-500"
                placeholder="36.5">
            @error('temperatura') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="saturacion_oxigeno" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                SpO2 (%)
            </label>
            <input type="number" step="0.01" id="saturacion_oxigeno" wire:model.live="saturacion_oxigeno"
                class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-blue-500"
                placeholder="98">
            @error('saturacion_oxigeno') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="glucosa" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                Glucosa (mg/dL)
            </label>
            <input type="number" step="0.01" id="glucosa" wire:model="glucosa"
                class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-blue-500"
                placeholder="100">
            @error('glucosa') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>
    </div>

    @if($nivelTriageSugerido)
        @include('livewire.urgencias.partials.triage-panel')
    @endif

    <div class="mt-4">
        <label for="observaciones_iniciales" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
            Observaciones
        </label>
        <textarea id="observaciones_iniciales" wire:model="observaciones_iniciales" rows="2"
            class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-blue-500"
            placeholder="Detalles adicionales sobre el estado del paciente"></textarea>
        @error('observaciones_iniciales') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
    </div>
</div>
