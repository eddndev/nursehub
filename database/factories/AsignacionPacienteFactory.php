<?php

namespace Database\Factories;

use App\Models\Enfermero;
use App\Models\Paciente;
use App\Models\Turno;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AsignacionPaciente>
 */
class AsignacionPacienteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'turno_id' => Turno::factory(),
            'enfermero_id' => Enfermero::factory(),
            'paciente_id' => Paciente::factory(),
            'fecha_hora_asignacion' => now()->subHours(rand(1, 8)),
            'fecha_hora_liberacion' => null,
            'asignado_por' => User::factory(),
            'liberado_por' => null,
            'motivo_liberacion' => null,
        ];
    }

    /**
     * Indicate that the assignment is active.
     */
    public function activa(): static
    {
        return $this->state(fn (array $attributes) => [
            'fecha_hora_liberacion' => null,
            'liberado_por' => null,
            'motivo_liberacion' => null,
        ]);
    }

    /**
     * Indicate that the assignment is released.
     */
    public function liberada(): static
    {
        return $this->state(fn (array $attributes) => [
            'fecha_hora_liberacion' => now(),
            'liberado_por' => User::factory(),
            'motivo_liberacion' => fake()->randomElement([
                'Fin de turno',
                'Alta del paciente',
                'Cambio de asignación',
                'Rotación de personal',
            ]),
        ]);
    }
}
