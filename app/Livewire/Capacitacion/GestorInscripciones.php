<?php

namespace App\Livewire\Capacitacion;

use App\Enums\EstadoInscripcion;
use App\Enums\TipoInscripcion;
use App\Models\ActividadCapacitacion;
use App\Models\Enfermero;
use App\Models\InscripcionCapacitacion;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class GestorInscripciones extends Component
{
    use WithPagination;

    // Actividad seleccionada
    public $actividadId;

    // Filtros
    public $filtroEstado = '';
    public $busqueda = '';
    public $filtroArea = '';

    // Modales
    public $modalInscribir = false;
    public $modalInscribirMultiple = false;
    public $modalDetalles = false;
    public $modalCancelar = false;
    public $modalSesiones = false;

    // Datos del formulario
    public $inscripcionId = null;
    public $enfermeroSeleccionado = null;
    public $enfermerosSeleccionados = [];
    public $tipoInscripcion = 'manual';
    public $observaciones = '';
    public $motivoCancelacion = '';

    public function mount($actividadId = null)
    {
        $this->actividadId = $actividadId;
    }

    #[Computed]
    public function actividad()
    {
        return ActividadCapacitacion::with(['area', 'inscripciones.enfermero', 'sesiones'])
            ->withCount('inscripciones')
            ->findOrFail($this->actividadId);
    }

    #[Computed]
    public function inscripciones()
    {
        $query = InscripcionCapacitacion::with(['enfermero.user', 'inscritoPor', 'aprobadoPor'])
            ->where('actividad_id', $this->actividadId);

        // Aplicar filtros
        if ($this->filtroEstado) {
            $query->where('estado', $this->filtroEstado);
        }

        if ($this->busqueda) {
            $query->whereHas('enfermero.user', function ($q) {
                $q->where('name', 'like', "%{$this->busqueda}%")
                    ->orWhere('email', 'like', "%{$this->busqueda}%");
            });
        }

        return $query->orderBy('created_at', 'desc')
            ->paginate(15);
    }

    #[Computed]
    public function enfermerosDisponibles()
    {
        // Obtener IDs de enfermeros ya inscritos
        $inscritosIds = InscripcionCapacitacion::where('actividad_id', $this->actividadId)
            ->whereIn('estado', [EstadoInscripcion::PENDIENTE->value, EstadoInscripcion::APROBADA->value])
            ->pluck('enfermero_id');

        $query = Enfermero::with(['user', 'area'])
            ->whereNotIn('id', $inscritosIds);

        // Filtro por área
        if ($this->filtroArea) {
            $query->where('area_id', $this->filtroArea);
        }

        // Búsqueda
        if ($this->busqueda && $this->modalInscribir) {
            $query->whereHas('user', function ($q) {
                $q->where('name', 'like', "%{$this->busqueda}%")
                    ->orWhere('email', 'like', "%{$this->busqueda}%");
            });
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    #[Computed]
    public function estadisticas()
    {
        $total = InscripcionCapacitacion::where('actividad_id', $this->actividadId)->count();
        $aprobadas = InscripcionCapacitacion::where('actividad_id', $this->actividadId)
            ->where('estado', EstadoInscripcion::APROBADA->value)
            ->count();
        $pendientes = InscripcionCapacitacion::where('actividad_id', $this->actividadId)
            ->where('estado', EstadoInscripcion::PENDIENTE->value)
            ->count();
        $rechazadas = InscripcionCapacitacion::where('actividad_id', $this->actividadId)
            ->where('estado', EstadoInscripcion::RECHAZADA->value)
            ->count();

        return compact('total', 'aprobadas', 'pendientes', 'rechazadas');
    }

    public function abrirModalInscribir()
    {
        $this->reset(['enfermeroSeleccionado', 'observaciones']);
        $this->tipoInscripcion = TipoInscripcion::MANUAL->value;
        $this->modalInscribir = true;
    }

    public function abrirModalInscribirMultiple()
    {
        $this->reset(['enfermerosSeleccionados', 'observaciones']);
        $this->tipoInscripcion = TipoInscripcion::OBLIGATORIA->value;
        $this->modalInscribirMultiple = true;
    }

    public function inscribirEnfermero()
    {
        $this->validate([
            'enfermeroSeleccionado' => 'required|exists:enfermeros,id',
            'tipoInscripcion' => 'required|in:' . implode(',', array_column(TipoInscripcion::cases(), 'value')),
            'observaciones' => 'nullable|string|max:500',
        ]);

        try {
            $actividad = $this->actividad;

            // Validar cupos disponibles
            if (!$actividad->tieneCapoDisponible()) {
                $this->dispatch('error', mensaje: 'No hay cupos disponibles para esta actividad');
                return;
            }

            // Validar que puede inscribirse
            if (!$actividad->puedeInscribirse()) {
                $this->dispatch('error', mensaje: 'Esta actividad no está disponible para inscripciones');
                return;
            }

            DB::transaction(function () {
                InscripcionCapacitacion::create([
                    'actividad_id' => $this->actividadId,
                    'enfermero_id' => $this->enfermeroSeleccionado,
                    'tipo' => $this->tipoInscripcion,
                    'estado' => EstadoInscripcion::PENDIENTE->value,
                    'inscrito_por' => auth()->id(),
                    'observaciones' => $this->observaciones,
                ]);
            });

            $this->modalInscribir = false;
            $this->dispatch('inscripcion-creada', mensaje: 'Enfermero inscrito exitosamente');
            $this->resetPage();
        } catch (\Exception $e) {
            $this->dispatch('error', mensaje: 'Error al inscribir: ' . $e->getMessage());
        }
    }

    public function inscribirMultiples()
    {
        $this->validate([
            'enfermerosSeleccionados' => 'required|array|min:1',
            'enfermerosSeleccionados.*' => 'exists:enfermeros,id',
            'tipoInscripcion' => 'required|in:' . implode(',', array_column(TipoInscripcion::cases(), 'value')),
            'observaciones' => 'nullable|string|max:500',
        ]);

        try {
            $actividad = $this->actividad;

            // Validar cupos disponibles para todos
            $cuposRequeridos = count($this->enfermerosSeleccionados);
            if ($actividad->cupos_disponibles < $cuposRequeridos) {
                $this->dispatch('error', mensaje: "Solo hay {$actividad->cupos_disponibles} cupos disponibles. Necesitas {$cuposRequeridos}.");
                return;
            }

            // Validar que puede inscribirse
            if (!$actividad->puedeInscribirse()) {
                $this->dispatch('error', mensaje: 'Esta actividad no está disponible para inscripciones');
                return;
            }

            $inscritos = 0;
            DB::transaction(function () use (&$inscritos) {
                foreach ($this->enfermerosSeleccionados as $enfermeroId) {
                    // Verificar que no esté ya inscrito
                    $yaInscrito = InscripcionCapacitacion::where('actividad_id', $this->actividadId)
                        ->where('enfermero_id', $enfermeroId)
                        ->whereIn('estado', [EstadoInscripcion::PENDIENTE->value, EstadoInscripcion::APROBADA->value])
                        ->exists();

                    if (!$yaInscrito) {
                        InscripcionCapacitacion::create([
                            'actividad_id' => $this->actividadId,
                            'enfermero_id' => $enfermeroId,
                            'tipo' => $this->tipoInscripcion,
                            'estado' => EstadoInscripcion::PENDIENTE->value,
                            'inscrito_por' => auth()->id(),
                            'observaciones' => $this->observaciones,
                        ]);
                        $inscritos++;
                    }
                }
            });

            $this->modalInscribirMultiple = false;
            $this->dispatch('inscripcion-creada', mensaje: "{$inscritos} enfermeros inscritos exitosamente");
            $this->resetPage();
        } catch (\Exception $e) {
            $this->dispatch('error', mensaje: 'Error al inscribir: ' . $e->getMessage());
        }
    }

    public function toggleEnfermeroSeleccionado($enfermeroId)
    {
        if (in_array($enfermeroId, $this->enfermerosSeleccionados)) {
            $this->enfermerosSeleccionados = array_values(
                array_filter($this->enfermerosSeleccionados, fn($id) => $id != $enfermeroId)
            );
        } else {
            $this->enfermerosSeleccionados[] = $enfermeroId;
        }
    }

    public function abrirModalDetalles($inscripcionId)
    {
        $this->inscripcionId = $inscripcionId;
        $this->modalDetalles = true;
    }

    public function abrirModalCancelar($inscripcionId)
    {
        $this->inscripcionId = $inscripcionId;
        $this->reset(['motivoCancelacion']);
        $this->modalCancelar = true;
    }

    public function cancelarInscripcion()
    {
        $this->validate([
            'motivoCancelacion' => 'required|string|min:10|max:500',
        ]);

        try {
            $inscripcion = InscripcionCapacitacion::findOrFail($this->inscripcionId);

            if (!in_array($inscripcion->estado->value, [EstadoInscripcion::PENDIENTE->value, EstadoInscripcion::APROBADA->value])) {
                $this->dispatch('error', mensaje: 'Solo se pueden cancelar inscripciones pendientes o aprobadas');
                return;
            }

            $inscripcion->cancelar(auth()->id(), $this->motivoCancelacion);

            $this->modalCancelar = false;
            $this->dispatch('inscripcion-cancelada', mensaje: 'Inscripción cancelada exitosamente');
        } catch (\Exception $e) {
            $this->dispatch('error', mensaje: 'Error al cancelar inscripción: ' . $e->getMessage());
        }
    }

    public function aprobarInscripcion($inscripcionId)
    {
        try {
            $inscripcion = InscripcionCapacitacion::findOrFail($inscripcionId);

            if ($inscripcion->estado !== EstadoInscripcion::PENDIENTE) {
                $this->dispatch('error', mensaje: 'Solo se pueden aprobar inscripciones pendientes');
                return;
            }

            $inscripcion->aprobar(auth()->id(), 'Aprobada manualmente');

            $this->dispatch('inscripcion-aprobada', mensaje: 'Inscripción aprobada exitosamente');
        } catch (\Exception $e) {
            $this->dispatch('error', mensaje: 'Error al aprobar inscripción: ' . $e->getMessage());
        }
    }

    public function rechazarInscripcion($inscripcionId, $motivo)
    {
        try {
            $inscripcion = InscripcionCapacitacion::findOrFail($inscripcionId);

            if ($inscripcion->estado !== EstadoInscripcion::PENDIENTE) {
                $this->dispatch('error', mensaje: 'Solo se pueden rechazar inscripciones pendientes');
                return;
            }

            $inscripcion->rechazar(auth()->id(), $motivo);

            $this->dispatch('inscripcion-rechazada', mensaje: 'Inscripción rechazada');
        } catch (\Exception $e) {
            $this->dispatch('error', mensaje: 'Error al rechazar inscripción: ' . $e->getMessage());
        }
    }

    public function limpiarFiltros()
    {
        $this->reset(['filtroEstado', 'busqueda', 'filtroArea']);
        $this->resetPage();
    }

    public function updatingBusqueda()
    {
        $this->resetPage();
    }

    public function updatingFiltroEstado()
    {
        $this->resetPage();
    }

    public function updatingFiltroArea()
    {
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.capacitacion.gestor-inscripciones', [
            'inscripcionSeleccionada' => $this->inscripcionId ? InscripcionCapacitacion::with(['enfermero.user', 'actividad', 'asistencias.sesion'])->find($this->inscripcionId) : null,
        ]);
    }
}
