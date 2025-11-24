<?php

namespace App\Models;

use App\Enums\EstadoActividad;
use App\Enums\TipoActividad;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ActividadCapacitacion extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'titulo',
        'descripcion',
        'tipo',
        'estado',
        'modalidad',
        'ubicacion',
        'url_virtual',
        'duracion_horas',
        'cupo_minimo',
        'cupo_maximo',
        'fecha_inicio',
        'fecha_fin',
        'hora_inicio',
        'hora_fin',
        'fecha_limite_inscripcion',
        'porcentaje_asistencia_minimo',
        'calificacion_minima_aprobacion',
        'otorga_certificado',
        'instructor_nombre',
        'instructor_credenciales',
        'objetivos',
        'contenido_tematico',
        'recursos_necesarios',
        'evaluacion_metodo',
        'notas_adicionales',
        'area_id',
        'creado_por',
        'aprobado_por',
        'aprobado_at',
        'cancelado_por',
        'cancelado_at',
        'motivo_cancelacion',
    ];

    protected $casts = [
        'tipo' => TipoActividad::class,
        'estado' => EstadoActividad::class,
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'fecha_limite_inscripcion' => 'date',
        'aprobado_at' => 'datetime',
        'cancelado_at' => 'datetime',
        'otorga_certificado' => 'boolean',
        'porcentaje_asistencia_minimo' => 'decimal:2',
        'calificacion_minima_aprobacion' => 'decimal:2',
    ];

    // Relaciones
    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    public function creadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creado_por');
    }

    public function aprobadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'aprobado_por');
    }

    public function canceladoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cancelado_por');
    }

    public function sesiones(): HasMany
    {
        return $this->hasMany(SesionCapacitacion::class, 'actividad_id');
    }

    public function inscripciones(): HasMany
    {
        return $this->hasMany(InscripcionCapacitacion::class, 'actividad_id');
    }

    // Query Scopes
    public function scopePorEstado($query, EstadoActividad $estado)
    {
        return $query->where('estado', $estado);
    }

    public function scopePorTipo($query, TipoActividad $tipo)
    {
        return $query->where('tipo', $tipo);
    }

    public function scopeActivas($query)
    {
        return $query->whereIn('estado', [
            EstadoActividad::INSCRIPCIONES_ABIERTAS,
            EstadoActividad::INSCRIPCIONES_CERRADAS,
            EstadoActividad::EN_CURSO,
        ]);
    }

    public function scopeInscripcionesAbiertas($query)
    {
        return $query->where('estado', EstadoActividad::INSCRIPCIONES_ABIERTAS)
            ->where(function ($q) {
                $q->whereNull('fecha_limite_inscripcion')
                    ->orWhere('fecha_limite_inscripcion', '>=', now());
            });
    }

    public function scopePorArea($query, int $areaId)
    {
        return $query->where('area_id', $areaId);
    }

    public function scopeProximas($query)
    {
        return $query->where('fecha_inicio', '>=', now())
            ->orderBy('fecha_inicio');
    }

    // Métodos de ayuda
    public function tieneCapoDisponible(): bool
    {
        $inscritos = $this->inscripciones()
            ->whereIn('estado', ['aprobada', 'pendiente'])
            ->count();

        return $inscritos < $this->cupo_maximo;
    }

    public function getCuposDisponiblesAttribute(): int
    {
        $inscritos = $this->inscripciones()
            ->whereIn('estado', ['aprobada', 'pendiente'])
            ->count();

        return max(0, $this->cupo_maximo - $inscritos);
    }

    public function getTotalInscritosAttribute(): int
    {
        return $this->inscripciones()
            ->whereIn('estado', ['aprobada', 'pendiente'])
            ->count();
    }

    public function puedeInscribirse(): bool
    {
        return $this->estado->isPuedeInscribirse()
            && $this->tieneCapoDisponible()
            && ($this->fecha_limite_inscripcion === null || $this->fecha_limite_inscripcion >= now());
    }
}
