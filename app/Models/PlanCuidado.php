<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PlanCuidado extends Model
{
    use HasFactory;

    protected $fillable = [
        'paciente_id',
        'diagnostico_id',
        'estado',
        'fecha_inicio',
        'fecha_fin',
        'registrado_por',
    ];

    protected $casts = [
        'fecha_inicio' => 'datetime',
        'fecha_fin' => 'datetime',
    ];

    public function paciente(): BelongsTo
    {
        return $this->belongsTo(Paciente::class);
    }

    public function diagnostico(): BelongsTo
    {
        return $this->belongsTo(DiagnosticoEnfermeria::class, 'diagnostico_id');
    }

    public function registrador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'registrado_por');
    }

    public function intervenciones(): HasMany
    {
        return $this->hasMany(IntervencionCuidado::class);
    }
}