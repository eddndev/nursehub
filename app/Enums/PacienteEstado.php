<?php

namespace App\Enums;

enum PacienteEstado: string
{
    case ACTIVO = 'activo';
    case DADO_ALTA = 'dado_alta';
    case TRANSFERIDO = 'transferido';
    case FALLECIDO = 'fallecido';

    public function getLabel(): string
    {
        return match ($this) {
            self::ACTIVO => 'Activo',
            self::DADO_ALTA => 'Dado de Alta',
            self::TRANSFERIDO => 'Transferido',
            self::FALLECIDO => 'Fallecido',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::ACTIVO => 'green',
            self::DADO_ALTA => 'blue',
            self::TRANSFERIDO => 'orange',
            self::FALLECIDO => 'red',
        };
    }
}
