<?php

namespace App\Jobs;

use App\Models\Certificacion;
use App\Models\InscripcionCapacitacion;
use App\Models\User;
use App\Notifications\CertificacionGeneradaNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class GenerarCertificacionesMasivas implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 600;

    protected array $inscripcionesIds;
    protected int $userId;
    protected int $mesesVigencia;
    protected ?string $competenciasDesarrolladas;
    protected ?string $observaciones;

    /**
     * Create a new job instance.
     */
    public function __construct(
        array $inscripcionesIds,
        int $userId,
        int $mesesVigencia = 12,
        ?string $competenciasDesarrolladas = null,
        ?string $observaciones = null
    ) {
        $this->inscripcionesIds = $inscripcionesIds;
        $this->userId = $userId;
        $this->mesesVigencia = $mesesVigencia;
        $this->competenciasDesarrolladas = $competenciasDesarrolladas;
        $this->observaciones = $observaciones;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $generadas = 0;
        $errores = 0;

        foreach ($this->inscripcionesIds as $inscripcionId) {
            try {
                $inscripcion = InscripcionCapacitacion::with(['actividad', 'enfermero.user', 'certificacion'])
                    ->find($inscripcionId);

                if (!$inscripcion) {
                    Log::warning('Certificación masiva: Inscripción no encontrada', ['id' => $inscripcionId]);
                    $errores++;
                    continue;
                }

                // Si ya tiene certificación, omitir
                if ($inscripcion->certificacion) {
                    Log::info('Certificación masiva: Ya tiene certificación', ['id' => $inscripcionId]);
                    continue;
                }

                // Verificar que cumple los criterios
                if (!$inscripcion->puedeObtenerCertificado()) {
                    Log::warning('Certificación masiva: No cumple criterios', [
                        'id' => $inscripcionId,
                        'asistencia_minima' => $inscripcion->cumpleAsistenciaMinima(),
                        'calificacion_minima' => $inscripcion->cumpleCalificacionMinima(),
                    ]);
                    $errores++;
                    continue;
                }

                // Generar certificación
                $certificacion = $this->generarCertificacion($inscripcion);

                // Notificar al enfermero
                if ($certificacion && $inscripcion->enfermero && $inscripcion->enfermero->user) {
                    $inscripcion->enfermero->user->notify(new CertificacionGeneradaNotification($certificacion));
                }

                $generadas++;
            } catch (\Exception $e) {
                Log::error('Error generando certificación masiva', [
                    'inscripcion_id' => $inscripcionId,
                    'error' => $e->getMessage(),
                ]);
                $errores++;
            }
        }

        Log::info('Generación masiva de certificaciones completada', [
            'total' => count($this->inscripcionesIds),
            'generadas' => $generadas,
            'errores' => $errores,
            'usuario' => $this->userId,
        ]);

        // Notificar al usuario que inició el proceso
        $user = User::find($this->userId);
        if ($user) {
            // Podría implementarse una notificación de resultado
        }
    }

    protected function generarCertificacion(InscripcionCapacitacion $inscripcion): Certificacion
    {
        $numeroCertificado = Certificacion::generarNumeroCertificado();
        $hashVerificacion = Certificacion::generarHashVerificacion($inscripcion, $numeroCertificado);

        $fechaEmision = now();
        $fechaVigenciaInicio = $fechaEmision->copy();
        $fechaVigenciaFin = $this->mesesVigencia > 0
            ? $fechaEmision->copy()->addMonths($this->mesesVigencia)
            : null;

        return Certificacion::create([
            'inscripcion_id' => $inscripcion->id,
            'numero_certificado' => $numeroCertificado,
            'fecha_emision' => $fechaEmision,
            'fecha_vigencia_inicio' => $fechaVigenciaInicio,
            'fecha_vigencia_fin' => $fechaVigenciaFin,
            'horas_certificadas' => $inscripcion->actividad->duracion_horas,
            'calificacion_obtenida' => $inscripcion->calificacion_evaluacion ?? $inscripcion->calificacion_final,
            'porcentaje_asistencia' => $inscripcion->porcentaje_asistencia,
            'competencias_desarrolladas' => $this->competenciasDesarrolladas,
            'observaciones' => $this->observaciones,
            'hash_verificacion' => $hashVerificacion,
            'emitido_por' => $this->userId,
            'emitido_at' => now(),
        ]);
    }
}
