<?php

namespace App\Models;

use App\Enums\PacienteEstado;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Paciente extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'codigo_qr',
        'nombre',
        'apellido_paterno',
        'apellido_materno',
        'sexo',
        'fecha_nacimiento',
        'curp',
        'telefono',
        'contacto_emergencia_nombre',
        'contacto_emergencia_telefono',
        'alergias',
        'antecedentes_medicos',
        'meta_balance_hidrico',
        'estado',
        'cama_actual_id',
        'admitido_por',
        'fecha_admision',
        'fecha_alta',
    ];

    protected function casts(): array
    {
        return [
            'fecha_nacimiento' => 'date',
            'fecha_admision' => 'datetime',
            'fecha_alta' => 'datetime',
            'estado' => PacienteEstado::class,
        ];
    }

    public function camaActual(): BelongsTo
    {
        return $this->belongsTo(Cama::class, 'cama_actual_id');
    }

    public function admitidoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admitido_por');
    }

    public function registrosSignosVitales(): HasMany
    {
        return $this->hasMany(RegistroSignosVitales::class);
    }

    public function historial(): HasMany
    {
        return $this->hasMany(HistorialPaciente::class);
    }

    public function asignaciones(): HasMany
    {
        return $this->hasMany(AsignacionPaciente::class);
    }

    public function asignacionActual()
    {
        return $this->hasOne(AsignacionPaciente::class)
            ->whereNull('fecha_hora_liberacion')
            ->latest('fecha_hora_asignacion');
    }

    public function getNombreCompletoAttribute(): string
    {
        return trim("{$this->nombre} {$this->apellido_paterno} {$this->apellido_materno}");
    }

    public function getEdadAttribute(): int
    {
        return $this->fecha_nacimiento->age;
    }

    public function getUltimoRegistroSignosVitalesAttribute()
    {
        return $this->registrosSignosVitales()->latest('fecha_registro')->first();
    }

    public function scopeActivos($query)
    {
        return $query->where('estado', PacienteEstado::ACTIVO);
    }

    public function scopePorTriage($query, $nivelTriage)
    {
        return $query->whereHas('registrosSignosVitales', function ($q) use ($nivelTriage) {
            $q->where('nivel_triage', $nivelTriage)
              ->whereNull('nivel_triage')
              ->orWhereRaw('id IN (
                  SELECT MAX(id)
                  FROM registros_signos_vitales
                  GROUP BY paciente_id
              )');
        });
    }
}
