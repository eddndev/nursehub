<?php

namespace Tests\Feature\RCE;

use App\Enums\EstadoPaciente;
use App\Enums\NivelTriage;
use App\Livewire\Enfermeria\ListaPacientes;
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

class ListaPacientesTest extends TestCase
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
        $this->area = Area::factory()->create();
        $this->piso = Piso::factory()->create(['area_id' => $this->area->id]);
        $this->cuarto = Cuarto::factory()->create(['piso_id' => $this->piso->id]);
        $this->cama = Cama::factory()->create(['cuarto_id' => $this->cuarto->id]);
    }

    /** @test */
    public function puede_acceder_a_lista_de_pacientes()
    {
        $this->actingAs($this->user)
            ->get(route('enfermeria.pacientes'))
            ->assertOk();
    }

    /** @test */
    public function guest_no_puede_acceder_a_lista()
    {
        $this->get(route('enfermeria.pacientes'))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function componente_carga_correctamente()
    {
        Livewire::actingAs($this->user)
            ->test(ListaPacientes::class)
            ->assertOk()
            ->assertSee('Lista de Pacientes');
    }

    /** @test */
    public function muestra_pacientes_activos()
    {
        Paciente::factory()->create([
            'nombre' => 'Juan',
            'apellido_paterno' => 'Pérez',
            'estado' => EstadoPaciente::ACTIVO,
            'cama_actual_id' => $this->cama->id,
            'admitido_por' => $this->user->id,
        ]);

        Livewire::actingAs($this->user)
            ->test(ListaPacientes::class)
            ->assertSee('Juan Pérez');
    }

    /** @test */
    public function puede_buscar_por_nombre()
    {
        Paciente::factory()->create([
            'nombre' => 'María',
            'apellido_paterno' => 'López',
            'cama_actual_id' => $this->cama->id,
            'admitido_por' => $this->user->id,
        ]);

        Paciente::factory()->create([
            'nombre' => 'Carlos',
            'apellido_paterno' => 'García',
            'cama_actual_id' => $this->cama->id,
            'admitido_por' => $this->user->id,
        ]);

        Livewire::actingAs($this->user)
            ->test(ListaPacientes::class)
            ->set('search', 'María')
            ->assertSee('María López')
            ->assertDontSee('Carlos García');
    }

    /** @test */
    public function puede_buscar_por_codigo_qr()
    {
        $paciente = Paciente::factory()->create([
            'nombre' => 'Ana',
            'codigo_qr' => 'ABC1234567',
            'cama_actual_id' => $this->cama->id,
            'admitido_por' => $this->user->id,
        ]);

        Livewire::actingAs($this->user)
            ->test(ListaPacientes::class)
            ->set('search', 'ABC1234567')
            ->assertSee('Ana');
    }

    /** @test */
    public function puede_buscar_por_curp()
    {
        Paciente::factory()->create([
            'nombre' => 'Pedro',
            'curp' => 'PEGG900515HDFRRC09',
            'cama_actual_id' => $this->cama->id,
            'admitido_por' => $this->user->id,
        ]);

        Livewire::actingAs($this->user)
            ->test(ListaPacientes::class)
            ->set('search', 'PEGG900515')
            ->assertSee('Pedro');
    }

    /** @test */
    public function puede_filtrar_por_nivel_triage()
    {
        $paciente1 = Paciente::factory()->create([
            'nombre' => 'Urgente',
            'cama_actual_id' => $this->cama->id,
            'admitido_por' => $this->user->id,
        ]);

        RegistroSignosVitales::factory()->create([
            'paciente_id' => $paciente1->id,
            'nivel_triage' => NivelTriage::ROJO,
            'registrado_por' => $this->user->id,
        ]);

        $paciente2 = Paciente::factory()->create([
            'nombre' => 'NoUrgente',
            'cama_actual_id' => $this->cama->id,
            'admitido_por' => $this->user->id,
        ]);

        RegistroSignosVitales::factory()->create([
            'paciente_id' => $paciente2->id,
            'nivel_triage' => NivelTriage::VERDE,
            'registrado_por' => $this->user->id,
        ]);

        Livewire::actingAs($this->user)
            ->test(ListaPacientes::class)
            ->set('filtroTriage', NivelTriage::ROJO->value)
            ->assertSee('Urgente')
            ->assertDontSee('NoUrgente');
    }

    /** @test */
    public function puede_filtrar_por_estado()
    {
        Paciente::factory()->create([
            'nombre' => 'Activo',
            'estado' => EstadoPaciente::ACTIVO,
            'cama_actual_id' => $this->cama->id,
            'admitido_por' => $this->user->id,
        ]);

        Paciente::factory()->create([
            'nombre' => 'Alta',
            'estado' => EstadoPaciente::ALTA,
            'cama_actual_id' => $this->cama->id,
            'admitido_por' => $this->user->id,
        ]);

        Livewire::actingAs($this->user)
            ->test(ListaPacientes::class)
            ->set('filtroEstado', EstadoPaciente::ACTIVO->value)
            ->assertSee('Activo')
            ->assertDontSee('Alta');
    }

    /** @test */
    public function ordena_por_prioridad_triage()
    {
        // Crear pacientes con diferentes niveles de TRIAGE
        $pacienteVerde = Paciente::factory()->create([
            'nombre' => 'Verde',
            'cama_actual_id' => $this->cama->id,
            'admitido_por' => $this->user->id,
        ]);

        RegistroSignosVitales::factory()->create([
            'paciente_id' => $pacienteVerde->id,
            'nivel_triage' => NivelTriage::VERDE,
            'registrado_por' => $this->user->id,
            'fecha_registro' => now(),
        ]);

        $pacienteRojo = Paciente::factory()->create([
            'nombre' => 'Rojo',
            'cama_actual_id' => $this->cama->id,
            'admitido_por' => $this->user->id,
        ]);

        RegistroSignosVitales::factory()->create([
            'paciente_id' => $pacienteRojo->id,
            'nivel_triage' => NivelTriage::ROJO,
            'registrado_por' => $this->user->id,
            'fecha_registro' => now(),
        ]);

        $component = Livewire::actingAs($this->user)
            ->test(ListaPacientes::class);

        // El paciente Rojo debe aparecer primero
        $html = $component->get('pacientes')->items();
        $this->assertEquals('Rojo', $html[0]->nombre);
    }

    /** @test */
    public function muestra_estadisticas_de_pacientes()
    {
        Paciente::factory()->count(3)->create([
            'estado' => EstadoPaciente::ACTIVO,
            'cama_actual_id' => $this->cama->id,
            'admitido_por' => $this->user->id,
        ]);

        Paciente::factory()->create([
            'estado' => EstadoPaciente::ALTA,
            'cama_actual_id' => $this->cama->id,
            'admitido_por' => $this->user->id,
        ]);

        Livewire::actingAs($this->user)
            ->test(ListaPacientes::class)
            ->assertSee('Total: 4')
            ->assertSee('Activos: 3');
    }

    /** @test */
    public function muestra_nivel_triage_con_colores()
    {
        $paciente = Paciente::factory()->create([
            'nombre' => 'Test',
            'cama_actual_id' => $this->cama->id,
            'admitido_por' => $this->user->id,
        ]);

        RegistroSignosVitales::factory()->create([
            'paciente_id' => $paciente->id,
            'nivel_triage' => NivelTriage::ROJO,
            'registrado_por' => $this->user->id,
        ]);

        Livewire::actingAs($this->user)
            ->test(ListaPacientes::class)
            ->assertSee('Rojo - Resucitación');
    }

    /** @test */
    public function muestra_indicador_de_triage_override()
    {
        $paciente = Paciente::factory()->create([
            'cama_actual_id' => $this->cama->id,
            'admitido_por' => $this->user->id,
        ]);

        RegistroSignosVitales::factory()->create([
            'paciente_id' => $paciente->id,
            'nivel_triage' => NivelTriage::NARANJA,
            'triage_override' => true,
            'registrado_por' => $this->user->id,
        ]);

        Livewire::actingAs($this->user)
            ->test(ListaPacientes::class)
            ->assertSee('TRIAGE modificado manualmente');
    }

    /** @test */
    public function muestra_ubicacion_del_paciente()
    {
        $this->area->update(['nombre' => 'Urgencias']);
        $this->piso->update(['nombre' => 'Piso 1']);
        $this->cuarto->update(['numero' => '101']);
        $this->cama->update(['numero' => 'A']);

        Paciente::factory()->create([
            'nombre' => 'Test',
            'cama_actual_id' => $this->cama->id,
            'admitido_por' => $this->user->id,
        ]);

        Livewire::actingAs($this->user)
            ->test(ListaPacientes::class)
            ->assertSee('Urgencias')
            ->assertSee('Piso 1')
            ->assertSee('101')
            ->assertSee('A');
    }

    /** @test */
    public function muestra_signos_vitales_recientes()
    {
        $paciente = Paciente::factory()->create([
            'cama_actual_id' => $this->cama->id,
            'admitido_por' => $this->user->id,
        ]);

        RegistroSignosVitales::factory()->create([
            'paciente_id' => $paciente->id,
            'presion_arterial' => '120/80',
            'frecuencia_cardiaca' => 75,
            'temperatura' => 36.5,
            'registrado_por' => $this->user->id,
            'fecha_registro' => now(),
        ]);

        Livewire::actingAs($this->user)
            ->test(ListaPacientes::class)
            ->assertSee('120/80')
            ->assertSee('75')
            ->assertSee('36.5');
    }

    /** @test */
    public function paginacion_funciona_correctamente()
    {
        Paciente::factory()->count(25)->create([
            'cama_actual_id' => $this->cama->id,
            'admitido_por' => $this->user->id,
        ]);

        $component = Livewire::actingAs($this->user)
            ->test(ListaPacientes::class);

        // Debe mostrar 20 pacientes por página
        $this->assertCount(20, $component->get('pacientes')->items());
    }

    /** @test */
    public function resetea_paginacion_al_buscar()
    {
        Paciente::factory()->count(25)->create([
            'cama_actual_id' => $this->cama->id,
            'admitido_por' => $this->user->id,
        ]);

        Paciente::factory()->create([
            'nombre' => 'Especial',
            'cama_actual_id' => $this->cama->id,
            'admitido_por' => $this->user->id,
        ]);

        Livewire::actingAs($this->user)
            ->test(ListaPacientes::class)
            ->set('page', 2) // Ir a página 2
            ->set('search', 'Especial') // Buscar debe resetear a página 1
            ->assertSee('Especial');
    }

    /** @test */
    public function enlace_a_expediente_funciona()
    {
        $paciente = Paciente::factory()->create([
            'cama_actual_id' => $this->cama->id,
            'admitido_por' => $this->user->id,
        ]);

        $this->actingAs($this->user)
            ->get(route('enfermeria.pacientes'))
            ->assertSee(route('enfermeria.expediente', $paciente->id));
    }

    /** @test */
    public function muestra_tiempo_desde_admision()
    {
        Paciente::factory()->create([
            'nombre' => 'Reciente',
            'fecha_admision' => now()->subHours(2),
            'cama_actual_id' => $this->cama->id,
            'admitido_por' => $this->user->id,
        ]);

        Livewire::actingAs($this->user)
            ->test(ListaPacientes::class)
            ->assertSee('hace 2 horas');
    }

    /** @test */
    public function parametros_url_persisten_filtros()
    {
        $component = Livewire::actingAs($this->user)
            ->test(ListaPacientes::class)
            ->set('search', 'test')
            ->set('filtroTriage', NivelTriage::ROJO->value);

        // Los parámetros deben reflejarse en la URL
        $this->assertEquals('test', $component->get('search'));
        $this->assertEquals(NivelTriage::ROJO->value, $component->get('filtroTriage'));
    }

    /** @test */
    public function boton_admitir_paciente_visible()
    {
        $this->actingAs($this->user)
            ->get(route('enfermeria.pacientes'))
            ->assertSee('Admitir Paciente')
            ->assertSee(route('urgencias.admision'));
    }

    /** @test */
    public function mensaje_cuando_no_hay_pacientes()
    {
        Livewire::actingAs($this->user)
            ->test(ListaPacientes::class)
            ->set('search', 'NoExiste')
            ->assertSee('No se encontraron pacientes');
    }
}
