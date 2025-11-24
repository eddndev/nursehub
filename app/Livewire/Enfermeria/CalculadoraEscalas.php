<?php

namespace App\Livewire\Enfermeria;

use App\Models\ValoracionEscala;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CalculadoraEscalas extends Component
{
    public $pacienteId;
    public $tipoEscala = 'EVA'; // Default

    // EVA Inputs
    public $evaPuntaje = 0;

    // Glasgow Inputs
    public $glasgowOcular = 4;
    public $glasgowVerbal = 5;
    public $glasgowMotora = 6;

    // Braden Inputs (Simplified for MVP)
    public $bradenPercepcion = 4;
    public $bradenExposicion = 4;
    public $bradenActividad = 4;
    public $bradenMovilidad = 4;
    public $bradenNutricion = 4;
    public $bradenFriccion = 3;

    public function mount($pacienteId)
    {
        $this->pacienteId = $pacienteId;
    }

    public function guardar()
    {
        $puntajeTotal = 0;
        $detalle = [];
        $riesgo = null;

        if ($this->tipoEscala === 'EVA') {
            $puntajeTotal = $this->evaPuntaje;
            $detalle = ['valor' => $this->evaPuntaje];
            $riesgo = $this->interpretarEva($puntajeTotal);
        } elseif ($this->tipoEscala === 'GLASGOW') {
            $puntajeTotal = $this->glasgowOcular + $this->glasgowVerbal + $this->glasgowMotora;
            $detalle = [
                'ocular' => $this->glasgowOcular,
                'verbal' => $this->glasgowVerbal,
                'motora' => $this->glasgowMotora,
            ];
            $riesgo = $this->interpretarGlasgow($puntajeTotal);
        } elseif ($this->tipoEscala === 'BRADEN') {
            $puntajeTotal = $this->bradenPercepcion + $this->bradenExposicion + $this->bradenActividad + 
                            $this->bradenMovilidad + $this->bradenNutricion + $this->bradenFriccion;
            $detalle = [
                'percepcion' => $this->bradenPercepcion,
                'exposicion' => $this->bradenExposicion,
                'actividad' => $this->bradenActividad,
                'movilidad' => $this->bradenMovilidad,
                'nutricion' => $this->bradenNutricion,
                'friccion' => $this->bradenFriccion,
            ];
            $riesgo = $this->interpretarBraden($puntajeTotal);
        }

        ValoracionEscala::create([
            'paciente_id' => $this->pacienteId,
            'tipo_escala' => $this->tipoEscala,
            'puntaje_total' => $puntajeTotal,
            'detalle_json' => $detalle,
            'riesgo_interpretado' => $riesgo,
            'fecha_hora' => Carbon::now(),
            'registrado_por' => Auth::id(),
        ]);

        $this->dispatch('escala-guardada');
        $this->resetInputs();
    }

    public function resetInputs()
    {
        $this->evaPuntaje = 0;
        $this->glasgowOcular = 4;
        // ... reset others if needed
    }

    private function interpretarEva($puntaje)
    {
        if ($puntaje == 0) return 'Sin Dolor';
        if ($puntaje <= 3) return 'Dolor Leve';
        if ($puntaje <= 7) return 'Dolor Moderado';
        return 'Dolor Severo';
    }

    private function interpretarGlasgow($puntaje)
    {
        if ($puntaje <= 8) return 'Grave (Coma)';
        if ($puntaje <= 12) return 'Moderado';
        return 'Leve';
    }

    private function interpretarBraden($puntaje)
    {
        if ($puntaje <= 12) return 'Alto Riesgo';
        if ($puntaje <= 14) return 'Riesgo Moderado';
        if ($puntaje <= 18) return 'Riesgo Bajo';
        return 'Sin Riesgo';
    }

    public function render()
    {
        $historico = ValoracionEscala::where('paciente_id', $this->pacienteId)
            ->orderBy('fecha_hora', 'desc')
            ->take(5)
            ->get();

        return view('livewire.enfermeria.calculadora-escalas', [
            'historico' => $historico
        ]);
    }
}
