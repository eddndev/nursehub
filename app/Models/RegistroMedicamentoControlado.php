<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RegistroMedicamentoControlado extends Model
{
    use HasFactory;

    protected $table = 'registro_medicamento_controlado';

    protected $fillable = [
        'medicamento_id',
        'solicitud_id',
        'administracion_id',
        'tipo_operacion',
        'cantidad',
        'usuario_id',
        'autorizado_por',
        'numero_receta',
        'justificacion',
        'fecha_operacion',
    ];

    protected $casts = [
        'fecha_operacion' => 'datetime',
    ];

    // Relaciones

    public function medicamento(): BelongsTo
    {
        return $this->belongsTo(Medicamento::class);
    }

    public function solicitud(): BelongsTo
    {
        return $this->belongsTo(SolicitudMedicamento::class, 'solicitud_id');
    }

    public function administracion(): BelongsTo
    {
        return $this->belongsTo(AdministracionMedicamento::class, 'administracion_id');
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function autorizadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'autorizado_por');
    }

    // Scopes

    public function scopePorMedicamento($query, int $medicamentoId)
    {
        return $query->where('medicamento_id', $medicamentoId);
    }

    public function scopePorTipo($query, string $tipo)
    {
        return $query->where('tipo_operacion', $tipo);
    }

    public function scopeEnRango($query, $fechaInicio, $fechaFin)
    {
        return $query->whereBetween('fecha_operacion', [$fechaInicio, $fechaFin]);
    }

    public function scopeEntradas($query)
    {
        return $query->where('tipo_operacion', 'entrada');
    }

    public function scopeSalidas($query)
    {
        return $query->where('tipo_operacion', 'salida');
    }

    // Métodos de ayuda

    public function tieneDobleVerificacion(): bool
    {
        return !is_null($this->autorizado_por) && $this->usuario_id !== $this->autorizado_por;
    }

    public function esValido(): bool
    {
        return $this->tieneDobleVerificacion() && !empty($this->justificacion);
    }
}
