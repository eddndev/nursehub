<?php

namespace App\Livewire\Capacitacion;

use App\Enums\EstadoInscripcion;
use App\Jobs\GenerarCertificacionesMasivas;
use App\Models\ActividadCapacitacion;
use App\Models\Certificacion;
use App\Models\InscripcionCapacitacion;
use App\Notifications\CertificacionGeneradaNotification;
use App\Notifications\InscripcionAprobadaNotification;
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

    // Campos de evaluación
    public $calificacionEvaluacion = null;
    public $retroalimentacion = '';

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
        $this->reset(['observacionesAprobacion', 'mesesVigencia', 'competenciasDesarrolladas', 'calificacionEvaluacion', 'retroalimentacion']);
        $this->mesesVigencia = 12;
        $this->calificacionEvaluacion = null;
        $this->modalAprobar = true;
    }

    public function aprobarInscripcion()
    {
        $inscripcion = InscripcionCapacitacion::with('actividad')->findOrFail($this->inscripcionId);

        // Validaciones dinámicas según si la actividad requiere evaluación
        $rules = [
            'observacionesAprobacion' => 'nullable|string|max:500',
            'mesesVigencia' => 'required|integer|min:1|max:120',
            'competenciasDesarrolladas' => 'nullable|string|max:1000',
            'retroalimentacion' => 'nullable|string|max:1000',
        ];

        if ($inscripcion->actividad->requiere_evaluacion) {
            $rules['calificacionEvaluacion'] = 'required|numeric|min:0|max:100';
        } else {
            $rules['calificacionEvaluacion'] = 'nullable|numeric|min:0|max:100';
        }

        $this->validate($rules);

        try {
            // Validar que esté pendiente
            if ($inscripcion->estado !== EstadoInscripcion::PENDIENTE) {
                $this->dispatch('error', mensaje: 'Solo se pueden aprobar inscripciones pendientes');
                return;
            }

            // Validar que cumpla el criterio de asistencia
            if (!$inscripcion->cumpleAsistenciaMinima()) {
                $this->dispatch('error', mensaje: 'Esta inscripción no cumple con el porcentaje mínimo de asistencia');
                return;
            }

            // Validar calificación mínima si la actividad requiere evaluación
            if ($inscripcion->actividad->requiere_evaluacion && $inscripcion->actividad->calificacion_minima_aprobacion !== null) {
                if ($this->calificacionEvaluacion < $inscripcion->actividad->calificacion_minima_aprobacion) {
                    $this->dispatch('error', mensaje: 'La calificación (' . $this->calificacionEvaluacion . ') es menor a la mínima aprobatoria (' . $inscripcion->actividad->calificacion_minima_aprobacion . ')');
                    return;
                }
            }

            $certificacion = null;
            DB::transaction(function () use ($inscripcion, &$certificacion) {
                // Guardar calificación y retroalimentación
                $inscripcion->update([
                    'calificacion_evaluacion' => $this->calificacionEvaluacion,
                    'retroalimentacion' => $this->retroalimentacion,
                    'calificacion_final' => $this->calificacionEvaluacion ?? $inscripcion->calificacion_final,
                ]);

                // Aprobar inscripción
                $inscripcion->aprobar(auth()->id(), $this->observacionesAprobacion ?: 'Aprobada por cumplir criterios');

                // Generar certificación
                $certificacion = $this->generarCertificacion($inscripcion);
            });

            // Enviar notificaciones fuera de la transacción
            $inscripcion->load('enfermero.user', 'actividad');
            $inscripcion->enfermero->user->notify(new InscripcionAprobadaNotification($inscripcion));

            if ($certificacion) {
                $certificacion->load('inscripcion.actividad', 'inscripcion.enfermero.user');
                $inscripcion->enfermero->user->notify(new CertificacionGeneradaNotification($certificacion));
            }

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

        $totalSeleccionadas = count($this->inscripcionesSeleccionadas);

        // Umbral para usar el job en segundo plano (más de 10 inscripciones)
        $umbralBackgroundJob = 10;

        try {
            // Si hay muchas inscripciones, usar el job en segundo plano
            if ($totalSeleccionadas > $umbralBackgroundJob) {
                $this->aprobarMasivoEnSegundoPlano();
                return;
            }

            // Proceso síncrono para pocas inscripciones
            $aprobadas = 0;
            $errores = 0;
            $inscripcionesAprobadas = [];

            DB::transaction(function () use (&$aprobadas, &$errores, &$inscripcionesAprobadas) {
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
                    $certificacion = $this->generarCertificacion($inscripcion);

                    $inscripcionesAprobadas[] = [
                        'inscripcion' => $inscripcion,
                        'certificacion' => $certificacion,
                    ];

                    $aprobadas++;
                }
            });

            // Enviar notificaciones fuera de la transacción
            foreach ($inscripcionesAprobadas as $data) {
                $inscripcion = $data['inscripcion'];
                $certificacion = $data['certificacion'];

                $inscripcion->load('enfermero.user', 'actividad');
                $inscripcion->enfermero->user->notify(new InscripcionAprobadaNotification($inscripcion));

                if ($certificacion) {
                    $certificacion->load('inscripcion.actividad', 'inscripcion.enfermero.user');
                    $inscripcion->enfermero->user->notify(new CertificacionGeneradaNotification($certificacion));
                }
            }

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

    protected function aprobarMasivoEnSegundoPlano(): void
    {
        // Filtrar solo las inscripciones que cumplen criterios
        $inscripcionesValidas = [];

        foreach ($this->inscripcionesSeleccionadas as $inscripcionId) {
            $inscripcion = InscripcionCapacitacion::find($inscripcionId);

            if (!$inscripcion || $inscripcion->estado !== EstadoInscripcion::PENDIENTE) {
                continue;
            }

            if (!$inscripcion->cumpleAsistenciaMinima()) {
                continue;
            }

            // Aprobar la inscripción de forma síncrona (rápido)
            $inscripcion->aprobar(auth()->id(), $this->observacionesAprobacion ?: 'Aprobada masivamente');
            $inscripcionesValidas[] = $inscripcionId;
        }

        if (empty($inscripcionesValidas)) {
            $this->dispatch('error', mensaje: 'No hay inscripciones válidas para aprobar');
            return;
        }

        // Despachar el job para generar certificaciones en segundo plano
        GenerarCertificacionesMasivas::dispatch(
            $inscripcionesValidas,
            auth()->id(),
            $this->mesesVigencia,
            $this->competenciasDesarrolladas ?: null,
            $this->observacionesAprobacion ?: null
        );

        $totalValidas = count($inscripcionesValidas);
        $this->inscripcionesSeleccionadas = [];
        $this->modalAprobarMasivo = false;

        $this->dispatch('aprobacion-masiva-en-proceso', mensaje: "{$totalValidas} inscripciones aprobadas. Las certificaciones se generarán en segundo plano.");
        $this->resetPage();
    }

    protected function generarCertificacion(InscripcionCapacitacion $inscripcion): Certificacion
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

        // Crear certificación (usar calificacion_evaluacion si está disponible)
        return Certificacion::create([
            'inscripcion_id' => $inscripcion->id,
            'numero_certificado' => $numeroCertificado,
            'fecha_emision' => $fechaEmision,
            'fecha_vigencia_inicio' => $fechaVigenciaInicio,
            'fecha_vigencia_fin' => $fechaVigenciaFin,
            'horas_certificadas' => $inscripcion->actividad->duracion_horas,
            'calificacion_obtenida' => $inscripcion->calificacion_evaluacion ?? $inscripcion->calificacion_final,
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
