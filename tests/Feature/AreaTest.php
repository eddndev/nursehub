<?php

namespace Tests\Feature;

use App\Models\Area;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AreaTest extends TestCase
{
    use RefreshDatabase;

    public function test_area_can_be_created()
    {
        $area = Area::create([
            'nombre' => 'Urgencias',
            'codigo' => 'URG',
            'descripcion' => 'Área de emergencias',
            'opera_24_7' => true,
            'ratio_enfermero_paciente' => 0.25,
            'requiere_certificacion' => true,
        ]);

        $this->assertDatabaseHas('areas', [
            'nombre' => 'Urgencias',
            'codigo' => 'URG',
        ]);

        $this->assertTrue($area->opera_24_7);
        $this->assertTrue($area->requiere_certificacion);
        $this->assertEquals('0.25', $area->ratio_enfermero_paciente);
    }

    public function test_area_nombre_is_unique()
    {
        Area::create([
            'nombre' => 'Urgencias',
            'codigo' => 'URG',
            'opera_24_7' => true,
            'ratio_enfermero_paciente' => 0.25,
        ]);

        $this->expectException(\Illuminate\Database\QueryException::class);

        Area::create([
            'nombre' => 'Urgencias',
            'codigo' => 'URG2',
            'opera_24_7' => true,
            'ratio_enfermero_paciente' => 0.25,
        ]);
    }

    public function test_area_codigo_is_unique()
    {
        Area::create([
            'nombre' => 'Urgencias',
            'codigo' => 'URG',
            'opera_24_7' => true,
            'ratio_enfermero_paciente' => 0.25,
        ]);

        $this->expectException(\Illuminate\Database\QueryException::class);

        Area::create([
            'nombre' => 'Emergencias',
            'codigo' => 'URG',
            'opera_24_7' => true,
            'ratio_enfermero_paciente' => 0.25,
        ]);
    }

    public function test_area_has_default_values()
    {
        $area = Area::create([
            'nombre' => 'Medicina Interna',
            'codigo' => 'MI',
        ]);

        $this->assertTrue($area->opera_24_7);
        $this->assertFalse($area->requiere_certificacion);
        $this->assertEquals('1.00', $area->ratio_enfermero_paciente);
    }

    public function test_area_can_be_updated()
    {
        $area = Area::create([
            'nombre' => 'Urgencias',
            'codigo' => 'URG',
            'opera_24_7' => true,
            'ratio_enfermero_paciente' => 0.25,
        ]);

        $area->update([
            'ratio_enfermero_paciente' => 0.30,
            'requiere_certificacion' => true,
        ]);

        $this->assertEquals('0.30', $area->fresh()->ratio_enfermero_paciente);
        $this->assertTrue($area->fresh()->requiere_certificacion);
    }

    public function test_area_can_be_deleted()
    {
        $area = Area::create([
            'nombre' => 'Urgencias',
            'codigo' => 'URG',
            'opera_24_7' => true,
            'ratio_enfermero_paciente' => 0.25,
        ]);

        $areaId = $area->id;
        $area->delete();

        $this->assertDatabaseMissing('areas', [
            'id' => $areaId,
        ]);
    }

    public function test_area_factory_creates_valid_area()
    {
        $area = Area::factory()->create();

        $this->assertNotNull($area->nombre);
        $this->assertNotNull($area->codigo);
        $this->assertIsBool($area->opera_24_7);
        $this->assertIsBool($area->requiere_certificacion);
        $this->assertIsNumeric($area->ratio_enfermero_paciente);
    }

    public function test_area_seeder_creates_eight_areas()
    {
        $this->seed(\Database\Seeders\AreaSeeder::class);

        $this->assertDatabaseCount('areas', 8);
        $this->assertDatabaseHas('areas', ['codigo' => 'URG']);
        $this->assertDatabaseHas('areas', ['codigo' => 'UCI']);
        $this->assertDatabaseHas('areas', ['codigo' => 'PED']);
    }
}
