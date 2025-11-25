<?php

namespace App\Enums;

enum EstadoTurno: string
{
    case ACTIVO = 'activo';
    case CERRADO = 'cerrado';

    public function getLabel(): string
    {
        return match ($this) {
            self::ACTIVO => 'Activo',
            self::CERRADO => 'Cerrado',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::ACTIVO => 'green',
            self::CERRADO => 'gray',
        };
    }
}
