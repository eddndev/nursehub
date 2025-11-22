<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Paciente>
 */
class PacienteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $sexo = fake()->randomElement(['M', 'F']);
        $nombre = $sexo === 'M' ? fake()->firstNameMale() : fake()->firstNameFemale();

        return [
            'codigo_qr' => 'NHUB-' . now()->format('YmdHis') . '-' . strtoupper(fake()->unique()->bothify('???###')),
            'nombre' => $nombre,
            'apellido_paterno' => fake()->lastName(),
            'apellido_materno' => fake()->lastName(),
            'sexo' => $sexo,
            'fecha_nacimiento' => fake()->dateTimeBetween('-85 years', '-18 years'),
            'curp' => strtoupper(fake()->unique()->bothify('????######???###')),
            'telefono' => fake()->numerify('##########'),
            'contacto_emergencia_nombre' => fake()->name(),
            'contacto_emergencia_telefono' => fake()->numerify('##########'),
            'alergias' => fake()->optional(0.3)->randomElement([
                'Penicilina',
                'Aspirina',
                'Ninguna conocida',
                'Látex, Penicilina',
                'Mariscos',
            ]),
            'antecedentes_medicos' => fake()->optional(0.5)->randomElement([
                'Hipertensión',
                'Diabetes tipo 2',
                'Ninguno relevante',
                'Asma, Hipertensión',
                'Cirugía de apendicitis hace 5 años',
            ]),
            'estado' => fake()->randomElement(['activo', 'activo', 'activo', 'dado_alta']),
            'cama_actual_id' => null,
            'admitido_por' => \App\Models\User::factory(),
            'fecha_admision' => fake()->dateTimeBetween('-30 days', 'now'),
            'fecha_alta' => null,
        ];
    }
}
