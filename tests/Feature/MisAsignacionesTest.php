<?php

use App\Enums\EstadoTurno;
use App\Enums\NivelTriage;
use App\Enums\UserRole;
use App\Livewire\MisAsignaciones;
use App\Models\Area;
use App\Models\AsignacionPaciente;
use App\Models\Enfermero;
use App\Models\Paciente;
use App\Models\RegistroSignosVitales;
use App\Models\Turno;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('mis asignaciones component can be rendered by enfermero', function () {
    $user = User::factory()->create(['role' => UserRole::ENFERMERO]);
    $enfermero = Enfermero::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user)
        ->get(route('enfermeria.mis-asignaciones'))
        ->assertStatus(200)
        ->assertSeeLivewire(MisAsignaciones::class);
});

test('mis asignaciones component cannot be accessed without enfermero profile', function () {
    $user = User::factory()->create(['role' => UserRole::ENFERMERO]);
    // User has ENFERMERO role but no enfermero profile

    $this->actingAs($user)
        ->get(route('enfermeria.mis-asignaciones'))
        ->assertStatus(403);
});

test('displays turno actual information when active turno exists', function () {
    $area = Area::factory()->create();
    $user = User::factory()->create(['role' => UserRole::ENFERMERO]);
    $enfermero = Enfermero::factory()->create([
        'user_id' => $user->id,
        'area_fija_id' => $area->id,
    ]);

    $turno = Turno::factory()->create([
        'area_id' => $area->id,
        'fecha' => today(),
        'estado' => EstadoTurno::ACTIVO,
    ]);

    $component = Livewire::actingAs($user)
        ->test(MisAsignaciones::class);

    $turnoActual = $component->get('turnoActual');
    expect($turnoActual)->not->toBeNull()
        ->and($turnoActual->id)->toBe($turno->id)
        ->and($turnoActual->area->nombre)->toBe($area->nombre);
});

test('displays message when no active turno exists', function () {
    $user = User::factory()->create(['role' => UserRole::ENFERMERO]);
    $enfermero = Enfermero::factory()->create(['user_id' => $user->id]);

    Livewire::actingAs($user)
        ->test(MisAsignaciones::class)
        ->assertSee('No hay turno activo');
});

test('displays assigned patients with their information', function () {
    $area = Area::factory()->create();
    $user = User::factory()->create(['role' => UserRole::ENFERMERO]);
    $enfermero = Enfermero::factory()->create([
        'user_id' => $user->id,
        'area_fija_id' => $area->id,
    ]);

    $turno = Turno::factory()->create([
        'area_id' => $area->id,
        'fecha' => today(),
        'estado' => EstadoTurno::ACTIVO,
    ]);

    $paciente1 = Paciente::factory()->create(['nombre' => 'Juan', 'apellido_paterno' => 'Pérez']);
    $paciente2 = Paciente::factory()->create(['nombre' => 'María', 'apellido_paterno' => 'García']);

    AsignacionPaciente::factory()->create([
        'turno_id' => $turno->id,
        'enfermero_id' => $enfermero->id,
        'paciente_id' => $paciente1->id,
        'fecha_hora_liberacion' => null,
    ]);

    AsignacionPaciente::factory()->create([
        'turno_id' => $turno->id,
        'enfermero_id' => $enfermero->id,
        'paciente_id' => $paciente2->id,
        'fecha_hora_liberacion' => null,
    ]);

    Livewire::actingAs($user)
        ->test(MisAsignaciones::class)
        ->assertSee('Juan Pérez')
        ->assertSee('María García');
});

test('only displays active assignments not released ones', function () {
    $area = Area::factory()->create();
    $user = User::factory()->create(['role' => UserRole::ENFERMERO]);
    $enfermero = Enfermero::factory()->create([
        'user_id' => $user->id,
        'area_fija_id' => $area->id,
    ]);

    $turno = Turno::factory()->create([
        'area_id' => $area->id,
        'fecha' => today(),
        'estado' => EstadoTurno::ACTIVO,
    ]);

    $pacienteActivo = Paciente::factory()->create(['nombre' => 'Juan', 'apellido_paterno' => 'Activo']);
    $pacienteLiberado = Paciente::factory()->create(['nombre' => 'María', 'apellido_paterno' => 'Liberada']);

    // Active assignment
    AsignacionPaciente::factory()->create([
        'turno_id' => $turno->id,
        'enfermero_id' => $enfermero->id,
        'paciente_id' => $pacienteActivo->id,
        'fecha_hora_liberacion' => null,
    ]);

    // Released assignment
    AsignacionPaciente::factory()->create([
        'turno_id' => $turno->id,
        'enfermero_id' => $enfermero->id,
        'paciente_id' => $pacienteLiberado->id,
        'fecha_hora_liberacion' => now(),
    ]);

    Livewire::actingAs($user)
        ->test(MisAsignaciones::class)
        ->assertSee('Juan Activo')
        ->assertDontSee('María Liberada');
});

test('displays vital signs for patients with recent recordings', function () {
    $area = Area::factory()->create();
    $user = User::factory()->create(['role' => UserRole::ENFERMERO]);
    $enfermero = Enfermero::factory()->create([
        'user_id' => $user->id,
        'area_fija_id' => $area->id,
    ]);

    $turno = Turno::factory()->create([
        'area_id' => $area->id,
        'fecha' => today(),
        'estado' => EstadoTurno::ACTIVO,
    ]);

    $paciente = Paciente::factory()->create();

    AsignacionPaciente::factory()->create([
        'turno_id' => $turno->id,
        'enfermero_id' => $enfermero->id,
        'paciente_id' => $paciente->id,
        'fecha_hora_liberacion' => null,
    ]);

    RegistroSignosVitales::factory()->create([
        'paciente_id' => $paciente->id,
        'frecuencia_cardiaca' => 75,
        'frecuencia_respiratoria' => 18,
        'temperatura' => 36.5,
        'saturacion_oxigeno' => 98,
        'nivel_triage' => NivelTriage::AMARILLO,
    ]);

    $component = Livewire::actingAs($user)
        ->test(MisAsignaciones::class);

    $pacientes = $component->get('pacientesAsignados');
    $pacienteData = $pacientes->first();

    expect($pacienteData->ultimo_registro_signos)->not->toBeNull()
        ->and($pacienteData->ultimo_registro_signos->frecuencia_cardiaca)->toBe(75)
        ->and($pacienteData->ultimo_registro_signos->frecuencia_respiratoria)->toBe(18)
        ->and((float)$pacienteData->ultimo_registro_signos->temperatura)->toBe(36.5)
        ->and((float)$pacienteData->ultimo_registro_signos->saturacion_oxigeno)->toBe(98.0);
});

test('displays triage level with correct badge', function () {
    $area = Area::factory()->create();
    $user = User::factory()->create(['role' => UserRole::ENFERMERO]);
    $enfermero = Enfermero::factory()->create([
        'user_id' => $user->id,
        'area_fija_id' => $area->id,
    ]);

    $turno = Turno::factory()->create([
        'area_id' => $area->id,
        'fecha' => today(),
        'estado' => EstadoTurno::ACTIVO,
    ]);

    $paciente = Paciente::factory()->create();

    AsignacionPaciente::factory()->create([
        'turno_id' => $turno->id,
        'enfermero_id' => $enfermero->id,
        'paciente_id' => $paciente->id,
        'fecha_hora_liberacion' => null,
    ]);

    RegistroSignosVitales::factory()->create([
        'paciente_id' => $paciente->id,
        'nivel_triage' => NivelTriage::ROJO,
    ]);

    $component = Livewire::actingAs($user)
        ->test(MisAsignaciones::class);

    $pacientes = $component->get('pacientesAsignados');
    expect($pacientes->first()->nivel_triage)->toBe(NivelTriage::ROJO);
});

test('calculates statistics correctly', function () {
    $area = Area::factory()->create();
    $user = User::factory()->create(['role' => UserRole::ENFERMERO]);
    $enfermero = Enfermero::factory()->create([
        'user_id' => $user->id,
        'area_fija_id' => $area->id,
    ]);

    $turno = Turno::factory()->create([
        'area_id' => $area->id,
        'fecha' => today(),
        'estado' => EstadoTurno::ACTIVO,
    ]);

    // Create 5 patients with different triage levels
    foreach ([NivelTriage::ROJO, NivelTriage::NARANJA, NivelTriage::AMARILLO, NivelTriage::VERDE, NivelTriage::AZUL] as $nivel) {
        $paciente = Paciente::factory()->create();

        AsignacionPaciente::factory()->create([
            'turno_id' => $turno->id,
            'enfermero_id' => $enfermero->id,
            'paciente_id' => $paciente->id,
            'fecha_hora_liberacion' => null,
        ]);

        RegistroSignosVitales::factory()->create([
            'paciente_id' => $paciente->id,
            'nivel_triage' => $nivel,
        ]);
    }

    $component = Livewire::actingAs($user)
        ->test(MisAsignaciones::class);

    $stats = $component->get('estadisticas');

    expect($stats['total_pacientes'])->toBe(5)
        ->and($stats['con_triage_rojo'])->toBe(1)
        ->and($stats['con_triage_naranja'])->toBe(1)
        ->and($stats['con_triage_amarillo'])->toBe(1)
        ->and($stats['con_triage_verde'])->toBe(1)
        ->and($stats['con_triage_azul'])->toBe(1);
});

test('can navigate to patient expediente', function () {
    $area = Area::factory()->create();
    $user = User::factory()->create(['role' => UserRole::ENFERMERO]);
    $enfermero = Enfermero::factory()->create([
        'user_id' => $user->id,
        'area_fija_id' => $area->id,
    ]);

    $turno = Turno::factory()->create([
        'area_id' => $area->id,
        'fecha' => today(),
        'estado' => EstadoTurno::ACTIVO,
    ]);

    $paciente = Paciente::factory()->create();

    AsignacionPaciente::factory()->create([
        'turno_id' => $turno->id,
        'enfermero_id' => $enfermero->id,
        'paciente_id' => $paciente->id,
        'fecha_hora_liberacion' => null,
    ]);

    Livewire::actingAs($user)
        ->test(MisAsignaciones::class)
        ->call('verExpediente', $paciente->id)
        ->assertRedirect(route('enfermeria.expediente', ['id' => $paciente->id]));
});

test('refrescar asignaciones dispatches event', function () {
    $user = User::factory()->create(['role' => UserRole::ENFERMERO]);
    $enfermero = Enfermero::factory()->create(['user_id' => $user->id]);

    Livewire::actingAs($user)
        ->test(MisAsignaciones::class)
        ->call('refrescarAsignaciones')
        ->assertDispatched('asignaciones-refrescadas');
});

test('displays enfermero information correctly', function () {
    $area = Area::factory()->create(['nombre' => 'Urgencias']);
    $user = User::factory()->create([
        'role' => UserRole::ENFERMERO,
        'name' => 'Juan Enfermero',
    ]);
    $enfermero = Enfermero::factory()->create([
        'user_id' => $user->id,
        'area_fija_id' => $area->id,
    ]);

    $component = Livewire::actingAs($user)
        ->test(MisAsignaciones::class);

    $enfermeroData = $component->get('enfermero');

    expect($enfermeroData)->not->toBeNull()
        ->and($enfermeroData->user->name)->toBe('Juan Enfermero')
        ->and($enfermeroData->areaFija->nombre)->toBe('Urgencias');
});

test('finds turno by assignments when enfermero has no fixed area', function () {
    $area = Area::factory()->create();
    $user = User::factory()->create(['role' => UserRole::ENFERMERO]);
    $enfermero = Enfermero::factory()->create([
        'user_id' => $user->id,
        'area_fija_id' => null, // No fixed area
    ]);

    $turno = Turno::factory()->create([
        'area_id' => $area->id,
        'fecha' => today(),
        'estado' => EstadoTurno::ACTIVO,
    ]);

    $paciente = Paciente::factory()->create();

    // Assign patient - this creates the connection
    AsignacionPaciente::factory()->create([
        'turno_id' => $turno->id,
        'enfermero_id' => $enfermero->id,
        'paciente_id' => $paciente->id,
        'fecha_hora_liberacion' => null,
    ]);

    $component = Livewire::actingAs($user)
        ->test(MisAsignaciones::class);

    $turnoActual = $component->get('turnoActual');

    expect($turnoActual)->not->toBeNull()
        ->and($turnoActual->id)->toBe($turno->id);
});

test('displays novedades relevo from turno', function () {
    $area = Area::factory()->create();
    $user = User::factory()->create(['role' => UserRole::ENFERMERO]);
    $enfermero = Enfermero::factory()->create([
        'user_id' => $user->id,
        'area_fija_id' => $area->id,
    ]);

    $turno = Turno::factory()->create([
        'area_id' => $area->id,
        'fecha' => today(),
        'estado' => EstadoTurno::ACTIVO,
        'novedades_relevo' => 'Paciente en cama 101 requiere atención especial',
    ]);

    Livewire::actingAs($user)
        ->test(MisAsignaciones::class)
        ->assertSee('Paciente en cama 101 requiere atención especial');
});

test('displays empty state message when no patients assigned', function () {
    $area = Area::factory()->create();
    $user = User::factory()->create(['role' => UserRole::ENFERMERO]);
    $enfermero = Enfermero::factory()->create([
        'user_id' => $user->id,
        'area_fija_id' => $area->id,
    ]);

    $turno = Turno::factory()->create([
        'area_id' => $area->id,
        'fecha' => today(),
        'estado' => EstadoTurno::ACTIVO,
    ]);

    Livewire::actingAs($user)
        ->test(MisAsignaciones::class)
        ->assertSee('No hay pacientes asignados');
});
