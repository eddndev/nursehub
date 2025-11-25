<?php

namespace App\Models;

use App\Enums\ViaAdministracionMedicamento;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdministracionMedicamento extends Model
{
    use HasFactory;

    protected $table = 'administraciones_medicamento';

    protected $fillable = [
        'paciente_id',
        'enfermero_id',
        'medicamento_id',
        'solicitud_id',
        'admision_id',
        'fecha_hora_administracion',
        'dosis_administrada',
        'via_administracion',
        'observaciones',
        'tuvo_reaccion_adversa',
        'descripcion_reaccion',
        'verificado_por',
    ];

    protected $casts = [
        'fecha_hora_administracion' => 'datetime',
        'tuvo_reaccion_adversa' => 'boolean',
        'via_administracion' => ViaAdministracionMedicamento::class,
    ];

    // Relaciones

    public function paciente(): BelongsTo
    {
        return $this->belongsTo(Paciente::class);
    }

    public function enfermero(): BelongsTo
    {
        return $this->belongsTo(Enfermero::class);
    }

    public function medicamento(): BelongsTo
    {
        return $this->belongsTo(Medicamento::class);
    }

    public function solicitud(): BelongsTo
    {
        return $this->belongsTo(SolicitudMedicamento::class, 'solicitud_id');
    }

    public function admision(): BelongsTo
    {
        return $this->belongsTo(Admision::class);
    }

    public function verificadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verificado_por');
    }

    // Scopes

    public function scopePorPaciente($query, int $pacienteId)
    {
        return $query->where('paciente_id', $pacienteId);
    }

    public function scopePorEnfermero($query, int $enfermeroId)
    {
        return $query->where('enfermero_id', $enfermeroId);
    }

    public function scopeConReaccionAdversa($query)
    {
        return $query->where('tuvo_reaccion_adversa', true);
    }

    public function scopeEnRango($query, $fechaInicio, $fechaFin)
    {
        return $query->whereBetween('fecha_hora_administracion', [$fechaInicio, $fechaFin]);
    }

    public function scopeUltimas24Horas($query, int $pacienteId)
    {
        return $query->where('paciente_id', $pacienteId)
            ->where('fecha_hora_administracion', '>=', now()->subHours(24));
    }

    // Métodos de ayuda

    public function estaVerificada(): bool
    {
        return !is_null($this->verificado_por);
    }

    public function puedeModificarse(): bool
    {
        // No se puede modificar después de 24 horas
        return $this->fecha_hora_administracion->diffInHours(now()) < 24;
    }
}
