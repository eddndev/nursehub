<div>
    {{-- Botón para abrir modal (se puede incluir desde otros componentes) --}}
    <button type="button" wire:click="abrirModal"
        class="inline-flex items-center px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors">
        <svg class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Registrar Signos
    </button>

    {{-- Modal --}}
    @if($modalOpen)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            {{-- Overlay --}}
            <div class="fixed inset-0 bg-slate-900/75 transition-opacity" wire:click="cerrarModal"></div>

            {{-- Modal panel --}}
            <div class="inline-block align-bottom bg-white dark:bg-slate-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                {{-- Header --}}
                <div class="bg-white dark:bg-slate-800 px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">
                                Registrar Signos Vitales
                            </h3>
                            <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">
                                {{ $paciente->nombre_completo }} • {{ $paciente->codigo_qr }}
                            </p>
                        </div>
                        <button type="button" wire:click="cerrarModal"
                            class="text-slate-400 hover:text-slate-500 dark:hover:text-slate-300">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Body --}}
                <div class="bg-white dark:bg-slate-800 px-6 py-4">
                    <form wire:submit.prevent="guardarRegistro">
                        {{-- Error general --}}
                        @error('general')
                        <div class="mb-4 p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                            <p class="text-sm text-red-700 dark:text-red-300">{{ $message }}</p>
                        </div>
                        @enderror

                        {{-- Signos Vitales --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                            {{-- Presión Arterial --}}
                            <div class="col-span-1">
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                    <div class="flex items-center gap-2">
                                        <svg class="h-4 w-4 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                        </svg>
                                        Presión Arterial
                                    </div>
                                </label>
                                <div class="flex items-center gap-2">
                                    <div class="flex-1">
                                        <input type="number" step="1" wire:model.live.debounce.300ms="presion_arterial_sistolica"
                                            placeholder="Sistólica"
                                            class="block w-full rounded-md border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                    </div>
                                    <span class="text-slate-500 dark:text-slate-400">/</span>
                                    <div class="flex-1">
                                        <input type="number" step="1" wire:model.live.debounce.300ms="presion_arterial_diastolica"
                                            placeholder="Diastólica"
                                            class="block w-full rounded-md border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                    </div>
                                </div>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">mmHg</p>
                                @error('presion_arterial_sistolica')
                                <p class="text-xs text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                                @enderror
                                @error('presion_arterial_diastolica')
                                <p class="text-xs text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Frecuencia Cardíaca --}}
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                    <div class="flex items-center gap-2">
                                        <svg class="h-4 w-4 text-pink-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                        </svg>
                                        Frecuencia Cardíaca
                                    </div>
                                </label>
                                <input type="number" step="1" wire:model.live.debounce.300ms="frecuencia_cardiaca"
                                    placeholder="Ej: 72"
                                    class="block w-full rounded-md border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">latidos por minuto</p>
                                @error('frecuencia_cardiaca')
                                <p class="text-xs text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Frecuencia Respiratoria --}}
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                    <div class="flex items-center gap-2">
                                        <svg class="h-4 w-4 text-cyan-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2 1m2-1l-2-1m2 1v2.5M14 4l-2-1-2 1M4 7l2-1M4 7l2 1M4 7v2.5M12 21l-2-1m2 1l2-1m-2 1v-2.5M6 18l-2-1v-2.5M18 18l2-1v-2.5" />
                                        </svg>
                                        Frecuencia Respiratoria
                                    </div>
                                </label>
                                <input type="number" step="1" wire:model.live.debounce.300ms="frecuencia_respiratoria"
                                    placeholder="Ej: 16"
                                    class="block w-full rounded-md border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">respiraciones por minuto</p>
                                @error('frecuencia_respiratoria')
                                <p class="text-xs text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Temperatura --}}
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                    <div class="flex items-center gap-2">
                                        <svg class="h-4 w-4 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                        </svg>
                                        Temperatura
                                    </div>
                                </label>
                                <input type="number" step="0.1" wire:model.live.debounce.300ms="temperatura"
                                    placeholder="Ej: 36.5"
                                    class="block w-full rounded-md border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">grados Celsius</p>
                                @error('temperatura')
                                <p class="text-xs text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Saturación de Oxígeno --}}
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                    <div class="flex items-center gap-2">
                                        <svg class="h-4 w-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z" />
                                        </svg>
                                        Saturación de Oxígeno
                                    </div>
                                </label>
                                <input type="number" step="1" wire:model.live.debounce.300ms="saturacion_oxigeno"
                                    placeholder="Ej: 98"
                                    class="block w-full rounded-md border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">porcentaje (SpO2)</p>
                                @error('saturacion_oxigeno')
                                <p class="text-xs text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Glucosa --}}
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                    <div class="flex items-center gap-2">
                                        <svg class="h-4 w-4 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                        </svg>
                                        Glucosa
                                    </div>
                                </label>
                                <input type="number" step="1" wire:model.live="glucosa"
                                    placeholder="Ej: 95"
                                    class="block w-full rounded-md border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">mg/dL</p>
                                @error('glucosa')
                                <p class="text-xs text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- TRIAGE Section --}}
                        <div class="mb-6 p-4 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-lg">
                            <div class="flex items-center justify-between mb-3">
                                <h4 class="text-sm font-semibold text-slate-900 dark:text-slate-100">Nivel TRIAGE</h4>
                                @if($triage_calculado && $triage_override)
                                <button type="button" wire:click="usarTriageCalculado"
                                    class="text-xs text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300">
                                    Usar calculado
                                </button>
                                @endif
                            </div>

                            @if($triage_calculado)
                            <div class="mb-3 p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                                <p class="text-xs font-medium text-blue-800 dark:text-blue-200 mb-1">TRIAGE Calculado Automáticamente</p>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $triage_calculado->getColor() }}-100 text-{{ $triage_calculado->getColor() }}-800 dark:bg-{{ $triage_calculado->getColor() }}-900/50 dark:text-{{ $triage_calculado->getColor() }}-200">
                                    {{ $triage_calculado->getLabel() }}
                                </span>
                            </div>
                            @endif

                            <div class="flex flex-wrap gap-2">
                                @foreach($nivelesTriage as $nivel)
                                <button type="button" wire:click="overrideTriage('{{ $nivel->value }}')"
                                    class="px-3 py-2 rounded-md text-sm font-medium transition-all
                                        {{ $nivel_triage === $nivel->value
                                            ? 'bg-' . $nivel->getColor() . '-600 text-white ring-2 ring-' . $nivel->getColor() . '-600 ring-offset-2'
                                            : 'bg-' . $nivel->getColor() . '-100 text-' . $nivel->getColor() . '-800 hover:bg-' . $nivel->getColor() . '-200 dark:bg-' . $nivel->getColor() . '-900/50 dark:text-' . $nivel->getColor() . '-200 dark:hover:bg-' . $nivel->getColor() . '-900/75'
                                        }}">
                                    {{ $nivel->getLabel() }}
                                </button>
                                @endforeach
                            </div>

                            @if($triage_override)
                            <p class="text-xs text-amber-600 dark:text-amber-400 mt-2">⚠️ TRIAGE modificado manualmente</p>
                            @endif
                        </div>

                        {{-- Observaciones --}}
                        <div class="mb-6">
                            <label for="observaciones" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Observaciones
                            </label>
                            <textarea id="observaciones" wire:model="observaciones" rows="3"
                                placeholder="Notas adicionales sobre el registro..."
                                class="block w-full rounded-md border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"></textarea>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Máximo 500 caracteres</p>
                            @error('observaciones')
                            <p class="text-xs text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Footer Actions --}}
                        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-200 dark:border-slate-700">
                            <button type="button" wire:click="cerrarModal"
                                class="px-4 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 text-sm font-medium rounded-md hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                                Cancelar
                            </button>
                            <button type="submit"
                                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors">
                                Guardar Registro
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
