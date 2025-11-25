<?php

use App\Enums\PacienteEstado;
use App\Models\AsignacionPaciente;
use App\Models\Paciente;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('observer auto-releases assignments when patient is discharged', function () {
    $paciente = Paciente::factory()->create(['estado' => PacienteEstado::ACTIVO]);
    $user = User::factory()->create();

    // Create active assignments
    $asignacion1 = AsignacionPaciente::factory()->create([
        'paciente_id' => $paciente->id,
        'fecha_hora_liberacion' => null,
    ]);

    $asignacion2 = AsignacionPaciente::factory()->create([
        'paciente_id' => $paciente->id,
        'fecha_hora_liberacion' => null,
    ]);

    expect($asignacion1->fresh()->isActiva())->toBeTrue()
        ->and($asignacion2->fresh()->isActiva())->toBeTrue();

    // Authenticate as user for the observer
    $this->actingAs($user);

    // Discharge patient
    $paciente->update(['estado' => PacienteEstado::DADO_ALTA]);

    // Verify assignments were auto-released
    expect($asignacion1->fresh()->isActiva())->toBeFalse()
        ->and($asignacion2->fresh()->isActiva())->toBeFalse()
        ->and($asignacion1->fresh()->motivo_liberacion)->toBe('Alta del paciente')
        ->and($asignacion2->fresh()->motivo_liberacion)->toBe('Alta del paciente')
        ->and($asignacion1->fresh()->liberado_por)->toBe($user->id)
        ->and($asignacion2->fresh()->liberado_por)->toBe($user->id);
});

test('observer does not release assignments if patient state changes to something other than dado_alta', function () {
    $paciente = Paciente::factory()->create(['estado' => PacienteEstado::ACTIVO]);

    // Create active assignment
    $asignacion = AsignacionPaciente::factory()->create([
        'paciente_id' => $paciente->id,
        'fecha_hora_liberacion' => null,
    ]);

    expect($asignacion->fresh()->isActiva())->toBeTrue();

    // Change state but not to dado_alta
    $paciente->update(['estado' => PacienteEstado::ACTIVO]);

    // Verify assignment is still active
    expect($asignacion->fresh()->isActiva())->toBeTrue();
});

test('observer does not release assignments if patient estado field is not dirty', function () {
    $paciente = Paciente::factory()->create(['estado' => PacienteEstado::DADO_ALTA]);

    // Create active assignment
    $asignacion = AsignacionPaciente::factory()->create([
        'paciente_id' => $paciente->id,
        'fecha_hora_liberacion' => null,
    ]);

    expect($asignacion->fresh()->isActiva())->toBeTrue();

    // Update patient without changing estado
    $paciente->update(['nombre' => 'New Name']);

    // Verify assignment is still active
    expect($asignacion->fresh()->isActiva())->toBeTrue();
});

test('observer only releases active assignments', function () {
    $paciente = Paciente::factory()->create(['estado' => PacienteEstado::ACTIVO]);
    $user = User::factory()->create();

    // Create one active and one already released assignment
    $asignacionActiva = AsignacionPaciente::factory()->create([
        'paciente_id' => $paciente->id,
        'fecha_hora_liberacion' => null,
    ]);

    $asignacionLiberada = AsignacionPaciente::factory()->create([
        'paciente_id' => $paciente->id,
        'fecha_hora_liberacion' => now()->subHours(2),
        'motivo_liberacion' => 'Fin de turno',
    ]);

    $this->actingAs($user);

    // Discharge patient
    $paciente->update(['estado' => PacienteEstado::DADO_ALTA]);

    // Verify only active assignment was released
    expect($asignacionActiva->fresh()->motivo_liberacion)->toBe('Alta del paciente')
        ->and($asignacionLiberada->fresh()->motivo_liberacion)->toBe('Fin de turno'); // Should not change
});
