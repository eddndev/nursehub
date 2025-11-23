<div class="p-6">
    {{-- Header --}}
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-slate-100">Lista de Pacientes</h1>
            <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">Gestión y seguimiento de pacientes activos</p>
        </div>
        <div>
            <a href="{{ route('urgencias.admision') }}"
                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors">
                <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Admitir Paciente
            </a>
        </div>
    </div>

    {{-- Estadísticas --}}
    @include('livewire.enfermeria.partials.estadisticas-pacientes', ['stats' => $stats])

    {{-- Filtros de búsqueda --}}
    @include('livewire.enfermeria.partials.filtros-busqueda', [
        'nivelesTriage' => $nivelesTriage,
        'estadosPaciente' => $estadosPaciente
    ])

    {{-- Tabla de pacientes --}}
    @include('livewire.enfermeria.partials.tabla-pacientes', ['pacientes' => $pacientes])
</div>
