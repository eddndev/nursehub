<?php

namespace Tests\Feature\Notifications;

use App\Enums\EstadoActividad;
use App\Enums\EstadoInscripcion;
use App\Jobs\EnviarRecordatoriosSesiones;
use App\Models\ActividadCapacitacion;
use App\Models\Area;
use App\Models\Certificacion;
use App\Models\Enfermero;
use App\Models\InscripcionCapacitacion;
use App\Models\SesionCapacitacion;
use App\Models\User;
use App\Notifications\CertificacionGeneradaNotification;
use App\Notifications\InscripcionAprobadaNotification;
use App\Notifications\InscripcionAutoservicioNotification;
use App\Notifications\InscripcionConfirmadaNotification;
use App\Notifications\RecordatorioSesionNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class NotificacionesCapacitacionTest extends TestCase
{
    use RefreshDatabase;

    protected User $enfermeroUser;
    protected User $coordinadorUser;
    protected Enfermero $enfermero;
    protected ActividadCapacitacion $actividad;
    protected InscripcionCapacitacion $inscripcion;

    protected function setUp(): void
    {
        parent::setUp();

        // Crear área
        $area = Area::factory()->create(['nombre' => 'UCI']);

        // Crear usuario enfermero
        $this->enfermeroUser = User::factory()->create([
            'name' => 'Juan Enfermero',
            'email' => 'enfermero@test.com',
        ]);

        // Crear enfermero
        $this->enfermero = Enfermero::factory()->create([
            'user_id' => $this->enfermeroUser->id,
            'area_fija_id' => $area->id,
        ]);

        // Crear usuario coordinador
        $this->coordinadorUser = User::factory()->create([
            'name' => 'Maria Coordinadora',
            'email' => 'coordinador@test.com',
        ]);

        // Crear actividad de capacitación
        $this->actividad = ActividadCapacitacion::factory()->create([
            'titulo' => 'Curso de RCP Avanzado',
            'estado' => EstadoActividad::INSCRIPCIONES_ABIERTAS->value,
            'fecha_inicio' => now()->addDays(7),
            'fecha_fin' => now()->addDays(14),
            'duracion_horas' => 8,
        ]);

        // Crear inscripción
        $this->inscripcion = InscripcionCapacitacion::factory()->create([
            'actividad_id' => $this->actividad->id,
            'enfermero_id' => $this->enfermero->id,
            'estado' => EstadoInscripcion::PENDIENTE->value,
            'inscrito_por' => $this->enfermeroUser->id,
        ]);
    }

    public function test_inscripcion_confirmada_notification_puede_enviarse()
    {
        Notification::fake();

        $this->enfermeroUser->notify(new InscripcionConfirmadaNotification($this->inscripcion));

        Notification::assertSentTo(
            $this->enfermeroUser,
            InscripcionConfirmadaNotification::class,
            function ($notification) {
                return $notification->toArray($this->enfermeroUser)['tipo'] === 'inscripcion_confirmada';
            }
        );
    }

    public function test_inscripcion_confirmada_notification_contiene_datos_correctos()
    {
        $notification = new InscripcionConfirmadaNotification($this->inscripcion);
        $data = $notification->toArray($this->enfermeroUser);

        $this->assertEquals($this->inscripcion->id, $data['inscripcion_id']);
        $this->assertEquals($this->actividad->id, $data['actividad_id']);
        $this->assertEquals('Curso de RCP Avanzado', $data['actividad_titulo']);
        $this->assertEquals('inscripcion_confirmada', $data['tipo']);
    }

    public function test_inscripcion_aprobada_notification_puede_enviarse()
    {
        Notification::fake();

        $this->inscripcion->update(['estado' => EstadoInscripcion::APROBADA->value]);
        $this->enfermeroUser->notify(new InscripcionAprobadaNotification($this->inscripcion));

        Notification::assertSentTo(
            $this->enfermeroUser,
            InscripcionAprobadaNotification::class,
            function ($notification) {
                return $notification->toArray($this->enfermeroUser)['tipo'] === 'inscripcion_aprobada';
            }
        );
    }

    public function test_inscripcion_autoservicio_notification_puede_enviarse_a_coordinadores()
    {
        Notification::fake();

        $this->coordinadorUser->notify(new InscripcionAutoservicioNotification($this->inscripcion));

        Notification::assertSentTo(
            $this->coordinadorUser,
            InscripcionAutoservicioNotification::class,
            function ($notification) {
                return $notification->toArray($this->coordinadorUser)['tipo'] === 'inscripcion_autoservicio';
            }
        );
    }

    public function test_certificacion_generada_notification_puede_enviarse()
    {
        Notification::fake();

        // Aprobar inscripción
        $this->inscripcion->update([
            'estado' => EstadoInscripcion::APROBADA->value,
            'porcentaje_asistencia' => 100,
        ]);

        // Crear certificación
        $certificacion = Certificacion::factory()->create([
            'inscripcion_id' => $this->inscripcion->id,
            'numero_certificado' => 'CERT-2024-001',
            'fecha_emision' => now(),
            'horas_certificadas' => 8,
            'emitido_por' => $this->coordinadorUser->id,
        ]);

        $this->enfermeroUser->notify(new CertificacionGeneradaNotification($certificacion));

        Notification::assertSentTo(
            $this->enfermeroUser,
            CertificacionGeneradaNotification::class,
            function ($notification) {
                return $notification->toArray($this->enfermeroUser)['tipo'] === 'certificacion_generada';
            }
        );
    }

    public function test_recordatorio_sesion_notification_puede_enviarse()
    {
        Notification::fake();

        // Crear sesión para mañana
        $sesion = SesionCapacitacion::factory()->create([
            'actividad_id' => $this->actividad->id,
            'numero_sesion' => 1,
            'titulo' => 'Introducción al RCP',
            'fecha' => now()->addDay(),
            'hora_inicio' => '09:00',
            'hora_fin' => '12:00',
            'ubicacion' => 'Aula 101',
        ]);

        $this->enfermeroUser->notify(new RecordatorioSesionNotification($sesion, $this->inscripcion));

        Notification::assertSentTo(
            $this->enfermeroUser,
            RecordatorioSesionNotification::class,
            function ($notification) {
                return $notification->toArray($this->enfermeroUser)['tipo'] === 'recordatorio_sesion';
            }
        );
    }

    public function test_job_enviar_recordatorios_sesiones_procesa_sesiones_de_manana()
    {
        Notification::fake();

        // Crear sesión para mañana
        $sesion = SesionCapacitacion::factory()->create([
            'actividad_id' => $this->actividad->id,
            'numero_sesion' => 1,
            'fecha' => now()->addDay(),
            'hora_inicio' => '09:00',
            'hora_fin' => '12:00',
        ]);

        // Aprobar inscripción para que reciba el recordatorio
        $this->inscripcion->update(['estado' => EstadoInscripcion::APROBADA->value]);

        // Ejecutar el job
        $job = new EnviarRecordatoriosSesiones();
        $job->handle();

        // Verificar que se envió el recordatorio
        Notification::assertSentTo(
            $this->enfermeroUser,
            RecordatorioSesionNotification::class
        );
    }

    public function test_job_no_envia_recordatorios_a_inscripciones_canceladas()
    {
        Notification::fake();

        // Crear sesión para mañana
        SesionCapacitacion::factory()->create([
            'actividad_id' => $this->actividad->id,
            'numero_sesion' => 1,
            'fecha' => now()->addDay(),
            'hora_inicio' => '09:00',
            'hora_fin' => '12:00',
        ]);

        // Cancelar inscripción
        $this->inscripcion->update(['estado' => EstadoInscripcion::CANCELADA->value]);

        // Ejecutar el job
        $job = new EnviarRecordatoriosSesiones();
        $job->handle();

        // Verificar que NO se envió el recordatorio
        Notification::assertNotSentTo(
            $this->enfermeroUser,
            RecordatorioSesionNotification::class
        );
    }

    public function test_notifications_usan_canales_mail_y_database()
    {
        $inscripcionConfirmada = new InscripcionConfirmadaNotification($this->inscripcion);
        $this->assertEquals(['mail', 'database'], $inscripcionConfirmada->via($this->enfermeroUser));

        $inscripcionAprobada = new InscripcionAprobadaNotification($this->inscripcion);
        $this->assertEquals(['mail', 'database'], $inscripcionAprobada->via($this->enfermeroUser));

        $autoservicio = new InscripcionAutoservicioNotification($this->inscripcion);
        $this->assertEquals(['mail', 'database'], $autoservicio->via($this->coordinadorUser));
    }

    public function test_notification_mail_contiene_subject_correcto()
    {
        $notification = new InscripcionConfirmadaNotification($this->inscripcion);
        $mail = $notification->toMail($this->enfermeroUser);

        $this->assertStringContainsString('Confirmacion de Inscripcion', $mail->subject);
        $this->assertStringContainsString('Curso de RCP Avanzado', $mail->subject);
    }
}
