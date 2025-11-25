<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MovimientoInventario>
 */
class MovimientoInventarioFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $cantidadAnterior = fake()->numberBetween(10, 500);
        $tipo = fake()->randomElement(\App\Enums\TipoMovimientoInventario::cases());
        $cantidad = fake()->numberBetween(5, 100);
        $cantidadNueva = $tipo->esPositivo() ? $cantidadAnterior + $cantidad : $cantidadAnterior - $cantidad;

        return [
            'inventario_id' => \App\Models\InventarioMedicamento::factory(),
            'tipo_movimiento' => $tipo,
            'cantidad' => $cantidad,
            'cantidad_anterior' => $cantidadAnterior,
            'cantidad_nueva' => max(0, $cantidadNueva),
            'motivo' => fake()->optional()->sentence(8),
            'usuario_id' => \App\Models\User::factory(),
            'fecha_movimiento' => fake()->dateTimeBetween('-30 days', 'now'),
            'referencia' => fake()->optional()->numerify('REF-####'),
        ];
    }
}
