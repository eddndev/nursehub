<?php

use App\Models\AsignacionPaciente;
use App\Models\Enfermero;
use App\Models\Paciente;
use App\Models\Turno;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('asignacion paciente can be created with valid data', function () {
    $turno = Turno::factory()->create();
    $enfermero = Enfermero::factory()->create();
    $paciente = Paciente::factory()->create();
    $user = User::factory()->create();

    $asignacion = AsignacionPaciente::create([
        'turno_id' => $turno->id,
        'enfermero_id' => $enfermero->id,
        'paciente_id' => $paciente->id,
        'fecha_hora_asignacion' => now(),
        'asignado_por' => $user->id,
    ]);

    expect($asignacion)->toBeInstanceOf(AsignacionPaciente::class)
        ->and($asignacion->turno_id)->toBe($turno->id)
        ->and($asignacion->enfermero_id)->toBe($enfermero->id)
        ->and($asignacion->paciente_id)->toBe($paciente->id)
        ->and($asignacion->fecha_hora_liberacion)->toBeNull();
});

test('asignacion paciente has correct relationships', function () {
    $turno = Turno::factory()->create();
    $enfermero = Enfermero::factory()->create();
    $paciente = Paciente::factory()->create();
    $asignadoPor = User::factory()->create();

    $asignacion = AsignacionPaciente::factory()->create([
        'turno_id' => $turno->id,
        'enfermero_id' => $enfermero->id,
        'paciente_id' => $paciente->id,
        'asignado_por' => $asignadoPor->id,
    ]);

    expect($asignacion->turno)->toBeInstanceOf(Turno::class)
        ->and($asignacion->enfermero)->toBeInstanceOf(Enfermero::class)
        ->and($asignacion->paciente)->toBeInstanceOf(Paciente::class)
        ->and($asignacion->asignadoPor)->toBeInstanceOf(User::class)
        ->and($asignacion->turno->id)->toBe($turno->id)
        ->and($asignacion->enfermero->id)->toBe($enfermero->id)
        ->and($asignacion->paciente->id)->toBe($paciente->id)
        ->and($asignacion->asignadoPor->id)->toBe($asignadoPor->id);
});

test('scope activas returns only active assignments', function () {
    AsignacionPaciente::factory()->count(3)->create(['fecha_hora_liberacion' => null]);
    AsignacionPaciente::factory()->count(2)->create(['fecha_hora_liberacion' => now()]);

    $activas = AsignacionPaciente::activas()->get();

    expect($activas)->toHaveCount(3)
        ->and($activas->every(fn($a) => $a->fecha_hora_liberacion === null))->toBeTrue();
});

test('scope liberadas returns only released assignments', function () {
    AsignacionPaciente::factory()->count(2)->create(['fecha_hora_liberacion' => null]);
    AsignacionPaciente::factory()->count(3)->create(['fecha_hora_liberacion' => now()]);

    $liberadas = AsignacionPaciente::liberadas()->get();

    expect($liberadas)->toHaveCount(3)
        ->and($liberadas->every(fn($a) => $a->fecha_hora_liberacion !== null))->toBeTrue();
});

test('scope por_enfermero filters by enfermero', function () {
    $enfermero1 = Enfermero::factory()->create();
    $enfermero2 = Enfermero::factory()->create();

    AsignacionPaciente::factory()->count(3)->create(['enfermero_id' => $enfermero1->id]);
    AsignacionPaciente::factory()->count(2)->create(['enfermero_id' => $enfermero2->id]);

    $asignaciones = AsignacionPaciente::porEnfermero($enfermero1->id)->get();

    expect($asignaciones)->toHaveCount(3)
        ->and($asignaciones->every(fn($a) => $a->enfermero_id === $enfermero1->id))->toBeTrue();
});

test('scope por_paciente filters by paciente', function () {
    $paciente1 = Paciente::factory()->create();
    $paciente2 = Paciente::factory()->create();

    AsignacionPaciente::factory()->count(2)->create(['paciente_id' => $paciente1->id]);
    AsignacionPaciente::factory()->count(3)->create(['paciente_id' => $paciente2->id]);

    $asignaciones = AsignacionPaciente::porPaciente($paciente1->id)->get();

    expect($asignaciones)->toHaveCount(2)
        ->and($asignaciones->every(fn($a) => $a->paciente_id === $paciente1->id))->toBeTrue();
});

test('scope por_turno filters by turno', function () {
    $turno1 = Turno::factory()->create();
    $turno2 = Turno::factory()->create();

    AsignacionPaciente::factory()->count(4)->create(['turno_id' => $turno1->id]);
    AsignacionPaciente::factory()->count(2)->create(['turno_id' => $turno2->id]);

    $asignaciones = AsignacionPaciente::porTurno($turno1->id)->get();

    expect($asignaciones)->toHaveCount(4)
        ->and($asignaciones->every(fn($a) => $a->turno_id === $turno1->id))->toBeTrue();
});

test('is_activa returns true for active assignment', function () {
    $asignacion = AsignacionPaciente::factory()->create(['fecha_hora_liberacion' => null]);

    expect($asignacion->isActiva())->toBeTrue();
});

test('is_activa returns false for released assignment', function () {
    $asignacion = AsignacionPaciente::factory()->create(['fecha_hora_liberacion' => now()]);

    expect($asignacion->isActiva())->toBeFalse();
});

test('liberar method releases assignment correctly', function () {
    $asignacion = AsignacionPaciente::factory()->create(['fecha_hora_liberacion' => null]);
    $user = User::factory()->create();

    expect($asignacion->isActiva())->toBeTrue();

    $asignacion->liberar($user, 'Fin de turno');
    $asignacion->refresh();

    expect($asignacion->isActiva())->toBeFalse()
        ->and($asignacion->fecha_hora_liberacion)->not->toBeNull()
        ->and($asignacion->liberado_por)->toBe($user->id)
        ->and($asignacion->motivo_liberacion)->toBe('Fin de turno');
});

test('paciente has many asignaciones relationship', function () {
    $paciente = Paciente::factory()->create();

    AsignacionPaciente::factory()->count(3)->create(['paciente_id' => $paciente->id]);

    expect($paciente->asignaciones)->toHaveCount(3);
});

test('paciente has one asignacion actual relationship', function () {
    $paciente = Paciente::factory()->create();

    // Create old released assignments
    AsignacionPaciente::factory()->count(2)->create([
        'paciente_id' => $paciente->id,
        'fecha_hora_liberacion' => now()->subHours(5),
        'fecha_hora_asignacion' => now()->subHours(10),
    ]);

    // Create current active assignment
    $asignacionActual = AsignacionPaciente::factory()->create([
        'paciente_id' => $paciente->id,
        'fecha_hora_liberacion' => null,
        'fecha_hora_asignacion' => now()->subHours(2),
    ]);

    $paciente->load('asignacionActual');

    expect($paciente->asignacionActual)->not->toBeNull()
        ->and($paciente->asignacionActual->id)->toBe($asignacionActual->id)
        ->and($paciente->asignacionActual->isActiva())->toBeTrue();
});

test('enfermero has many asignaciones relationship', function () {
    $enfermero = Enfermero::factory()->create();

    AsignacionPaciente::factory()->count(5)->create(['enfermero_id' => $enfermero->id]);

    expect($enfermero->asignaciones)->toHaveCount(5);
});
