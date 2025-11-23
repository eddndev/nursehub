<?php

namespace App\Enums;

enum NivelTriage: string
{
    case ROJO = 'rojo';
    case NARANJA = 'naranja';
    case AMARILLO = 'amarillo';
    case VERDE = 'verde';
    case AZUL = 'azul';

    public function getLabel(): string
    {
        return match ($this) {
            self::ROJO => 'Resucitación',
            self::NARANJA => 'Emergencia',
            self::AMARILLO => 'Urgente',
            self::VERDE => 'Menos Urgente',
            self::AZUL => 'No Urgente',
        };
    }

    public function getTiempoEspera(): string
    {
        return match ($this) {
            self::ROJO => 'Inmediato',
            self::NARANJA => '10-15 min',
            self::AMARILLO => '30-60 min',
            self::VERDE => '1-2 horas',
            self::AZUL => '2-4 horas',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::ROJO => 'red',
            self::NARANJA => 'orange',
            self::AMARILLO => 'yellow',
            self::VERDE => 'green',
            self::AZUL => 'blue',
        };
    }

    public function getPrioridad(): int
    {
        return match ($this) {
            self::ROJO => 1,
            self::NARANJA => 2,
            self::AMARILLO => 3,
            self::VERDE => 4,
            self::AZUL => 5,
        };
    }
}
