<?php

namespace Tests\Feature\RCE;

use App\Livewire\Enfermeria\ExpedientePaciente;
use App\Models\Area;
use App\Models\Cama;
use App\Models\Cuarto;
use App\Models\Paciente;
use App\Models\Piso;
use App\Models\RegistroSignosVitales;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ExpedientePacienteTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Paciente $paciente;
    protected Cama $cama;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $area = Area::factory()->create(['nombre' => 'Medicina Interna']);
        $piso = Piso::factory()->create(['area_id' => $area->id, 'nombre' => 'Piso 3']);
        $cuarto = Cuarto::factory()->create(['piso_id' => $piso->id, 'numero' => '301']);
        $this->cama = Cama::factory()->create(['cuarto_id' => $cuarto->id, 'numero' => 'A']);

        $this->paciente = Paciente::factory()->create([
            'nombre' => 'Juan',
            'apellido_paterno' => 'Pérez',
            'apellido_materno' => 'García',
            'cama_actual_id' => $this->cama->id,
            'admitido_por' => $this->user->id,
        ]);
    }

    public function test_puede_acceder_a_expediente_de_paciente()
    {
        $this->actingAs($this->user)
            ->get(route('enfermeria.expediente', $this->paciente->id))
            ->assertOk();
    }

    public function test_guest_no_puede_acceder_a_expediente()
    {
        $this->get(route('enfermeria.expediente', $this->paciente->id))
            ->assertRedirect(route('login'));
    }

    public function test_componente_carga_correctamente()
    {
        Livewire::actingAs($this->user)
            ->test(ExpedientePaciente::class, ['id' => $this->paciente->id])
            ->assertOk()
            ->assertSet('paciente.id', $this->paciente->id);
    }

    public function test_muestra_informacion_basica_del_paciente()
    {
        Livewire::actingAs($this->user)
            ->test(ExpedientePaciente::class, ['id' => $this->paciente->id])
            ->assertSee('Juan Pérez García')
            ->assertSee($this->paciente->codigo_qr);
    }

    public function test_muestra_edad_del_paciente()
    {
        Livewire::actingAs($this->user)
            ->test(ExpedientePaciente::class, ['id' => $this->paciente->id])
            ->assertSee($this->paciente->edad . ' años');
    }

    public function test_muestra_ubicacion_actual()
    {
        Livewire::actingAs($this->user)
            ->test(ExpedientePaciente::class, ['id' => $this->paciente->id])
            ->assertSee('Medicina Interna')
            ->assertSee('Piso 3')
            ->assertSee('Cuarto 301')
            ->assertSee('Cama A');
    }

    public function test_muestra_alergias_si_existen()
    {
        $this->paciente->update(['alergias' => 'Penicilina, Polen']);

        Livewire::actingAs($this->user)
            ->test(ExpedientePaciente::class, ['id' => $this->paciente->id])
            ->assertSee('Alergias Conocidas')
            ->assertSee('Penicilina, Polen');
    }

    public function test_muestra_antecedentes_medicos_si_existen()
    {
        $this->paciente->update(['antecedentes_medicos' => 'Hipertensión']);

        Livewire::actingAs($this->user)
            ->test(ExpedientePaciente::class, ['id' => $this->paciente->id])
            ->assertSee('Antecedentes Médicos')
            ->assertSee('Hipertensión');
    }

    public function test_muestra_ultimo_registro_de_signos_vitales()
    {
        RegistroSignosVitales::factory()->create([
            'paciente_id' => $this->paciente->id,
            'presion_arterial' => '120/80',
            'frecuencia_cardiaca' => 75,
            'temperatura' => 36.5,
            'registrado_por' => $this->user->id,
            'fecha_registro' => now(),
        ]);

        Livewire::actingAs($this->user)
            ->test(ExpedientePaciente::class, ['id' => $this->paciente->id])
            ->assertSee('120/80')
            ->assertSee('75')
            ->assertSee('36.5');
    }

    public function test_muestra_mensaje_cuando_no_hay_signos_vitales()
    {
        Livewire::actingAs($this->user)
            ->test(ExpedientePaciente::class, ['id' => $this->paciente->id])
            ->assertSee('No hay registros de signos vitales');
    }

    public function test_muestra_historial_del_paciente()
    {
        $this->paciente->historial()->create([
            'tipo_evento' => 'Admisión',
            'descripcion' => 'Paciente admitido en urgencias',
            'usuario_id' => $this->user->id,
            'fecha_evento' => now(),
        ]);

        Livewire::actingAs($this->user)
            ->test(ExpedientePaciente::class, ['id' => $this->paciente->id])
            ->assertSee('Paciente admitido en urgencias');
    }

    public function test_muestra_quien_admitio_al_paciente()
    {
        Livewire::actingAs($this->user)
            ->test(ExpedientePaciente::class, ['id' => $this->paciente->id])
            ->assertSee($this->user->name);
    }

    public function test_muestra_estado_del_paciente()
    {
        Livewire::actingAs($this->user)
            ->test(ExpedientePaciente::class, ['id' => $this->paciente->id])
            ->assertSee($this->paciente->estado->getLabel());
    }

    public function test_carga_relaciones_necesarias_eficientemente()
    {
        // Crear datos relacionados
        RegistroSignosVitales::factory()->count(3)->create([
            'paciente_id' => $this->paciente->id,
            'registrado_por' => $this->user->id,
        ]);

        $this->paciente->historial()->create([
            'tipo_evento' => 'Test',
            'descripcion' => 'Test',
            'usuario_id' => $this->user->id,
            'fecha_evento' => now(),
        ]);

        // El componente debe cargar todas las relaciones sin N+1 queries
        $component = Livewire::actingAs($this->user)
            ->test(ExpedientePaciente::class, ['id' => $this->paciente->id]);

        $paciente = $component->get('paciente');

        $this->assertTrue($paciente->relationLoaded('camaActual'));
        $this->assertTrue($paciente->relationLoaded('registrosSignosVitales'));
        $this->assertTrue($paciente->relationLoaded('historial'));
        $this->assertTrue($paciente->relationLoaded('admitidoPor'));
    }

    public function test_se_actualiza_cuando_se_registran_signos_vitales()
    {
        $component = Livewire::actingAs($this->user)
            ->test(ExpedientePaciente::class, ['id' => $this->paciente->id]);

        // Crear un nuevo registro de signos vitales
        RegistroSignosVitales::factory()->create([
            'paciente_id' => $this->paciente->id,
            'temperatura' => 37.5,
            'registrado_por' => $this->user->id,
            'fecha_registro' => now(),
        ]);

        // Disparar el evento de refresco
        $component->dispatch('signos-vitales-registrados')
            ->call('refreshPaciente');

        // Verificar que el componente se actualizó
        $paciente = $component->get('paciente');
        $this->assertCount(1, $paciente->registrosSignosVitales);
    }

    public function test_boton_volver_redirige_a_lista_pacientes()
    {
        $this->actingAs($this->user)
            ->get(route('enfermeria.expediente', $this->paciente->id))
            ->assertSee(route('enfermeria.pacientes'));
    }

    public function test_muestra_fecha_de_admision()
    {
        Livewire::actingAs($this->user)
            ->test(ExpedientePaciente::class, ['id' => $this->paciente->id])
            ->assertSee($this->paciente->fecha_admision->format('d/m/Y'));
    }

    public function test_muestra_contacto_de_emergencia_si_existe()
    {
        $this->paciente->update([
            'contacto_emergencia_nombre' => 'María García',
            'contacto_emergencia_telefono' => '5551234567',
        ]);

        Livewire::actingAs($this->user)
            ->test(ExpedientePaciente::class, ['id' => $this->paciente->id])
            ->assertSee('María García')
            ->assertSee('5551234567');
    }

    public function test_muestra_curp_si_existe()
    {
        $this->paciente->update(['curp' => 'PEGG900515HDFRRC09']);

        Livewire::actingAs($this->user)
            ->test(ExpedientePaciente::class, ['id' => $this->paciente->id])
            ->assertSee('PEGG900515HDFRRC09');
    }

    public function test_error_404_si_paciente_no_existe()
    {
        $this->actingAs($this->user)
            ->get(route('enfermeria.expediente', 99999))
            ->assertNotFound();
    }
}