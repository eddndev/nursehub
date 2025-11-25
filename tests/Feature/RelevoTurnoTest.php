<?php

use App\Enums\EstadoTurno;
use App\Enums\TipoTurno;
use App\Enums\UserRole;
use App\Livewire\RelevoTurno;
use App\Models\Area;
use App\Models\AsignacionPaciente;
use App\Models\Enfermero;
use App\Models\Paciente;
use App\Models\Turno;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('relevo turno component can be rendered by jefe de piso', function () {
    $user = User::factory()->create(['role' => UserRole::JEFE_PISO]);

    $this->actingAs($user)
        ->get(route('turnos.relevo'))
        ->assertStatus(200)
        ->assertSeeLivewire(RelevoTurno::class);
});

test('relevo turno component cannot be accessed by enfermero regular', function () {
    $user = User::factory()->create(['role' => UserRole::ENFERMERO]);

    $this->actingAs($user)
        ->get(route('turnos.relevo'))
        ->assertStatus(403);
});

test('displays turno actual information when active turno exists', function () {
    $area = Area::factory()->create();
    $user = User::factory()->create(['role' => UserRole::JEFE_PISO]);

    $turno = Turno::factory()->create([
        'area_id' => $area->id,
        'fecha' => today(),
        'estado' => EstadoTurno::ACTIVO,
    ]);

    $component = Livewire::actingAs($user)
        ->test(RelevoTurno::class, ['areaId' => $area->id]);

    $turnoActual = $component->get('turnoActual');

    expect($turnoActual)->not->toBeNull()
        ->and($turnoActual->id)->toBe($turno->id)
        ->and($turnoActual->area->nombre)->toBe($area->nombre);
});

test('displays message when no active turno exists', function () {
    $area = Area::factory()->create();
    $user = User::factory()->create(['role' => UserRole::JEFE_PISO]);

    Livewire::actingAs($user)
        ->test(RelevoTurno::class, ['areaId' => $area->id])
        ->assertSee('No hay turno activo');
});

test('displays novedades from turno anterior', function () {
    $area = Area::factory()->create();
    $user = User::factory()->create(['role' => UserRole::JEFE_PISO]);

    // Crear turno anterior cerrado
    $turnoAnterior = Turno::factory()->create([
        'area_id' => $area->id,
        'fecha' => today()->subDay(),
        'tipo' => TipoTurno::MATUTINO,
        'estado' => EstadoTurno::CERRADO,
        'novedades_relevo' => 'Paciente en cama 301-A requiere atención especial',
    ]);

    // Crear turno actual
    $turnoActual = Turno::factory()->create([
        'area_id' => $area->id,
        'fecha' => today(),
        'tipo' => TipoTurno::MATUTINO,
        'estado' => EstadoTurno::ACTIVO,
    ]);

    $component = Livewire::actingAs($user)
        ->test(RelevoTurno::class, ['areaId' => $area->id]);

    $turnoAnteriorData = $component->get('turnoAnterior');

    expect($turnoAnteriorData)->not->toBeNull()
        ->and($turnoAnteriorData->novedades_relevo)->toBe('Paciente en cama 301-A requiere atención especial');
});

test('can save novedades without closing turno', function () {
    $area = Area::factory()->create();
    $user = User::factory()->create(['role' => UserRole::JEFE_PISO]);

    $turno = Turno::factory()->create([
        'area_id' => $area->id,
        'fecha' => today(),
        'estado' => EstadoTurno::ACTIVO,
    ]);

    Livewire::actingAs($user)
        ->test(RelevoTurno::class, ['areaId' => $area->id])
        ->set('novedadesRelevo', 'Paciente en cama 101 requiere control cada 2 horas')
        ->call('guardarNovedades')
        ->assertDispatched('novedades-guardadas');

    $turno->refresh();
    expect($turno->novedades_relevo)->toBe('Paciente en cama 101 requiere control cada 2 horas')
        ->and($turno->estado)->toBe(EstadoTurno::ACTIVO);
});

test('can close turno with relevo', function () {
    $area = Area::factory()->create();
    $user = User::factory()->create(['role' => UserRole::JEFE_PISO]);

    $turno = Turno::factory()->create([
        'area_id' => $area->id,
        'fecha' => today(),
        'estado' => EstadoTurno::ACTIVO,
    ]);

    Livewire::actingAs($user)
        ->test(RelevoTurno::class, ['areaId' => $area->id])
        ->set('novedadesRelevo', 'Fin del turno, todo en orden')
        ->call('cerrarTurnoConRelevo')
        ->assertDispatched('turno-cerrado');

    $turno->refresh();
    expect($turno->estado)->toBe(EstadoTurno::CERRADO)
        ->and($turno->novedades_relevo)->toBe('Fin del turno, todo en orden')
        ->and($turno->cerrado_por)->toBe($user->id)
        ->and($turno->cerrado_at)->not->toBeNull();
});

test('liberates all asignaciones when closing turno', function () {
    $area = Area::factory()->create();
    $user = User::factory()->create(['role' => UserRole::JEFE_PISO]);

    $turno = Turno::factory()->create([
        'area_id' => $area->id,
        'fecha' => today(),
        'estado' => EstadoTurno::ACTIVO,
    ]);

    // Crear asignaciones activas
    $asignaciones = AsignacionPaciente::factory()->count(3)->create([
        'turno_id' => $turno->id,
        'fecha_hora_liberacion' => null,
    ]);

    Livewire::actingAs($user)
        ->test(RelevoTurno::class, ['areaId' => $area->id])
        ->set('novedadesRelevo', 'Cierre de turno')
        ->call('cerrarTurnoConRelevo');

    // Verificar que todas las asignaciones fueron liberadas
    foreach ($asignaciones as $asignacion) {
        $asignacion->refresh();
        expect($asignacion->fecha_hora_liberacion)->not->toBeNull()
            ->and($asignacion->liberado_por)->toBe($user->id)
            ->and($asignacion->motivo_liberacion)->toBe('Cierre de turno');
    }
});

test('cannot close already closed turno', function () {
    $area = Area::factory()->create();
    $user = User::factory()->create(['role' => UserRole::JEFE_PISO]);

    $turno = Turno::factory()->create([
        'area_id' => $area->id,
        'fecha' => today(),
        'estado' => EstadoTurno::CERRADO,
        'cerrado_at' => now(),
        'cerrado_por' => $user->id,
    ]);

    Livewire::actingAs($user)
        ->test(RelevoTurno::class, ['areaId' => $area->id])
        ->set('novedadesRelevo', 'Intento de cerrar turno ya cerrado')
        ->call('cerrarTurnoConRelevo')
        ->assertDispatched('error');
});

test('displays resumen de asignaciones correctly', function () {
    $area = Area::factory()->create();
    $user = User::factory()->create(['role' => UserRole::JEFE_PISO]);

    $turno = Turno::factory()->create([
        'area_id' => $area->id,
        'fecha' => today(),
        'estado' => EstadoTurno::ACTIVO,
    ]);

    $enfermero1 = Enfermero::factory()->create();
    $enfermero2 = Enfermero::factory()->create();

    // 2 asignaciones para enfermero1
    AsignacionPaciente::factory()->count(2)->create([
        'turno_id' => $turno->id,
        'enfermero_id' => $enfermero1->id,
        'fecha_hora_liberacion' => null,
    ]);

    // 1 asignación para enfermero2
    AsignacionPaciente::factory()->create([
        'turno_id' => $turno->id,
        'enfermero_id' => $enfermero2->id,
        'fecha_hora_liberacion' => null,
    ]);

    $component = Livewire::actingAs($user)
        ->test(RelevoTurno::class, ['areaId' => $area->id]);

    $resumen = $component->get('resumenAsignaciones');

    expect($resumen['total_asignaciones'])->toBe(3)
        ->and($resumen['enfermeros_activos'])->toBe(2)
        ->and($resumen['pacientes_asignados'])->toBe(3);
});

test('coordinador can change area', function () {
    $area1 = Area::factory()->create(['nombre' => 'Urgencias']);
    $area2 = Area::factory()->create(['nombre' => 'Cirugía']);
    $user = User::factory()->create(['role' => UserRole::COORDINADOR]);

    $turno1 = Turno::factory()->create([
        'area_id' => $area1->id,
        'fecha' => today(),
        'estado' => EstadoTurno::ACTIVO,
    ]);

    $turno2 = Turno::factory()->create([
        'area_id' => $area2->id,
        'fecha' => today(),
        'estado' => EstadoTurno::ACTIVO,
    ]);

    $component = Livewire::actingAs($user)
        ->test(RelevoTurno::class, ['areaId' => $area1->id])
        ->assertSet('turnoActualId', $turno1->id)
        ->set('areaId', $area2->id)
        ->call('cambiarArea')
        ->assertSet('turnoActualId', $turno2->id)
        ->assertDispatched('area-cambiada');
});

test('validates novedades length', function () {
    $area = Area::factory()->create();
    $user = User::factory()->create(['role' => UserRole::JEFE_PISO]);

    $turno = Turno::factory()->create([
        'area_id' => $area->id,
        'fecha' => today(),
        'estado' => EstadoTurno::ACTIVO,
    ]);

    $novedadesLargas = str_repeat('A', 5001); // 5001 caracteres, excede el límite de 5000

    Livewire::actingAs($user)
        ->test(RelevoTurno::class, ['areaId' => $area->id])
        ->set('novedadesRelevo', $novedadesLargas)
        ->call('guardarNovedades')
        ->assertHasErrors(['novedadesRelevo']);
});

test('jefe de piso with area fija auto-loads turno', function () {
    $area = Area::factory()->create();
    $user = User::factory()->create(['role' => UserRole::JEFE_PISO]);
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
        ->test(RelevoTurno::class);

    expect($component->get('areaId'))->toBe($area->id)
        ->and($component->get('turnoActualId'))->toBe($turno->id);
});

test('only shows turno anterior of same tipo', function () {
    $area = Area::factory()->create();
    $user = User::factory()->create(['role' => UserRole::JEFE_PISO]);

    // Turno anterior vespertino (no debería mostrarse)
    Turno::factory()->create([
        'area_id' => $area->id,
        'fecha' => today()->subDay(),
        'tipo' => TipoTurno::VESPERTINO,
        'estado' => EstadoTurno::CERRADO,
        'novedades_relevo' => 'Novedades del vespertino',
    ]);

    // Turno anterior matutino (debería mostrarse)
    $turnoAnteriorMatutino = Turno::factory()->create([
        'area_id' => $area->id,
        'fecha' => today()->subDay(),
        'tipo' => TipoTurno::MATUTINO,
        'estado' => EstadoTurno::CERRADO,
        'novedades_relevo' => 'Novedades del matutino anterior',
    ]);

    // Turno actual matutino
    $turnoActual = Turno::factory()->create([
        'area_id' => $area->id,
        'fecha' => today(),
        'tipo' => TipoTurno::MATUTINO,
        'estado' => EstadoTurno::ACTIVO,
    ]);

    $component = Livewire::actingAs($user)
        ->test(RelevoTurno::class, ['areaId' => $area->id]);

    $turnoAnterior = $component->get('turnoAnterior');

    expect($turnoAnterior)->not->toBeNull()
        ->and($turnoAnterior->id)->toBe($turnoAnteriorMatutino->id)
        ->and($turnoAnterior->tipo)->toBe(TipoTurno::MATUTINO)
        ->and($turnoAnterior->novedades_relevo)->toBe('Novedades del matutino anterior');
});

test('prevents closing turno without turno activo', function () {
    $user = User::factory()->create(['role' => UserRole::JEFE_PISO]);

    Livewire::actingAs($user)
        ->test(RelevoTurno::class)
        ->call('cerrarTurnoConRelevo')
        ->assertDispatched('error');
});

test('prevents saving novedades without turno activo', function () {
    $user = User::factory()->create(['role' => UserRole::JEFE_PISO]);

    Livewire::actingAs($user)
        ->test(RelevoTurno::class)
        ->set('novedadesRelevo', 'Intento de guardar sin turno')
        ->call('guardarNovedades')
        ->assertDispatched('error');
});
