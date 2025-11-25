<?php

namespace App\Models;

use App\Enums\CategoriaMedicamento;
use App\Enums\ViaAdministracionMedicamento;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Medicamento extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'medicamentos';

    protected $fillable = [
        'codigo_medicamento',
        'nombre_comercial',
        'nombre_generico',
        'principio_activo',
        'laboratorio',
        'presentacion',
        'concentracion',
        'via_administracion',
        'categoria',
        'es_controlado',
        'precio_unitario',
        'indicaciones',
        'contraindicaciones',
        'efectos_adversos',
        'dosis_maxima_24h',
        'unidad_dosis_maxima',
        'requiere_refrigeracion',
        'activo',
    ];

    protected $casts = [
        'es_controlado' => 'boolean',
        'requiere_refrigeracion' => 'boolean',
        'activo' => 'boolean',
        'precio_unitario' => 'decimal:2',
        'dosis_maxima_24h' => 'decimal:2',
        'via_administracion' => ViaAdministracionMedicamento::class,
        'categoria' => CategoriaMedicamento::class,
    ];

    // Relaciones

    public function inventarios(): HasMany
    {
        return $this->hasMany(InventarioMedicamento::class);
    }

    public function administraciones(): HasMany
    {
        return $this->hasMany(AdministracionMedicamento::class);
    }

    public function movimientos(): HasMany
    {
        return $this->hasMany(MovimientoInventario::class);
    }

    public function interaccionesComo_A(): HasMany
    {
        return $this->hasMany(InteraccionMedicamentosa::class, 'medicamento_a_id');
    }

    public function interaccionesComo_B(): HasMany
    {
        return $this->hasMany(InteraccionMedicamentosa::class, 'medicamento_b_id');
    }

    public function medicamentosConInteraccion(): BelongsToMany
    {
        return $this->belongsToMany(
            Medicamento::class,
            'interacciones_medicamentosas',
            'medicamento_a_id',
            'medicamento_b_id'
        )->withPivot('severidad', 'descripcion', 'recomendacion', 'fuente_referencia');
    }

    public function registrosControlados(): HasMany
    {
        return $this->hasMany(RegistroMedicamentoControlado::class);
    }

    // Scopes

    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    public function scopeControlados($query)
    {
        return $query->where('es_controlado', true);
    }

    public function scopeDisponibles($query)
    {
        return $query->whereHas('inventarios', function ($q) {
            $q->where('cantidad_actual', '>', 0)
              ->where('estado', 'disponible');
        });
    }

    public function scopeBuscar($query, $termino)
    {
        return $query->where(function ($q) use ($termino) {
            $q->where('nombre_comercial', 'like', "%{$termino}%")
              ->orWhere('nombre_generico', 'like', "%{$termino}%")
              ->orWhere('principio_activo', 'like', "%{$termino}%")
              ->orWhere('codigo_medicamento', 'like', "%{$termino}%");
        });
    }

    // Métodos de ayuda

    public function getStockTotal(): int
    {
        return $this->inventarios()
            ->where('estado', 'disponible')
            ->sum('cantidad_actual');
    }

    public function tieneInteraccionesCon(Medicamento $medicamento): bool
    {
        return $this->medicamentosConInteraccion()->where('medicamento_b_id', $medicamento->id)->exists()
            || $medicamento->medicamentosConInteraccion()->where('medicamento_b_id', $this->id)->exists();
    }

    public function getNombreCompletoAttribute(): string
    {
        return "{$this->nombre_comercial} ({$this->nombre_generico}) - {$this->concentracion}";
    }
}
