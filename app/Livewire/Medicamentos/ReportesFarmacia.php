<?php

namespace App\Livewire\Medicamentos;

use App\Models\Medicamento;
use App\Models\InventarioMedicamento;
use App\Models\MovimientoInventario;
use App\Models\SolicitudMedicamento;
use App\Models\AdministracionMedicamento;
use App\Models\RegistroMedicamentoControlado;
use App\Models\Area;
use App\Enums\EstadoSolicitudMedicamento;
use App\Enums\TipoMovimientoInventario;
use App\Enums\EstadoInventarioMedicamento;
use App\Exports\ReporteFarmaciaExport;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportesFarmacia extends Component
{
    public $tipoReporte = 'consumo';
    public $fechaInicio;
    public $fechaFin;
    public $areaFiltro = '';
    public $medicamentoFiltro = '';

    public $datosReporte = [];
    public $totales = [];
    public $chartData = [];

    protected $queryString = ['tipoReporte'];

    public function mount()
    {
        $this->fechaInicio = now()->startOfMonth()->format('Y-m-d');
        $this->fechaFin = now()->format('Y-m-d');
        $this->generarReporte();
    }

    public function render()
    {
        $areas = Area::orderBy('nombre')->get();
        $medicamentos = Medicamento::activos()->orderBy('nombre_comercial')->get();

        return view('livewire.medicamentos.reportes-farmacia', [
            'areas' => $areas,
            'medicamentos' => $medicamentos,
        ]);
    }

    public function updatedTipoReporte()
    {
        $this->generarReporte();
    }

    public function updatedFechaInicio()
    {
        $this->generarReporte();
    }

    public function updatedFechaFin()
    {
        $this->generarReporte();
    }

    public function updatedAreaFiltro()
    {
        $this->generarReporte();
    }

    public function generarReporte()
    {
        switch ($this->tipoReporte) {
            case 'consumo':
                $this->generarReporteConsumo();
                break;
            case 'costos':
                $this->generarReporteCostos();
                break;
            case 'desperdicios':
                $this->generarReporteDesperdicios();
                break;
            case 'controlados':
                $this->generarReporteControlados();
                break;
            case 'inventario':
                $this->generarReporteInventario();
                break;
            case 'movimientos':
                $this->generarReporteMovimientos();
                break;
        }
    }

    protected function generarReporteConsumo()
    {
        $query = MovimientoInventario::with(['inventario.medicamento', 'inventario.area'])
            ->where('tipo_movimiento', TipoMovimientoInventario::SALIDA)
            ->whereBetween('fecha_movimiento', [$this->fechaInicio, $this->fechaFin . ' 23:59:59']);

        if ($this->areaFiltro) {
            $query->where('area_destino_id', $this->areaFiltro);
        }

        $movimientos = $query->get();

        // Agrupar por medicamento
        $consumoPorMedicamento = $movimientos->groupBy(fn($m) => $m->inventario->medicamento_id)
            ->map(function ($grupo) {
                $medicamento = $grupo->first()->inventario->medicamento;
                $totalCantidad = $grupo->sum('cantidad');
                $totalCosto = $grupo->sum(fn($m) => $m->cantidad * $m->inventario->costo_unitario);

                return [
                    'medicamento_id' => $medicamento->id,
                    'codigo' => $medicamento->codigo_medicamento,
                    'nombre' => $medicamento->nombre_comercial,
                    'categoria' => $medicamento->categoria?->getLabel() ?? 'Sin categoría',
                    'cantidad_total' => $totalCantidad,
                    'costo_total' => $totalCosto,
                    'movimientos' => $grupo->count(),
                ];
            })
            ->sortByDesc('cantidad_total')
            ->values()
            ->toArray();

        $this->datosReporte = $consumoPorMedicamento;
        $this->totales = [
            'total_medicamentos' => count($consumoPorMedicamento),
            'total_unidades' => collect($consumoPorMedicamento)->sum('cantidad_total'),
            'total_costo' => collect($consumoPorMedicamento)->sum('costo_total'),
            'total_movimientos' => $movimientos->count(),
        ];

        // Datos para gráfico (top 10)
        $this->chartData = [
            'labels' => collect($consumoPorMedicamento)->take(10)->pluck('nombre')->toArray(),
            'data' => collect($consumoPorMedicamento)->take(10)->pluck('cantidad_total')->toArray(),
        ];
    }

    protected function generarReporteCostos()
    {
        // Costos por área
        $query = MovimientoInventario::with(['inventario.medicamento', 'areaDestino'])
            ->where('tipo_movimiento', TipoMovimientoInventario::SALIDA)
            ->whereBetween('fecha_movimiento', [$this->fechaInicio, $this->fechaFin . ' 23:59:59']);

        $movimientos = $query->get();

        $costosPorArea = $movimientos->groupBy('area_destino_id')
            ->map(function ($grupo, $areaId) {
                $area = $grupo->first()->areaDestino;
                $totalCosto = $grupo->sum(fn($m) => $m->cantidad * $m->inventario->costo_unitario);
                $totalUnidades = $grupo->sum('cantidad');

                return [
                    'area_id' => $areaId,
                    'area' => $area?->nombre ?? 'Sin área asignada',
                    'total_costo' => $totalCosto,
                    'total_unidades' => $totalUnidades,
                    'costo_promedio_unidad' => $totalUnidades > 0 ? $totalCosto / $totalUnidades : 0,
                    'movimientos' => $grupo->count(),
                ];
            })
            ->sortByDesc('total_costo')
            ->values()
            ->toArray();

        $this->datosReporte = $costosPorArea;
        $this->totales = [
            'total_areas' => count($costosPorArea),
            'total_costo' => collect($costosPorArea)->sum('total_costo'),
            'total_unidades' => collect($costosPorArea)->sum('total_unidades'),
        ];

        $this->chartData = [
            'labels' => collect($costosPorArea)->pluck('area')->toArray(),
            'data' => collect($costosPorArea)->pluck('total_costo')->toArray(),
        ];
    }

    protected function generarReporteDesperdicios()
    {
        // Medicamentos caducados y mermas
        $caducados = InventarioMedicamento::with(['medicamento'])
            ->where('estado', EstadoInventarioMedicamento::CADUCADO)
            ->whereBetween('updated_at', [$this->fechaInicio, $this->fechaFin . ' 23:59:59'])
            ->get();

        $mermas = MovimientoInventario::with(['inventario.medicamento'])
            ->where('tipo_movimiento', TipoMovimientoInventario::MERMA)
            ->whereBetween('fecha_movimiento', [$this->fechaInicio, $this->fechaFin . ' 23:59:59'])
            ->get();

        $desperdicios = [];

        // Procesar caducados
        foreach ($caducados as $inv) {
            $desperdicios[] = [
                'tipo' => 'Caducado',
                'medicamento' => $inv->medicamento->nombre_comercial,
                'lote' => $inv->lote,
                'cantidad' => $inv->cantidad_actual,
                'valor_perdido' => $inv->cantidad_actual * $inv->costo_unitario,
                'fecha' => $inv->updated_at->format('d/m/Y'),
                'motivo' => 'Fecha de caducidad vencida',
            ];
        }

        // Procesar mermas
        foreach ($mermas as $mov) {
            $desperdicios[] = [
                'tipo' => 'Merma',
                'medicamento' => $mov->inventario->medicamento->nombre_comercial,
                'lote' => $mov->inventario->lote,
                'cantidad' => $mov->cantidad,
                'valor_perdido' => $mov->cantidad * $mov->inventario->costo_unitario,
                'fecha' => $mov->fecha_movimiento->format('d/m/Y'),
                'motivo' => $mov->motivo ?? 'Sin especificar',
            ];
        }

        $this->datosReporte = $desperdicios;
        $this->totales = [
            'total_caducados' => $caducados->count(),
            'total_mermas' => $mermas->count(),
            'valor_perdido_caducados' => $caducados->sum(fn($i) => $i->cantidad_actual * $i->costo_unitario),
            'valor_perdido_mermas' => $mermas->sum(fn($m) => $m->cantidad * $m->inventario->costo_unitario),
            'total_valor_perdido' => collect($desperdicios)->sum('valor_perdido'),
        ];

        $this->chartData = [
            'labels' => ['Caducados', 'Mermas'],
            'data' => [$this->totales['valor_perdido_caducados'], $this->totales['valor_perdido_mermas']],
        ];
    }

    protected function generarReporteControlados()
    {
        $registros = RegistroMedicamentoControlado::with(['medicamento', 'usuario', 'autorizadoPor', 'solicitud.paciente'])
            ->whereBetween('fecha_operacion', [$this->fechaInicio, $this->fechaFin . ' 23:59:59'])
            ->orderBy('fecha_operacion', 'desc')
            ->get();

        $this->datosReporte = $registros->map(function ($reg) {
            return [
                'fecha' => $reg->fecha_operacion->format('d/m/Y H:i'),
                'medicamento' => $reg->medicamento->nombre_comercial,
                'tipo_operacion' => ucfirst($reg->tipo_operacion),
                'cantidad' => $reg->cantidad,
                'paciente' => $reg->solicitud?->paciente?->nombre . ' ' . $reg->solicitud?->paciente?->apellido,
                'numero_receta' => $reg->numero_receta,
                'usuario' => $reg->usuario->name,
                'autorizado_por' => $reg->autorizadoPor->name,
                'justificacion' => $reg->justificacion,
            ];
        })->toArray();

        // Resumen por medicamento
        $porMedicamento = $registros->where('tipo_operacion', 'salida')
            ->groupBy('medicamento_id')
            ->map(fn($g) => [
                'nombre' => $g->first()->medicamento->nombre_comercial,
                'total' => $g->sum('cantidad'),
            ]);

        $this->totales = [
            'total_operaciones' => $registros->count(),
            'total_salidas' => $registros->where('tipo_operacion', 'salida')->sum('cantidad'),
            'total_entradas' => $registros->where('tipo_operacion', 'entrada')->sum('cantidad'),
            'medicamentos_distintos' => $registros->unique('medicamento_id')->count(),
        ];

        $this->chartData = [
            'labels' => $porMedicamento->pluck('nombre')->toArray(),
            'data' => $porMedicamento->pluck('total')->toArray(),
        ];
    }

    protected function generarReporteInventario()
    {
        $query = InventarioMedicamento::with(['medicamento', 'area'])
            ->where('estado', EstadoInventarioMedicamento::DISPONIBLE)
            ->where('cantidad_actual', '>', 0);

        if ($this->areaFiltro) {
            $query->where('area_id', $this->areaFiltro);
        }

        $inventario = $query->get();

        // Agrupar por medicamento
        $inventarioPorMedicamento = $inventario->groupBy('medicamento_id')
            ->map(function ($grupo) {
                $medicamento = $grupo->first()->medicamento;
                $stockTotal = $grupo->sum('cantidad_actual');
                $valorTotal = $grupo->sum(fn($i) => $i->cantidad_actual * $i->costo_unitario);
                $stockMinimo = $grupo->first()->stock_minimo;
                $proximoCaducar = $grupo->sortBy('fecha_caducidad')->first();

                return [
                    'medicamento_id' => $medicamento->id,
                    'codigo' => $medicamento->codigo_medicamento,
                    'nombre' => $medicamento->nombre_comercial,
                    'es_controlado' => $medicamento->es_controlado,
                    'stock_total' => $stockTotal,
                    'stock_minimo' => $stockMinimo,
                    'valor_inventario' => $valorTotal,
                    'lotes' => $grupo->count(),
                    'proxima_caducidad' => $proximoCaducar->fecha_caducidad->format('d/m/Y'),
                    'dias_para_caducar' => now()->diffInDays($proximoCaducar->fecha_caducidad, false),
                    'estado_stock' => $stockTotal <= $stockMinimo ? 'bajo' : 'normal',
                ];
            })
            ->sortBy('nombre')
            ->values()
            ->toArray();

        $this->datosReporte = $inventarioPorMedicamento;
        $this->totales = [
            'total_medicamentos' => count($inventarioPorMedicamento),
            'total_unidades' => collect($inventarioPorMedicamento)->sum('stock_total'),
            'valor_total_inventario' => collect($inventarioPorMedicamento)->sum('valor_inventario'),
            'medicamentos_stock_bajo' => collect($inventarioPorMedicamento)->where('estado_stock', 'bajo')->count(),
        ];

        $this->chartData = [
            'labels' => ['Stock Normal', 'Stock Bajo'],
            'data' => [
                count($inventarioPorMedicamento) - $this->totales['medicamentos_stock_bajo'],
                $this->totales['medicamentos_stock_bajo'],
            ],
        ];
    }

    protected function generarReporteMovimientos()
    {
        $query = MovimientoInventario::with(['inventario.medicamento', 'inventario.area', 'usuario', 'areaOrigen', 'areaDestino'])
            ->whereBetween('fecha_movimiento', [$this->fechaInicio, $this->fechaFin . ' 23:59:59'])
            ->orderBy('fecha_movimiento', 'desc');

        if ($this->areaFiltro) {
            $query->where(function ($q) {
                $q->where('area_origen_id', $this->areaFiltro)
                  ->orWhere('area_destino_id', $this->areaFiltro);
            });
        }

        $movimientos = $query->limit(500)->get();

        $this->datosReporte = $movimientos->map(function ($mov) {
            return [
                'fecha' => $mov->fecha_movimiento->format('d/m/Y H:i'),
                'tipo' => $mov->tipo_movimiento->getLabel(),
                'medicamento' => $mov->inventario->medicamento->nombre_comercial,
                'lote' => $mov->inventario->lote,
                'cantidad' => $mov->cantidad,
                'cantidad_anterior' => $mov->cantidad_anterior,
                'cantidad_nueva' => $mov->cantidad_nueva,
                'area_origen' => $mov->areaOrigen?->nombre ?? '-',
                'area_destino' => $mov->areaDestino?->nombre ?? '-',
                'referencia' => $mov->referencia,
                'usuario' => $mov->usuario->name,
                'motivo' => $mov->motivo,
            ];
        })->toArray();

        // Resumen por tipo de movimiento
        $porTipo = $movimientos->groupBy(fn($m) => $m->tipo_movimiento->value)
            ->map(fn($g) => $g->count());

        // Calcular totales agrupados
        $entradas = ($porTipo['entrada'] ?? 0) + ($porTipo['ajuste_positivo'] ?? 0) +
                    ($porTipo['transferencia_entrada'] ?? 0) + ($porTipo['devolucion'] ?? 0);
        $salidas = ($porTipo['salida'] ?? 0) + ($porTipo['ajuste_negativo'] ?? 0) +
                   ($porTipo['transferencia_salida'] ?? 0) + ($porTipo['despacho'] ?? 0);
        $transferencias = ($porTipo['transferencia_entrada'] ?? 0) + ($porTipo['transferencia_salida'] ?? 0);
        $ajustes = ($porTipo['ajuste_positivo'] ?? 0) + ($porTipo['ajuste_negativo'] ?? 0);

        $this->totales = [
            'total_movimientos' => $movimientos->count(),
            'entradas' => $entradas,
            'salidas' => $salidas,
            'transferencias' => $transferencias,
            'ajustes' => $ajustes,
        ];

        $this->chartData = [
            'labels' => ['Entradas', 'Salidas', 'Despachos', 'Ajustes', 'Mermas'],
            'data' => [
                $porTipo['entrada'] ?? 0,
                $porTipo['salida'] ?? 0,
                $porTipo['despacho'] ?? 0,
                $ajustes,
                $porTipo['merma'] ?? 0,
            ],
        ];
    }

    public function exportarExcel()
    {
        $nombreArchivo = "reporte_farmacia_{$this->tipoReporte}_" . now()->format('Y-m-d') . ".xlsx";

        return Excel::download(
            new ReporteFarmaciaExport($this->tipoReporte, $this->datosReporte, $this->totales),
            $nombreArchivo
        );
    }

    public function exportarPdf()
    {
        $nombreArchivo = "reporte_farmacia_{$this->tipoReporte}_" . now()->format('Y-m-d') . ".pdf";

        $pdf = Pdf::loadView('exports.reporte-farmacia-pdf', [
            'tipoReporte' => $this->tipoReporte,
            'datos' => $this->datosReporte,
            'totales' => $this->totales,
            'fechaInicio' => $this->fechaInicio,
            'fechaFin' => $this->fechaFin,
        ]);

        return response()->streamDownload(
            fn() => print($pdf->output()),
            $nombreArchivo
        );
    }
}
