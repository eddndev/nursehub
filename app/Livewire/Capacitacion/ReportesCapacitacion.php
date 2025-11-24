<?php

namespace App\Livewire\Capacitacion;

use App\Enums\EstadoActividad;
use App\Enums\EstadoInscripcion;
use App\Enums\TipoActividad;
use App\Models\ActividadCapacitacion;
use App\Models\Area;
use App\Models\Certificacion;
use App\Models\Enfermero;
use App\Models\InscripcionCapacitacion;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;

class ReportesCapacitacion extends Component
{
    // Filtros de fechas
    public $fechaInicio;
    public $fechaFin;
    public $tipoReporte = 'general'; // general, por-area, por-enfermero, por-actividad

    // Filtros adicionales
    public $areaSeleccionada = '';
    public $enfermeroSeleccionado = '';
    public $tipoActividadFiltro = '';

    public function mount()
    {
        $this->fechaInicio = now()->startOfMonth()->format('Y-m-d');
        $this->fechaFin = now()->endOfMonth()->format('Y-m-d');
    }

    #[Computed]
    public function estadisticasGenerales()
    {
        $fechaInicio = Carbon::parse($this->fechaInicio)->startOfDay();
        $fechaFin = Carbon::parse($this->fechaFin)->endOfDay();

        // Actividades
        $totalActividades = ActividadCapacitacion::whereBetween('fecha_inicio', [$fechaInicio, $fechaFin])->count();
        $actividadesPublicadas = ActividadCapacitacion::whereBetween('fecha_inicio', [$fechaInicio, $fechaFin])
            ->where('estado', EstadoActividad::PUBLICADA->value)
            ->count();
        $actividadesEnCurso = ActividadCapacitacion::whereBetween('fecha_inicio', [$fechaInicio, $fechaFin])
            ->where('estado', EstadoActividad::EN_CURSO->value)
            ->count();
        $actividadesFinalizadas = ActividadCapacitacion::whereBetween('fecha_inicio', [$fechaInicio, $fechaFin])
            ->where('estado', EstadoActividad::FINALIZADA->value)
            ->count();

        // Inscripciones
        $totalInscripciones = InscripcionCapacitacion::whereHas('actividad', function ($q) use ($fechaInicio, $fechaFin) {
            $q->whereBetween('fecha_inicio', [$fechaInicio, $fechaFin]);
        })->count();

        $inscripcionesAprobadas = InscripcionCapacitacion::whereHas('actividad', function ($q) use ($fechaInicio, $fechaFin) {
            $q->whereBetween('fecha_inicio', [$fechaInicio, $fechaFin]);
        })
        ->where('estado', EstadoInscripcion::APROBADA->value)
        ->count();

        $inscripcionesPendientes = InscripcionCapacitacion::whereHas('actividad', function ($q) use ($fechaInicio, $fechaFin) {
            $q->whereBetween('fecha_inicio', [$fechaInicio, $fechaFin]);
        })
        ->where('estado', EstadoInscripcion::PENDIENTE->value)
        ->count();

        $inscripcionesReprobadas = InscripcionCapacitacion::whereHas('actividad', function ($q) use ($fechaInicio, $fechaFin) {
            $q->whereBetween('fecha_inicio', [$fechaInicio, $fechaFin]);
        })
        ->where('estado', EstadoInscripcion::REPROBADA->value)
        ->count();

        // Certificaciones
        $certificacionesGeneradas = Certificacion::whereBetween('fecha_emision', [$fechaInicio, $fechaFin])->count();
        $certificacionesVigentes = Certificacion::whereBetween('fecha_emision', [$fechaInicio, $fechaFin])
            ->where(function ($q) {
                $q->whereNull('fecha_vigencia_fin')
                    ->orWhere('fecha_vigencia_fin', '>', now());
            })
            ->count();

        // Horas y participación
        $horasTotalesCertificadas = Certificacion::whereBetween('fecha_emision', [$fechaInicio, $fechaFin])
            ->sum('horas_certificadas');

        $enfermerosCapacitados = InscripcionCapacitacion::whereHas('actividad', function ($q) use ($fechaInicio, $fechaFin) {
            $q->whereBetween('fecha_inicio', [$fechaInicio, $fechaFin]);
        })
        ->distinct('enfermero_id')
        ->count('enfermero_id');

        $totalEnfermeros = Enfermero::count();
        $porcentajeParticipacion = $totalEnfermeros > 0 ? round(($enfermerosCapacitados / $totalEnfermeros) * 100, 2) : 0;

        // Tasa de aprobación
        $tasaAprobacion = $totalInscripciones > 0
            ? round(($inscripcionesAprobadas / $totalInscripciones) * 100, 2)
            : 0;

        // Promedio de asistencia
        $promedioAsistencia = InscripcionCapacitacion::whereHas('actividad', function ($q) use ($fechaInicio, $fechaFin) {
            $q->whereBetween('fecha_inicio', [$fechaInicio, $fechaFin]);
        })
        ->where('estado', EstadoInscripcion::APROBADA->value)
        ->avg('porcentaje_asistencia');

        return compact(
            'totalActividades',
            'actividadesPublicadas',
            'actividadesEnCurso',
            'actividadesFinalizadas',
            'totalInscripciones',
            'inscripcionesAprobadas',
            'inscripcionesPendientes',
            'inscripcionesReprobadas',
            'certificacionesGeneradas',
            'certificacionesVigentes',
            'horasTotalesCertificadas',
            'enfermerosCapacitados',
            'totalEnfermeros',
            'porcentajeParticipacion',
            'tasaAprobacion',
            'promedioAsistencia'
        );
    }

    #[Computed]
    public function reportePorArea()
    {
        if ($this->tipoReporte !== 'por-area') {
            return collect();
        }

        $fechaInicio = Carbon::parse($this->fechaInicio)->startOfDay();
        $fechaFin = Carbon::parse($this->fechaFin)->endOfDay();

        $query = Area::withCount([
            'actividadesCapacitacion' => function ($q) use ($fechaInicio, $fechaFin) {
                $q->whereBetween('fecha_inicio', [$fechaInicio, $fechaFin]);
            }
        ])
        ->with(['enfermeros' => function ($q) use ($fechaInicio, $fechaFin) {
            $q->withCount([
                'inscripciones' => function ($sq) use ($fechaInicio, $fechaFin) {
                    $sq->whereHas('actividad', function ($ssq) use ($fechaInicio, $fechaFin) {
                        $ssq->whereBetween('fecha_inicio', [$fechaInicio, $fechaFin]);
                    });
                }
            ]);
        }])
        ->get();

        return $query->map(function ($area) use ($fechaInicio, $fechaFin) {
            $inscripciones = InscripcionCapacitacion::whereHas('actividad', function ($q) use ($area, $fechaInicio, $fechaFin) {
                $q->where('area_id', $area->id)
                    ->whereBetween('fecha_inicio', [$fechaInicio, $fechaFin]);
            })->get();

            $certificaciones = Certificacion::whereHas('inscripcion.actividad', function ($q) use ($area, $fechaInicio, $fechaFin) {
                $q->where('area_id', $area->id)
                    ->whereBetween('fecha_inicio', [$fechaInicio, $fechaFin]);
            })->count();

            return [
                'area' => $area->nombre,
                'total_actividades' => $area->actividades_capacitacion_count,
                'total_enfermeros' => $area->enfermeros->count(),
                'enfermeros_capacitados' => $area->enfermeros->filter(fn($e) => $e->inscripciones_count > 0)->count(),
                'total_inscripciones' => $inscripciones->count(),
                'inscripciones_aprobadas' => $inscripciones->where('estado', EstadoInscripcion::APROBADA)->count(),
                'certificaciones' => $certificaciones,
                'horas_certificadas' => Certificacion::whereHas('inscripcion.actividad', function ($q) use ($area, $fechaInicio, $fechaFin) {
                    $q->where('area_id', $area->id)
                        ->whereBetween('fecha_inicio', [$fechaInicio, $fechaFin]);
                })->sum('horas_certificadas'),
            ];
        });
    }

    #[Computed]
    public function reportePorTipoActividad()
    {
        $fechaInicio = Carbon::parse($this->fechaInicio)->startOfDay();
        $fechaFin = Carbon::parse($this->fechaFin)->endOfDay();

        $reportes = [];

        foreach (TipoActividad::cases() as $tipo) {
            $actividades = ActividadCapacitacion::where('tipo', $tipo->value)
                ->whereBetween('fecha_inicio', [$fechaInicio, $fechaFin])
                ->get();

            $inscripciones = InscripcionCapacitacion::whereHas('actividad', function ($q) use ($tipo, $fechaInicio, $fechaFin) {
                $q->where('tipo', $tipo->value)
                    ->whereBetween('fecha_inicio', [$fechaInicio, $fechaFin]);
            })->get();

            $certificaciones = Certificacion::whereHas('inscripcion.actividad', function ($q) use ($tipo, $fechaInicio, $fechaFin) {
                $q->where('tipo', $tipo->value)
                    ->whereBetween('fecha_inicio', [$fechaInicio, $fechaFin]);
            })->count();

            $reportes[] = [
                'tipo' => $tipo->getLabel(),
                'total_actividades' => $actividades->count(),
                'total_inscripciones' => $inscripciones->count(),
                'inscripciones_aprobadas' => $inscripciones->where('estado', EstadoInscripcion::APROBADA)->count(),
                'certificaciones' => $certificaciones,
                'horas_totales' => $actividades->sum('duracion_horas'),
                'horas_certificadas' => Certificacion::whereHas('inscripcion.actividad', function ($q) use ($tipo, $fechaInicio, $fechaFin) {
                    $q->where('tipo', $tipo->value)
                        ->whereBetween('fecha_inicio', [$fechaInicio, $fechaFin]);
                })->sum('horas_certificadas'),
            ];
        }

        return collect($reportes);
    }

    #[Computed]
    public function topEnfermerosCapacitados()
    {
        $fechaInicio = Carbon::parse($this->fechaInicio)->startOfDay();
        $fechaFin = Carbon::parse($this->fechaFin)->endOfDay();

        return Enfermero::with('user', 'area')
            ->withCount([
                'inscripciones' => function ($q) use ($fechaInicio, $fechaFin) {
                    $q->whereHas('actividad', function ($sq) use ($fechaInicio, $fechaFin) {
                        $sq->whereBetween('fecha_inicio', [$fechaInicio, $fechaFin]);
                    });
                },
                'certificaciones' => function ($q) use ($fechaInicio, $fechaFin) {
                    $q->whereBetween('fecha_emision', [$fechaInicio, $fechaFin]);
                }
            ])
            ->withSum([
                'certificaciones as horas_totales' => function ($q) use ($fechaInicio, $fechaFin) {
                    $q->whereBetween('fecha_emision', [$fechaInicio, $fechaFin]);
                }
            ], 'horas_certificadas')
            ->having('inscripciones_count', '>', 0)
            ->orderByDesc('certificaciones_count')
            ->orderByDesc('horas_totales')
            ->take(10)
            ->get();
    }

    #[Computed]
    public function actividadesMasPopulares()
    {
        $fechaInicio = Carbon::parse($this->fechaInicio)->startOfDay();
        $fechaFin = Carbon::parse($this->fechaFin)->endOfDay();

        return ActividadCapacitacion::with('area')
            ->whereBetween('fecha_inicio', [$fechaInicio, $fechaFin])
            ->withCount('inscripciones')
            ->orderByDesc('inscripciones_count')
            ->take(10)
            ->get();
    }

    public function cambiarTipoReporte($tipo)
    {
        $this->tipoReporte = $tipo;
    }

    public function limpiarFiltros()
    {
        $this->fechaInicio = now()->startOfMonth()->format('Y-m-d');
        $this->fechaFin = now()->endOfMonth()->format('Y-m-d');
        $this->areaSeleccionada = '';
        $this->enfermeroSeleccionado = '';
        $this->tipoActividadFiltro = '';
    }

    public function exportarExcel()
    {
        // TODO: Implementar exportación a Excel
        $this->dispatch('info', mensaje: 'Funcionalidad de exportación a Excel próximamente');
    }

    public function exportarPDF()
    {
        // TODO: Implementar exportación a PDF
        $this->dispatch('info', mensaje: 'Funcionalidad de exportación a PDF próximamente');
    }

    public function render()
    {
        return view('livewire.capacitacion.reportes-capacitacion');
    }
}
