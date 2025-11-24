<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AsistenciaCapacitacion>
 */
class AsistenciaCapacitacionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $presente = fake()->boolean(85);
        $horaEntrada = $presente ? fake()->time('H:i:s') : null;
        $horaSalida = $presente && fake()->boolean(70) ? fake()->time('H:i:s') : null;

        $minutosAsistidos = null;
        if ($horaEntrada && $horaSalida) {
            $entrada = \Carbon\Carbon::parse($horaEntrada);
            $salida = \Carbon\Carbon::parse($horaSalida);
            $minutosAsistidos = $salida->diffInMinutes($entrada);
        }

        return [
            'sesion_id' => \App\Models\SesionCapacitacion::factory(),
            'inscripcion_id' => \App\Models\InscripcionCapacitacion::factory(),
            'presente' => $presente,
            'hora_entrada' => $horaEntrada,
            'hora_salida' => $horaSalida,
            'minutos_asistidos' => $minutosAsistidos,
            'observaciones' => fake()->optional(0.2)->sentence(),
            'tardanza' => $presente && fake()->boolean(15),
            'salida_temprana' => $presente && fake()->boolean(10),
            'registrado_por' => \App\Models\User::factory(),
            'registrado_at' => fake()->optional(0.8)->dateTimeBetween('-1 week', 'now'),
        ];
    }
}
