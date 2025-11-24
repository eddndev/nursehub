<?php

namespace App\Enums;

enum TipoEscala: string
{
    case EVA = 'eva';
    case BRADEN = 'braden';
    case GLASGOW = 'glasgow';
    case NORTON = 'norton';

    public function getLabel(): string
    {
        return match ($this) {
            self::EVA => 'Escala EVA (Dolor)',
            self::BRADEN => 'Escala Braden (UPP)',
            self::GLASGOW => 'Escala Glasgow (Conciencia)',
            self::NORTON => 'Escala Norton (UPP)',
        };
    }
}
