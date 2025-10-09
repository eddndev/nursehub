<?php

namespace App\Enums;

/**
 * Tipo de Asignación de Enfermero Enum
 *
 * Define el tipo de asignación del enfermero a áreas del hospital.
 */
enum TipoAsignacion: string
{
    case FIJO = 'fijo';
    case ROTATIVO = 'rotativo';

    /**
     * Obtener el nombre legible del tipo
     */
    public function label(): string
    {
        return match($this) {
            self::FIJO => 'Fijo',
            self::ROTATIVO => 'Rotativo',
        };
    }

    /**
     * Obtener descripción del tipo
     */
    public function descripcion(): string
    {
        return match($this) {
            self::FIJO => 'Enfermero asignado permanentemente a un área específica',
            self::ROTATIVO => 'Enfermero que rota entre diferentes áreas del hospital',
        };
    }

    /**
     * Obtener todos los tipos como array
     */
    public static function toArray(): array
    {
        return array_column(self::cases(), 'value');
    }
}