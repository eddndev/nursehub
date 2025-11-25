<?php

namespace App\Models;

use App\Enums\EstadoTurno;
use App\Enums\TipoTurno;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Turno extends Model
{
    use HasFactory;
    protected $fillable = [
        'area_id',
        'fecha',
        'tipo',
        'hora_inicio',
        'hora_fin',
        'jefe_turno_id',
        'novedades_relevo',
        'estado',
        'cerrado_at',
        'cerrado_por',
    ];

    protected $casts = [
        'fecha' => 'date',
        'hora_inicio' => 'datetime:H:i',
        'hora_fin' => 'datetime:H:i',
        'tipo' => TipoTurno::class,
        'estado' => EstadoTurno::class,
        'cerrado_at' => 'datetime',
    ];

    // Relaciones
    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    public function jefeTurno(): BelongsTo
    {
        return $this->belongsTo(User::class, 'jefe_turno_id');
    }

    public function cerradoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cerrado_por');
    }

    public function asignaciones(): HasMany
    {
        return $this->hasMany(AsignacionPaciente::class);
    }

    // Scopes
    public function scopeActivos($query)
    {
        return $query->where('estado', EstadoTurno::ACTIVO);
    }

    public function scopeDeHoy($query)
    {
        return $query->whereDate('fecha', today());
    }

    public function scopePorArea($query, $areaId)
    {
        return $query->where('area_id', $areaId);
    }

    // Helper methods
    public function isActivo(): bool
    {
        return $this->estado === EstadoTurno::ACTIVO;
    }

    public function isCerrado(): bool
    {
        return $this->estado === EstadoTurno::CERRADO;
    }

    public function getTotalAsignaciones(): int
    {
        return $this->asignaciones()->whereNull('fecha_hora_liberacion')->count();
    }
}
