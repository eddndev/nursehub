<?php

namespace App\Livewire\Enfermeria;

use App\Enums\NivelTriage;
use App\Models\Paciente;
use App\Models\RegistroSignosVitales;
use App\Services\TriageService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class RegistroSignosVitales extends Component
{
    public Paciente $paciente;

    // Signos vitales
    public $presion_arterial_sistolica = '';
    public $presion_arterial_diastolica = '';
    public $frecuencia_cardiaca = '';
    public $frecuencia_respiratoria = '';
    public $temperatura = '';
    public $saturacion_oxigeno = '';
    public $glucosa = '';
    public $observaciones = '';

    // TRIAGE
    public $nivel_triage;
    public $triage_calculado;
    public $triage_override = false;

    public $modalOpen = false;

    protected $rules = [
        'presion_arterial_sistolica' => 'nullable|numeric|min:40|max:300',
        'presion_arterial_diastolica' => 'nullable|numeric|min:20|max:200',
        'frecuencia_cardiaca' => 'nullable|numeric|min:20|max:250',
        'frecuencia_respiratoria' => 'nullable|numeric|min:5|max:60',
        'temperatura' => 'nullable|numeric|min:30|max:45',
        'saturacion_oxigeno' => 'nullable|numeric|min:50|max:100',
        'glucosa' => 'nullable|numeric|min:20|max:600',
        'observaciones' => 'nullable|string|max:500',
        'nivel_triage' => 'nullable|string',
    ];

    protected $messages = [
        'presion_arterial_sistolica.min' => 'La presión sistólica debe ser al menos 40 mmHg',
        'presion_arterial_sistolica.max' => 'La presión sistólica no puede ser mayor a 300 mmHg',
        'presion_arterial_diastolica.min' => 'La presión diastólica debe ser al menos 20 mmHg',
        'presion_arterial_diastolica.max' => 'La presión diastólica no puede ser mayor a 200 mmHg',
        'frecuencia_cardiaca.min' => 'La frecuencia cardíaca debe ser al menos 20 lpm',
        'frecuencia_cardiaca.max' => 'La frecuencia cardíaca no puede ser mayor a 250 lpm',
        'frecuencia_respiratoria.min' => 'La frecuencia respiratoria debe ser al menos 5 rpm',
        'frecuencia_respiratoria.max' => 'La frecuencia respiratoria no puede ser mayor a 60 rpm',
        'temperatura.min' => 'La temperatura debe ser al menos 30°C',
        'temperatura.max' => 'La temperatura no puede ser mayor a 45°C',
        'saturacion_oxigeno.min' => 'La saturación de oxígeno debe ser al menos 50%',
        'saturacion_oxigeno.max' => 'La saturación de oxígeno no puede ser mayor a 100%',
        'glucosa.min' => 'La glucosa debe ser al menos 20 mg/dL',
        'glucosa.max' => 'La glucosa no puede ser mayor a 600 mg/dL',
        'observaciones.max' => 'Las observaciones no pueden exceder 500 caracteres',
    ];

    public function mount($pacienteId)
    {
        $this->paciente = Paciente::findOrFail($pacienteId);
    }

    public function abrirModal()
    {
        $this->modalOpen = true;
        $this->resetForm();
    }

    public function cerrarModal()
    {
        $this->modalOpen = false;
        $this->resetForm();
        $this->resetValidation();
    }

    private function resetForm()
    {
        $this->presion_arterial_sistolica = '';
        $this->presion_arterial_diastolica = '';
        $this->frecuencia_cardiaca = '';
        $this->frecuencia_respiratoria = '';
        $this->temperatura = '';
        $this->saturacion_oxigeno = '';
        $this->glucosa = '';
        $this->observaciones = '';
        $this->nivel_triage = null;
        $this->triage_calculado = null;
        $this->triage_override = false;
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);

        // Recalcular TRIAGE automáticamente cuando cambian los signos vitales
        if (in_array($propertyName, [
            'presion_arterial_sistolica',
            'presion_arterial_diastolica',
            'frecuencia_cardiaca',
            'frecuencia_respiratoria',
            'temperatura',
            'saturacion_oxigeno'
        ])) {
            $this->calcularTriage();
        }
    }

    public function calcularTriage()
    {
        $triageService = app(TriageService::class);

        $signosVitales = [
            'presion_arterial_sistolica' => $this->presion_arterial_sistolica ?: null,
            'presion_arterial_diastolica' => $this->presion_arterial_diastolica ?: null,
            'frecuencia_cardiaca' => $this->frecuencia_cardiaca ?: null,
            'frecuencia_respiratoria' => $this->frecuencia_respiratoria ?: null,
            'temperatura' => $this->temperatura ?: null,
            'saturacion_oxigeno' => $this->saturacion_oxigeno ?: null,
        ];

        // Solo calcular si hay al menos un signo vital
        if (array_filter($signosVitales)) {
            $this->triage_calculado = $triageService->calcularNivelTriage($signosVitales);

            // Si no hay override manual, usar el calculado
            if (!$this->triage_override) {
                $this->nivel_triage = $this->triage_calculado->value;
            }
        } else {
            $this->triage_calculado = null;
            if (!$this->triage_override) {
                $this->nivel_triage = null;
            }
        }
    }

    public function overrideTriage($nivelValue)
    {
        $this->nivel_triage = $nivelValue;
        $this->triage_override = true;
    }

    public function usarTriageCalculado()
    {
        if ($this->triage_calculado) {
            $this->nivel_triage = $this->triage_calculado->value;
            $this->triage_override = false;
        }
    }

    public function guardarRegistro()
    {
        $this->validate();

        // Validar que haya al menos un signo vital
        if (empty($this->presion_arterial_sistolica) &&
            empty($this->presion_arterial_diastolica) &&
            empty($this->frecuencia_cardiaca) &&
            empty($this->frecuencia_respiratoria) &&
            empty($this->temperatura) &&
            empty($this->saturacion_oxigeno) &&
            empty($this->glucosa)) {
            $this->addError('general', 'Debe registrar al menos un signo vital');
            return;
        }

        // Validar presión arterial completa
        if ((!empty($this->presion_arterial_sistolica) && empty($this->presion_arterial_diastolica)) ||
            (empty($this->presion_arterial_sistolica) && !empty($this->presion_arterial_diastolica))) {
            $this->addError('presion_arterial_sistolica', 'Debe ingresar presión sistólica y diastólica');
            $this->addError('presion_arterial_diastolica', 'Debe ingresar presión sistólica y diastólica');
            return;
        }

        // Validar que sistólica sea mayor que diastólica
        if (!empty($this->presion_arterial_sistolica) && !empty($this->presion_arterial_diastolica)) {
            if ($this->presion_arterial_sistolica <= $this->presion_arterial_diastolica) {
                $this->addError('presion_arterial_sistolica', 'La presión sistólica debe ser mayor que la diastólica');
                return;
            }
        }

        try {
            DB::beginTransaction();

            // Construir presión arterial en formato "120/80"
            $presion_arterial = null;
            if (!empty($this->presion_arterial_sistolica) && !empty($this->presion_arterial_diastolica)) {
                $presion_arterial = $this->presion_arterial_sistolica . '/' . $this->presion_arterial_diastolica;
            }

            // Crear el registro
            $registro = RegistroSignosVitales::create([
                'paciente_id' => $this->paciente->id,
                'presion_arterial' => $presion_arterial,
                'frecuencia_cardiaca' => $this->frecuencia_cardiaca ?: null,
                'frecuencia_respiratoria' => $this->frecuencia_respiratoria ?: null,
                'temperatura' => $this->temperatura ?: null,
                'saturacion_oxigeno' => $this->saturacion_oxigeno ?: null,
                'glucosa' => $this->glucosa ?: null,
                'nivel_triage' => $this->nivel_triage ? NivelTriage::from($this->nivel_triage) : null,
                'triage_override' => $this->triage_override,
                'observaciones' => $this->observaciones ?: null,
                'registrado_por' => Auth::id(),
                'fecha_registro' => now(),
            ]);

            // Registrar en el historial
            $this->paciente->historial()->create([
                'tipo_evento' => 'Registro de Signos Vitales',
                'descripcion' => $this->generarDescripcionHistorial($presion_arterial),
                'usuario_id' => Auth::id(),
                'fecha_evento' => now(),
            ]);

            DB::commit();

            session()->flash('message', 'Signos vitales registrados exitosamente');

            $this->cerrarModal();

            // Emitir evento para refrescar el componente padre
            $this->dispatch('signos-vitales-registrados');

        } catch (\Exception $e) {
            DB::rollBack();
            $this->addError('general', 'Error al guardar el registro: ' . $e->getMessage());
        }
    }

    private function generarDescripcionHistorial($presion_arterial)
    {
        $partes = [];

        if ($presion_arterial) $partes[] = "P/A: {$presion_arterial} mmHg";
        if ($this->frecuencia_cardiaca) $partes[] = "FC: {$this->frecuencia_cardiaca} lpm";
        if ($this->frecuencia_respiratoria) $partes[] = "FR: {$this->frecuencia_respiratoria} rpm";
        if ($this->temperatura) $partes[] = "Temp: {$this->temperatura}°C";
        if ($this->saturacion_oxigeno) $partes[] = "SpO2: {$this->saturacion_oxigeno}%";
        if ($this->glucosa) $partes[] = "Glucosa: {$this->glucosa} mg/dL";

        $descripcion = implode(', ', $partes);

        if ($this->nivel_triage) {
            $nivelTriageEnum = NivelTriage::from($this->nivel_triage);
            $descripcion .= " | TRIAGE: {$nivelTriageEnum->getLabel()}";
            if ($this->triage_override) {
                $descripcion .= " (Manual)";
            }
        }

        return $descripcion;
    }

    public function render()
    {
        return view('livewire.enfermeria.registro-signos-vitales', [
            'nivelesTriage' => NivelTriage::cases(),
        ]);
    }
}
