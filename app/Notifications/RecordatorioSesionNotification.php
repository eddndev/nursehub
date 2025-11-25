<?php

namespace App\Notifications;

use App\Models\InscripcionCapacitacion;
use App\Models\SesionCapacitacion;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RecordatorioSesionNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected SesionCapacitacion $sesion;
    protected InscripcionCapacitacion $inscripcion;

    /**
     * Create a new notification instance.
     */
    public function __construct(SesionCapacitacion $sesion, InscripcionCapacitacion $inscripcion)
    {
        $this->sesion = $sesion;
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
        $actividad = $this->sesion->actividad;

        $message = (new MailMessage)
            ->subject('Recordatorio: Sesion de Capacitacion Manana - ' . $actividad->titulo)
            ->greeting('Hola ' . $notifiable->name . '!')
            ->line('Te recordamos que manana tienes una sesion de capacitacion programada:')
            ->line('')
            ->line('Actividad: ' . $actividad->titulo)
            ->line('Sesion: ' . $this->sesion->numero_sesion . ' - ' . ($this->sesion->titulo ?? 'Sin titulo'))
            ->line('Fecha: ' . $this->sesion->fecha->format('d/m/Y'))
            ->line('Hora: ' . $this->sesion->hora_inicio . ' - ' . $this->sesion->hora_fin);

        if ($this->sesion->ubicacion) {
            $message->line('Ubicacion: ' . $this->sesion->ubicacion);
        }

        if ($this->sesion->url_virtual) {
            $message->line('Enlace virtual: ' . $this->sesion->url_virtual);
        }

        return $message
            ->line('')
            ->line('Por favor, asegurate de asistir puntualmente.')
            ->action('Ver Mis Inscripciones', url('/capacitacion/dashboard'))
            ->line('Nos vemos manana!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'sesion_id' => $this->sesion->id,
            'inscripcion_id' => $this->inscripcion->id,
            'actividad_titulo' => $this->sesion->actividad->titulo,
            'sesion_numero' => $this->sesion->numero_sesion,
            'fecha' => $this->sesion->fecha->format('Y-m-d'),
            'hora' => $this->sesion->hora_inicio,
            'tipo' => 'recordatorio_sesion',
            'mensaje' => 'Manana: Sesion ' . $this->sesion->numero_sesion . ' de "' . $this->sesion->actividad->titulo . '"',
        ];
    }
}
