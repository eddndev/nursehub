<?php

namespace App\Enums;

enum TipoBalance: string
{
    case INGRESO = 'ingreso';
    case EGRESO = 'egreso';

    public function getLabel(): string
    {
        return match ($this) {
            self::INGRESO => 'Ingreso',
            self::EGRESO => 'Egreso',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::INGRESO => 'blue',
            self::EGRESO => 'orange',
        };
    }
}
