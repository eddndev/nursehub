<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InscripcionCapacitacion>
 */
class InscripcionCapacitacionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'actividad_id' => \App\Models\ActividadCapacitacion::factory(),
            'enfermero_id' => \App\Models\Enfermero::factory(),
            'tipo' => fake()->randomElement(\App\Enums\TipoInscripcion::cases()),
            'estado' => fake()->randomElement(\App\Enums\EstadoInscripcion::cases()),
            'motivacion' => fake()->optional(0.6)->paragraph(2),
            'expectativas' => fake()->optional(0.5)->paragraph(1),
            'prioridad' => fake()->numberBetween(0, 5),
            'fecha_inscripcion' => fake()->dateTimeBetween('-1 month', 'now'),
            'inscrito_por' => \App\Models\User::factory(),
            'calificacion_final' => fake()->optional(0.4)->randomFloat(2, 60, 100),
            'porcentaje_asistencia' => fake()->optional(0.4)->randomFloat(2, 50, 100),
            'aprobado' => fake()->optional(0.3)->boolean(70),
            'observaciones_finales' => fake()->optional(0.3)->sentence(),
        ];
    }
}
