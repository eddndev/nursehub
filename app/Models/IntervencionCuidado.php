<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IntervencionCuidado extends Model
{
    use HasFactory;

    protected $fillable = [
        'plan_cuidado_id',
        'descripcion',
        'frecuencia',
        'observaciones',
        'realizado',
        'realizado_at',
        'realizado_por',
    ];

    protected $casts = [
        'realizado' => 'boolean',
        'realizado_at' => 'datetime',
    ];

    public function planCuidado(): BelongsTo
    {
        return $this->belongsTo(PlanCuidado::class);
    }

    public function realizador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'realizado_por');
    }
}