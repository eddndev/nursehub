<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Area extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'nombre',
        'codigo',
        'descripcion',
        'opera_24_7',
        'ratio_enfermero_paciente',
        'requiere_certificacion',
    ];

    /**
     * The attributes that should have default values.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'opera_24_7' => true,
        'ratio_enfermero_paciente' => 1.00,
        'requiere_certificacion' => false,
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'opera_24_7' => 'boolean',
            'ratio_enfermero_paciente' => 'decimal:2',
            'requiere_certificacion' => 'boolean',
        ];
    }

    /**
     * Relación: Un área tiene muchos pisos
     */
    public function pisos(): HasMany
    {
        return $this->hasMany(Piso::class);
    }

    /**
     * Relación: Un área tiene muchas rotaciones de enfermeros
     */
    public function rotaciones(): HasMany
    {
        return $this->hasMany(Rotacion::class);
    }
}
