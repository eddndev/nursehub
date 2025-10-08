<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Area>
 */
class AreaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $areas = [
            ['nombre' => 'Urgencias', 'codigo' => 'URG', 'ratio' => 0.25],
            ['nombre' => 'Unidad de Cuidados Intensivos', 'codigo' => 'UCI', 'ratio' => 0.50],
            ['nombre' => 'Cirugía General', 'codigo' => 'CIR', 'ratio' => 0.17],
            ['nombre' => 'Pediatría', 'codigo' => 'PED', 'ratio' => 0.20],
            ['nombre' => 'Oncología', 'codigo' => 'ONC', 'ratio' => 0.20],
        ];

        $area = fake()->randomElement($areas);

        return [
            'nombre' => $area['nombre'],
            'codigo' => $area['codigo'],
            'descripcion' => fake()->sentence(),
            'opera_24_7' => fake()->boolean(80), // 80% opera 24/7
            'ratio_enfermero_paciente' => $area['ratio'],
            'requiere_certificacion' => fake()->boolean(40), // 40% requiere certificación
        ];
    }
}
