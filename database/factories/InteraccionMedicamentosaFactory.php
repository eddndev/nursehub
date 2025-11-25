<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InteraccionMedicamentosa>
 */
class InteraccionMedicamentosaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'medicamento_a_id' => \App\Models\Medicamento::factory(),
            'medicamento_b_id' => \App\Models\Medicamento::factory(),
            'severidad' => fake()->randomElement(\App\Enums\SeveridadInteraccion::cases()),
            'descripcion' => fake()->sentence(15),
            'recomendacion' => fake()->sentence(12),
            'fuente_referencia' => fake()->randomElement([
                'Vademécum Internacional',
                'Micromedex DrugReax',
                'UpToDate',
                'Clinical Pharmacology',
            ]),
        ];
    }
}
