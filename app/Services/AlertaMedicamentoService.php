<?php

namespace App\Services;

use App\Models\InventarioMedicamento;
use App\Models\Medicamento;
use App\Enums\EstadoInventarioMedicamento;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Notification;

class AlertaMedicamentoService
{
    /**
     * Obtiene medicamentos próximos a caducar
     *
     * @param int $diasAnticipacion
     * @return Collection
     */
    public function obtenerProximosCaducar(int $diasAnticipacion = 60): Collection
    {
        return InventarioMedicamento::with(['medicamento', 'area'])
            ->where('fecha_caducidad', '<=', now()->addDays($diasAnticipacion))
            ->where('fecha_caducidad', '>', now())
            ->where('cantidad_actual', '>', 0)
            ->where('estado', EstadoInventarioMedicamento::DISPONIBLE)
            ->orderBy('fecha_caducidad', 'asc')
            ->get()
            ->map(function ($inventario) {
                return [
                    'id' => $inventario->id,
                    'medicamento' => $inventario->medicamento->nombre_comercial,
                    'medicamento_codigo' => $inventario->medicamento->codigo_medicamento,
                    'lote' => $inventario->lote,
                    'cantidad' => $inventario->cantidad_actual,
                    'fecha_caducidad' => $inventario->fecha_caducidad,
                    'dias_restantes' => now()->diffInDays($inventario->fecha_caducidad, false),
                    'area' => $inventario->area ? $inventario->area->nombre : 'Almacén General',
                    'valor_total' => $inventario->valor_total,
                    'nivel_urgencia' => $this->calcularNivelUrgenciaCaducidad($inventario),
                ];
            });
    }

    /**
     * Obtiene medicamentos con stock bajo el mínimo
     *
     * @return Collection
     */
    public function obtenerStockBajo(): Collection
    {
        return InventarioMedicamento::with(['medicamento', 'area'])
            ->whereColumn('cantidad_actual', '<=', 'stock_minimo')
            ->where('estado', EstadoInventarioMedicamento::DISPONIBLE)
            ->orderBy('cantidad_actual', 'asc')
            ->get()
            ->groupBy('medicamento_id')
            ->map(function ($inventarios, $medicamentoId) {
                $medicamento = $inventarios->first()->medicamento;
                $totalStock = $inventarios->sum('cantidad_actual');
                $stockMinimo = $inventarios->first()->stock_minimo;

                return [
                    'medicamento_id' => $medicamentoId,
                    'medicamento' => $medicamento->nombre_comercial,
                    'medicamento_codigo' => $medicamento->codigo_medicamento,
                    'total_stock' => $totalStock,
                    'stock_minimo' => $stockMinimo,
                    'porcentaje_stock' => $stockMinimo > 0 ? round(($totalStock / $stockMinimo) * 100, 1) : 0,
                    'es_controlado' => $medicamento->es_controlado,
                    'areas_afectadas' => $inventarios->map(function ($inv) {
                        return [
                            'area' => $inv->area ? $inv->area->nombre : 'Almacén General',
                            'cantidad' => $inv->cantidad_actual,
                            'minimo' => $inv->stock_minimo,
                        ];
                    })->toArray(),
                    'nivel_urgencia' => $this->calcularNivelUrgenciaStock($totalStock, $stockMinimo),
                ];
            })->values();
    }

    /**
     * Obtiene medicamentos ya caducados
     *
     * @return Collection
     */
    public function obtenerCaducados(): Collection
    {
        return InventarioMedicamento::with(['medicamento', 'area'])
            ->where('fecha_caducidad', '<', now())
            ->where('cantidad_actual', '>', 0)
            ->whereIn('estado', [
                EstadoInventarioMedicamento::DISPONIBLE,
                EstadoInventarioMedicamento::CUARENTENA,
            ])
            ->orderBy('fecha_caducidad', 'asc')
            ->get()
            ->map(function ($inventario) {
                return [
                    'id' => $inventario->id,
                    'medicamento' => $inventario->medicamento->nombre_comercial,
                    'lote' => $inventario->lote,
                    'cantidad' => $inventario->cantidad_actual,
                    'fecha_caducidad' => $inventario->fecha_caducidad,
                    'dias_caducado' => now()->diffInDays($inventario->fecha_caducidad, false),
                    'area' => $inventario->area ? $inventario->area->nombre : 'Almacén General',
                    'valor_perdido' => $inventario->valor_total,
                ];
            });
    }

    /**
     * Obtiene medicamentos controlados que requieren reorden
     *
     * @return Collection
     */
    public function obtenerControladosParaReorden(): Collection
    {
        $medicamentosControlados = Medicamento::where('es_controlado', true)
            ->where('activo', true)
            ->with('inventarios')
            ->get();

        return $medicamentosControlados
            ->filter(function ($medicamento) {
                $stockTotal = $medicamento->inventarios
                    ->where('estado', EstadoInventarioMedicamento::DISPONIBLE)
                    ->sum('cantidad_actual');

                $stockMinimo = $medicamento->inventarios->first()->stock_minimo ?? 10;

                return $stockTotal <= $stockMinimo;
            })
            ->map(function ($medicamento) {
                $inventarios = $medicamento->inventarios->where('estado', EstadoInventarioMedicamento::DISPONIBLE);
                $stockTotal = $inventarios->sum('cantidad_actual');
                $stockMinimo = $inventarios->first()->stock_minimo ?? 10;

                return [
                    'medicamento_id' => $medicamento->id,
                    'medicamento' => $medicamento->nombre_comercial,
                    'codigo' => $medicamento->codigo_medicamento,
                    'stock_actual' => $stockTotal,
                    'stock_minimo' => $stockMinimo,
                    'categoria' => $medicamento->categoria->getLabel(),
                    'requiere_atencion_urgente' => $stockTotal === 0,
                ];
            })->values();
    }

    /**
     * Genera un resumen ejecutivo de todas las alertas
     *
     * @return array
     */
    public function obtenerResumenAlertas(): array
    {
        $proximosCaducar = $this->obtenerProximosCaducar(60);
        $stockBajo = $this->obtenerStockBajo();
        $caducados = $this->obtenerCaducados();
        $controladosReorden = $this->obtenerControladosParaReorden();

        // Calcular valor en riesgo
        $valorEnRiesgo = $proximosCaducar->sum('valor_total') + $caducados->sum('valor_perdido');

        return [
            'total_alertas' => $proximosCaducar->count() + $stockBajo->count() + $caducados->count() + $controladosReorden->count(),
            'proximos_caducar' => [
                'count' => $proximosCaducar->count(),
                'urgentes' => $proximosCaducar->where('nivel_urgencia', 'critico')->count(),
                'items' => $proximosCaducar->take(5)->toArray(),
            ],
            'stock_bajo' => [
                'count' => $stockBajo->count(),
                'criticos' => $stockBajo->where('nivel_urgencia', 'critico')->count(),
                'items' => $stockBajo->take(5)->toArray(),
            ],
            'caducados' => [
                'count' => $caducados->count(),
                'valor_perdido' => $caducados->sum('valor_perdido'),
                'items' => $caducados->take(5)->toArray(),
            ],
            'controlados_reorden' => [
                'count' => $controladosReorden->count(),
                'urgentes' => $controladosReorden->where('requiere_atencion_urgente', true)->count(),
                'items' => $controladosReorden->take(5)->toArray(),
            ],
            'valor_en_riesgo' => round($valorEnRiesgo, 2),
            'requiere_accion_inmediata' => $caducados->count() > 0 || $controladosReorden->where('requiere_atencion_urgente', true)->count() > 0,
        ];
    }

    /**
     * Calcula el nivel de urgencia basado en días restantes para caducidad
     *
     * @param InventarioMedicamento $inventario
     * @return string
     */
    private function calcularNivelUrgenciaCaducidad(InventarioMedicamento $inventario): string
    {
        $diasRestantes = now()->diffInDays($inventario->fecha_caducidad, false);

        if ($diasRestantes <= 30) {
            return 'critico';
        } elseif ($diasRestantes <= 45) {
            return 'alto';
        } else {
            return 'medio';
        }
    }

    /**
     * Calcula el nivel de urgencia basado en stock
     *
     * @param int $stockActual
     * @param int $stockMinimo
     * @return string
     */
    private function calcularNivelUrgenciaStock(int $stockActual, int $stockMinimo): string
    {
        if ($stockActual === 0) {
            return 'critico';
        }

        $porcentaje = ($stockActual / $stockMinimo) * 100;

        if ($porcentaje <= 50) {
            return 'critico';
        } elseif ($porcentaje <= 75) {
            return 'alto';
        } else {
            return 'medio';
        }
    }

    /**
     * Marca inventarios caducados y cambia su estado
     *
     * @return int Cantidad de items actualizados
     */
    public function marcarCaducados(): int
    {
        $caducados = InventarioMedicamento::where('fecha_caducidad', '<', now())
            ->where('estado', '!=', EstadoInventarioMedicamento::CADUCADO)
            ->get();

        foreach ($caducados as $inventario) {
            $inventario->update(['estado' => EstadoInventarioMedicamento::CADUCADO]);
        }

        return $caducados->count();
    }
}
