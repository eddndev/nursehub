<?php

namespace App\Enums;

enum TipoActividad: string
{
    case CURSO = 'curso';
    case TALLER = 'taller';
    case SEMINARIO = 'seminario';
    case CONFERENCIA = 'conferencia';
    case CAPACITACION_INTERNA = 'capacitacion_interna';
    case CAPACITACION_EXTERNA = 'capacitacion_externa';
    case CERTIFICACION = 'certificacion';
    case ACTUALIZACION = 'actualizacion';

    public function getLabel(): string
    {
        return match ($this) {
            self::CURSO => 'Curso',
            self::TALLER => 'Taller',
            self::SEMINARIO => 'Seminario',
            self::CONFERENCIA => 'Conferencia',
            self::CAPACITACION_INTERNA => 'Capacitación Interna',
            self::CAPACITACION_EXTERNA => 'Capacitación Externa',
            self::CERTIFICACION => 'Certificación',
            self::ACTUALIZACION => 'Actualización',
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::CURSO => '📚',
            self::TALLER => '🔧',
            self::SEMINARIO => '🎓',
            self::CONFERENCIA => '🎤',
            self::CAPACITACION_INTERNA => '🏥',
            self::CAPACITACION_EXTERNA => '🌐',
            self::CERTIFICACION => '🏆',
            self::ACTUALIZACION => '🔄',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::CURSO => '#3B82F6', // blue
            self::TALLER => '#8B5CF6', // purple
            self::SEMINARIO => '#10B981', // green
            self::CONFERENCIA => '#F59E0B', // amber
            self::CAPACITACION_INTERNA => '#06B6D4', // cyan
            self::CAPACITACION_EXTERNA => '#EC4899', // pink
            self::CERTIFICACION => '#EF4444', // red
            self::ACTUALIZACION => '#6366F1', // indigo
        };
    }
}
