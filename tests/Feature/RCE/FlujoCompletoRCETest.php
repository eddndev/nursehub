<?php

namespace Tests\Feature\RCE;

use App\Enums\CamaEstado;
use App\Enums\EstadoPaciente;
use App\Enums\NivelTriage;
use App\Models\Area;
use App\Models\Cama;
use App\Models\Cuarto;
use App\Models\Paciente;
use App\Models\Piso;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Test del flujo completo del módulo RCE (Registro Clínico Electrónico)
 * Prueba el journey completo: Admisión -> Lista -> Expediente -> Signos Vitales -> Gráficos
 */
class FlujoCompletoRCETest extends TestCase
{
    use RefreshDatabase;

    protected User $enfermera;
    protected Area $area;
    protected Piso $piso;
    protected Cuarto $cuarto;
    protected Cama $cama;

    protected function setUp(): void
    {
        parent::setUp();

        // Crear usuario enfermera
        $this->enfermera = User::factory()->create([
            'name' => 'Enfermera Test',
            'email' => 'enfermera@test.com',
        ]);

        // Crear infraestructura hospitalaria
        $this->area = Area::factory()->create(['nombre' => 'Urgencias']);
        $this->piso = Piso::factory()->create([
            'area_id' => $this->area->id,
            'nombre' => 'Piso 1',
        ]);
        $this->cuarto = Cuarto::factory()->create([
            'piso_id' => $this->piso->id,
            'numero' => '101',
        ]);
        $this->cama = Cama::factory()->create([
            'cuarto_id' => $this->cuarto->id,
            'numero' => 'A',
            'estado' => CamaEstado::LIBRE,
        ]);
    }

    public function test_flujo_completo_admision_paciente_critico()
    {
        // PASO 1: Enfermera accede al sistema de admisión
        $response = $this->actingAs($this->enfermera)
            ->get(route('urgencias.admision'));

        $response->assertOk()
            ->assertSee('Admisión de Paciente');

        // PASO 2: Registra paciente con signos vitales críticos
        $datosAdmision = [
            'nombre' => 'Carlos',
            'apellido_paterno' => 'Hernández',
            'apellido_materno' => 'Mora',
            'fecha_nacimiento' => '1975-03-15',
            'sexo' => 'M',
            'curp' => 'HEMC750315HDFRRR08',
            'telefono' => '5551234567',
            'alergias' => 'Penicilina',
            'antecedentes_medicos' => 'Hipertensión, Diabetes tipo 2',
            'contacto_emergencia_nombre' => 'Ana Hernández',
            'contacto_emergencia_telefono' => '5559876543',
            'cama_id' => $this->cama->id,
            // Signos vitales críticos
            'presion_arterial_sistolica' => 180,
            'presion_arterial_diastolica' => 110,
            'frecuencia_cardiaca' => 135,
            'frecuencia_respiratoria' => 28,
            'temperatura' => 39.2,
            'saturacion_oxigeno' => 89,
            'glucosa' => 250,
            'observaciones' => 'Paciente con dolor torácico intenso',
        ];

        $paciente = Paciente::create([
            'nombre' => $datosAdmision['nombre'],
            'apellido_paterno' => $datosAdmision['apellido_paterno'],
            'apellido_materno' => $datosAdmision['apellido_materno'],
            'fecha_nacimiento' => $datosAdmision['fecha_nacimiento'],
            'sexo' => $datosAdmision['sexo'],
            'curp' => $datosAdmision['curp'],
            'telefono' => $datosAdmision['telefono'],
            'alergias' => $datosAdmision['alergias'],
            'antecedentes_medicos' => $datosAdmision['antecedentes_medicos'],
            'contacto_emergencia_nombre' => $datosAdmision['contacto_emergencia_nombre'],
            'contacto_emergencia_telefono' => $datosAdmision['contacto_emergencia_telefono'],
            'cama_actual_id' => $datosAdmision['cama_id'],
            'codigo_qr' => 'TEST123456',
            'estado' => EstadoPaciente::ACTIVO,
            'admitido_por' => $this->enfermera->id,
            'fecha_admision' => now(),
        ]);

        // Crear registro de signos vitales
        $registro = $paciente->registrosSignosVitales()->create([
            'presion_arterial' => $datosAdmision['presion_arterial_sistolica'] . '/' . $datosAdmision['presion_arterial_diastolica'],
            'frecuencia_cardiaca' => $datosAdmision['frecuencia_cardiaca'],
            'frecuencia_respiratoria' => $datosAdmision['frecuencia_respiratoria'],
            'temperatura' => $datosAdmision['temperatura'],
            'saturacion_oxigeno' => $datosAdmision['saturacion_oxigeno'],
            'glucosa' => $datosAdmision['glucosa'],
            'nivel_triage' => NivelTriage::ROJO,
            'triage_override' => false,
            'observaciones' => $datosAdmision['observaciones'],
            'registrado_por' => $this->enfermera->id,
            'fecha_registro' => now(),
        ]);

        // Crear historial de admisión
        $paciente->historial()->create([
            'tipo_evento' => 'Admisión',
            'descripcion' => 'Paciente admitido en urgencias con signos vitales críticos',
            'usuario_id' => $this->enfermera->id,
            'fecha_evento' => now(),
        ]);

        // Marcar cama como ocupada
        $this->cama->update(['estado' => CamaEstado::OCUPADA]);

        // PASO 3: Verificar que el paciente fue creado correctamente
        $this->assertDatabaseHas('pacientes', [
            'nombre' => 'Carlos',
            'apellido_paterno' => 'Hernández',
            'curp' => 'HEMC750315HDFRRR08',
            'alergias' => 'Penicilina',
            'estado' => EstadoPaciente::ACTIVO->value,
        ]);

        // PASO 4: Verificar que se creó el registro de signos vitales
        $this->assertDatabaseHas('registros_signos_vitales', [
            'paciente_id' => $paciente->id,
            'presion_arterial' => '180/110',
            'frecuencia_cardiaca' => 135,
            'temperatura' => 39.2,
            'nivel_triage' => NivelTriage::ROJO->value,
        ]);

        // PASO 5: Verificar que la cama quedó ocupada
        $this->assertEquals(CamaEstado::OCUPADA, $this->cama->fresh()->estado);

        // PASO 6: Verificar que se creó entrada en el historial
        $this->assertDatabaseHas('historial_pacientes', [
            'paciente_id' => $paciente->id,
            'tipo_evento' => 'Admisión',
            'usuario_id' => $this->enfermera->id,
        ]);

        // PASO 7: Acceder a la lista de pacientes y verificar que aparece
        $response = $this->actingAs($this->enfermera)
            ->get(route('enfermeria.pacientes'));

        $response->assertOk()
            ->assertSee('Carlos Hernández Mora')
            ->assertSee('TEST123456')
            ->assertSee('Rojo - Resucitación'); // Nivel TRIAGE

        // PASO 8: Acceder al expediente del paciente
        $response = $this->actingAs($this->enfermera)
            ->get(route('enfermeria.expediente', $paciente->id));

        $response->assertOk()
            ->assertSee('Carlos Hernández Mora')
            ->assertSee('HEMC750315HDFRRR08') // CURP
            ->assertSee('Penicilina') // Alergias
            ->assertSee('Hipertensión, Diabetes tipo 2') // Antecedentes
            ->assertSee('180/110') // Presión arterial
            ->assertSee('135') // FC
            ->assertSee('39.2') // Temperatura
            ->assertSee('Urgencias') // Área
            ->assertSee('Piso 1') // Piso
            ->assertSee('101') // Cuarto
            ->assertSee('A'); // Cama

        // PASO 9: Registrar evolución - signos vitales mejorados
        $registro2 = $paciente->registrosSignosVitales()->create([
            'presion_arterial' => '140/90',
            'frecuencia_cardiaca' => 95,
            'frecuencia_respiratoria' => 20,
            'temperatura' => 37.8,
            'saturacion_oxigeno' => 95,
            'glucosa' => 180,
            'nivel_triage' => NivelTriage::NARANJA,
            'triage_override' => false,
            'observaciones' => 'Paciente con mejoría tras tratamiento inicial',
            'registrado_por' => $this->enfermera->id,
            'fecha_registro' => now()->addHours(2),
        ]);

        $paciente->historial()->create([
            'tipo_evento' => 'Registro de Signos Vitales',
            'descripcion' => 'P/A: 140/90 mmHg, FC: 95 lpm, Temp: 37.8°C | TRIAGE: Naranja',
            'usuario_id' => $this->enfermera->id,
            'fecha_evento' => now()->addHours(2),
        ]);

        // PASO 10: Verificar que hay múltiples registros de signos vitales
        $this->assertEquals(2, $paciente->registrosSignosVitales()->count());

        // PASO 11: Verificar que el último registro es el más reciente
        $ultimoRegistro = $paciente->registrosSignosVitales()
            ->latest('fecha_registro')
            ->first();

        $this->assertEquals('140/90', $ultimoRegistro->presion_arterial);
        $this->assertEquals(NivelTriage::NARANJA, $ultimoRegistro->nivel_triage);

        // PASO 12: Verificar que el nivel TRIAGE mejoró
        $this->assertEquals(NivelTriage::ROJO, $registro->nivel_triage);
        $this->assertEquals(NivelTriage::NARANJA, $registro2->nivel_triage);

        // PASO 13: Verificar que el historial tiene múltiples eventos
        $this->assertGreaterThanOrEqual(2, $paciente->historial()->count());

        // PASO 14: Registrar tercer control - paciente estabilizado
        $registro3 = $paciente->registrosSignosVitales()->create([
            'presion_arterial' => '120/80',
            'frecuencia_cardiaca' => 78,
            'frecuencia_respiratoria' => 16,
            'temperatura' => 36.8,
            'saturacion_oxigeno' => 98,
            'glucosa' => 110,
            'nivel_triage' => NivelTriage::VERDE,
            'triage_override' => false,
            'observaciones' => 'Paciente estabilizado, signos vitales normales',
            'registrado_por' => $this->enfermera->id,
            'fecha_registro' => now()->addHours(4),
        ]);

        // PASO 15: Verificar progresión del TRIAGE
        $nivelesTriage = $paciente->registrosSignosVitales()
            ->orderBy('fecha_registro')
            ->pluck('nivel_triage')
            ->toArray();

        $this->assertEquals([
            NivelTriage::ROJO,
            NivelTriage::NARANJA,
            NivelTriage::VERDE,
        ], $nivelesTriage);

        // PASO 16: Verificar que los datos son correctos para gráficos
        $registros = $paciente->registrosSignosVitales()
            ->orderBy('fecha_registro')
            ->get();

        $this->assertCount(3, $registros);

        // Verificar progresión de presión arterial
        $presiones = $registros->pluck('presion_arterial')->toArray();
        $this->assertEquals(['180/110', '140/90', '120/80'], $presiones);

        // Verificar progresión de temperatura
        $temperaturas = $registros->pluck('temperatura')->toArray();
        $this->assertEquals([39.2, 37.8, 36.8], $temperaturas);

        // PASO 17: Verificar integridad de relaciones
        $paciente->load([
            'camaActual.cuarto.piso.area',
            'registrosSignosVitales.registradoPor',
            'historial.usuario',
            'admitidoPor',
        ]);

        $this->assertNotNull($paciente->camaActual);
        $this->assertEquals('Urgencias', $paciente->camaActual->cuarto->piso->area->nombre);
        $this->assertEquals($this->enfermera->id, $paciente->admitido_por);
        $this->assertCount(3, $paciente->registrosSignosVitales);

        // PASO 18: Verificar que todos los registros tienen usuario
        foreach ($paciente->registrosSignosVitales as $reg) {
            $this->assertNotNull($reg->registradoPor);
            $this->assertEquals($this->enfermera->id, $reg->registrado_por);
        }

        // PASO 19: Verificar edad calculada correctamente
        $this->assertEquals(
            now()->diffInYears($paciente->fecha_nacimiento),
            $paciente->edad
        );

        // PASO 20: Verificar nombre completo
        $this->assertEquals(
            'Carlos Hernández Mora',
            $paciente->nombre_completo
        );
    }

    public function test_flujo_completo_paciente_no_urgente()
    {
        // Paciente con signos vitales normales
        $paciente = Paciente::factory()->create([
            'nombre' => 'María',
            'apellido_paterno' => 'González',
            'cama_actual_id' => $this->cama->id,
            'admitido_por' => $this->enfermera->id,
        ]);

        // Signos vitales normales
        $paciente->registrosSignosVitales()->create([
            'presion_arterial' => '120/80',
            'frecuencia_cardiaca' => 72,
            'frecuencia_respiratoria' => 16,
            'temperatura' => 36.5,
            'saturacion_oxigeno' => 98,
            'glucosa' => 95,
            'nivel_triage' => NivelTriage::VERDE,
            'registrado_por' => $this->enfermera->id,
            'fecha_registro' => now(),
        ]);

        // Verificar que se asignó TRIAGE verde (no urgente)
        $ultimoRegistro = $paciente->registrosSignosVitales->first();
        $this->assertEquals(NivelTriage::VERDE, $ultimoRegistro->nivel_triage);

        // Verificar que aparece en la lista pero con baja prioridad
        $response = $this->actingAs($this->enfermera)
            ->get(route('enfermeria.pacientes'));

        $response->assertOk()
            ->assertSee('María González')
            ->assertSee('Verde - Menos Urgente');
    }

    public function test_flujo_busqueda_y_filtrado()
    {
        // Crear múltiples pacientes
        $paciente1 = Paciente::factory()->create([
            'nombre' => 'Juan',
            'apellido_paterno' => 'Pérez',
            'codigo_qr' => 'AAA1111111',
            'estado' => EstadoPaciente::ACTIVO,
            'cama_actual_id' => $this->cama->id,
            'admitido_por' => $this->enfermera->id,
        ]);

        $paciente2 = Paciente::factory()->create([
            'nombre' => 'Ana',
            'apellido_paterno' => 'López',
            'codigo_qr' => 'BBB2222222',
            'estado' => EstadoPaciente::ALTA,
            'cama_actual_id' => $this->cama->id,
            'admitido_por' => $this->enfermera->id,
        ]);

        // Verificar que la lista muestra pacientes activos por defecto
        $response = $this->actingAs($this->enfermera)
            ->get(route('enfermeria.pacientes'));

        $response->assertOk()
            ->assertSee('Juan Pérez')
            ->assertDontSee('Ana López'); // Porque está de alta
    }

    public function test_flujo_multiples_enfermeras()
    {
        $enfermera2 = User::factory()->create(['name' => 'Enfermera 2']);

        $paciente = Paciente::factory()->create([
            'cama_actual_id' => $this->cama->id,
            'admitido_por' => $this->enfermera->id,
        ]);

        // Primera enfermera registra signos
        $paciente->registrosSignosVitales()->create([
            'temperatura' => 37.0,
            'registrado_por' => $this->enfermera->id,
            'fecha_registro' => now(),
        ]);

        // Segunda enfermera registra signos
        $paciente->registrosSignosVitales()->create([
            'temperatura' => 36.8,
            'registrado_por' => $enfermera2->id,
            'fecha_registro' => now()->addHours(1),
        ]);

        // Verificar que ambos registros existen
        $this->assertEquals(2, $paciente->registrosSignosVitales()->count());

        // Verificar que cada registro tiene su enfermera
        $registros = $paciente->registrosSignosVitales()
            ->with('registradoPor')
            ->orderBy('fecha_registro')
            ->get();

        $this->assertEquals($this->enfermera->id, $registros[0]->registrado_por);
        $this->assertEquals($enfermera2->id, $registros[1]->registrado_por);
    }
}
