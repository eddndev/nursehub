<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SolicitudMedicamento>
 */
class SolicitudMedicamentoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $fechaSolicitud = fake()->dateTimeBetween('-7 days', 'now');

        return [
            'numero_solicitud' => 'SOL-' . now()->format('Y') . '-' . str_pad(fake()->unique()->numberBetween(1, 9999), 4, '0', STR_PAD_LEFT),
            'enfermero_id' => \App\Models\Enfermero::factory(),
            'paciente_id' => \App\Models\Paciente::factory(),
            'area_id' => \App\Models\Area::factory(),
            'prioridad' => fake()->randomElement(\App\Enums\PrioridadSolicitudMedicamento::cases()),
            'estado' => fake()->randomElement([
                \App\Enums\EstadoSolicitudMedicamento::PENDIENTE,
                \App\Enums\EstadoSolicitudMedicamento::APROBADA,
                \App\Enums\EstadoSolicitudMedicamento::DESPACHADA,
            ]),
            'fecha_solicitud' => $fechaSolicitud,
            'observaciones' => fake()->optional()->sentence(10),
            'es_urgencia' => fake()->boolean(20),
        ];
    }

    public function pendiente(): static
    {
        return $this->state(fn (array $attributes) => [
            'estado' => \App\Enums\EstadoSolicitudMedicamento::PENDIENTE,
            'aprobado_por' => null,
            'fecha_aprobacion' => null,
            'despachado_por' => null,
            'fecha_despacho' => null,
        ]);
    }

    public function aprobada(): static
    {
        return $this->state(function (array $attributes) {
            $fechaAprobacion = fake()->dateTimeBetween($attributes['fecha_solicitud'], 'now');
            return [
                'estado' => \App\Enums\EstadoSolicitudMedicamento::APROBADA,
                'aprobado_por' => \App\Models\User::factory(),
                'fecha_aprobacion' => $fechaAprobacion,
            ];
        });
    }

    public function despachada(): static
    {
        return $this->state(function (array $attributes) {
            $fechaAprobacion = fake()->dateTimeBetween($attributes['fecha_solicitud'], 'now');
            $fechaDespacho = fake()->dateTimeBetween($fechaAprobacion, 'now');

            return [
                'estado' => \App\Enums\EstadoSolicitudMedicamento::DESPACHADA,
                'aprobado_por' => \App\Models\User::factory(),
                'fecha_aprobacion' => $fechaAprobacion,
                'despachado_por' => \App\Models\User::factory(),
                'fecha_despacho' => $fechaDespacho,
            ];
        });
    }
}
