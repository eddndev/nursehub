<?php

namespace App\Livewire\Capacitacion;

use App\Enums\EstadoActividad;
use App\Enums\EstadoInscripcion;
use App\Models\ActividadCapacitacion;
use App\Models\Area;
use App\Models\Enfermero;
use App\Models\InscripcionCapacitacion;
use App\Models\SesionCapacitacion;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;

class CalendarioCapacitaciones extends Component
{
    // Navegación del calendario
    public $mesActual;
    public $añoActual;

    // Filtros
    public $filtroArea = '';
    public $filtroEnfermero = '';
    public $vistaCalendario = 'mes'; // mes, semana, lista

    // Modales
    public $modalDetallesDia = false;
    public $modalDetallesActividad = false;
    public $modalDetallesSesion = false;

    // IDs seleccionados
    public $fechaSeleccionada = null;
    public $actividadId = null;
    public $sesionId = null;

    public function mount()
    {
        $this->mesActual = now()->month;
        $this->añoActual = now()->year;
    }

    #[Computed]
    public function jefePiso()
    {
        return Enfermero::where('user_id', auth()->id())->first();
    }

    #[Computed]
    public function areasDisponibles()
    {
        $jefe = $this->jefePiso;
        if (!$jefe) {
            return Area::all();
        }

        // Si es jefe de piso, solo su área; si es coordinador/admin, todas las áreas
        if (auth()->user()->hasAnyRole(['coordinador', 'admin'])) {
            return Area::all();
        }

        return Area::where('id', $jefe->area_id)->get();
    }

    #[Computed]
    public function enfermerosArea()
    {
        if (!$this->filtroArea) {
            $jefe = $this->jefePiso;
            if ($jefe && !auth()->user()->hasAnyRole(['coordinador', 'admin'])) {
                return Enfermero::where('area_id', $jefe->area_id)
                    ->with('user')
                    ->get();
            }
            return Enfermero::with('user')->get();
        }

        return Enfermero::where('area_id', $this->filtroArea)
            ->with('user')
            ->get();
    }

    #[Computed]
    public function diasDelMes()
    {
        $primerDia = Carbon::create($this->añoActual, $this->mesActual, 1);
        $ultimoDia = $primerDia->copy()->endOfMonth();

        $dias = [];
        $diaActual = $primerDia->copy();

        while ($diaActual <= $ultimoDia) {
            $dias[] = [
                'fecha' => $diaActual->copy(),
                'esHoy' => $diaActual->isToday(),
                'esMesActual' => true,
            ];
            $diaActual->addDay();
        }

        return $dias;
    }

    #[Computed]
    public function actividadesDelMes()
    {
        $primerDia = Carbon::create($this->añoActual, $this->mesActual, 1)->startOfDay();
        $ultimoDia = $primerDia->copy()->endOfMonth()->endOfDay();

        $query = ActividadCapacitacion::with(['area', 'sesiones', 'inscripciones.enfermero'])
            ->where(function ($q) use ($primerDia, $ultimoDia) {
                $q->whereBetween('fecha_inicio', [$primerDia, $ultimoDia])
                    ->orWhereBetween('fecha_fin', [$primerDia, $ultimoDia])
                    ->orWhere(function ($sq) use ($primerDia, $ultimoDia) {
                        $sq->where('fecha_inicio', '<=', $primerDia)
                            ->where('fecha_fin', '>=', $ultimoDia);
                    });
            });

        // Filtro por área
        if ($this->filtroArea) {
            $query->where('area_id', $this->filtroArea);
        } elseif (!auth()->user()->hasAnyRole(['coordinador', 'admin'])) {
            $jefe = $this->jefePiso;
            if ($jefe) {
                $query->where('area_id', $jefe->area_id);
            }
        }

        // Filtro por enfermero (mostrar actividades donde esté inscrito)
        if ($this->filtroEnfermero) {
            $query->whereHas('inscripciones', function ($q) {
                $q->where('enfermero_id', $this->filtroEnfermero)
                    ->whereIn('estado', [EstadoInscripcion::PENDIENTE->value, EstadoInscripcion::APROBADA->value]);
            });
        }

        return $query->get();
    }

    #[Computed]
    public function sesionesDelMes()
    {
        $primerDia = Carbon::create($this->añoActual, $this->mesActual, 1)->startOfDay();
        $ultimoDia = $primerDia->copy()->endOfMonth()->endOfDay();

        $query = SesionCapacitacion::with(['actividad.area', 'actividad.inscripciones.enfermero', 'asistencias'])
            ->whereBetween('fecha', [$primerDia, $ultimoDia]);

        // Aplicar los mismos filtros que en actividades
        if ($this->filtroArea) {
            $query->whereHas('actividad', function ($q) {
                $q->where('area_id', $this->filtroArea);
            });
        } elseif (!auth()->user()->hasAnyRole(['coordinador', 'admin'])) {
            $jefe = $this->jefePiso;
            if ($jefe) {
                $query->whereHas('actividad', function ($q) use ($jefe) {
                    $q->where('area_id', $jefe->area_id);
                });
            }
        }

        if ($this->filtroEnfermero) {
            $query->whereHas('actividad.inscripciones', function ($q) {
                $q->where('enfermero_id', $this->filtroEnfermero)
                    ->whereIn('estado', [EstadoInscripcion::PENDIENTE->value, EstadoInscripcion::APROBADA->value]);
            });
        }

        return $query->get()->groupBy(function ($sesion) {
            return $sesion->fecha->format('Y-m-d');
        });
    }

    #[Computed]
    public function estadisticasMes()
    {
        $primerDia = Carbon::create($this->añoActual, $this->mesActual, 1)->startOfDay();
        $ultimoDia = $primerDia->copy()->endOfMonth()->endOfDay();

        $totalActividades = $this->actividadesDelMes->count();
        $totalSesiones = $this->sesionesDelMes->flatten()->count();

        $actividadesEnCurso = $this->actividadesDelMes
            ->where('estado', EstadoActividad::EN_CURSO->value)
            ->count();

        $sesionesHoy = $this->sesionesDelMes->get(now()->format('Y-m-d'), collect())->count();

        // Enfermeros con capacitaciones este mes
        $enfermerosCapacitandose = InscripcionCapacitacion::whereHas('actividad', function ($q) use ($primerDia, $ultimoDia) {
            $q->where(function ($sq) use ($primerDia, $ultimoDia) {
                $sq->whereBetween('fecha_inicio', [$primerDia, $ultimoDia])
                    ->orWhereBetween('fecha_fin', [$primerDia, $ultimoDia])
                    ->orWhere(function ($ssq) use ($primerDia, $ultimoDia) {
                        $ssq->where('fecha_inicio', '<=', $primerDia)
                            ->where('fecha_fin', '>=', $ultimoDia);
                    });
            });
        })
        ->whereIn('estado', [EstadoInscripcion::PENDIENTE->value, EstadoInscripcion::APROBADA->value])
        ->distinct('enfermero_id')
        ->count('enfermero_id');

        $horasTotales = $this->actividadesDelMes->sum('duracion_horas');

        return compact('totalActividades', 'totalSesiones', 'actividadesEnCurso', 'sesionesHoy', 'enfermerosCapacitandose', 'horasTotales');
    }

    public function mesAnterior()
    {
        if ($this->mesActual == 1) {
            $this->mesActual = 12;
            $this->añoActual--;
        } else {
            $this->mesActual--;
        }
    }

    public function mesSiguiente()
    {
        if ($this->mesActual == 12) {
            $this->mesActual = 1;
            $this->añoActual++;
        } else {
            $this->mesActual++;
        }
    }

    public function irAHoy()
    {
        $this->mesActual = now()->month;
        $this->añoActual = now()->year;
    }

    public function abrirModalDetallesDia($fecha)
    {
        $this->fechaSeleccionada = $fecha;
        $this->modalDetallesDia = true;
    }

    public function abrirModalDetallesActividad($actividadId)
    {
        $this->actividadId = $actividadId;
        $this->modalDetallesActividad = true;
    }

    public function abrirModalDetallesSesion($sesionId)
    {
        $this->sesionId = $sesionId;
        $this->modalDetallesSesion = true;
    }

    public function limpiarFiltros()
    {
        $this->reset(['filtroArea', 'filtroEnfermero']);
    }

    public function cambiarVista($vista)
    {
        $this->vistaCalendario = $vista;
    }

    public function render()
    {
        $actividadSeleccionada = $this->actividadId
            ? ActividadCapacitacion::with(['area', 'sesiones', 'inscripciones.enfermero.user'])->find($this->actividadId)
            : null;

        $sesionSeleccionada = $this->sesionId
            ? SesionCapacitacion::with(['actividad', 'asistencias.inscripcion.enfermero.user'])->find($this->sesionId)
            : null;

        $sesionesDelDia = $this->fechaSeleccionada
            ? $this->sesionesDelMes->get($this->fechaSeleccionada, collect())
            : collect();

        return view('livewire.capacitacion.calendario-capacitaciones', [
            'actividadSeleccionada' => $actividadSeleccionada,
            'sesionSeleccionada' => $sesionSeleccionada,
            'sesionesDelDia' => $sesionesDelDia,
        ]);
    }
}
