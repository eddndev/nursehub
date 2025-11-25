<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\Medicamento;
use App\Models\InventarioMedicamento;
use App\Enums\EstadoInventarioMedicamento;
use App\Notifications\StockMinimoNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class AlertasStockMinimoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('Ejecutando AlertasStockMinimoJob');

        // Obtener medicamentos con stock bajo el mínimo
        $stockBajo = InventarioMedicamento::with(['medicamento', 'area'])
            ->whereColumn('cantidad_actual', '<=', 'stock_minimo')
            ->where('estado', EstadoInventarioMedicamento::DISPONIBLE)
            ->get()
            ->groupBy('medicamento_id');

        if ($stockBajo->isEmpty()) {
            Log::info('No hay medicamentos con stock bajo');
            return;
        }

        // Procesar y agrupar por medicamento
        $alertas = [];
        $criticos = 0;
        $agotados = 0;

        foreach ($stockBajo as $medicamentoId => $inventarios) {
            $medicamento = $inventarios->first()->medicamento;
            $totalStock = $inventarios->sum('cantidad_actual');
            $stockMinimo = $inventarios->first()->stock_minimo;
            $porcentaje = $stockMinimo > 0 ? round(($totalStock / $stockMinimo) * 100, 1) : 0;

            $nivel = 'medio';
            if ($totalStock === 0) {
                $nivel = 'agotado';
                $agotados++;
            } elseif ($porcentaje <= 50) {
                $nivel = 'critico';
                $criticos++;
            }

            $alertas[] = [
                'medicamento_id' => $medicamentoId,
                'medicamento' => $medicamento->nombre_comercial,
                'codigo' => $medicamento->codigo_medicamento,
                'es_controlado' => $medicamento->es_controlado,
                'total_stock' => $totalStock,
                'stock_minimo' => $stockMinimo,
                'porcentaje' => $porcentaje,
                'nivel' => $nivel,
                'areas_afectadas' => $inventarios->map(fn($inv) => [
                    'area' => $inv->area?->nombre ?? 'Almacén General',
                    'cantidad' => $inv->cantidad_actual,
                ])->toArray(),
            ];
        }

        // Ordenar por nivel de urgencia
        usort($alertas, function ($a, $b) {
            $orden = ['agotado' => 0, 'critico' => 1, 'medio' => 2];
            return ($orden[$a['nivel']] ?? 3) <=> ($orden[$b['nivel']] ?? 3);
        });

        $resumen = [
            'total' => count($alertas),
            'agotados' => $agotados,
            'criticos' => $criticos,
            'items' => array_slice($alertas, 0, 15),
            'controlados_afectados' => collect($alertas)->where('es_controlado', true)->count(),
        ];

        // Obtener usuarios farmacéuticos para notificar
        $farmaceuticos = User::whereHas('roles', function ($query) {
            $query->whereIn('name', ['farmaceutico', 'admin', 'coordinador']);
        })->get();

        // Notificar
        foreach ($farmaceuticos as $usuario) {
            try {
                $usuario->notify(new StockMinimoNotification($resumen));
            } catch (\Exception $e) {
                Log::error('Error al enviar notificación de stock mínimo', [
                    'usuario_id' => $usuario->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        Log::info('AlertasStockMinimoJob completado', [
            'notificaciones_enviadas' => $farmaceuticos->count(),
            'resumen' => $resumen,
        ]);
    }
}
