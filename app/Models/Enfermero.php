<?php

namespace App\Models;

use App\Enums\TipoAsignacion;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Enfermero extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'user_id',
        'cedula_profesional',
        'tipo_asignacion',
        'area_fija_id',
        'especialidades',
        'anos_experiencia',
    ];

    /**
     * The attributes that should have default values.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'anos_experiencia' => 0,
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tipo_asignacion' => TipoAsignacion::class,
            'anos_experiencia' => 'integer',
        ];
    }

    /**
     * Relación: Un enfermero pertenece a un usuario
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación: Un enfermero puede tener un área fija asignada
     */
    public function areaFija(): BelongsTo
    {
        return $this->belongsTo(Area::class, 'area_fija_id');
    }

    /**
     * Relación: Un enfermero tiene muchas asignaciones de pacientes
     */
    public function asignaciones(): HasMany
    {
        return $this->hasMany(AsignacionPaciente::class);
    }

    /**
     * Scope para filtrar enfermeros fijos
     */
    public function scopeFijos($query)
    {
        return $query->where('tipo_asignacion', TipoAsignacion::FIJO);
    }

    /**
     * Scope para filtrar enfermeros rotativos
     */
    public function scopeRotativos($query)
    {
        return $query->where('tipo_asignacion', TipoAsignacion::ROTATIVO);
    }

    /**
     * Scope para filtrar por área fija
     */
    public function scopeByArea($query, int $areaId)
    {
        return $query->where('area_fija_id', $areaId);
    }
}
