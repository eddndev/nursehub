<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MedicamentosControladosNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected array $resumen;

    /**
     * Create a new notification instance.
     */
    public function __construct(array $resumen)
    {
        $this->resumen = $resumen;
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
        $urgente = $this->resumen['requiere_atencion_urgente'] ? '🚨 URGENTE: ' : '';

        $mail = (new MailMessage)
            ->subject($urgente . 'Reporte Semanal de Medicamentos Controlados')
            ->greeting('Hola ' . $notifiable->name)
            ->line('Reporte semanal del estado de medicamentos controlados.')
            ->line("**Total de medicamentos controlados:** {$this->resumen['total_controlados']}")
            ->line("**Alertas de reorden:** {$this->resumen['alertas_reorden']}")
            ->line("**Alertas de caducidad:** {$this->resumen['alertas_caducidad']}");

        if (!empty($this->resumen['items_reorden'])) {
            $mail->line('---');
            $mail->line('**Medicamentos que requieren reorden:**');
            foreach (array_slice($this->resumen['items_reorden'], 0, 5) as $item) {
                $urgente = $item['requiere_atencion_urgente'] ? '🔴 AGOTADO' : '🟠';
                $mail->line("• {$urgente} {$item['medicamento']}: {$item['stock_actual']}/{$item['stock_minimo']}");
            }
        }

        if (!empty($this->resumen['items_caducidad'])) {
            $mail->line('---');
            $mail->line('**Medicamentos próximos a caducar (30 días):**');
            foreach (array_slice($this->resumen['items_caducidad'], 0, 5) as $item) {
                $mail->line("• {$item['medicamento']} (Lote: {$item['lote']}) - {$item['dias_restantes']} días");
            }
        }

        if (!empty($this->resumen['movimientos_semanales'])) {
            $mail->line('---');
            $mail->line('**Movimientos de la semana:**');
            foreach ($this->resumen['movimientos_semanales'] as $mov) {
                $mail->line("• {$mov['medicamento']}: {$mov['total_movimientos']} movimientos");
            }
        }

        return $mail
            ->action('Ver Reporte de Controlados', url('/medicamentos/reportes?tipo=controlados'))
            ->line('Este es un reporte automático semanal.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'tipo' => 'reporte_controlados',
            'titulo' => 'Reporte de Medicamentos Controlados',
            'mensaje' => "Alertas: {$this->resumen['alertas_reorden']} reorden, {$this->resumen['alertas_caducidad']} caducidad",
            'total_controlados' => $this->resumen['total_controlados'],
            'alertas_reorden' => $this->resumen['alertas_reorden'],
            'alertas_caducidad' => $this->resumen['alertas_caducidad'],
            'requiere_atencion_urgente' => $this->resumen['requiere_atencion_urgente'],
            'url' => '/medicamentos/reportes?tipo=controlados',
        ];
    }
}
