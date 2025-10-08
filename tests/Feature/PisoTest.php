<?php

namespace Tests\Feature;

use App\Models\Area;
use App\Models\Piso;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PisoTest extends TestCase
{
    use RefreshDatabase;

    public function test_piso_can_be_created()
    {
        $area = Area::factory()->create(['nombre' => 'Urgencias', 'codigo' => 'URG']);

        $piso = Piso::create([
            'area_id' => $area->id,
            'nombre' => 'Urgencias - Planta Baja',
            'numero_piso' => 0,
            'especialidad' => 'Traumatología',
        ]);

        $this->assertDatabaseHas('pisos', [
            'nombre' => 'Urgencias - Planta Baja',
            'numero_piso' => 0,
        ]);

        $this->assertEquals('Traumatología', $piso->especialidad);
    }

    public function test_piso_belongs_to_area()
    {
        $area = Area::factory()->create(['nombre' => 'UCI', 'codigo' => 'UCI']);
        $piso = Piso::factory()->create(['area_id' => $area->id]);

        $this->assertInstanceOf(Area::class, $piso->area);
        $this->assertEquals($area->id, $piso->area->id);
    }

    public function test_area_has_many_pisos()
    {
        $area = Area::factory()->create();
        Piso::factory()->count(3)->create(['area_id' => $area->id]);

        $this->assertCount(3, $area->pisos);
    }

    public function test_piso_requires_area_id()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        Piso::create([
            'nombre' => 'Piso sin área',
            'numero_piso' => 1,
        ]);
    }

    public function test_piso_can_be_updated()
    {
        $area = Area::factory()->create();
        $piso = Piso::factory()->create(['area_id' => $area->id]);

        $piso->update([
            'especialidad' => 'Cardiología',
            'numero_piso' => 5,
        ]);

        $this->assertEquals('Cardiología', $piso->fresh()->especialidad);
        $this->assertEquals(5, $piso->fresh()->numero_piso);
    }

    public function test_piso_can_be_deleted()
    {
        $area = Area::factory()->create();
        $piso = Piso::factory()->create(['area_id' => $area->id]);

        $pisoId = $piso->id;
        $piso->delete();

        $this->assertDatabaseMissing('pisos', ['id' => $pisoId]);
    }

    public function test_piso_is_deleted_when_area_is_deleted()
    {
        $area = Area::factory()->create();
        $piso = Piso::factory()->create(['area_id' => $area->id]);

        $pisoId = $piso->id;
        $area->delete();

        // El piso debe ser eliminado por cascade
        $this->assertDatabaseMissing('pisos', ['id' => $pisoId]);
    }

    public function test_piso_factory_creates_valid_piso()
    {
        $piso = Piso::factory()->create();

        $this->assertNotNull($piso->area_id);
        $this->assertNotNull($piso->nombre);
        $this->assertIsInt($piso->numero_piso);
        $this->assertNotNull($piso->especialidad);
    }

    public function test_piso_seeder_creates_twelve_pisos()
    {
        // Primero ejecutar el seeder de áreas
        $this->seed(\Database\Seeders\AreaSeeder::class);
        // Luego el de pisos
        $this->seed(\Database\Seeders\PisoSeeder::class);

        $this->assertDatabaseCount('pisos', 12);
        $this->assertDatabaseHas('pisos', ['nombre' => 'Urgencias - Planta Baja']);
        $this->assertDatabaseHas('pisos', ['nombre' => 'UCI - Piso 5']);
        $this->assertDatabaseHas('pisos', ['nombre' => 'Quirófanos - Piso 3']);
    }
}
