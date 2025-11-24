<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\AsignacionPaciente;
use App\Models\Enfermero;
use App\Models\Paciente;
use App\Models\Turno;
use App\Models\User;
use Illuminate\Database\Seeder;

class AsignacionPacienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener turnos activos de hoy
        $turnosActivos = Turno::activos()->deHoy()->get();

        if ($turnosActivos->isEmpty()) {
            $this->command->warn('⚠️  No hay turnos activos para crear asignaciones. Ejecuta TurnoSeeder primero.');
            return;
        }

        // Obtener enfermeros disponibles (primero intentar de la tabla enfermeros)
        $enfermeros = Enfermero::with('user')->limit(5)->get();

        // Si no hay registros en enfermeros, crear algunos de prueba con users existentes
        if ($enfermeros->isEmpty()) {
            $this->command->info('ℹ️  No hay enfermeros, creando registros de prueba...');

            $users = User::whereIn('role', [UserRole::ENFERMERO, UserRole::JEFE_PISO])->get();

            if ($users->isEmpty()) {
                $this->command->warn('⚠️  No hay usuarios disponibles para crear asignaciones.');
                return;
            }

            // Crear registros de Enfermero para los users
            foreach ($users as $user) {
                $enfermeros->push(Enfermero::create([
                    'user_id' => $user->id,
                    'cedula_profesional' => 'ENF' . str_pad($user->id, 6, '0', STR_PAD_LEFT),
                    'tipo_asignacion' => \App\Enums\TipoAsignacion::ROTATIVO,
                    'area_fija_id' => null,
                    'anos_experiencia' => rand(1, 10),
                ]));
            }

            $this->command->info('✅ Se crearon ' . $enfermeros->count() . ' registros de enfermeros');
        }

        // Obtener pacientes activos
        $pacientes = Paciente::activos()->limit(10)->get();

        if ($pacientes->isEmpty()) {
            $this->command->warn('⚠️  No hay pacientes activos para crear asignaciones.');
            return;
        }

        $asignaciones = [];
        $enfermeroIndex = 0;

        // Crear asignaciones para cada turno
        foreach ($turnosActivos as $turno) {
            // Asignar 2-3 pacientes por turno
            $numPacientes = min(3, $pacientes->count());

            for ($i = 0; $i < $numPacientes; $i++) {
                if (!isset($pacientes[$i])) {
                    break;
                }

                $enfermero = $enfermeros[$enfermeroIndex % $enfermeros->count()];

                $asignaciones[] = [
                    'turno_id' => $turno->id,
                    'enfermero_id' => $enfermero->id,
                    'paciente_id' => $pacientes[$i]->id,
                    'fecha_hora_asignacion' => now()->subHours(rand(1, 4)),
                    'fecha_hora_liberacion' => null,
                    'asignado_por' => $turno->jefe_turno_id,
                    'liberado_por' => null,
                    'motivo_liberacion' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                $enfermeroIndex++;
            }
        }

        // Crear algunas asignaciones liberadas como historial
        if ($pacientes->count() > 5 && $enfermeros->count() > 1) {
            $turnoAyer = Turno::where('fecha', today()->subDay())->first();

            if ($turnoAyer) {
                $asignaciones[] = [
                    'turno_id' => $turnoAyer->id,
                    'enfermero_id' => $enfermeros[0]->id,
                    'paciente_id' => $pacientes[5]->id,
                    'fecha_hora_asignacion' => today()->subDay()->setTime(8, 0),
                    'fecha_hora_liberacion' => today()->subDay()->setTime(14, 30),
                    'asignado_por' => $turnoAyer->jefe_turno_id,
                    'liberado_por' => $turnoAyer->jefe_turno_id,
                    'motivo_liberacion' => 'Fin de turno',
                    'created_at' => today()->subDay(),
                    'updated_at' => today()->subDay()->setTime(14, 30),
                ];

                $asignaciones[] = [
                    'turno_id' => $turnoAyer->id,
                    'enfermero_id' => $enfermeros[1]->id,
                    'paciente_id' => $pacientes[6]->id,
                    'fecha_hora_asignacion' => today()->subDay()->setTime(8, 15),
                    'fecha_hora_liberacion' => today()->subDay()->setTime(12, 0),
                    'asignado_por' => $turnoAyer->jefe_turno_id,
                    'liberado_por' => $turnoAyer->jefe_turno_id,
                    'motivo_liberacion' => 'Alta del paciente',
                    'created_at' => today()->subDay(),
                    'updated_at' => today()->subDay()->setTime(12, 0),
                ];
            }
        }

        foreach ($asignaciones as $asignacion) {
            AsignacionPaciente::create($asignacion);
        }

        $this->command->info('✅ Se han creado ' . count($asignaciones) . ' asignaciones de pacientes');
    }
}
