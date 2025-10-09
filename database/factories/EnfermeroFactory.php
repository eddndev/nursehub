<?php

namespace Database\Factories;

use App\Enums\TipoAsignacion;
use App\Models\Area;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Enfermero>
 */
class EnfermeroFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $tipoAsignacion = fake()->randomElement([TipoAsignacion::FIJO, TipoAsignacion::ROTATIVO]);

        // Solo asignar área fija si el tipo es FIJO
        $areaFijaId = $tipoAsignacion === TipoAsignacion::FIJO
            ? Area::inRandomOrder()->first()?->id
            : null;

        $especialidades = fake()->optional(0.6)->passthrough(
            fake()->randomElements(
                ['Urgencias', 'UCI', 'Pediatría', 'Geriatría', 'Oncología', 'Quirófano', 'Medicina Interna'],
                fake()->numberBetween(1, 3)
            )
        );

        return [
            'user_id' => User::factory(),
            'cedula_profesional' => fake()->unique()->numerify('########'),
            'tipo_asignacion' => $tipoAsignacion,
            'area_fija_id' => $areaFijaId,
            'especialidades' => $especialidades ? implode(', ', $especialidades) : null,
            'anos_experiencia' => fake()->numberBetween(0, 30),
        ];
    }

    /**
     * Indica que el enfermero es de tipo FIJO
     */
    public function fijo(?int $areaId = null): static
    {
        return $this->state(fn (array $attributes) => [
            'tipo_asignacion' => TipoAsignacion::FIJO,
            'area_fija_id' => $areaId ?? Area::inRandomOrder()->first()?->id,
        ]);
    }

    /**
     * Indica que el enfermero es de tipo ROTATIVO
     */
    public function rotativo(): static
    {
        return $this->state(fn (array $attributes) => [
            'tipo_asignacion' => TipoAsignacion::ROTATIVO,
            'area_fija_id' => null,
        ]);
    }
}
