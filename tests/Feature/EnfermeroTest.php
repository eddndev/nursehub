<?php

namespace Tests\Feature;

use App\Enums\TipoAsignacion;
use App\Enums\UserRole;
use App\Models\Area;
use App\Models\Enfermero;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EnfermeroTest extends TestCase
{
    use RefreshDatabase;

    public function test_enfermero_can_be_created(): void
    {
        $user = User::factory()->create(['role' => UserRole::ENFERMERO]);
        $area = Area::factory()->create();

        $enfermero = Enfermero::create([
            'user_id' => $user->id,
            'cedula_profesional' => '12345678',
            'tipo_asignacion' => TipoAsignacion::FIJO,
            'area_fija_id' => $area->id,
            'especialidades' => 'UCI, Urgencias',
            'anos_experiencia' => 5,
        ]);

        $this->assertDatabaseHas('enfermeros', [
            'user_id' => $user->id,
            'cedula_profesional' => '12345678',
            'tipo_asignacion' => 'fijo',
            'area_fija_id' => $area->id,
        ]);

        $this->assertEquals(5, $enfermero->anos_experiencia);
    }

    public function test_enfermero_belongs_to_user(): void
    {
        $user = User::factory()->create(['role' => UserRole::ENFERMERO]);
        $enfermero = Enfermero::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $enfermero->user);
        $this->assertEquals($user->id, $enfermero->user->id);
    }

    public function test_user_has_one_enfermero(): void
    {
        $user = User::factory()->create(['role' => UserRole::ENFERMERO]);
        $enfermero = Enfermero::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(Enfermero::class, $user->enfermero);
        $this->assertEquals($enfermero->id, $user->enfermero->id);
    }

    public function test_enfermero_belongs_to_area_fija(): void
    {
        $area = Area::factory()->create();
        $enfermero = Enfermero::factory()->fijo($area->id)->create();

        $this->assertInstanceOf(Area::class, $enfermero->areaFija);
        $this->assertEquals($area->id, $enfermero->areaFija->id);
    }

    public function test_enfermero_rotativo_has_no_area_fija(): void
    {
        $enfermero = Enfermero::factory()->rotativo()->create();

        $this->assertEquals(TipoAsignacion::ROTATIVO, $enfermero->tipo_asignacion);
        $this->assertNull($enfermero->area_fija_id);
        $this->assertNull($enfermero->areaFija);
    }

    public function test_enfermero_fijo_has_area_fija(): void
    {
        Area::factory()->create(); // Asegurar que hay áreas disponibles
        $enfermero = Enfermero::factory()->fijo()->create();

        $this->assertEquals(TipoAsignacion::FIJO, $enfermero->tipo_asignacion);
        $this->assertNotNull($enfermero->area_fija_id);
        $this->assertInstanceOf(Area::class, $enfermero->areaFija);
    }

    public function test_scope_fijos_filters_correctly(): void
    {
        Area::factory()->create();
        Enfermero::factory()->fijo()->count(3)->create();
        Enfermero::factory()->rotativo()->count(2)->create();

        $fijos = Enfermero::fijos()->get();

        $this->assertCount(3, $fijos);
        $fijos->each(function ($enfermero) {
            $this->assertEquals(TipoAsignacion::FIJO, $enfermero->tipo_asignacion);
        });
    }

    public function test_scope_rotativos_filters_correctly(): void
    {
        Area::factory()->create();
        Enfermero::factory()->fijo()->count(3)->create();
        Enfermero::factory()->rotativo()->count(2)->create();

        $rotativos = Enfermero::rotativos()->get();

        $this->assertCount(2, $rotativos);
        $rotativos->each(function ($enfermero) {
            $this->assertEquals(TipoAsignacion::ROTATIVO, $enfermero->tipo_asignacion);
        });
    }

    public function test_scope_by_area_filters_correctly(): void
    {
        $area1 = Area::factory()->create();
        $area2 = Area::factory()->create();

        Enfermero::factory()->fijo($area1->id)->count(3)->create();
        Enfermero::factory()->fijo($area2->id)->count(2)->create();

        $enfermerosArea1 = Enfermero::byArea($area1->id)->get();

        $this->assertCount(3, $enfermerosArea1);
        $enfermerosArea1->each(function ($enfermero) use ($area1) {
            $this->assertEquals($area1->id, $enfermero->area_fija_id);
        });
    }

    public function test_enfermero_has_default_anos_experiencia(): void
    {
        $user = User::factory()->create(['role' => UserRole::ENFERMERO]);
        $enfermero = Enfermero::create([
            'user_id' => $user->id,
            'cedula_profesional' => '87654321',
            'tipo_asignacion' => TipoAsignacion::ROTATIVO,
        ]);

        $this->assertEquals(0, $enfermero->anos_experiencia);
    }

    public function test_enfermero_cedula_is_unique(): void
    {
        $user1 = User::factory()->create(['role' => UserRole::ENFERMERO]);
        $user2 = User::factory()->create(['role' => UserRole::ENFERMERO]);

        Enfermero::create([
            'user_id' => $user1->id,
            'cedula_profesional' => '11111111',
            'tipo_asignacion' => TipoAsignacion::ROTATIVO,
        ]);

        $this->expectException(\Illuminate\Database\QueryException::class);

        Enfermero::create([
            'user_id' => $user2->id,
            'cedula_profesional' => '11111111',
            'tipo_asignacion' => TipoAsignacion::ROTATIVO,
        ]);
    }

    public function test_enfermero_is_deleted_when_user_is_deleted(): void
    {
        $user = User::factory()->create(['role' => UserRole::ENFERMERO]);
        $enfermero = Enfermero::factory()->create(['user_id' => $user->id]);

        $enfermeroId = $enfermero->id;

        $user->delete();

        $this->assertDatabaseMissing('enfermeros', ['id' => $enfermeroId]);
    }

    public function test_area_fija_is_set_null_when_area_is_deleted(): void
    {
        $area = Area::factory()->create();
        $enfermero = Enfermero::factory()->fijo($area->id)->create();

        $area->delete();

        $enfermero->refresh();

        $this->assertNull($enfermero->area_fija_id);
    }

    public function test_tipo_asignacion_enum_methods(): void
    {
        $this->assertEquals('Fijo', TipoAsignacion::FIJO->label());
        $this->assertEquals('Rotativo', TipoAsignacion::ROTATIVO->label());

        $this->assertStringContainsString('permanentemente', TipoAsignacion::FIJO->descripcion());
        $this->assertStringContainsString('rota', TipoAsignacion::ROTATIVO->descripcion());
    }

    public function test_tipo_asignacion_enum_to_array(): void
    {
        $tipos = TipoAsignacion::toArray();

        $this->assertIsArray($tipos);
        $this->assertContains('fijo', $tipos);
        $this->assertContains('rotativo', $tipos);
        $this->assertCount(2, $tipos);
    }
}
