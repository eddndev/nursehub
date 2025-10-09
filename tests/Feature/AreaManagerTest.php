<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Livewire\Admin\AreaManager;
use App\Models\Area;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AreaManagerTest extends TestCase
{
    use RefreshDatabase;

    protected function actingAsAdmin()
    {
        $admin = User::factory()->create(['role' => UserRole::ADMIN]);
        return $this->actingAs($admin);
    }

    public function test_admin_can_access_area_manager_page(): void
    {
        $this->actingAsAdmin();

        $response = $this->get(route('admin.areas'));

        $response->assertOk();
    }

    public function test_non_admin_cannot_access_area_manager_page(): void
    {
        $user = User::factory()->create(['role' => UserRole::ENFERMERO]);

        $this->actingAs($user);

        $response = $this->get(route('admin.areas'));

        $response->assertStatus(403);
    }

    public function test_guest_cannot_access_area_manager_page(): void
    {
        $response = $this->get(route('admin.areas'));

        $response->assertRedirect('/login');
    }

    public function test_component_renders_successfully(): void
    {
        $this->actingAsAdmin();

        Livewire::test(AreaManager::class)
            ->assertOk()
            ->assertSee('Áreas del Hospital');
    }

    public function test_component_lists_areas(): void
    {
        $this->actingAsAdmin();

        $area = Area::factory()->create(['nombre' => 'Test Area 1']);

        Livewire::test(AreaManager::class)
            ->assertOk()
            ->assertSee('Test Area 1');
    }

    public function test_can_create_area(): void
    {
        $this->actingAsAdmin();

        Livewire::test(AreaManager::class)
            ->call('create')
            ->set('nombre', 'Urgencias')
            ->set('codigo', 'URG')
            ->set('descripcion', 'Área de urgencias')
            ->set('opera_24_7', true)
            ->set('ratio_enfermero_paciente', 1.5)
            ->set('requiere_certificacion', true)
            ->call('store')
            ->assertHasNoErrors()
            ->assertSet('showForm', false);

        $this->assertDatabaseHas('areas', [
            'nombre' => 'Urgencias',
            'codigo' => 'URG',
            'descripcion' => 'Área de urgencias',
        ]);
    }

    public function test_validates_required_fields(): void
    {
        $this->actingAsAdmin();

        Livewire::test(AreaManager::class)
            ->call('create')
            ->set('nombre', '')
            ->set('codigo', '')
            ->call('store')
            ->assertHasErrors(['nombre', 'codigo']);
    }

    public function test_validates_unique_nombre(): void
    {
        $this->actingAsAdmin();

        $area = Area::factory()->create(['nombre' => 'UCI']);

        Livewire::test(AreaManager::class)
            ->call('create')
            ->set('nombre', 'UCI')
            ->set('codigo', 'UNIQUE')
            ->call('store')
            ->assertHasErrors(['nombre']);
    }

    public function test_validates_unique_codigo(): void
    {
        $this->actingAsAdmin();

        $area = Area::factory()->create(['codigo' => 'URG']);

        Livewire::test(AreaManager::class)
            ->call('create')
            ->set('nombre', 'Unique Name')
            ->set('codigo', 'URG')
            ->call('store')
            ->assertHasErrors(['codigo']);
    }

    public function test_can_edit_area(): void
    {
        $this->actingAsAdmin();

        $area = Area::factory()->create([
            'nombre' => 'Original',
            'codigo' => 'ORIG',
        ]);

        Livewire::test(AreaManager::class)
            ->call('edit', $area->id)
            ->assertSet('isEditing', true)
            ->assertSet('editingAreaId', $area->id)
            ->assertSet('nombre', 'Original')
            ->assertSet('codigo', 'ORIG')
            ->set('nombre', 'Actualizado')
            ->set('codigo', 'ACT')
            ->call('update')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('areas', [
            'id' => $area->id,
            'nombre' => 'Actualizado',
            'codigo' => 'ACT',
        ]);
    }

    public function test_can_delete_area(): void
    {
        $this->actingAsAdmin();

        $area = Area::factory()->create();

        Livewire::test(AreaManager::class)
            ->call('delete', $area->id)
            ->assertHasNoErrors();

        $this->assertDatabaseMissing('areas', [
            'id' => $area->id,
        ]);
    }

    public function test_can_cancel_form(): void
    {
        $this->actingAsAdmin();

        Livewire::test(AreaManager::class)
            ->call('create')
            ->assertSet('showForm', true)
            ->set('nombre', 'Test')
            ->call('cancel')
            ->assertSet('showForm', false)
            ->assertSet('nombre', '');
    }

    public function test_validates_ratio_range(): void
    {
        $this->actingAsAdmin();

        Livewire::test(AreaManager::class)
            ->call('create')
            ->set('nombre', 'Test')
            ->set('codigo', 'TST')
            ->set('ratio_enfermero_paciente', 0) // Below minimum
            ->call('store')
            ->assertHasErrors(['ratio_enfermero_paciente']);

        Livewire::test(AreaManager::class)
            ->call('create')
            ->set('nombre', 'Test')
            ->set('codigo', 'TST')
            ->set('ratio_enfermero_paciente', 100) // Above maximum
            ->call('store')
            ->assertHasErrors(['ratio_enfermero_paciente']);
    }
}
