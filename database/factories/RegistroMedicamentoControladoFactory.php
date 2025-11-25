<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RegistroMedicamentoControlado>
 */
class RegistroMedicamentoControladoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'medicamento_id' => \App\Models\Medicamento::factory()->controlado(),
            'solicitud_id' => \App\Models\SolicitudMedicamento::factory(),
            'administracion_id' => null,
            'tipo_operacion' => fake()->randomElement(['entrada', 'salida', 'ajuste', 'destruccion']),
            'cantidad' => fake()->numberBetween(1, 20),
            'usuario_id' => \App\Models\User::factory(),
            'autorizado_por' => \App\Models\User::factory(),
            'numero_receta' => 'RX-' . fake()->numerify('####-####'),
            'justificacion' => fake()->sentence(15),
            'fecha_operacion' => fake()->dateTimeBetween('-30 days', 'now'),
        ];
    }
}
