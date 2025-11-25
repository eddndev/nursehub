<?php

namespace App\Enums;

enum RiesgoEscala: string
{
    case BAJO = 'bajo';
    case MEDIO = 'medio';
    case ALTO = 'alto';
    case MUY_ALTO = 'muy_alto';
    case SIN_RIESGO = 'sin_riesgo';

    public function getLabel(): string
    {
        return match ($this) {
            self::BAJO => 'Riesgo Bajo',
            self::MEDIO => 'Riesgo Medio',
            self::ALTO => 'Riesgo Alto',
            self::MUY_ALTO => 'Riesgo Muy Alto',
            self::SIN_RIESGO => 'Sin Riesgo',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::BAJO => 'green',
            self::MEDIO => 'yellow',
            self::ALTO => 'orange',
            self::MUY_ALTO => 'red',
            self::SIN_RIESGO => 'gray',
        };
    }
}
