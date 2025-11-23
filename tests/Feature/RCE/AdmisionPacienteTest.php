<?php

namespace Tests\Feature\RCE;

use App\Enums\EstadoPaciente;
use App\Enums\NivelTriage;
use App\Livewire\Urgencias\AdmisionPaciente;
use App\Models\Area;
use App\Models\Cama;
use App\Models\Cuarto;
use App\Models\Paciente;
use App\Models\Piso;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AdmisionPacienteTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Area $area;
    protected Piso $piso;
    protected Cuarto $cuarto;
    protected Cama $cama;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->area = Area::factory()->create(['nombre' => 'Urgencias']);
        $this->piso = Piso::factory()->create(['area_id' => $this->area->id]);
        $this->cuarto = Cuarto::factory()->create(['piso_id' => $this->piso->id]);
        $this->cama = Cama::factory()->create([
            'cuarto_id' => $this->cuarto->id,
            'disponible' => true,
        ]);
    }

    /** @test */
    public function admin_puede_acceder_a_admision()
    {
        $this->actingAs($this->user)
            ->get(route('urgencias.admision'))
            ->assertOk();
    }

    /** @test */
    public function guest_no_puede_acceder_a_admision()
    {
        $this->get(route('urgencias.admision'))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function componente_carga_correctamente()
    {
        Livewire::actingAs($this->user)
            ->test(AdmisionPaciente::class)
            ->assertOk()
            ->assertSee('Admisión de Paciente');
    }

    /** @test */
    public function puede_admitir_paciente_con_datos_minimos()
    {
        Livewire::actingAs($this->user)
            ->test(AdmisionPaciente::class)
            ->set('nombre', 'Juan')
            ->set('apellido_paterno', 'Pérez')
            ->set('apellido_materno', 'García')
            ->set('fecha_nacimiento', '1990-05-15')
            ->set('sexo', 'M')
            ->set('cama_id', $this->cama->id)
            ->call('admitirPaciente')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('pacientes', [
            'nombre' => 'Juan',
            'apellido_paterno' => 'Pérez',
            'apellido_materno' => 'García',
            'sexo' => 'M',
            'cama_actual_id' => $this->cama->id,
            'estado' => EstadoPaciente::ACTIVO->value,
            'admitido_por' => $this->user->id,
        ]);
    }

    /** @test */
    public function genera_codigo_qr_unico_al_admitir()
    {
        Livewire::actingAs($this->user)
            ->test(AdmisionPaciente::class)
            ->set('nombre', 'María')
            ->set('apellido_paterno', 'López')
            ->set('fecha_nacimiento', '1985-03-20')
            ->set('sexo', 'F')
            ->set('cama_id', $this->cama->id)
            ->call('admitirPaciente');

        $paciente = Paciente::where('nombre', 'María')->first();

        $this->assertNotNull($paciente->codigo_qr);
        $this->assertEquals(10, strlen($paciente->codigo_qr));
        $this->assertMatchesRegularExpression('/^[A-Z0-9]{10}$/', $paciente->codigo_qr);
    }

    /** @test */
    public function valida_campos_requeridos()
    {
        Livewire::actingAs($this->user)
            ->test(AdmisionPaciente::class)
            ->call('admitirPaciente')
            ->assertHasErrors([
                'nombre',
                'apellido_paterno',
                'fecha_nacimiento',
                'sexo',
                'cama_id',
            ]);
    }

    /** @test */
    public function valida_formato_de_curp()
    {
        Livewire::actingAs($this->user)
            ->test(AdmisionPaciente::class)
            ->set('curp', 'INVALIDO')
            ->assertHasErrors(['curp'])
            ->set('curp', 'PEGG900515HDFRRC09')
            ->assertHasNoErrors(['curp']);
    }

    /** @test */
    public function valida_fecha_nacimiento_no_futura()
    {
        Livewire::actingAs($this->user)
            ->test(AdmisionPaciente::class)
            ->set('fecha_nacimiento', now()->addDays(1)->format('Y-m-d'))
            ->assertHasErrors(['fecha_nacimiento']);
    }

    /** @test */
    public function valida_email_formato()
    {
        Livewire::actingAs($this->user)
            ->test(AdmisionPaciente::class)
            ->set('email', 'correo-invalido')
            ->assertHasErrors(['email'])
            ->set('email', 'valido@example.com')
            ->assertHasNoErrors(['email']);
    }

    /** @test */
    public function puede_registrar_signos_vitales_iniciales()
    {
        Livewire::actingAs($this->user)
            ->test(AdmisionPaciente::class)
            ->set('nombre', 'Carlos')
            ->set('apellido_paterno', 'Ramírez')
            ->set('fecha_nacimiento', '1995-08-10')
            ->set('sexo', 'M')
            ->set('cama_id', $this->cama->id)
            ->set('presion_arterial_sistolica', 120)
            ->set('presion_arterial_diastolica', 80)
            ->set('frecuencia_cardiaca', 75)
            ->set('temperatura', 36.5)
            ->call('admitirPaciente');

        $paciente = Paciente::where('nombre', 'Carlos')->first();

        $this->assertDatabaseHas('registros_signos_vitales', [
            'paciente_id' => $paciente->id,
            'presion_arterial' => '120/80',
            'frecuencia_cardiaca' => 75,
            'temperatura' => 36.5,
        ]);
    }

    /** @test */
    public function calcula_triage_automaticamente_con_signos_vitales()
    {
        Livewire::actingAs($this->user)
            ->test(AdmisionPaciente::class)
            ->set('nombre', 'Ana')
            ->set('apellido_paterno', 'Torres')
            ->set('fecha_nacimiento', '1988-12-05')
            ->set('sexo', 'F')
            ->set('cama_id', $this->cama->id)
            ->set('presion_arterial_sistolica', 180)
            ->set('presion_arterial_diastolica', 110)
            ->set('frecuencia_cardiaca', 140)
            ->set('temperatura', 39.5)
            ->call('admitirPaciente');

        $paciente = Paciente::where('nombre', 'Ana')->first();
        $registro = $paciente->registrosSignosVitales->first();

        $this->assertNotNull($registro->nivel_triage);
        $this->assertInstanceOf(NivelTriage::class, $registro->nivel_triage);
    }

    /** @test */
    public function crea_entrada_en_historial_al_admitir()
    {
        Livewire::actingAs($this->user)
            ->test(AdmisionPaciente::class)
            ->set('nombre', 'Luis')
            ->set('apellido_paterno', 'Hernández')
            ->set('fecha_nacimiento', '1992-07-25')
            ->set('sexo', 'M')
            ->set('cama_id', $this->cama->id)
            ->call('admitirPaciente');

        $paciente = Paciente::where('nombre', 'Luis')->first();

        $this->assertDatabaseHas('historial_pacientes', [
            'paciente_id' => $paciente->id,
            'tipo_evento' => 'Admisión',
            'usuario_id' => $this->user->id,
        ]);
    }

    /** @test */
    public function marca_cama_como_ocupada_al_admitir()
    {
        $this->assertTrue($this->cama->disponible);

        Livewire::actingAs($this->user)
            ->test(AdmisionPaciente::class)
            ->set('nombre', 'Pedro')
            ->set('apellido_paterno', 'Sánchez')
            ->set('fecha_nacimiento', '1980-11-30')
            ->set('sexo', 'M')
            ->set('cama_id', $this->cama->id)
            ->call('admitirPaciente');

        $this->cama->refresh();
        $this->assertFalse($this->cama->disponible);
    }

    /** @test */
    public function no_puede_asignar_cama_ocupada()
    {
        $this->cama->update(['disponible' => false]);

        Livewire::actingAs($this->user)
            ->test(AdmisionPaciente::class)
            ->set('nombre', 'Elena')
            ->set('apellido_paterno', 'Martínez')
            ->set('fecha_nacimiento', '1993-04-18')
            ->set('sexo', 'F')
            ->set('cama_id', $this->cama->id)
            ->call('admitirPaciente')
            ->assertHasErrors(['cama_id']);
    }

    /** @test */
    public function puede_guardar_alergias()
    {
        Livewire::actingAs($this->user)
            ->test(AdmisionPaciente::class)
            ->set('nombre', 'Rosa')
            ->set('apellido_paterno', 'Flores')
            ->set('fecha_nacimiento', '1987-09-12')
            ->set('sexo', 'F')
            ->set('cama_id', $this->cama->id)
            ->set('alergias', 'Penicilina, Polen')
            ->call('admitirPaciente');

        $this->assertDatabaseHas('pacientes', [
            'nombre' => 'Rosa',
            'alergias' => 'Penicilina, Polen',
        ]);
    }

    /** @test */
    public function puede_guardar_antecedentes_medicos()
    {
        Livewire::actingAs($this->user)
            ->test(AdmisionPaciente::class)
            ->set('nombre', 'Jorge')
            ->set('apellido_paterno', 'Castro')
            ->set('fecha_nacimiento', '1975-02-28')
            ->set('sexo', 'M')
            ->set('cama_id', $this->cama->id)
            ->set('antecedentes_medicos', 'Hipertensión, Diabetes tipo 2')
            ->call('admitirPaciente');

        $this->assertDatabaseHas('pacientes', [
            'nombre' => 'Jorge',
            'antecedentes_medicos' => 'Hipertensión, Diabetes tipo 2',
        ]);
    }

    /** @test */
    public function puede_guardar_contacto_emergencia()
    {
        Livewire::actingAs($this->user)
            ->test(AdmisionPaciente::class)
            ->set('nombre', 'Laura')
            ->set('apellido_paterno', 'Vega')
            ->set('fecha_nacimiento', '1991-06-14')
            ->set('sexo', 'F')
            ->set('cama_id', $this->cama->id)
            ->set('contacto_emergencia_nombre', 'Roberto Vega')
            ->set('contacto_emergencia_telefono', '5551234567')
            ->call('admitirPaciente');

        $this->assertDatabaseHas('pacientes', [
            'nombre' => 'Laura',
            'contacto_emergencia_nombre' => 'Roberto Vega',
            'contacto_emergencia_telefono' => '5551234567',
        ]);
    }

    /** @test */
    public function valida_presion_arterial_completa()
    {
        Livewire::actingAs($this->user)
            ->test(AdmisionPaciente::class)
            ->set('presion_arterial_sistolica', 120)
            ->assertHasErrors(['presion_arterial_diastolica']);
    }

    /** @test */
    public function valida_que_sistolica_mayor_que_diastolica()
    {
        Livewire::actingAs($this->user)
            ->test(AdmisionPaciente::class)
            ->set('presion_arterial_sistolica', 80)
            ->set('presion_arterial_diastolica', 120)
            ->call('admitirPaciente')
            ->assertHasErrors(['presion_arterial_sistolica']);
    }

    /** @test */
    public function resetea_formulario_despues_de_admitir()
    {
        $component = Livewire::actingAs($this->user)
            ->test(AdmisionPaciente::class)
            ->set('nombre', 'Miguel')
            ->set('apellido_paterno', 'Ruiz')
            ->set('fecha_nacimiento', '1989-10-22')
            ->set('sexo', 'M')
            ->set('cama_id', $this->cama->id)
            ->call('admitirPaciente');

        // El formulario debe estar vacío después de la admisión
        $this->assertEquals('', $component->get('nombre'));
        $this->assertEquals('', $component->get('apellido_paterno'));
    }

    /** @test */
    public function puede_admitir_paciente_sin_apellido_materno()
    {
        Livewire::actingAs($this->user)
            ->test(AdmisionPaciente::class)
            ->set('nombre', 'Sofía')
            ->set('apellido_paterno', 'Mendoza')
            ->set('apellido_materno', '')
            ->set('fecha_nacimiento', '1994-01-08')
            ->set('sexo', 'F')
            ->set('cama_id', $this->cama->id)
            ->call('admitirPaciente')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('pacientes', [
            'nombre' => 'Sofía',
            'apellido_paterno' => 'Mendoza',
            'apellido_materno' => null,
        ]);
    }

    /** @test */
    public function calcula_edad_correctamente()
    {
        Livewire::actingAs($this->user)
            ->test(AdmisionPaciente::class)
            ->set('nombre', 'Alberto')
            ->set('apellido_paterno', 'Reyes')
            ->set('fecha_nacimiento', now()->subYears(45)->format('Y-m-d'))
            ->set('sexo', 'M')
            ->set('cama_id', $this->cama->id)
            ->call('admitirPaciente');

        $paciente = Paciente::where('nombre', 'Alberto')->first();
        $this->assertEquals(45, $paciente->edad);
    }

    /** @test */
    public function redirige_a_expediente_despues_de_admitir()
    {
        $component = Livewire::actingAs($this->user)
            ->test(AdmisionPaciente::class)
            ->set('nombre', 'Carmen')
            ->set('apellido_paterno', 'Silva')
            ->set('fecha_nacimiento', '1986-03-17')
            ->set('sexo', 'F')
            ->set('cama_id', $this->cama->id)
            ->call('admitirPaciente');

        $paciente = Paciente::where('nombre', 'Carmen')->first();
        $component->assertRedirect(route('enfermeria.expediente', $paciente->id));
    }
}
