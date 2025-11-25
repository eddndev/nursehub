<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AdministracionMedicamento>
 */
class AdministracionMedicamentoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $tuvoReaccion = fake()->boolean(5); // 5% probabilidad

        return [
            'paciente_id' => \App\Models\Paciente::factory(),
            'enfermero_id' => \App\Models\Enfermero::factory(),
            'medicamento_id' => \App\Models\Medicamento::factory(),
            'solicitud_id' => \App\Models\SolicitudMedicamento::factory(),
            'admision_id' => null,
            'fecha_hora_administracion' => fake()->dateTimeBetween('-7 days', 'now'),
            'dosis_administrada' => fake()->randomElement(['500mg', '1g', '10ml', '5ml', '2 tabletas', '1 ampolleta']),
            'via_administracion' => fake()->randomElement(\App\Enums\ViaAdministracionMedicamento::cases()),
            'observaciones' => fake()->optional()->sentence(10),
            'tuvo_reaccion_adversa' => $tuvoReaccion,
            'descripcion_reaccion' => $tuvoReaccion ? fake()->sentence(12) : null,
            'verificado_por' => fake()->boolean(50) ? \App\Models\User::factory() : null,
        ];
    }
}
