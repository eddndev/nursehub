<?php

namespace App\Models;

use App\Enums\TipoBalance;
use App\Enums\ViaAdministracion;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BalanceLiquido extends Model
{
    use HasFactory;

    protected $fillable = [
        'paciente_id',
        'tipo',
        'via',
        'solucion',
        'volumen_ml',
        'fecha_hora',
        'turno',
        'registrado_por',
    ];

    protected $casts = [
        'fecha_hora' => 'datetime',
        'tipo' => TipoBalance::class,
        'via' => ViaAdministracion::class,
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