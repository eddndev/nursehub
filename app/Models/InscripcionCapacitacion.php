<?php

namespace App\Models;

use App\Enums\EstadoInscripcion;
use App\Enums\TipoInscripcion;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class InscripcionCapacitacion extends Model
{
    use HasFactory;

    protected $fillable = [
        'actividad_id',
        'enfermero_id',
        'tipo',
        'estado',
        'motivacion',
        'expectativas',
        'prioridad',
        'fecha_inscripcion',
        'inscrito_por',
        'aprobado_por',
        'aprobado_at',
        'notas_aprobacion',
        'rechazado_por',
        'rechazado_at',
        'motivo_rechazo',
        'cancelado_por',
        'cancelado_at',
        'motivo_cancelacion',
        'calificacion_final',
        'porcentaje_asistencia',
        'aprobado',
        'observaciones_finales',
    ];

    protected $casts = [
        'tipo' => TipoInscripcion::class,
        'estado' => EstadoInscripcion::class,
        'fecha_inscripcion' => 'datetime',
        'aprobado_at' => 'datetime',
        'rechazado_at' => 'datetime',
        'cancelado_at' => 'datetime',
        'calificacion_final' => 'decimal:2',
        'porcentaje_asistencia' => 'decimal:2',
        'aprobado' => 'boolean',
    ];

    // Relaciones
    public function actividad(): BelongsTo
    {
        return $this->belongsTo(ActividadCapacitacion::class, 'actividad_id');
    }

    public function enfermero(): BelongsTo
    {
        return $this->belongsTo(Enfermero::class);
    }

    public function inscritoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'inscrito_por');
    }

    public function aprobadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'aprobado_por');
    }

    public function rechazadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'rechazado_por');
    }

    public function canceladoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cancelado_por');
    }

    public function asistencias(): HasMany
    {
        return $this->hasMany(AsistenciaCapacitacion::class, 'inscripcion_id');
    }

    public function certificacion(): HasOne
    {
        return $this->hasOne(Certificacion::class, 'inscripcion_id');
    }

    // Query Scopes
    public function scopePorActividad($query, int $actividadId)
    {
        return $query->where('actividad_id', $actividadId);
    }

    public function scopePorEnfermero($query, int $enfermeroId)
    {
        return $query->where('enfermero_id', $enfermeroId);
    }

    public function scopePorEstado($query, EstadoInscripcion $estado)
    {
        return $query->where('estado', $estado);
    }

    public function scopePorTipo($query, TipoInscripcion $tipo)
    {
        return $query->where('tipo', $tipo);
    }

    public function scopeAprobadas($query)
    {
        return $query->where('estado', EstadoInscripcion::APROBADA);
    }

    public function scopePendientes($query)
    {
        return $query->where('estado', EstadoInscripcion::PENDIENTE);
    }

    public function scopeActivas($query)
    {
        return $query->whereIn('estado', [
            EstadoInscripcion::APROBADA,
            EstadoInscripcion::PENDIENTE,
        ]);
    }

    // Métodos de ayuda
    public function aprobar(int $userId, ?string $notas = null): void
    {
        $this->update([
            'estado' => EstadoInscripcion::APROBADA,
            'aprobado_por' => $userId,
            'aprobado_at' => now(),
            'notas_aprobacion' => $notas,
        ]);
    }

    public function rechazar(int $userId, string $motivo): void
    {
        $this->update([
            'estado' => EstadoInscripcion::RECHAZADA,
            'rechazado_por' => $userId,
            'rechazado_at' => now(),
            'motivo_rechazo' => $motivo,
        ]);
    }

    public function cancelar(int $userId, string $motivo): void
    {
        $this->update([
            'estado' => EstadoInscripcion::CANCELADA,
            'cancelado_por' => $userId,
            'cancelado_at' => now(),
            'motivo_cancelacion' => $motivo,
        ]);
    }

    public function calcularPorcentajeAsistencia(): float
    {
        $totalSesiones = $this->actividad->sesiones()->count();

        if ($totalSesiones === 0) {
            return 0;
        }

        $sesionesAsistidas = $this->asistencias()->where('presente', true)->count();

        return round(($sesionesAsistidas / $totalSesiones) * 100, 2);
    }

    public function cumpleAsistenciaMinima(): bool
    {
        $porcentaje = $this->porcentaje_asistencia ?? $this->calcularPorcentajeAsistencia();

        return $porcentaje >= $this->actividad->porcentaje_asistencia_minimo;
    }

    public function cumpleCalificacionMinima(): bool
    {
        if ($this->actividad->calificacion_minima_aprobacion === null) {
            return true;
        }

        if ($this->calificacion_final === null) {
            return false;
        }

        return $this->calificacion_final >= $this->actividad->calificacion_minima_aprobacion;
    }

    public function puedeObtenerCertificado(): bool
    {
        return $this->actividad->otorga_certificado
            && $this->estado === EstadoInscripcion::APROBADA
            && $this->cumpleAsistenciaMinima()
            && $this->cumpleCalificacionMinima()
            && $this->aprobado === true;
    }
}
