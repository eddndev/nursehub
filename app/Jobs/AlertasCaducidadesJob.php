<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\InventarioMedicamento;
use App\Enums\EstadoInventarioMedicamento;
use App\Notifications\MedicamentoProximoCaducarNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class AlertasCaducidadesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected int $diasAnticipacion;

    /**
     * Create a new job instance.
     */
    public function __construct(int $diasAnticipacion = 60)
    {
        $this->diasAnticipacion = $diasAnticipacion;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('Ejecutando AlertasCaducidadesJob', [
            'dias_anticipacion' => $this->diasAnticipacion,
        ]);

        // Obtener medicamentos próximos a caducar
        $proximosCaducar = InventarioMedicamento::with(['medicamento', 'area'])
            ->where('fecha_caducidad', '<=', now()->addDays($this->diasAnticipacion))
            ->where('fecha_caducidad', '>', now())
            ->where('cantidad_actual', '>', 0)
            ->where('estado', EstadoInventarioMedicamento::DISPONIBLE)
            ->orderBy('fecha_caducidad', 'asc')
            ->get();

        if ($proximosCaducar->isEmpty()) {
            Log::info('No hay medicamentos próximos a caducar');
            return;
        }

        // Agrupar por urgencia
        $criticos = $proximosCaducar->filter(fn($inv) => now()->diffInDays($inv->fecha_caducidad) <= 30);
        $altos = $proximosCaducar->filter(fn($inv) => now()->diffInDays($inv->fecha_caducidad) > 30 && now()->diffInDays($inv->fecha_caducidad) <= 45);
        $medios = $proximosCaducar->filter(fn($inv) => now()->diffInDays($inv->fecha_caducidad) > 45);

        // Obtener usuarios farmacéuticos para notificar
        $farmaceuticos = User::whereHas('roles', function ($query) {
            $query->whereIn('name', ['farmaceutico', 'admin', 'coordinador']);
        })->get();

        // Preparar datos del resumen
        $resumen = [
            'total' => $proximosCaducar->count(),
            'criticos' => $criticos->count(),
            'altos' => $altos->count(),
            'medios' => $medios->count(),
            'valor_en_riesgo' => $proximosCaducar->sum(fn($inv) => $inv->cantidad_actual * $inv->costo_unitario),
            'items_criticos' => $criticos->take(10)->map(fn($inv) => [
                'medicamento' => $inv->medicamento->nombre_comercial,
                'lote' => $inv->lote,
                'cantidad' => $inv->cantidad_actual,
                'fecha_caducidad' => $inv->fecha_caducidad->format('d/m/Y'),
                'dias_restantes' => now()->diffInDays($inv->fecha_caducidad),
                'area' => $inv->area?->nombre ?? 'Almacén General',
            ])->toArray(),
        ];

        // Notificar a los farmacéuticos
        foreach ($farmaceuticos as $usuario) {
            try {
                $usuario->notify(new MedicamentoProximoCaducarNotification($resumen));
            } catch (\Exception $e) {
                Log::error('Error al enviar notificación de caducidades', [
                    'usuario_id' => $usuario->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        // Marcar automáticamente como caducados los que ya pasaron
        $caducados = InventarioMedicamento::where('fecha_caducidad', '<', now())
            ->where('estado', '!=', EstadoInventarioMedicamento::CADUCADO)
            ->update(['estado' => EstadoInventarioMedicamento::CADUCADO]);

        Log::info('AlertasCaducidadesJob completado', [
            'notificaciones_enviadas' => $farmaceuticos->count(),
            'medicamentos_marcados_caducados' => $caducados,
            'resumen' => $resumen,
        ]);
    }
}
