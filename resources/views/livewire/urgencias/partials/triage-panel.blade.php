<div class="mt-6 p-4 bg-slate-50 dark:bg-slate-900/50 rounded-md border border-slate-200 dark:border-slate-700">
    <h3 class="text-sm font-medium text-slate-900 dark:text-slate-100 mb-3">Nivel de TRIAGE Sugerido</h3>
    <div class="flex items-center gap-4">
        <div class="flex-shrink-0">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-{{ $nivelTriageSugerido->getColor() }}-100 text-{{ $nivelTriageSugerido->getColor() }}-800 dark:bg-{{ $nivelTriageSugerido->getColor() }}-900/50 dark:text-{{ $nivelTriageSugerido->getColor() }}-200">
                {{ $nivelTriageSugerido->getLabel() }}
            </span>
        </div>
        <div class="text-sm text-slate-600 dark:text-slate-400">
            Tiempo de espera: {{ $nivelTriageSugerido->getTiempoEspera() }}
        </div>
    </div>

    <div class="mt-4">
        <p class="text-xs text-slate-600 dark:text-slate-400 mb-2">Override manual:</p>
        <div class="flex gap-2 flex-wrap">
            @foreach(['rojo', 'naranja', 'amarillo', 'verde', 'azul'] as $nivel)
                <button type="button" wire:click="overrideTriage('{{ $nivel }}')"
                    class="px-3 py-1 text-xs rounded-full border-2 transition-colors
                    {{ $nivelTriageOverride === $nivel
                        ? 'border-' . $nivel . '-500 bg-' . $nivel . '-50 dark:bg-' . $nivel . '-900/50 font-semibold'
                        : 'border-slate-300 dark:border-slate-600 hover:border-' . $nivel . '-300 dark:hover:border-' . $nivel . '-700' }}">
                    {{ ucfirst($nivel) }}
                </button>
            @endforeach
        </div>
        @if($nivelTriageOverride)
            <p class="text-xs text-amber-600 dark:text-amber-400 mt-2 flex items-center gap-1">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                TRIAGE modificado manualmente a: <strong>{{ ucfirst($nivelTriageOverride) }}</strong>
            </p>
        @endif
    </div>
</div>
