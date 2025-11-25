<?php

namespace App\Livewire\Medicamentos;

use App\Models\AdministracionMedicamento;
use App\Models\Paciente;
use App\Models\Medicamento;
use App\Models\SolicitudMedicamento;
use App\Models\InventarioMedicamento;
use App\Enums\EstadoSolicitudMedicamento;
use App\Enums\EstadoInventarioMedicamento;
use App\Services\InteraccionMedicamentosaService;
use App\Services\AlertaMedicamentoService;
use Livewire\Component;
use Livewire\WithPagination;

class AdministracionMedicamentos extends Component
{
    use WithPagination;

    // Filtros
    public $busquedaPaciente = '';
    public $fechaFiltro = '';

    // Paciente seleccionado
    public $pacienteSeleccionado = null;
    public $pacienteId = null;

    // Modal administrar
    public $modalAdministrar = false;
    public $medicamento_id = null;
    public $solicitud_id = null;
    public $dosis_administrada = '';
    public $via_administracion = '';
    public $observaciones = '';
    public $tuvo_reaccion_adversa = false;
    public $descripcion_reaccion = '';

    // Alertas
    public $alertasInteraccion = [];
    public $alertasAlergia = [];
    public $bloqueoAdministracion = false;

    // Historial
    public $modalHistorial = false;

    protected $interaccionService;

    public function boot(InteraccionMedicamentosaService $service)
    {
        $this->interaccionService = $service;
    }

    protected $rules = [
        'medicamento_id' => 'required|exists:medicamentos,id',
        'dosis_administrada' => 'required|string|max:100',
        'via_administracion' => 'required|string|max:50',
    ];

    public function render()
    {
        $pacientes = Paciente::query()
            ->when($this->busquedaPaciente, function ($query) {
                $query->where(function ($q) {
                    $q->where('nombre', 'like', "%{$this->busquedaPaciente}%")
                      ->orWhere('apellido', 'like', "%{$this->busquedaPaciente}%")
                      ->orWhere('numero_identificacion', 'like', "%{$this->busquedaPaciente}%");
                });
            })
            ->orderBy('nombre')
            ->paginate(10);

        $historialAdministraciones = [];
        $medicamentosDisponibles = [];
        $solicitudesPendientes = collect();

        if ($this->pacienteSeleccionado) {
            // Obtener historial de administraciones del paciente
            $historialAdministraciones = AdministracionMedicamento::with(['medicamento', 'enfermero.user'])
                ->where('paciente_id', $this->pacienteSeleccionado->id)
                ->orderBy('fecha_hora_administracion', 'desc')
                ->take(20)
                ->get();

            // Obtener solicitudes despachadas para este paciente
            $solicitudesPendientes = SolicitudMedicamento::with(['detalles.medicamento', 'detalles.inventario'])
                ->where('paciente_id', $this->pacienteSeleccionado->id)
                ->where('estado', EstadoSolicitudMedicamento::DESPACHADA)
                ->orderBy('fecha_despacho', 'desc')
                ->get();

            // Medicamentos disponibles para administrar
            $medicamentosDisponibles = Medicamento::activos()
                ->orderBy('nombre_comercial')
                ->get();
        }

        return view('livewire.medicamentos.administracion-medicamentos', [
            'pacientes' => $pacientes,
            'historialAdministraciones' => $historialAdministraciones,
            'medicamentosDisponibles' => $medicamentosDisponibles,
            'solicitudesPendientes' => $solicitudesPendientes,
        ]);
    }

    public function seleccionarPaciente($pacienteId)
    {
        $this->pacienteSeleccionado = Paciente::with(['alergias'])->findOrFail($pacienteId);
        $this->pacienteId = $pacienteId;
        $this->resetPage();
    }

    public function limpiarPaciente()
    {
        $this->pacienteSeleccionado = null;
        $this->pacienteId = null;
        $this->reset(['alertasInteraccion', 'alertasAlergia', 'bloqueoAdministracion']);
    }

    public function abrirModalAdministrar($medicamentoId = null, $solicitudId = null)
    {
        if (!$this->pacienteSeleccionado) {
            session()->flash('error', 'Debe seleccionar un paciente primero.');
            return;
        }

        $this->resetValidation();
        $this->reset([
            'medicamento_id', 'solicitud_id', 'dosis_administrada',
            'via_administracion', 'observaciones', 'tuvo_reaccion_adversa',
            'descripcion_reaccion', 'alertasInteraccion', 'alertasAlergia', 'bloqueoAdministracion'
        ]);

        $this->medicamento_id = $medicamentoId;
        $this->solicitud_id = $solicitudId;

        if ($medicamentoId) {
            $this->verificarAlertasMedicamento($medicamentoId);
            $medicamento = Medicamento::find($medicamentoId);
            if ($medicamento) {
                $this->via_administracion = $medicamento->via_administracion->value ?? '';
            }
        }

        $this->modalAdministrar = true;
    }

    public function updatedMedicamentoId($value)
    {
        if ($value) {
            $this->verificarAlertasMedicamento($value);
            $medicamento = Medicamento::find($value);
            if ($medicamento) {
                $this->via_administracion = $medicamento->via_administracion->value ?? '';
            }
        } else {
            $this->reset(['alertasInteraccion', 'alertasAlergia', 'bloqueoAdministracion']);
        }
    }

    protected function verificarAlertasMedicamento($medicamentoId)
    {
        $this->alertasInteraccion = [];
        $this->alertasAlergia = [];
        $this->bloqueoAdministracion = false;

        // Verificar alergias del paciente
        if ($this->pacienteSeleccionado && method_exists($this->pacienteSeleccionado, 'alergias')) {
            $medicamento = Medicamento::find($medicamentoId);
            if ($medicamento) {
                $alergias = $this->pacienteSeleccionado->alergias ?? collect();
                foreach ($alergias as $alergia) {
                    if (str_contains(strtolower($medicamento->principio_activo), strtolower($alergia->alergeno ?? ''))) {
                        $this->alertasAlergia[] = [
                            'tipo' => 'alergia',
                            'mensaje' => "ALERTA: Paciente alérgico a {$alergia->alergeno}",
                            'severidad' => 'critica',
                        ];
                        $this->bloqueoAdministracion = true;
                    }
                }
            }
        }

        // Verificar interacciones medicamentosas
        if ($this->pacienteSeleccionado) {
            $interacciones = $this->interaccionService->verificarInteracciones(
                $this->pacienteSeleccionado,
                $medicamentoId
            );

            foreach ($interacciones as $interaccion) {
                $this->alertasInteraccion[] = [
                    'medicamento' => $interaccion->medicamentoA->nombre_comercial ?? 'Medicamento',
                    'severidad' => $interaccion->severidad->value,
                    'descripcion' => $interaccion->descripcion,
                    'recomendacion' => $interaccion->recomendacion,
                ];

                if ($interaccion->severidad->value === 'contraindicada') {
                    $this->bloqueoAdministracion = true;
                }
            }
        }
    }

    public function verificarDosisMaxima()
    {
        if (!$this->medicamento_id || !$this->pacienteSeleccionado) {
            return true;
        }

        $medicamento = Medicamento::find($this->medicamento_id);
        if (!$medicamento || !$medicamento->dosis_maxima_24h) {
            return true;
        }

        // Calcular dosis administrada en las últimas 24 horas
        $dosisUltimas24h = AdministracionMedicamento::where('paciente_id', $this->pacienteSeleccionado->id)
            ->where('medicamento_id', $this->medicamento_id)
            ->where('fecha_hora_administracion', '>=', now()->subHours(24))
            ->sum('dosis_administrada');

        $dosisNueva = floatval(preg_replace('/[^0-9.]/', '', $this->dosis_administrada));
        $dosisTotal = $dosisUltimas24h + $dosisNueva;

        return $dosisTotal <= $medicamento->dosis_maxima_24h;
    }

    public function registrarAdministracion()
    {
        $this->validate();

        if (!$this->pacienteSeleccionado) {
            session()->flash('error', 'Debe seleccionar un paciente.');
            return;
        }

        if ($this->bloqueoAdministracion) {
            session()->flash('error', 'No se puede administrar el medicamento debido a alergias o interacciones contraindicadas.');
            return;
        }

        // Verificar dosis máxima
        if (!$this->verificarDosisMaxima()) {
            session()->flash('error', 'La dosis excede el máximo permitido en 24 horas.');
            return;
        }

        // Obtener enfermero actual
        $enfermero = auth()->user()->enfermero;
        if (!$enfermero) {
            session()->flash('error', 'No tiene permisos de enfermero para administrar medicamentos.');
            return;
        }

        AdministracionMedicamento::create([
            'paciente_id' => $this->pacienteSeleccionado->id,
            'enfermero_id' => $enfermero->id,
            'medicamento_id' => $this->medicamento_id,
            'solicitud_id' => $this->solicitud_id,
            'fecha_hora_administracion' => now(),
            'dosis_administrada' => $this->dosis_administrada,
            'via_administracion' => $this->via_administracion,
            'observaciones' => $this->observaciones,
            'tuvo_reaccion_adversa' => $this->tuvo_reaccion_adversa,
            'descripcion_reaccion' => $this->tuvo_reaccion_adversa ? $this->descripcion_reaccion : null,
        ]);

        session()->flash('message', 'Administración registrada exitosamente.');
        $this->modalAdministrar = false;
        $this->reset([
            'medicamento_id', 'solicitud_id', 'dosis_administrada',
            'via_administracion', 'observaciones', 'tuvo_reaccion_adversa',
            'descripcion_reaccion', 'alertasInteraccion', 'alertasAlergia'
        ]);
    }

    public function registrarReaccionAdversa($administracionId)
    {
        $administracion = AdministracionMedicamento::findOrFail($administracionId);

        // Verificar que no hayan pasado más de 24 horas
        if ($administracion->fecha_hora_administracion->diffInHours(now()) > 24) {
            session()->flash('error', 'No se puede modificar el registro después de 24 horas.');
            return;
        }

        $administracion->update([
            'tuvo_reaccion_adversa' => true,
            'descripcion_reaccion' => $this->descripcion_reaccion,
        ]);

        session()->flash('message', 'Reacción adversa registrada.');
    }

    public function abrirHistorial()
    {
        if (!$this->pacienteSeleccionado) {
            session()->flash('error', 'Debe seleccionar un paciente primero.');
            return;
        }
        $this->modalHistorial = true;
    }

    public function cerrarModal()
    {
        $this->modalAdministrar = false;
        $this->modalHistorial = false;
        $this->reset([
            'medicamento_id', 'solicitud_id', 'dosis_administrada',
            'via_administracion', 'observaciones', 'tuvo_reaccion_adversa',
            'descripcion_reaccion', 'alertasInteraccion', 'alertasAlergia', 'bloqueoAdministracion'
        ]);
    }
}
