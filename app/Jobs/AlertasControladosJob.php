<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\Medicamento;
use App\Models\InventarioMedicamento;
use App\Models\RegistroMedicamentoControlado;
use App\Enums\EstadoInventarioMedicamento;
use App\Notifications\MedicamentosControladosNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class AlertasControladosJob implements ShouldQueue
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
        Log::info('Ejecutando AlertasControladosJob');

        // Obtener medicamentos controlados
        $medicamentosControlados = Medicamento::where('es_controlado', true)
            ->where('activo', true)
            ->with(['inventarios' => function ($query) {
                $query->where('estado', EstadoInventarioMedicamento::DISPONIBLE);
            }])
            ->get();

        $alertasReorden = [];
        $alertasCaducidad = [];
        $movimientosSemanales = [];

        foreach ($medicamentosControlados as $medicamento) {
            $stockTotal = $medicamento->inventarios->sum('cantidad_actual');
            $stockMinimo = $medicamento->inventarios->first()?->stock_minimo ?? 10;

            // Alertas de reorden
            if ($stockTotal <= $stockMinimo) {
                $alertasReorden[] = [
                    'medicamento' => $medicamento->nombre_comercial,
                    'codigo' => $medicamento->codigo_medicamento,
                    'stock_actual' => $stockTotal,
                    'stock_minimo' => $stockMinimo,
                    'requiere_atencion_urgente' => $stockTotal === 0,
                ];
            }

            // Alertas de caducidad para controlados (30 días)
            $proximosCaducar = $medicamento->inventarios
                ->where('fecha_caducidad', '<=', now()->addDays(30))
                ->where('fecha_caducidad', '>', now());

            foreach ($proximosCaducar as $inv) {
                $alertasCaducidad[] = [
                    'medicamento' => $medicamento->nombre_comercial,
                    'lote' => $inv->lote,
                    'cantidad' => $inv->cantidad_actual,
                    'fecha_caducidad' => $inv->fecha_caducidad->format('d/m/Y'),
                    'dias_restantes' => now()->diffInDays($inv->fecha_caducidad),
                ];
            }

            // Movimientos de la última semana
            $movimientos = RegistroMedicamentoControlado::where('medicamento_id', $medicamento->id)
                ->where('fecha_operacion', '>=', now()->subDays(7))
                ->count();

            if ($movimientos > 0) {
                $movimientosSemanales[] = [
                    'medicamento' => $medicamento->nombre_comercial,
                    'total_movimientos' => $movimientos,
                ];
            }
        }

        $resumen = [
            'total_controlados' => $medicamentosControlados->count(),
            'alertas_reorden' => count($alertasReorden),
            'alertas_caducidad' => count($alertasCaducidad),
            'items_reorden' => array_slice($alertasReorden, 0, 10),
            'items_caducidad' => array_slice($alertasCaducidad, 0, 10),
            'movimientos_semanales' => $movimientosSemanales,
            'requiere_atencion_urgente' => collect($alertasReorden)->where('requiere_atencion_urgente', true)->count() > 0,
        ];

        // Solo notificar si hay alertas
        if (count($alertasReorden) === 0 && count($alertasCaducidad) === 0) {
            Log::info('No hay alertas de medicamentos controlados');
            return;
        }

        // Obtener coordinadores y farmacéuticos senior
        $responsables = User::whereHas('roles', function ($query) {
            $query->whereIn('name', ['coordinador', 'admin', 'farmaceutico_senior']);
        })->get();

        // Notificar
        foreach ($responsables as $usuario) {
            try {
                $usuario->notify(new MedicamentosControladosNotification($resumen));
            } catch (\Exception $e) {
                Log::error('Error al enviar notificación de controlados', [
                    'usuario_id' => $usuario->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        Log::info('AlertasControladosJob completado', [
            'notificaciones_enviadas' => $responsables->count(),
            'resumen' => $resumen,
        ]);
    }
}
