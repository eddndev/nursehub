<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SesionCapacitacion>
 */
class SesionCapacitacionFactory extends Factory
{
    private static $sesionCounter = [];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $horaInicio = fake()->time('H:i:s');
        $duracionMinutos = fake()->numberBetween(60, 240);
        $actividadId = \App\Models\ActividadCapacitacion::factory();

        return [
            'actividad_id' => $actividadId,
            'numero_sesion' => function (array $attributes) {
                $actId = $attributes['actividad_id'];
                if (!isset(self::$sesionCounter[$actId])) {
                    self::$sesionCounter[$actId] = 0;
                }
                self::$sesionCounter[$actId]++;
                return self::$sesionCounter[$actId];
            },
            'titulo' => fake()->sentence(4),
            'descripcion' => fake()->optional(0.7)->paragraph(2),
            'fecha' => fake()->dateTimeBetween('now', '+3 months'),
            'hora_inicio' => $horaInicio,
            'hora_fin' => date('H:i:s', strtotime($horaInicio) + ($duracionMinutos * 60)),
            'duracion_minutos' => $duracionMinutos,
            'ubicacion' => fake()->optional(0.7)->randomElement(['Auditorio Principal', 'Sala de Conferencias', 'Aula 301', 'Laboratorio de Simulación']),
            'url_virtual' => fake()->optional(0.3)->url(),
            'instructor_nombre' => fake()->optional(0.8)->name(),
            'contenido' => fake()->optional(0.6)->paragraph(3),
            'recursos_utilizados' => fake()->optional(0.5)->sentence(),
            'observaciones' => fake()->optional(0.3)->sentence(),
            'asistencia_registrada' => fake()->boolean(30),
        ];
    }
}
