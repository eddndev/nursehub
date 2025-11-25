<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InventarioMedicamento>
 */
class InventarioMedicamentoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $cantidadInicial = fake()->numberBetween(50, 500);
        $cantidadActual = fake()->numberBetween(10, $cantidadInicial);

        return [
            'medicamento_id' => \App\Models\Medicamento::factory(),
            'area_id' => null, // Almacén general por defecto
            'lote' => 'LOT-' . strtoupper(fake()->bothify('??####')),
            'fecha_caducidad' => fake()->dateTimeBetween('+1 month', '+2 years'),
            'cantidad_actual' => $cantidadActual,
            'cantidad_inicial' => $cantidadInicial,
            'stock_minimo' => 10,
            'stock_maximo' => 1000,
            'costo_unitario' => fake()->randomFloat(2, 1, 100),
            'estado' => fake()->randomElement([
                \App\Enums\EstadoInventarioMedicamento::DISPONIBLE,
                \App\Enums\EstadoInventarioMedicamento::DISPONIBLE,
                \App\Enums\EstadoInventarioMedicamento::DISPONIBLE, // 75% disponible
                \App\Enums\EstadoInventarioMedicamento::CUARENTENA,
            ]),
            'ubicacion_fisica' => fake()->randomElement(['A-1-3', 'B-2-5', 'C-3-7', 'D-1-2', 'E-4-6']),
        ];
    }

    public function conArea(int $areaId): static
    {
        return $this->state(fn (array $attributes) => [
            'area_id' => $areaId,
        ]);
    }

    public function caducado(): static
    {
        return $this->state(fn (array $attributes) => [
            'fecha_caducidad' => fake()->dateTimeBetween('-6 months', '-1 day'),
            'estado' => \App\Enums\EstadoInventarioMedicamento::CADUCADO,
        ]);
    }

    public function agotado(): static
    {
        return $this->state(fn (array $attributes) => [
            'cantidad_actual' => 0,
            'estado' => \App\Enums\EstadoInventarioMedicamento::AGOTADO,
        ]);
    }
}
