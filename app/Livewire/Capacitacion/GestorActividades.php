<?php

namespace App\Livewire\Capacitacion;

use App\Enums\EstadoActividad;
use App\Enums\TipoActividad;
use App\Models\ActividadCapacitacion;
use App\Models\Area;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class GestorActividades extends Component
{
    use WithPagination;

    // Filtros
    public $filtroEstado = '';
    public $filtroTipo = '';
    public $filtroArea = '';
    public $busqueda = '';

    // Modales
    public $modalCrear = false;
    public $modalEditar = false;
    public $modalEliminar = false;
    public $modalDetalles = false;

    // Datos del formulario
    public $actividadId = null;
    public $titulo = '';
    public $descripcion = '';
    public $tipo = '';
    public $estado = '';
    public $modalidad = 'presencial';
    public $ubicacion = '';
    public $url_virtual = '';
    public $duracion_horas = '';
    public $cupo_minimo = 5;
    public $cupo_maximo = 30;
    public $fecha_inicio = '';
    public $fecha_fin = '';
    public $hora_inicio = '';
    public $hora_fin = '';
    public $fecha_limite_inscripcion = '';
    public $porcentaje_asistencia_minimo = 80.00;
    public $calificacion_minima_aprobacion = '';
    public $otorga_certificado = true;
    public $instructor_nombre = '';
    public $instructor_credenciales = '';
    public $objetivos = '';
    public $contenido_tematico = '';
    public $recursos_necesarios = '';
    public $evaluacion_metodo = '';
    public $notas_adicionales = '';
    public $area_id = '';

    protected function rules()
    {
        return [
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'tipo' => 'required|in:' . implode(',', array_column(TipoActividad::cases(), 'value')),
            'estado' => 'nullable|in:' . implode(',', array_column(EstadoActividad::cases(), 'value')),
            'modalidad' => 'required|in:presencial,virtual,hibrida',
            'ubicacion' => 'nullable|string|max:255',
            'url_virtual' => 'nullable|url|max:255',
            'duracion_horas' => 'required|integer|min:1|max:200',
            'cupo_minimo' => 'required|integer|min:1|max:100',
            'cupo_maximo' => 'required|integer|min:1|max:200|gte:cupo_minimo',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'hora_inicio' => 'nullable|date_format:H:i',
            'hora_fin' => 'nullable|date_format:H:i|after:hora_inicio',
            'fecha_limite_inscripcion' => 'nullable|date|before_or_equal:fecha_inicio',
            'porcentaje_asistencia_minimo' => 'required|numeric|min:0|max:100',
            'calificacion_minima_aprobacion' => 'nullable|numeric|min:0|max:100',
            'otorga_certificado' => 'boolean',
            'instructor_nombre' => 'nullable|string|max:255',
            'instructor_credenciales' => 'nullable|string|max:255',
            'objetivos' => 'nullable|string',
            'contenido_tematico' => 'nullable|string',
            'recursos_necesarios' => 'nullable|string',
            'evaluacion_metodo' => 'nullable|string',
            'notas_adicionales' => 'nullable|string',
            'area_id' => 'nullable|exists:areas,id',
        ];
    }

    #[Computed]
    public function actividades()
    {
        $query = ActividadCapacitacion::with(['area', 'creadoPor', 'inscripciones'])
            ->withCount('inscripciones');

        // Aplicar filtros
        if ($this->filtroEstado) {
            $query->where('estado', $this->filtroEstado);
        }

        if ($this->filtroTipo) {
            $query->where('tipo', $this->filtroTipo);
        }

        if ($this->filtroArea) {
            $query->where('area_id', $this->filtroArea);
        }

        if ($this->busqueda) {
            $query->where(function ($q) {
                $q->where('titulo', 'like', "%{$this->busqueda}%")
                    ->orWhere('descripcion', 'like', "%{$this->busqueda}%")
                    ->orWhere('instructor_nombre', 'like', "%{$this->busqueda}%");
            });
        }

        return $query->orderBy('fecha_inicio', 'desc')
            ->paginate(10);
    }

    #[Computed]
    public function areas()
    {
        return Area::orderBy('nombre')->get();
    }

    #[Computed]
    public function estadisticas()
    {
        return [
            'total' => ActividadCapacitacion::count(),
            'activas' => ActividadCapacitacion::activas()->count(),
            'inscripciones_abiertas' => ActividadCapacitacion::inscripcionesAbiertas()->count(),
            'proximas' => ActividadCapacitacion::proximas()->count(),
        ];
    }

    public function abrirModalCrear()
    {
        $this->reset([
            'actividadId', 'titulo', 'descripcion', 'tipo', 'estado',
            'modalidad', 'ubicacion', 'url_virtual', 'duracion_horas',
            'cupo_minimo', 'cupo_maximo', 'fecha_inicio', 'fecha_fin',
            'hora_inicio', 'hora_fin', 'fecha_limite_inscripcion',
            'porcentaje_asistencia_minimo', 'calificacion_minima_aprobacion',
            'otorga_certificado', 'instructor_nombre', 'instructor_credenciales',
            'objetivos', 'contenido_tematico', 'recursos_necesarios',
            'evaluacion_metodo', 'notas_adicionales', 'area_id'
        ]);

        $this->estado = EstadoActividad::PLANIFICADA->value;
        $this->otorga_certificado = true;
        $this->porcentaje_asistencia_minimo = 80.00;
        $this->cupo_minimo = 5;
        $this->cupo_maximo = 30;
        $this->modalCrear = true;
    }

    public function crearActividad()
    {
        $this->validate();

        try {
            DB::transaction(function () {
                ActividadCapacitacion::create([
                    'titulo' => $this->titulo,
                    'descripcion' => $this->descripcion,
                    'tipo' => $this->tipo,
                    'estado' => $this->estado ?: EstadoActividad::PLANIFICADA->value,
                    'modalidad' => $this->modalidad,
                    'ubicacion' => $this->ubicacion,
                    'url_virtual' => $this->url_virtual,
                    'duracion_horas' => $this->duracion_horas,
                    'cupo_minimo' => $this->cupo_minimo,
                    'cupo_maximo' => $this->cupo_maximo,
                    'fecha_inicio' => $this->fecha_inicio,
                    'fecha_fin' => $this->fecha_fin,
                    'hora_inicio' => $this->hora_inicio,
                    'hora_fin' => $this->hora_fin,
                    'fecha_limite_inscripcion' => $this->fecha_limite_inscripcion,
                    'porcentaje_asistencia_minimo' => $this->porcentaje_asistencia_minimo,
                    'calificacion_minima_aprobacion' => $this->calificacion_minima_aprobacion,
                    'otorga_certificado' => $this->otorga_certificado,
                    'instructor_nombre' => $this->instructor_nombre,
                    'instructor_credenciales' => $this->instructor_credenciales,
                    'objetivos' => $this->objetivos,
                    'contenido_tematico' => $this->contenido_tematico,
                    'recursos_necesarios' => $this->recursos_necesarios,
                    'evaluacion_metodo' => $this->evaluacion_metodo,
                    'notas_adicionales' => $this->notas_adicionales,
                    'area_id' => $this->area_id,
                    'creado_por' => auth()->id(),
                ]);
            });

            $this->modalCrear = false;
            $this->dispatch('actividad-creada', mensaje: 'Actividad de capacitación creada exitosamente');
            $this->resetPage();
        } catch (\Exception $e) {
            $this->dispatch('error', mensaje: 'Error al crear la actividad: ' . $e->getMessage());
        }
    }

    public function abrirModalEditar($actividadId)
    {
        $actividad = ActividadCapacitacion::findOrFail($actividadId);

        $this->actividadId = $actividad->id;
        $this->titulo = $actividad->titulo;
        $this->descripcion = $actividad->descripcion;
        $this->tipo = $actividad->tipo->value;
        $this->estado = $actividad->estado->value;
        $this->modalidad = $actividad->modalidad;
        $this->ubicacion = $actividad->ubicacion;
        $this->url_virtual = $actividad->url_virtual;
        $this->duracion_horas = $actividad->duracion_horas;
        $this->cupo_minimo = $actividad->cupo_minimo;
        $this->cupo_maximo = $actividad->cupo_maximo;
        $this->fecha_inicio = $actividad->fecha_inicio->format('Y-m-d');
        $this->fecha_fin = $actividad->fecha_fin->format('Y-m-d');
        $this->hora_inicio = $actividad->hora_inicio;
        $this->hora_fin = $actividad->hora_fin;
        $this->fecha_limite_inscripcion = $actividad->fecha_limite_inscripcion?->format('Y-m-d');
        $this->porcentaje_asistencia_minimo = $actividad->porcentaje_asistencia_minimo;
        $this->calificacion_minima_aprobacion = $actividad->calificacion_minima_aprobacion;
        $this->otorga_certificado = $actividad->otorga_certificado;
        $this->instructor_nombre = $actividad->instructor_nombre;
        $this->instructor_credenciales = $actividad->instructor_credenciales;
        $this->objetivos = $actividad->objetivos;
        $this->contenido_tematico = $actividad->contenido_tematico;
        $this->recursos_necesarios = $actividad->recursos_necesarios;
        $this->evaluacion_metodo = $actividad->evaluacion_metodo;
        $this->notas_adicionales = $actividad->notas_adicionales;
        $this->area_id = $actividad->area_id;

        $this->modalEditar = true;
    }

    public function actualizarActividad()
    {
        $this->validate();

        try {
            $actividad = ActividadCapacitacion::findOrFail($this->actividadId);

            DB::transaction(function () use ($actividad) {
                $actividad->update([
                    'titulo' => $this->titulo,
                    'descripcion' => $this->descripcion,
                    'tipo' => $this->tipo,
                    'estado' => $this->estado,
                    'modalidad' => $this->modalidad,
                    'ubicacion' => $this->ubicacion,
                    'url_virtual' => $this->url_virtual,
                    'duracion_horas' => $this->duracion_horas,
                    'cupo_minimo' => $this->cupo_minimo,
                    'cupo_maximo' => $this->cupo_maximo,
                    'fecha_inicio' => $this->fecha_inicio,
                    'fecha_fin' => $this->fecha_fin,
                    'hora_inicio' => $this->hora_inicio,
                    'hora_fin' => $this->hora_fin,
                    'fecha_limite_inscripcion' => $this->fecha_limite_inscripcion,
                    'porcentaje_asistencia_minimo' => $this->porcentaje_asistencia_minimo,
                    'calificacion_minima_aprobacion' => $this->calificacion_minima_aprobacion,
                    'otorga_certificado' => $this->otorga_certificado,
                    'instructor_nombre' => $this->instructor_nombre,
                    'instructor_credenciales' => $this->instructor_credenciales,
                    'objetivos' => $this->objetivos,
                    'contenido_tematico' => $this->contenido_tematico,
                    'recursos_necesarios' => $this->recursos_necesarios,
                    'evaluacion_metodo' => $this->evaluacion_metodo,
                    'notas_adicionales' => $this->notas_adicionales,
                    'area_id' => $this->area_id,
                ]);
            });

            $this->modalEditar = false;
            $this->dispatch('actividad-actualizada', mensaje: 'Actividad actualizada exitosamente');
        } catch (\Exception $e) {
            $this->dispatch('error', mensaje: 'Error al actualizar la actividad: ' . $e->getMessage());
        }
    }

    public function abrirModalEliminar($actividadId)
    {
        $this->actividadId = $actividadId;
        $this->modalEliminar = true;
    }

    public function eliminarActividad()
    {
        try {
            $actividad = ActividadCapacitacion::findOrFail($this->actividadId);

            // Verificar que no tenga inscripciones activas
            $inscripcionesActivas = $actividad->inscripciones()
                ->whereIn('estado', ['aprobada', 'pendiente'])
                ->count();

            if ($inscripcionesActivas > 0) {
                $this->dispatch('error', mensaje: 'No se puede eliminar una actividad con inscripciones activas');
                return;
            }

            $actividad->delete();

            $this->modalEliminar = false;
            $this->dispatch('actividad-eliminada', mensaje: 'Actividad eliminada exitosamente');
            $this->resetPage();
        } catch (\Exception $e) {
            $this->dispatch('error', mensaje: 'Error al eliminar la actividad: ' . $e->getMessage());
        }
    }

    public function cambiarEstado($actividadId, $nuevoEstado)
    {
        try {
            $actividad = ActividadCapacitacion::findOrFail($actividadId);

            $actividad->update([
                'estado' => $nuevoEstado,
            ]);

            $this->dispatch('estado-cambiado', mensaje: 'Estado actualizado exitosamente');
        } catch (\Exception $e) {
            $this->dispatch('error', mensaje: 'Error al cambiar el estado: ' . $e->getMessage());
        }
    }

    public function abrirModalDetalles($actividadId)
    {
        $this->actividadId = $actividadId;
        $this->modalDetalles = true;
    }

    public function limpiarFiltros()
    {
        $this->reset(['filtroEstado', 'filtroTipo', 'filtroArea', 'busqueda']);
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

    public function updatingFiltroTipo()
    {
        $this->resetPage();
    }

    public function updatingFiltroArea()
    {
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.capacitacion.gestor-actividades', [
            'actividadSeleccionada' => $this->actividadId ? ActividadCapacitacion::with(['area', 'inscripciones', 'sesiones'])->find($this->actividadId) : null,
        ]);
    }
}
