<?php

namespace App\Services;

use App\Enums\EstadoActividad;
use App\Enums\EstadoInscripcion;
use App\Models\ActividadCapacitacion;
use App\Models\Area;
use App\Models\Certificacion;
use App\Models\InscripcionCapacitacion;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;

class ReportePDFService
{
    protected ?string $fechaInicio;
    protected ?string $fechaFin;
    protected ?int $areaId;

    public function __construct(?string $fechaInicio = null, ?string $fechaFin = null, ?int $areaId = null)
    {
        $this->fechaInicio = $fechaInicio;
        $this->fechaFin = $fechaFin;
        $this->areaId = $areaId;
    }

    public function generarReporteGeneral(): Response
    {
        $datos = $this->obtenerDatosGenerales();

        $pdf = Pdf::loadView('pdfs.reporte-capacitacion', [
            'titulo' => 'Reporte General de Capacitación',
            'tipo' => 'general',
            'datos' => $datos,
            'fechaInicio' => $this->fechaInicio,
            'fechaFin' => $this->fechaFin,
            'fechaGeneracion' => now(),
        ]);

        return $pdf->download('reporte-capacitacion-general-' . now()->format('Y-m-d') . '.pdf');
    }

    public function generarReportePorArea(): Response
    {
        $datos = $this->obtenerDatosPorArea();

        $pdf = Pdf::loadView('pdfs.reporte-capacitacion', [
            'titulo' => 'Reporte de Capacitación por Área',
            'tipo' => 'por_area',
            'datos' => $datos,
            'fechaInicio' => $this->fechaInicio,
            'fechaFin' => $this->fechaFin,
            'fechaGeneracion' => now(),
        ]);

        return $pdf->download('reporte-capacitacion-por-area-' . now()->format('Y-m-d') . '.pdf');
    }

    public function generarReporteCertificaciones(): Response
    {
        $datos = $this->obtenerDatosCertificaciones();

        $pdf = Pdf::loadView('pdfs.reporte-capacitacion', [
            'titulo' => 'Reporte de Certificaciones',
            'tipo' => 'certificaciones',
            'datos' => $datos,
            'fechaInicio' => $this->fechaInicio,
            'fechaFin' => $this->fechaFin,
            'fechaGeneracion' => now(),
        ]);

        return $pdf->download('reporte-certificaciones-' . now()->format('Y-m-d') . '.pdf');
    }

    protected function obtenerDatosGenerales(): array
    {
        $query = ActividadCapacitacion::query();

        if ($this->fechaInicio) {
            $query->where('fecha_inicio', '>=', $this->fechaInicio);
        }
        if ($this->fechaFin) {
            $query->where('fecha_fin', '<=', $this->fechaFin);
        }
        if ($this->areaId) {
            $query->where('area_id', $this->areaId);
        }

        $totalActividades = $query->count();
        $actividadesCompletadas = (clone $query)->where('estado', EstadoActividad::COMPLETADA->value)->count();
        $actividadesEnCurso = (clone $query)->where('estado', EstadoActividad::EN_CURSO->value)->count();

        $actividadesIds = (clone $query)->pluck('id');

        $inscripcionesQuery = InscripcionCapacitacion::whereIn('actividad_id', $actividadesIds);
        $totalInscripciones = $inscripcionesQuery->count();
        $inscripcionesAprobadas = (clone $inscripcionesQuery)->where('estado', EstadoInscripcion::APROBADA->value)->count();
        $inscripcionesPendientes = (clone $inscripcionesQuery)->where('estado', EstadoInscripcion::PENDIENTE->value)->count();

        $totalCertificaciones = Certificacion::whereHas('inscripcion', function ($q) use ($actividadesIds) {
            $q->whereIn('actividad_id', $actividadesIds);
        })->count();

        $certificacionesVigentes = Certificacion::whereHas('inscripcion', function ($q) use ($actividadesIds) {
            $q->whereIn('actividad_id', $actividadesIds);
        })->where(function ($q) {
            $q->whereNull('fecha_vigencia_fin')
                ->orWhere('fecha_vigencia_fin', '>', now());
        })->count();

        $totalHoras = Certificacion::whereHas('inscripcion', function ($q) use ($actividadesIds) {
            $q->whereIn('actividad_id', $actividadesIds);
        })->sum('horas_certificadas');

        return [
            'totalActividades' => $totalActividades,
            'actividadesCompletadas' => $actividadesCompletadas,
            'actividadesEnCurso' => $actividadesEnCurso,
            'totalInscripciones' => $totalInscripciones,
            'inscripcionesAprobadas' => $inscripcionesAprobadas,
            'inscripcionesPendientes' => $inscripcionesPendientes,
            'totalCertificaciones' => $totalCertificaciones,
            'certificacionesVigentes' => $certificacionesVigentes,
            'totalHoras' => $totalHoras,
            'tasaAprobacion' => $totalInscripciones > 0 ? round(($inscripcionesAprobadas / $totalInscripciones) * 100, 1) : 0,
        ];
    }

    protected function obtenerDatosPorArea(): array
    {
        $areas = Area::with(['actividades' => function ($q) {
            if ($this->fechaInicio) {
                $q->where('fecha_inicio', '>=', $this->fechaInicio);
            }
            if ($this->fechaFin) {
                $q->where('fecha_fin', '<=', $this->fechaFin);
            }
        }])->get();

        return $areas->map(function ($area) {
            $actividadesIds = $area->actividades->pluck('id');

            $inscripciones = InscripcionCapacitacion::whereIn('actividad_id', $actividadesIds);
            $aprobadas = (clone $inscripciones)->where('estado', EstadoInscripcion::APROBADA->value);

            $certificaciones = Certificacion::whereHas('inscripcion', function ($q) use ($actividadesIds) {
                $q->whereIn('actividad_id', $actividadesIds);
            });

            return [
                'area' => $area->nombre,
                'totalActividades' => $area->actividades->count(),
                'enfermerosCapacitados' => (clone $aprobadas)->distinct('enfermero_id')->count('enfermero_id'),
                'inscripciones' => $inscripciones->count(),
                'aprobadas' => $aprobadas->count(),
                'certificaciones' => $certificaciones->count(),
                'horasCertificadas' => $certificaciones->sum('horas_certificadas'),
            ];
        })->toArray();
    }

    protected function obtenerDatosCertificaciones(): array
    {
        $query = Certificacion::with(['inscripcion.enfermero.user', 'inscripcion.enfermero.areaFija', 'inscripcion.actividad']);

        if ($this->fechaInicio) {
            $query->where('fecha_emision', '>=', $this->fechaInicio);
        }
        if ($this->fechaFin) {
            $query->where('fecha_emision', '<=', $this->fechaFin);
        }
        if ($this->areaId) {
            $query->whereHas('inscripcion.actividad', function ($q) {
                $q->where('area_id', $this->areaId);
            });
        }

        return $query->get()->map(function ($cert) {
            return [
                'numeroCertificado' => $cert->numero_certificado,
                'enfermero' => $cert->inscripcion->enfermero->user->name,
                'area' => $cert->inscripcion->enfermero->areaFija->nombre ?? 'N/A',
                'actividad' => $cert->inscripcion->actividad->titulo,
                'fechaEmision' => $cert->fecha_emision->format('d/m/Y'),
                'vigenciaHasta' => $cert->fecha_vigencia_fin?->format('d/m/Y') ?? 'Sin vencimiento',
                'horas' => $cert->horas_certificadas,
                'calificacion' => $cert->calificacion_obtenida ? number_format($cert->calificacion_obtenida, 1) . '%' : 'N/A',
                'asistencia' => number_format($cert->porcentaje_asistencia, 1) . '%',
                'vigente' => $cert->estaVigente(),
            ];
        })->toArray();
    }
}
