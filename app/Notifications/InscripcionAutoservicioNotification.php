<?php

namespace App\Notifications;

use App\Models\InscripcionCapacitacion;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InscripcionAutoservicioNotification extends Notification implements ShouldQueue
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
        $enfermero = $this->inscripcion->enfermero;

        return (new MailMessage)
            ->subject('Nueva Inscripcion por Autoservicio - ' . $actividad->titulo)
            ->greeting('Hola ' . $notifiable->name . '!')
            ->line('Se ha registrado una nueva inscripcion por autoservicio:')
            ->line('')
            ->line('Actividad: ' . $actividad->titulo)
            ->line('Enfermero: ' . $enfermero->user->name)
            ->line('Area: ' . ($enfermero->areaFija ? $enfermero->areaFija->nombre : 'Sin asignar'))
            ->line('Fecha de inscripcion: ' . now()->format('d/m/Y H:i'))
            ->line('')
            ->line('La inscripcion esta pendiente de aprobacion.')
            ->action('Gestionar Inscripciones', url('/capacitacion/inscripciones'))
            ->line('Gracias por administrar las capacitaciones!');
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
            'enfermero_nombre' => $this->inscripcion->enfermero->user->name,
            'tipo' => 'inscripcion_autoservicio',
            'mensaje' => $this->inscripcion->enfermero->user->name . ' se inscribio en "' . $this->inscripcion->actividad->titulo . '"',
        ];
    }
}
