<?php

namespace App\Models;

use App\Enums\TipoMovimientoInventario;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MovimientoInventario extends Model
{
    use HasFactory;

    protected $table = 'movimientos_inventario';

    protected $fillable = [
        'inventario_id',
        'tipo_movimiento',
        'cantidad',
        'cantidad_anterior',
        'cantidad_nueva',
        'area_origen_id',
        'area_destino_id',
        'motivo',
        'usuario_id',
        'fecha_movimiento',
        'referencia',
    ];

    protected $casts = [
        'tipo_movimiento' => TipoMovimientoInventario::class,
        'fecha_movimiento' => 'datetime',
    ];

    // Relaciones

    public function inventario(): BelongsTo
    {
        return $this->belongsTo(InventarioMedicamento::class, 'inventario_id');
    }

    public function areaOrigen(): BelongsTo
    {
        return $this->belongsTo(Area::class, 'area_origen_id');
    }

    public function areaDestino(): BelongsTo
    {
        return $this->belongsTo(Area::class, 'area_destino_id');
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Scopes

    public function scopePorTipo($query, TipoMovimientoInventario $tipo)
    {
        return $query->where('tipo_movimiento', $tipo);
    }

    public function scopeEntradas($query)
    {
        return $query->whereIn('tipo_movimiento', [
            TipoMovimientoInventario::ENTRADA,
            TipoMovimientoInventario::AJUSTE_POSITIVO,
            TipoMovimientoInventario::TRANSFERENCIA_ENTRADA,
            TipoMovimientoInventario::DEVOLUCION,
        ]);
    }

    public function scopeSalidas($query)
    {
        return $query->whereIn('tipo_movimiento', [
            TipoMovimientoInventario::SALIDA,
            TipoMovimientoInventario::AJUSTE_NEGATIVO,
            TipoMovimientoInventario::TRANSFERENCIA_SALIDA,
            TipoMovimientoInventario::MERMA,
            TipoMovimientoInventario::CADUCIDAD,
            TipoMovimientoInventario::DESPACHO,
        ]);
    }

    public function scopeEnRango($query, $fechaInicio, $fechaFin)
    {
        return $query->whereBetween('fecha_movimiento', [$fechaInicio, $fechaFin]);
    }

    // Métodos de ayuda

    public function esEntrada(): bool
    {
        return $this->tipo_movimiento->esPositivo();
    }

    public function esSalida(): bool
    {
        return $this->tipo_movimiento->esNegativo();
    }
}
