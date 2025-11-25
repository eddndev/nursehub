<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MedicamentoProximoCaducarNotification extends Notification implements ShouldQueue
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
        $mail = (new MailMessage)
            ->subject('⚠️ Alerta: Medicamentos Próximos a Caducar')
            ->greeting('Hola ' . $notifiable->name)
            ->line('Se han detectado medicamentos próximos a caducar en el inventario.')
            ->line("**Total de alertas:** {$this->resumen['total']}")
            ->line("**Críticos (< 30 días):** {$this->resumen['criticos']}")
            ->line("**Valor en riesgo:** $" . number_format($this->resumen['valor_en_riesgo'], 2));

        if (!empty($this->resumen['items_criticos'])) {
            $mail->line('---');
            $mail->line('**Medicamentos críticos:**');
            foreach (array_slice($this->resumen['items_criticos'], 0, 5) as $item) {
                $mail->line("• {$item['medicamento']} (Lote: {$item['lote']}) - {$item['dias_restantes']} días - Cantidad: {$item['cantidad']}");
            }
        }

        return $mail
            ->action('Ver Inventario', url('/medicamentos/inventario'))
            ->line('Por favor, revise y tome las acciones necesarias.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'tipo' => 'alerta_caducidad',
            'titulo' => 'Medicamentos Próximos a Caducar',
            'mensaje' => "Hay {$this->resumen['total']} medicamentos próximos a caducar ({$this->resumen['criticos']} críticos)",
            'total' => $this->resumen['total'],
            'criticos' => $this->resumen['criticos'],
            'valor_en_riesgo' => $this->resumen['valor_en_riesgo'],
            'items' => $this->resumen['items_criticos'] ?? [],
            'url' => '/medicamentos/inventario',
        ];
    }
}
