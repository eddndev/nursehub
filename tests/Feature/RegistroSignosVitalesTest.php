<?php

namespace Tests\Feature;

use App\Enums\EstadoPaciente;
use App\Enums\NivelTriage;
use App\Livewire\Enfermeria\RegistroSignosVitales;
use App\Models\Area;
use App\Models\Cama;
use App\Models\Cuarto;
use App\Models\Paciente;
use App\Models\Piso;
use App\Models\RegistroSignosVitales as RegistroSignosVitalesModel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class RegistroSignosVitalesTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Paciente $paciente;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        // Crear estructura para el paciente
        $area = Area::factory()->create();
        $piso = Piso::factory()->create(['area_id' => $area->id]);
        $cuarto = Cuarto::factory()->create(['piso_id' => $piso->id]);
        $cama = Cama::factory()->create(['cuarto_id' => $cuarto->id]);

        $this->paciente = Paciente::factory()->create([
            'cama_actual_id' => $cama->id,
            'admitido_por' => $this->user->id,
        ]);
    }

    /** @test */
    public function puede_abrir_modal_de_registro()
    {
        Livewire::actingAs($this->user)
            ->test(RegistroSignosVitales::class, ['pacienteId' => $this->paciente->id])
            ->call('abrirModal')
            ->assertSet('modalOpen', true);
    }

    /** @test */
    public function puede_cerrar_modal_y_resetear_formulario()
    {
        Livewire::actingAs($this->user)
            ->test(RegistroSignosVitales::class, ['pacienteId' => $this->paciente->id])
            ->call('abrirModal')
            ->set('frecuencia_cardiaca', 80)
            ->set('temperatura', 37.5)
            ->call('cerrarModal')
            ->assertSet('modalOpen', false)
            ->assertSet('frecuencia_cardiaca', '')
            ->assertSet('temperatura', '');
    }

    /** @test */
    public function puede_registrar_signos_vitales_completos()
    {
        Livewire::actingAs($this->user)
            ->test(RegistroSignosVitales::class, ['pacienteId' => $this->paciente->id])
            ->call('abrirModal')
            ->set('presion_arterial_sistolica', 120)
            ->set('presion_arterial_diastolica', 80)
            ->set('frecuencia_cardiaca', 75)
            ->set('frecuencia_respiratoria', 18)
            ->set('temperatura', 36.8)
            ->set('saturacion_oxigeno', 98)
            ->set('glucosa', 95)
            ->set('observaciones', 'Paciente estable')
            ->call('guardarRegistro')
            ->assertHasNoErrors()
            ->assertSet('modalOpen', false);

        $this->assertDatabaseHas('registros_signos_vitales', [
            'paciente_id' => $this->paciente->id,
            'presion_arterial' => '120/80',
            'frecuencia_cardiaca' => 75,
            'frecuencia_respiratoria' => 18,
            'temperatura' => 36.8,
            'saturacion_oxigeno' => 98,
            'glucosa' => 95,
            'observaciones' => 'Paciente estable',
            'registrado_por' => $this->user->id,
        ]);
    }

    /** @test */
    public function puede_registrar_solo_algunos_signos_vitales()
    {
        Livewire::actingAs($this->user)
            ->test(RegistroSignosVitales::class, ['pacienteId' => $this->paciente->id])
            ->call('abrirModal')
            ->set('temperatura', 37.2)
            ->set('saturacion_oxigeno', 97)
            ->call('guardarRegistro')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('registros_signos_vitales', [
            'paciente_id' => $this->paciente->id,
            'temperatura' => 37.2,
            'saturacion_oxigeno' => 97,
        ]);
    }

    /** @test */
    public function valida_que_haya_al_menos_un_signo_vital()
    {
        Livewire::actingAs($this->user)
            ->test(RegistroSignosVitales::class, ['pacienteId' => $this->paciente->id])
            ->call('abrirModal')
            ->call('guardarRegistro')
            ->assertHasErrors(['general']);
    }

    /** @test */
    public function valida_presion_arterial_completa()
    {
        Livewire::actingAs($this->user)
            ->test(RegistroSignosVitales::class, ['pacienteId' => $this->paciente->id])
            ->call('abrirModal')
            ->set('presion_arterial_sistolica', 120)
            ->call('guardarRegistro')
            ->assertHasErrors(['presion_arterial_sistolica', 'presion_arterial_diastolica']);
    }

    /** @test */
    public function valida_que_sistolica_sea_mayor_que_diastolica()
    {
        Livewire::actingAs($this->user)
            ->test(RegistroSignosVitales::class, ['pacienteId' => $this->paciente->id])
            ->call('abrirModal')
            ->set('presion_arterial_sistolica', 80)
            ->set('presion_arterial_diastolica', 120)
            ->call('guardarRegistro')
            ->assertHasErrors(['presion_arterial_sistolica']);
    }

    /** @test */
    public function valida_rangos_de_frecuencia_cardiaca()
    {
        Livewire::actingAs($this->user)
            ->test(RegistroSignosVitales::class, ['pacienteId' => $this->paciente->id])
            ->call('abrirModal')
            ->set('frecuencia_cardiaca', 10) // Muy bajo
            ->assertHasErrors(['frecuencia_cardiaca'])
            ->set('frecuencia_cardiaca', 300) // Muy alto
            ->assertHasErrors(['frecuencia_cardiaca'])
            ->set('frecuencia_cardiaca', 75) // Normal
            ->assertHasNoErrors(['frecuencia_cardiaca']);
    }

    /** @test */
    public function valida_rangos_de_temperatura()
    {
        Livewire::actingAs($this->user)
            ->test(RegistroSignosVitales::class, ['pacienteId' => $this->paciente->id])
            ->call('abrirModal')
            ->set('temperatura', 25) // Muy bajo
            ->assertHasErrors(['temperatura'])
            ->set('temperatura', 50) // Muy alto
            ->assertHasErrors(['temperatura'])
            ->set('temperatura', 36.5) // Normal
            ->assertHasNoErrors(['temperatura']);
    }

    /** @test */
    public function valida_rangos_de_saturacion_oxigeno()
    {
        Livewire::actingAs($this->user)
            ->test(RegistroSignosVitales::class, ['pacienteId' => $this->paciente->id])
            ->call('abrirModal')
            ->set('saturacion_oxigeno', 40) // Muy bajo
            ->assertHasErrors(['saturacion_oxigeno'])
            ->set('saturacion_oxigeno', 110) // Muy alto
            ->assertHasErrors(['saturacion_oxigeno'])
            ->set('saturacion_oxigeno', 98) // Normal
            ->assertHasNoErrors(['saturacion_oxigeno']);
    }

    /** @test */
    public function calcula_triage_automaticamente()
    {
        $component = Livewire::actingAs($this->user)
            ->test(RegistroSignosVitales::class, ['pacienteId' => $this->paciente->id])
            ->call('abrirModal')
            ->set('presion_arterial_sistolica', 180) // Alta
            ->set('presion_arterial_diastolica', 100)
            ->set('frecuencia_cardiaca', 140) // Alta
            ->set('temperatura', 39.5); // Fiebre alta

        $this->assertNotNull($component->get('triage_calculado'));
        $this->assertInstanceOf(NivelTriage::class, $component->get('triage_calculado'));
    }

    /** @test */
    public function puede_hacer_override_manual_de_triage()
    {
        Livewire::actingAs($this->user)
            ->test(RegistroSignosVitales::class, ['pacienteId' => $this->paciente->id])
            ->call('abrirModal')
            ->set('temperatura', 36.5) // Normal
            ->call('overrideTriage', NivelTriage::ROJO->value)
            ->assertSet('nivel_triage', NivelTriage::ROJO->value)
            ->assertSet('triage_override', true);
    }

    /** @test */
    public function puede_volver_a_usar_triage_calculado_despues_de_override()
    {
        $component = Livewire::actingAs($this->user)
            ->test(RegistroSignosVitales::class, ['pacienteId' => $this->paciente->id])
            ->call('abrirModal')
            ->set('temperatura', 36.5)
            ->set('frecuencia_cardiaca', 75);

        $triageCalculado = $component->get('triage_calculado');

        $component
            ->call('overrideTriage', NivelTriage::ROJO->value)
            ->assertSet('triage_override', true)
            ->call('usarTriageCalculado')
            ->assertSet('triage_override', false)
            ->assertSet('nivel_triage', $triageCalculado->value);
    }

    /** @test */
    public function guarda_triage_override_en_la_base_de_datos()
    {
        Livewire::actingAs($this->user)
            ->test(RegistroSignosVitales::class, ['pacienteId' => $this->paciente->id])
            ->call('abrirModal')
            ->set('temperatura', 36.5)
            ->call('overrideTriage', NivelTriage::NARANJA->value)
            ->call('guardarRegistro');

        $this->assertDatabaseHas('registros_signos_vitales', [
            'paciente_id' => $this->paciente->id,
            'nivel_triage' => NivelTriage::NARANJA->value,
            'triage_override' => true,
        ]);
    }

    /** @test */
    public function crea_entrada_en_historial_al_registrar()
    {
        Livewire::actingAs($this->user)
            ->test(RegistroSignosVitales::class, ['pacienteId' => $this->paciente->id])
            ->call('abrirModal')
            ->set('presion_arterial_sistolica', 120)
            ->set('presion_arterial_diastolica', 80)
            ->set('frecuencia_cardiaca', 75)
            ->call('guardarRegistro');

        $this->assertDatabaseHas('historial_pacientes', [
            'paciente_id' => $this->paciente->id,
            'tipo_evento' => 'Registro de Signos Vitales',
            'usuario_id' => $this->user->id,
        ]);

        $historial = $this->paciente->historial()->latest()->first();
        $this->assertStringContainsString('P/A: 120/80', $historial->descripcion);
        $this->assertStringContainsString('FC: 75', $historial->descripcion);
    }

    /** @test */
    public function emite_evento_despues_de_guardar()
    {
        Livewire::actingAs($this->user)
            ->test(RegistroSignosVitales::class, ['pacienteId' => $this->paciente->id])
            ->call('abrirModal')
            ->set('temperatura', 36.5)
            ->call('guardarRegistro')
            ->assertDispatched('signos-vitales-registrados');
    }

    /** @test */
    public function valida_observaciones_maximo_500_caracteres()
    {
        Livewire::actingAs($this->user)
            ->test(RegistroSignosVitales::class, ['pacienteId' => $this->paciente->id])
            ->call('abrirModal')
            ->set('temperatura', 36.5)
            ->set('observaciones', str_repeat('a', 501))
            ->assertHasErrors(['observaciones']);
    }
}
