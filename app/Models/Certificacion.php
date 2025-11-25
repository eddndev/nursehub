<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Certificacion extends Model
{
    use HasFactory;

    protected $fillable = [
        'inscripcion_id',
        'numero_certificado',
        'fecha_emision',
        'fecha_vigencia_inicio',
        'fecha_vigencia_fin',
        'horas_certificadas',
        'calificacion_obtenida',
        'porcentaje_asistencia',
        'competencias_desarrolladas',
        'observaciones',
        'hash_verificacion',
        'url_descarga',
        'emitido_por',
        'emitido_at',
        'revocado_por',
        'revocado_at',
        'motivo_revocacion',
    ];

    protected $casts = [
        'fecha_emision' => 'date',
        'fecha_vigencia_inicio' => 'date',
        'fecha_vigencia_fin' => 'date',
        'calificacion_obtenida' => 'decimal:2',
        'porcentaje_asistencia' => 'decimal:2',
        'emitido_at' => 'datetime',
        'revocado_at' => 'datetime',
    ];

    // Relaciones
    public function inscripcion(): BelongsTo
    {
        return $this->belongsTo(InscripcionCapacitacion::class, 'inscripcion_id');
    }

    public function emitidoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'emitido_por');
    }

    public function revocadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'revocado_por');
    }

    // Query Scopes
    public function scopePorInscripcion($query, int $inscripcionId)
    {
        return $query->where('inscripcion_id', $inscripcionId);
    }

    public function scopeVigentes($query)
    {
        return $query->whereNull('revocado_at')
            ->where(function ($q) {
                $q->whereNull('fecha_vigencia_fin')
                    ->orWhere('fecha_vigencia_fin', '>=', now());
            });
    }

    public function scopeRevocados($query)
    {
        return $query->whereNotNull('revocado_at');
    }

    public function scopePorNumero($query, string $numeroCertificado)
    {
        return $query->where('numero_certificado', $numeroCertificado);
    }

    public function scopePorHash($query, string $hash)
    {
        return $query->where('hash_verificacion', $hash);
    }

    // Métodos de ayuda
    public static function generarNumeroCertificado(): string
    {
        $prefix = 'CERT';
        $year = now()->format('Y');

        // Find the last certificate number for the current year
        $lastCert = self::whereYear('created_at', now()->year)
            ->orderBy('id', 'desc')
            ->first();

        $sequential = 1;
        if ($lastCert && $lastCert->numero_certificado) {
            // Extract the sequential number from the last certificate
            $parts = explode('-', $lastCert->numero_certificado);
            if (count($parts) === 3) {
                $sequential = ((int) $parts[2]) + 1;
            }
        }

        $sequential = str_pad($sequential, 5, '0', STR_PAD_LEFT);

        return "{$prefix}-{$year}-{$sequential}";
    }

    public static function generarHashVerificacion(InscripcionCapacitacion $inscripcion, string $numeroCertificado): string
    {
        $data = [
            'inscripcion_id' => $inscripcion->id,
            'numero_certificado' => $numeroCertificado,
            'enfermero_id' => $inscripcion->enfermero_id,
            'actividad_id' => $inscripcion->actividad_id,
            'timestamp' => now()->timestamp,
        ];

        return hash('sha256', json_encode($data));
    }

    public function revocar(int $userId, string $motivo): void
    {
        $this->update([
            'revocado_por' => $userId,
            'revocado_at' => now(),
            'motivo_revocacion' => $motivo,
        ]);
    }

    public function estaVigente(): bool
    {
        if ($this->revocado_at !== null) {
            return false;
        }

        if ($this->fecha_vigencia_fin === null) {
            return true;
        }

        return $this->fecha_vigencia_fin >= now();
    }

    public function getEnfermeroAttribute()
    {
        return $this->inscripcion->enfermero;
    }

    public function getActividadAttribute()
    {
        return $this->inscripcion->actividad;
    }
}

