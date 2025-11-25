<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Medicamento>
 */
class MedicamentoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $medicamentos = [
            [
                'nombre_comercial' => 'Paracetamol',
                'nombre_generico' => 'Acetaminofén',
                'principio_activo' => 'Paracetamol',
                'presentacion' => 'Tableta',
                'concentracion' => '500mg',
                'via_administracion' => \App\Enums\ViaAdministracionMedicamento::ORAL,
                'categoria' => \App\Enums\CategoriaMedicamento::ANALGESICO,
                'dosis_maxima_24h' => 4000,
                'unidad_dosis_maxima' => 'mg',
            ],
            [
                'nombre_comercial' => 'Amoxicilina',
                'nombre_generico' => 'Amoxicilina',
                'principio_activo' => 'Amoxicilina trihidratada',
                'presentacion' => 'Cápsula',
                'concentracion' => '500mg',
                'via_administracion' => \App\Enums\ViaAdministracionMedicamento::ORAL,
                'categoria' => \App\Enums\CategoriaMedicamento::ANTIBIOTICO,
                'dosis_maxima_24h' => 3000,
                'unidad_dosis_maxima' => 'mg',
            ],
            [
                'nombre_comercial' => 'Ibuprofeno',
                'nombre_generico' => 'Ibuprofeno',
                'principio_activo' => 'Ibuprofeno',
                'presentacion' => 'Tableta',
                'concentracion' => '400mg',
                'via_administracion' => \App\Enums\ViaAdministracionMedicamento::ORAL,
                'categoria' => \App\Enums\CategoriaMedicamento::ANTIINFLAMATORIO,
                'dosis_maxima_24h' => 2400,
                'unidad_dosis_maxima' => 'mg',
            ],
            [
                'nombre_comercial' => 'Morfina',
                'nombre_generico' => 'Sulfato de morfina',
                'principio_activo' => 'Morfina',
                'presentacion' => 'Ampolleta',
                'concentracion' => '10mg/ml',
                'via_administracion' => \App\Enums\ViaAdministracionMedicamento::INTRAVENOSA,
                'categoria' => \App\Enums\CategoriaMedicamento::ANALGESICO,
                'es_controlado' => true,
                'dosis_maxima_24h' => 100,
                'unidad_dosis_maxima' => 'mg',
            ],
        ];

        $selected = fake()->randomElement($medicamentos);

        return [
            'codigo_medicamento' => 'MED-' . strtoupper(fake()->unique()->bothify('????####')),
            'nombre_comercial' => $selected['nombre_comercial'],
            'nombre_generico' => $selected['nombre_generico'],
            'principio_activo' => $selected['principio_activo'],
            'laboratorio' => fake()->randomElement(['Pfizer', 'Bayer', 'Novartis', 'Roche', 'GSK', 'Sanofi']),
            'presentacion' => $selected['presentacion'],
            'concentracion' => $selected['concentracion'],
            'via_administracion' => $selected['via_administracion'],
            'categoria' => $selected['categoria'],
            'es_controlado' => $selected['es_controlado'] ?? false,
            'precio_unitario' => fake()->randomFloat(2, 5, 500),
            'indicaciones' => fake()->sentence(10),
            'contraindicaciones' => fake()->sentence(8),
            'efectos_adversos' => fake()->sentence(8),
            'dosis_maxima_24h' => $selected['dosis_maxima_24h'],
            'unidad_dosis_maxima' => $selected['unidad_dosis_maxima'],
            'requiere_refrigeracion' => fake()->boolean(20),
            'activo' => true,
        ];
    }

    public function controlado(): static
    {
        return $this->state(fn (array $attributes) => [
            'es_controlado' => true,
        ]);
    }

    public function inactivo(): static
    {
        return $this->state(fn (array $attributes) => [
            'activo' => false,
        ]);
    }
}
