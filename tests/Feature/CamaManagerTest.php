<?php

namespace Tests\Feature;

use App\Enums\CamaEstado;
use App\Enums\UserRole;
use App\Livewire\Admin\CamaManager;
use App\Models\Area;
use App\Models\Cama;
use App\Models\Cuarto;
use App\Models\Piso;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CamaManagerTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $enfermero;
    private Area $area;
    private Piso $piso;
    private Cuarto $cuarto;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role' => UserRole::ADMIN]);
        $this->enfermero = User::factory()->create(['role' => UserRole::ENFERMERO]);
        $this->area = Area::factory()->create();
        $this->piso = Piso::factory()->create(['area_id' => $this->area->id]);
        $this->cuarto = Cuarto::factory()->create(['piso_id' => $this->piso->id]);
    }

    /** @test */
    public function test_admin_puede_acceder_a_cama_manager()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.camas'));
        $response->assertStatus(200);
    }

    /** @test */
    public function test_no_admin_recibe_403()
    {
        $response = $this->actingAs($this->enfermero)->get(route('admin.camas'));
        $response->assertStatus(403);
    }

    /** @test */
    public function test_guest_redirige_a_login()
    {
        $response = $this->get(route('admin.camas'));
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function test_componente_se_renderiza_correctamente()
    {
        Livewire::actingAs($this->admin)
            ->test(CamaManager::class)
            ->assertStatus(200)
            ->assertSee('Gestión de Camas');
    }

    /** @test */
    public function test_lista_de_camas_se_muestra()
    {
        $cama = Cama::factory()->create([
            'cuarto_id' => $this->cuarto->id,
            'numero_cama' => 'A',
        ]);

        Livewire::actingAs($this->admin)
            ->test(CamaManager::class)
            ->assertSee('A');
    }

    /** @test */
    public function test_puede_crear_cama()
    {
        Livewire::actingAs($this->admin)
            ->test(CamaManager::class)
            ->set('cuarto_id', $this->cuarto->id)
            ->set('numero_cama', 'B')
            ->set('estado', 'libre')
            ->call('create')
            ->assertHasNoErrors()
            ->assertSet('showForm', false);

        $this->assertDatabaseHas('camas', [
            'cuarto_id' => $this->cuarto->id,
            'numero_cama' => 'B',
            'estado' => 'libre',
        ]);
    }

    /** @test */
    public function test_valida_campos_requeridos()
    {
        Livewire::actingAs($this->admin)
            ->test(CamaManager::class)
            ->set('cuarto_id', '')
            ->set('numero_cama', '')
            ->set('estado', '')
            ->call('create')
            ->assertHasErrors(['cuarto_id', 'numero_cama', 'estado']);
    }

    /** @test */
    public function test_valida_que_cuarto_exista()
    {
        Livewire::actingAs($this->admin)
            ->test(CamaManager::class)
            ->set('cuarto_id', 99999)
            ->set('numero_cama', 'C')
            ->set('estado', 'libre')
            ->call('create')
            ->assertHasErrors(['cuarto_id']);
    }

    /** @test */
    public function test_valida_estados_permitidos()
    {
        Livewire::actingAs($this->admin)
            ->test(CamaManager::class)
            ->set('cuarto_id', $this->cuarto->id)
            ->set('numero_cama', 'D')
            ->set('estado', 'invalido')
            ->call('create')
            ->assertHasErrors(['estado']);
    }

    /** @test */
    public function test_puede_editar_cama()
    {
        $cama = Cama::factory()->create([
            'cuarto_id' => $this->cuarto->id,
            'numero_cama' => 'E',
            'estado' => CamaEstado::LIBRE,
        ]);

        Livewire::actingAs($this->admin)
            ->test(CamaManager::class)
            ->call('edit', $cama->id)
            ->assertSet('editingCamaId', $cama->id)
            ->assertSet('numero_cama', 'E')
            ->set('numero_cama', 'F')
            ->call('update')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('camas', [
            'id' => $cama->id,
            'numero_cama' => 'F',
        ]);
    }

    /** @test */
    public function test_puede_eliminar_cama()
    {
        $cama = Cama::factory()->create([
            'cuarto_id' => $this->cuarto->id,
        ]);

        Livewire::actingAs($this->admin)
            ->test(CamaManager::class)
            ->call('delete', $cama->id);

        $this->assertDatabaseMissing('camas', ['id' => $cama->id]);
    }

    /** @test */
    public function test_puede_actualizar_estado_de_cama()
    {
        $cama = Cama::factory()->create([
            'cuarto_id' => $this->cuarto->id,
            'estado' => CamaEstado::LIBRE,
        ]);

        Livewire::actingAs($this->admin)
            ->test(CamaManager::class)
            ->call('updateEstado', $cama->id, 'ocupada');

        $this->assertDatabaseHas('camas', [
            'id' => $cama->id,
            'estado' => 'ocupada',
        ]);
    }

    /** @test */
    public function test_puede_cancelar_formulario()
    {
        Livewire::actingAs($this->admin)
            ->test(CamaManager::class)
            ->set('numero_cama', 'G')
            ->set('showForm', true)
            ->call('cancelEdit')
            ->assertSet('numero_cama', '')
            ->assertSet('showForm', false);
    }

    /** @test */
    public function test_puede_filtrar_camas_por_cuarto()
    {
        $cuarto2 = Cuarto::factory()->create(['piso_id' => $this->piso->id]);

        $cama1 = Cama::factory()->create([
            'cuarto_id' => $this->cuarto->id,
            'numero_cama' => 'CAMA-1',
        ]);

        $cama2 = Cama::factory()->create([
            'cuarto_id' => $cuarto2->id,
            'numero_cama' => 'CAMA-2',
        ]);

        // Sin filtro, ve ambas
        Livewire::actingAs($this->admin)
            ->test(CamaManager::class)
            ->assertSee('CAMA-1')
            ->assertSee('CAMA-2');

        // Con filtro, solo ve la del cuarto específico
        Livewire::actingAs($this->admin)
            ->test(CamaManager::class, ['cuarto_id' => $this->cuarto->id])
            ->assertSee('CAMA-1')
            ->assertDontSee('CAMA-2');
    }

    /** @test */
    public function test_cuartos_se_cargan_en_dropdown()
    {
        $cuarto2 = Cuarto::factory()->create([
            'piso_id' => $this->piso->id,
            'numero_cuarto' => '201',
        ]);

        $component = Livewire::actingAs($this->admin)
            ->test(CamaManager::class);

        // Verificar que se cargaron los cuartos
        $this->assertCount(2, $component->get('cuartos'));
    }

    /** @test */
    public function test_cama_se_muestra_con_relaciones_completas()
    {
        $cama = Cama::factory()->create([
            'cuarto_id' => $this->cuarto->id,
            'numero_cama' => 'J',
        ]);

        Livewire::actingAs($this->admin)
            ->test(CamaManager::class)
            ->assertSee('J')
            ->assertSee($this->cuarto->numero_cuarto)
            ->assertSee('Piso ' . $this->piso->numero_piso)
            ->assertSee($this->area->nombre);
    }
}