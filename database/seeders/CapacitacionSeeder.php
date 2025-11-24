<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CapacitacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener usuarios y áreas existentes
        $coordinador = \App\Models\User::where('role', 'coordinador')->first();
        $areas = \App\Models\Area::all();
        $enfermeros = \App\Models\Enfermero::all();

        if (!$coordinador || $areas->isEmpty() || $enfermeros->isEmpty()) {
            $this->command->warn('Se requieren usuarios, áreas y enfermeros existentes para generar datos de capacitación.');
            return;
        }

        // Crear 10 actividades de capacitación con diferentes estados
        $actividades = collect();

        // 3 actividades con inscripciones abiertas
        for ($i = 0; $i < 3; $i++) {
            $actividades->push(\App\Models\ActividadCapacitacion::create([
                'titulo' => fake()->randomElement([
                    'Actualización en Cuidados Intensivos',
                    'Manejo Avanzado de Vías Aéreas',
                    'Taller de Venopunción y Cateterización',
                ]),
                'descripcion' => 'Capacitación enfocada en actualizar conocimientos y habilidades del personal de enfermería en áreas críticas del cuidado hospitalario.',
                'tipo' => \App\Enums\TipoActividad::CURSO,
                'estado' => \App\Enums\EstadoActividad::INSCRIPCIONES_ABIERTAS,
                'modalidad' => 'presencial',
                'ubicacion' => 'Auditorio Principal',
                'duracion_horas' => 20,
                'cupo_minimo' => 10,
                'cupo_maximo' => 30,
                'fecha_inicio' => now()->addDays(15 + $i * 10),
                'fecha_fin' => now()->addDays(20 + $i * 10),
                'hora_inicio' => '08:00:00',
                'hora_fin' => '16:00:00',
                'fecha_limite_inscripcion' => now()->addDays(10 + $i * 10),
                'porcentaje_asistencia_minimo' => 80.00,
                'calificacion_minima_aprobacion' => 75.00,
                'otorga_certificado' => true,
                'instructor_nombre' => 'Dra. María Sánchez',
                'instructor_credenciales' => 'Especialista en Cuidados Intensivos',
                'objetivos' => 'Actualizar conocimientos y desarrollar habilidades prácticas en el manejo de pacientes críticos.',
                'area_id' => $areas->random()->id,
                'creado_por' => $coordinador->id,
                'aprobado_por' => $coordinador->id,
                'aprobado_at' => now(),
            ]));
        }

        // 2 actividades en curso
        for ($i = 0; $i < 2; $i++) {
            $actividades->push(\App\Models\ActividadCapacitacion::create([
                'titulo' => fake()->randomElement([
                    'Curso de RCP Avanzado',
                    'Seminario de Cuidados Paliativos',
                ]),
                'descripcion' => 'Programa de capacitación avanzada para el desarrollo profesional continuo del personal de enfermería.',
                'tipo' => \App\Enums\TipoActividad::TALLER,
                'estado' => \App\Enums\EstadoActividad::EN_CURSO,
                'modalidad' => 'hibrida',
                'ubicacion' => 'Sala de Conferencias',
                'url_virtual' => 'https://zoom.us/j/123456789',
                'duracion_horas' => 16,
                'cupo_minimo' => 8,
                'cupo_maximo' => 25,
                'fecha_inicio' => now()->subDays(5),
                'fecha_fin' => now()->addDays(10),
                'hora_inicio' => '09:00:00',
                'hora_fin' => '17:00:00',
                'porcentaje_asistencia_minimo' => 85.00,
                'calificacion_minima_aprobacion' => 80.00,
                'otorga_certificado' => true,
                'instructor_nombre' => 'Dr. Carlos Rodríguez',
                'instructor_credenciales' => 'Certificación Internacional en RCP',
                'objetivos' => 'Desarrollar competencias avanzadas en técnicas de resucitación cardiopulmonar.',
                'area_id' => $areas->random()->id,
                'creado_por' => $coordinador->id,
                'aprobado_por' => $coordinador->id,
                'aprobado_at' => now()->subDays(20),
            ]));
        }

        // 2 actividades completadas
        for ($i = 0; $i < 2; $i++) {
            $actividades->push(\App\Models\ActividadCapacitacion::create([
                'titulo' => fake()->randomElement([
                    'Capacitación en Manejo del Dolor',
                    'Actualización en Farmacología',
                ]),
                'descripcion' => 'Capacitación completada exitosamente con alta participación del personal de enfermería.',
                'tipo' => \App\Enums\TipoActividad::SEMINARIO,
                'estado' => \App\Enums\EstadoActividad::COMPLETADA,
                'modalidad' => 'presencial',
                'ubicacion' => 'Aula 301',
                'duracion_horas' => 12,
                'cupo_minimo' => 10,
                'cupo_maximo' => 20,
                'fecha_inicio' => now()->subDays(30 + $i * 15),
                'fecha_fin' => now()->subDays(25 + $i * 15),
                'hora_inicio' => '08:00:00',
                'hora_fin' => '14:00:00',
                'porcentaje_asistencia_minimo' => 80.00,
                'calificacion_minima_aprobacion' => 75.00,
                'otorga_certificado' => true,
                'instructor_nombre' => 'Lic. Ana López',
                'instructor_credenciales' => 'Maestría en Enfermería',
                'area_id' => $areas->random()->id,
                'creado_por' => $coordinador->id,
                'aprobado_por' => $coordinador->id,
                'aprobado_at' => now()->subDays(40 + $i * 15),
            ]));
        }

        // 1 actividad planificada
        $actividades->push(\App\Models\ActividadCapacitacion::create([
            'titulo' => 'Taller de Heridas y Curaciones',
            'descripcion' => 'Taller práctico para el manejo avanzado de heridas complejas y curaciones especializadas.',
            'tipo' => \App\Enums\TipoActividad::TALLER,
            'estado' => \App\Enums\EstadoActividad::PLANIFICADA,
            'modalidad' => 'presencial',
            'ubicacion' => 'Laboratorio de Simulación',
            'duracion_horas' => 8,
            'cupo_minimo' => 5,
            'cupo_maximo' => 15,
            'fecha_inicio' => now()->addDays(60),
            'fecha_fin' => now()->addDays(62),
            'hora_inicio' => '09:00:00',
            'hora_fin' => '17:00:00',
            'fecha_limite_inscripcion' => now()->addDays(50),
            'porcentaje_asistencia_minimo' => 90.00,
            'otorga_certificado' => true,
            'instructor_nombre' => 'Enf. Pedro Martínez',
            'instructor_credenciales' => 'Especialista en Heridas Complejas',
            'area_id' => $areas->random()->id,
            'creado_por' => $coordinador->id,
        ]));

        // Crear sesiones para las actividades en curso y completadas
        foreach ($actividades->where('estado', \App\Enums\EstadoActividad::EN_CURSO) as $actividad) {
            for ($sesion = 1; $sesion <= 4; $sesion++) {
                \App\Models\SesionCapacitacion::create([
                    'actividad_id' => $actividad->id,
                    'numero_sesion' => $sesion,
                    'titulo' => "Sesión {$sesion}: Módulo {$sesion}",
                    'descripcion' => "Contenido del módulo {$sesion} de la capacitación.",
                    'fecha' => $actividad->fecha_inicio->addDays($sesion - 1),
                    'hora_inicio' => $actividad->hora_inicio,
                    'hora_fin' => $actividad->hora_fin,
                    'duracion_minutos' => 480,
                    'ubicacion' => $actividad->ubicacion,
                    'url_virtual' => $actividad->url_virtual,
                    'instructor_nombre' => $actividad->instructor_nombre,
                    'asistencia_registrada' => $sesion <= 2,
                    'registrada_por' => $coordinador->id,
                    'registrada_at' => $sesion <= 2 ? now() : null,
                ]);
            }
        }

        // Crear inscripciones para actividades con inscripciones abiertas
        foreach ($actividades->where('estado', \App\Enums\EstadoActividad::INSCRIPCIONES_ABIERTAS) as $actividad) {
            $enfermerosMuestra = $enfermeros->random(min(15, $enfermeros->count()));

            foreach ($enfermerosMuestra as $index => $enfermero) {
                \App\Models\InscripcionCapacitacion::create([
                    'actividad_id' => $actividad->id,
                    'enfermero_id' => $enfermero->id,
                    'tipo' => fake()->randomElement([\App\Enums\TipoInscripcion::VOLUNTARIA, \App\Enums\TipoInscripcion::ASIGNADA]),
                    'estado' => $index < 10 ? \App\Enums\EstadoInscripcion::APROBADA : \App\Enums\EstadoInscripcion::PENDIENTE,
                    'motivacion' => 'Interesado en actualizar conocimientos en el área.',
                    'expectativas' => 'Desarrollar nuevas habilidades aplicables a mi práctica diaria.',
                    'prioridad' => $index < 5 ? 1 : 0,
                    'fecha_inscripcion' => now()->subDays(rand(1, 10)),
                    'inscrito_por' => $enfermero->user_id,
                    'aprobado_por' => $index < 10 ? $coordinador->id : null,
                    'aprobado_at' => $index < 10 ? now()->subDays(rand(1, 5)) : null,
                ]);
            }
        }

        // Crear inscripciones para actividades en curso
        foreach ($actividades->where('estado', \App\Enums\EstadoActividad::EN_CURSO) as $actividad) {
            $enfermerosMuestra = $enfermeros->random(min(12, $enfermeros->count()));

            foreach ($enfermerosMuestra as $enfermero) {
                $inscripcion = \App\Models\InscripcionCapacitacion::create([
                    'actividad_id' => $actividad->id,
                    'enfermero_id' => $enfermero->id,
                    'tipo' => \App\Enums\TipoInscripcion::VOLUNTARIA,
                    'estado' => \App\Enums\EstadoInscripcion::APROBADA,
                    'fecha_inscripcion' => now()->subDays(rand(15, 25)),
                    'inscrito_por' => $enfermero->user_id,
                    'aprobado_por' => $coordinador->id,
                    'aprobado_at' => now()->subDays(rand(10, 20)),
                ]);

                // Crear registros de asistencia para las sesiones que ya ocurrieron
                $sesiones = $actividad->sesiones()->where('asistencia_registrada', true)->get();
                foreach ($sesiones as $sesion) {
                    \App\Models\AsistenciaCapacitacion::create([
                        'sesion_id' => $sesion->id,
                        'inscripcion_id' => $inscripcion->id,
                        'presente' => fake()->boolean(90),
                        'hora_entrada' => $sesion->hora_inicio,
                        'hora_salida' => $sesion->hora_fin,
                        'minutos_asistidos' => $sesion->duracion_minutos,
                        'registrado_por' => $coordinador->id,
                        'registrado_at' => now(),
                    ]);
                }
            }
        }

        $this->command->info('✓ Datos de capacitación generados exitosamente');
        $this->command->info("  - {$actividades->count()} actividades creadas");
        $this->command->info('  - Inscripciones y asistencias generadas');
    }
}
