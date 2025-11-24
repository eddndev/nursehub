<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ActividadCapacitacion>
 */
class ActividadCapacitacionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $fechaInicio = fake()->dateTimeBetween('now', '+3 months');
        $duracionHoras = fake()->numberBetween(4, 40);
        $fechaFin = (clone $fechaInicio)->modify("+{$duracionHoras} hours");

        return [
            'titulo' => fake()->randomElement([
                'Actualización en Cuidados Intensivos',
                'Manejo Avanzado de Vías Aéreas',
                'Taller de Venopunción y Cateterización',
                'Curso de RCP Avanzado',
                'Seminario de Cuidados Paliativos',
                'Capacitación en Manejo del Dolor',
                'Actualización en Farmacología',
                'Taller de Heridas y Curaciones',
                'Curso de Urgencias Pediátricas',
                'Seminario de Bioética en Enfermería',
            ]),
            'descripcion' => fake()->paragraph(3),
            'tipo' => fake()->randomElement(\App\Enums\TipoActividad::cases()),
            'estado' => fake()->randomElement(\App\Enums\EstadoActividad::cases()),
            'modalidad' => fake()->randomElement(['presencial', 'virtual', 'hibrida']),
            'ubicacion' => fake()->randomElement(['Auditorio Principal', 'Sala de Conferencias', 'Aula 301', 'Laboratorio de Simulación', null]),
            'url_virtual' => fake()->optional(0.3)->url(),
            'duracion_horas' => $duracionHoras,
            'cupo_minimo' => fake()->numberBetween(5, 10),
            'cupo_maximo' => fake()->numberBetween(15, 50),
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin,
            'hora_inicio' => fake()->time('H:i:s'),
            'hora_fin' => fake()->time('H:i:s'),
            'fecha_limite_inscripcion' => fake()->optional(0.8)->dateTimeBetween('now', $fechaInicio),
            'porcentaje_asistencia_minimo' => fake()->randomElement([70.00, 75.00, 80.00, 85.00]),
            'calificacion_minima_aprobacion' => fake()->optional(0.7)->randomElement([70.00, 75.00, 80.00]),
            'otorga_certificado' => fake()->boolean(80),
            'instructor_nombre' => fake()->name(),
            'instructor_credenciales' => fake()->optional(0.6)->randomElement([
                'Maestría en Enfermería',
                'Especialista en Cuidados Críticos',
                'Doctorado en Ciencias de la Salud',
                'Certificación Internacional en RCP',
            ]),
            'objetivos' => fake()->optional(0.7)->paragraph(2),
            'contenido_tematico' => fake()->optional(0.7)->paragraph(3),
            'recursos_necesarios' => fake()->optional(0.5)->sentence(),
            'evaluacion_metodo' => fake()->optional(0.6)->randomElement([
                'Examen teórico',
                'Evaluación práctica',
                'Examen teórico-práctico',
                'Presentación de caso',
            ]),
            'notas_adicionales' => fake()->optional(0.3)->sentence(),
            'area_id' => \App\Models\Area::factory(),
            'creado_por' => \App\Models\User::factory(),
        ];
    }
}
