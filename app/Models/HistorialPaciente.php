<?php

namespace App\Models;

use App\Enums\TipoEventoHistorial;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HistorialPaciente extends Model
{
    use HasFactory;

    protected $fillable = [
        'paciente_id',
        'usuario_id',
        'tipo_evento',
        'descripcion',
        'metadata',
        'fecha_evento',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
            'fecha_evento' => 'datetime',
            'tipo_evento' => TipoEventoHistorial::class,
        ];
    }

    public function paciente(): BelongsTo
    {
        return $this->belongsTo(Paciente::class);
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeRecientes($query, int $limit = 50)
    {
        return $query->orderBy('fecha_evento', 'desc')->limit($limit);
    }

    public function scopePorPaciente($query, int $pacienteId)
    {
        return $query->where('paciente_id', $pacienteId);
    }

    public function scopePorTipoEvento($query, TipoEventoHistorial $tipoEvento)
    {
        return $query->where('tipo_evento', $tipoEvento);
    }
}
