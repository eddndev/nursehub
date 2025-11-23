<?php

namespace Tests\Feature;

use App\Enums\NivelTriage;
use App\Livewire\Enfermeria\GraficosTendencias;
use App\Models\Area;
use App\Models\Cama;
use App\Models\Cuarto;
use App\Models\Paciente;
use App\Models\Piso;
use App\Models\RegistroSignosVitales;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class GraficosTendenciasTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Paciente $paciente;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $area = Area::factory()->create();
        $piso = Piso::factory()->create(['area_id' => $area->id]);
        $cuarto = Cuarto::factory()->create(['piso_id' => $piso->id]);
        $cama = Cama::factory()->create(['cuarto_id' => $cuarto->id]);

        $this->paciente = Paciente::factory()->create([
            'cama_actual_id' => $cama->id,
            'admitido_por' => $this->user->id,
        ]);
    }

    /** @test */
    public function puede_cargar_componente_graficos()
    {
        Livewire::actingAs($this->user)
            ->test(GraficosTendencias::class, ['pacienteId' => $this->paciente->id])
            ->assertOk()
            ->assertSet('paciente.id', $this->paciente->id);
    }

    /** @test */
    public function muestra_mensaje_cuando_no_hay_datos()
    {
        Livewire::actingAs($this->user)
            ->test(GraficosTendencias::class, ['pacienteId' => $this->paciente->id])
            ->assertSee('No hay registros de signos vitales en el período seleccionado');
    }

    /** @test */
    public function carga_datos_de_signos_vitales()
    {
        // Crear algunos registros
        RegistroSignosVitales::factory()->count(5)->create([
            'paciente_id' => $this->paciente->id,
            'registrado_por' => $this->user->id,
            'fecha_registro' => now()->subHours(2),
        ]);

        $component = Livewire::actingAs($this->user)
            ->test(GraficosTendencias::class, ['pacienteId' => $this->paciente->id]);

        $datosGraficos = $component->get('datosGraficos');

        $this->assertCount(5, $datosGraficos['labels']);
        $this->assertEquals(5, $datosGraficos['estadisticas']['total_registros']);
    }

    /** @test */
    public function puede_cambiar_periodo_a_24_horas()
    {
        // Crear registros en diferentes períodos
        RegistroSignosVitales::factory()->create([
            'paciente_id' => $this->paciente->id,
            'registrado_por' => $this->user->id,
            'fecha_registro' => now()->subHours(12), // Dentro de 24h
        ]);

        RegistroSignosVitales::factory()->create([
            'paciente_id' => $this->paciente->id,
            'registrado_por' => $this->user->id,
            'fecha_registro' => now()->subDays(3), // Fuera de 24h
        ]);

        $component = Livewire::actingAs($this->user)
            ->test(GraficosTendencias::class, ['pacienteId' => $this->paciente->id])
            ->call('cambiarPeriodo', '24h');

        $datosGraficos = $component->get('datosGraficos');
        $this->assertEquals(1, $datosGraficos['estadisticas']['total_registros']);
    }

    /** @test */
    public function puede_cambiar_periodo_a_7_dias()
    {
        RegistroSignosVitales::factory()->create([
            'paciente_id' => $this->paciente->id,
            'registrado_por' => $this->user->id,
            'fecha_registro' => now()->subDays(5),
        ]);

        RegistroSignosVitales::factory()->create([
            'paciente_id' => $this->paciente->id,
            'registrado_por' => $this->user->id,
            'fecha_registro' => now()->subDays(10),
        ]);

        $component = Livewire::actingAs($this->user)
            ->test(GraficosTendencias::class, ['pacienteId' => $this->paciente->id])
            ->call('cambiarPeriodo', '7d');

        $datosGraficos = $component->get('datosGraficos');
        $this->assertEquals(1, $datosGraficos['estadisticas']['total_registros']);
    }

    /** @test */
    public function puede_cambiar_periodo_a_30_dias()
    {
        RegistroSignosVitales::factory()->create([
            'paciente_id' => $this->paciente->id,
            'registrado_por' => $this->user->id,
            'fecha_registro' => now()->subDays(20),
        ]);

        RegistroSignosVitales::factory()->create([
            'paciente_id' => $this->paciente->id,
            'registrado_por' => $this->user->id,
            'fecha_registro' => now()->subDays(40),
        ]);

        $component = Livewire::actingAs($this->user)
            ->test(GraficosTendencias::class, ['pacienteId' => $this->paciente->id])
            ->call('cambiarPeriodo', '30d');

        $datosGraficos = $component->get('datosGraficos');
        $this->assertEquals(1, $datosGraficos['estadisticas']['total_registros']);
    }

    /** @test */
    public function periodo_todo_muestra_todos_los_registros()
    {
        RegistroSignosVitales::factory()->count(3)->create([
            'paciente_id' => $this->paciente->id,
            'registrado_por' => $this->user->id,
        ]);

        $component = Livewire::actingAs($this->user)
            ->test(GraficosTendencias::class, ['pacienteId' => $this->paciente->id])
            ->call('cambiarPeriodo', 'todo');

        $datosGraficos = $component->get('datosGraficos');
        $this->assertEquals(3, $datosGraficos['estadisticas']['total_registros']);
    }

    /** @test */
    public function calcula_estadisticas_de_presion_arterial()
    {
        RegistroSignosVitales::factory()->create([
            'paciente_id' => $this->paciente->id,
            'registrado_por' => $this->user->id,
            'presion_arterial' => '120/80',
            'fecha_registro' => now(),
        ]);

        RegistroSignosVitales::factory()->create([
            'paciente_id' => $this->paciente->id,
            'registrado_por' => $this->user->id,
            'presion_arterial' => '140/90',
            'fecha_registro' => now(),
        ]);

        $component = Livewire::actingAs($this->user)
            ->test(GraficosTendencias::class, ['pacienteId' => $this->paciente->id]);

        $stats = $component->get('datosGraficos.estadisticas.presion_arterial');

        $this->assertEquals(130, $stats['promedio_sistolica']); // (120+140)/2
        $this->assertEquals(85, $stats['promedio_diastolica']); // (80+90)/2
        $this->assertEquals(140, $stats['max_sistolica']);
        $this->assertEquals(120, $stats['min_sistolica']);
    }

    /** @test */
    public function calcula_estadisticas_de_frecuencia_cardiaca()
    {
        RegistroSignosVitales::factory()->create([
            'paciente_id' => $this->paciente->id,
            'registrado_por' => $this->user->id,
            'frecuencia_cardiaca' => 70,
            'fecha_registro' => now(),
        ]);

        RegistroSignosVitales::factory()->create([
            'paciente_id' => $this->paciente->id,
            'registrado_por' => $this->user->id,
            'frecuencia_cardiaca' => 80,
            'fecha_registro' => now(),
        ]);

        $component = Livewire::actingAs($this->user)
            ->test(GraficosTendencias::class, ['pacienteId' => $this->paciente->id]);

        $stats = $component->get('datosGraficos.estadisticas.frecuencia_cardiaca');

        $this->assertEquals(75, $stats['promedio']);
        $this->assertEquals(80, $stats['max']);
        $this->assertEquals(70, $stats['min']);
    }

    /** @test */
    public function calcula_estadisticas_de_temperatura()
    {
        RegistroSignosVitales::factory()->create([
            'paciente_id' => $this->paciente->id,
            'registrado_por' => $this->user->id,
            'temperatura' => 36.5,
            'fecha_registro' => now(),
        ]);

        RegistroSignosVitales::factory()->create([
            'paciente_id' => $this->paciente->id,
            'registrado_por' => $this->user->id,
            'temperatura' => 37.5,
            'fecha_registro' => now(),
        ]);

        $component = Livewire::actingAs($this->user)
            ->test(GraficosTendencias::class, ['pacienteId' => $this->paciente->id]);

        $stats = $component->get('datosGraficos.estadisticas.temperatura');

        $this->assertEquals(37.0, $stats['promedio']);
        $this->assertEquals(37.5, $stats['max']);
        $this->assertEquals(36.5, $stats['min']);
    }

    /** @test */
    public function incluye_datos_de_triage()
    {
        RegistroSignosVitales::factory()->create([
            'paciente_id' => $this->paciente->id,
            'registrado_por' => $this->user->id,
            'nivel_triage' => NivelTriage::ROJO,
            'fecha_registro' => now(),
        ]);

        RegistroSignosVitales::factory()->create([
            'paciente_id' => $this->paciente->id,
            'registrado_por' => $this->user->id,
            'nivel_triage' => NivelTriage::VERDE,
            'fecha_registro' => now(),
        ]);

        $component = Livewire::actingAs($this->user)
            ->test(GraficosTendencias::class, ['pacienteId' => $this->paciente->id]);

        $triageColors = $component->get('datosGraficos.triage_colors');
        $triageLabels = $component->get('datosGraficos.triage_labels');

        $this->assertCount(2, $triageColors);
        $this->assertCount(2, $triageLabels);
        $this->assertContains('Rojo - Resucitación', $triageLabels);
        $this->assertContains('Verde - Menos Urgente', $triageLabels);
    }

    /** @test */
    public function maneja_registros_sin_triage()
    {
        RegistroSignosVitales::factory()->create([
            'paciente_id' => $this->paciente->id,
            'registrado_por' => $this->user->id,
            'nivel_triage' => null,
            'fecha_registro' => now(),
        ]);

        $component = Livewire::actingAs($this->user)
            ->test(GraficosTendencias::class, ['pacienteId' => $this->paciente->id]);

        $triageLabels = $component->get('datosGraficos.triage_labels');

        $this->assertContains('Sin TRIAGE', $triageLabels);
    }

    /** @test */
    public function formatea_fechas_segun_periodo()
    {
        RegistroSignosVitales::factory()->create([
            'paciente_id' => $this->paciente->id,
            'registrado_por' => $this->user->id,
            'fecha_registro' => Carbon::parse('2024-01-15 14:30:00'),
        ]);

        // Período 24h - debe mostrar solo hora
        $component = Livewire::actingAs($this->user)
            ->test(GraficosTendencias::class, ['pacienteId' => $this->paciente->id])
            ->call('cambiarPeriodo', 'todo');

        $labels = $component->get('datosGraficos.labels');
        $this->assertNotEmpty($labels);
    }

    /** @test */
    public function se_actualiza_cuando_se_registran_nuevos_signos_vitales()
    {
        $component = Livewire::actingAs($this->user)
            ->test(GraficosTendencias::class, ['pacienteId' => $this->paciente->id]);

        // Verificar que no hay datos inicialmente
        $this->assertEquals(0, count($component->get('datosGraficos.labels')));

        // Crear un nuevo registro
        RegistroSignosVitales::factory()->create([
            'paciente_id' => $this->paciente->id,
            'registrado_por' => $this->user->id,
            'fecha_registro' => now(),
        ]);

        // Disparar evento de refresco
        $component->dispatch('signos-vitales-registrados');

        // Verificar que los datos se actualizaron
        $component->call('refreshDatos');
        $this->assertEquals(1, count($component->get('datosGraficos.labels')));
    }

    /** @test */
    public function ordena_registros_cronologicamente()
    {
        RegistroSignosVitales::factory()->create([
            'paciente_id' => $this->paciente->id,
            'registrado_por' => $this->user->id,
            'temperatura' => 37.0,
            'fecha_registro' => now()->subHours(5),
        ]);

        RegistroSignosVitales::factory()->create([
            'paciente_id' => $this->paciente->id,
            'registrado_por' => $this->user->id,
            'temperatura' => 36.5,
            'fecha_registro' => now()->subHours(10),
        ]);

        RegistroSignosVitales::factory()->create([
            'paciente_id' => $this->paciente->id,
            'registrado_por' => $this->user->id,
            'temperatura' => 37.5,
            'fecha_registro' => now()->subHours(1),
        ]);

        $component = Livewire::actingAs($this->user)
            ->test(GraficosTendencias::class, ['pacienteId' => $this->paciente->id]);

        $temperaturas = $component->get('datosGraficos.temperatura');

        // Debe estar ordenado del más antiguo al más reciente
        $this->assertEquals([36.5, 37.0, 37.5], $temperaturas);
    }
}
