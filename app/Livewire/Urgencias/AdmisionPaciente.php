<?php

namespace App\Livewire\Urgencias;

use App\Enums\TipoEventoHistorial;
use App\Models\Cama;
use App\Models\HistorialPaciente;
use App\Models\Paciente;
use App\Models\RegistroSignosVitales;
use App\Services\QRCodeGenerator;
use App\Services\TriageCalculator;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Validate;
use Livewire\Component;

class AdmisionPaciente extends Component
{
    #[Validate('required|string|max:255')]
    public $nombre = '';

    #[Validate('required|string|max:255')]
    public $apellido_paterno = '';

    #[Validate('nullable|string|max:255')]
    public $apellido_materno = '';

    #[Validate('required|in:M,F,Otro')]
    public $sexo = '';

    #[Validate('required|date|before:today')]
    public $fecha_nacimiento = '';

    #[Validate('nullable|string|size:18|unique:pacientes,curp')]
    public $curp = '';

    #[Validate('nullable|string|max:15')]
    public $telefono = '';

    #[Validate('nullable|string|max:255')]
    public $contacto_emergencia_nombre = '';

    #[Validate('nullable|string|max:15')]
    public $contacto_emergencia_telefono = '';

    #[Validate('nullable|string')]
    public $alergias = '';

    #[Validate('nullable|string')]
    public $antecedentes_medicos = '';

    #[Validate('nullable|exists:camas,id')]
    public $cama_id = null;

    #[Validate('nullable|numeric|min:50|max:250')]
    public $presion_arterial_sistolica = null;

    #[Validate('nullable|numeric|min:30|max:150')]
    public $presion_arterial_diastolica = null;

    #[Validate('nullable|integer|min:30|max:200')]
    public $frecuencia_cardiaca = null;

    #[Validate('nullable|integer|min:5|max:50')]
    public $frecuencia_respiratoria = null;

    #[Validate('nullable|numeric|min:30|max:45')]
    public $temperatura = null;

    #[Validate('nullable|numeric|min:50|max:100')]
    public $saturacion_oxigeno = null;

    #[Validate('nullable|numeric|min:50|max:500')]
    public $glucosa = null;

    #[Validate('nullable|string')]
    public $observaciones_iniciales = '';

    public $nivel_triage_sugerido = null;
    public $nivel_triage_override = null;
    public $paciente_admitido = null;
    public $codigo_qr = null;
    public $mensaje_exito = false;

    public function updatedPresionArterialSistolica()
    {
        $this->calcularTriageSugerido();
    }

    public function updatedPresionArterialDiastolica()
    {
        $this->calcularTriageSugerido();
    }

    public function updatedFrecuenciaCardiaca()
    {
        $this->calcularTriageSugerido();
    }

    public function updatedFrecuenciaRespiratoria()
    {
        $this->calcularTriageSugerido();
    }

    public function updatedTemperatura()
    {
        $this->calcularTriageSugerido();
    }

    public function updatedSaturacionOxigeno()
    {
        $this->calcularTriageSugerido();
    }

    private function calcularTriageSugerido()
    {
        if ($this->presion_arterial_sistolica || $this->frecuencia_cardiaca ||
            $this->temperatura || $this->saturacion_oxigeno) {

            $signos = [
                'presion_arterial_sistolica' => $this->presion_arterial_sistolica,
                'presion_arterial_diastolica' => $this->presion_arterial_diastolica,
                'frecuencia_cardiaca' => $this->frecuencia_cardiaca,
                'frecuencia_respiratoria' => $this->frecuencia_respiratoria,
                'temperatura' => $this->temperatura,
                'saturacion_oxigeno' => $this->saturacion_oxigeno,
            ];

            $this->nivel_triage_sugerido = TriageCalculator::calcular($signos);
        } else {
            $this->nivel_triage_sugerido = null;
        }
    }

    public function overrideTriage(string $nivel)
    {
        $this->nivel_triage_override = $nivel;
    }

    public function admitir()
    {
        $this->validate();

        DB::transaction(function () {
            $codigoQR = QRCodeGenerator::generarCodigoPaciente();

            $paciente = Paciente::create([
                'codigo_qr' => $codigoQR,
                'nombre' => $this->nombre,
                'apellido_paterno' => $this->apellido_paterno,
                'apellido_materno' => $this->apellido_materno,
                'sexo' => $this->sexo,
                'fecha_nacimiento' => $this->fecha_nacimiento,
                'curp' => $this->curp,
                'telefono' => $this->telefono,
                'contacto_emergencia_nombre' => $this->contacto_emergencia_nombre,
                'contacto_emergencia_telefono' => $this->contacto_emergencia_telefono,
                'alergias' => $this->alergias,
                'antecedentes_medicos' => $this->antecedentes_medicos,
                'estado' => 'activo',
                'cama_actual_id' => $this->cama_id,
                'admitido_por' => auth()->id(),
                'fecha_admision' => now(),
            ]);

            HistorialPaciente::create([
                'paciente_id' => $paciente->id,
                'usuario_id' => auth()->id(),
                'tipo_evento' => TipoEventoHistorial::ADMISION,
                'descripcion' => 'Paciente admitido en Urgencias',
                'metadata' => [
                    'codigo_qr' => $codigoQR,
                    'cama_id' => $this->cama_id,
                ],
                'fecha_evento' => now(),
            ]);

            if ($this->tieneSignosVitales()) {
                $nivelTriage = $this->nivel_triage_override ?? $this->nivel_triage_sugerido;

                RegistroSignosVitales::create([
                    'paciente_id' => $paciente->id,
                    'registrado_por' => auth()->id(),
                    'presion_arterial_sistolica' => $this->presion_arterial_sistolica,
                    'presion_arterial_diastolica' => $this->presion_arterial_diastolica,
                    'frecuencia_cardiaca' => $this->frecuencia_cardiaca,
                    'frecuencia_respiratoria' => $this->frecuencia_respiratoria,
                    'temperatura' => $this->temperatura,
                    'saturacion_oxigeno' => $this->saturacion_oxigeno,
                    'glucosa' => $this->glucosa,
                    'nivel_triage' => $nivelTriage,
                    'triage_override' => $this->nivel_triage_override !== null,
                    'observaciones' => $this->observaciones_iniciales,
                    'fecha_registro' => now(),
                ]);

                HistorialPaciente::create([
                    'paciente_id' => $paciente->id,
                    'usuario_id' => auth()->id(),
                    'tipo_evento' => TipoEventoHistorial::SIGNOS_VITALES,
                    'descripcion' => 'Signos vitales iniciales registrados - TRIAGE: ' . $nivelTriage->getLabel(),
                    'metadata' => [
                        'triage' => $nivelTriage->value,
                        'override' => $this->nivel_triage_override !== null,
                    ],
                    'fecha_evento' => now(),
                ]);
            }

            if ($this->cama_id) {
                $cama = Cama::find($this->cama_id);
                $cama->update(['estado' => CamaEstado::OCUPADA]);

                HistorialPaciente::create([
                    'paciente_id' => $paciente->id,
                    'usuario_id' => auth()->id(),
                    'tipo_evento' => TipoEventoHistorial::CAMBIO_CAMA,
                    'descripcion' => 'Paciente asignado a cama ' . $cama->numero,
                    'metadata' => [
                        'cama_id' => $cama->id,
                        'cama_numero' => $cama->numero,
                    ],
                    'fecha_evento' => now(),
                ]);
            }

            $this->paciente_admitido = $paciente->load('camaActual');
            $this->codigo_qr = $codigoQR;
            $this->mensaje_exito = true;
        });

        $this->dispatch('paciente-admitido', id: $this->paciente_admitido->id);
    }

    private function tieneSignosVitales(): bool
    {
        return $this->presion_arterial_sistolica || $this->presion_arterial_diastolica ||
               $this->frecuencia_cardiaca || $this->frecuencia_respiratoria ||
               $this->temperatura || $this->saturacion_oxigeno || $this->glucosa;
    }

    public function nuevaAdmision()
    {
        $this->reset();
        $this->mensaje_exito = false;
    }

    public function render()
    {
        $camasDisponibles = Cama::where('estado', 'libre')
            ->with(['cuarto.piso.area'])
            ->get();

        return view('livewire.urgencias.admision-paciente', [
            'camasDisponibles' => $camasDisponibles,
        ])->layout('layouts.admin');
    }
}
