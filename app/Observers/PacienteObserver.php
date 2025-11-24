<?php

namespace App\Observers;

use App\Enums\PacienteEstado;
use App\Models\Paciente;

class PacienteObserver
{
    /**
     * Handle the Paciente "updated" event.
     *
     * Libera automáticamente las asignaciones del paciente cuando es dado de alta.
     */
    public function updated(Paciente $paciente): void
    {
        // Verificar si el estado cambió a "dado_alta"
        if ($paciente->isDirty('estado') && $paciente->estado === PacienteEstado::DADO_ALTA) {
            // Obtener todas las asignaciones activas del paciente
            $asignacionesActivas = $paciente->asignaciones()
                ->whereNull('fecha_hora_liberacion')
                ->get();

            // Liberar cada asignación
            foreach ($asignacionesActivas as $asignacion) {
                $asignacion->update([
                    'fecha_hora_liberacion' => now(),
                    'liberado_por' => auth()->id(),
                    'motivo_liberacion' => 'Alta del paciente',
                ]);
            }
        }
    }
}
