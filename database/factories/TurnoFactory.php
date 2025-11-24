<?php

namespace Database\Factories;

use App\Enums\EstadoTurno;
use App\Enums\TipoTurno;
use App\Models\Area;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Turno>
 */
class TurnoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $tipo = fake()->randomElement(TipoTurno::cases());

        return [
            'area_id' => Area::factory(),
            'fecha' => fake()->dateTimeBetween('-1 week', '+1 week'),
            'tipo' => $tipo,
            'hora_inicio' => $tipo->getHoraInicio(),
            'hora_fin' => $tipo->getHoraFin(),
            'jefe_turno_id' => User::factory(),
            'novedades_relevo' => fake()->optional()->sentence(),
            'estado' => fake()->randomElement(EstadoTurno::cases()),
            'cerrado_at' => null,
            'cerrado_por' => null,
        ];
    }

    /**
     * Indicate that the shift is active.
     */
    public function activo(): static
    {
        return $this->state(fn (array $attributes) => [
            'estado' => EstadoTurno::ACTIVO,
            'cerrado_at' => null,
            'cerrado_por' => null,
        ]);
    }

    /**
     * Indicate that the shift is closed.
     */
    public function cerrado(): static
    {
        return $this->state(fn (array $attributes) => [
            'estado' => EstadoTurno::CERRADO,
            'cerrado_at' => now(),
            'cerrado_por' => User::factory(),
        ]);
    }

    /**
     * Indicate that the shift is for today.
     */
    public function hoy(): static
    {
        return $this->state(fn (array $attributes) => [
            'fecha' => today(),
        ]);
    }

    /**
     * Indicate that the shift is matutino.
     */
    public function matutino(): static
    {
        return $this->state(fn (array $attributes) => [
            'tipo' => TipoTurno::MATUTINO,
            'hora_inicio' => '07:00',
            'hora_fin' => '15:00',
        ]);
    }

    /**
     * Indicate that the shift is vespertino.
     */
    public function vespertino(): static
    {
        return $this->state(fn (array $attributes) => [
            'tipo' => TipoTurno::VESPERTINO,
            'hora_inicio' => '15:00',
            'hora_fin' => '23:00',
        ]);
    }

    /**
     * Indicate that the shift is nocturno.
     */
    public function nocturno(): static
    {
        return $this->state(fn (array $attributes) => [
            'tipo' => TipoTurno::NOCTURNO,
            'hora_inicio' => '23:00',
            'hora_fin' => '07:00',
        ]);
    }
}
