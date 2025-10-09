<?php

namespace Tests\Feature;

use App\Enums\CamaEstado;
use App\Models\Area;
use App\Models\Cama;
use App\Models\Cuarto;
use App\Models\Piso;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CamaTest extends TestCase
{
    use RefreshDatabase;

    public function test_cama_can_be_created()
    {
        $area = Area::factory()->create();
        $piso = Piso::factory()->create(['area_id' => $area->id]);
        $cuarto = Cuarto::factory()->create(['piso_id' => $piso->id]);

        $cama = Cama::create([
            'cuarto_id' => $cuarto->id,
            'numero_cama' => '101-1',
            'estado' => CamaEstado::LIBRE,
        ]);

        $this->assertDatabaseHas('camas', [
            'numero_cama' => '101-1',
            'estado' => 'libre',
        ]);

        $this->assertEquals(CamaEstado::LIBRE, $cama->estado);
    }

    public function test_cama_belongs_to_cuarto()
    {
        $area = Area::factory()->create();
        $piso = Piso::factory()->create(['area_id' => $area->id]);
        $cuarto = Cuarto::factory()->create(['piso_id' => $piso->id]);
        $cama = Cama::factory()->create(['cuarto_id' => $cuarto->id]);

        $this->assertInstanceOf(Cuarto::class, $cama->cuarto);
        $this->assertEquals($cuarto->id, $cama->cuarto->id);
    }

    public function test_cuarto_has_many_camas()
    {
        $area = Area::factory()->create();
        $piso = Piso::factory()->create(['area_id' => $area->id]);
        $cuarto = Cuarto::factory()->create(['piso_id' => $piso->id]);
        Cama::factory()->count(3)->create(['cuarto_id' => $cuarto->id]);

        $this->assertCount(3, $cuarto->camas);
    }

    public function test_cama_requires_cuarto_id()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        Cama::create([
            'numero_cama' => '101-1',
            'estado' => CamaEstado::LIBRE,
        ]);
    }

    public function test_cama_has_default_estado_libre()
    {
        $area = Area::factory()->create();
        $piso = Piso::factory()->create(['area_id' => $area->id]);
        $cuarto = Cuarto::factory()->create(['piso_id' => $piso->id]);

        $cama = Cama::create([
            'cuarto_id' => $cuarto->id,
            'numero_cama' => '101-1',
        ]);

        $this->assertEquals(CamaEstado::LIBRE, $cama->estado);
    }

    public function test_cama_accepts_all_estados()
    {
        $area = Area::factory()->create();
        $piso = Piso::factory()->create(['area_id' => $area->id]);
        $cuarto = Cuarto::factory()->create(['piso_id' => $piso->id]);

        $camaLibre = Cama::create([
            'cuarto_id' => $cuarto->id,
            'numero_cama' => '101-1',
            'estado' => CamaEstado::LIBRE,
        ]);

        $camaOcupada = Cama::create([
            'cuarto_id' => $cuarto->id,
            'numero_cama' => '101-2',
            'estado' => CamaEstado::OCUPADA,
        ]);

        $camaLimpieza = Cama::create([
            'cuarto_id' => $cuarto->id,
            'numero_cama' => '101-3',
            'estado' => CamaEstado::EN_LIMPIEZA,
        ]);

        $camaMantenimiento = Cama::create([
            'cuarto_id' => $cuarto->id,
            'numero_cama' => '101-4',
            'estado' => CamaEstado::EN_MANTENIMIENTO,
        ]);

        $this->assertEquals(CamaEstado::LIBRE, $camaLibre->estado);
        $this->assertEquals(CamaEstado::OCUPADA, $camaOcupada->estado);
        $this->assertEquals(CamaEstado::EN_LIMPIEZA, $camaLimpieza->estado);
        $this->assertEquals(CamaEstado::EN_MANTENIMIENTO, $camaMantenimiento->estado);
    }

    public function test_cama_estado_can_be_changed()
    {
        $area = Area::factory()->create();
        $piso = Piso::factory()->create(['area_id' => $area->id]);
        $cuarto = Cuarto::factory()->create(['piso_id' => $piso->id]);
        $cama = Cama::factory()->create([
            'cuarto_id' => $cuarto->id,
            'estado' => CamaEstado::LIBRE,
        ]);

        $cama->update(['estado' => CamaEstado::OCUPADA]);
        $this->assertEquals(CamaEstado::OCUPADA, $cama->fresh()->estado);

        $cama->update(['estado' => CamaEstado::EN_LIMPIEZA]);
        $this->assertEquals(CamaEstado::EN_LIMPIEZA, $cama->fresh()->estado);
    }

    public function test_cama_scope_libre_filters_correctly()
    {
        $area = Area::factory()->create();
        $piso = Piso::factory()->create(['area_id' => $area->id]);
        $cuarto = Cuarto::factory()->create(['piso_id' => $piso->id]);

        Cama::factory()->create(['cuarto_id' => $cuarto->id, 'estado' => CamaEstado::LIBRE]);
        Cama::factory()->create(['cuarto_id' => $cuarto->id, 'estado' => CamaEstado::LIBRE]);
        Cama::factory()->create(['cuarto_id' => $cuarto->id, 'estado' => CamaEstado::OCUPADA]);

        $camasLibres = Cama::libre()->get();

        $this->assertCount(2, $camasLibres);
    }

    public function test_cama_scope_ocupada_filters_correctly()
    {
        $area = Area::factory()->create();
        $piso = Piso::factory()->create(['area_id' => $area->id]);
        $cuarto = Cuarto::factory()->create(['piso_id' => $piso->id]);

        Cama::factory()->create(['cuarto_id' => $cuarto->id, 'estado' => CamaEstado::OCUPADA]);
        Cama::factory()->create(['cuarto_id' => $cuarto->id, 'estado' => CamaEstado::OCUPADA]);
        Cama::factory()->create(['cuarto_id' => $cuarto->id, 'estado' => CamaEstado::LIBRE]);

        $camasOcupadas = Cama::ocupada()->get();

        $this->assertCount(2, $camasOcupadas);
    }

    public function test_cama_is_deleted_when_cuarto_is_deleted()
    {
        $area = Area::factory()->create();
        $piso = Piso::factory()->create(['area_id' => $area->id]);
        $cuarto = Cuarto::factory()->create(['piso_id' => $piso->id]);
        $cama = Cama::factory()->create(['cuarto_id' => $cuarto->id]);

        $camaId = $cama->id;
        $cuarto->delete();

        $this->assertDatabaseMissing('camas', ['id' => $camaId]);
    }

    public function test_cama_factory_creates_valid_cama()
    {
        $cama = Cama::factory()->create();

        $this->assertNotNull($cama->cuarto_id);
        $this->assertNotNull($cama->numero_cama);
        $this->assertInstanceOf(CamaEstado::class, $cama->estado);
    }

    public function test_cama_seeder_creates_camas_for_all_cuartos()
    {
        // Ejecutar seeders previos
        $this->seed(\Database\Seeders\AreaSeeder::class);
        $this->seed(\Database\Seeders\PisoSeeder::class);
        $this->seed(\Database\Seeders\CuartoSeeder::class);
        $this->seed(\Database\Seeders\CamaSeeder::class);

        // Verificar que se crearon camas
        $this->assertGreaterThan(0, Cama::count());

        // Verificar que hay camas de diferentes estados
        $this->assertGreaterThan(0, Cama::where('estado', CamaEstado::LIBRE)->count());
        $this->assertGreaterThan(0, Cama::where('estado', CamaEstado::OCUPADA)->count());
    }

    public function test_cama_enum_has_correct_labels()
    {
        $this->assertEquals('Libre', CamaEstado::LIBRE->label());
        $this->assertEquals('Ocupada', CamaEstado::OCUPADA->label());
        $this->assertEquals('En Limpieza', CamaEstado::EN_LIMPIEZA->label());
        $this->assertEquals('En Mantenimiento', CamaEstado::EN_MANTENIMIENTO->label());
    }

    public function test_cama_enum_has_correct_colors()
    {
        $this->assertEquals('green', CamaEstado::LIBRE->color());
        $this->assertEquals('red', CamaEstado::OCUPADA->color());
        $this->assertEquals('yellow', CamaEstado::EN_LIMPIEZA->color());
        $this->assertEquals('gray', CamaEstado::EN_MANTENIMIENTO->color());
    }

    public function test_cama_enum_is_disponible_method()
    {
        $this->assertTrue(CamaEstado::LIBRE->isDisponible());
        $this->assertFalse(CamaEstado::OCUPADA->isDisponible());
        $this->assertFalse(CamaEstado::EN_LIMPIEZA->isDisponible());
        $this->assertFalse(CamaEstado::EN_MANTENIMIENTO->isDisponible());
    }
}
