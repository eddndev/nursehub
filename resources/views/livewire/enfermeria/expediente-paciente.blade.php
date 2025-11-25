<div class="p-6">
    {{-- Header del Paciente --}}
    @include('livewire.enfermeria.partials.expediente-header')

    {{-- Información Básica del Paciente --}}
    <div class="mb-6">
        @include('livewire.enfermeria.partials.expediente-info-basica')
    </div>

    {{-- Tabs Navigation --}}
    <div class="mb-6 border-b border-gray-200">
        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
            <button wire:click="$set('activeTab', 'general')" 
                class="{{ $activeTab === 'general' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                General / Signos Vitales
            </button>
            <button wire:click="$set('activeTab', 'liquidos')" 
                class="{{ $activeTab === 'liquidos' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                Control de Líquidos
            </button>
            <button wire:click="$set('activeTab', 'escalas')" 
                class="{{ $activeTab === 'escalas' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                Escalas de Valoración
            </button>
            <button wire:click="$set('activeTab', 'planes')" 
                class="{{ $activeTab === 'planes' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                Planes de Cuidado
            </button>
        </nav>
    </div>

    {{-- Tab Content --}}
    <div>
        @if($activeTab === 'general')
            {{-- Signos Vitales Recientes --}}
            @php
                $ultimoRegistro = $paciente->registrosSignosVitales->first();
            @endphp

            <div class="mb-6">
                @include('livewire.enfermeria.partials.expediente-signos-vitales')
            </div>

            {{-- Gráficos de Tendencias --}}
            <div class="mb-6">
                @livewire('enfermeria.graficos-tendencias', ['pacienteId' => $paciente->id], key('graficos-'.$paciente->id))
            </div>

            {{-- Historial del Paciente --}}
            @include('livewire.enfermeria.partials.expediente-historial')
            
        @elseif($activeTab === 'liquidos')
            @livewire('enfermeria.control-liquidos', ['pacienteId' => $paciente->id], key('liquidos-'.$paciente->id))
            
        @elseif($activeTab === 'escalas')
            @livewire('enfermeria.calculadora-escalas', ['pacienteId' => $paciente->id], key('escalas-'.$paciente->id))
            
        @elseif($activeTab === 'planes')
            @livewire('enfermeria.plan-cuidados-manager', ['pacienteId' => $paciente->id], key('planes-'.$paciente->id))
        @endif
    </div>
</div>