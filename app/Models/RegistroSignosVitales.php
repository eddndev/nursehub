<?php

namespace App\Models;

use App\Enums\NivelTriage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RegistroSignosVitales extends Model
{
    use HasFactory;

    protected $table = 'registros_signos_vitales';

    protected $fillable = [
        'paciente_id',
        'registrado_por',
        'presion_arterial_sistolica',
        'presion_arterial_diastolica',
        'frecuencia_cardiaca',
        'frecuencia_respiratoria',
        'temperatura',
        'saturacion_oxigeno',
        'glucosa',
        'nivel_triage',
        'triage_override',
        'observaciones',
        'fecha_registro',
    ];

    protected function casts(): array
    {
        return [
            'presion_arterial_sistolica' => 'decimal:2',
            'presion_arterial_diastolica' => 'decimal:2',
            'frecuencia_cardiaca' => 'integer',
            'frecuencia_respiratoria' => 'integer',
            'temperatura' => 'decimal:2',
            'saturacion_oxigeno' => 'decimal:2',
            'glucosa' => 'decimal:2',
            'triage_override' => 'boolean',
            'fecha_registro' => 'datetime',
            'nivel_triage' => NivelTriage::class,
        ];
    }

    public function paciente(): BelongsTo
    {
        return $this->belongsTo(Paciente::class);
    }

    public function registradoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'registrado_por');
    }

    public function getPresionArterialAttribute(): ?string
    {
        if ($this->presion_arterial_sistolica && $this->presion_arterial_diastolica) {
            return "{$this->presion_arterial_sistolica}/{$this->presion_arterial_diastolica}";
        }
        return null;
    }

    public function scopeRecientes($query, int $limit = 10)
    {
        return $query->orderBy('fecha_registro', 'desc')->limit($limit);
    }

    public function scopePorPaciente($query, int $pacienteId)
    {
        return $query->where('paciente_id', $pacienteId);
    }
}
