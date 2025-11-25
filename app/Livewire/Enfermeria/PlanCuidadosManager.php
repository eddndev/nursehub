<?php

namespace App\Livewire\Enfermeria;

use App\Models\DiagnosticoEnfermeria;
use App\Models\IntervencionCuidado;
use App\Models\PlanCuidado;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class PlanCuidadosManager extends Component
{
    public $pacienteId;
    public $activePlanId = null;
    public $showCreateForm = false;

    // Create Plan Inputs
    public $selectedDiagnosticoId;
    public $customDiagnostico = ''; // Fallback if catalog is empty

    // Add Intervention Inputs
    public $newIntervencionDesc;
    public $newIntervencionFrec;

    protected $rules = [
        'selectedDiagnosticoId' => 'required|exists:diagnostico_enfermerias,id',
    ];

    public function mount($pacienteId)
    {
        $this->pacienteId = $pacienteId;
    }

    public function toggleCreateForm()
    {
        $this->showCreateForm = !$this->showCreateForm;
    }

    public function createPlan()
    {
        $this->validate();

        $plan = PlanCuidado::create([
            'paciente_id' => $this->pacienteId,
            'diagnostico_id' => $this->selectedDiagnosticoId,
            'estado' => 'activo',
            'fecha_inicio' => Carbon::now(),
            'registrado_por' => Auth::id(),
        ]);

        $this->activePlanId = $plan->id;
        $this->showCreateForm = false;
        $this->reset('selectedDiagnosticoId');
    }

    public function selectPlan($planId)
    {
        $this->activePlanId = $planId;
    }

    public function addIntervencion()
    {
        $this->validate([
            'newIntervencionDesc' => 'required|string|min:3',
            'newIntervencionFrec' => 'nullable|string',
        ]);

        if ($this->activePlanId) {
            IntervencionCuidado::create([
                'plan_cuidado_id' => $this->activePlanId,
                'descripcion' => $this->newIntervencionDesc,
                'frecuencia' => $this->newIntervencionFrec,
                'realizado' => false,
            ]);

            $this->reset(['newIntervencionDesc', 'newIntervencionFrec']);
        }
    }

    public function toggleIntervencion($intervencionId)
    {
        $intervencion = IntervencionCuidado::find($intervencionId);
        if ($intervencion) {
            $intervencion->realizado = !$intervencion->realizado;
            if ($intervencion->realizado) {
                $intervencion->realizado_at = Carbon::now();
                $intervencion->realizado_por = Auth::id();
            } else {
                $intervencion->realizado_at = null;
                $intervencion->realizado_por = null;
            }
            $intervencion->save();
        }
    }

    public function render()
    {
        $planes = PlanCuidado::with('diagnostico')
            ->where('paciente_id', $this->pacienteId)
            ->orderBy('created_at', 'desc')
            ->get();

        $activePlan = null;
        if ($this->activePlanId) {
            $activePlan = PlanCuidado::with(['diagnostico', 'intervenciones'])->find($this->activePlanId);
        }

        $diagnosticos = DiagnosticoEnfermeria::all();

        return view('livewire.enfermeria.plan-cuidados-manager', [
            'planes' => $planes,
            'activePlan' => $activePlan,
            'diagnosticos' => $diagnosticos,
        ]);
    }
}
