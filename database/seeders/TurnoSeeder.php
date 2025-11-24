<?php

namespace Database\Seeders;

use App\Enums\EstadoTurno;
use App\Enums\TipoTurno;
use App\Enums\UserRole;
use App\Models\Area;
use App\Models\Turno;
use App\Models\User;
use Illuminate\Database\Seeder;

class TurnoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener áreas y usuarios para jefes de turno
        $urgencias = Area::where('nombre', 'Urgencias')->first();
        $hospitalizacion = Area::where('nombre', 'Hospitalización General')->first();

        // Obtener enfermeros o jefes de piso para usar como jefes de turno
        $jefes = User::whereIn('role', [UserRole::ENFERMERO, UserRole::JEFE_PISO])->get();

        if (!$urgencias || !$hospitalizacion) {
            $this->command->warn('⚠️  No hay áreas disponibles para crear turnos de prueba');
            return;
        }

        if ($jefes->count() < 1) {
            $this->command->warn('⚠️  No hay usuarios disponibles para asignar como jefes de turno');
            return;
        }

        // Si hay menos de 3 jefes, reutilizar los disponibles
        if ($jefes->count() < 3) {
            $this->command->info('ℹ️  Reutilizando jefes de turno disponibles (' . $jefes->count() . ' encontrados)');
        }

        $turnos = [];
        $fecha = today();

        // Turno matutino - Urgencias
        $turnos[] = [
            'area_id' => $urgencias->id,
            'fecha' => $fecha,
            'tipo' => TipoTurno::MATUTINO,
            'hora_inicio' => '07:00',
            'hora_fin' => '15:00',
            'jefe_turno_id' => $jefes[0 % $jefes->count()]->id,
            'novedades_relevo' => 'Inicio de turno matutino. 3 pacientes en observación.',
            'estado' => EstadoTurno::ACTIVO,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // Turno vespertino - Urgencias
        $turnos[] = [
            'area_id' => $urgencias->id,
            'fecha' => $fecha,
            'tipo' => TipoTurno::VESPERTINO,
            'hora_inicio' => '15:00',
            'hora_fin' => '23:00',
            'jefe_turno_id' => $jefes[1 % $jefes->count()]->id,
            'novedades_relevo' => 'Turno vespertino. Se espera alta carga por consulta externa.',
            'estado' => EstadoTurno::ACTIVO,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // Turno nocturno - Urgencias
        $turnos[] = [
            'area_id' => $urgencias->id,
            'fecha' => $fecha,
            'tipo' => TipoTurno::NOCTURNO,
            'hora_inicio' => '23:00',
            'hora_fin' => '07:00',
            'jefe_turno_id' => $jefes[2 % $jefes->count()]->id,
            'novedades_relevo' => 'Turno nocturno. Guardia completa.',
            'estado' => EstadoTurno::ACTIVO,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // Turno matutino - Hospitalización
        $turnos[] = [
            'area_id' => $hospitalizacion->id,
            'fecha' => $fecha,
            'tipo' => TipoTurno::MATUTINO,
            'hora_inicio' => '07:00',
            'hora_fin' => '15:00',
            'jefe_turno_id' => $jefes[0 % $jefes->count()]->id,
            'novedades_relevo' => 'Turno matutino hospitalización. 15 pacientes hospitalizados.',
            'estado' => EstadoTurno::ACTIVO,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // Turno vespertino - Hospitalización
        $turnos[] = [
            'area_id' => $hospitalizacion->id,
            'fecha' => $fecha,
            'tipo' => TipoTurno::VESPERTINO,
            'hora_inicio' => '15:00',
            'hora_fin' => '23:00',
            'jefe_turno_id' => $jefes[1 % $jefes->count()]->id,
            'novedades_relevo' => 'Turno vespertino hospitalización. Visita de familiares de 17:00 a 19:00.',
            'estado' => EstadoTurno::ACTIVO,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // Turno cerrado de ayer como ejemplo
        $turnos[] = [
            'area_id' => $urgencias->id,
            'fecha' => $fecha->copy()->subDay(),
            'tipo' => TipoTurno::MATUTINO,
            'hora_inicio' => '07:00',
            'hora_fin' => '15:00',
            'jefe_turno_id' => $jefes[2 % $jefes->count()]->id,
            'novedades_relevo' => 'Turno anterior cerrado. Se atendieron 12 pacientes.',
            'estado' => EstadoTurno::CERRADO,
            'cerrado_at' => $fecha->copy()->subDay()->setTime(15, 0),
            'cerrado_por' => $jefes[2 % $jefes->count()]->id,
            'created_at' => $fecha->copy()->subDay(),
            'updated_at' => $fecha->copy()->subDay()->setTime(15, 0),
        ];

        foreach ($turnos as $turno) {
            Turno::create($turno);
        }

        $this->command->info('✅ Se han creado ' . count($turnos) . ' turnos de prueba');
    }
}
