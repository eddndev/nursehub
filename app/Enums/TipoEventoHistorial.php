<?php

namespace App\Enums;

enum TipoEventoHistorial: string
{
    case ADMISION = 'admision';
    case SIGNOS_VITALES = 'signos_vitales';
    case CAMBIO_CAMA = 'cambio_cama';
    case CAMBIO_ESTADO = 'cambio_estado';
    case NOTA_ENFERMERIA = 'nota_enfermeria';
    case ALTA = 'alta';

    public function getLabel(): string
    {
        return match ($this) {
            self::ADMISION => 'Admisión',
            self::SIGNOS_VITALES => 'Signos Vitales',
            self::CAMBIO_CAMA => 'Cambio de Cama',
            self::CAMBIO_ESTADO => 'Cambio de Estado',
            self::NOTA_ENFERMERIA => 'Nota de Enfermería',
            self::ALTA => 'Alta Médica',
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::ADMISION => 'user-plus',
            self::SIGNOS_VITALES => 'activity',
            self::CAMBIO_CAMA => 'bed',
            self::CAMBIO_ESTADO => 'edit',
            self::NOTA_ENFERMERIA => 'file-text',
            self::ALTA => 'user-check',
        };
    }
}
