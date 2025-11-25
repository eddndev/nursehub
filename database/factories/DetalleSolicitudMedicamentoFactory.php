<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DetalleSolicitudMedicamento>
 */
class DetalleSolicitudMedicamentoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $cantidadSolicitada = fake()->numberBetween(1, 50);
        $cantidadDespachada = fake()->numberBetween(0, $cantidadSolicitada);

        return [
            'solicitud_id' => \App\Models\SolicitudMedicamento::factory(),
            'medicamento_id' => \App\Models\Medicamento::factory(),
            'cantidad_solicitada' => $cantidadSolicitada,
            'cantidad_despachada' => $cantidadDespachada,
            'inventario_id' => $cantidadDespachada > 0 ? \App\Models\InventarioMedicamento::factory() : null,
            'indicaciones_medicas' => fake()->optional()->sentence(12),
        ];
    }
}
