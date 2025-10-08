<?php

namespace Database\Seeders;

use App\Models\Area;
use App\Models\Piso;
use Illuminate\Database\Seeder;

class PisoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener IDs de las áreas existentes
        $urgencias = Area::where('codigo', 'URG')->first();
        $uci = Area::where('codigo', 'UCI')->first();
        $cirugia = Area::where('codigo', 'CIR')->first();
        $pediatria = Area::where('codigo', 'PED')->first();
        $oncologia = Area::where('codigo', 'ONC')->first();
        $gino = Area::where('codigo', 'GINO')->first();
        $medicinaInterna = Area::where('codigo', 'MI')->first();
        $hospitalizacion = Area::where('codigo', 'HOSP')->first();

        $pisos = [
            // Urgencias - Planta Baja
            [
                'area_id' => $urgencias->id,
                'nombre' => 'Urgencias - Planta Baja',
                'numero_piso' => 0,
                'especialidad' => 'Traumatología y Emergencias',
            ],

            // UCI - Piso 5
            [
                'area_id' => $uci->id,
                'nombre' => 'UCI - Piso 5',
                'numero_piso' => 5,
                'especialidad' => 'Cuidados Intensivos',
            ],

            // Cirugía - Pisos 3 y 4
            [
                'area_id' => $cirugia->id,
                'nombre' => 'Quirófanos - Piso 3',
                'numero_piso' => 3,
                'especialidad' => 'Cirugía General',
            ],
            [
                'area_id' => $cirugia->id,
                'nombre' => 'Recuperación Post-Quirúrgica - Piso 4',
                'numero_piso' => 4,
                'especialidad' => 'Post-operatorio',
            ],

            // Pediatría - Piso 6
            [
                'area_id' => $pediatria->id,
                'nombre' => 'Pediatría General - Piso 6',
                'numero_piso' => 6,
                'especialidad' => 'Pediatría',
            ],
            [
                'area_id' => $pediatria->id,
                'nombre' => 'Neonatología - Piso 6',
                'numero_piso' => 6,
                'especialidad' => 'Neonatología',
            ],

            // Oncología - Piso 7
            [
                'area_id' => $oncologia->id,
                'nombre' => 'Oncología - Piso 7',
                'numero_piso' => 7,
                'especialidad' => 'Oncología Médica',
            ],

            // Ginecología y Obstetricia - Piso 2
            [
                'area_id' => $gino->id,
                'nombre' => 'Ginecología - Piso 2',
                'numero_piso' => 2,
                'especialidad' => 'Ginecología',
            ],
            [
                'area_id' => $gino->id,
                'nombre' => 'Maternidad - Piso 2',
                'numero_piso' => 2,
                'especialidad' => 'Obstetricia',
            ],

            // Medicina Interna - Piso 8
            [
                'area_id' => $medicinaInterna->id,
                'nombre' => 'Medicina Interna - Piso 8',
                'numero_piso' => 8,
                'especialidad' => 'Medicina Interna',
            ],

            // Hospitalización General - Pisos 9 y 10
            [
                'area_id' => $hospitalizacion->id,
                'nombre' => 'Hospitalización General - Piso 9',
                'numero_piso' => 9,
                'especialidad' => 'Hospitalización',
            ],
            [
                'area_id' => $hospitalizacion->id,
                'nombre' => 'Hospitalización General - Piso 10',
                'numero_piso' => 10,
                'especialidad' => 'Hospitalización',
            ],
        ];

        foreach ($pisos as $piso) {
            Piso::create($piso);
        }
    }
}
