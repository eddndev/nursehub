<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Livewire\HospitalMap;
use App\Models\Area;
use App\Models\Cama;
use App\Models\Cuarto;
use App\Models\Piso;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class HospitalMapTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $coordinador;
    private User $enfermero;
    private Area $area;
    private Piso $piso;
    private Cuarto $cuarto;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role' => UserRole::ADMIN]);
        $this->coordinador = User::factory()->create(['role' => UserRole::COORDINADOR]);
        $this->enfermero = User::factory()->create(['role' => UserRole::ENFERMERO]);

        $this->area = Area::factory()->create();
        $this->piso = Piso::factory()->create(['area_id' => $this->area->id]);
        $this->cuarto = Cuarto::factory()->create(['piso_id' => $this->piso->id]);
    }

    /** @test */
    public function test_admin_puede_acceder_al_mapa()
    {
        $response = $this->actingAs($this->admin)->get(route('hospital.map'));
        $response->assertStatus(200);
    }

    /** @test */
    public function test_coordinador_puede_acceder_al_mapa()
    {
        $response = $this->actingAs($this->coordinador)->get(route('hospital.map'));
        $response->assertStatus(200);
    }

    /** @test */
    public function test_enfermero_no_puede_acceder_al_mapa()
    {
        $response = $this->actingAs($this->enfermero)->get(route('hospital.map'));
        $response->assertStatus(403);
    }

    /** @test */
    public function test_guest_redirige_a_login()
    {
        $response = $this->get(route('hospital.map'));
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function test_componente_se_renderiza_correctamente()
    {
        Livewire::actingAs($this->admin)
            ->test(HospitalMap::class)
            ->assertStatus(200)
            ->assertSee('Mapa del Hospital');
    }

    /** @test */
    public function test_muestra_estadisticas_generales()
    {
        Cama::factory()->count(3)->create(['cuarto_id' => $this->cuarto->id, 'estado' => 'libre']);
        Cama::factory()->count(2)->create(['cuarto_id' => $this->cuarto->id, 'estado' => 'ocupada']);

        Livewire::actingAs($this->admin)
            ->test(HospitalMap::class)
            ->assertSee('Total Áreas')
            ->assertSee('Total Camas')
            ->assertSee('Camas Libres')
            ->assertSee('Camas Ocupadas')
            ->assertSet('estadisticas.total_areas', 1)
            ->assertSet('estadisticas.total_camas', 5)
            ->assertSet('estadisticas.camas_libres', 3)
            ->assertSet('estadisticas.camas_ocupadas', 2);
    }

    /** @test */
    public function test_muestra_areas_con_pisos_y_cuartos()
    {
        Livewire::actingAs($this->admin)
            ->test(HospitalMap::class)
            ->assertSee($this->area->nombre)
            ->assertSee('Piso ' . $this->piso->numero_piso)
            ->assertSee('Cuarto ' . $this->cuarto->numero_cuarto);
    }

    /** @test */
    public function test_muestra_camas_con_estados()
    {
        $cama = Cama::factory()->create([
            'cuarto_id' => $this->cuarto->id,
            'numero_cama' => 'A',
            'estado' => 'libre',
        ]);

        Livewire::actingAs($this->admin)
            ->test(HospitalMap::class)
            ->assertSee('Cama A')
            ->assertSee('Libre');
    }

    /** @test */
    public function test_filtro_por_area_funciona()
    {
        $area2 = Area::factory()->create(['nombre' => 'UCI']);
        $piso2 = Piso::factory()->create(['area_id' => $area2->id]);

        $component = Livewire::actingAs($this->admin)
            ->test(HospitalMap::class)
            ->set('filtroArea', $this->area->id)
            ->call('aplicarFiltros');

        // Verificar que solo carga el área filtrada
        $this->assertEquals(1, $component->get('areas')->count());
        $this->assertEquals($this->area->id, $component->get('areas')->first()->id);
    }

    /** @test */
    public function test_filtro_por_estado_funciona()
    {
        Cama::factory()->create(['cuarto_id' => $this->cuarto->id, 'estado' => 'libre']);
        Cama::factory()->create(['cuarto_id' => $this->cuarto->id, 'estado' => 'ocupada']);

        $component = Livewire::actingAs($this->admin)
            ->test(HospitalMap::class)
            ->set('filtroEstado', 'libre')
            ->call('aplicarFiltros');

        // Verificar que solo muestra camas con estado 'libre'
        $camas = $component->get('areas')->first()->pisos->first()->cuartos->first()->camas;
        $this->assertEquals(1, $camas->count());
        $this->assertEquals('libre', $camas->first()->estado->value);
    }

    /** @test */
    public function test_solo_disponibles_filtra_camas_libres()
    {
        Cama::factory()->create(['cuarto_id' => $this->cuarto->id, 'estado' => 'libre']);
        Cama::factory()->create(['cuarto_id' => $this->cuarto->id, 'estado' => 'ocupada']);

        $component = Livewire::actingAs($this->admin)
            ->test(HospitalMap::class)
            ->set('soloDisponibles', true)
            ->call('aplicarFiltros');

        // Verificar que solo muestra camas libres
        $camas = $component->get('areas')->first()->pisos->first()->cuartos->first()->camas;
        $this->assertEquals(1, $camas->count());
        $this->assertEquals('libre', $camas->first()->estado->value);
    }

    /** @test */
    public function test_limpiar_filtros_resetea_todos_los_filtros()
    {
        Livewire::actingAs($this->admin)
            ->test(HospitalMap::class)
            ->set('filtroArea', $this->area->id)
            ->set('filtroEstado', 'libre')
            ->set('soloDisponibles', true)
            ->call('limpiarFiltros')
            ->assertSet('filtroArea', '')
            ->assertSet('filtroEstado', '')
            ->assertSet('soloDisponibles', false);
    }

    /** @test */
    public function test_calcula_porcentaje_de_ocupacion_correctamente()
    {
        Cama::factory()->count(7)->create(['cuarto_id' => $this->cuarto->id, 'estado' => 'libre']);
        Cama::factory()->count(3)->create(['cuarto_id' => $this->cuarto->id, 'estado' => 'ocupada']);

        $component = Livewire::actingAs($this->admin)
            ->test(HospitalMap::class);

        // 3 de 10 = 30%
        $this->assertEquals(30.0, $component->get('estadisticas.porcentaje_ocupacion'));
        $this->assertEquals(70.0, $component->get('estadisticas.porcentaje_disponibilidad'));
    }

    /** @test */
    public function test_muestra_mensaje_cuando_no_hay_resultados()
    {
        Livewire::actingAs($this->admin)
            ->test(HospitalMap::class)
            ->set('filtroArea', 99999)
            ->call('aplicarFiltros')
            ->assertSee('No se encontraron resultados');
    }

    /** @test */
    public function test_muestra_tipo_de_cuarto_correctamente()
    {
        $cuartoIndividual = Cuarto::factory()->create([
            'piso_id' => $this->piso->id,
            'tipo' => 'individual',
        ]);

        Livewire::actingAs($this->admin)
            ->test(HospitalMap::class)
            ->assertSee('Individual');
    }

    /** @test */
    public function test_cuenta_pisos_y_cuartos_por_area()
    {
        // Crear un piso adicional
        Piso::factory()->create(['area_id' => $this->area->id]);

        $component = Livewire::actingAs($this->admin)
            ->test(HospitalMap::class);

        // El área debe tener 2 pisos
        $this->assertEquals(2, $component->get('areas')->first()->pisos->count());
    }
}