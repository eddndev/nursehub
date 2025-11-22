<?php

namespace App\Services;

use App\Enums\NivelTriage;

class TriageCalculator
{
    public static function calcular(array $signosVitales): NivelTriage
    {
        if (self::esCritico($signosVitales)) {
            return NivelTriage::ROJO;
        }

        if (self::esEmergencia($signosVitales)) {
            return NivelTriage::NARANJA;
        }

        if (self::esUrgente($signosVitales)) {
            return NivelTriage::AMARILLO;
        }

        if (self::esMenosUrgente($signosVitales)) {
            return NivelTriage::VERDE;
        }

        return NivelTriage::AZUL;
    }

    private static function esCritico(array $sv): bool
    {
        $fc = $sv['frecuencia_cardiaca'] ?? null;
        $pas = $sv['presion_arterial_sistolica'] ?? null;
        $spo2 = $sv['saturacion_oxigeno'] ?? null;
        $temp = $sv['temperatura'] ?? null;
        $fr = $sv['frecuencia_respiratoria'] ?? null;

        return ($fc !== null && ($fc > 140 || $fc < 40)) ||
               ($pas !== null && ($pas > 220 || $pas < 80)) ||
               ($spo2 !== null && $spo2 < 85) ||
               ($temp !== null && $temp > 41.0) ||
               ($fr !== null && ($fr < 8 || $fr > 35));
    }

    private static function esEmergencia(array $sv): bool
    {
        $fc = $sv['frecuencia_cardiaca'] ?? null;
        $pas = $sv['presion_arterial_sistolica'] ?? null;
        $spo2 = $sv['saturacion_oxigeno'] ?? null;
        $temp = $sv['temperatura'] ?? null;
        $fr = $sv['frecuencia_respiratoria'] ?? null;

        return ($fc !== null && ($fc > 120 || $fc < 50)) ||
               ($pas !== null && ($pas > 180 || $pas < 90)) ||
               ($spo2 !== null && $spo2 < 90) ||
               ($temp !== null && ($temp > 39.5 || $temp < 35.0)) ||
               ($fr !== null && ($fr < 10 || $fr > 30));
    }

    private static function esUrgente(array $sv): bool
    {
        $fc = $sv['frecuencia_cardiaca'] ?? null;
        $pas = $sv['presion_arterial_sistolica'] ?? null;
        $spo2 = $sv['saturacion_oxigeno'] ?? null;
        $temp = $sv['temperatura'] ?? null;
        $fr = $sv['frecuencia_respiratoria'] ?? null;

        return ($fc !== null && ($fc > 100 || $fc < 60)) ||
               ($pas !== null && ($pas > 160 || $pas < 100)) ||
               ($spo2 !== null && $spo2 < 92) ||
               ($temp !== null && ($temp > 38.5 || $temp < 35.5)) ||
               ($fr !== null && ($fr < 12 || $fr > 25));
    }

    private static function esMenosUrgente(array $sv): bool
    {
        $fc = $sv['frecuencia_cardiaca'] ?? null;
        $pas = $sv['presion_arterial_sistolica'] ?? null;
        $spo2 = $sv['saturacion_oxigeno'] ?? null;
        $temp = $sv['temperatura'] ?? null;

        return ($fc !== null && ($fc > 90 || $fc < 65)) ||
               ($pas !== null && ($pas > 140 || $pas < 110)) ||
               ($spo2 !== null && $spo2 < 94) ||
               ($temp !== null && ($temp > 38.0 || $temp < 36.0));
    }
}
