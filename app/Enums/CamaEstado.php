<?php

namespace App\Enums;

/**
 * Estado de Cama Enum
 *
 * Define los estados posibles de una cama en el hospital.
 */
enum CamaEstado: string
{
    case LIBRE = 'libre';
    case OCUPADA = 'ocupada';
    case EN_LIMPIEZA = 'en_limpieza';
    case EN_MANTENIMIENTO = 'en_mantenimiento';

    /**
     * Obtener el nombre legible del estado
     */
    public function label(): string
    {
        return match($this) {
            self::LIBRE => 'Libre',
            self::OCUPADA => 'Ocupada',
            self::EN_LIMPIEZA => 'En Limpieza',
            self::EN_MANTENIMIENTO => 'En Mantenimiento',
        };
    }

    /**
     * Obtener el color asociado al estado (para badges)
     */
    public function color(): string
    {
        return match($this) {
            self::LIBRE => 'green',
            self::OCUPADA => 'red',
            self::EN_LIMPIEZA => 'yellow',
            self::EN_MANTENIMIENTO => 'gray',
        };
    }

    /**
     * Verificar si la cama está disponible para asignar
     */
    public function isDisponible(): bool
    {
        return $this === self::LIBRE;
    }

    /**
     * Obtener todos los estados como array
     */
    public static function toArray(): array
    {
        return array_column(self::cases(), 'value');
    }
}
