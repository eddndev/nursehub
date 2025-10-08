<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserRoleTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_be_created_with_admin_role()
    {
        $user = User::factory()->create([
            'role' => UserRole::ADMIN,
        ]);

        $this->assertInstanceOf(UserRole::class, $user->role);
        $this->assertEquals(UserRole::ADMIN, $user->role);
        $this->assertTrue($user->isAdmin());
    }

    public function test_user_can_be_created_with_coordinador_role()
    {
        $user = User::factory()->create([
            'role' => UserRole::COORDINADOR,
        ]);

        $this->assertEquals(UserRole::COORDINADOR, $user->role);
        $this->assertFalse($user->isAdmin());
        $this->assertTrue($user->isCoordinadorOrAbove());
    }

    public function test_user_can_be_created_with_jefe_piso_role()
    {
        $user = User::factory()->create([
            'role' => UserRole::JEFE_PISO,
        ]);

        $this->assertEquals(UserRole::JEFE_PISO, $user->role);
        $this->assertTrue($user->role->isJefe());
    }

    public function test_user_can_be_created_with_enfermero_role()
    {
        $user = User::factory()->create([
            'role' => UserRole::ENFERMERO,
        ]);

        $this->assertEquals(UserRole::ENFERMERO, $user->role);
        $this->assertFalse($user->isAdmin());
        $this->assertFalse($user->role->isJefe());
    }

    public function test_user_can_be_created_with_jefe_capacitacion_role()
    {
        $user = User::factory()->create([
            'role' => UserRole::JEFE_CAPACITACION,
        ]);

        $this->assertEquals(UserRole::JEFE_CAPACITACION, $user->role);
        $this->assertTrue($user->role->isJefe());
    }

    public function test_user_defaults_to_enfermero_role_when_not_specified()
    {
        $user = User::factory()->create();

        $this->assertEquals(UserRole::ENFERMERO, $user->role);
    }

    public function test_user_is_active_by_default()
    {
        $user = User::factory()->create();

        $this->assertTrue($user->is_active);
    }

    public function test_user_can_be_created_as_inactive()
    {
        $user = User::factory()->create([
            'is_active' => false,
        ]);

        $this->assertFalse($user->is_active);
    }

    public function test_active_scope_filters_only_active_users()
    {
        User::factory()->create(['is_active' => true]);
        User::factory()->create(['is_active' => true]);
        User::factory()->create(['is_active' => false]);

        $activeUsers = User::active()->get();

        $this->assertCount(2, $activeUsers);
    }

    public function test_by_role_scope_filters_users_by_specific_role()
    {
        User::factory()->create(['role' => UserRole::ADMIN]);
        User::factory()->create(['role' => UserRole::ENFERMERO]);
        User::factory()->create(['role' => UserRole::ENFERMERO]);

        $enfermeros = User::byRole(UserRole::ENFERMERO)->get();

        $this->assertCount(2, $enfermeros);
        $enfermeros->each(function ($user) {
            $this->assertEquals(UserRole::ENFERMERO, $user->role);
        });
    }

    public function test_role_enum_has_correct_labels()
    {
        $this->assertEquals('Administrador', UserRole::ADMIN->label());
        $this->assertEquals('Coordinador General de Enfermería', UserRole::COORDINADOR->label());
        $this->assertEquals('Jefe de Piso/Área', UserRole::JEFE_PISO->label());
        $this->assertEquals('Enfermero', UserRole::ENFERMERO->label());
        $this->assertEquals('Jefe de Capacitación', UserRole::JEFE_CAPACITACION->label());
    }

    public function test_role_enum_can_check_admin_permissions()
    {
        $this->assertTrue(UserRole::ADMIN->isAdmin());
        $this->assertFalse(UserRole::COORDINADOR->isAdmin());
        $this->assertFalse(UserRole::ENFERMERO->isAdmin());
    }

    public function test_role_enum_can_check_coordinador_or_above_permissions()
    {
        $this->assertTrue(UserRole::ADMIN->isCoordinadorOrAbove());
        $this->assertTrue(UserRole::COORDINADOR->isCoordinadorOrAbove());
        $this->assertFalse(UserRole::JEFE_PISO->isCoordinadorOrAbove());
        $this->assertFalse(UserRole::ENFERMERO->isCoordinadorOrAbove());
    }

    public function test_role_enum_can_check_jefe_permissions()
    {
        $this->assertTrue(UserRole::ADMIN->isJefe());
        $this->assertTrue(UserRole::COORDINADOR->isJefe());
        $this->assertTrue(UserRole::JEFE_PISO->isJefe());
        $this->assertTrue(UserRole::JEFE_CAPACITACION->isJefe());
        $this->assertFalse(UserRole::ENFERMERO->isJefe());
    }
}
