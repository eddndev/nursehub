<?php

namespace App\Notifications;

use App\Models\Certificacion;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CertificacionGeneradaNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected Certificacion $certificacion;

    /**
     * Create a new notification instance.
     */
    public function __construct(Certificacion $certificacion)
    {
        $this->certificacion = $certificacion;
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
        $actividad = $this->certificacion->inscripcion->actividad;

        $message = (new MailMessage)
            ->subject('Certificado Generado - ' . $actividad->titulo)
            ->greeting('Felicidades ' . $notifiable->name . '!')
            ->line('Se ha generado tu certificado por completar exitosamente la siguiente capacitacion:')
            ->line('')
            ->line('Actividad: ' . $actividad->titulo)
            ->line('Numero de Certificado: ' . $this->certificacion->numero_certificado)
            ->line('Fecha de Emision: ' . $this->certificacion->fecha_emision->format('d/m/Y'))
            ->line('Horas Certificadas: ' . $this->certificacion->horas_certificadas);

        if ($this->certificacion->fecha_vigencia_fin) {
            $message->line('Vigente hasta: ' . $this->certificacion->fecha_vigencia_fin->format('d/m/Y'));
        }

        return $message
            ->line('')
            ->line('Puedes descargar tu certificado desde el sistema.')
            ->action('Ver Mi Certificado', url('/capacitacion/dashboard'))
            ->line('Gracias por tu compromiso con tu desarrollo profesional!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'certificacion_id' => $this->certificacion->id,
            'numero_certificado' => $this->certificacion->numero_certificado,
            'actividad_titulo' => $this->certificacion->inscripcion->actividad->titulo,
            'tipo' => 'certificacion_generada',
            'mensaje' => 'Tu certificado de "' . $this->certificacion->inscripcion->actividad->titulo . '" esta listo',
        ];
    }
}
