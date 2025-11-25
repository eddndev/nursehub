<?php

namespace App\Enums;

enum PrioridadSolicitudMedicamento: string
{
    case NORMAL = 'normal';
    case URGENTE = 'urgente';
    case STAT = 'stat';

    public function getLabel(): string
    {
        return match ($this) {
            self::NORMAL => 'Normal',
            self::URGENTE => 'Urgente',
            self::STAT => 'STAT (Inmediato)',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::NORMAL => 'bg-gray-100 text-gray-800',
            self::URGENTE => 'bg-orange-100 text-orange-800',
            self::STAT => 'bg-red-100 text-red-800',
        };
    }

    public function getOrden(): int
    {
        return match ($this) {
            self::STAT => 1,
            self::URGENTE => 2,
            self::NORMAL => 3,
        };
    }

    public function getTiempoMaximoRespuesta(): int
    {
        return match ($this) {
            self::STAT => 15,      // 15 minutos
            self::URGENTE => 60,   // 1 hora
            self::NORMAL => 240,   // 4 horas
        };
    }
}
