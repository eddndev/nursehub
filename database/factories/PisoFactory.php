<?php

namespace Database\Factories;

use App\Models\Area;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Piso>
 */
class PisoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $especialidades = [
            'Cardiología',
            'Neurología',
            'Traumatología',
            'Medicina Interna',
            'Pediatría General',
            'Cuidados Intensivos',
        ];

        $numeroPiso = fake()->numberBetween(1, 10);

        return [
            'area_id' => Area::factory(),
            'nombre' => 'Piso ' . $numeroPiso,
            'numero_piso' => $numeroPiso,
            'especialidad' => fake()->randomElement($especialidades),
        ];
    }
}
