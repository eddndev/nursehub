<?php

namespace App\Livewire\Medicamentos;

use App\Models\SolicitudMedicamento;
use App\Models\DetalleSolicitudMedicamento;
use App\Models\Medicamento;
use App\Models\Paciente;
use App\Models\Area;
use App\Enums\EstadoSolicitudMedicamento;
use App\Enums\PrioridadSolicitudMedicamento;
use Livewire\Component;
use Livewire\WithPagination;

class SolicitudesMedicamentos extends Component
{
    use WithPagination;

    public $estadoFiltro = '';
    public $prioridadFiltro = '';
    public $fechaDesde = '';
    public $fechaHasta = '';

    public $modalNueva = false;
    public $modalDetalle = false;

    public $paciente_id = null;
    public $area_id = null;
    public $prioridad = 'normal';
    public $observaciones = '';

    public $medicamentosAgregados = [];
    public $medicamento_id_temp = null;
    public $cantidad_temp = '';
    public $indicaciones_temp = '';

    public $solicitudSeleccionada = null;

    protected $rules = [
        'paciente_id' => 'required|exists:pacientes,id',
        'prioridad' => 'required',
        'medicamentosAgregados' => 'required|array|min:1',
        'medicamentosAgregados.*.medicamento_id' => 'required|exists:medicamentos,id',
        'medicamentosAgregados.*.cantidad' => 'required|integer|min:1',
    ];

    public function mount()
    {
        $this->area_id = auth()->user()->enfermero->area_id ?? null;
    }

    public function render()
    {
        $solicitudes = SolicitudMedicamento::with(['paciente', 'enfermero.user', 'area', 'detalles.medicamento'])
            ->where('enfermero_id', auth()->user()->enfermero->id)
            ->when($this->estadoFiltro, function ($query) {
                $query->where('estado', $this->estadoFiltro);
            })
            ->when($this->prioridadFiltro, function ($query) {
                $query->where('prioridad', $this->prioridadFiltro);
            })
            ->when($this->fechaDesde, function ($query) {
                $query->whereDate('fecha_solicitud', '>=', $this->fechaDesde);
            })
            ->when($this->fechaHasta, function ($query) {
                $query->whereDate('fecha_solicitud', '<=', $this->fechaHasta);
            })
            ->orderBy('fecha_solicitud', 'desc')
            ->paginate(15);

        $pacientes = Paciente::orderBy('nombre')->get();
        $medicamentos = Medicamento::activos()->orderBy('nombre_comercial')->get();
        $areas = Area::orderBy('nombre')->get();

        return view('livewire.medicamentos.solicitudes-medicamentos', [
            'solicitudes' => $solicitudes,
            'pacientes' => $pacientes,
            'medicamentos' => $medicamentos,
            'areas' => $areas,
            'estados' => EstadoSolicitudMedicamento::cases(),
            'prioridades' => PrioridadSolicitudMedicamento::cases(),
        ]);
    }

    public function abrirModalNueva()
    {
        $this->resetValidation();
        $this->reset(['paciente_id', 'prioridad', 'observaciones', 'medicamentosAgregados']);
        $this->prioridad = 'normal';
        $this->area_id = auth()->user()->enfermero->area_id ?? null;
        $this->modalNueva = true;
    }

    public function agregarMedicamento()
    {
        $this->validate([
            'medicamento_id_temp' => 'required|exists:medicamentos,id',
            'cantidad_temp' => 'required|integer|min:1',
        ]);

        $medicamento = Medicamento::find($this->medicamento_id_temp);

        $existe = collect($this->medicamentosAgregados)
            ->firstWhere('medicamento_id', $this->medicamento_id_temp);

        if ($existe) {
            session()->flash('error', 'Este medicamento ya está agregado a la solicitud.');
            return;
        }

        $this->medicamentosAgregados[] = [
            'medicamento_id' => $this->medicamento_id_temp,
            'medicamento_nombre' => $medicamento->nombre_comercial,
            'cantidad' => $this->cantidad_temp,
            'indicaciones' => $this->indicaciones_temp,
        ];

        $this->reset(['medicamento_id_temp', 'cantidad_temp', 'indicaciones_temp']);
    }

    public function quitarMedicamento($index)
    {
        unset($this->medicamentosAgregados[$index]);
        $this->medicamentosAgregados = array_values($this->medicamentosAgregados);
    }

    public function crearSolicitud()
    {
        $this->validate();

        $numeroSolicitud = 'SOL-' . date('Y') . '-' . str_pad(
            SolicitudMedicamento::whereYear('created_at', date('Y'))->count() + 1,
            5,
            '0',
            STR_PAD_LEFT
        );

        $solicitud = SolicitudMedicamento::create([
            'numero_solicitud' => $numeroSolicitud,
            'enfermero_id' => auth()->user()->enfermero->id,
            'paciente_id' => $this->paciente_id,
            'area_id' => $this->area_id,
            'prioridad' => PrioridadSolicitudMedicamento::from($this->prioridad),
            'estado' => EstadoSolicitudMedicamento::PENDIENTE,
            'fecha_solicitud' => now(),
            'observaciones' => $this->observaciones,
        ]);

        foreach ($this->medicamentosAgregados as $item) {
            DetalleSolicitudMedicamento::create([
                'solicitud_id' => $solicitud->id,
                'medicamento_id' => $item['medicamento_id'],
                'cantidad_solicitada' => $item['cantidad'],
                'indicaciones_medicas' => $item['indicaciones'],
            ]);
        }

        session()->flash('message', "Solicitud {$numeroSolicitud} creada exitosamente.");
        $this->modalNueva = false;
        $this->reset(['medicamentosAgregados', 'paciente_id']);
    }

    public function verDetalle($solicitudId)
    {
        $this->solicitudSeleccionada = SolicitudMedicamento::with([
            'paciente',
            'enfermero.user',
            'area',
            'detalles.medicamento',
            'detalles.inventario',
            'aprobadoPor',
            'despachadoPor'
        ])->findOrFail($solicitudId);

        $this->modalDetalle = true;
    }

    public function cancelarSolicitud($solicitudId)
    {
        $solicitud = SolicitudMedicamento::findOrFail($solicitudId);

        if ($solicitud->enfermero_id !== auth()->user()->enfermero->id) {
            session()->flash('error', 'No tienes permiso para cancelar esta solicitud.');
            return;
        }

        if (!in_array($solicitud->estado, [EstadoSolicitudMedicamento::PENDIENTE, EstadoSolicitudMedicamento::APROBADA])) {
            session()->flash('error', 'Solo se pueden cancelar solicitudes pendientes o aprobadas.');
            return;
        }

        $solicitud->update(['estado' => EstadoSolicitudMedicamento::CANCELADA]);

        session()->flash('message', 'Solicitud cancelada exitosamente.');
        $this->modalDetalle = false;
    }

    public function cerrarModal()
    {
        $this->modalNueva = false;
        $this->modalDetalle = false;
        $this->reset(['solicitudSeleccionada']);
    }
}
