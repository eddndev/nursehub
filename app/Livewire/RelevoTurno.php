<?php

namespace App\Livewire;

use App\Enums\EstadoTurno;
use App\Enums\TipoTurno;
use App\Models\Area;
use App\Models\Turno;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;

class RelevoTurno extends Component
{
    public ?int $areaId = null;

    public ?int $turnoActualId = null;

    public string $novedadesRelevo = '';

    public function mount(?int $areaId = null)
    {
        $this->areaId = $areaId;

        // Si es jefe de piso, usar su área fija
        if (!$this->areaId && auth()->user()->enfermero?->area_fija_id) {
            $this->areaId = auth()->user()->enfermero->area_fija_id;
        }

        // Buscar turno activo
        if ($this->areaId) {
            $turnoActivo = Turno::activos()
                ->deHoy()
                ->where('area_id', $this->areaId)
                ->first();

            if ($turnoActivo) {
                $this->turnoActualId = $turnoActivo->id;
                $this->novedadesRelevo = $turnoActivo->novedades_relevo ?? '';
            }
        }
    }

    #[Computed]
    public function areas()
    {
        return Area::orderBy('nombre')->get();
    }

    #[Computed]
    public function turnoActual()
    {
        if (!$this->turnoActualId) {
            return null;
        }

        return Turno::with(['area', 'jefeTurno', 'asignaciones.enfermero.user', 'asignaciones.paciente'])
            ->find($this->turnoActualId);
    }

    #[Computed]
    public function turnoAnterior()
    {
        if (!$this->areaId || !$this->turnoActual) {
            return null;
        }

        // Buscar el turno anterior del mismo tipo
        return Turno::where('area_id', $this->areaId)
            ->where('tipo', $this->turnoActual->tipo)
            ->where('fecha', '<', $this->turnoActual->fecha)
            ->where('estado', EstadoTurno::CERRADO)
            ->orderBy('fecha', 'desc')
            ->orderBy('created_at', 'desc')
            ->with(['jefeTurno'])
            ->first();
    }

    #[Computed]
    public function resumenAsignaciones()
    {
        if (!$this->turnoActual) {
            return [
                'total_asignaciones' => 0,
                'enfermeros_activos' => 0,
                'pacientes_asignados' => 0,
            ];
        }

        $asignacionesActivas = $this->turnoActual->asignaciones()
            ->activas()
            ->with('enfermero', 'paciente')
            ->get();

        return [
            'total_asignaciones' => $asignacionesActivas->count(),
            'enfermeros_activos' => $asignacionesActivas->pluck('enfermero_id')->unique()->count(),
            'pacientes_asignados' => $asignacionesActivas->pluck('paciente_id')->unique()->count(),
        ];
    }

    public function cambiarArea()
    {
        $this->validate([
            'areaId' => 'required|exists:areas,id',
        ]);

        // Buscar turno activo en la nueva área
        $turnoActivo = Turno::activos()
            ->deHoy()
            ->where('area_id', $this->areaId)
            ->first();

        if ($turnoActivo) {
            $this->turnoActualId = $turnoActivo->id;
            $this->novedadesRelevo = $turnoActivo->novedades_relevo ?? '';
        } else {
            $this->turnoActualId = null;
            $this->novedadesRelevo = '';
        }

        $this->dispatch('area-cambiada');
    }

    public function guardarNovedades()
    {
        if (!$this->turnoActualId) {
            $this->dispatch('error', mensaje: 'No hay turno activo');
            return;
        }

        $this->validate([
            'novedadesRelevo' => 'nullable|string|max:5000',
        ]);

        try {
            $turno = Turno::find($this->turnoActualId);

            if (!$turno) {
                $this->dispatch('error', mensaje: 'Turno no encontrado');
                return;
            }

            $turno->update([
                'novedades_relevo' => $this->novedadesRelevo,
            ]);

            $this->dispatch('novedades-guardadas', mensaje: 'Novedades guardadas exitosamente');
        } catch (\Exception $e) {
            $this->dispatch('error', mensaje: 'Error al guardar novedades: ' . $e->getMessage());
        }
    }

    public function cerrarTurnoConRelevo()
    {
        if (!$this->turnoActualId) {
            $this->dispatch('error', mensaje: 'No hay turno activo para cerrar');
            return;
        }

        $this->validate([
            'novedadesRelevo' => 'nullable|string|max:5000',
        ]);

        try {
            DB::transaction(function () {
                $turno = Turno::find($this->turnoActualId);

                if (!$turno) {
                    throw new \Exception('Turno no encontrado');
                }

                if ($turno->estado === EstadoTurno::CERRADO) {
                    throw new \Exception('El turno ya está cerrado');
                }

                // Actualizar turno
                $turno->update([
                    'novedades_relevo' => $this->novedadesRelevo,
                    'estado' => EstadoTurno::CERRADO,
                    'cerrado_at' => now(),
                    'cerrado_por' => auth()->id(),
                ]);

                // Liberar todas las asignaciones activas del turno
                $turno->asignaciones()
                    ->activas()
                    ->update([
                        'fecha_hora_liberacion' => now(),
                        'liberado_por' => auth()->id(),
                        'motivo_liberacion' => 'Cierre de turno',
                    ]);

                $this->turnoActualId = null;
                $this->novedadesRelevo = '';
            });

            $this->dispatch('turno-cerrado', mensaje: 'Turno cerrado exitosamente. Relevo registrado.');
        } catch (\Exception $e) {
            $this->dispatch('error', mensaje: 'Error al cerrar turno: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.relevo-turno')->layout('layouts.app');
    }
}
