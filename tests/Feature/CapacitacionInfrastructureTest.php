<?php

use App\Enums\EstadoActividad;
use App\Enums\EstadoInscripcion;
use App\Enums\TipoActividad;
use App\Enums\TipoInscripcion;
use App\Models\ActividadCapacitacion;
use App\Models\Area;
use App\Models\AsistenciaCapacitacion;
use App\Models\Certificacion;
use App\Models\Enfermero;
use App\Models\InscripcionCapacitacion;
use App\Models\SesionCapacitacion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// ===== ENUM TESTS =====

test('TipoActividad enum has all expected values', function () {
    $tipos = TipoActividad::cases();

    expect($tipos)->toHaveCount(8)
        ->and(TipoActividad::CURSO->getLabel())->toBe('Curso')
        ->and(TipoActividad::TALLER->getLabel())->toBe('Taller')
        ->and(TipoActividad::SEMINARIO->getLabel())->toBe('Seminario')
        ->and(TipoActividad::CURSO->getColor())->toBeString();
});

test('EstadoActividad enum has all expected values and methods', function () {
    expect(EstadoActividad::INSCRIPCIONES_ABIERTAS->isPuedeInscribirse())->toBeTrue()
        ->and(EstadoActividad::PLANIFICADA->isPuedeInscribirse())->toBeFalse()
        ->and(EstadoActividad::EN_CURSO->isActiva())->toBeTrue()
        ->and(EstadoActividad::COMPLETADA->isActiva())->toBeFalse();
});

test('EstadoInscripcion enum has all expected values and methods', function () {
    expect(EstadoInscripcion::APROBADA->isPuedeAsistir())->toBeTrue()
        ->and(EstadoInscripcion::PENDIENTE->isPuedeAsistir())->toBeFalse()
        ->and(EstadoInscripcion::PENDIENTE->isPendiente())->toBeTrue();
});

test('TipoInscripcion enum has all expected values and methods', function () {
    expect(TipoInscripcion::VOLUNTARIA->isPuedeRechazar())->toBeTrue()
        ->and(TipoInscripcion::OBLIGATORIA->isPuedeRechazar())->toBeFalse();
});

// ===== ACTIVIDAD CAPACITACION TESTS =====

test('can create actividad capacitacion', function () {
    $actividad = ActividadCapacitacion::factory()->create();

    expect($actividad)->toBeInstanceOf(ActividadCapacitacion::class)
        ->and($actividad->titulo)->toBeString()
        ->and($actividad->tipo)->toBeInstanceOf(TipoActividad::class)
        ->and($actividad->estado)->toBeInstanceOf(EstadoActividad::class);
});

test('actividad capacitacion has relationships', function () {
    $actividad = ActividadCapacitacion::factory()->create();

    expect($actividad->area)->toBeInstanceOf(Area::class)
        ->and($actividad->creadoPor)->toBeInstanceOf(User::class);
});

test('actividad capacitacion has correct scopes', function () {
    ActividadCapacitacion::factory()->create(['estado' => EstadoActividad::INSCRIPCIONES_ABIERTAS]);
    ActividadCapacitacion::factory()->create(['estado' => EstadoActividad::COMPLETADA]);

    expect(ActividadCapacitacion::activas()->count())->toBe(1)
        ->and(ActividadCapacitacion::inscripcionesAbiertas()->count())->toBe(1);
});

test('actividad capacitacion calculates cupos disponibles correctly', function () {
    $actividad = ActividadCapacitacion::factory()->create([
        'cupo_maximo' => 30,
    ]);

    // Crear 15 inscripciones aprobadas
    InscripcionCapacitacion::factory()->count(15)->create([
        'actividad_id' => $actividad->id,
        'estado' => EstadoInscripcion::APROBADA,
    ]);

    expect($actividad->fresh()->cupos_disponibles)->toBe(15)
        ->and($actividad->fresh()->total_inscritos)->toBe(15)
        ->and($actividad->fresh()->tieneCapoDisponible())->toBeTrue();
});

test('actividad capacitacion puedeInscribirse validates correctly', function () {
    $actividad = ActividadCapacitacion::factory()->create([
        'estado' => EstadoActividad::INSCRIPCIONES_ABIERTAS,
        'cupo_maximo' => 30,
        'fecha_limite_inscripcion' => now()->addDays(10),
    ]);

    expect($actividad->puedeInscribirse())->toBeTrue();

    // Cambiar estado
    $actividad->update(['estado' => EstadoActividad::COMPLETADA]);
    expect($actividad->puedeInscribirse())->toBeFalse();
});

// ===== SESION CAPACITACION TESTS =====

test('can create sesion capacitacion', function () {
    $sesion = SesionCapacitacion::factory()->create();

    expect($sesion)->toBeInstanceOf(SesionCapacitacion::class)
        ->and($sesion->actividad)->toBeInstanceOf(ActividadCapacitacion::class)
        ->and($sesion->titulo)->toBeString();
});

test('sesion capacitacion calculates statistics correctly', function () {
    $sesion = SesionCapacitacion::factory()->create();

    // Crear 10 asistencias, 8 presentes
    AsistenciaCapacitacion::factory()->count(8)->create([
        'sesion_id' => $sesion->id,
        'presente' => true,
    ]);

    AsistenciaCapacitacion::factory()->count(2)->create([
        'sesion_id' => $sesion->id,
        'presente' => false,
    ]);

    expect($sesion->total_asistentes)->toBe(8)
        ->and($sesion->total_inscritos)->toBe(10)
        ->and($sesion->porcentaje_asistencia)->toBe(80.0);
});

// ===== INSCRIPCION CAPACITACION TESTS =====

test('can create inscripcion capacitacion', function () {
    $inscripcion = InscripcionCapacitacion::factory()->create();

    expect($inscripcion)->toBeInstanceOf(InscripcionCapacitacion::class)
        ->and($inscripcion->actividad)->toBeInstanceOf(ActividadCapacitacion::class)
        ->and($inscripcion->enfermero)->toBeInstanceOf(Enfermero::class)
        ->and($inscripcion->tipo)->toBeInstanceOf(TipoInscripcion::class)
        ->and($inscripcion->estado)->toBeInstanceOf(EstadoInscripcion::class);
});

test('inscripcion can be approved', function () {
    $user = User::factory()->create();
    $inscripcion = InscripcionCapacitacion::factory()->create([
        'estado' => EstadoInscripcion::PENDIENTE,
    ]);

    $inscripcion->aprobar($user->id, 'Aprobada por cumplir requisitos');

    $inscripcion->refresh();

    expect($inscripcion->estado)->toBe(EstadoInscripcion::APROBADA)
        ->and($inscripcion->aprobado_por)->toBe($user->id)
        ->and($inscripcion->aprobado_at)->not->toBeNull()
        ->and($inscripcion->notas_aprobacion)->toBe('Aprobada por cumplir requisitos');
});

test('inscripcion can be rejected', function () {
    $user = User::factory()->create();
    $inscripcion = InscripcionCapacitacion::factory()->create([
        'estado' => EstadoInscripcion::PENDIENTE,
    ]);

    $inscripcion->rechazar($user->id, 'No cumple con los requisitos mínimos');

    $inscripcion->refresh();

    expect($inscripcion->estado)->toBe(EstadoInscripcion::RECHAZADA)
        ->and($inscripcion->rechazado_por)->toBe($user->id)
        ->and($inscripcion->motivo_rechazo)->toBe('No cumple con los requisitos mínimos');
});

test('inscripcion can be canceled', function () {
    $user = User::factory()->create();
    $inscripcion = InscripcionCapacitacion::factory()->create([
        'estado' => EstadoInscripcion::APROBADA,
    ]);

    $inscripcion->cancelar($user->id, 'Solicitada por el participante');

    $inscripcion->refresh();

    expect($inscripcion->estado)->toBe(EstadoInscripcion::CANCELADA)
        ->and($inscripcion->cancelado_por)->toBe($user->id)
        ->and($inscripcion->motivo_cancelacion)->toBe('Solicitada por el participante');
});

test('inscripcion calculates porcentaje asistencia correctly', function () {
    $actividad = ActividadCapacitacion::factory()->create();
    $inscripcion = InscripcionCapacitacion::factory()->create([
        'actividad_id' => $actividad->id,
    ]);

    // Crear 5 sesiones
    $sesiones = SesionCapacitacion::factory()->count(5)->create([
        'actividad_id' => $actividad->id,
    ]);

    // Crear asistencia a 4 de 5 sesiones
    foreach ($sesiones->take(4) as $sesion) {
        AsistenciaCapacitacion::factory()->create([
            'sesion_id' => $sesion->id,
            'inscripcion_id' => $inscripcion->id,
            'presente' => true,
        ]);
    }

    // Una ausencia
    AsistenciaCapacitacion::factory()->create([
        'sesion_id' => $sesiones->last()->id,
        'inscripcion_id' => $inscripcion->id,
        'presente' => false,
    ]);

    expect($inscripcion->calcularPorcentajeAsistencia())->toBe(80.0);
});

test('inscripcion validates asistencia minima correctly', function () {
    $actividad = ActividadCapacitacion::factory()->create([
        'porcentaje_asistencia_minimo' => 75.00,
    ]);

    $inscripcion = InscripcionCapacitacion::factory()->create([
        'actividad_id' => $actividad->id,
        'porcentaje_asistencia' => 80.00,
    ]);

    expect($inscripcion->cumpleAsistenciaMinima())->toBeTrue();

    $inscripcion->update(['porcentaje_asistencia' => 70.00]);
    expect($inscripcion->cumpleAsistenciaMinima())->toBeFalse();
});

test('inscripcion validates calificacion minima correctly', function () {
    $actividad = ActividadCapacitacion::factory()->create([
        'requiere_evaluacion' => true,
        'calificacion_minima_aprobacion' => 75.00,
    ]);

    $inscripcion = InscripcionCapacitacion::factory()->create([
        'actividad_id' => $actividad->id,
        'calificacion_evaluacion' => 85.00,
    ]);

    expect($inscripcion->cumpleCalificacionMinima())->toBeTrue();

    $inscripcion->update(['calificacion_evaluacion' => 70.00]);
    expect($inscripcion->cumpleCalificacionMinima())->toBeFalse();

    // Verificar que actividades sin requiere_evaluacion siempre cumplen
    $actividadSinEvaluacion = ActividadCapacitacion::factory()->create([
        'requiere_evaluacion' => false,
        'calificacion_minima_aprobacion' => 75.00,
    ]);

    $inscripcionSinRequisito = InscripcionCapacitacion::factory()->create([
        'actividad_id' => $actividadSinEvaluacion->id,
        'calificacion_evaluacion' => null,
    ]);

    expect($inscripcionSinRequisito->cumpleCalificacionMinima())->toBeTrue();
});

test('inscripcion puedeObtenerCertificado validates all requirements', function () {
    $actividad = ActividadCapacitacion::factory()->create([
        'otorga_certificado' => true,
        'porcentaje_asistencia_minimo' => 80.00,
        'requiere_evaluacion' => true,
        'calificacion_minima_aprobacion' => 75.00,
    ]);

    $inscripcion = InscripcionCapacitacion::factory()->create([
        'actividad_id' => $actividad->id,
        'estado' => EstadoInscripcion::APROBADA,
        'porcentaje_asistencia' => 85.00,
        'calificacion_evaluacion' => 80.00,
        'aprobado' => true,
    ]);

    expect($inscripcion->puedeObtenerCertificado())->toBeTrue();

    // No cumple asistencia
    $inscripcion->update(['porcentaje_asistencia' => 75.00]);
    expect($inscripcion->puedeObtenerCertificado())->toBeFalse();
});

// ===== ASISTENCIA CAPACITACION TESTS =====

test('can create asistencia capacitacion', function () {
    $asistencia = AsistenciaCapacitacion::factory()->create();

    expect($asistencia)->toBeInstanceOf(AsistenciaCapacitacion::class)
        ->and($asistencia->sesion)->toBeInstanceOf(SesionCapacitacion::class)
        ->and($asistencia->inscripcion)->toBeInstanceOf(InscripcionCapacitacion::class);
});

test('asistencia can mark presente', function () {
    $user = User::factory()->create();
    $asistencia = AsistenciaCapacitacion::factory()->create([
        'presente' => false,
    ]);

    $asistencia->marcarPresente($user->id, '08:30:00', 'Llegó puntual');

    $asistencia->refresh();

    expect($asistencia->presente)->toBeTrue()
        ->and($asistencia->hora_entrada)->toBe('08:30:00')
        ->and($asistencia->observaciones)->toBe('Llegó puntual')
        ->and($asistencia->registrado_por)->toBe($user->id);
});

test('asistencia calculates minutos asistidos on salida', function () {
    $asistencia = AsistenciaCapacitacion::factory()->create([
        'presente' => true,
        'hora_entrada' => '08:00:00',
        'hora_salida' => null,
    ]);

    $asistencia->marcarSalida('12:00:00');

    $asistencia->refresh();

    expect($asistencia->hora_salida)->toBe('12:00:00')
        ->and($asistencia->minutos_asistidos)->toBe(240);
});

// ===== CERTIFICACION TESTS =====

test('can create certificacion', function () {
    $certificacion = Certificacion::factory()->create();

    expect($certificacion)->toBeInstanceOf(Certificacion::class)
        ->and($certificacion->inscripcion)->toBeInstanceOf(InscripcionCapacitacion::class)
        ->and($certificacion->numero_certificado)->toBeString()
        ->and($certificacion->hash_verificacion)->toBeString();
});

test('certificacion generates unique numero certificado', function () {
    $numero1 = Certificacion::generarNumeroCertificado();

    // Create a certificate with the first number
    Certificacion::factory()->create([
        'numero_certificado' => $numero1,
    ]);

    $numero2 = Certificacion::generarNumeroCertificado();

    expect($numero1)->toStartWith('CERT-')
        ->and($numero1)->not->toBe($numero2)
        ->and($numero1)->toBe('CERT-2025-00001')
        ->and($numero2)->toBe('CERT-2025-00002');
});

test('certificacion generates hash verificacion', function () {
    $inscripcion = InscripcionCapacitacion::factory()->create();
    $numero = 'CERT-2025-00001';

    $hash = Certificacion::generarHashVerificacion($inscripcion, $numero);

    expect($hash)->toBeString()
        ->and(strlen($hash))->toBe(64); // SHA-256 produces 64 character hex string
});

test('certificacion can be revoked', function () {
    $user = User::factory()->create();
    $certificacion = Certificacion::factory()->create();

    expect($certificacion->estaVigente())->toBeTrue();

    $certificacion->revocar($user->id, 'Datos incorrectos');

    $certificacion->refresh();

    expect($certificacion->revocado_por)->toBe($user->id)
        ->and($certificacion->revocado_at)->not->toBeNull()
        ->and($certificacion->motivo_revocacion)->toBe('Datos incorrectos')
        ->and($certificacion->estaVigente())->toBeFalse();
});

test('certificacion validates vigencia correctly', function () {
    $certificacion = Certificacion::factory()->create([
        'fecha_vigencia_fin' => now()->addYear(),
    ]);

    expect($certificacion->estaVigente())->toBeTrue();

    $certificacion->update(['fecha_vigencia_fin' => now()->subDay()]);
    expect($certificacion->estaVigente())->toBeFalse();
});

test('certificacion has accessor methods for enfermero and actividad', function () {
    $certificacion = Certificacion::factory()->create();

    expect($certificacion->enfermero)->toBeInstanceOf(Enfermero::class)
        ->and($certificacion->actividad)->toBeInstanceOf(ActividadCapacitacion::class);
});
