<?php

namespace App\Livewire\Capacitacion;

use App\Enums\EstadoInscripcion;
use App\Models\ActividadCapacitacion;
use App\Models\Certificacion;
use App\Models\InscripcionCapacitacion;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class GestorAprobaciones extends Component
{
    use WithPagination;

    // Identificador
    public $actividadId;

    // Filtros
    public $filtroEstado = 'pendiente';
    public $filtroCumpleCriterio = '';
    public $busqueda = '';

    // Modales
    public $modalAprobar = false;
    public $modalReprobar = false;
    public $modalAprobarMasivo = false;
    public $modalDetallesCertificacion = false;

    // Datos del formulario
    public $inscripcionId = null;
    public $inscripcionesSeleccionadas = [];
    public $motivoReprobacion = '';
    public $observacionesAprobacion = '';
    public $certificacionId = null;

    // Configuración de certificación
    public $mesesVigencia = 12;
    public $competenciasDesarrolladas = '';

    public function mount($actividadId)
    {
        $this->actividadId = $actividadId;
    }

    #[Computed]
    public function actividad()
    {
        return ActividadCapacitacion::with(['inscripciones', 'sesiones'])
            ->findOrFail($this->actividadId);
    }

    #[Computed]
    public function inscripciones()
    {
        $query = InscripcionCapacitacion::with(['enfermero.user', 'enfermero.area', 'certificacion'])
            ->where('actividad_id', $this->actividadId);

        // Filtro por estado
        if ($this->filtroEstado === 'pendiente') {
            $query->where('estado', EstadoInscripcion::PENDIENTE->value);
        } elseif ($this->filtroEstado === 'aprobada') {
            $query->where('estado', EstadoInscripcion::APROBADA->value);
        } elseif ($this->filtroEstado === 'reprobada') {
            $query->where('estado', EstadoInscripcion::REPROBADA->value);
        }

        // Filtro por cumplimiento de criterio
        if ($this->filtroCumpleCriterio === 'cumple') {
            $query->whereRaw('porcentaje_asistencia >= (SELECT porcentaje_asistencia_minimo FROM actividades_capacitacion WHERE id = ?)', [$this->actividadId]);
        } elseif ($this->filtroCumpleCriterio === 'no_cumple') {
            $query->whereRaw('porcentaje_asistencia < (SELECT porcentaje_asistencia_minimo FROM actividades_capacitacion WHERE id = ?)', [$this->actividadId]);
        }

        // Búsqueda
        if ($this->busqueda) {
            $query->whereHas('enfermero.user', function ($q) {
                $q->where('name', 'like', "%{$this->busqueda}%")
                    ->orWhere('email', 'like', "%{$this->busqueda}%");
            });
        }

        return $query->orderBy('porcentaje_asistencia', 'desc')
            ->paginate(15);
    }

    #[Computed]
    public function estadisticas()
    {
        $total = InscripcionCapacitacion::where('actividad_id', $this->actividadId)->count();

        $pendientes = InscripcionCapacitacion::where('actividad_id', $this->actividadId)
            ->where('estado', EstadoInscripcion::PENDIENTE->value)
            ->count();

        $aprobadas = InscripcionCapacitacion::where('actividad_id', $this->actividadId)
            ->where('estado', EstadoInscripcion::APROBADA->value)
            ->count();

        $reprobadas = InscripcionCapacitacion::where('actividad_id', $this->actividadId)
            ->where('estado', EstadoInscripcion::REPROBADA->value)
            ->count();

        $cumplenCriterio = InscripcionCapacitacion::where('actividad_id', $this->actividadId)
            ->whereRaw('porcentaje_asistencia >= (SELECT porcentaje_asistencia_minimo FROM actividades_capacitacion WHERE id = ?)', [$this->actividadId])
            ->count();

        $certificacionesGeneradas = Certificacion::whereHas('inscripcion', function ($q) {
            $q->where('actividad_id', $this->actividadId);
        })->count();

        return compact('total', 'pendientes', 'aprobadas', 'reprobadas', 'cumplenCriterio', 'certificacionesGeneradas');
    }

    public function toggleInscripcionSeleccionada($inscripcionId)
    {
        if (in_array($inscripcionId, $this->inscripcionesSeleccionadas)) {
            $this->inscripcionesSeleccionadas = array_values(
                array_filter($this->inscripcionesSeleccionadas, fn($id) => $id != $inscripcionId)
            );
        } else {
            $this->inscripcionesSeleccionadas[] = $inscripcionId;
        }
    }

    public function seleccionarTodasQueCumplen()
    {
        $inscripciones = InscripcionCapacitacion::where('actividad_id', $this->actividadId)
            ->where('estado', EstadoInscripcion::PENDIENTE->value)
            ->whereRaw('porcentaje_asistencia >= (SELECT porcentaje_asistencia_minimo FROM actividades_capacitacion WHERE id = ?)', [$this->actividadId])
            ->pluck('id')
            ->toArray();

        $this->inscripcionesSeleccionadas = $inscripciones;
        $this->dispatch('success', mensaje: count($inscripciones) . ' inscripciones seleccionadas');
    }

    public function abrirModalAprobar($inscripcionId)
    {
        $this->inscripcionId = $inscripcionId;
        $this->reset(['observacionesAprobacion', 'mesesVigencia', 'competenciasDesarrolladas']);
        $this->mesesVigencia = 12;
        $this->modalAprobar = true;
    }

    public function aprobarInscripcion()
    {
        $this->validate([
            'observacionesAprobacion' => 'nullable|string|max:500',
            'mesesVigencia' => 'required|integer|min:1|max:120',
            'competenciasDesarrolladas' => 'nullable|string|max:1000',
        ]);

        try {
            $inscripcion = InscripcionCapacitacion::findOrFail($this->inscripcionId);

            // Validar que esté pendiente
            if ($inscripcion->estado !== EstadoInscripcion::PENDIENTE) {
                $this->dispatch('error', mensaje: 'Solo se pueden aprobar inscripciones pendientes');
                return;
            }

            // Validar que cumpla el criterio
            if (!$inscripcion->cumpleAsistenciaMinima()) {
                $this->dispatch('error', mensaje: 'Esta inscripción no cumple con el porcentaje mínimo de asistencia');
                return;
            }

            DB::transaction(function () use ($inscripcion) {
                // Aprobar inscripción
                $inscripcion->aprobar(auth()->id(), $this->observacionesAprobacion ?: 'Aprobada por cumplir criterios');

                // Generar certificación
                $this->generarCertificacion($inscripcion);
            });

            $this->modalAprobar = false;
            $this->dispatch('inscripcion-aprobada', mensaje: 'Inscripción aprobada y certificación generada exitosamente');
            $this->resetPage();
        } catch (\Exception $e) {
            $this->dispatch('error', mensaje: 'Error al aprobar: ' . $e->getMessage());
        }
    }

    public function abrirModalAprobarMasivo()
    {
        if (empty($this->inscripcionesSeleccionadas)) {
            $this->dispatch('error', mensaje: 'Debes seleccionar al menos una inscripción');
            return;
        }

        $this->reset(['observacionesAprobacion', 'mesesVigencia', 'competenciasDesarrolladas']);
        $this->mesesVigencia = 12;
        $this->modalAprobarMasivo = true;
    }

    public function aprobarMasivo()
    {
        $this->validate([
            'observacionesAprobacion' => 'nullable|string|max:500',
            'mesesVigencia' => 'required|integer|min:1|max:120',
            'competenciasDesarrolladas' => 'nullable|string|max:1000',
        ]);

        try {
            $aprobadas = 0;
            $errores = 0;

            DB::transaction(function () use (&$aprobadas, &$errores) {
                foreach ($this->inscripcionesSeleccionadas as $inscripcionId) {
                    $inscripcion = InscripcionCapacitacion::find($inscripcionId);

                    if (!$inscripcion || $inscripcion->estado !== EstadoInscripcion::PENDIENTE) {
                        $errores++;
                        continue;
                    }

                    if (!$inscripcion->cumpleAsistenciaMinima()) {
                        $errores++;
                        continue;
                    }

                    // Aprobar inscripción
                    $inscripcion->aprobar(auth()->id(), $this->observacionesAprobacion ?: 'Aprobada masivamente');

                    // Generar certificación
                    $this->generarCertificacion($inscripcion);

                    $aprobadas++;
                }
            });

            $this->inscripcionesSeleccionadas = [];
            $this->modalAprobarMasivo = false;

            if ($aprobadas > 0) {
                $mensaje = "{$aprobadas} inscripciones aprobadas y certificaciones generadas";
                if ($errores > 0) {
                    $mensaje .= " ({$errores} inscripciones no pudieron ser aprobadas)";
                }
                $this->dispatch('aprobacion-masiva-completada', mensaje: $mensaje);
            } else {
                $this->dispatch('error', mensaje: 'No se pudo aprobar ninguna inscripción');
            }

            $this->resetPage();
        } catch (\Exception $e) {
            $this->dispatch('error', mensaje: 'Error en aprobación masiva: ' . $e->getMessage());
        }
    }

    protected function generarCertificacion(InscripcionCapacitacion $inscripcion)
    {
        // Generar número de certificado único
        $numeroCertificado = Certificacion::generarNumeroCertificado();

        // Generar hash de verificación
        $hashVerificacion = Certificacion::generarHashVerificacion($inscripcion, $numeroCertificado);

        // Calcular fechas de vigencia
        $fechaEmision = now();
        $fechaVigenciaInicio = $fechaEmision->copy();
        $fechaVigenciaFin = $this->mesesVigencia > 0
            ? $fechaEmision->copy()->addMonths($this->mesesVigencia)
            : null;

        // Crear certificación
        Certificacion::create([
            'inscripcion_id' => $inscripcion->id,
            'numero_certificado' => $numeroCertificado,
            'fecha_emision' => $fechaEmision,
            'fecha_vigencia_inicio' => $fechaVigenciaInicio,
            'fecha_vigencia_fin' => $fechaVigenciaFin,
            'horas_certificadas' => $inscripcion->actividad->duracion_horas,
            'calificacion_obtenida' => $inscripcion->calificacion_final,
            'porcentaje_asistencia' => $inscripcion->porcentaje_asistencia,
            'competencias_desarrolladas' => $this->competenciasDesarrolladas,
            'observaciones' => $this->observacionesAprobacion,
            'hash_verificacion' => $hashVerificacion,
            'emitido_por' => auth()->id(),
            'emitido_at' => now(),
        ]);
    }

    public function abrirModalReprobar($inscripcionId)
    {
        $this->inscripcionId = $inscripcionId;
        $this->reset(['motivoReprobacion']);
        $this->modalReprobar = true;
    }

    public function reprobarInscripcion()
    {
        $this->validate([
            'motivoReprobacion' => 'required|string|min:10|max:500',
        ]);

        try {
            $inscripcion = InscripcionCapacitacion::findOrFail($this->inscripcionId);

            if ($inscripcion->estado !== EstadoInscripcion::PENDIENTE) {
                $this->dispatch('error', mensaje: 'Solo se pueden reprobar inscripciones pendientes');
                return;
            }

            $inscripcion->rechazar(auth()->id(), $this->motivoReprobacion);

            $this->modalReprobar = false;
            $this->dispatch('inscripcion-reprobada', mensaje: 'Inscripción reprobada exitosamente');
            $this->resetPage();
        } catch (\Exception $e) {
            $this->dispatch('error', mensaje: 'Error al reprobar: ' . $e->getMessage());
        }
    }

    public function abrirModalDetallesCertificacion($certificacionId)
    {
        $this->certificacionId = $certificacionId;
        $this->modalDetallesCertificacion = true;
    }

    public function regenerarCertificacion($certificacionId)
    {
        try {
            $certificacion = Certificacion::findOrFail($certificacionId);

            // Aquí iría la lógica de regeneración de PDF
            // Por ahora solo actualizamos la fecha de emisión
            $certificacion->update([
                'emitido_at' => now(),
                'emitido_por' => auth()->id(),
            ]);

            $this->dispatch('certificacion-regenerada', mensaje: 'Certificación regenerada exitosamente');
        } catch (\Exception $e) {
            $this->dispatch('error', mensaje: 'Error al regenerar: ' . $e->getMessage());
        }
    }

    public function limpiarFiltros()
    {
        $this->reset(['filtroEstado', 'filtroCumpleCriterio', 'busqueda']);
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

    public function updatingFiltroCumpleCriterio()
    {
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.capacitacion.gestor-aprobaciones', [
            'certificacionSeleccionada' => $this->certificacionId ? Certificacion::with(['inscripcion.enfermero.user', 'inscripcion.actividad', 'emitidoPor'])->find($this->certificacionId) : null,
        ]);
    }
}
