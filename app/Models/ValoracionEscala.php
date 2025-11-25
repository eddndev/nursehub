<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ValoracionEscala extends Model
{
    use HasFactory;

    protected $fillable = [
        'paciente_id',
        'tipo_escala',
        'puntaje_total',
        'detalle_json',
        'riesgo_interpretado',
        'fecha_hora',
        'registrado_por',
    ];

    protected $casts = [
        'detalle_json' => 'array',
        'fecha_hora' => 'datetime',
    ];

    public function paciente(): BelongsTo
    {
        return $this->belongsTo(Paciente::class);
    }

    public function registrador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'registrado_por');
    }
}