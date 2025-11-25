<?php

namespace App\Enums;

enum EstadoInventarioMedicamento: string
{
    case DISPONIBLE = 'disponible';
    case CUARENTENA = 'cuarentena';
    case CADUCADO = 'caducado';
    case AGOTADO = 'agotado';
    case BLOQUEADO = 'bloqueado';

    public function getLabel(): string
    {
        return match ($this) {
            self::DISPONIBLE => 'Disponible',
            self::CUARENTENA => 'En Cuarentena',
            self::CADUCADO => 'Caducado',
            self::AGOTADO => 'Agotado',
            self::BLOQUEADO => 'Bloqueado',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::DISPONIBLE => 'bg-green-100 text-green-800',
            self::CUARENTENA => 'bg-yellow-100 text-yellow-800',
            self::CADUCADO => 'bg-red-100 text-red-800',
            self::AGOTADO => 'bg-gray-100 text-gray-800',
            self::BLOQUEADO => 'bg-orange-100 text-orange-800',
        };
    }

    public function puedeDespachar(): bool
    {
        return $this === self::DISPONIBLE;
    }
}
