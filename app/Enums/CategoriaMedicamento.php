<?php

namespace App\Enums;

enum CategoriaMedicamento: string
{
    case ANALGESICO = 'analgesico';
    case ANTIBIOTICO = 'antibiotico';
    case ANTIINFLAMATORIO = 'antiinflamatorio';
    case CARDIOVASCULAR = 'cardiovascular';
    case GASTROINTESTINAL = 'gastrointestinal';
    case RESPIRATORIO = 'respiratorio';
    case ENDOCRINO = 'endocrino';
    case NEUROLOGICO = 'neurologico';
    case HEMATOLOGICO = 'hematologico';
    case DERMATOLOGICO = 'dermatologico';
    case PSIQUIATRICO = 'psiquiatrico';
    case OFTALMICO = 'oftalmico';
    case OTICO = 'otico';
    case OTRO = 'otro';

    public function getLabel(): string
    {
        return match ($this) {
            self::ANALGESICO => 'Analgésico',
            self::ANTIBIOTICO => 'Antibiótico',
            self::ANTIINFLAMATORIO => 'Antiinflamatorio',
            self::CARDIOVASCULAR => 'Cardiovascular',
            self::GASTROINTESTINAL => 'Gastrointestinal',
            self::RESPIRATORIO => 'Respiratorio',
            self::ENDOCRINO => 'Endocrino',
            self::NEUROLOGICO => 'Neurológico',
            self::HEMATOLOGICO => 'Hematológico',
            self::DERMATOLOGICO => 'Dermatológico',
            self::PSIQUIATRICO => 'Psiquiátrico',
            self::OFTALMICO => 'Oftálmico',
            self::OTICO => 'Ótico',
            self::OTRO => 'Otro',
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::ANALGESICO => 'heroicon-o-fire',
            self::ANTIBIOTICO => 'heroicon-o-shield-check',
            self::ANTIINFLAMATORIO => 'heroicon-o-hand-raised',
            self::CARDIOVASCULAR => 'heroicon-o-heart',
            self::GASTROINTESTINAL => 'heroicon-o-beaker',
            self::RESPIRATORIO => 'heroicon-o-cloud',
            self::ENDOCRINO => 'heroicon-o-adjustments-horizontal',
            self::NEUROLOGICO => 'heroicon-o-cpu-chip',
            self::HEMATOLOGICO => 'heroicon-o-arrow-path',
            self::DERMATOLOGICO => 'heroicon-o-swatch',
            self::PSIQUIATRICO => 'heroicon-o-sparkles',
            self::OFTALMICO => 'heroicon-o-eye',
            self::OTICO => 'heroicon-o-speaker-wave',
            self::OTRO => 'heroicon-o-document',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::ANALGESICO => 'bg-red-100 text-red-800',
            self::ANTIBIOTICO => 'bg-green-100 text-green-800',
            self::ANTIINFLAMATORIO => 'bg-orange-100 text-orange-800',
            self::CARDIOVASCULAR => 'bg-pink-100 text-pink-800',
            self::GASTROINTESTINAL => 'bg-yellow-100 text-yellow-800',
            self::RESPIRATORIO => 'bg-blue-100 text-blue-800',
            self::ENDOCRINO => 'bg-purple-100 text-purple-800',
            self::NEUROLOGICO => 'bg-indigo-100 text-indigo-800',
            self::HEMATOLOGICO => 'bg-rose-100 text-rose-800',
            self::DERMATOLOGICO => 'bg-teal-100 text-teal-800',
            self::PSIQUIATRICO => 'bg-violet-100 text-violet-800',
            self::OFTALMICO => 'bg-cyan-100 text-cyan-800',
            self::OTICO => 'bg-sky-100 text-sky-800',
            self::OTRO => 'bg-gray-100 text-gray-800',
        };
    }
}
