<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RegistroSignosVitales>
 */
class RegistroSignosVitalesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'paciente_id' => \App\Models\Paciente::factory(),
            'registrado_por' => \App\Models\User::factory(),
            'presion_arterial_sistolica' => fake()->numberBetween(90, 180),
            'presion_arterial_diastolica' => fake()->numberBetween(60, 120),
            'frecuencia_cardiaca' => fake()->numberBetween(50, 120),
            'frecuencia_respiratoria' => fake()->numberBetween(12, 25),
            'temperatura' => fake()->randomFloat(2, 35.5, 39.5),
            'saturacion_oxigeno' => fake()->randomFloat(2, 88, 100),
            'glucosa' => fake()->optional(0.5)->randomFloat(2, 70, 200),
            'nivel_triage' => fake()->randomElement(['rojo', 'naranja', 'amarillo', 'verde', 'azul']),
            'triage_override' => false,
            'observaciones' => fake()->optional(0.3)->sentence(),
            'fecha_registro' => fake()->dateTimeBetween('-7 days', 'now'),
        ];
    }

    public function critico(): static
    {
        return $this->state(fn (array $attributes) => [
            'presion_arterial_sistolica' => fake()->numberBetween(160, 220),
            'frecuencia_cardiaca' => fake()->numberBetween(120, 160),
            'temperatura' => fake()->randomFloat(2, 38.5, 41.0),
            'saturacion_oxigeno' => fake()->randomFloat(2, 80, 89),
            'nivel_triage' => 'rojo',
        ]);
    }

    public function normal(): static
    {
        return $this->state(fn (array $attributes) => [
            'presion_arterial_sistolica' => fake()->numberBetween(110, 130),
            'presion_arterial_diastolica' => fake()->numberBetween(70, 85),
            'frecuencia_cardiaca' => fake()->numberBetween(60, 90),
            'frecuencia_respiratoria' => fake()->numberBetween(14, 20),
            'temperatura' => fake()->randomFloat(2, 36.0, 37.5),
            'saturacion_oxigeno' => fake()->randomFloat(2, 95, 100),
            'nivel_triage' => 'verde',
        ]);
    }
}
