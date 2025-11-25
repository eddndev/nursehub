<?php

namespace App\Services;

use App\Models\AdministracionMedicamento;
use App\Models\InteraccionMedicamentosa;
use App\Models\Medicamento;
use App\Models\Paciente;
use App\Enums\SeveridadInteraccion;
use Illuminate\Support\Collection;

class InteraccionMedicamentosaService
{
    /**
     * Verifica interacciones entre un medicamento y los medicamentos activos del paciente
     *
     * @param Paciente $paciente
     * @param int $medicamentoId
     * @param int $horasRetroactivas Horas a revisar en el historial (default: 24h)
     * @return Collection
     */
    public function verificarInteracciones(Paciente $paciente, int $medicamentoId, int $horasRetroactivas = 24): Collection
    {
        // Obtener medicamentos administrados recientemente al paciente
        $medicamentosActivos = AdministracionMedicamento::where('paciente_id', $paciente->id)
            ->where('fecha_hora_administracion', '>=', now()->subHours($horasRetroactivas))
            ->with('medicamento')
            ->get()
            ->pluck('medicamento_id')
            ->unique();

        $interacciones = collect();

        foreach ($medicamentosActivos as $medicamentoActivoId) {
            // Buscar interacción bidireccional
            $interaccion = InteraccionMedicamentosa::where(function ($query) use ($medicamentoId, $medicamentoActivoId) {
                $query->where('medicamento_a_id', $medicamentoId)
                    ->where('medicamento_b_id', $medicamentoActivoId);
            })->orWhere(function ($query) use ($medicamentoId, $medicamentoActivoId) {
                $query->where('medicamento_a_id', $medicamentoActivoId)
                    ->where('medicamento_b_id', $medicamentoId);
            })->with(['medicamentoA', 'medicamentoB'])->first();

            if ($interaccion) {
                $interacciones->push($interaccion);
            }
        }

        return $interacciones->sortByDesc(function ($interaccion) {
            // Ordenar por severidad (más graves primero)
            $orden = [
                SeveridadInteraccion::CONTRAINDICADA->value => 4,
                SeveridadInteraccion::GRAVE->value => 3,
                SeveridadInteraccion::MODERADA->value => 2,
                SeveridadInteraccion::LEVE->value => 1,
            ];

            return $orden[$interaccion->severidad->value] ?? 0;
        })->values();
    }

    /**
     * Verifica si existe alguna interacción grave o contraindicada
     *
     * @param Paciente $paciente
     * @param int $medicamentoId
     * @return bool
     */
    public function tieneInteraccionGrave(Paciente $paciente, int $medicamentoId): bool
    {
        $interacciones = $this->verificarInteracciones($paciente, $medicamentoId);

        return $interacciones->contains(function ($interaccion) {
            return in_array($interaccion->severidad, [
                SeveridadInteraccion::GRAVE,
                SeveridadInteraccion::CONTRAINDICADA,
            ]);
        });
    }

    /**
     * Verifica si la administración está bloqueada por interacciones contraindicadas
     *
     * @param Paciente $paciente
     * @param int $medicamentoId
     * @return bool
     */
    public function bloqueaAdministracion(Paciente $paciente, int $medicamentoId): bool
    {
        $interacciones = $this->verificarInteracciones($paciente, $medicamentoId);

        return $interacciones->contains(function ($interaccion) {
            return $interaccion->severidad === SeveridadInteraccion::CONTRAINDICADA;
        });
    }

    /**
     * Obtiene un resumen de las interacciones para mostrar al usuario
     *
     * @param Paciente $paciente
     * @param int $medicamentoId
     * @return array
     */
    public function obtenerResumenInteracciones(Paciente $paciente, int $medicamentoId): array
    {
        $interacciones = $this->verificarInteracciones($paciente, $medicamentoId);

        $medicamento = Medicamento::find($medicamentoId);

        return [
            'medicamento' => $medicamento,
            'total_interacciones' => $interacciones->count(),
            'tiene_contraindicadas' => $interacciones->where('severidad', SeveridadInteraccion::CONTRAINDICADA)->count() > 0,
            'tiene_graves' => $interacciones->where('severidad', SeveridadInteraccion::GRAVE)->count() > 0,
            'bloqueado' => $this->bloqueaAdministracion($paciente, $medicamentoId),
            'requiere_confirmacion' => $this->tieneInteraccionGrave($paciente, $medicamentoId),
            'interacciones' => $interacciones->map(function ($interaccion) use ($medicamentoId) {
                // Determinar cuál es el "otro" medicamento en la interacción
                $otroMedicamento = $interaccion->medicamento_a_id === $medicamentoId
                    ? $interaccion->medicamentoB
                    : $interaccion->medicamentoA;

                return [
                    'id' => $interaccion->id,
                    'otro_medicamento' => $otroMedicamento->nombre_comercial,
                    'severidad' => $interaccion->severidad,
                    'severidad_label' => $interaccion->severidad->getLabel(),
                    'severidad_color' => $interaccion->severidad->getColor(),
                    'descripcion' => $interaccion->descripcion,
                    'recomendacion' => $interaccion->recomendacion,
                    'fuente' => $interaccion->fuente_referencia,
                ];
            })->toArray(),
        ];
    }

    /**
     * Registra una nueva interacción medicamentosa
     *
     * @param int $medicamentoAId
     * @param int $medicamentoBId
     * @param SeveridadInteraccion $severidad
     * @param string $descripcion
     * @param string|null $recomendacion
     * @param string|null $fuente
     * @return InteraccionMedicamentosa
     */
    public function registrarInteraccion(
        int $medicamentoAId,
        int $medicamentoBId,
        SeveridadInteraccion $severidad,
        string $descripcion,
        ?string $recomendacion = null,
        ?string $fuente = null
    ): InteraccionMedicamentosa {
        // Verificar que no exista ya la interacción (en cualquier orden)
        $existente = InteraccionMedicamentosa::where(function ($query) use ($medicamentoAId, $medicamentoBId) {
            $query->where('medicamento_a_id', $medicamentoAId)
                ->where('medicamento_b_id', $medicamentoBId);
        })->orWhere(function ($query) use ($medicamentoAId, $medicamentoBId) {
            $query->where('medicamento_a_id', $medicamentoBId)
                ->where('medicamento_b_id', $medicamentoAId);
        })->first();

        if ($existente) {
            // Actualizar la existente
            $existente->update([
                'severidad' => $severidad,
                'descripcion' => $descripcion,
                'recomendacion' => $recomendacion,
                'fuente_referencia' => $fuente,
            ]);

            return $existente;
        }

        // Crear nueva interacción
        return InteraccionMedicamentosa::create([
            'medicamento_a_id' => $medicamentoAId,
            'medicamento_b_id' => $medicamentoBId,
            'severidad' => $severidad,
            'descripcion' => $descripcion,
            'recomendacion' => $recomendacion,
            'fuente_referencia' => $fuente,
        ]);
    }

    /**
     * Obtiene todas las interacciones de un medicamento específico
     *
     * @param int $medicamentoId
     * @return Collection
     */
    public function obtenerInteraccionesDeMedicamento(int $medicamentoId): Collection
    {
        return InteraccionMedicamentosa::where('medicamento_a_id', $medicamentoId)
            ->orWhere('medicamento_b_id', $medicamentoId)
            ->with(['medicamentoA', 'medicamentoB'])
            ->get();
    }
}
