<?php

namespace App\Livewire;

use App\Enums\EstadoTurno;
use App\Enums\TipoAsignacion;
use App\Enums\TipoTurno;
use App\Models\Area;
use App\Models\AsignacionPaciente;
use App\Models\Enfermero;
use App\Models\Paciente;
use App\Models\Turno;
use App\Services\ConflictoHorarioService;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class GestorTurnos extends Component
{
    // Propiedades principales
    public ?int $areaId = null;
    public ?int $turnoActualId = null;

    // Formulario de creación de turno
    public $fecha;
    public $tipo;
    public $jefeTurnoId;

    // Formulario de asignación
    public ?int $pacienteSeleccionado = null;
    public ?int $enfermeroSeleccionado = null;

    // Formulario de reasignación
    public ?int $asignacionReasignar = null;
    public ?int $nuevoEnfermero = null;

    // Formulario de liberación
    public ?int $asignacionLiberar = null;
    public $motivoLiberacion = '';

    // Modales
    public bool $mostrarModalCrearTurno = false;
    public bool $mostrarModalAsignar = false;
    public bool $mostrarModalReasignar = false;
    public bool $mostrarModalLiberar = false;
    public bool $mostrarModalCerrarTurno = false;

    // Novedades para el relevo
    public $novedadesRelevo = '';

    public function mount(?int $areaId = null)
    {
        // Si no se especifica área, usar el área del usuario autenticado
        if ($areaId) {
            $this->areaId = $areaId;
        } elseif (auth()->user()->enfermero && auth()->user()->enfermero->area_fija_id) {
            $this->areaId = auth()->user()->enfermero->area_fija_id;
        } else {
            // Si no tiene área asignada, usar la primera disponible
            $this->areaId = Area::first()?->id;
        }

        // Buscar turno activo del día
        $this->turnoActualId = Turno::activos()
            ->deHoy()
            ->where('area_id', $this->areaId)
            ->first()?->id;

        // Inicializar fecha y jefe de turno
        $this->fecha = today()->format('Y-m-d');
        $this->jefeTurnoId = auth()->id();
    }

    #[Computed]
    public function area()
    {
        return Area::find($this->areaId);
    }

    #[Computed]
    public function turnoActual()
    {
        return $this->turnoActualId ? Turno::find($this->turnoActualId) : null;
    }

    #[Computed]
    public function areas()
    {
        return Area::orderBy('nombre')->get();
    }

    #[Computed]
    public function tiposTurno()
    {
        return TipoTurno::cases();
    }

    #[Computed]
    public function enfermeros()
    {
        if (!$this->areaId) {
            return collect();
        }

        $conflictoService = app(ConflictoHorarioService::class);

        // Enfermeros fijos del área o rotativos
        return Enfermero::with('user')
            ->where(function ($query) {
                $query->where('area_fija_id', $this->areaId)
                    ->orWhere('tipo_asignacion', TipoAsignacion::ROTATIVO);
            })
            ->get()
            ->map(function ($enfermero) use ($conflictoService) {
                $enfermero->pacientes_asignados = $this->turnoActualId
                    ? AsignacionPaciente::activas()
                        ->porTurno($this->turnoActualId)
                        ->porEnfermero($enfermero->id)
                        ->count()
                    : 0;

                // Agregar información de capacitación
                $enfermero->en_capacitacion = $conflictoService->tieneCapacitacionesActivas($enfermero->id);
                $enfermero->en_capacitacion_ahora = $conflictoService->estaEnCapacitacion($enfermero->id);
                $enfermero->proximas_sesiones = $conflictoService->obtenerProximasSesiones($enfermero->id, 3);

                return $enfermero;
            });
    }

    #[Computed]
    public function pacientes()
    {
        if (!$this->areaId) {
            return collect();
        }

        return Paciente::activos()
            ->whereHas('camaActual', function ($query) {
                $query->whereHas('cuarto.piso', function ($q) {
                    $q->where('area_id', $this->areaId);
                });
            })
            ->with(['camaActual.cuarto.piso', 'asignacionActual.enfermero.user'])
            ->get();
    }

    #[Computed]
    public function asignaciones()
    {
        if (!$this->turnoActualId) {
            return collect();
        }

        return AsignacionPaciente::with([
            'enfermero.user',
            'paciente.camaActual.cuarto.piso',
            'asignadoPor',
        ])
            ->porTurno($this->turnoActualId)
            ->activas()
            ->orderBy('fecha_hora_asignacion', 'desc')
            ->get()
            ->groupBy('enfermero_id');
    }

    public function cambiarArea($areaId)
    {
        $this->areaId = $areaId;

        // Buscar turno activo de esta área
        $this->turnoActualId = Turno::activos()
            ->deHoy()
            ->where('area_id', $this->areaId)
            ->first()?->id;
    }

    public function abrirModalCrearTurno()
    {
        $this->mostrarModalCrearTurno = true;
    }

    public function cerrarModalCrearTurno()
    {
        $this->mostrarModalCrearTurno = false;
        $this->reset(['fecha', 'tipo']);
        $this->jefeTurnoId = auth()->id();
    }

    public function crearTurno()
    {
        $this->validate([
            'fecha' => 'required|date',
            'tipo' => 'required|in:' . implode(',', array_column(TipoTurno::cases(), 'value')),
            'jefeTurnoId' => 'required|exists:users,id',
        ]);

        try {
            $tipo = TipoTurno::from($this->tipo);

            $turno = Turno::create([
                'area_id' => $this->areaId,
                'fecha' => $this->fecha,
                'tipo' => $tipo,
                'hora_inicio' => $tipo->getHoraInicio(),
                'hora_fin' => $tipo->getHoraFin(),
                'jefe_turno_id' => $this->jefeTurnoId,
                'estado' => EstadoTurno::ACTIVO,
            ]);

            $this->turnoActualId = $turno->id;
            $this->cerrarModalCrearTurno();

            $this->dispatch('turno-creado', mensaje: 'Turno creado exitosamente');
        } catch (\Exception $e) {
            $this->dispatch('error', mensaje: 'Error al crear el turno: ' . $e->getMessage());
        }
    }

    public function abrirModalAsignar($pacienteId)
    {
        $this->pacienteSeleccionado = $pacienteId;
        $this->enfermeroSeleccionado = null;
        $this->mostrarModalAsignar = true;
    }

    public function cerrarModalAsignar()
    {
        $this->mostrarModalAsignar = false;
        $this->reset(['pacienteSeleccionado', 'enfermeroSeleccionado']);
    }

    public function asignarPaciente()
    {
        if (!$this->turnoActualId) {
            $this->dispatch('error', mensaje: 'Debe crear un turno activo primero');
            return;
        }

        $this->validate([
            'enfermeroSeleccionado' => 'required|exists:enfermeros,id',
            'pacienteSeleccionado' => 'required|exists:pacientes,id',
        ]);

        // Validar conflictos con capacitación
        $conflictoService = app(ConflictoHorarioService::class);
        $turno = Turno::find($this->turnoActualId);
        $enfermero = Enfermero::with('user')->find($this->enfermeroSeleccionado);

        $conflictos = $conflictoService->verificarConflictoParaTurno(
            $this->enfermeroSeleccionado,
            $turno->fecha,
            $turno->hora_inicio,
            $turno->hora_fin
        );

        if (!empty($conflictos)) {
            $conflicto = $conflictos[0];
            $this->dispatch('error', mensaje:
                "No se puede asignar. {$enfermero->user->name} está inscrito en capacitación:\n" .
                "📚 {$conflicto['actividad']->titulo}\n" .
                "📅 Sesión {$conflicto['entidad']->numero_sesion}: {$conflicto['entidad']->fecha->format('d/m/Y')} " .
                "{$conflicto['entidad']->hora_inicio}-{$conflicto['entidad']->hora_fin}"
            );
            return;
        }

        try {
            DB::transaction(function () {
                // Liberar asignación anterior si existe
                $asignacionAnterior = AsignacionPaciente::activas()
                    ->porPaciente($this->pacienteSeleccionado)
                    ->first();

                if ($asignacionAnterior) {
                    $asignacionAnterior->liberar(
                        auth()->user(),
                        'Reasignación a otro enfermero'
                    );
                }

                // Crear nueva asignación
                AsignacionPaciente::create([
                    'turno_id' => $this->turnoActualId,
                    'enfermero_id' => $this->enfermeroSeleccionado,
                    'paciente_id' => $this->pacienteSeleccionado,
                    'fecha_hora_asignacion' => now(),
                    'asignado_por' => auth()->id(),
                ]);
            });

            $this->cerrarModalAsignar();
            $this->dispatch('paciente-asignado', mensaje: 'Paciente asignado exitosamente');
        } catch (\Exception $e) {
            $this->dispatch('error', mensaje: 'Error al asignar paciente: ' . $e->getMessage());
        }
    }

    public function abrirModalReasignar($asignacionId)
    {
        $this->asignacionReasignar = $asignacionId;
        $this->nuevoEnfermero = null;
        $this->mostrarModalReasignar = true;
    }

    public function cerrarModalReasignar()
    {
        $this->mostrarModalReasignar = false;
        $this->reset(['asignacionReasignar', 'nuevoEnfermero']);
    }

    public function reasignarPaciente()
    {
        $this->validate([
            'nuevoEnfermero' => 'required|exists:enfermeros,id',
        ]);

        // Validar conflictos con capacitación
        $conflictoService = app(ConflictoHorarioService::class);
        $turno = Turno::find($this->turnoActualId);
        $enfermero = Enfermero::with('user')->find($this->nuevoEnfermero);

        $conflictos = $conflictoService->verificarConflictoParaTurno(
            $this->nuevoEnfermero,
            $turno->fecha,
            $turno->hora_inicio,
            $turno->hora_fin
        );

        if (!empty($conflictos)) {
            $conflicto = $conflictos[0];
            $this->dispatch('error', mensaje:
                "No se puede reasignar. {$enfermero->user->name} está inscrito en capacitación:\n" .
                "📚 {$conflicto['actividad']->titulo}\n" .
                "📅 Sesión {$conflicto['entidad']->numero_sesion}: {$conflicto['entidad']->fecha->format('d/m/Y')} " .
                "{$conflicto['entidad']->hora_inicio}-{$conflicto['entidad']->hora_fin}"
            );
            return;
        }

        try {
            DB::transaction(function () {
                $asignacion = AsignacionPaciente::findOrFail($this->asignacionReasignar);

                // Liberar asignación actual
                $asignacion->liberar(
                    auth()->user(),
                    'Reasignación a otro enfermero'
                );

                // Crear nueva asignación
                AsignacionPaciente::create([
                    'turno_id' => $this->turnoActualId,
                    'enfermero_id' => $this->nuevoEnfermero,
                    'paciente_id' => $asignacion->paciente_id,
                    'fecha_hora_asignacion' => now(),
                    'asignado_por' => auth()->id(),
                ]);
            });

            $this->cerrarModalReasignar();
            $this->dispatch('paciente-reasignado', mensaje: 'Paciente reasignado exitosamente');
        } catch (\Exception $e) {
            $this->dispatch('error', mensaje: 'Error al reasignar paciente: ' . $e->getMessage());
        }
    }

    public function abrirModalLiberar($asignacionId)
    {
        $this->asignacionLiberar = $asignacionId;
        $this->motivoLiberacion = '';
        $this->mostrarModalLiberar = true;
    }

    public function cerrarModalLiberar()
    {
        $this->mostrarModalLiberar = false;
        $this->reset(['asignacionLiberar', 'motivoLiberacion']);
    }

    public function liberarAsignacion()
    {
        $this->validate([
            'motivoLiberacion' => 'required|string|min:3',
        ]);

        try {
            $asignacion = AsignacionPaciente::findOrFail($this->asignacionLiberar);
            $asignacion->liberar(auth()->user(), $this->motivoLiberacion);

            $this->cerrarModalLiberar();
            $this->dispatch('asignacion-liberada', mensaje: 'Asignación liberada exitosamente');
        } catch (\Exception $e) {
            $this->dispatch('error', mensaje: 'Error al liberar asignación: ' . $e->getMessage());
        }
    }

    public function abrirModalCerrarTurno()
    {
        if (!$this->turnoActual) {
            $this->dispatch('error', mensaje: 'No hay turno activo para cerrar');
            return;
        }

        $this->novedadesRelevo = '';
        $this->mostrarModalCerrarTurno = true;
    }

    public function cerrarModalCerrarTurno()
    {
        $this->mostrarModalCerrarTurno = false;
        $this->reset(['novedadesRelevo']);
    }

    public function cerrarTurno()
    {
        $this->validate([
            'novedadesRelevo' => 'nullable|string',
        ]);

        try {
            $turno = Turno::findOrFail($this->turnoActualId);

            $turno->update([
                'estado' => EstadoTurno::CERRADO,
                'novedades_relevo' => $this->novedadesRelevo,
                'cerrado_at' => now(),
                'cerrado_por' => auth()->id(),
            ]);

            $this->turnoActualId = null;
            $this->cerrarModalCerrarTurno();

            $this->dispatch('turno-cerrado', mensaje: 'Turno cerrado exitosamente');
        } catch (\Exception $e) {
            $this->dispatch('error', mensaje: 'Error al cerrar turno: ' . $e->getMessage());
        }
    }

    #[On('turno-creado')]
    #[On('paciente-asignado')]
    #[On('paciente-reasignado')]
    #[On('asignacion-liberada')]
    #[On('turno-cerrado')]
    public function refrescar()
    {
        // Los computed properties se actualizan automáticamente
    }

    public function render()
    {
        return view('livewire.gestor-turnos')->layout('layouts.app');
    }
}
