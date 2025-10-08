<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_protected_route()
    {
        $response = $this->get('/admin/test');

        // Laravel redirige a login para usuarios no autenticados
        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_without_role_cannot_access_admin_route()
    {
        $user = User::factory()->create([
            'role' => UserRole::ENFERMERO,
        ]);

        $response = $this->actingAs($user)->get('/admin/test');

        $response->assertStatus(403);
    }

    public function test_admin_can_access_admin_route()
    {
        $admin = User::factory()->create([
            'role' => UserRole::ADMIN,
        ]);

        $response = $this->actingAs($admin)->get('/admin/test');

        $response->assertStatus(200);
    }

    public function test_coordinador_can_access_coordinador_route()
    {
        $coordinador = User::factory()->create([
            'role' => UserRole::COORDINADOR,
        ]);

        $response = $this->actingAs($coordinador)->get('/coordinador/test');

        $response->assertStatus(200);
    }

    public function test_enfermero_cannot_access_coordinador_route()
    {
        $enfermero = User::factory()->create([
            'role' => UserRole::ENFERMERO,
        ]);

        $response = $this->actingAs($enfermero)->get('/coordinador/test');

        $response->assertStatus(403);
    }

    public function test_admin_can_access_multiple_role_route()
    {
        $admin = User::factory()->create([
            'role' => UserRole::ADMIN,
        ]);

        $response = $this->actingAs($admin)->get('/jefes/test');

        $response->assertStatus(200);
    }

    public function test_coordinador_can_access_multiple_role_route()
    {
        $coordinador = User::factory()->create([
            'role' => UserRole::COORDINADOR,
        ]);

        $response = $this->actingAs($coordinador)->get('/jefes/test');

        $response->assertStatus(200);
    }

    public function test_jefe_piso_can_access_multiple_role_route()
    {
        $jefePiso = User::factory()->create([
            'role' => UserRole::JEFE_PISO,
        ]);

        $response = $this->actingAs($jefePiso)->get('/jefes/test');

        $response->assertStatus(200);
    }

    public function test_enfermero_cannot_access_multiple_role_route()
    {
        $enfermero = User::factory()->create([
            'role' => UserRole::ENFERMERO,
        ]);

        $response = $this->actingAs($enfermero)->get('/jefes/test');

        $response->assertStatus(403);
    }
}
