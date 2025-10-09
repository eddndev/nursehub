<?php

namespace Tests\Feature;

use App\Models\Area;
use App\Models\Cuarto;
use App\Models\Piso;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CuartoTest extends TestCase
{
    use RefreshDatabase;

    public function test_cuarto_can_be_created()
    {
        $area = Area::factory()->create();
        $piso = Piso::factory()->create(['area_id' => $area->id]);

        $cuarto = Cuarto::create([
            'piso_id' => $piso->id,
            'numero_cuarto' => '101',
            'tipo' => 'individual',
        ]);

        $this->assertDatabaseHas('cuartos', [
            'numero_cuarto' => '101',
            'tipo' => 'individual',
        ]);

        $this->assertEquals('individual', $cuarto->tipo);
    }

    public function test_cuarto_belongs_to_piso()
    {
        $area = Area::factory()->create();
        $piso = Piso::factory()->create(['area_id' => $area->id]);
        $cuarto = Cuarto::factory()->create(['piso_id' => $piso->id]);

        $this->assertInstanceOf(Piso::class, $cuarto->piso);
        $this->assertEquals($piso->id, $cuarto->piso->id);
    }

    public function test_piso_has_many_cuartos()
    {
        $area = Area::factory()->create();
        $piso = Piso::factory()->create(['area_id' => $area->id]);
        Cuarto::factory()->count(5)->create(['piso_id' => $piso->id]);

        $this->assertCount(5, $piso->cuartos);
    }

    public function test_cuarto_requires_piso_id()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        Cuarto::create([
            'numero_cuarto' => '101',
            'tipo' => 'individual',
        ]);
    }

    public function test_cuarto_has_default_tipo()
    {
        $area = Area::factory()->create();
        $piso = Piso::factory()->create(['area_id' => $area->id]);

        $cuarto = Cuarto::create([
            'piso_id' => $piso->id,
            'numero_cuarto' => '101',
        ]);

        $this->assertEquals('individual', $cuarto->tipo);
    }

    public function test_cuarto_accepts_valid_tipos()
    {
        $area = Area::factory()->create();
        $piso = Piso::factory()->create(['area_id' => $area->id]);

        $cuartoIndividual = Cuarto::create([
            'piso_id' => $piso->id,
            'numero_cuarto' => '101',
            'tipo' => 'individual',
        ]);

        $cuartoDoble = Cuarto::create([
            'piso_id' => $piso->id,
            'numero_cuarto' => '102',
            'tipo' => 'doble',
        ]);

        $cuartoMultiple = Cuarto::create([
            'piso_id' => $piso->id,
            'numero_cuarto' => '103',
            'tipo' => 'multiple',
        ]);

        $this->assertEquals('individual', $cuartoIndividual->tipo);
        $this->assertEquals('doble', $cuartoDoble->tipo);
        $this->assertEquals('multiple', $cuartoMultiple->tipo);
    }

    public function test_cuarto_can_be_updated()
    {
        $area = Area::factory()->create();
        $piso = Piso::factory()->create(['area_id' => $area->id]);
        $cuarto = Cuarto::factory()->create(['piso_id' => $piso->id]);

        $cuarto->update([
            'numero_cuarto' => '999',
            'tipo' => 'doble',
        ]);

        $this->assertEquals('999', $cuarto->fresh()->numero_cuarto);
        $this->assertEquals('doble', $cuarto->fresh()->tipo);
    }

    public function test_cuarto_can_be_deleted()
    {
        $area = Area::factory()->create();
        $piso = Piso::factory()->create(['area_id' => $area->id]);
        $cuarto = Cuarto::factory()->create(['piso_id' => $piso->id]);

        $cuartoId = $cuarto->id;
        $cuarto->delete();

        $this->assertDatabaseMissing('cuartos', ['id' => $cuartoId]);
    }

    public function test_cuarto_is_deleted_when_piso_is_deleted()
    {
        $area = Area::factory()->create();
        $piso = Piso::factory()->create(['area_id' => $area->id]);
        $cuarto = Cuarto::factory()->create(['piso_id' => $piso->id]);

        $cuartoId = $cuarto->id;
        $piso->delete();

        // El cuarto debe ser eliminado por cascade
        $this->assertDatabaseMissing('cuartos', ['id' => $cuartoId]);
    }

    public function test_cuarto_factory_creates_valid_cuarto()
    {
        $cuarto = Cuarto::factory()->create();

        $this->assertNotNull($cuarto->piso_id);
        $this->assertNotNull($cuarto->numero_cuarto);
        $this->assertContains($cuarto->tipo, ['individual', 'doble', 'multiple']);
    }

    public function test_cuarto_seeder_creates_cuartos_for_all_pisos()
    {
        // Ejecutar seeders previos
        $this->seed(\Database\Seeders\AreaSeeder::class);
        $this->seed(\Database\Seeders\PisoSeeder::class);
        $this->seed(\Database\Seeders\CuartoSeeder::class);

        // Verificar que se crearon cuartos
        $this->assertGreaterThan(0, Cuarto::count());

        // Verificar que hay cuartos de diferentes tipos
        $this->assertGreaterThan(0, Cuarto::where('tipo', 'individual')->count());
        $this->assertGreaterThan(0, Cuarto::where('tipo', 'doble')->count());
    }
}
