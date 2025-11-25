<?php

namespace App\Enums;

enum EstadoSolicitudMedicamento: string
{
    case PENDIENTE = 'pendiente';
    case APROBADA = 'aprobada';
    case DESPACHADA = 'despachada';
    case RECHAZADA = 'rechazada';
    case CANCELADA = 'cancelada';
    case PARCIAL = 'parcial';

    public function getLabel(): string
    {
        return match ($this) {
            self::PENDIENTE => 'Pendiente',
            self::APROBADA => 'Aprobada',
            self::DESPACHADA => 'Despachada',
            self::RECHAZADA => 'Rechazada',
            self::CANCELADA => 'Cancelada',
            self::PARCIAL => 'Despacho Parcial',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::PENDIENTE => 'bg-yellow-100 text-yellow-800',
            self::APROBADA => 'bg-blue-100 text-blue-800',
            self::DESPACHADA => 'bg-green-100 text-green-800',
            self::RECHAZADA => 'bg-red-100 text-red-800',
            self::CANCELADA => 'bg-gray-100 text-gray-800',
            self::PARCIAL => 'bg-orange-100 text-orange-800',
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::PENDIENTE => 'heroicon-o-clock',
            self::APROBADA => 'heroicon-o-check-circle',
            self::DESPACHADA => 'heroicon-o-truck',
            self::RECHAZADA => 'heroicon-o-x-circle',
            self::CANCELADA => 'heroicon-o-ban',
            self::PARCIAL => 'heroicon-o-arrow-path',
        };
    }

    public function puedeModificarse(): bool
    {
        return in_array($this, [self::PENDIENTE]);
    }

    public function puedeCancelarse(): bool
    {
        return in_array($this, [self::PENDIENTE, self::APROBADA]);
    }
}
