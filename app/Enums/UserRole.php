<?php

namespace App\Enums;

/**
 * User Role Enum
 *
 * Define los roles disponibles en el sistema NurseHub.
 * Basado en: docs/03-database-schema.md
 */
enum UserRole: string
{
    case ADMIN = 'admin';
    case COORDINADOR = 'coordinador';
    case JEFE_PISO = 'jefe_piso';
    case ENFERMERO = 'enfermero';
    case JEFE_CAPACITACION = 'jefe_capacitacion';

    /**
     * Obtener el nombre legible del rol
     */
    public function label(): string
    {
        return match($this) {
            self::ADMIN => 'Administrador',
            self::COORDINADOR => 'Coordinador General de Enfermería',
            self::JEFE_PISO => 'Jefe de Piso/Área',
            self::ENFERMERO => 'Enfermero',
            self::JEFE_CAPACITACION => 'Jefe de Capacitación',
        };
    }

    /**
     * Verificar si el rol tiene permisos de administrador
     */
    public function isAdmin(): bool
    {
        return $this === self::ADMIN;
    }

    /**
     * Verificar si el rol tiene permisos de coordinador o superior
     */
    public function isCoordinadorOrAbove(): bool
    {
        return in_array($this, [self::ADMIN, self::COORDINADOR]);
    }

    /**
     * Verificar si el rol es de jefe (cualquier tipo)
     */
    public function isJefe(): bool
    {
        return in_array($this, [self::ADMIN, self::COORDINADOR, self::JEFE_PISO, self::JEFE_CAPACITACION]);
    }

    /**
     * Obtener todos los roles disponibles como array
     */
    public static function toArray(): array
    {
        return array_column(self::cases(), 'value');
    }
}
