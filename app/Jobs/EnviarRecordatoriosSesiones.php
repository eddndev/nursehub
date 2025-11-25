<?php

namespace App\Jobs;

use App\Enums\EstadoInscripcion;
use App\Models\SesionCapacitacion;
use App\Notifications\RecordatorioSesionNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class EnviarRecordatoriosSesiones implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

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
        // Obtener sesiones de manana
        $sesionesManana = SesionCapacitacion::whereDate('fecha', now()->addDay())
            ->with(['actividad.inscripciones.enfermero.user'])
            ->get();

        $enviados = 0;

        foreach ($sesionesManana as $sesion) {
            foreach ($sesion->actividad->inscripciones as $inscripcion) {
                // Solo enviar a inscripciones activas
                if (in_array($inscripcion->estado->value, [
                    EstadoInscripcion::PENDIENTE->value,
                    EstadoInscripcion::APROBADA->value
                ])) {
                    try {
                        $inscripcion->enfermero->user->notify(
                            new RecordatorioSesionNotification($sesion, $inscripcion)
                        );
                        $enviados++;
                    } catch (\Exception $e) {
                        Log::error('Error enviando recordatorio de sesion', [
                            'sesion_id' => $sesion->id,
                            'inscripcion_id' => $inscripcion->id,
                            'error' => $e->getMessage(),
                        ]);
                    }
                }
            }
        }

        Log::info('Recordatorios de sesiones enviados', [
            'sesiones_procesadas' => $sesionesManana->count(),
            'recordatorios_enviados' => $enviados,
        ]);
    }
}
