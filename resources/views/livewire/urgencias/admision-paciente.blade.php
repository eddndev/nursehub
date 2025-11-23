<div class="p-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-900 dark:text-slate-100">Admisión de Paciente - Urgencias</h1>
        <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">Registrar un nuevo paciente en el servicio de urgencias</p>
    </div>

    @if ($mensaje_exito && $paciente_admitido)
        @include('livewire.urgencias.partials.mensaje-exito', [
            'pacienteAdmitido' => $paciente_admitido,
            'codigoQr' => $codigo_qr
        ])
    @else
        <form wire:submit="admitir">
            @include('livewire.urgencias.partials.datos-demograficos', [
                'camasDisponibles' => $camasDisponibles
            ])

            @include('livewire.urgencias.partials.signos-vitales', [
                'nivelTriageSugerido' => $nivel_triage_sugerido,
                'nivelTriageOverride' => $nivel_triage_override
            ])

            <div class="flex justify-end gap-3">
                <a href="{{ route('dashboard') }}"
                    class="px-4 py-2 text-sm font-medium text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-md hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                    Cancelar
                </a>
                <button type="submit"
                    class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md transition-colors">
                    Admitir Paciente
                </button>
            </div>
        </form>
    @endif
</div>
