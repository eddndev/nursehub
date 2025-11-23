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
                // Todas las camas inician LIBRES para poder probar admisiones
                // En producción se pueden cambiar a estados reales
                Cama::create([
                    'cuarto_id' => $cuarto->id,
                    'numero_cama' => $cuarto->numero_cuarto . '-' . $i,
                    'estado' => CamaEstado::LIBRE,
                ]);
            }
        }
    }
}
