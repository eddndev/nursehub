<?php

namespace App\Livewire\Enfermeria;

use App\Models\Paciente;
use Livewire\Attributes\On;
use Livewire\Component;

class ExpedientePaciente extends Component
{
    public Paciente $paciente;

    public function mount($id)
    {
        $this->paciente = Paciente::with([
            'camaActual.cuarto.piso.area',
            'registrosSignosVitales.registradoPor',
            'historial.usuario',
            'admitidoPor'
        ])->findOrFail($id);
    }

    #[On('signos-vitales-registrados')]
    public function refreshPaciente()
    {
        $this->paciente->refresh();
        $this->paciente->load([
            'camaActual.cuarto.piso.area',
            'registrosSignosVitales.registradoPor',
            'historial.usuario',
            'admitidoPor'
        ]);
    }

    public function render()
    {
        return view('livewire.enfermeria.expediente-paciente')->layout('layouts.admin');
    }
}
