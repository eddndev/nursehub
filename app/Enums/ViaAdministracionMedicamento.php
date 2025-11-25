<?php

namespace App\Enums;

enum ViaAdministracionMedicamento: string
{
    case ORAL = 'oral';
    case INTRAVENOSA = 'intravenosa';
    case INTRAMUSCULAR = 'intramuscular';
    case SUBCUTANEA = 'subcutanea';
    case TOPICA = 'topica';
    case RECTAL = 'rectal';
    case INHALATORIA = 'inhalatoria';
    case OFTALMICA = 'oftalmica';
    case OTICA = 'otica';
    case NASAL = 'nasal';
    case SUBLINGUAL = 'sublingual';
    case TRANSDERMICA = 'transdermica';

    public function getLabel(): string
    {
        return match ($this) {
            self::ORAL => 'Oral',
            self::INTRAVENOSA => 'Intravenosa (IV)',
            self::INTRAMUSCULAR => 'Intramuscular (IM)',
            self::SUBCUTANEA => 'Subcutánea (SC)',
            self::TOPICA => 'Tópica',
            self::RECTAL => 'Rectal',
            self::INHALATORIA => 'Inhalatoria',
            self::OFTALMICA => 'Oftálmica',
            self::OTICA => 'Ótica',
            self::NASAL => 'Nasal',
            self::SUBLINGUAL => 'Sublingual',
            self::TRANSDERMICA => 'Transdérmica',
        };
    }

    public function getAbreviatura(): string
    {
        return match ($this) {
            self::ORAL => 'VO',
            self::INTRAVENOSA => 'IV',
            self::INTRAMUSCULAR => 'IM',
            self::SUBCUTANEA => 'SC',
            self::TOPICA => 'TOP',
            self::RECTAL => 'VR',
            self::INHALATORIA => 'INH',
            self::OFTALMICA => 'OFT',
            self::OTICA => 'OT',
            self::NASAL => 'NAS',
            self::SUBLINGUAL => 'SL',
            self::TRANSDERMICA => 'TD',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::ORAL => 'bg-blue-100 text-blue-800',
            self::INTRAVENOSA => 'bg-red-100 text-red-800',
            self::INTRAMUSCULAR => 'bg-orange-100 text-orange-800',
            self::SUBCUTANEA => 'bg-yellow-100 text-yellow-800',
            self::TOPICA => 'bg-green-100 text-green-800',
            self::RECTAL => 'bg-purple-100 text-purple-800',
            self::INHALATORIA => 'bg-cyan-100 text-cyan-800',
            self::OFTALMICA => 'bg-teal-100 text-teal-800',
            self::OTICA => 'bg-indigo-100 text-indigo-800',
            self::NASAL => 'bg-sky-100 text-sky-800',
            self::SUBLINGUAL => 'bg-pink-100 text-pink-800',
            self::TRANSDERMICA => 'bg-amber-100 text-amber-800',
        };
    }
}
