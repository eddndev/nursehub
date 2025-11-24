<?php

namespace Database\Seeders;

use App\Models\DiagnosticoEnfermeria;
use Illuminate\Database\Seeder;

class DiagnosticoEnfermeriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Diagnósticos NANDA-I más comunes en urgencias y hospitalización.
     */
    public function run(): void
    {
        $diagnosticos = [
            // Dominio 1: Promoción de la Salud
            [
                'codigo' => '00078',
                'descripcion' => 'Gestión ineficaz de la salud',
                'dominio' => 'Promoción de la Salud',
                'clase' => 'Gestión de la Salud',
            ],

            // Dominio 2: Nutrición
            [
                'codigo' => '00002',
                'descripcion' => 'Desequilibrio nutricional: inferior a las necesidades corporales',
                'dominio' => 'Nutrición',
                'clase' => 'Ingestión',
            ],
            [
                'codigo' => '00027',
                'descripcion' => 'Déficit de volumen de líquidos',
                'dominio' => 'Nutrición',
                'clase' => 'Hidratación',
            ],
            [
                'codigo' => '00026',
                'descripcion' => 'Exceso de volumen de líquidos',
                'dominio' => 'Nutrición',
                'clase' => 'Hidratación',
            ],

            // Dominio 3: Eliminación e Intercambio
            [
                'codigo' => '00011',
                'descripcion' => 'Estreñimiento',
                'dominio' => 'Eliminación e Intercambio',
                'clase' => 'Función Gastrointestinal',
            ],
            [
                'codigo' => '00016',
                'descripcion' => 'Deterioro de la eliminación urinaria',
                'dominio' => 'Eliminación e Intercambio',
                'clase' => 'Función Urinaria',
            ],

            // Dominio 4: Actividad/Reposo
            [
                'codigo' => '00085',
                'descripcion' => 'Deterioro de la movilidad física',
                'dominio' => 'Actividad/Reposo',
                'clase' => 'Actividad/Ejercicio',
            ],
            [
                'codigo' => '00092',
                'descripcion' => 'Intolerancia a la actividad',
                'dominio' => 'Actividad/Reposo',
                'clase' => 'Actividad/Ejercicio',
            ],
            [
                'codigo' => '00095',
                'descripcion' => 'Insomnio',
                'dominio' => 'Actividad/Reposo',
                'clase' => 'Sueño/Reposo',
            ],
            [
                'codigo' => '00032',
                'descripcion' => 'Patrón respiratorio ineficaz',
                'dominio' => 'Actividad/Reposo',
                'clase' => 'Respuestas Cardiovasculares/Pulmonares',
            ],
            [
                'codigo' => '00029',
                'descripcion' => 'Disminución del gasto cardíaco',
                'dominio' => 'Actividad/Reposo',
                'clase' => 'Respuestas Cardiovasculares/Pulmonares',
            ],

            // Dominio 11: Seguridad/Protección
            [
                'codigo' => '00004',
                'descripcion' => 'Riesgo de infección',
                'dominio' => 'Seguridad/Protección',
                'clase' => 'Infección',
            ],
            [
                'codigo' => '00046',
                'descripcion' => 'Deterioro de la integridad cutánea',
                'dominio' => 'Seguridad/Protección',
                'clase' => 'Lesión Física',
            ],
            [
                'codigo' => '00047',
                'descripcion' => 'Riesgo de deterioro de la integridad cutánea',
                'dominio' => 'Seguridad/Protección',
                'clase' => 'Lesión Física',
            ],
            [
                'codigo' => '00155',
                'descripcion' => 'Riesgo de caídas',
                'dominio' => 'Seguridad/Protección',
                'clase' => 'Lesión Física',
            ],

            // Dominio 12: Confort
            [
                'codigo' => '00132',
                'descripcion' => 'Dolor agudo',
                'dominio' => 'Confort',
                'clase' => 'Confort Físico',
            ],
            [
                'codigo' => '00133',
                'descripcion' => 'Dolor crónico',
                'dominio' => 'Confort',
                'clase' => 'Confort Físico',
            ],

            // Dominio 9: Afrontamiento/Tolerancia al Estrés
            [
                'codigo' => '00146',
                'descripcion' => 'Ansiedad',
                'dominio' => 'Afrontamiento/Tolerancia al Estrés',
                'clase' => 'Respuestas de Afrontamiento',
            ],
            [
                'codigo' => '00148',
                'descripcion' => 'Temor',
                'dominio' => 'Afrontamiento/Tolerancia al Estrés',
                'clase' => 'Respuestas de Afrontamiento',
            ],

            // Dominio 5: Percepción/Cognición
            [
                'codigo' => '00128',
                'descripcion' => 'Confusión aguda',
                'dominio' => 'Percepción/Cognición',
                'clase' => 'Cognición',
            ],
        ];

        foreach ($diagnosticos as $diagnostico) {
            DiagnosticoEnfermeria::create($diagnostico);
        }

        $this->command->info('✅ Se han creado ' . count($diagnosticos) . ' diagnósticos de enfermería NANDA-I');
    }
}
