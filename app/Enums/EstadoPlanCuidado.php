<?php

namespace App\Enums;

enum EstadoPlanCuidado: string
{
    case ACTIVO = 'activo';
    case RESUELTO = 'resuelto';
    case CANCELADO = 'cancelado';

    public function getLabel(): string
    {
        return match ($this) {
            self::ACTIVO => 'Activo',
            self::RESUELTO => 'Resuelto',
            self::CANCELADO => 'Cancelado',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::ACTIVO => 'green',
            self::RESUELTO => 'blue',
            self::CANCELADO => 'red',
        };
    }
}
