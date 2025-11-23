<div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-6 mb-6">
    <div class="flex items-start">
        <div class="flex-shrink-0">
            <svg class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <div class="ml-3 flex-1">
            <h3 class="text-sm font-medium text-green-800 dark:text-green-200">Paciente admitido exitosamente</h3>
            <div class="mt-2 text-sm text-green-700 dark:text-green-300">
                <p><strong>Nombre:</strong> {{ $pacienteAdmitido->nombre_completo }}</p>
                <p><strong>Código QR:</strong> <code class="bg-white dark:bg-slate-800 px-2 py-1 rounded">{{ $codigoQr }}</code></p>
                @if($pacienteAdmitido->camaActual)
                    <p><strong>Cama asignada:</strong> {{ $pacienteAdmitido->camaActual->numero }}</p>
                @endif
            </div>
            <div class="mt-4 flex gap-3">
                <button wire:click="nuevaAdmision" type="button"
                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-green-700 bg-green-100 hover:bg-green-200 dark:text-green-200 dark:bg-green-800 dark:hover:bg-green-700">
                    Admitir otro paciente
                </button>
                <a href="{{ route('enfermeria.expediente', $pacienteAdmitido->id) }}"
                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 dark:text-blue-200 dark:bg-blue-800 dark:hover:bg-blue-700">
                    Ver expediente
                </a>
            </div>
        </div>
    </div>
</div>
