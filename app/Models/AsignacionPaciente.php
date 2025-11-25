<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AsignacionPaciente extends Model
{
    use HasFactory;
    protected $table = 'asignacion_pacientes';

    protected $fillable = [
        'turno_id',
        'enfermero_id',
        'paciente_id',
        'fecha_hora_asignacion',
        'fecha_hora_liberacion',
        'asignado_por',
        'liberado_por',
        'motivo_liberacion',
    ];

    protected $casts = [
        'fecha_hora_asignacion' => 'datetime',
        'fecha_hora_liberacion' => 'datetime',
    ];

    // Relaciones
    public function turno(): BelongsTo
    {
        return $this->belongsTo(Turno::class);
    }

    public function enfermero(): BelongsTo
    {
        return $this->belongsTo(Enfermero::class);
    }

    public function paciente(): BelongsTo
    {
        return $this->belongsTo(Paciente::class);
    }

    public function asignadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'asignado_por');
    }

    public function liberadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'liberado_por');
    }

    // Scopes
    public function scopeActivas($query)
    {
        return $query->whereNull('fecha_hora_liberacion');
    }

    public function scopeLiberadas($query)
    {
        return $query->whereNotNull('fecha_hora_liberacion');
    }

    public function scopePorEnfermero($query, $enfermeroId)
    {
        return $query->where('enfermero_id', $enfermeroId);
    }

    public function scopePorPaciente($query, $pacienteId)
    {
        return $query->where('paciente_id', $pacienteId);
    }

    public function scopePorTurno($query, $turnoId)
    {
        return $query->where('turno_id', $turnoId);
    }

    // Helper methods
    public function isActiva(): bool
    {
        return $this->fecha_hora_liberacion === null;
    }

    public function liberar(User $usuario, string $motivo): void
    {
        $this->update([
            'fecha_hora_liberacion' => now(),
            'liberado_por' => $usuario->id,
            'motivo_liberacion' => $motivo,
        ]);
    }
}
