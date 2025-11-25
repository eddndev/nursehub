<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StockMinimoNotification extends Notification implements ShouldQueue
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
            ->subject('⚠️ Alerta: Stock Bajo de Medicamentos')
            ->greeting('Hola ' . $notifiable->name)
            ->line('Se han detectado medicamentos con stock bajo el mínimo establecido.')
            ->line("**Total de medicamentos afectados:** {$this->resumen['total']}")
            ->line("**Agotados:** {$this->resumen['agotados']}")
            ->line("**Críticos:** {$this->resumen['criticos']}");

        if ($this->resumen['controlados_afectados'] > 0) {
            $mail->line("**⚠️ Controlados afectados:** {$this->resumen['controlados_afectados']}");
        }

        if (!empty($this->resumen['items'])) {
            $mail->line('---');
            $mail->line('**Medicamentos con stock bajo:**');
            foreach (array_slice($this->resumen['items'], 0, 5) as $item) {
                $estado = $item['nivel'] === 'agotado' ? '🔴 AGOTADO' : ($item['nivel'] === 'critico' ? '🟠 CRÍTICO' : '🟡 BAJO');
                $controlado = $item['es_controlado'] ? ' [CONTROLADO]' : '';
                $mail->line("• {$item['medicamento']}{$controlado}: {$item['total_stock']}/{$item['stock_minimo']} ({$item['porcentaje']}%) - {$estado}");
            }
        }

        return $mail
            ->action('Ver Inventario', url('/medicamentos/inventario'))
            ->line('Por favor, gestione los pedidos necesarios.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'tipo' => 'alerta_stock_minimo',
            'titulo' => 'Stock Bajo de Medicamentos',
            'mensaje' => "Hay {$this->resumen['total']} medicamentos con stock bajo ({$this->resumen['agotados']} agotados)",
            'total' => $this->resumen['total'],
            'agotados' => $this->resumen['agotados'],
            'criticos' => $this->resumen['criticos'],
            'controlados_afectados' => $this->resumen['controlados_afectados'],
            'items' => $this->resumen['items'] ?? [],
            'url' => '/medicamentos/inventario',
        ];
    }
}
