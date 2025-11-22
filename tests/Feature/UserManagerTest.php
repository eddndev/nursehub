<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Livewire\Admin\UserManager;
use App\Models\Area;
use App\Models\Enfermero;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class UserManagerTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $coordinador;
    private User $enfermero;
    private Area $area;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role' => UserRole::ADMIN]);
        $this->coordinador = User::factory()->create(['role' => UserRole::COORDINADOR]);
        $this->enfermero = User::factory()->create(['role' => UserRole::ENFERMERO]);
        $this->area = Area::factory()->create();
    }

    /** @test */
    public function test_admin_puede_acceder_a_user_manager()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.users'));
        $response->assertStatus(200);
    }

    /** @test */
    public function test_coordinador_puede_acceder_a_user_manager()
    {
        $response = $this->actingAs($this->coordinador)->get(route('admin.users'));
        $response->assertStatus(200);
    }

    /** @test */
    public function test_no_admin_o_coordinador_recibe_403()
    {
        $response = $this->actingAs($this->enfermero)->get(route('admin.users'));
        $response->assertStatus(403);
    }

    /** @test */
    public function test_guest_redirige_a_login()
    {
        $response = $this->get(route('admin.users'));
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function test_componente_se_renderiza_correctamente()
    {
        Livewire::actingAs($this->admin)
            ->test(UserManager::class)
            ->assertStatus(200)
            ->assertSee('Gestión de Usuarios');
    }

    /** @test */
    public function test_lista_de_usuarios_se_muestra()
    {
        $user = User::factory()->create(['name' => 'Juan Pérez']);

        Livewire::actingAs($this->admin)
            ->test(UserManager::class)
            ->assertSee('Juan Pérez');
    }

    /** @test */
    public function test_puede_crear_usuario_simple()
    {
        Livewire::actingAs($this->admin)
            ->test(UserManager::class)
            ->set('name', 'Nuevo Usuario')
            ->set('email', 'nuevo@nursehub.com')
            ->set('password', 'password123')
            ->set('role', 'admin')
            ->set('is_active', true)
            ->call('create')
            ->assertHasNoErrors()
            ->assertSet('showForm', false);

        $this->assertDatabaseHas('users', [
            'name' => 'Nuevo Usuario',
            'email' => 'nuevo@nursehub.com',
            'role' => 'admin',
            'is_active' => true,
        ]);
    }

    /** @test */
    public function test_puede_crear_usuario_enfermero_con_perfil()
    {
        Livewire::actingAs($this->admin)
            ->test(UserManager::class)
            ->set('name', 'Enfermero Nuevo')
            ->set('email', 'enfermero.nuevo@nursehub.com')
            ->set('password', 'password123')
            ->set('role', 'enfermero')
            ->set('cedula_profesional', '12345678')
            ->set('tipo_asignacion', 'fijo')
            ->set('area_fija_id', $this->area->id)
            ->set('anos_experiencia', 5)
            ->call('create')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('users', [
            'email' => 'enfermero.nuevo@nursehub.com',
            'role' => 'enfermero',
        ]);

        $this->assertDatabaseHas('enfermeros', [
            'cedula_profesional' => '12345678',
            'tipo_asignacion' => 'fijo',
            'area_fija_id' => $this->area->id,
            'anos_experiencia' => 5,
        ]);
    }

    /** @test */
    public function test_valida_campos_requeridos()
    {
        Livewire::actingAs($this->admin)
            ->test(UserManager::class)
            ->set('name', '')
            ->set('email', '')
            ->set('password', '')
            ->set('role', 'admin') // Usar rol válido pero campos vacíos
            ->call('create')
            ->assertHasErrors(['name', 'email', 'password']);
    }

    /** @test */
    public function test_valida_email_unico()
    {
        $existingUser = User::factory()->create(['email' => 'existing@nursehub.com', 'role' => UserRole::ADMIN]);

        Livewire::actingAs($this->admin)
            ->test(UserManager::class)
            ->set('name', 'Test User')
            ->set('email', 'existing@nursehub.com')
            ->set('password', 'password123')
            ->set('role', 'admin')
            ->call('create')
            ->assertHasErrors(['email']);
    }

    /** @test */
    public function test_valida_password_minimo()
    {
        Livewire::actingAs($this->admin)
            ->test(UserManager::class)
            ->set('name', 'Test User')
            ->set('email', 'test@nursehub.com')
            ->set('password', '123')
            ->set('role', 'admin')
            ->call('create')
            ->assertHasErrors(['password']);
    }

    /** @test */
    public function test_valida_cedula_requerida_para_enfermero()
    {
        Livewire::actingAs($this->admin)
            ->test(UserManager::class)
            ->set('name', 'Enfermero Test')
            ->set('email', 'enfermero.test@nursehub.com')
            ->set('password', 'password123')
            ->set('role', 'enfermero')
            ->set('cedula_profesional', '')
            ->set('tipo_asignacion', 'rotativo')
            ->call('create')
            ->assertHasErrors(['cedula_profesional']);
    }

    /** @test */
    public function test_valida_area_fija_requerida_para_enfermero_fijo()
    {
        Livewire::actingAs($this->admin)
            ->test(UserManager::class)
            ->set('name', 'Enfermero Fijo')
            ->set('email', 'enfermero.fijo@nursehub.com')
            ->set('password', 'password123')
            ->set('role', 'enfermero')
            ->set('cedula_profesional', '11111111')
            ->set('tipo_asignacion', 'fijo')
            ->set('area_fija_id', '')
            ->call('create')
            ->assertHasErrors(['area_fija_id']);
    }

    /** @test */
    public function test_valida_cedula_unica()
    {
        $enfermerExistente = User::factory()->create(['role' => UserRole::ENFERMERO]);
        Enfermero::factory()->create([
            'user_id' => $enfermerExistente->id,
            'cedula_profesional' => '99999999',
        ]);

        Livewire::actingAs($this->admin)
            ->test(UserManager::class)
            ->set('name', 'Otro Enfermero')
            ->set('email', 'otro@nursehub.com')
            ->set('password', 'password123')
            ->set('role', 'enfermero')
            ->set('cedula_profesional', '99999999')
            ->set('tipo_asignacion', 'rotativo')
            ->call('create')
            ->assertHasErrors(['cedula_profesional']);
    }

    /** @test */
    public function test_puede_editar_usuario()
    {
        $user = User::factory()->create([
            'name' => 'Usuario Original',
            'email' => 'original@nursehub.com',
            'role' => UserRole::COORDINADOR,
        ]);

        Livewire::actingAs($this->admin)
            ->test(UserManager::class)
            ->call('edit', $user->id)
            ->assertSet('editingUserId', $user->id)
            ->assertSet('name', 'Usuario Original')
            ->assertSet('email', 'original@nursehub.com')
            ->set('name', 'Usuario Modificado')
            ->call('update')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Usuario Modificado',
        ]);
    }

    /** @test */
    public function test_puede_editar_usuario_sin_cambiar_password()
    {
        $user = User::factory()->create([
            'role' => UserRole::COORDINADOR,
            'password' => bcrypt('original_password'),
        ]);

        $originalPassword = $user->password;

        Livewire::actingAs($this->admin)
            ->test(UserManager::class)
            ->call('edit', $user->id)
            ->set('name', 'Nombre Modificado')
            ->set('password', '') // No cambiar password
            ->call('update')
            ->assertHasNoErrors();

        $user->refresh();
        $this->assertEquals($originalPassword, $user->password);
    }

    /** @test */
    public function test_puede_cambiar_password_al_editar()
    {
        $user = User::factory()->create([
            'role' => UserRole::COORDINADOR,
            'password' => bcrypt('old_password'),
        ]);

        $originalPassword = $user->password;

        Livewire::actingAs($this->admin)
            ->test(UserManager::class)
            ->call('edit', $user->id)
            ->set('password', 'new_password123')
            ->call('update')
            ->assertHasNoErrors();

        $user->refresh();
        $this->assertNotEquals($originalPassword, $user->password);
    }

    /** @test */
    public function test_puede_eliminar_usuario()
    {
        $user = User::factory()->create();

        Livewire::actingAs($this->admin)
            ->test(UserManager::class)
            ->call('delete', $user->id);

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    /** @test */
    public function test_puede_activar_desactivar_usuario()
    {
        $user = User::factory()->create(['is_active' => true]);

        Livewire::actingAs($this->admin)
            ->test(UserManager::class)
            ->call('toggleActive', $user->id);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'is_active' => false,
        ]);

        Livewire::actingAs($this->admin)
            ->test(UserManager::class)
            ->call('toggleActive', $user->id);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'is_active' => true,
        ]);
    }

    /** @test */
    public function test_puede_cancelar_formulario()
    {
        Livewire::actingAs($this->admin)
            ->test(UserManager::class)
            ->set('name', 'Test')
            ->set('showForm', true)
            ->call('cancelEdit')
            ->assertSet('name', '')
            ->assertSet('showForm', false);
    }

    /** @test */
    public function test_crea_perfil_enfermero_solo_si_rol_es_enfermero()
    {
        Livewire::actingAs($this->admin)
            ->test(UserManager::class)
            ->set('name', 'Coordinador Test')
            ->set('email', 'coord@nursehub.com')
            ->set('password', 'password123')
            ->set('role', 'coordinador')
            ->call('create')
            ->assertHasNoErrors();

        $user = User::where('email', 'coord@nursehub.com')->first();
        $this->assertNull($user->enfermero);
    }

    /** @test */
    public function test_cambia_rol_a_enfermero_crea_perfil()
    {
        $user = User::factory()->create(['role' => UserRole::COORDINADOR]);

        Livewire::actingAs($this->admin)
            ->test(UserManager::class)
            ->call('edit', $user->id)
            ->set('role', 'enfermero')
            ->set('cedula_profesional', '55555555')
            ->set('tipo_asignacion', 'rotativo')
            ->call('update')
            ->assertHasNoErrors();

        $user->refresh();
        $this->assertNotNull($user->enfermero);
        $this->assertEquals('55555555', $user->enfermero->cedula_profesional);
    }

    /** @test */
    public function test_cambia_rol_desde_enfermero_elimina_perfil()
    {
        $user = User::factory()->create(['role' => UserRole::ENFERMERO]);
        $enfermero = Enfermero::factory()->create(['user_id' => $user->id]);

        Livewire::actingAs($this->admin)
            ->test(UserManager::class)
            ->call('edit', $user->id)
            ->set('role', 'admin')
            ->call('update')
            ->assertHasNoErrors();

        $user->refresh();
        $this->assertNull($user->enfermero);
        $this->assertDatabaseMissing('enfermeros', ['id' => $enfermero->id]);
    }

    /** @test */
    public function test_actualiza_perfil_enfermero_existente()
    {
        $user = User::factory()->create(['role' => UserRole::ENFERMERO]);
        $enfermero = Enfermero::factory()->create([
            'user_id' => $user->id,
            'cedula_profesional' => '11111111',
            'tipo_asignacion' => 'rotativo',
        ]);

        Livewire::actingAs($this->admin)
            ->test(UserManager::class)
            ->call('edit', $user->id)
            ->set('cedula_profesional', '22222222')
            ->set('tipo_asignacion', 'fijo')
            ->set('area_fija_id', $this->area->id)
            ->call('update')
            ->assertHasNoErrors();

        $enfermero->refresh();
        $this->assertEquals('22222222', $enfermero->cedula_profesional);
        $this->assertEquals('fijo', $enfermero->tipo_asignacion->value);
        $this->assertEquals($this->area->id, $enfermero->area_fija_id);
    }

    /** @test */
    public function test_muestra_datos_enfermero_en_tabla()
    {
        $user = User::factory()->create(['role' => UserRole::ENFERMERO]);
        Enfermero::factory()->create([
            'user_id' => $user->id,
            'cedula_profesional' => '12345678',
            'tipo_asignacion' => 'fijo',
            'area_fija_id' => $this->area->id,
        ]);

        Livewire::actingAs($this->admin)
            ->test(UserManager::class)
            ->assertSee('12345678')
            ->assertSee('Fijo')
            ->assertSee($this->area->nombre);
    }

    /** @test */
    public function test_areas_se_cargan_correctamente()
    {
        $area1 = Area::factory()->create(['nombre' => 'Cardiología', 'codigo' => 'CARD']);
        $area2 = Area::factory()->create(['nombre' => 'Neurología', 'codigo' => 'NEURO']);

        $component = Livewire::actingAs($this->admin)
            ->test(UserManager::class);

        $this->assertCount(3, $component->get('areas')); // 2 nuevas + 1 del setUp
    }

    /** @test */
    public function test_enfermero_rotativo_no_requiere_area_fija()
    {
        Livewire::actingAs($this->admin)
            ->test(UserManager::class)
            ->set('name', 'Enfermero Rotativo')
            ->set('email', 'rotativo@nursehub.com')
            ->set('password', 'password123')
            ->set('role', 'enfermero')
            ->set('cedula_profesional', '77777777')
            ->set('tipo_asignacion', 'rotativo')
            ->set('area_fija_id', '')
            ->call('create')
            ->assertHasNoErrors();

        $user = User::where('email', 'rotativo@nursehub.com')->first();
        $this->assertNotNull($user->enfermero);
        $this->assertNull($user->enfermero->area_fija_id);
    }
}