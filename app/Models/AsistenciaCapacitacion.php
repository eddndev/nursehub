<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AsistenciaCapacitacion extends Model
{
    use HasFactory;

    protected $fillable = [
        'sesion_id',
        'inscripcion_id',
        'presente',
        'hora_entrada',
        'hora_salida',
        'minutos_asistidos',
        'observaciones',
        'tardanza',
        'salida_temprana',
        'registrado_por',
        'registrado_at',
    ];

    protected $casts = [
        'presente' => 'boolean',
        'tardanza' => 'boolean',
        'salida_temprana' => 'boolean',
        'registrado_at' => 'datetime',
    ];

    // Relaciones
    public function sesion(): BelongsTo
    {
        return $this->belongsTo(SesionCapacitacion::class, 'sesion_id');
    }

    public function inscripcion(): BelongsTo
    {
        return $this->belongsTo(InscripcionCapacitacion::class, 'inscripcion_id');
    }

    public function registradoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'registrado_por');
    }

    // Query Scopes
    public function scopePorSesion($query, int $sesionId)
    {
        return $query->where('sesion_id', $sesionId);
    }

    public function scopePorInscripcion($query, int $inscripcionId)
    {
        return $query->where('inscripcion_id', $inscripcionId);
    }

    public function scopePresentes($query)
    {
        return $query->where('presente', true);
    }

    public function scopeAusentes($query)
    {
        return $query->where('presente', false);
    }

    // Métodos de ayuda
    public function marcarPresente(int $userId, ?string $horaEntrada = null, ?string $observaciones = null): void
    {
        $this->update([
            'presente' => true,
            'hora_entrada' => $horaEntrada ?? now()->format('H:i:s'),
            'observaciones' => $observaciones,
            'registrado_por' => $userId,
            'registrado_at' => now(),
        ]);
    }

    public function marcarSalida(string $horaSalida): void
    {
        $this->update([
            'hora_salida' => $horaSalida,
        ]);

        // Calcular minutos asistidos si tiene hora de entrada
        if ($this->hora_entrada && $this->hora_salida) {
            $entrada = \Carbon\Carbon::parse($this->hora_entrada);
            $salida = \Carbon\Carbon::parse($this->hora_salida);
            $this->update([
                'minutos_asistidos' => abs($salida->diffInMinutes($entrada)),
            ]);
        }
    }
}
