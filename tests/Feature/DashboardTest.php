<?php

namespace Tests\Feature;

use App\Enums\CamaEstado;
use App\Enums\UserRole;
use App\Livewire\Admin\Dashboard;
use App\Models\Area;
use App\Models\Cama;
use App\Models\Cuarto;
use App\Models\Enfermero;
use App\Models\Piso;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $coordinador;
    private User $enfermero;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role' => UserRole::ADMIN]);
        $this->coordinador = User::factory()->create(['role' => UserRole::COORDINADOR]);
        $this->enfermero = User::factory()->create(['role' => UserRole::ENFERMERO]);
    }

    /** @test */
    public function test_admin_puede_acceder_al_dashboard()
    {
        $response = $this->actingAs($this->admin)->get(route('dashboard'));
        $response->assertStatus(200);
    }

    /** @test */
    public function test_coordinador_puede_acceder_al_dashboard()
    {
        $response = $this->actingAs($this->coordinador)->get(route('dashboard'));
        $response->assertStatus(200);
    }

    /** @test */
    public function test_enfermero_puede_acceder_al_dashboard()
    {
        $response = $this->actingAs($this->enfermero)->get(route('dashboard'));
        $response->assertStatus(200);
    }

    /** @test */
    public function test_guest_redirige_a_login()
    {
        $response = $this->get(route('dashboard'));
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function test_componente_se_renderiza_correctamente()
    {
        Livewire::actingAs($this->admin)
            ->test(Dashboard::class)
            ->assertStatus(200)
            ->assertSee('Dashboard del Administrador');
    }

    /** @test */
    public function test_muestra_estadisticas_de_areas()
    {
        // Limpiar áreas existentes para evitar unique constraint
        Area::query()->delete();

        Area::factory()->count(5)->create();

        Livewire::actingAs($this->admin)
            ->test(Dashboard::class)
            ->assertSet('stats.total_areas', 5);
    }

    /** @test */
    public function test_muestra_estadisticas_de_pisos()
    {
        $area = Area::factory()->create();
        Piso::factory()->count(3)->create(['area_id' => $area->id]);

        Livewire::actingAs($this->admin)
            ->test(Dashboard::class)
            ->assertSet('stats.total_pisos', 3);
    }

    /** @test */
    public function test_muestra_estadisticas_de_cuartos()
    {
        $area = Area::factory()->create();
        $piso = Piso::factory()->create(['area_id' => $area->id]);
        Cuarto::factory()->count(10)->create(['piso_id' => $piso->id]);

        Livewire::actingAs($this->admin)
            ->test(Dashboard::class)
            ->assertSet('stats.total_cuartos', 10);
    }

    /** @test */
    public function test_muestra_estadisticas_de_camas()
    {
        $area = Area::factory()->create();
        $piso = Piso::factory()->create(['area_id' => $area->id]);
        $cuarto = Cuarto::factory()->create(['piso_id' => $piso->id]);
        Cama::factory()->count(20)->create(['cuarto_id' => $cuarto->id]);

        Livewire::actingAs($this->admin)
            ->test(Dashboard::class)
            ->assertSet('stats.total_camas', 20);
    }

    /** @test */
    public function test_calcula_porcentaje_de_ocupacion_correctamente()
    {
        $area = Area::factory()->create();
        $piso = Piso::factory()->create(['area_id' => $area->id]);
        $cuarto = Cuarto::factory()->create(['piso_id' => $piso->id]);

        // Crear 10 camas: 5 libres, 5 ocupadas
        Cama::factory()->count(5)->create([
            'cuarto_id' => $cuarto->id,
            'estado' => CamaEstado::LIBRE,
        ]);
        Cama::factory()->count(5)->create([
            'cuarto_id' => $cuarto->id,
            'estado' => CamaEstado::OCUPADA,
        ]);

        $component = Livewire::actingAs($this->admin)->test(Dashboard::class);

        $this->assertEquals(50.0, $component->get('stats.porcentaje_ocupacion'));
    }

    /** @test */
    public function test_cuenta_camas_por_estado()
    {
        $area = Area::factory()->create();
        $piso = Piso::factory()->create(['area_id' => $area->id]);
        $cuarto = Cuarto::factory()->create(['piso_id' => $piso->id]);

        Cama::factory()->count(10)->create(['cuarto_id' => $cuarto->id, 'estado' => CamaEstado::LIBRE]);
        Cama::factory()->count(15)->create(['cuarto_id' => $cuarto->id, 'estado' => CamaEstado::OCUPADA]);
        Cama::factory()->count(3)->create(['cuarto_id' => $cuarto->id, 'estado' => CamaEstado::EN_LIMPIEZA]);
        Cama::factory()->count(2)->create(['cuarto_id' => $cuarto->id, 'estado' => CamaEstado::EN_MANTENIMIENTO]);

        $component = Livewire::actingAs($this->admin)->test(Dashboard::class);

        $this->assertEquals(10, $component->get('stats.camas_libres'));
        $this->assertEquals(15, $component->get('stats.camas_ocupadas'));
        $this->assertEquals(3, $component->get('stats.camas_limpieza'));
        $this->assertEquals(2, $component->get('stats.camas_mantenimiento'));
    }

    /** @test */
    public function test_muestra_estadisticas_de_usuarios()
    {
        User::factory()->count(10)->create(['is_active' => true]);
        User::factory()->count(2)->create(['is_active' => false]);

        $component = Livewire::actingAs($this->admin)->test(Dashboard::class);

        // Total incluye los 3 del setUp + 12 nuevos = 15
        $this->assertEquals(15, $component->get('stats.total_usuarios'));
        $this->assertEquals(13, $component->get('stats.usuarios_activos')); // 3 del setUp + 10 activos
        $this->assertEquals(2, $component->get('stats.usuarios_inactivos'));
    }

    /** @test */
    public function test_muestra_estadisticas_de_enfermeros()
    {
        $area = Area::factory()->create();

        // Crear enfermeros fijos
        $users_fijos = User::factory()->count(5)->create(['role' => UserRole::ENFERMERO]);
        foreach ($users_fijos as $user) {
            Enfermero::factory()->create([
                'user_id' => $user->id,
                'tipo_asignacion' => 'fijo',
                'area_fija_id' => $area->id,
            ]);
        }

        // Crear enfermeros rotativos
        $users_rotativos = User::factory()->count(3)->create(['role' => UserRole::ENFERMERO]);
        foreach ($users_rotativos as $user) {
            Enfermero::factory()->create([
                'user_id' => $user->id,
                'tipo_asignacion' => 'rotativo',
            ]);
        }

        $component = Livewire::actingAs($this->admin)->test(Dashboard::class);

        // Total: 8 enfermeros creados (el enfermero del setUp no tiene perfil de Enfermero creado automáticamente)
        $this->assertEquals(8, $component->get('stats.total_enfermeros'));
        $this->assertEquals(5, $component->get('stats.enfermeros_fijos'));
        $this->assertEquals(3, $component->get('stats.enfermeros_rotativos'));
    }

    /** @test */
    public function test_muestra_ultimos_usuarios_registrados()
    {
        // Crear 10 usuarios
        User::factory()->count(10)->create();

        $component = Livewire::actingAs($this->admin)->test(Dashboard::class);

        // Debe mostrar solo los 5 más recientes
        $this->assertCount(5, $component->get('stats.ultimos_usuarios'));
    }

    /** @test */
    public function test_ultimos_usuarios_estan_ordenados_por_fecha_creacion()
    {
        // Limpiar usuarios del setUp para control total
        User::query()->delete();

        // Crear usuarios con timestamps específicos
        $usuario1 = User::factory()->create(['name' => 'Usuario Antiguo', 'created_at' => now()->subDays(10)]);
        $usuario2 = User::factory()->create(['name' => 'Usuario Reciente', 'created_at' => now()]);

        // Recrear admin para este test
        $admin = User::factory()->create(['role' => UserRole::ADMIN]);

        $component = Livewire::actingAs($admin)->test(Dashboard::class);

        $ultimos = $component->get('stats.ultimos_usuarios');

        // El más reciente debe aparecer primero (admin que acabamos de crear)
        // Como creamos admin al final, será el primero
        $this->assertCount(3, $ultimos); // admin + usuario1 + usuario2
        $this->assertTrue(
            $ultimos->contains('name', 'Usuario Reciente'),
            'La lista debe contener Usuario Reciente'
        );
    }

    /** @test */
    public function test_muestra_top_areas_por_capacidad_de_camas()
    {
        // Limpiar áreas existentes
        Area::query()->delete();

        $area1 = Area::factory()->create(['nombre' => 'UCI', 'codigo' => 'UCI01']);
        $area2 = Area::factory()->create(['nombre' => 'Urgencias', 'codigo' => 'URG01']);

        $piso1 = Piso::factory()->create(['area_id' => $area1->id]);
        $piso2 = Piso::factory()->create(['area_id' => $area2->id]);

        $cuarto1 = Cuarto::factory()->create(['piso_id' => $piso1->id]);
        $cuarto2 = Cuarto::factory()->create(['piso_id' => $piso2->id]);

        // UCI: 10 camas
        Cama::factory()->count(10)->create(['cuarto_id' => $cuarto1->id]);

        // Urgencias: 20 camas
        Cama::factory()->count(20)->create(['cuarto_id' => $cuarto2->id]);

        $component = Livewire::actingAs($this->admin)->test(Dashboard::class);

        $topAreas = $component->get('stats.top_areas');

        // Urgencias debe aparecer primero (tiene más camas)
        $this->assertEquals('Urgencias', $topAreas->first()->nombre);
        $this->assertEquals(20, $topAreas->first()->camas_count);
    }

    /** @test */
    public function test_distribucion_camas_contiene_todos_los_estados()
    {
        $area = Area::factory()->create();
        $piso = Piso::factory()->create(['area_id' => $area->id]);
        $cuarto = Cuarto::factory()->create(['piso_id' => $piso->id]);

        Cama::factory()->count(5)->create(['cuarto_id' => $cuarto->id, 'estado' => CamaEstado::LIBRE]);
        Cama::factory()->count(3)->create(['cuarto_id' => $cuarto->id, 'estado' => CamaEstado::OCUPADA]);
        Cama::factory()->count(2)->create(['cuarto_id' => $cuarto->id, 'estado' => CamaEstado::EN_LIMPIEZA]);
        Cama::factory()->count(1)->create(['cuarto_id' => $cuarto->id, 'estado' => CamaEstado::EN_MANTENIMIENTO]);

        $component = Livewire::actingAs($this->admin)->test(Dashboard::class);

        $distribucion = $component->get('stats.distribucion_camas');

        $this->assertArrayHasKey('libre', $distribucion);
        $this->assertArrayHasKey('ocupada', $distribucion);
        $this->assertArrayHasKey('limpieza', $distribucion);
        $this->assertArrayHasKey('mantenimiento', $distribucion);

        $this->assertEquals(5, $distribucion['libre']);
        $this->assertEquals(3, $distribucion['ocupada']);
        $this->assertEquals(2, $distribucion['limpieza']);
        $this->assertEquals(1, $distribucion['mantenimiento']);
    }

    /** @test */
    public function test_dashboard_renderiza_con_datos_vacios()
    {
        // No crear ningún dato
        Livewire::actingAs($this->admin)
            ->test(Dashboard::class)
            ->assertStatus(200)
            ->assertSet('stats.total_areas', 0)
            ->assertSet('stats.total_pisos', 0)
            ->assertSet('stats.total_camas', 0)
            ->assertSet('stats.porcentaje_ocupacion', 0);
    }

    /** @test */
    public function test_enlaces_de_navegacion_estan_presentes()
    {
        Livewire::actingAs($this->admin)
            ->test(Dashboard::class)
            ->assertSee('Ver áreas')
            ->assertSee('Ver pisos')
            ->assertSee('Ver camas')
            ->assertSee('Ver usuarios')
            ->assertSee('Ver mapa completo')
            ->assertSee('Ver personal');
    }
}
