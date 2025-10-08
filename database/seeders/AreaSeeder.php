<?php

namespace Database\Seeders;

use App\Models\Area;
use Illuminate\Database\Seeder;

class AreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $areas = [
            [
                'nombre' => 'Urgencias',
                'codigo' => 'URG',
                'descripcion' => 'Área de atención de emergencias médicas y traumatológicas',
                'opera_24_7' => true,
                'ratio_enfermero_paciente' => 0.25,
                'requiere_certificacion' => true,
            ],
            [
                'nombre' => 'Unidad de Cuidados Intensivos',
                'codigo' => 'UCI',
                'descripcion' => 'Atención de pacientes críticos con monitoreo continuo',
                'opera_24_7' => true,
                'ratio_enfermero_paciente' => 0.50,
                'requiere_certificacion' => true,
            ],
            [
                'nombre' => 'Cirugía General',
                'codigo' => 'CIR',
                'descripcion' => 'Área quirúrgica para procedimientos generales',
                'opera_24_7' => true,
                'ratio_enfermero_paciente' => 0.17,
                'requiere_certificacion' => true,
            ],
            [
                'nombre' => 'Pediatría',
                'codigo' => 'PED',
                'descripcion' => 'Atención médica especializada para niños y adolescentes',
                'opera_24_7' => true,
                'ratio_enfermero_paciente' => 0.20,
                'requiere_certificacion' => true,
            ],
            [
                'nombre' => 'Oncología',
                'codigo' => 'ONC',
                'descripcion' => 'Tratamiento y cuidados para pacientes con cáncer',
                'opera_24_7' => false,
                'ratio_enfermero_paciente' => 0.20,
                'requiere_certificacion' => true,
            ],
            [
                'nombre' => 'Ginecología y Obstetricia',
                'codigo' => 'GINO',
                'descripcion' => 'Atención de partos y cuidados ginecológicos',
                'opera_24_7' => true,
                'ratio_enfermero_paciente' => 0.20,
                'requiere_certificacion' => true,
            ],
            [
                'nombre' => 'Medicina Interna',
                'codigo' => 'MI',
                'descripcion' => 'Diagnóstico y tratamiento de enfermedades internas',
                'opera_24_7' => false,
                'ratio_enfermero_paciente' => 0.15,
                'requiere_certificacion' => false,
            ],
            [
                'nombre' => 'Hospitalización General',
                'codigo' => 'HOSP',
                'descripcion' => 'Camas de hospitalización para recuperación post-operatoria',
                'opera_24_7' => true,
                'ratio_enfermero_paciente' => 0.12,
                'requiere_certificacion' => false,
            ],
        ];

        foreach ($areas as $area) {
            Area::create($area);
        }
    }
}
