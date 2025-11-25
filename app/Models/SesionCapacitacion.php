<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SesionCapacitacion extends Model
{
    use HasFactory;

    protected $fillable = [
        'actividad_id',
        'numero_sesion',
        'titulo',
        'descripcion',
        'fecha',
        'hora_inicio',
        'hora_fin',
        'duracion_minutos',
        'ubicacion',
        'url_virtual',
        'instructor_nombre',
        'contenido',
        'recursos_utilizados',
        'observaciones',
        'asistencia_registrada',
        'registrada_por',
        'registrada_at',
    ];

    protected $casts = [
        'fecha' => 'date',
        'registrada_at' => 'datetime',
        'asistencia_registrada' => 'boolean',
    ];

    // Relaciones
    public function actividad(): BelongsTo
    {
        return $this->belongsTo(ActividadCapacitacion::class, 'actividad_id');
    }

    public function registradaPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'registrada_por');
    }

    public function asistencias(): HasMany
    {
        return $this->hasMany(AsistenciaCapacitacion::class, 'sesion_id');
    }

    // Query Scopes
    public function scopePorActividad($query, int $actividadId)
    {
        return $query->where('actividad_id', $actividadId);
    }

    public function scopePorFecha($query, $fecha)
    {
        return $query->where('fecha', $fecha);
    }

    public function scopeProximas($query)
    {
        return $query->where('fecha', '>=', now()->toDateString())
            ->orderBy('fecha')
            ->orderBy('hora_inicio');
    }

    public function scopePasadas($query)
    {
        return $query->where('fecha', '<', now()->toDateString())
            ->orderBy('fecha', 'desc')
            ->orderBy('hora_inicio', 'desc');
    }

    // Métodos de ayuda
    public function getTotalAsistentesAttribute(): int
    {
        return $this->asistencias()->where('presente', true)->count();
    }

    public function getTotalInscritosAttribute(): int
    {
        return $this->asistencias()->count();
    }

    public function getPorcentajeAsistenciaAttribute(): float
    {
        if ($this->total_inscritos === 0) {
            return 0;
        }

        return round(($this->total_asistentes / $this->total_inscritos) * 100, 2);
    }
}
