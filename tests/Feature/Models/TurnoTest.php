<?php

use App\Enums\EstadoTurno;
use App\Enums\TipoTurno;
use App\Models\Area;
use App\Models\AsignacionPaciente;
use App\Models\Turno;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('turno can be created with valid data', function () {
    $area = Area::factory()->create();
    $user = User::factory()->create();

    $turno = Turno::create([
        'area_id' => $area->id,
        'fecha' => today(),
        'tipo' => TipoTurno::MATUTINO,
        'hora_inicio' => '07:00',
        'hora_fin' => '15:00',
        'jefe_turno_id' => $user->id,
        'estado' => EstadoTurno::ACTIVO,
    ]);

    expect($turno)->toBeInstanceOf(Turno::class)
        ->and($turno->area_id)->toBe($area->id)
        ->and($turno->tipo)->toBe(TipoTurno::MATUTINO)
        ->and($turno->estado)->toBe(EstadoTurno::ACTIVO);
});

test('turno has correct relationships', function () {
    $area = Area::factory()->create();
    $jefe = User::factory()->create();

    $turno = Turno::factory()->create([
        'area_id' => $area->id,
        'jefe_turno_id' => $jefe->id,
    ]);

    expect($turno->area)->toBeInstanceOf(Area::class)
        ->and($turno->jefeTurno)->toBeInstanceOf(User::class)
        ->and($turno->area->id)->toBe($area->id)
        ->and($turno->jefeTurno->id)->toBe($jefe->id);
});

test('turno scope activos returns only active shifts', function () {
    Turno::factory()->create(['estado' => EstadoTurno::ACTIVO]);
    Turno::factory()->create(['estado' => EstadoTurno::ACTIVO]);
    Turno::factory()->create(['estado' => EstadoTurno::CERRADO]);

    $activos = Turno::activos()->get();

    expect($activos)->toHaveCount(2)
        ->and($activos->every(fn($t) => $t->estado === EstadoTurno::ACTIVO))->toBeTrue();
});

test('turno scope de_hoy returns only today shifts', function () {
    Turno::factory()->create(['fecha' => today()]);
    Turno::factory()->create(['fecha' => today()]);
    Turno::factory()->create(['fecha' => today()->subDay()]);

    $hoy = Turno::deHoy()->get();

    expect($hoy)->toHaveCount(2);
});

test('turno scope por_area filters by area', function () {
    $area1 = Area::factory()->create();
    $area2 = Area::factory()->create();

    Turno::factory()->create(['area_id' => $area1->id]);
    Turno::factory()->create(['area_id' => $area1->id]);
    Turno::factory()->create(['area_id' => $area2->id]);

    $turnosArea1 = Turno::porArea($area1->id)->get();

    expect($turnosArea1)->toHaveCount(2)
        ->and($turnosArea1->every(fn($t) => $t->area_id === $area1->id))->toBeTrue();
});

test('is_activo returns true for active shifts', function () {
    $turno = Turno::factory()->create(['estado' => EstadoTurno::ACTIVO]);

    expect($turno->isActivo())->toBeTrue();
});

test('is_activo returns false for closed shifts', function () {
    $turno = Turno::factory()->create(['estado' => EstadoTurno::CERRADO]);

    expect($turno->isActivo())->toBeFalse();
});

test('is_cerrado returns true for closed shifts', function () {
    $turno = Turno::factory()->create(['estado' => EstadoTurno::CERRADO]);

    expect($turno->isCerrado())->toBeTrue();
});

test('is_cerrado returns false for active shifts', function () {
    $turno = Turno::factory()->create(['estado' => EstadoTurno::ACTIVO]);

    expect($turno->isCerrado())->toBeFalse();
});

test('get_total_asignaciones returns count of active assignments', function () {
    $turno = Turno::factory()->create();

    // Create active assignments
    AsignacionPaciente::factory()->count(3)->create([
        'turno_id' => $turno->id,
        'fecha_hora_liberacion' => null,
    ]);

    // Create released assignment (should not be counted)
    AsignacionPaciente::factory()->create([
        'turno_id' => $turno->id,
        'fecha_hora_liberacion' => now(),
    ]);

    expect($turno->getTotalAsignaciones())->toBe(3);
});

test('turno enforces unique constraint on area fecha tipo', function () {
    $area = Area::factory()->create();

    Turno::factory()->create([
        'area_id' => $area->id,
        'fecha' => today(),
        'tipo' => TipoTurno::MATUTINO,
    ]);

    // Attempt to create duplicate
    expect(fn() => Turno::create([
        'area_id' => $area->id,
        'fecha' => today(),
        'tipo' => TipoTurno::MATUTINO,
        'hora_inicio' => '07:00',
        'hora_fin' => '15:00',
        'jefe_turno_id' => User::factory()->create()->id,
        'estado' => EstadoTurno::ACTIVO,
    ]))->toThrow(\Exception::class);
});

test('turno casts tipo to TipoTurno enum', function () {
    $turno = Turno::factory()->create(['tipo' => TipoTurno::MATUTINO]);

    expect($turno->tipo)->toBeInstanceOf(TipoTurno::class);
});

test('turno casts estado to EstadoTurno enum', function () {
    $turno = Turno::factory()->create(['estado' => EstadoTurno::ACTIVO]);

    expect($turno->estado)->toBeInstanceOf(EstadoTurno::class);
});
