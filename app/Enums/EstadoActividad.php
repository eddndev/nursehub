<?php

namespace App\Enums;

enum EstadoActividad: string
{
    case PLANIFICADA = 'planificada';
    case INSCRIPCIONES_ABIERTAS = 'inscripciones_abiertas';
    case INSCRIPCIONES_CERRADAS = 'inscripciones_cerradas';
    case EN_CURSO = 'en_curso';
    case COMPLETADA = 'completada';
    case CANCELADA = 'cancelada';
    case POSPUESTA = 'pospuesta';

    public function getLabel(): string
    {
        return match ($this) {
            self::PLANIFICADA => 'Planificada',
            self::INSCRIPCIONES_ABIERTAS => 'Inscripciones Abiertas',
            self::INSCRIPCIONES_CERRADAS => 'Inscripciones Cerradas',
            self::EN_CURSO => 'En Curso',
            self::COMPLETADA => 'Completada',
            self::CANCELADA => 'Cancelada',
            self::POSPUESTA => 'Pospuesta',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::PLANIFICADA => '#9CA3AF', // gray
            self::INSCRIPCIONES_ABIERTAS => '#10B981', // green
            self::INSCRIPCIONES_CERRADAS => '#F59E0B', // amber
            self::EN_CURSO => '#3B82F6', // blue
            self::COMPLETADA => '#6366F1', // indigo
            self::CANCELADA => '#EF4444', // red
            self::POSPUESTA => '#8B5CF6', // purple
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::PLANIFICADA => '📋',
            self::INSCRIPCIONES_ABIERTAS => '✅',
            self::INSCRIPCIONES_CERRADAS => '🔒',
            self::EN_CURSO => '▶️',
            self::COMPLETADA => '✔️',
            self::CANCELADA => '❌',
            self::POSPUESTA => '⏸️',
        };
    }

    public function isPuedeInscribirse(): bool
    {
        return $this === self::INSCRIPCIONES_ABIERTAS;
    }

    public function isActiva(): bool
    {
        return in_array($this, [
            self::INSCRIPCIONES_ABIERTAS,
            self::INSCRIPCIONES_CERRADAS,
            self::EN_CURSO,
        ]);
    }
}
