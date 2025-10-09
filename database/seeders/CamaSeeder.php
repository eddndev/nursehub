<?php

namespace Database\Seeders;

use App\Enums\CamaEstado;
use App\Models\Cama;
use App\Models\Cuarto;
use Illuminate\Database\Seeder;

class CamaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener todos los cuartos
        $cuartos = Cuarto::all();

        foreach ($cuartos as $cuarto) {
            // Determinar cantidad de camas según el tipo de cuarto
            $cantidadCamas = match ($cuarto->tipo) {
                'individual' => 1,
                'doble' => 2,
                'multiple' => fake()->numberBetween(4, 6), // 4-6 camas
            };

            // Crear camas para el cuarto
            for ($i = 1; $i <= $cantidadCamas; $i++) {
                // Determinar estado con distribución realista
                // 60% libres, 30% ocupadas, 7% en limpieza, 3% en mantenimiento
                $random = fake()->numberBetween(1, 100);
                $estado = match (true) {
                    $random <= 60 => CamaEstado::LIBRE,
                    $random <= 90 => CamaEstado::OCUPADA,
                    $random <= 97 => CamaEstado::EN_LIMPIEZA,
                    default => CamaEstado::EN_MANTENIMIENTO,
                };

                Cama::create([
                    'cuarto_id' => $cuarto->id,
                    'numero_cama' => $cuarto->numero_cuarto . '-' . $i,
                    'estado' => $estado,
                ]);
            }
        }
    }
}
