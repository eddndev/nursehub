<?php

namespace App\Models;

use App\Enums\SeveridadInteraccion;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InteraccionMedicamentosa extends Model
{
    use HasFactory;

    protected $table = 'interacciones_medicamentosas';

    protected $fillable = [
        'medicamento_a_id',
        'medicamento_b_id',
        'severidad',
        'descripcion',
        'recomendacion',
        'fuente_referencia',
    ];

    protected $casts = [
        'severidad' => SeveridadInteraccion::class,
    ];

    // Relaciones

    public function medicamentoA(): BelongsTo
    {
        return $this->belongsTo(Medicamento::class, 'medicamento_a_id');
    }

    public function medicamentoB(): BelongsTo
    {
        return $this->belongsTo(Medicamento::class, 'medicamento_b_id');
    }

    // Scopes

    public function scopeSeveridadGrave($query)
    {
        return $query->whereIn('severidad', [
            SeveridadInteraccion::GRAVE->value,
            SeveridadInteraccion::CONTRAINDICADA->value,
        ]);
    }

    public function scopeEntreMedicamentos($query, int $medA, int $medB)
    {
        return $query->where(function ($q) use ($medA, $medB) {
            $q->where('medicamento_a_id', $medA)->where('medicamento_b_id', $medB);
        })->orWhere(function ($q) use ($medA, $medB) {
            $q->where('medicamento_a_id', $medB)->where('medicamento_b_id', $medA);
        });
    }

    // Métodos de ayuda

    public function bloqueaAdministracion(): bool
    {
        return $this->severidad === SeveridadInteraccion::CONTRAINDICADA;
    }

    public function requiereConfirmacion(): bool
    {
        return in_array($this->severidad, [
            SeveridadInteraccion::GRAVE,
            SeveridadInteraccion::CONTRAINDICADA,
        ]);
    }
}
