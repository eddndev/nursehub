<?php

namespace App\Services;

use App\Enums\EstadoInscripcion;
use App\Models\Enfermero;
use App\Models\InscripcionCapacitacion;
use App\Models\SesionCapacitacion;
use App\Models\Turno;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class ConflictoHorarioService
{
    /**
     * Verifica si un enfermero tiene conflictos entre turnos y sesiones de capacitación
     * en una fecha y horario específico.
     */
    public function verificarConflicto(
        int $enfermeroId,
        Carbon $fecha,
        string $horaInicio,
        string $horaFin
    ): array {
        $conflictos = [];

        // 1. Buscar turnos del enfermero en la fecha
        $turnos = $this->obtenerTurnosEnfermero($enfermeroId, $fecha);

        // 2. Buscar sesiones de capacitación donde el enfermero está inscrito en la fecha
        $sesiones = $this->obtenerSesionesCapacitacion($enfermeroId, $fecha);

        // 3. Verificar intersección con turnos
        foreach ($turnos as $turno) {
            if ($this->hayInterseccionHoraria(
                $horaInicio,
                $horaFin,
                $turno->hora_inicio,
                $turno->hora_fin
            )) {
                $conflictos[] = [
                    'tipo' => 'turno',
                    'entidad' => $turno,
                    'mensaje' => "Conflicto con turno {$turno->tipo->getLabel()} en {$turno->area->nombre}",
                ];
            }
        }

        // 4. Verificar intersección con sesiones de capacitación
        foreach ($sesiones as $sesion) {
            if ($this->hayInterseccionHoraria(
                $horaInicio,
                $horaFin,
                $sesion->hora_inicio,
                $sesion->hora_fin
            )) {
                $conflictos[] = [
                    'tipo' => 'capacitacion',
                    'entidad' => $sesion,
                    'actividad' => $sesion->actividad,
                    'mensaje' => "Conflicto con capacitación: {$sesion->actividad->titulo} (Sesión {$sesion->numero_sesion})",
                ];
            }
        }

        return $conflictos;
    }

    /**
     * Verifica conflictos al crear un turno para un enfermero.
     */
    public function verificarConflictoParaTurno(
        int $enfermeroId,
        Carbon $fecha,
        string $horaInicio,
        string $horaFin
    ): array {
        $conflictos = [];

        // Solo buscar sesiones de capacitación
        $sesiones = $this->obtenerSesionesCapacitacion($enfermeroId, $fecha);

        foreach ($sesiones as $sesion) {
            if ($this->hayInterseccionHoraria(
                $horaInicio,
                $horaFin,
                $sesion->hora_inicio,
                $sesion->hora_fin
            )) {
                $conflictos[] = [
                    'tipo' => 'capacitacion',
                    'entidad' => $sesion,
                    'actividad' => $sesion->actividad,
                    'mensaje' => "El enfermero tiene una sesión de capacitación: {$sesion->actividad->titulo} (Sesión {$sesion->numero_sesion}) de {$sesion->hora_inicio} a {$sesion->hora_fin}",
                ];
            }
        }

        return $conflictos;
    }

    /**
     * Verifica conflictos al inscribir un enfermero en una capacitación.
     */
    public function verificarConflictoParaInscripcion(
        int $enfermeroId,
        int $actividadId
    ): array {
        $conflictos = [];

        // Obtener todas las sesiones de la actividad
        $sesiones = SesionCapacitacion::where('actividad_id', $actividadId)
            ->orderBy('fecha')
            ->orderBy('hora_inicio')
            ->get();

        foreach ($sesiones as $sesion) {
            // Buscar turnos del enfermero en la fecha de la sesión
            $turnos = $this->obtenerTurnosEnfermero($enfermeroId, $sesion->fecha);

            foreach ($turnos as $turno) {
                if ($this->hayInterseccionHoraria(
                    $sesion->hora_inicio,
                    $sesion->hora_fin,
                    $turno->hora_inicio,
                    $turno->hora_fin
                )) {
                    $conflictos[] = [
                        'tipo' => 'turno',
                        'sesion' => $sesion,
                        'turno' => $turno,
                        'mensaje' => "Sesión {$sesion->numero_sesion} ({$sesion->fecha->format('d/m/Y')}) conflicta con turno {$turno->tipo->getLabel()} en {$turno->area->nombre}",
                    ];
                }
            }
        }

        return $conflictos;
    }

    /**
     * Obtiene todos los conflictos de un enfermero en un rango de fechas.
     */
    public function obtenerConflictosEnRango(
        int $enfermeroId,
        Carbon $fechaInicio,
        Carbon $fechaFin
    ): Collection {
        $conflictos = collect();

        // Obtener todas las sesiones del enfermero en el rango
        $sesiones = SesionCapacitacion::whereHas('actividad.inscripciones', function ($query) use ($enfermeroId) {
            $query->where('enfermero_id', $enfermeroId)
                ->whereIn('estado', [EstadoInscripcion::PENDIENTE->value, EstadoInscripcion::APROBADA->value]);
        })
            ->whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->with('actividad')
            ->get();

        foreach ($sesiones as $sesion) {
            // Buscar turnos del enfermero en la fecha de la sesión
            $turnos = $this->obtenerTurnosEnfermero($enfermeroId, $sesion->fecha);

            foreach ($turnos as $turno) {
                if ($this->hayInterseccionHoraria(
                    $sesion->hora_inicio,
                    $sesion->hora_fin,
                    $turno->hora_inicio,
                    $turno->hora_fin
                )) {
                    $conflictos->push([
                        'fecha' => $sesion->fecha,
                        'sesion' => $sesion,
                        'turno' => $turno,
                        'actividad' => $sesion->actividad,
                    ]);
                }
            }
        }

        return $conflictos;
    }

    /**
     * Verifica si un enfermero está actualmente en capacitación.
     */
    public function estaEnCapacitacion(int $enfermeroId, ?Carbon $fecha = null): bool
    {
        $fecha = $fecha ?? now();

        return SesionCapacitacion::whereHas('actividad.inscripciones', function ($query) use ($enfermeroId) {
            $query->where('enfermero_id', $enfermeroId)
                ->whereIn('estado', [EstadoInscripcion::PENDIENTE->value, EstadoInscripcion::APROBADA->value]);
        })
            ->where('fecha', $fecha->toDateString())
            ->where('hora_inicio', '<=', $fecha->format('H:i'))
            ->where('hora_fin', '>=', $fecha->format('H:i'))
            ->exists();
    }

    /**
     * Verifica si un enfermero tiene inscripciones activas en capacitaciones.
     */
    public function tieneCapacitacionesActivas(int $enfermeroId): bool
    {
        return InscripcionCapacitacion::where('enfermero_id', $enfermeroId)
            ->whereIn('estado', [EstadoInscripcion::PENDIENTE->value, EstadoInscripcion::APROBADA->value])
            ->whereHas('actividad.sesiones', function ($query) {
                $query->where('fecha', '>=', now()->toDateString());
            })
            ->exists();
    }

    /**
     * Obtiene las próximas sesiones de capacitación de un enfermero.
     */
    public function obtenerProximasSesiones(int $enfermeroId, int $limite = 5): Collection
    {
        return SesionCapacitacion::whereHas('actividad.inscripciones', function ($query) use ($enfermeroId) {
            $query->where('enfermero_id', $enfermeroId)
                ->whereIn('estado', [EstadoInscripcion::PENDIENTE->value, EstadoInscripcion::APROBADA->value]);
        })
            ->where('fecha', '>=', now()->toDateString())
            ->with('actividad')
            ->orderBy('fecha')
            ->orderBy('hora_inicio')
            ->limit($limite)
            ->get();
    }

    /**
     * Verifica si hay intersección horaria entre dos eventos.
     */
    protected function hayInterseccionHoraria(
        string $inicio1,
        string $fin1,
        string $inicio2,
        string $fin2
    ): bool {
        $inicio1 = Carbon::parse($inicio1);
        $fin1 = Carbon::parse($fin1);
        $inicio2 = Carbon::parse($inicio2);
        $fin2 = Carbon::parse($fin2);

        // Hay intersección si un evento comienza antes de que termine el otro
        // y termina después de que comience el otro
        return $inicio1->lt($fin2) && $fin1->gt($inicio2);
    }

    /**
     * Obtiene los turnos de un enfermero en una fecha específica.
     */
    protected function obtenerTurnosEnfermero(int $enfermeroId, Carbon $fecha): Collection
    {
        // Buscar turnos donde el enfermero tiene asignaciones
        return Turno::whereDate('fecha', $fecha)
            ->whereHas('asignaciones', function ($query) use ($enfermeroId) {
                $query->where('enfermero_id', $enfermeroId)
                    ->whereNull('fecha_hora_liberacion');
            })
            ->with('area')
            ->get();
    }

    /**
     * Obtiene las sesiones de capacitación de un enfermero en una fecha específica.
     */
    protected function obtenerSesionesCapacitacion(int $enfermeroId, Carbon $fecha): Collection
    {
        return SesionCapacitacion::whereHas('actividad.inscripciones', function ($query) use ($enfermeroId) {
            $query->where('enfermero_id', $enfermeroId)
                ->whereIn('estado', [EstadoInscripcion::PENDIENTE->value, EstadoInscripcion::APROBADA->value]);
        })
            ->where('fecha', $fecha->toDateString())
            ->with('actividad')
            ->get();
    }
}
