<?php

namespace App\Models;

use App\Enums\CamaEstado;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cama extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'cuarto_id',
        'numero_cama',
        'estado',
    ];

    /**
     * The attributes that should have default values.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'estado' => 'libre',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'estado' => CamaEstado::class,
        ];
    }

    /**
     * Relación: Una cama pertenece a un cuarto
     */
    public function cuarto(): BelongsTo
    {
        return $this->belongsTo(Cuarto::class);
    }

    /**
     * Scope para filtrar camas libres
     */
    public function scopeLibre($query)
    {
        return $query->where('estado', CamaEstado::LIBRE);
    }

    /**
     * Scope para filtrar camas ocupadas
     */
    public function scopeOcupada($query)
    {
        return $query->where('estado', CamaEstado::OCUPADA);
    }

    /**
     * Scope para filtrar por estado específico
     */
    public function scopeByEstado($query, CamaEstado $estado)
    {
        return $query->where('estado', $estado);
    }
}
