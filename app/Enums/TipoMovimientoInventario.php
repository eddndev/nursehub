<?php

namespace App\Enums;

enum TipoMovimientoInventario: string
{
    case ENTRADA = 'entrada';
    case SALIDA = 'salida';
    case AJUSTE_POSITIVO = 'ajuste_positivo';
    case AJUSTE_NEGATIVO = 'ajuste_negativo';
    case TRANSFERENCIA_ENTRADA = 'transferencia_entrada';
    case TRANSFERENCIA_SALIDA = 'transferencia_salida';
    case DEVOLUCION = 'devolucion';
    case MERMA = 'merma';
    case CADUCIDAD = 'caducidad';
    case DESPACHO = 'despacho';

    public function getLabel(): string
    {
        return match ($this) {
            self::ENTRADA => 'Entrada (Compra)',
            self::SALIDA => 'Salida',
            self::AJUSTE_POSITIVO => 'Ajuste Positivo',
            self::AJUSTE_NEGATIVO => 'Ajuste Negativo',
            self::TRANSFERENCIA_ENTRADA => 'Transferencia Entrada',
            self::TRANSFERENCIA_SALIDA => 'Transferencia Salida',
            self::DEVOLUCION => 'Devolución',
            self::MERMA => 'Merma/Pérdida',
            self::CADUCIDAD => 'Caducidad',
            self::DESPACHO => 'Despacho a Área',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::ENTRADA, self::AJUSTE_POSITIVO, self::TRANSFERENCIA_ENTRADA, self::DEVOLUCION => 'bg-green-100 text-green-800',
            self::SALIDA, self::AJUSTE_NEGATIVO, self::TRANSFERENCIA_SALIDA, self::DESPACHO => 'bg-blue-100 text-blue-800',
            self::MERMA, self::CADUCIDAD => 'bg-red-100 text-red-800',
        };
    }

    public function esPositivo(): bool
    {
        return in_array($this, [
            self::ENTRADA,
            self::AJUSTE_POSITIVO,
            self::TRANSFERENCIA_ENTRADA,
            self::DEVOLUCION,
        ]);
    }

    public function esNegativo(): bool
    {
        return !$this->esPositivo();
    }

    public function requiereReferencia(): bool
    {
        return in_array($this, [
            self::ENTRADA,
            self::DEVOLUCION,
            self::DESPACHO,
        ]);
    }
}
