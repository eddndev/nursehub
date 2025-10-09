<?php

namespace Database\Factories;

use App\Models\Piso;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cuarto>
 */
class CuartoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $tipos = ['individual', 'doble', 'multiple'];
        $numeroCuarto = fake()->numberBetween(101, 999);

        return [
            'piso_id' => Piso::factory(),
            'numero_cuarto' => (string) $numeroCuarto,
            'tipo' => fake()->randomElement($tipos),
        ];
    }
}
