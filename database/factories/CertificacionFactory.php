<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Certificacion>
 */
class CertificacionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $inscripcion = \App\Models\InscripcionCapacitacion::factory()->create();
        $numeroCertificado = \App\Models\Certificacion::generarNumeroCertificado();
        $hashVerificacion = \App\Models\Certificacion::generarHashVerificacion($inscripcion, $numeroCertificado);

        $fechaEmision = fake()->dateTimeBetween('-1 year', 'now');
        $fechaVigenciaInicio = $fechaEmision;
        $horasCertificadas = fake()->numberBetween(4, 80);

        return [
            'inscripcion_id' => $inscripcion->id,
            'numero_certificado' => $numeroCertificado,
            'fecha_emision' => $fechaEmision,
            'fecha_vigencia_inicio' => $fechaVigenciaInicio,
            'fecha_vigencia_fin' => fake()->optional(0.3)->dateTimeBetween($fechaVigenciaInicio, '+3 years'),
            'horas_certificadas' => $horasCertificadas,
            'calificacion_obtenida' => fake()->randomFloat(2, 70, 100),
            'porcentaje_asistencia' => fake()->randomFloat(2, 80, 100),
            'competencias_desarrolladas' => fake()->optional(0.7)->paragraph(2),
            'observaciones' => fake()->optional(0.3)->sentence(),
            'hash_verificacion' => $hashVerificacion,
            'url_descarga' => fake()->optional(0.5)->url(),
            'emitido_por' => \App\Models\User::factory(),
            'emitido_at' => $fechaEmision,
        ];
    }
}
