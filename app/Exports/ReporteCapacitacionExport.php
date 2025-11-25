<?php

namespace App\Exports;

use App\Enums\EstadoActividad;
use App\Enums\EstadoInscripcion;
use App\Models\ActividadCapacitacion;
use App\Models\Area;
use App\Models\Certificacion;
use App\Models\InscripcionCapacitacion;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReporteCapacitacionExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithTitle
{
    protected string $tipoReporte;
    protected ?string $fechaInicio;
    protected ?string $fechaFin;
    protected ?int $areaId;

    public function __construct(
        string $tipoReporte = 'general',
        ?string $fechaInicio = null,
        ?string $fechaFin = null,
        ?int $areaId = null
    ) {
        $this->tipoReporte = $tipoReporte;
        $this->fechaInicio = $fechaInicio;
        $this->fechaFin = $fechaFin;
        $this->areaId = $areaId;
    }

    public function collection(): Collection
    {
        return match ($this->tipoReporte) {
            'por_area' => $this->reportePorArea(),
            'por_actividad' => $this->reportePorActividad(),
            'certificaciones' => $this->reporteCertificaciones(),
            default => $this->reporteGeneral(),
        };
    }

    public function headings(): array
    {
        return match ($this->tipoReporte) {
            'por_area' => [
                'Área',
                'Total Actividades',
                'Enfermeros Capacitados',
                'Inscripciones',
                'Aprobadas',
                'Certificaciones',
                'Horas Certificadas',
            ],
            'por_actividad' => [
                'Actividad',
                'Tipo',
                'Estado',
                'Fecha Inicio',
                'Fecha Fin',
                'Cupo',
                'Inscripciones',
                'Aprobadas',
                'Certificaciones',
                'Horas',
            ],
            'certificaciones' => [
                'No. Certificado',
                'Enfermero',
                'Área',
                'Actividad',
                'Fecha Emisión',
                'Vigencia Hasta',
                'Horas',
                'Calificación',
                '% Asistencia',
                'Estado',
            ],
            default => [
                'Métrica',
                'Valor',
            ],
        };
    }

    public function map($row): array
    {
        return match ($this->tipoReporte) {
            'por_area' => [
                $row['area'],
                $row['total_actividades'],
                $row['enfermeros_capacitados'],
                $row['inscripciones'],
                $row['aprobadas'],
                $row['certificaciones'],
                $row['horas_certificadas'],
            ],
            'por_actividad' => [
                $row['titulo'],
                $row['tipo'],
                $row['estado'],
                $row['fecha_inicio'],
                $row['fecha_fin'],
                $row['cupo'],
                $row['inscripciones'],
                $row['aprobadas'],
                $row['certificaciones'],
                $row['horas'],
            ],
            'certificaciones' => [
                $row['numero_certificado'],
                $row['enfermero'],
                $row['area'],
                $row['actividad'],
                $row['fecha_emision'],
                $row['vigencia_hasta'],
                $row['horas'],
                $row['calificacion'],
                $row['asistencia'],
                $row['estado'],
            ],
            default => [
                $row['metrica'],
                $row['valor'],
            ],
        };
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4F46E5'],
                ],
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            ],
        ];
    }

    public function title(): string
    {
        return match ($this->tipoReporte) {
            'por_area' => 'Reporte por Área',
            'por_actividad' => 'Reporte por Actividad',
            'certificaciones' => 'Certificaciones',
            default => 'Reporte General',
        };
    }

    protected function reporteGeneral(): Collection
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

        $inscripcionesQuery = InscripcionCapacitacion::whereIn('actividad_id', (clone $query)->pluck('id'));
        $totalInscripciones = $inscripcionesQuery->count();
        $inscripcionesAprobadas = (clone $inscripcionesQuery)->where('estado', EstadoInscripcion::APROBADA->value)->count();

        $totalCertificaciones = Certificacion::whereHas('inscripcion', function ($q) use ($query) {
            $q->whereIn('actividad_id', (clone $query)->pluck('id'));
        })->count();

        $totalHoras = Certificacion::whereHas('inscripcion', function ($q) use ($query) {
            $q->whereIn('actividad_id', (clone $query)->pluck('id'));
        })->sum('horas_certificadas');

        return collect([
            ['metrica' => 'Total de Actividades', 'valor' => $totalActividades],
            ['metrica' => 'Actividades Completadas', 'valor' => $actividadesCompletadas],
            ['metrica' => 'Total de Inscripciones', 'valor' => $totalInscripciones],
            ['metrica' => 'Inscripciones Aprobadas', 'valor' => $inscripcionesAprobadas],
            ['metrica' => 'Certificaciones Generadas', 'valor' => $totalCertificaciones],
            ['metrica' => 'Horas Totales Certificadas', 'valor' => $totalHoras],
            ['metrica' => 'Tasa de Aprobación', 'valor' => $totalInscripciones > 0 ? round(($inscripcionesAprobadas / $totalInscripciones) * 100, 1) . '%' : '0%'],
        ]);
    }

    protected function reportePorArea(): Collection
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
                'total_actividades' => $area->actividades->count(),
                'enfermeros_capacitados' => (clone $aprobadas)->distinct('enfermero_id')->count('enfermero_id'),
                'inscripciones' => $inscripciones->count(),
                'aprobadas' => $aprobadas->count(),
                'certificaciones' => $certificaciones->count(),
                'horas_certificadas' => $certificaciones->sum('horas_certificadas'),
            ];
        });
    }

    protected function reportePorActividad(): Collection
    {
        $query = ActividadCapacitacion::with(['inscripciones', 'sesiones']);

        if ($this->fechaInicio) {
            $query->where('fecha_inicio', '>=', $this->fechaInicio);
        }
        if ($this->fechaFin) {
            $query->where('fecha_fin', '<=', $this->fechaFin);
        }
        if ($this->areaId) {
            $query->where('area_id', $this->areaId);
        }

        return $query->get()->map(function ($actividad) {
            $aprobadas = $actividad->inscripciones->where('estado', EstadoInscripcion::APROBADA);

            return [
                'titulo' => $actividad->titulo,
                'tipo' => $actividad->tipo->getLabel(),
                'estado' => $actividad->estado->getLabel(),
                'fecha_inicio' => $actividad->fecha_inicio->format('d/m/Y'),
                'fecha_fin' => $actividad->fecha_fin->format('d/m/Y'),
                'cupo' => $actividad->total_inscritos . '/' . $actividad->cupo_maximo,
                'inscripciones' => $actividad->inscripciones->count(),
                'aprobadas' => $aprobadas->count(),
                'certificaciones' => $aprobadas->filter(fn($i) => $i->certificacion)->count(),
                'horas' => $actividad->duracion_horas,
            ];
        });
    }

    protected function reporteCertificaciones(): Collection
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
                'numero_certificado' => $cert->numero_certificado,
                'enfermero' => $cert->inscripcion->enfermero->user->name,
                'area' => $cert->inscripcion->enfermero->areaFija->nombre ?? 'N/A',
                'actividad' => $cert->inscripcion->actividad->titulo,
                'fecha_emision' => $cert->fecha_emision->format('d/m/Y'),
                'vigencia_hasta' => $cert->fecha_vigencia_fin?->format('d/m/Y') ?? 'Sin vencimiento',
                'horas' => $cert->horas_certificadas,
                'calificacion' => $cert->calificacion_obtenida ? number_format($cert->calificacion_obtenida, 1) . '%' : 'N/A',
                'asistencia' => number_format($cert->porcentaje_asistencia, 1) . '%',
                'estado' => $cert->estaVigente() ? 'Vigente' : 'Vencido',
            ];
        });
    }
}
