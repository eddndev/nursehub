<?php

namespace App\Notifications;

use App\Models\InscripcionCapacitacion;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InscripcionAprobadaNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected InscripcionCapacitacion $inscripcion;

    /**
     * Create a new notification instance.
     */
    public function __construct(InscripcionCapacitacion $inscripcion)
    {
        $this->inscripcion = $inscripcion;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $actividad = $this->inscripcion->actividad;
        $sesiones = $actividad->sesiones->sortBy('fecha');
        $primeraSesion = $sesiones->first();

        $message = (new MailMessage)
            ->subject('Inscripcion Aprobada - ' . $actividad->titulo)
            ->greeting('Felicidades ' . $notifiable->name . '!')
            ->line('Tu inscripcion ha sido APROBADA para la siguiente actividad:')
            ->line('')
            ->line('Actividad: ' . $actividad->titulo)
            ->line('Modalidad: ' . ucfirst($actividad->modalidad ?? 'presencial'))
            ->line('Duracion: ' . $actividad->duracion_horas . ' horas');

        if ($primeraSesion) {
            $message->line('')
                ->line('Primera sesion:')
                ->line('Fecha: ' . $primeraSesion->fecha->format('d/m/Y'))
                ->line('Hora: ' . $primeraSesion->hora_inicio . ' - ' . $primeraSesion->hora_fin);

            if ($primeraSesion->ubicacion) {
                $message->line('Ubicacion: ' . $primeraSesion->ubicacion);
            }
        }

        return $message
            ->line('')
            ->line('Recuerda asistir puntualmente a todas las sesiones.')
            ->action('Ver Detalle de la Actividad', url('/capacitacion/dashboard'))
            ->line('Te esperamos!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'inscripcion_id' => $this->inscripcion->id,
            'actividad_id' => $this->inscripcion->actividad_id,
            'actividad_titulo' => $this->inscripcion->actividad->titulo,
            'tipo' => 'inscripcion_aprobada',
            'mensaje' => 'Tu inscripcion en "' . $this->inscripcion->actividad->titulo . '" ha sido aprobada',
        ];
    }
}
