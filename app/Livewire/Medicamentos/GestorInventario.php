<?php

namespace App\Livewire\Medicamentos;

use App\Models\InventarioMedicamento;
use App\Models\Medicamento;
use App\Models\MovimientoInventario;
use App\Models\Area;
use App\Enums\TipoMovimientoInventario;
use App\Enums\EstadoInventarioMedicamento;
use App\Services\AlertaMedicamentoService;
use Livewire\Component;
use Livewire\WithPagination;

class GestorInventario extends Component
{
    use WithPagination;

    public $busqueda = '';
    public $areaFiltro = '';
    public $estadoFiltro = '';
    public $soloStockBajo = false;
    public $soloProximoCaducar = false;

    public $modalEntrada = false;
    public $modalSalida = false;
    public $modalAjuste = false;
    public $modalTransferencia = false;

    public $medicamento_id = null;
    public $area_id = null;
    public $lote = '';
    public $fecha_caducidad = '';
    public $cantidad = '';
    public $cantidad_inicial = '';
    public $stock_minimo = 10;
    public $stock_maximo = null;
    public $costo_unitario = '';
    public $estado = '';

    public $inventario_id = null;
    public $tipo_movimiento = '';
    public $cantidad_movimiento = '';
    public $area_origen_id = null;
    public $area_destino_id = null;
    public $motivo = '';
    public $referencia = '';

    protected $alertaService;

    public function boot(AlertaMedicamentoService $service)
    {
        $this->alertaService = $service;
    }

    protected function rules()
    {
        return [
            'medicamento_id' => 'required|exists:medicamentos,id',
            'lote' => 'required|string|max:100',
            'fecha_caducidad' => 'required|date|after:today',
            'cantidad' => 'required|integer|min:1',
            'stock_minimo' => 'required|integer|min:0',
            'stock_maximo' => 'nullable|integer|min:0',
            'costo_unitario' => 'required|numeric|min:0',
        ];
    }

    public function updatingBusqueda()
    {
        $this->resetPage();
    }

    public function updatingAreaFiltro()
    {
        $this->resetPage();
    }

    public function render()
    {
        $inventario = InventarioMedicamento::with(['medicamento', 'area'])
            ->when($this->busqueda, function ($query) {
                $query->whereHas('medicamento', function ($q) {
                    $q->where('nombre_comercial', 'like', "%{$this->busqueda}%")
                      ->orWhere('nombre_generico', 'like', "%{$this->busqueda}%")
                      ->orWhere('codigo_medicamento', 'like', "%{$this->busqueda}%");
                });
            })
            ->when($this->areaFiltro, function ($query) {
                if ($this->areaFiltro === 'almacen') {
                    $query->whereNull('area_id');
                } else {
                    $query->where('area_id', $this->areaFiltro);
                }
            })
            ->when($this->estadoFiltro, function ($query) {
                $query->where('estado', $this->estadoFiltro);
            })
            ->when($this->soloStockBajo, function ($query) {
                $query->whereColumn('cantidad_actual', '<=', 'stock_minimo');
            })
            ->when($this->soloProximoCaducar, function ($query) {
                $query->where('fecha_caducidad', '<=', now()->addDays(60))
                      ->where('fecha_caducidad', '>', now());
            })
            ->orderBy('fecha_caducidad', 'asc')
            ->paginate(20);

        $resumenAlertas = $this->alertaService->obtenerResumenAlertas();

        $areas = Area::orderBy('nombre')->get();
        $medicamentos = Medicamento::activos()->orderBy('nombre_comercial')->get();

        return view('livewire.medicamentos.gestor-inventario', [
            'inventario' => $inventario,
            'resumenAlertas' => $resumenAlertas,
            'areas' => $areas,
            'medicamentos' => $medicamentos,
            'estados' => EstadoInventarioMedicamento::cases(),
        ]);
    }

    public function abrirModalEntrada()
    {
        $this->resetValidation();
        $this->reset([
            'medicamento_id', 'area_id', 'lote', 'fecha_caducidad',
            'cantidad', 'stock_minimo', 'stock_maximo', 'costo_unitario'
        ]);
        $this->area_id = null;
        $this->stock_minimo = 10;
        $this->estado = EstadoInventarioMedicamento::DISPONIBLE->value;
        $this->modalEntrada = true;
    }

    public function registrarEntrada()
    {
        $this->validate();

        $inventario = InventarioMedicamento::create([
            'medicamento_id' => $this->medicamento_id,
            'area_id' => $this->area_id,
            'lote' => $this->lote,
            'fecha_caducidad' => $this->fecha_caducidad,
            'cantidad_actual' => $this->cantidad,
            'cantidad_inicial' => $this->cantidad,
            'stock_minimo' => $this->stock_minimo,
            'stock_maximo' => $this->stock_maximo,
            'costo_unitario' => $this->costo_unitario,
            'estado' => EstadoInventarioMedicamento::DISPONIBLE,
        ]);

        MovimientoInventario::create([
            'inventario_id' => $inventario->id,
            'tipo_movimiento' => TipoMovimientoInventario::ENTRADA,
            'cantidad' => $this->cantidad,
            'cantidad_anterior' => 0,
            'cantidad_nueva' => $this->cantidad,
            'area_destino_id' => $this->area_id,
            'motivo' => 'Entrada de inventario - Nuevo lote',
            'usuario_id' => auth()->id(),
            'fecha_movimiento' => now(),
            'referencia' => $this->referencia,
        ]);

        session()->flash('message', 'Entrada de inventario registrada exitosamente.');
        $this->modalEntrada = false;
        $this->reset(['medicamento_id', 'lote']);
    }

    public function abrirModalAjuste($inventarioId)
    {
        $inventario = InventarioMedicamento::findOrFail($inventarioId);
        $this->inventario_id = $inventarioId;
        $this->cantidad_movimiento = $inventario->cantidad_actual;
        $this->motivo = '';
        $this->modalAjuste = true;
    }

    public function registrarAjuste()
    {
        $this->validate([
            'cantidad_movimiento' => 'required|integer|min:0',
            'motivo' => 'required|string',
        ]);

        $inventario = InventarioMedicamento::findOrFail($this->inventario_id);
        $cantidadAnterior = $inventario->cantidad_actual;

        $inventario->update([
            'cantidad_actual' => $this->cantidad_movimiento,
        ]);

        MovimientoInventario::create([
            'inventario_id' => $inventario->id,
            'tipo_movimiento' => TipoMovimientoInventario::AJUSTE,
            'cantidad' => abs($this->cantidad_movimiento - $cantidadAnterior),
            'cantidad_anterior' => $cantidadAnterior,
            'cantidad_nueva' => $this->cantidad_movimiento,
            'motivo' => $this->motivo,
            'usuario_id' => auth()->id(),
            'fecha_movimiento' => now(),
        ]);

        session()->flash('message', 'Ajuste de inventario registrado exitosamente.');
        $this->modalAjuste = false;
    }

    public function abrirModalTransferencia($inventarioId)
    {
        $inventario = InventarioMedicamento::findOrFail($inventarioId);
        $this->inventario_id = $inventarioId;
        $this->area_origen_id = $inventario->area_id;
        $this->cantidad_movimiento = '';
        $this->area_destino_id = null;
        $this->motivo = '';
        $this->modalTransferencia = true;
    }

    public function registrarTransferencia()
    {
        $this->validate([
            'cantidad_movimiento' => 'required|integer|min:1',
            'area_destino_id' => 'required|exists:areas,id|different:area_origen_id',
            'motivo' => 'nullable|string',
        ]);

        $inventarioOrigen = InventarioMedicamento::findOrFail($this->inventario_id);

        if ($inventarioOrigen->cantidad_actual < $this->cantidad_movimiento) {
            session()->flash('error', 'No hay suficiente stock para transferir.');
            return;
        }

        $inventarioOrigen->decrement('cantidad_actual', $this->cantidad_movimiento);

        $inventarioDestino = InventarioMedicamento::firstOrCreate(
            [
                'medicamento_id' => $inventarioOrigen->medicamento_id,
                'area_id' => $this->area_destino_id,
                'lote' => $inventarioOrigen->lote,
            ],
            [
                'fecha_caducidad' => $inventarioOrigen->fecha_caducidad,
                'cantidad_actual' => 0,
                'cantidad_inicial' => 0,
                'stock_minimo' => $inventarioOrigen->stock_minimo,
                'costo_unitario' => $inventarioOrigen->costo_unitario,
                'estado' => EstadoInventarioMedicamento::DISPONIBLE,
            ]
        );

        $inventarioDestino->increment('cantidad_actual', $this->cantidad_movimiento);

        MovimientoInventario::create([
            'inventario_id' => $inventarioOrigen->id,
            'tipo_movimiento' => TipoMovimientoInventario::TRANSFERENCIA,
            'cantidad' => $this->cantidad_movimiento,
            'cantidad_anterior' => $inventarioOrigen->cantidad_actual + $this->cantidad_movimiento,
            'cantidad_nueva' => $inventarioOrigen->cantidad_actual,
            'area_origen_id' => $this->area_origen_id,
            'area_destino_id' => $this->area_destino_id,
            'motivo' => $this->motivo ?? 'Transferencia entre áreas',
            'usuario_id' => auth()->id(),
            'fecha_movimiento' => now(),
        ]);

        session()->flash('message', 'Transferencia realizada exitosamente.');
        $this->modalTransferencia = false;
    }

    public function marcarCaducado($inventarioId)
    {
        $inventario = InventarioMedicamento::findOrFail($inventarioId);
        $inventario->update(['estado' => EstadoInventarioMedicamento::CADUCADO]);

        MovimientoInventario::create([
            'inventario_id' => $inventario->id,
            'tipo_movimiento' => TipoMovimientoInventario::MERMA,
            'cantidad' => $inventario->cantidad_actual,
            'cantidad_anterior' => $inventario->cantidad_actual,
            'cantidad_nueva' => 0,
            'motivo' => 'Medicamento caducado',
            'usuario_id' => auth()->id(),
            'fecha_movimiento' => now(),
        ]);

        session()->flash('message', 'Medicamento marcado como caducado.');
    }

    public function cerrarModal()
    {
        $this->modalEntrada = false;
        $this->modalSalida = false;
        $this->modalAjuste = false;
        $this->modalTransferencia = false;
        $this->reset(['inventario_id', 'medicamento_id']);
    }
}
