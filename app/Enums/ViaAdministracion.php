<?php

namespace App\Enums;

enum ViaAdministracion: string
{
    case ORAL = 'oral';
    case PARENTERAL = 'parenteral';
    case ENTERAL = 'enteral';
    case SONDA = 'sonda';
    case ORINA = 'orina';
    case EVACUACION = 'evacuacion';
    case VOMITO = 'vomito';
    case DRENAJE = 'drenaje';
    case OTRO = 'otro';

    public function getLabel(): string
    {
        return match ($this) {
            self::ORAL => 'Vía Oral',
            self::PARENTERAL => 'Parenteral (IV)',
            self::ENTERAL => 'Enteral',
            self::SONDA => 'Sonda Nasogástrica',
            self::ORINA => 'Orina',
            self::EVACUACION => 'Evacuación',
            self::VOMITO => 'Vómito',
            self::DRENAJE => 'Drenaje',
            self::OTRO => 'Otro',
        };
    }

    public static function ingresos(): array
    {
        return [self::ORAL, self::PARENTERAL, self::ENTERAL, self::SONDA];
    }

    public static function egresos(): array
    {
        return [self::ORINA, self::EVACUACION, self::VOMITO, self::DRENAJE, self::OTRO];
    }
}
