<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cuarto extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'piso_id',
        'numero_cuarto',
        'tipo',
    ];

    /**
     * The attributes that should have default values.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'tipo' => 'individual',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tipo' => 'string',
        ];
    }

    /**
     * Relación: Un cuarto pertenece a un piso
     */
    public function piso(): BelongsTo
    {
        return $this->belongsTo(Piso::class);
    }

    /**
     * Relación: Un cuarto tiene muchas camas
     */
    public function camas(): HasMany
    {
        return $this->hasMany(Cama::class);
    }
}
