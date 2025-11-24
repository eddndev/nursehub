<?php

namespace App\Livewire\Capacitacion;

use App\Models\ActividadCapacitacion;
use App\Models\AsistenciaCapacitacion;
use App\Models\InscripcionCapacitacion;
use App\Models\SesionCapacitacion;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;

class ControlAsistencia extends Component
{
    // Identificadores
    public $actividadId;
    public $sesionId;

    // Datos temporales para registro de asistencia
    public $asistencias = [];
    public $busqueda = '';
    public $mostrarSoloAusentes = false;

    // Modales
    public $modalConfirmarGuardar = false;
    public $modalDetallesSesion = false;

    // Estadísticas temporales
    public $totalPresentes = 0;
    public $totalAusentes = 0;

    public function mount($actividadId, $sesionId)
    {
        $this->actividadId = $actividadId;
        $this->sesionId = $sesionId;
        $this->cargarAsistencias();
    }

    #[Computed]
    public function actividad()
    {
        return ActividadCapacitacion::with(['inscripciones.enfermero.user', 'sesiones'])
            ->findOrFail($this->actividadId);
    }

    #[Computed]
    public function sesion()
    {
        return SesionCapacitacion::with(['actividad', 'asistencias.inscripcion.enfermero.user'])
            ->findOrFail($this->sesionId);
    }

    #[Computed]
    public function inscripciones()
    {
        $query = InscripcionCapacitacion::with(['enfermero.user', 'enfermero.area'])
            ->where('actividad_id', $this->actividadId)
            ->whereIn('estado', ['pendiente', 'aprobada']);

        // Filtro de búsqueda
        if ($this->busqueda) {
            $query->whereHas('enfermero.user', function ($q) {
                $q->where('name', 'like', "%{$this->busqueda}%")
                    ->orWhere('email', 'like', "%{$this->busqueda}%");
            });
        }

        // Filtro de solo ausentes
        if ($this->mostrarSoloAusentes) {
            $inscripcionIds = collect($this->asistencias)
                ->filter(fn($asistencia) => !$asistencia['presente'])
                ->pluck('inscripcion_id')
                ->toArray();

            if (!empty($inscripcionIds)) {
                $query->whereIn('id', $inscripcionIds);
            } else {
                // Si no hay ausentes, mostrar ninguno
                $query->whereRaw('1 = 0');
            }
        }

        return $query->orderBy('created_at', 'asc')->get();
    }

    public function cargarAsistencias()
    {
        // Obtener inscripciones de la actividad
        $inscripciones = InscripcionCapacitacion::where('actividad_id', $this->actividadId)
            ->whereIn('estado', ['pendiente', 'aprobada'])
            ->pluck('id');

        // Cargar asistencias existentes o crear array vacío
        $asistenciasExistentes = AsistenciaCapacitacion::where('sesion_id', $this->sesionId)
            ->whereIn('inscripcion_id', $inscripciones)
            ->get()
            ->keyBy('inscripcion_id');

        // Inicializar array de asistencias
        $this->asistencias = [];
        foreach ($inscripciones as $inscripcionId) {
            $asistenciaExistente = $asistenciasExistentes->get($inscripcionId);

            $this->asistencias[$inscripcionId] = [
                'inscripcion_id' => $inscripcionId,
                'presente' => $asistenciaExistente ? $asistenciaExistente->presente : false,
                'hora_entrada' => $asistenciaExistente?->hora_entrada,
                'hora_salida' => $asistenciaExistente?->hora_salida,
                'observaciones' => $asistenciaExistente?->observaciones ?? '',
                'id' => $asistenciaExistente?->id,
            ];
        }

        $this->calcularEstadisticas();
    }

    public function toggleAsistencia($inscripcionId)
    {
        if (isset($this->asistencias[$inscripcionId])) {
            $this->asistencias[$inscripcionId]['presente'] = !$this->asistencias[$inscripcionId]['presente'];

            // Si se marca como presente, asignar hora de entrada actual si no existe
            if ($this->asistencias[$inscripcionId]['presente'] && !$this->asistencias[$inscripcionId]['hora_entrada']) {
                $this->asistencias[$inscripcionId]['hora_entrada'] = now()->format('H:i:s');
            }

            $this->calcularEstadisticas();
        }
    }

    public function marcarTodosPresentes()
    {
        $horaActual = now()->format('H:i:s');

        foreach ($this->asistencias as $inscripcionId => $asistencia) {
            $this->asistencias[$inscripcionId]['presente'] = true;
            if (!$this->asistencias[$inscripcionId]['hora_entrada']) {
                $this->asistencias[$inscripcionId]['hora_entrada'] = $horaActual;
            }
        }

        $this->calcularEstadisticas();
        $this->dispatch('success', mensaje: 'Todos marcados como presentes');
    }

    public function marcarTodosAusentes()
    {
        foreach ($this->asistencias as $inscripcionId => $asistencia) {
            $this->asistencias[$inscripcionId]['presente'] = false;
            $this->asistencias[$inscripcionId]['hora_entrada'] = null;
            $this->asistencias[$inscripcionId]['hora_salida'] = null;
        }

        $this->calcularEstadisticas();
        $this->dispatch('success', mensaje: 'Todos marcados como ausentes');
    }

    public function calcularEstadisticas()
    {
        $this->totalPresentes = collect($this->asistencias)->filter(fn($a) => $a['presente'])->count();
        $this->totalAusentes = collect($this->asistencias)->filter(fn($a) => !$a['presente'])->count();
    }

    public function abrirModalConfirmarGuardar()
    {
        if (empty($this->asistencias)) {
            $this->dispatch('error', mensaje: 'No hay asistencias para guardar');
            return;
        }

        $this->modalConfirmarGuardar = true;
    }

    public function guardarAsistencias()
    {
        try {
            DB::transaction(function () {
                foreach ($this->asistencias as $inscripcionId => $datos) {
                    // Buscar o crear registro de asistencia
                    $asistencia = AsistenciaCapacitacion::updateOrCreate(
                        [
                            'sesion_id' => $this->sesionId,
                            'inscripcion_id' => $inscripcionId,
                        ],
                        [
                            'presente' => $datos['presente'],
                            'hora_entrada' => $datos['presente'] ? ($datos['hora_entrada'] ?? now()->format('H:i:s')) : null,
                            'hora_salida' => $datos['hora_salida'],
                            'observaciones' => $datos['observaciones'] ?? '',
                            'registrado_por' => auth()->id(),
                        ]
                    );

                    // Calcular minutos asistidos si hay entrada y salida
                    if ($asistencia->presente && $asistencia->hora_entrada && $asistencia->hora_salida) {
                        $entrada = \Carbon\Carbon::parse($asistencia->hora_entrada);
                        $salida = \Carbon\Carbon::parse($asistencia->hora_salida);
                        $asistencia->minutos_asistidos = $salida->diffInMinutes($entrada);
                        $asistencia->save();
                    }
                }

                // Marcar la sesión como registrada
                $sesion = SesionCapacitacion::find($this->sesionId);
                $sesion->update([
                    'asistencia_registrada' => true,
                    'registrada_por' => auth()->id(),
                    'registrada_at' => now(),
                ]);

                // Recalcular porcentaje de asistencia para cada inscripción
                $this->recalcularPorcentajesAsistencia();
            });

            $this->modalConfirmarGuardar = false;
            $this->dispatch('asistencias-guardadas', mensaje: 'Asistencias guardadas exitosamente');

            // Recargar asistencias
            $this->cargarAsistencias();
        } catch (\Exception $e) {
            $this->dispatch('error', mensaje: 'Error al guardar asistencias: ' . $e->getMessage());
        }
    }

    protected function recalcularPorcentajesAsistencia()
    {
        // Obtener todas las inscripciones de la actividad
        $inscripciones = InscripcionCapacitacion::where('actividad_id', $this->actividadId)
            ->whereIn('estado', ['pendiente', 'aprobada'])
            ->get();

        // Obtener total de sesiones de la actividad
        $totalSesiones = SesionCapacitacion::where('actividad_id', $this->actividadId)->count();

        if ($totalSesiones === 0) {
            return;
        }

        foreach ($inscripciones as $inscripcion) {
            // Contar asistencias presentes de esta inscripción
            $asistenciasPresentes = AsistenciaCapacitacion::whereHas('sesion', function ($query) {
                $query->where('actividad_id', $this->actividadId);
            })
                ->where('inscripcion_id', $inscripcion->id)
                ->where('presente', true)
                ->count();

            // Calcular porcentaje
            $porcentaje = ($asistenciasPresentes / $totalSesiones) * 100;

            // Actualizar inscripción
            $inscripcion->update([
                'porcentaje_asistencia' => round($porcentaje, 2),
            ]);
        }
    }

    public function abrirModalDetallesSesion()
    {
        $this->modalDetallesSesion = true;
    }

    public function updatingBusqueda()
    {
        // Trigger re-render cuando cambia la búsqueda
    }

    public function updatingMostrarSoloAusentes()
    {
        // Trigger re-render cuando cambia el filtro
    }

    public function render()
    {
        return view('livewire.capacitacion.control-asistencia');
    }
}
