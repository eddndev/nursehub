<?php

namespace App\Enums;

enum SeveridadInteraccion: string
{
    case LEVE = 'leve';
    case MODERADA = 'moderada';
    case GRAVE = 'grave';
    case CONTRAINDICADA = 'contraindicada';

    public function getLabel(): string
    {
        return match ($this) {
            self::LEVE => 'Leve',
            self::MODERADA => 'Moderada',
            self::GRAVE => 'Grave',
            self::CONTRAINDICADA => 'Contraindicada',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::LEVE => 'bg-yellow-100 text-yellow-800',
            self::MODERADA => 'bg-orange-100 text-orange-800',
            self::GRAVE => 'bg-red-100 text-red-800',
            self::CONTRAINDICADA => 'bg-red-600 text-white',
        };
    }

    public function getDescripcion(): string
    {
        return match ($this) {
            self::LEVE => 'Interacción menor, generalmente no requiere cambio de tratamiento.',
            self::MODERADA => 'Puede requerir ajuste de dosis o monitoreo adicional.',
            self::GRAVE => 'Interacción seria, requiere evaluación médica antes de administrar.',
            self::CONTRAINDICADA => 'NO administrar juntos. Riesgo alto de efectos adversos severos.',
        };
    }

    public function bloqueaAdministracion(): bool
    {
        return $this === self::CONTRAINDICADA;
    }

    public function requiereConfirmacion(): bool
    {
        return in_array($this, [self::GRAVE, self::CONTRAINDICADA]);
    }
}
