<?php

namespace Database\Factories;

use App\Enums\CamaEstado;
use App\Models\Cuarto;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cama>
 */
class CamaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'cuarto_id' => Cuarto::factory(),
            'numero_cama' => fake()->unique()->numerify('C-###'),
            'estado' => CamaEstado::LIBRE, // Por defecto libre para testing
        ];
    }

    /**
     * Indicar que la cama está libre
     */
    public function libre(): static
    {
        return $this->state(fn (array $attributes) => [
            'estado' => CamaEstado::LIBRE,
        ]);
    }

    /**
     * Indicar que la cama está ocupada
     */
    public function ocupada(): static
    {
        return $this->state(fn (array $attributes) => [
            'estado' => CamaEstado::OCUPADA,
        ]);
    }
}
