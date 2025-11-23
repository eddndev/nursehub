<div class="p-6">
    {{-- Header del Paciente --}}
    @include('livewire.enfermeria.partials.expediente-header')

    {{-- Información Básica del Paciente --}}
    <div class="mb-6">
        @include('livewire.enfermeria.partials.expediente-info-basica')
    </div>

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
</div>
