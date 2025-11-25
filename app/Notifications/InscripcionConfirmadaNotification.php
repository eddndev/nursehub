<?php

namespace App\Notifications;

use App\Models\InscripcionCapacitacion;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InscripcionConfirmadaNotification extends Notification implements ShouldQueue
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
        $sesionesCount = $actividad->sesiones->count();

        return (new MailMessage)
            ->subject('Confirmacion de Inscripcion - ' . $actividad->titulo)
            ->greeting('Hola ' . $notifiable->name . '!')
            ->line('Te has inscrito exitosamente en la siguiente actividad de capacitacion:')
            ->line('')
            ->line('Actividad: ' . $actividad->titulo)
            ->line('Fecha de inicio: ' . $actividad->fecha_inicio->format('d/m/Y'))
            ->line('Sesiones programadas: ' . $sesionesCount)
            ->line('Estado de inscripcion: Pendiente de aprobacion')
            ->line('')
            ->line('Tu inscripcion esta pendiente de aprobacion por el coordinador.')
            ->line('Recibiras una notificacion cuando sea aprobada.')
            ->action('Ver Mis Inscripciones', url('/capacitacion/dashboard'))
            ->line('Gracias por tu interes en continuar tu desarrollo profesional!');
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
            'tipo' => 'inscripcion_confirmada',
            'mensaje' => 'Te has inscrito en: ' . $this->inscripcion->actividad->titulo,
        ];
    }
}
