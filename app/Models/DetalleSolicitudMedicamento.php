<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetalleSolicitudMedicamento extends Model
{
    use HasFactory;

    protected $table = 'detalles_solicitud_medicamento';

    protected $fillable = [
        'solicitud_id',
        'medicamento_id',
        'cantidad_solicitada',
        'cantidad_despachada',
        'inventario_id',
        'indicaciones_medicas',
    ];

    // Relaciones

    public function solicitud(): BelongsTo
    {
        return $this->belongsTo(SolicitudMedicamento::class, 'solicitud_id');
    }

    public function medicamento(): BelongsTo
    {
        return $this->belongsTo(Medicamento::class);
    }

    public function inventario(): BelongsTo
    {
        return $this->belongsTo(InventarioMedicamento::class, 'inventario_id');
    }

    // Métodos de ayuda

    public function estaDespachado(): bool
    {
        return $this->cantidad_despachada >= $this->cantidad_solicitada;
    }

    public function estaParcialmenteDespachado(): bool
    {
        return $this->cantidad_despachada > 0 && $this->cantidad_despachada < $this->cantidad_solicitada;
    }

    public function getCantidadPendienteAttribute(): int
    {
        return max(0, $this->cantidad_solicitada - $this->cantidad_despachada);
    }
}
