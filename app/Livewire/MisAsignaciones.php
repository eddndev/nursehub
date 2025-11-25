<?php

namespace App\Livewire;

use App\Models\AsignacionPaciente;
use App\Models\Enfermero;
use App\Models\Turno;
use Livewire\Attributes\Computed;
use Livewire\Component;

class MisAsignaciones extends Component
{
    public ?int $enfermeroId = null;

    public function mount()
    {
        // Obtener el enfermero del usuario autenticado
        $this->enfermeroId = auth()->user()->enfermero?->id;

        if (!$this->enfermeroId) {
            abort(403, 'Usuario no tiene perfil de enfermero asignado');
        }
    }

    #[Computed]
    public function enfermero()
    {
        return Enfermero::with('user', 'areaFija')->find($this->enfermeroId);
    }

    #[Computed]
    public function turnoActual()
    {
        // Buscar turno activo de hoy en el área del enfermero
        if (!$this->enfermero || !$this->enfermero->area_fija_id) {
            // Si no tiene área fija, buscar cualquier turno donde tenga asignaciones
            return Turno::activos()
                ->deHoy()
                ->whereHas('asignaciones', function ($query) {
                    $query->where('enfermero_id', $this->enfermeroId)
                        ->whereNull('fecha_hora_liberacion');
                })
                ->with('area', 'jefeTurno')
                ->first();
        }

        return Turno::activos()
            ->deHoy()
            ->where('area_id', $this->enfermero->area_fija_id)
            ->with('area', 'jefeTurno')
            ->first();
    }

    #[Computed]
    public function pacientesAsignados()
    {
        if (!$this->turnoActual) {
            return collect();
        }

        return AsignacionPaciente::with([
            'paciente.camaActual.cuarto.piso',
            'paciente.registrosSignosVitales' => function ($query) {
                $query->latest('fecha_registro')->limit(1);
            },
        ])
            ->porTurno($this->turnoActual->id)
            ->porEnfermero($this->enfermeroId)
            ->activas()
            ->orderBy('fecha_hora_asignacion', 'desc')
            ->get()
            ->map(function ($asignacion) {
                $paciente = $asignacion->paciente;

                // Obtener último registro de signos vitales
                $ultimoRegistro = $paciente->registrosSignosVitales->first();

                $paciente->ultimo_registro_signos = $ultimoRegistro;
                $paciente->nivel_triage = $ultimoRegistro?->nivel_triage;
                $paciente->asignacion = $asignacion;

                return $paciente;
            });
    }

    #[Computed]
    public function estadisticas()
    {
        $pacientes = $this->pacientesAsignados;

        return [
            'total_pacientes' => $pacientes->count(),
            'con_triage_rojo' => $pacientes->filter(fn($p) => $p->nivel_triage?->getPrioridad() === 1)->count(),
            'con_triage_naranja' => $pacientes->filter(fn($p) => $p->nivel_triage?->getPrioridad() === 2)->count(),
            'con_triage_amarillo' => $pacientes->filter(fn($p) => $p->nivel_triage?->getPrioridad() === 3)->count(),
            'con_triage_verde' => $pacientes->filter(fn($p) => $p->nivel_triage?->getPrioridad() === 4)->count(),
            'con_triage_azul' => $pacientes->filter(fn($p) => $p->nivel_triage?->getPrioridad() === 5)->count(),
        ];
    }

    public function verExpediente($pacienteId)
    {
        return redirect()->route('enfermeria.expediente', ['id' => $pacienteId]);
    }

    public function refrescarAsignaciones()
    {
        // Los computed properties se actualizan automáticamente
        $this->dispatch('asignaciones-refrescadas');
    }

    public function render()
    {
        return view('livewire.mis-asignaciones')->layout('layouts.app');
    }
}
