<?php

use App\Enums\EstadoTurno;
use App\Enums\TipoTurno;
use App\Enums\UserRole;
use App\Livewire\GestorTurnos;
use App\Models\Area;
use App\Models\AsignacionPaciente;
use App\Models\Enfermero;
use App\Models\Paciente;
use App\Models\Turno;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('gestor turnos component can be rendered by jefe de piso', function () {
    $user = User::factory()->create(['role' => UserRole::JEFE_PISO]);

    $this->actingAs($user)
        ->get(route('turnos.gestor'))
        ->assertStatus(200)
        ->assertSeeLivewire(GestorTurnos::class);
});

test('gestor turnos component cannot be accessed by enfermero regular', function () {
    $user = User::factory()->create(['role' => UserRole::ENFERMERO]);

    $this->actingAs($user)
        ->get(route('turnos.gestor'))
        ->assertStatus(403);
});

test('can create a new turno', function () {
    $area = Area::factory()->create();
    $user = User::factory()->create(['role' => UserRole::JEFE_PISO]);

    Livewire::actingAs($user)
        ->test(GestorTurnos::class, ['areaId' => $area->id])
        ->set('fecha', today()->format('Y-m-d'))
        ->set('tipo', TipoTurno::MATUTINO->value)
        ->set('jefeTurnoId', $user->id)
        ->call('crearTurno')
        ->assertDispatched('turno-creado');

    $this->assertDatabaseHas('turnos', [
        'area_id' => $area->id,
        'tipo' => TipoTurno::MATUTINO,
        'estado' => EstadoTurno::ACTIVO,
    ]);
});

test('can assign patient to nurse', function () {
    $area = Area::factory()->create();
    $user = User::factory()->create(['role' => UserRole::JEFE_PISO]);
    $turno = Turno::factory()->create(['area_id' => $area->id, 'estado' => EstadoTurno::ACTIVO]);
    $enfermero = Enfermero::factory()->create();
    $paciente = Paciente::factory()->create();

    Livewire::actingAs($user)
        ->test(GestorTurnos::class, ['areaId' => $area->id])
        ->set('turnoActualId', $turno->id)
        ->set('pacienteSeleccionado', $paciente->id)
        ->set('enfermeroSeleccionado', $enfermero->id)
        ->call('asignarPaciente')
        ->assertDispatched('paciente-asignado');

    $this->assertDatabaseHas('asignacion_pacientes', [
        'turno_id' => $turno->id,
        'enfermero_id' => $enfermero->id,
        'paciente_id' => $paciente->id,
        'fecha_hora_liberacion' => null,
    ]);
});

test('can reassign patient to different nurse', function () {
    $area = Area::factory()->create();
    $user = User::factory()->create(['role' => UserRole::JEFE_PISO]);
    $turno = Turno::factory()->create(['area_id' => $area->id, 'estado' => EstadoTurno::ACTIVO]);
    $enfermero1 = Enfermero::factory()->create();
    $enfermero2 = Enfermero::factory()->create();
    $paciente = Paciente::factory()->create();

    $asignacion = AsignacionPaciente::factory()->create([
        'turno_id' => $turno->id,
        'enfermero_id' => $enfermero1->id,
        'paciente_id' => $paciente->id,
        'fecha_hora_liberacion' => null,
    ]);

    Livewire::actingAs($user)
        ->test(GestorTurnos::class, ['areaId' => $area->id])
        ->set('turnoActualId', $turno->id)
        ->set('asignacionReasignar', $asignacion->id)
        ->set('nuevoEnfermero', $enfermero2->id)
        ->call('reasignarPaciente')
        ->assertDispatched('paciente-reasignado');

    // Old assignment should be released
    $this->assertDatabaseHas('asignacion_pacientes', [
        'id' => $asignacion->id,
    ]);
    expect($asignacion->fresh()->fecha_hora_liberacion)->not->toBeNull();

    // New assignment should exist
    $this->assertDatabaseHas('asignacion_pacientes', [
        'turno_id' => $turno->id,
        'enfermero_id' => $enfermero2->id,
        'paciente_id' => $paciente->id,
        'fecha_hora_liberacion' => null,
    ]);
});

test('can release assignment with motivo', function () {
    $area = Area::factory()->create();
    $user = User::factory()->create(['role' => UserRole::JEFE_PISO]);
    $turno = Turno::factory()->create(['area_id' => $area->id]);
    $asignacion = AsignacionPaciente::factory()->create([
        'turno_id' => $turno->id,
        'fecha_hora_liberacion' => null,
    ]);

    Livewire::actingAs($user)
        ->test(GestorTurnos::class, ['areaId' => $area->id])
        ->set('asignacionLiberar', $asignacion->id)
        ->set('motivoLiberacion', 'Fin de turno')
        ->call('liberarAsignacion')
        ->assertDispatched('asignacion-liberada');

    $asignacion->refresh();
    expect($asignacion->fecha_hora_liberacion)->not->toBeNull()
        ->and($asignacion->motivo_liberacion)->toBe('Fin de turno')
        ->and($asignacion->liberado_por)->toBe($user->id);
});

test('can close turno with novedades', function () {
    $area = Area::factory()->create();
    $user = User::factory()->create(['role' => UserRole::JEFE_PISO]);
    $turno = Turno::factory()->create([
        'area_id' => $area->id,
        'estado' => EstadoTurno::ACTIVO,
    ]);

    Livewire::actingAs($user)
        ->test(GestorTurnos::class, ['areaId' => $area->id])
        ->set('turnoActualId', $turno->id)
        ->set('novedadesRelevo', 'Paciente en observación en cama 101')
        ->call('cerrarTurno')
        ->assertDispatched('turno-cerrado');

    $turno->refresh();
    expect($turno->estado)->toBe(EstadoTurno::CERRADO)
        ->and($turno->novedades_relevo)->toBe('Paciente en observación en cama 101')
        ->and($turno->cerrado_por)->toBe($user->id)
        ->and($turno->cerrado_at)->not->toBeNull();
});

test('displays enfermeros with patient count', function () {
    $area = Area::factory()->create();
    $user = User::factory()->create(['role' => UserRole::JEFE_PISO]);
    $turno = Turno::factory()->create(['area_id' => $area->id, 'estado' => EstadoTurno::ACTIVO]);

    $enfermero1 = Enfermero::factory()->create(['area_fija_id' => $area->id]);
    $enfermero2 = Enfermero::factory()->create(['area_fija_id' => $area->id]);

    // Assign 2 patients to enfermero1
    AsignacionPaciente::factory()->count(2)->create([
        'turno_id' => $turno->id,
        'enfermero_id' => $enfermero1->id,
        'fecha_hora_liberacion' => null,
    ]);

    // Assign 1 patient to enfermero2
    AsignacionPaciente::factory()->create([
        'turno_id' => $turno->id,
        'enfermero_id' => $enfermero2->id,
        'fecha_hora_liberacion' => null,
    ]);

    $component = Livewire::actingAs($user)
        ->test(GestorTurnos::class, ['areaId' => $area->id])
        ->set('turnoActualId', $turno->id);

    $enfermeros = $component->get('enfermeros');

    $enf1 = $enfermeros->firstWhere('id', $enfermero1->id);
    $enf2 = $enfermeros->firstWhere('id', $enfermero2->id);

    expect($enf1->pacientes_asignados)->toBe(2)
        ->and($enf2->pacientes_asignados)->toBe(1);
});

test('prevents creating turno without required fields', function () {
    $area = Area::factory()->create();
    $user = User::factory()->create(['role' => UserRole::JEFE_PISO]);

    Livewire::actingAs($user)
        ->test(GestorTurnos::class, ['areaId' => $area->id])
        ->set('fecha', '')
        ->set('tipo', '')
        ->call('crearTurno')
        ->assertHasErrors(['fecha', 'tipo']);
});

test('prevents assigning patient without selecting nurse', function () {
    $area = Area::factory()->create();
    $user = User::factory()->create(['role' => UserRole::JEFE_PISO]);
    $turno = Turno::factory()->create(['area_id' => $area->id, 'estado' => EstadoTurno::ACTIVO]);
    $paciente = Paciente::factory()->create();

    Livewire::actingAs($user)
        ->test(GestorTurnos::class, ['areaId' => $area->id])
        ->set('turnoActualId', $turno->id)
        ->set('pacienteSeleccionado', $paciente->id)
        ->set('enfermeroSeleccionado', null)
        ->call('asignarPaciente')
        ->assertHasErrors(['enfermeroSeleccionado']);
});

test('prevents liberating assignment without motivo', function () {
    $area = Area::factory()->create();
    $user = User::factory()->create(['role' => UserRole::JEFE_PISO]);
    $asignacion = AsignacionPaciente::factory()->create();

    Livewire::actingAs($user)
        ->test(GestorTurnos::class, ['areaId' => $area->id])
        ->set('asignacionLiberar', $asignacion->id)
        ->set('motivoLiberacion', '')
        ->call('liberarAsignacion')
        ->assertHasErrors(['motivoLiberacion']);
});
