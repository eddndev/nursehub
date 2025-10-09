<?php

namespace Database\Seeders;

use App\Models\Cuarto;
use App\Models\Piso;
use Illuminate\Database\Seeder;

class CuartoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener todos los pisos
        $pisos = Piso::all();

        foreach ($pisos as $piso) {
            // Determinar cantidad de cuartos según el tipo de piso
            $cantidadCuartos = match (true) {
                str_contains($piso->nombre, 'UCI') => 8,  // UCI tiene 8 cuartos individuales
                str_contains($piso->nombre, 'Urgencias') => 12, // Urgencias tiene 12 cuartos
                str_contains($piso->nombre, 'Quirófanos') => 6, // 6 quirófanos
                str_contains($piso->nombre, 'Recuperación') => 10, // 10 cuartos de recuperación
                str_contains($piso->nombre, 'Pediatría') => 15, // 15 cuartos
                str_contains($piso->nombre, 'Neonatología') => 12, // 12 cuartos
                str_contains($piso->nombre, 'Oncología') => 20, // 20 cuartos
                str_contains($piso->nombre, 'Ginecología') => 18, // 18 cuartos
                str_contains($piso->nombre, 'Maternidad') => 16, // 16 cuartos
                str_contains($piso->nombre, 'Medicina Interna') => 25, // 25 cuartos
                str_contains($piso->nombre, 'Hospitalización') => 30, // 30 cuartos por piso
                default => 20,
            };

            // Determinar distribución de tipos de cuartos
            $distribucion = match (true) {
                str_contains($piso->nombre, 'UCI') => ['individual' => 100], // UCI solo individuales
                str_contains($piso->nombre, 'Quirófanos') => ['individual' => 100], // Quirófanos individuales
                str_contains($piso->nombre, 'Recuperación') => ['individual' => 80, 'doble' => 20],
                str_contains($piso->nombre, 'Pediatría') => ['individual' => 40, 'doble' => 40, 'multiple' => 20],
                str_contains($piso->nombre, 'Neonatología') => ['individual' => 60, 'multiple' => 40],
                str_contains($piso->nombre, 'Oncología') => ['individual' => 50, 'doble' => 50],
                str_contains($piso->nombre, 'Maternidad') => ['individual' => 100], // Maternidad individuales
                default => ['individual' => 30, 'doble' => 50, 'multiple' => 20],
            };

            // Crear cuartos según distribución
            $cuartosCreados = 0;
            $numeroCuartoBase = ($piso->numero_piso * 100) + 1;

            foreach ($distribucion as $tipo => $porcentaje) {
                $cantidad = (int) ceil(($porcentaje / 100) * $cantidadCuartos);

                for ($i = 0; $i < $cantidad && $cuartosCreados < $cantidadCuartos; $i++) {
                    Cuarto::create([
                        'piso_id' => $piso->id,
                        'numero_cuarto' => (string) ($numeroCuartoBase + $cuartosCreados),
                        'tipo' => $tipo,
                    ]);
                    $cuartosCreados++;
                }
            }
        }
    }
}
