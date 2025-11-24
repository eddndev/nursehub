<?php

namespace App\Livewire\Capacitacion;

use App\Enums\EstadoActividad;
use App\Enums\EstadoInscripcion;
use App\Models\ActividadCapacitacion;
use App\Models\Certificacion;
use App\Models\Enfermero;
use App\Models\InscripcionCapacitacion;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class DashboardCapacitacion extends Component
{
    use WithPagination;

    // Filtros
    public $filtroTipo = '';
    public $filtroEstadoActividad = '';
    public $busqueda = '';
    public $vistaActual = 'disponibles'; // disponibles, mis-inscripciones, mis-certificaciones

    // Modales
    public $modalDetallesActividad = false;
    public $modalDetallesInscripcion = false;
    public $modalDetallesCertificacion = false;
    public $modalInscribirse = false;

    // IDs seleccionados
    public $actividadId = null;
    public $inscripcionId = null;
    public $certificacionId = null;

    // Datos del formulario
    public $observacionesInscripcion = '';

    #[Computed]
    public function enfermeroActual()
    {
        return Enfermero::where('user_id', auth()->id())->first();
    }

    #[Computed]
    public function actividadesDisponibles()
    {
        if ($this->vistaActual !== 'disponibles') {
            return collect();
        }

        $enfermero = $this->enfermeroActual;
        if (!$enfermero) {
            return collect();
        }

        // IDs de actividades ya inscritas
        $inscritosIds = InscripcionCapacitacion::where('enfermero_id', $enfermero->id)
            ->whereIn('estado', [EstadoInscripcion::PENDIENTE->value, EstadoInscripcion::APROBADA->value])
            ->pluck('actividad_id');

        $query = ActividadCapacitacion::with(['area', 'sesiones'])
            ->whereNotIn('id', $inscritosIds)
            ->where('estado', EstadoActividad::PUBLICADA->value)
            ->where('fecha_inicio', '>', now());

        // Aplicar filtros
        if ($this->filtroTipo) {
            $query->where('tipo', $this->filtroTipo);
        }

        if ($this->busqueda) {
            $query->where(function ($q) {
                $q->where('titulo', 'like', "%{$this->busqueda}%")
                    ->orWhere('descripcion', 'like', "%{$this->busqueda}%");
            });
        }

        return $query->orderBy('fecha_inicio', 'asc')
            ->paginate(9);
    }

    #[Computed]
    public function misInscripciones()
    {
        if ($this->vistaActual !== 'mis-inscripciones') {
            return collect();
        }

        $enfermero = $this->enfermeroActual;
        if (!$enfermero) {
            return collect();
        }

        $query = InscripcionCapacitacion::with(['actividad.area', 'actividad.sesiones', 'asistencias'])
            ->where('enfermero_id', $enfermero->id);

        // Filtro por estado de actividad
        if ($this->filtroEstadoActividad === 'en_curso') {
            $query->whereHas('actividad', function ($q) {
                $q->where('estado', EstadoActividad::EN_CURSO->value);
            });
        } elseif ($this->filtroEstadoActividad === 'finalizada') {
            $query->whereHas('actividad', function ($q) {
                $q->where('estado', EstadoActividad::FINALIZADA->value);
            });
        }

        // Búsqueda
        if ($this->busqueda) {
            $query->whereHas('actividad', function ($q) {
                $q->where('titulo', 'like', "%{$this->busqueda}%");
            });
        }

        return $query->orderBy('created_at', 'desc')
            ->paginate(12);
    }

    #[Computed]
    public function misCertificaciones()
    {
        if ($this->vistaActual !== 'mis-certificaciones') {
            return collect();
        }

        $enfermero = $this->enfermeroActual;
        if (!$enfermero) {
            return collect();
        }

        $query = Certificacion::with(['inscripcion.actividad', 'inscripcion.enfermero.user'])
            ->whereHas('inscripcion', function ($q) use ($enfermero) {
                $q->where('enfermero_id', $enfermero->id);
            });

        // Búsqueda
        if ($this->busqueda) {
            $query->where(function ($q) {
                $q->where('numero_certificado', 'like', "%{$this->busqueda}%")
                    ->orWhereHas('inscripcion.actividad', function ($sq) {
                        $sq->where('titulo', 'like', "%{$this->busqueda}%");
                    });
            });
        }

        return $query->orderBy('fecha_emision', 'desc')
            ->paginate(12);
    }

    #[Computed]
    public function estadisticas()
    {
        $enfermero = $this->enfermeroActual;
        if (!$enfermero) {
            return [
                'total_inscripciones' => 0,
                'inscripciones_aprobadas' => 0,
                'inscripciones_pendientes' => 0,
                'certificaciones_totales' => 0,
                'certificaciones_vigentes' => 0,
                'horas_acumuladas' => 0,
                'actividades_disponibles' => 0,
            ];
        }

        $totalInscripciones = InscripcionCapacitacion::where('enfermero_id', $enfermero->id)->count();

        $inscripcionesAprobadas = InscripcionCapacitacion::where('enfermero_id', $enfermero->id)
            ->where('estado', EstadoInscripcion::APROBADA->value)
            ->count();

        $inscripcionesPendientes = InscripcionCapacitacion::where('enfermero_id', $enfermero->id)
            ->where('estado', EstadoInscripcion::PENDIENTE->value)
            ->count();

        $certificacionesTotales = Certificacion::whereHas('inscripcion', function ($q) use ($enfermero) {
            $q->where('enfermero_id', $enfermero->id);
        })->count();

        $certificacionesVigentes = Certificacion::whereHas('inscripcion', function ($q) use ($enfermero) {
            $q->where('enfermero_id', $enfermero->id);
        })
        ->where(function ($q) {
            $q->whereNull('fecha_vigencia_fin')
                ->orWhere('fecha_vigencia_fin', '>', now());
        })
        ->count();

        $horasAcumuladas = Certificacion::whereHas('inscripcion', function ($q) use ($enfermero) {
            $q->where('enfermero_id', $enfermero->id);
        })->sum('horas_certificadas');

        $actividadesDisponibles = ActividadCapacitacion::where('estado', EstadoActividad::PUBLICADA->value)
            ->where('fecha_inicio', '>', now())
            ->whereDoesntHave('inscripciones', function ($q) use ($enfermero) {
                $q->where('enfermero_id', $enfermero->id)
                    ->whereIn('estado', [EstadoInscripcion::PENDIENTE->value, EstadoInscripcion::APROBADA->value]);
            })
            ->count();

        return compact(
            'totalInscripciones',
            'inscripcionesAprobadas',
            'inscripcionesPendientes',
            'certificacionesTotales',
            'certificacionesVigentes',
            'horasAcumuladas',
            'actividadesDisponibles'
        );
    }

    public function cambiarVista($vista)
    {
        $this->vistaActual = $vista;
        $this->reset(['filtroTipo', 'filtroEstadoActividad', 'busqueda']);
        $this->resetPage();
    }

    public function abrirModalDetallesActividad($actividadId)
    {
        $this->actividadId = $actividadId;
        $this->modalDetallesActividad = true;
    }

    public function abrirModalInscribirse($actividadId)
    {
        $this->actividadId = $actividadId;
        $this->reset(['observacionesInscripcion']);
        $this->modalInscribirse = true;
    }

    public function inscribirse()
    {
        $this->validate([
            'observacionesInscripcion' => 'nullable|string|max:500',
        ]);

        try {
            $enfermero = $this->enfermeroActual;
            $actividad = ActividadCapacitacion::findOrFail($this->actividadId);

            // Validar que está publicada
            if ($actividad->estado !== EstadoActividad::PUBLICADA) {
                $this->dispatch('error', mensaje: 'Esta actividad no está disponible para inscripciones');
                return;
            }

            // Validar cupos disponibles
            if (!$actividad->tieneCapoDisponible()) {
                $this->dispatch('error', mensaje: 'No hay cupos disponibles');
                return;
            }

            // Validar que no esté ya inscrito
            $yaInscrito = InscripcionCapacitacion::where('actividad_id', $actividad->id)
                ->where('enfermero_id', $enfermero->id)
                ->whereIn('estado', [EstadoInscripcion::PENDIENTE->value, EstadoInscripcion::APROBADA->value])
                ->exists();

            if ($yaInscrito) {
                $this->dispatch('error', mensaje: 'Ya estás inscrito en esta actividad');
                return;
            }

            DB::transaction(function () use ($enfermero) {
                InscripcionCapacitacion::create([
                    'actividad_id' => $this->actividadId,
                    'enfermero_id' => $enfermero->id,
                    'tipo' => 'voluntaria',
                    'estado' => EstadoInscripcion::PENDIENTE->value,
                    'inscrito_por' => auth()->id(),
                    'observaciones' => $this->observacionesInscripcion,
                ]);
            });

            $this->modalInscribirse = false;
            $this->dispatch('inscripcion-exitosa', mensaje: 'Te has inscrito exitosamente. Tu inscripción está pendiente de aprobación.');
            $this->resetPage();
        } catch (\Exception $e) {
            $this->dispatch('error', mensaje: 'Error al inscribirse: ' . $e->getMessage());
        }
    }

    public function abrirModalDetallesInscripcion($inscripcionId)
    {
        $this->inscripcionId = $inscripcionId;
        $this->modalDetallesInscripcion = true;
    }

    public function abrirModalDetallesCertificacion($certificacionId)
    {
        $this->certificacionId = $certificacionId;
        $this->modalDetallesCertificacion = true;
    }

    public function cancelarInscripcion($inscripcionId)
    {
        try {
            $inscripcion = InscripcionCapacitacion::findOrFail($inscripcionId);
            $enfermero = $this->enfermeroActual;

            // Validar que sea su inscripción
            if ($inscripcion->enfermero_id !== $enfermero->id) {
                $this->dispatch('error', mensaje: 'No puedes cancelar esta inscripción');
                return;
            }

            // Validar que pueda cancelarse
            if (!in_array($inscripcion->estado->value, [EstadoInscripcion::PENDIENTE->value])) {
                $this->dispatch('error', mensaje: 'Solo puedes cancelar inscripciones pendientes');
                return;
            }

            $inscripcion->cancelar(auth()->id(), 'Cancelada por el enfermero');

            $this->dispatch('inscripcion-cancelada', mensaje: 'Inscripción cancelada exitosamente');
            $this->resetPage();
        } catch (\Exception $e) {
            $this->dispatch('error', mensaje: 'Error al cancelar: ' . $e->getMessage());
        }
    }

    public function limpiarFiltros()
    {
        $this->reset(['filtroTipo', 'filtroEstadoActividad', 'busqueda']);
        $this->resetPage();
    }

    public function updatingBusqueda()
    {
        $this->resetPage();
    }

    public function updatingFiltroTipo()
    {
        $this->resetPage();
    }

    public function updatingFiltroEstadoActividad()
    {
        $this->resetPage();
    }

    public function render()
    {
        $actividadSeleccionada = $this->actividadId
            ? ActividadCapacitacion::with(['area', 'sesiones', 'inscripciones'])->find($this->actividadId)
            : null;

        $inscripcionSeleccionada = $this->inscripcionId
            ? InscripcionCapacitacion::with(['actividad.area', 'actividad.sesiones', 'asistencias.sesion', 'enfermero.user'])->find($this->inscripcionId)
            : null;

        $certificacionSeleccionada = $this->certificacionId
            ? Certificacion::with(['inscripcion.actividad', 'inscripcion.enfermero.user', 'emitidoPor'])->find($this->certificacionId)
            : null;

        return view('livewire.capacitacion.dashboard-capacitacion', [
            'actividadSeleccionada' => $actividadSeleccionada,
            'inscripcionSeleccionada' => $inscripcionSeleccionada,
            'certificacionSeleccionada' => $certificacionSeleccionada,
        ]);
    }
}
