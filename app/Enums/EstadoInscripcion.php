<?php

namespace App\Enums;

enum EstadoInscripcion: string
{
    case PENDIENTE = 'pendiente';
    case APROBADA = 'aprobada';
    case RECHAZADA = 'rechazada';
    case CANCELADA = 'cancelada';
    case EN_LISTA_ESPERA = 'en_lista_espera';

    public function getLabel(): string
    {
        return match ($this) {
            self::PENDIENTE => 'Pendiente de Aprobación',
            self::APROBADA => 'Aprobada',
            self::RECHAZADA => 'Rechazada',
            self::CANCELADA => 'Cancelada',
            self::EN_LISTA_ESPERA => 'En Lista de Espera',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::PENDIENTE => '#F59E0B', // amber
            self::APROBADA => '#10B981', // green
            self::RECHAZADA => '#EF4444', // red
            self::CANCELADA => '#6B7280', // gray
            self::EN_LISTA_ESPERA => '#3B82F6', // blue
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::PENDIENTE => '⏳',
            self::APROBADA => '✅',
            self::RECHAZADA => '❌',
            self::CANCELADA => '🚫',
            self::EN_LISTA_ESPERA => '⏱️',
        };
    }

    public function isPuedeAsistir(): bool
    {
        return $this === self::APROBADA;
    }

    public function isPendiente(): bool
    {
        return $this === self::PENDIENTE;
    }
}
