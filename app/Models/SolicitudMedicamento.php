<?php

namespace App\Models;

use App\Enums\EstadoSolicitudMedicamento;
use App\Enums\PrioridadSolicitudMedicamento;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SolicitudMedicamento extends Model
{
    use HasFactory;

    protected $table = 'solicitudes_medicamento';

    protected $fillable = [
        'numero_solicitud',
        'enfermero_id',
        'paciente_id',
        'area_id',
        'prioridad',
        'estado',
        'fecha_solicitud',
        'aprobado_por',
        'fecha_aprobacion',
        'despachado_por',
        'fecha_despacho',
        'observaciones',
        'motivo_rechazo',
        'es_urgencia',
    ];

    protected $casts = [
        'prioridad' => PrioridadSolicitudMedicamento::class,
        'estado' => EstadoSolicitudMedicamento::class,
        'fecha_solicitud' => 'datetime',
        'fecha_aprobacion' => 'datetime',
        'fecha_despacho' => 'datetime',
        'es_urgencia' => 'boolean',
    ];

    // Relaciones

    public function enfermero(): BelongsTo
    {
        return $this->belongsTo(Enfermero::class);
    }

    public function paciente(): BelongsTo
    {
        return $this->belongsTo(Paciente::class);
    }

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    public function aprobadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'aprobado_por');
    }

    public function despachadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'despachado_por');
    }

    public function detalles(): HasMany
    {
        return $this->hasMany(DetalleSolicitudMedicamento::class, 'solicitud_id');
    }

    public function administraciones(): HasMany
    {
        return $this->hasMany(AdministracionMedicamento::class, 'solicitud_id');
    }

    public function registrosControlados(): HasMany
    {
        return $this->hasMany(RegistroMedicamentoControlado::class, 'solicitud_id');
    }

    // Scopes

    public function scopePendientes($query)
    {
        return $query->where('estado', EstadoSolicitudMedicamento::PENDIENTE);
    }

    public function scopeAprobadas($query)
    {
        return $query->where('estado', EstadoSolicitudMedicamento::APROBADA);
    }

    public function scopeDespachadas($query)
    {
        return $query->where('estado', EstadoSolicitudMedicamento::DESPACHADA);
    }

    public function scopeUrgentes($query)
    {
        return $query->where('prioridad', PrioridadSolicitudMedicamento::STAT)
            ->orWhere('prioridad', PrioridadSolicitudMedicamento::URGENTE);
    }

    public function scopePorEnfermero($query, int $enfermeroId)
    {
        return $query->where('enfermero_id', $enfermeroId);
    }

    public function scopePorPaciente($query, int $pacienteId)
    {
        return $query->where('paciente_id', $pacienteId);
    }

    // Métodos de ayuda

    public function puedeModificarse(): bool
    {
        return $this->estado->puedeModificarse();
    }

    public function puedeCancelarse(): bool
    {
        return $this->estado->puedeCancelarse();
    }

    public function aprobar(int $usuarioId): bool
    {
        if ($this->estado !== EstadoSolicitudMedicamento::PENDIENTE) {
            return false;
        }

        $this->update([
            'estado' => EstadoSolicitudMedicamento::APROBADA,
            'aprobado_por' => $usuarioId,
            'fecha_aprobacion' => now(),
        ]);

        return true;
    }

    public function rechazar(int $usuarioId, string $motivo): bool
    {
        if ($this->estado !== EstadoSolicitudMedicamento::PENDIENTE) {
            return false;
        }

        $this->update([
            'estado' => EstadoSolicitudMedicamento::RECHAZADA,
            'aprobado_por' => $usuarioId,
            'fecha_aprobacion' => now(),
            'motivo_rechazo' => $motivo,
        ]);

        return true;
    }

    public function despachar(int $usuarioId): bool
    {
        if ($this->estado !== EstadoSolicitudMedicamento::APROBADA) {
            return false;
        }

        $this->update([
            'estado' => EstadoSolicitudMedicamento::DESPACHADA,
            'despachado_por' => $usuarioId,
            'fecha_despacho' => now(),
        ]);

        return true;
    }

    public function getTotalMedicamentosAttribute(): int
    {
        return $this->detalles()->count();
    }

    public function getTotalDespachado(): int
    {
        return $this->detalles()->sum('cantidad_despachada');
    }
}
