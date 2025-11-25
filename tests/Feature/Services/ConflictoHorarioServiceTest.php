<?php

namespace Tests\Feature\Services;

use App\Enums\EstadoActividad;
use App\Enums\EstadoInscripcion;
use App\Enums\EstadoTurno;
use App\Enums\TipoTurno;
use App\Models\ActividadCapacitacion;
use App\Models\Area;
use App\Models\AsignacionPaciente;
use App\Models\Enfermero;
use App\Models\InscripcionCapacitacion;
use App\Models\Paciente;
use App\Models\SesionCapacitacion;
use App\Models\Turno;
use App\Models\User;
use App\Services\ConflictoHorarioService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ConflictoHorarioServiceTest extends TestCase
{
    use RefreshDatabase;

    protected ConflictoHorarioService $service;
    protected Enfermero $enfermero;
    protected Area $area;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new ConflictoHorarioService();

        // Crear datos de prueba
        $this->area = Area::factory()->create(['nombre' => 'Medicina Interna']);
        $user = User::factory()->create();
        $this->enfermero = Enfermero::factory()->create([
            'user_id' => $user->id,
            'area_fija_id' => $this->area->id,
        ]);
    }

    public function test_detecta_conflicto_turno_con_sesion_capacitacion(): void
    {
        // Este test requiere MySQL ya que usa relaciones anidadas
        if (config('database.default') === 'sqlite') {
            $this->markTestSkipped('Este test requiere MySQL para relaciones anidadas');
        }

        // Crear actividad con sesión
        $actividad = ActividadCapacitacion::factory()->create([
            'estado' => EstadoActividad::INSCRIPCIONES_ABIERTAS,
        ]);

        $sesion = SesionCapacitacion::factory()->create([
            'actividad_id' => $actividad->id,
            'numero_sesion' => 1,
            'fecha' => today(),
            'hora_inicio' => '09:00',
            'hora_fin' => '12:00',
        ]);

        // Inscribir enfermero en la actividad (usar el valor string directamente)
        InscripcionCapacitacion::factory()->create([
            'actividad_id' => $actividad->id,
            'enfermero_id' => $this->enfermero->id,
            'estado' => 'aprobada',
        ]);

        // Verificar conflicto con turno matutino (07:00-15:00)
        $conflictos = $this->service->verificarConflictoParaTurno(
            $this->enfermero->id,
            today(),
            '07:00',
            '15:00'
        );

        $this->assertNotEmpty($conflictos);
        $this->assertEquals('capacitacion', $conflictos[0]['tipo']);
        $this->assertStringContainsString($actividad->titulo, $conflictos[0]['mensaje']);
    }

    public function test_no_detecta_conflicto_sin_interseccion_horaria(): void
    {
        // Crear actividad con sesión en la mañana
        $actividad = ActividadCapacitacion::factory()->create([
            'estado' => EstadoActividad::INSCRIPCIONES_ABIERTAS,
        ]);

        $sesion = SesionCapacitacion::factory()->create([
            'actividad_id' => $actividad->id,
            'numero_sesion' => 1,
            'fecha' => today(),
            'hora_inicio' => '08:00',
            'hora_fin' => '10:00',
        ]);

        // Inscribir enfermero
        InscripcionCapacitacion::factory()->create([
            'actividad_id' => $actividad->id,
            'enfermero_id' => $this->enfermero->id,
            'estado' => 'aprobada',
        ]);

        // Verificar que no hay conflicto con turno vespertino (15:00-23:00)
        $conflictos = $this->service->verificarConflictoParaTurno(
            $this->enfermero->id,
            today(),
            '15:00',
            '23:00'
        );

        $this->assertEmpty($conflictos);
    }

    public function test_verifica_conflicto_para_inscripcion(): void
    {
        // Crear turno activo para hoy
        $turno = Turno::factory()->create([
            'area_id' => $this->area->id,
            'fecha' => today(),
            'tipo' => TipoTurno::MATUTINO,
            'hora_inicio' => '07:00',
            'hora_fin' => '15:00',
            'estado' => EstadoTurno::ACTIVO,
        ]);

        // Crear paciente y asignar al enfermero
        $paciente = Paciente::factory()->create();
        AsignacionPaciente::create([
            'turno_id' => $turno->id,
            'enfermero_id' => $this->enfermero->id,
            'paciente_id' => $paciente->id,
            'fecha_hora_asignacion' => now(),
            'asignado_por' => User::factory()->create()->id,
        ]);

        // Crear actividad con sesión que conflictúa
        $actividad = ActividadCapacitacion::factory()->create([
            'estado' => EstadoActividad::INSCRIPCIONES_ABIERTAS,
        ]);

        SesionCapacitacion::factory()->create([
            'actividad_id' => $actividad->id,
            'numero_sesion' => 1,
            'fecha' => today(),
            'hora_inicio' => '09:00',
            'hora_fin' => '12:00',
        ]);

        // Verificar conflictos al intentar inscribirse
        $conflictos = $this->service->verificarConflictoParaInscripcion(
            $this->enfermero->id,
            $actividad->id
        );

        $this->assertNotEmpty($conflictos);
        $this->assertEquals('turno', $conflictos[0]['tipo']);
    }

    public function test_enfermero_esta_en_capacitacion_ahora(): void
    {
        // Este test requiere MySQL ya que usa relaciones anidadas
        if (config('database.default') === 'sqlite') {
            $this->markTestSkipped('Este test requiere MySQL para relaciones anidadas');
        }

        // Crear actividad con sesión activa ahora
        $actividad = ActividadCapacitacion::factory()->create([
            'estado' => EstadoActividad::EN_CURSO,
        ]);

        $sesion = SesionCapacitacion::factory()->create([
            'actividad_id' => $actividad->id,
            'numero_sesion' => 1,
            'fecha' => today(),
            'hora_inicio' => now()->subHour()->format('H:i'),
            'hora_fin' => now()->addHour()->format('H:i'),
        ]);

        // Inscribir enfermero
        InscripcionCapacitacion::factory()->create([
            'actividad_id' => $actividad->id,
            'enfermero_id' => $this->enfermero->id,
            'estado' => 'aprobada',
        ]);

        $this->assertTrue($this->service->estaEnCapacitacion($this->enfermero->id));
    }

    public function test_enfermero_no_esta_en_capacitacion_fuera_de_horario(): void
    {
        // Crear actividad con sesión que ya pasó
        $actividad = ActividadCapacitacion::factory()->create([
            'estado' => EstadoActividad::EN_CURSO,
        ]);

        $sesion = SesionCapacitacion::factory()->create([
            'actividad_id' => $actividad->id,
            'numero_sesion' => 1,
            'fecha' => today(),
            'hora_inicio' => '06:00',
            'hora_fin' => '07:00', // Ya terminó
        ]);

        // Inscribir enfermero
        InscripcionCapacitacion::factory()->create([
            'actividad_id' => $actividad->id,
            'enfermero_id' => $this->enfermero->id,
            'estado' => 'aprobada',
        ]);

        // Si la hora actual es después de las 07:00, no debería estar en capacitación
        if (now()->format('H:i') > '07:00') {
            $this->assertFalse($this->service->estaEnCapacitacion($this->enfermero->id));
        } else {
            $this->markTestSkipped('Este test solo funciona después de las 07:00');
        }
    }

    public function test_tiene_capacitaciones_activas(): void
    {
        // Crear actividad con sesión futura
        $actividad = ActividadCapacitacion::factory()->create([
            'estado' => EstadoActividad::INSCRIPCIONES_ABIERTAS,
        ]);

        SesionCapacitacion::factory()->create([
            'actividad_id' => $actividad->id,
            'numero_sesion' => 1,
            'fecha' => today()->addDays(5),
            'hora_inicio' => '09:00',
            'hora_fin' => '12:00',
        ]);

        // Inscribir enfermero
        InscripcionCapacitacion::factory()->create([
            'actividad_id' => $actividad->id,
            'enfermero_id' => $this->enfermero->id,
            'estado' => 'aprobada',
        ]);

        $this->assertTrue($this->service->tieneCapacitacionesActivas($this->enfermero->id));
    }

    public function test_no_tiene_capacitaciones_activas_sin_inscripciones(): void
    {
        $this->assertFalse($this->service->tieneCapacitacionesActivas($this->enfermero->id));
    }

    public function test_obtener_proximas_sesiones(): void
    {
        // Crear actividad con múltiples sesiones
        $actividad = ActividadCapacitacion::factory()->create([
            'estado' => EstadoActividad::INSCRIPCIONES_ABIERTAS,
        ]);

        for ($i = 1; $i <= 5; $i++) {
            SesionCapacitacion::factory()->create([
                'actividad_id' => $actividad->id,
                'numero_sesion' => $i,
                'fecha' => today()->addDays($i),
                'hora_inicio' => '09:00',
                'hora_fin' => '12:00',
            ]);
        }

        // Inscribir enfermero
        InscripcionCapacitacion::factory()->create([
            'actividad_id' => $actividad->id,
            'enfermero_id' => $this->enfermero->id,
            'estado' => 'aprobada',
        ]);

        $sesiones = $this->service->obtenerProximasSesiones($this->enfermero->id, 3);

        $this->assertCount(3, $sesiones);
    }

    public function test_no_considera_inscripciones_canceladas(): void
    {
        // Crear actividad con sesión
        $actividad = ActividadCapacitacion::factory()->create([
            'estado' => EstadoActividad::INSCRIPCIONES_ABIERTAS,
        ]);

        SesionCapacitacion::factory()->create([
            'actividad_id' => $actividad->id,
            'numero_sesion' => 1,
            'fecha' => today(),
            'hora_inicio' => '09:00',
            'hora_fin' => '12:00',
        ]);

        // Inscripción CANCELADA
        InscripcionCapacitacion::factory()->create([
            'actividad_id' => $actividad->id,
            'enfermero_id' => $this->enfermero->id,
            'estado' => 'cancelada',
        ]);

        // No debería encontrar conflictos
        $conflictos = $this->service->verificarConflictoParaTurno(
            $this->enfermero->id,
            today(),
            '07:00',
            '15:00'
        );

        $this->assertEmpty($conflictos);
    }

    public function test_obtener_conflictos_en_rango(): void
    {
        // Crear turno
        $turno = Turno::factory()->create([
            'area_id' => $this->area->id,
            'fecha' => today()->addDays(3),
            'tipo' => TipoTurno::MATUTINO,
            'hora_inicio' => '07:00',
            'hora_fin' => '15:00',
            'estado' => EstadoTurno::ACTIVO,
        ]);

        // Asignar al enfermero
        $paciente = Paciente::factory()->create();
        AsignacionPaciente::create([
            'turno_id' => $turno->id,
            'enfermero_id' => $this->enfermero->id,
            'paciente_id' => $paciente->id,
            'fecha_hora_asignacion' => now(),
            'asignado_por' => User::factory()->create()->id,
        ]);

        // Crear actividad con sesión en la misma fecha
        $actividad = ActividadCapacitacion::factory()->create([
            'estado' => EstadoActividad::INSCRIPCIONES_ABIERTAS,
        ]);

        SesionCapacitacion::factory()->create([
            'actividad_id' => $actividad->id,
            'numero_sesion' => 1,
            'fecha' => today()->addDays(3),
            'hora_inicio' => '09:00',
            'hora_fin' => '12:00',
        ]);

        // Inscribir enfermero
        InscripcionCapacitacion::factory()->create([
            'actividad_id' => $actividad->id,
            'enfermero_id' => $this->enfermero->id,
            'estado' => 'aprobada',
        ]);

        // Buscar conflictos en rango
        $conflictos = $this->service->obtenerConflictosEnRango(
            $this->enfermero->id,
            today(),
            today()->addDays(7)
        );

        $this->assertNotEmpty($conflictos);
    }
}
