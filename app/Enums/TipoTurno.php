<?php

namespace App\Enums;

enum TipoTurno: string
{
    case MATUTINO = 'matutino';
    case VESPERTINO = 'vespertino';
    case NOCTURNO = 'nocturno';

    public function getLabel(): string
    {
        return match ($this) {
            self::MATUTINO => 'Matutino',
            self::VESPERTINO => 'Vespertino',
            self::NOCTURNO => 'Nocturno',
        };
    }

    public function getHoraInicio(): string
    {
        return match ($this) {
            self::MATUTINO => '07:00',
            self::VESPERTINO => '15:00',
            self::NOCTURNO => '23:00',
        };
    }

    public function getHoraFin(): string
    {
        return match ($this) {
            self::MATUTINO => '15:00',
            self::VESPERTINO => '23:00',
            self::NOCTURNO => '07:00',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::MATUTINO => 'yellow',
            self::VESPERTINO => 'orange',
            self::NOCTURNO => 'indigo',
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::MATUTINO => '☀️',
            self::VESPERTINO => '🌆',
            self::NOCTURNO => '🌙',
        };
    }
}
