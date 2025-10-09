<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Livewire\Admin\PisoManager;
use App\Models\Area;
use App\Models\Piso;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class PisoManagerTest extends TestCase
{
    use RefreshDatabase;

    protected function actingAsAdmin()
    {
        $admin = User::factory()->create(['role' => UserRole::ADMIN]);
        return $this->actingAs($admin);
    }

    public function test_admin_can_access_piso_manager_page(): void
    {
        $this->actingAsAdmin();

        $response = $this->get(route('admin.pisos'));

        $response->assertOk();
    }

    public function test_non_admin_cannot_access_piso_manager_page(): void
    {
        $user = User::factory()->create(['role' => UserRole::ENFERMERO]);

        $this->actingAs($user);

        $response = $this->get(route('admin.pisos'));

        $response->assertStatus(403);
    }

    public function test_guest_cannot_access_piso_manager_page(): void
    {
        $response = $this->get(route('admin.pisos'));

        $response->assertRedirect('/login');
    }

    public function test_component_renders_successfully(): void
    {
        $this->actingAsAdmin();

        Livewire::test(PisoManager::class)
            ->assertOk()
            ->assertSee('Pisos del Hospital');
    }

    public function test_component_lists_pisos(): void
    {
        $this->actingAsAdmin();

        $area = Area::factory()->create();
        $piso = Piso::factory()->create([
            'nombre' => 'Test Piso 1',
            'area_id' => $area->id,
        ]);

        Livewire::test(PisoManager::class)
            ->assertOk()
            ->assertSee('Test Piso 1');
    }

    public function test_can_create_piso(): void
    {
        $this->actingAsAdmin();

        $area = Area::factory()->create();

        Livewire::test(PisoManager::class)
            ->call('create')
            ->set('nombre', 'Piso Cirugía General')
            ->set('numero_piso', 3)
            ->set('area_id', $area->id)
            ->set('especialidad', 'Cirugía General')
            ->call('store')
            ->assertHasNoErrors()
            ->assertSet('showForm', false);

        $this->assertDatabaseHas('pisos', [
            'nombre' => 'Piso Cirugía General',
            'numero_piso' => 3,
            'area_id' => $area->id,
            'especialidad' => 'Cirugía General',
        ]);
    }

    public function test_validates_required_fields(): void
    {
        $this->actingAsAdmin();

        Livewire::test(PisoManager::class)
            ->call('create')
            ->set('nombre', '')
            ->set('numero_piso', '')
            ->set('area_id', '')
            ->call('store')
            ->assertHasErrors(['nombre', 'numero_piso', 'area_id']);
    }

    public function test_validates_area_exists(): void
    {
        $this->actingAsAdmin();

        Livewire::test(PisoManager::class)
            ->call('create')
            ->set('nombre', 'Test Piso')
            ->set('numero_piso', 1)
            ->set('area_id', 99999) // Non-existent area
            ->call('store')
            ->assertHasErrors(['area_id']);
    }

    public function test_validates_numero_piso_range(): void
    {
        $this->actingAsAdmin();

        $area = Area::factory()->create();

        Livewire::test(PisoManager::class)
            ->call('create')
            ->set('nombre', 'Test')
            ->set('numero_piso', 0) // Below minimum
            ->set('area_id', $area->id)
            ->call('store')
            ->assertHasErrors(['numero_piso']);

        Livewire::test(PisoManager::class)
            ->call('create')
            ->set('nombre', 'Test')
            ->set('numero_piso', 51) // Above maximum
            ->set('area_id', $area->id)
            ->call('store')
            ->assertHasErrors(['numero_piso']);
    }

    public function test_can_edit_piso(): void
    {
        $this->actingAsAdmin();

        $area = Area::factory()->create();
        $piso = Piso::factory()->create([
            'nombre' => 'Original',
            'numero_piso' => 1,
            'area_id' => $area->id,
        ]);

        Livewire::test(PisoManager::class)
            ->call('edit', $piso->id)
            ->assertSet('isEditing', true)
            ->assertSet('editingPisoId', $piso->id)
            ->assertSet('nombre', 'Original')
            ->assertSet('numero_piso', 1)
            ->set('nombre', 'Actualizado')
            ->set('numero_piso', 2)
            ->call('update')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('pisos', [
            'id' => $piso->id,
            'nombre' => 'Actualizado',
            'numero_piso' => 2,
        ]);
    }

    public function test_can_delete_piso(): void
    {
        $this->actingAsAdmin();

        $area = Area::factory()->create();
        $piso = Piso::factory()->create(['area_id' => $area->id]);

        Livewire::test(PisoManager::class)
            ->call('delete', $piso->id)
            ->assertHasNoErrors();

        $this->assertDatabaseMissing('pisos', [
            'id' => $piso->id,
        ]);
    }

    public function test_can_cancel_form(): void
    {
        $this->actingAsAdmin();

        Livewire::test(PisoManager::class)
            ->call('create')
            ->assertSet('showForm', true)
            ->set('nombre', 'Test')
            ->call('cancel')
            ->assertSet('showForm', false)
            ->assertSet('nombre', '');
    }

    public function test_areas_are_loaded_for_dropdown(): void
    {
        $this->actingAsAdmin();

        $area1 = Area::factory()->create([
            'nombre' => 'Área Test 1',
            'codigo' => 'AT1'
        ]);
        $area2 = Area::factory()->create([
            'nombre' => 'Área Test 2',
            'codigo' => 'AT2'
        ]);

        Livewire::test(PisoManager::class)
            ->call('create')
            ->assertSee('Área Test 1')
            ->assertSee('Área Test 2');
    }

    public function test_piso_displays_with_area_relationship(): void
    {
        $this->actingAsAdmin();

        $area = Area::factory()->create(['nombre' => 'UCI']);
        $piso = Piso::factory()->create([
            'nombre' => 'Piso UCI 3',
            'area_id' => $area->id,
        ]);

        Livewire::test(PisoManager::class)
            ->assertSee('Piso UCI 3')
            ->assertSee('UCI');
    }
}
