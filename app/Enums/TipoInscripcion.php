<?php

namespace App\Enums;

enum TipoInscripcion: string
{
    case VOLUNTARIA = 'voluntaria';
    case ASIGNADA = 'asignada';
    case OBLIGATORIA = 'obligatoria';

    public function getLabel(): string
    {
        return match ($this) {
            self::VOLUNTARIA => 'Voluntaria',
            self::ASIGNADA => 'Asignada',
            self::OBLIGATORIA => 'Obligatoria',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::VOLUNTARIA => '#10B981', // green
            self::ASIGNADA => '#3B82F6', // blue
            self::OBLIGATORIA => '#EF4444', // red
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::VOLUNTARIA => '🙋',
            self::ASIGNADA => '📋',
            self::OBLIGATORIA => '⚠️',
        };
    }

    public function isPuedeRechazar(): bool
    {
        return $this === self::VOLUNTARIA;
    }
}
