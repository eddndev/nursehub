<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Livewire\Admin\CuartoManager;
use App\Models\Area;
use App\Models\Cuarto;
use App\Models\Piso;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CuartoManagerTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $enfermero;
    private Area $area;
    private Piso $piso;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role' => UserRole::ADMIN]);
        $this->enfermero = User::factory()->create(['role' => UserRole::ENFERMERO]);
        $this->area = Area::factory()->create();
        $this->piso = Piso::factory()->create(['area_id' => $this->area->id]);
    }

    /** @test */
    public function test_admin_puede_acceder_a_cuarto_manager()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.cuartos'));
        $response->assertStatus(200);
    }

    /** @test */
    public function test_no_admin_recibe_403()
    {
        $response = $this->actingAs($this->enfermero)->get(route('admin.cuartos'));
        $response->assertStatus(403);
    }

    /** @test */
    public function test_guest_redirige_a_login()
    {
        $response = $this->get(route('admin.cuartos'));
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function test_componente_se_renderiza_correctamente()
    {
        Livewire::actingAs($this->admin)
            ->test(CuartoManager::class)
            ->assertStatus(200)
            ->assertSee('Gestión de Cuartos');
    }

    /** @test */
    public function test_lista_de_cuartos_se_muestra()
    {
        $cuarto = Cuarto::factory()->create([
            'piso_id' => $this->piso->id,
            'numero_cuarto' => '301',
        ]);

        Livewire::actingAs($this->admin)
            ->test(CuartoManager::class)
            ->assertSee('301');
    }

    /** @test */
    public function test_puede_crear_cuarto()
    {
        Livewire::actingAs($this->admin)
            ->test(CuartoManager::class)
            ->set('piso_id', $this->piso->id)
            ->set('numero_cuarto', '401')
            ->set('tipo', 'individual')
            ->call('create')
            ->assertHasNoErrors()
            ->assertSet('showForm', false);

        $this->assertDatabaseHas('cuartos', [
            'piso_id' => $this->piso->id,
            'numero_cuarto' => '401',
            'tipo' => 'individual',
        ]);
    }

    /** @test */
    public function test_valida_campos_requeridos()
    {
        Livewire::actingAs($this->admin)
            ->test(CuartoManager::class)
            ->set('piso_id', '')
            ->set('numero_cuarto', '')
            ->set('tipo', '')
            ->call('create')
            ->assertHasErrors(['piso_id', 'numero_cuarto', 'tipo']);
    }

    /** @test */
    public function test_valida_que_piso_exista()
    {
        Livewire::actingAs($this->admin)
            ->test(CuartoManager::class)
            ->set('piso_id', 99999)
            ->set('numero_cuarto', '501')
            ->set('tipo', 'individual')
            ->call('create')
            ->assertHasErrors(['piso_id']);
    }

    /** @test */
    public function test_valida_tipos_permitidos()
    {
        Livewire::actingAs($this->admin)
            ->test(CuartoManager::class)
            ->set('piso_id', $this->piso->id)
            ->set('numero_cuarto', '601')
            ->set('tipo', 'invalido')
            ->call('create')
            ->assertHasErrors(['tipo']);
    }

    /** @test */
    public function test_puede_editar_cuarto()
    {
        $cuarto = Cuarto::factory()->create([
            'piso_id' => $this->piso->id,
            'numero_cuarto' => '701',
            'tipo' => 'individual',
        ]);

        Livewire::actingAs($this->admin)
            ->test(CuartoManager::class)
            ->call('edit', $cuarto->id)
            ->assertSet('editingCuartoId', $cuarto->id)
            ->assertSet('numero_cuarto', '701')
            ->set('numero_cuarto', '702')
            ->call('update')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('cuartos', [
            'id' => $cuarto->id,
            'numero_cuarto' => '702',
        ]);
    }

    /** @test */
    public function test_puede_eliminar_cuarto()
    {
        $cuarto = Cuarto::factory()->create([
            'piso_id' => $this->piso->id,
        ]);

        Livewire::actingAs($this->admin)
            ->test(CuartoManager::class)
            ->call('delete', $cuarto->id);

        $this->assertDatabaseMissing('cuartos', ['id' => $cuarto->id]);
    }

    /** @test */
    public function test_puede_cancelar_formulario()
    {
        Livewire::actingAs($this->admin)
            ->test(CuartoManager::class)
            ->set('numero_cuarto', '801')
            ->set('showForm', true)
            ->call('cancelEdit')
            ->assertSet('numero_cuarto', '')
            ->assertSet('showForm', false);
    }

    /** @test */
    public function test_pisos_se_cargan_en_dropdown()
    {
        $piso2 = Piso::factory()->create([
            'area_id' => $this->area->id,
            'numero_piso' => 5,
        ]);

        $component = Livewire::actingAs($this->admin)
            ->test(CuartoManager::class);

        // Verificar que se cargaron los pisos
        $this->assertCount(2, $component->get('pisos'));
    }

    /** @test */
    public function test_cuarto_se_muestra_con_relacion_de_piso_y_area()
    {
        $cuarto = Cuarto::factory()->create([
            'piso_id' => $this->piso->id,
            'numero_cuarto' => '901',
        ]);

        Livewire::actingAs($this->admin)
            ->test(CuartoManager::class)
            ->assertSee('901')
            ->assertSee('Piso ' . $this->piso->numero_piso)
            ->assertSee($this->area->nombre);
    }
}