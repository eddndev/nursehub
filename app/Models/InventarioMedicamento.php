<?php

namespace App\Models;

use App\Enums\EstadoInventarioMedicamento;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InventarioMedicamento extends Model
{
    use HasFactory;

    protected $table = 'inventario_medicamentos';

    protected $fillable = [
        'medicamento_id',
        'area_id',
        'lote',
        'fecha_caducidad',
        'cantidad_actual',
        'cantidad_inicial',
        'stock_minimo',
        'stock_maximo',
        'costo_unitario',
        'estado',
        'ubicacion_fisica',
    ];

    protected $casts = [
        'fecha_caducidad' => 'date',
        'costo_unitario' => 'decimal:2',
        'estado' => EstadoInventarioMedicamento::class,
    ];

    // Relaciones

    public function medicamento(): BelongsTo
    {
        return $this->belongsTo(Medicamento::class);
    }

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    public function movimientos(): HasMany
    {
        return $this->hasMany(MovimientoInventario::class, 'inventario_id');
    }

    public function detallesSolicitud(): HasMany
    {
        return $this->hasMany(DetalleSolicitudMedicamento::class, 'inventario_id');
    }

    // Scopes

    public function scopeDisponibles($query)
    {
        return $query->where('estado', EstadoInventarioMedicamento::DISPONIBLE)
            ->where('cantidad_actual', '>', 0);
    }

    public function scopeAlmacenGeneral($query)
    {
        return $query->whereNull('area_id');
    }

    public function scopePorArea($query, int $areaId)
    {
        return $query->where('area_id', $areaId);
    }

    public function scopeStockBajo($query)
    {
        return $query->whereColumn('cantidad_actual', '<=', 'stock_minimo');
    }

    public function scopeProximosCaducar($query, int $dias = 60)
    {
        return $query->where('fecha_caducidad', '<=', now()->addDays($dias))
            ->where('fecha_caducidad', '>', now())
            ->where('cantidad_actual', '>', 0);
    }

    public function scopeCaducados($query)
    {
        return $query->where('fecha_caducidad', '<', now());
    }

    // Métodos de ayuda

    public function puedeDespachar(): bool
    {
        return $this->estado === EstadoInventarioMedicamento::DISPONIBLE
            && $this->cantidad_actual > 0
            && $this->fecha_caducidad > now()->addDays(30);
    }

    public function estaBajoMinimo(): bool
    {
        return $this->cantidad_actual <= $this->stock_minimo;
    }

    public function estaCercaCaducidad(int $dias = 60): bool
    {
        return $this->fecha_caducidad <= now()->addDays($dias);
    }

    public function getValorTotalAttribute(): float
    {
        return $this->cantidad_actual * $this->costo_unitario;
    }

    public function getDiasParaCaducidadAttribute(): int
    {
        return now()->diffInDays($this->fecha_caducidad, false);
    }
}
