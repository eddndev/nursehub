# Sprint 2: Registro Clínico Electrónico Básico

**Epic:** Epic #2 - Módulo RCE (Registro Clínico Electrónico)
**Duración:** 3 semanas
**Fecha de inicio:** 2025-11-22
**Fecha de finalización:** 2025-12-13
**Estado:** Planificado

---

## 1. Objetivos del Sprint

### Objetivo Principal
Implementar el módulo básico de Registro Clínico Electrónico (RCE) que permita a los enfermeros registrar pacientes, realizar TRIAGE automatizado, capturar signos vitales y mantener una hoja de enfermería digital.

### Objetivos Específicos
1. Crear el modelo de datos completo para pacientes y registros clínicos
2. Implementar el flujo de admisión de pacientes en Urgencias con generación de código QR
3. Desarrollar el sistema de TRIAGE automatizado basado en signos vitales
4. Construir la hoja de enfermería digital para registro de signos vitales
5. Implementar visualización de tendencias de signos vitales con gráficos
6. Crear el dashboard de pacientes para Urgencias y Pisos

### Métricas de Éxito
- Capacidad de registrar un paciente nuevo en menos de 2 minutos
- Generación automática de código QR único por paciente
- Clasificación TRIAGE automática con posibilidad de override manual
- Registro de signos vitales con timestamp automático
- Visualización gráfica de tendencias de signos vitales
- 100% de cobertura de tests en funcionalidades críticas

---

## 2. Alcance del Sprint

### Historias de Usuario Incluidas

#### **Como Enfermera de Urgencias**
- [ ] US-RCE-001: Registrar nuevo paciente con datos demográficos básicos
- [ ] US-RCE-002: Generación automática de código QR único por paciente
- [ ] US-RCE-003: Ingresar signos vitales iniciales (PA, FC, FR, Temp, SpO2)
- [ ] US-RCE-004: Sugerencia automática de nivel de TRIAGE basado en signos vitales
- [ ] US-RCE-005: Override manual del nivel de TRIAGE sugerido
- [ ] US-RCE-006: Visualizar lista de pacientes en espera ordenados por TRIAGE
- [ ] US-RCE-007: Registrar alergias y antecedentes médicos del paciente

#### **Como Enfermero de Piso**
- [ ] US-RCE-008: Escanear código QR de pulsera del paciente para acceder a expediente
- [ ] US-RCE-009: Registrar signos vitales en hoja de enfermería digital
- [ ] US-RCE-010: Visualizar gráficos de tendencias de signos vitales
- [ ] US-RCE-011: Visualizar historial cronológico completo del paciente
- [ ] US-RCE-012: Agregar observaciones a cada registro

#### **Como Jefe de Piso**
- [ ] US-RCE-013: Ver dashboard con todos los pacientes activos en el piso
- [ ] US-RCE-014: Filtrar pacientes por nivel de TRIAGE o estado
- [ ] US-RCE-015: Ver qué enfermero está asignado a cada paciente

### Funcionalidades Excluidas (Para Sprints Futuros)
- Balances de líquidos (Sprint 3)
- Escalas de valoración (EVA, Braden) (Sprint 3)
- Diagnósticos de enfermería y planes de cuidado (Sprint 3)
- Reportes de calidad de registros clínicos (Sprint 6)

---

## 3. Arquitectura Técnica

### 3.1 Modelos de Datos

#### **Paciente**
```php
Schema::create('pacientes', function (Blueprint $table) {
    $table->id();
    $table->string('codigo_qr')->unique(); // Generado automáticamente
    $table->string('nombre');
    $table->string('apellido_paterno');
    $table->string('apellido_materno')->nullable();
    $table->enum('sexo', ['M', 'F', 'Otro']);
    $table->date('fecha_nacimiento');
    $table->string('curp', 18)->unique()->nullable();
    $table->string('telefono', 15)->nullable();
    $table->string('contacto_emergencia_nombre')->nullable();
    $table->string('contacto_emergencia_telefono', 15)->nullable();
    $table->text('alergias')->nullable();
    $table->text('antecedentes_medicos')->nullable();
    $table->enum('estado', ['activo', 'dado_alta', 'transferido', 'fallecido'])->default('activo');
    $table->foreignId('cama_actual_id')->nullable()->constrained('camas')->nullOnDelete();
    $table->foreignId('admitido_por')->constrained('users'); // Enfermero que admitió
    $table->timestamp('fecha_admision');
    $table->timestamp('fecha_alta')->nullable();
    $table->timestamps();
    $table->softDeletes();
});
```

#### **RegistroSignosVitales**
```php
Schema::create('registros_signos_vitales', function (Blueprint $table) {
    $table->id();
    $table->foreignId('paciente_id')->constrained('pacientes')->cascadeOnDelete();
    $table->foreignId('registrado_por')->constrained('users'); // Enfermero
    $table->decimal('presion_arterial_sistolica', 5, 2)->nullable();
    $table->decimal('presion_arterial_diastolica', 5, 2)->nullable();
    $table->integer('frecuencia_cardiaca')->nullable(); // latidos por minuto
    $table->integer('frecuencia_respiratoria')->nullable(); // respiraciones por minuto
    $table->decimal('temperatura', 4, 2)->nullable(); // grados Celsius
    $table->decimal('saturacion_oxigeno', 5, 2)->nullable(); // % SpO2
    $table->decimal('glucosa', 6, 2)->nullable(); // mg/dL (opcional)
    $table->enum('nivel_triage', ['rojo', 'naranja', 'amarillo', 'verde', 'azul'])->nullable();
    $table->boolean('triage_override')->default(false); // Indica si fue override manual
    $table->text('observaciones')->nullable();
    $table->timestamp('fecha_registro');
    $table->timestamps();
});
```

#### **HistorialPaciente**
```php
Schema::create('historial_pacientes', function (Blueprint $table) {
    $table->id();
    $table->foreignId('paciente_id')->constrained('pacientes')->cascadeOnDelete();
    $table->foreignId('usuario_id')->constrained('users'); // Quién hizo la acción
    $table->enum('tipo_evento', [
        'admision',
        'signos_vitales',
        'cambio_cama',
        'cambio_estado',
        'nota_enfermeria',
        'alta'
    ]);
    $table->text('descripcion');
    $table->json('metadata')->nullable(); // Datos adicionales del evento
    $table->timestamp('fecha_evento');
    $table->timestamps();
});
```

### 3.2 Enums PHP

#### **PacienteEstado**
```php
enum PacienteEstado: string {
    case ACTIVO = 'activo';
    case DADO_ALTA = 'dado_alta';
    case TRANSFERIDO = 'transferido';
    case FALLECIDO = 'fallecido';
}
```

#### **NivelTriage**
```php
enum NivelTriage: string {
    case ROJO = 'rojo';       // Resucitación - Inmediato
    case NARANJA = 'naranja'; // Emergencia - 10-15 min
    case AMARILLO = 'amarillo'; // Urgente - 30-60 min
    case VERDE = 'verde';     // Menos urgente - 1-2 horas
    case AZUL = 'azul';       // No urgente - 2-4 horas

    public function getLabel(): string {
        return match($this) {
            self::ROJO => 'Resucitación',
            self::NARANJA => 'Emergencia',
            self::AMARILLO => 'Urgente',
            self::VERDE => 'Menos Urgente',
            self::AZUL => 'No Urgente',
        };
    }

    public function getTiempoEspera(): string {
        return match($this) {
            self::ROJO => 'Inmediato',
            self::NARANJA => '10-15 min',
            self::AMARILLO => '30-60 min',
            self::VERDE => '1-2 horas',
            self::AZUL => '2-4 horas',
        };
    }
}
```

### 3.3 Componentes Livewire

#### **AdmisionPaciente** (`app/Livewire/Urgencias/AdmisionPaciente.php`)
- Formulario de admisión con datos demográficos
- Generación automática de código QR
- Registro de signos vitales iniciales
- Clasificación TRIAGE automática con override manual
- Asignación a cama (opcional)

#### **ListaPacientes** (`app/Livewire/Enfermeria/ListaPacientes.php`)
- Dashboard de pacientes activos
- Filtros: TRIAGE, área, búsqueda por nombre/CURP/QR
- Ordenamiento por TRIAGE (prioridad)
- Acceso rápido a expediente

#### **ExpedientePaciente** (`app/Livewire/Enfermeria/ExpedientePaciente.php`)
- Vista completa del expediente del paciente
- Sección de datos demográficos
- Historial completo de eventos
- Registro de signos vitales con gráficos de tendencias
- Notas de enfermería
- Acceso por escaneo de código QR

#### **RegistroSignosVitales** (`app/Livewire/Enfermeria/RegistroSignosVitales.php`)
- Formulario de captura de signos vitales
- Validaciones en tiempo real
- Timestamp automático
- Recalculación de TRIAGE (si aplica)
- Campo de observaciones

#### **GraficoTendencias** (`app/Livewire/Enfermeria/GraficoTendencias.php`)
- Gráficos interactivos con Chart.js o ApexCharts
- Selección de signo vital a visualizar
- Rango de fechas configurable
- Exportación a imagen

### 3.4 Lógica de Negocio

#### **TriageCalculator** (`app/Services/TriageCalculator.php`)
Servicio para calcular el nivel de TRIAGE basado en signos vitales según protocolo internacional:

```php
class TriageCalculator {
    public static function calcular(array $signosVitales): NivelTriage {
        // Criterios de ROJO (Resucitación)
        if (self::esCritico($signosVitales)) {
            return NivelTriage::ROJO;
        }

        // Criterios de NARANJA (Emergencia)
        if (self::esEmergencia($signosVitales)) {
            return NivelTriage::NARANJA;
        }

        // Criterios de AMARILLO (Urgente)
        if (self::esUrgente($signosVitales)) {
            return NivelTriage::AMARILLO;
        }

        // Criterios de VERDE (Menos urgente)
        if (self::esMenosUrgente($signosVitales)) {
            return NivelTriage::VERDE;
        }

        // Por defecto AZUL (No urgente)
        return NivelTriage::AZUL;
    }

    private static function esCritico(array $sv): bool {
        // Criterios críticos
        return ($sv['frecuencia_cardiaca'] ?? 0) > 140 ||
               ($sv['frecuencia_cardiaca'] ?? 0) < 40 ||
               ($sv['presion_arterial_sistolica'] ?? 0) > 220 ||
               ($sv['presion_arterial_sistolica'] ?? 0) < 80 ||
               ($sv['saturacion_oxigeno'] ?? 100) < 85 ||
               ($sv['temperatura'] ?? 36.5) > 41.0;
    }

    // ... resto de métodos
}
```

#### **QRCodeGenerator** (`app/Services/QRCodeGenerator.php`)
Servicio para generar códigos QR únicos por paciente:

```php
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QRCodeGenerator {
    public static function generarCodigoPaciente(): string {
        // Formato: NHUB-{timestamp}-{random}
        return 'NHUB-' . now()->format('YmdHis') . '-' . strtoupper(Str::random(6));
    }

    public static function generarImagenQR(string $codigo): string {
        // Genera imagen QR en base64
        return base64_encode(QrCode::format('png')
            ->size(300)
            ->errorCorrection('H')
            ->generate($codigo));
    }
}
```

### 3.5 Rutas

```php
// Urgencias
Route::middleware(['auth', 'role:enfermero,jefe_piso,coordinador'])->prefix('urgencias')->group(function () {
    Route::get('/admision', AdmisionPaciente::class)->name('urgencias.admision');
    Route::get('/lista', ListaPacientes::class)->name('urgencias.lista');
});

// Enfermería General
Route::middleware(['auth', 'role:enfermero,jefe_piso,coordinador'])->prefix('enfermeria')->group(function () {
    Route::get('/pacientes', ListaPacientes::class)->name('enfermeria.pacientes');
    Route::get('/paciente/{id}', ExpedientePaciente::class)->name('enfermeria.expediente');
    Route::get('/signos-vitales/{pacienteId}', RegistroSignosVitales::class)->name('enfermeria.signos-vitales');
});

// Escaneo QR - Acceso rápido
Route::middleware(['auth', 'role:enfermero,jefe_piso,coordinador'])->group(function () {
    Route::get('/qr/{codigo}', function($codigo) {
        $paciente = Paciente::where('codigo_qr', $codigo)->firstOrFail();
        return redirect()->route('enfermeria.expediente', $paciente->id);
    })->name('qr.scan');
});
```

---

## 4. Issues de GitHub

### Issue #19: Crear modelos y migraciones para módulo RCE
**Prioridad:** Alta
**Etiquetas:** `enhancement`, `database`, `sprint-2`
**Estimación:** 3 puntos

**Descripción:**
Crear todos los modelos Eloquent, migraciones, factories, seeders y enums necesarios para el módulo de Registro Clínico Electrónico.

**Tareas:**
- [ ] Crear migración `create_pacientes_table`
- [ ] Crear migración `create_registros_signos_vitales_table`
- [ ] Crear migración `create_historial_pacientes_table`
- [ ] Crear modelo `Paciente` con relaciones
- [ ] Crear modelo `RegistroSignosVitales` con relaciones
- [ ] Crear modelo `HistorialPaciente` con relaciones
- [ ] Crear enum `PacienteEstado`
- [ ] Crear enum `NivelTriage`
- [ ] Crear enum `TipoEventoHistorial`
- [ ] Crear `PacienteFactory`
- [ ] Crear `RegistroSignosVitalesFactory`
- [ ] Crear seeders de prueba

**Criterios de Aceptación:**
- Todas las migraciones ejecutan sin errores
- Relaciones Eloquent funcionan correctamente
- Factories generan datos realistas
- Tests de modelos pasan al 100%

---

### Issue #20: Implementar servicios de TRIAGE y generación de QR
**Prioridad:** Alta
**Etiquetas:** `enhancement`, `business-logic`, `sprint-2`
**Estimación:** 5 puntos

**Descripción:**
Implementar la lógica de negocio para clasificación automática de TRIAGE basada en signos vitales y generación de códigos QR únicos.

**Tareas:**
- [ ] Crear servicio `TriageCalculator`
- [ ] Implementar método `calcular()` con protocolo internacional
- [ ] Implementar métodos privados para cada nivel de criticidad
- [ ] Crear tests unitarios para todos los escenarios de TRIAGE
- [ ] Instalar paquete `simplesoftwareio/simple-qrcode`
- [ ] Crear servicio `QRCodeGenerator`
- [ ] Implementar generación de código único
- [ ] Implementar generación de imagen QR
- [ ] Crear tests para generación de QR

**Criterios de Aceptación:**
- Clasificación TRIAGE precisa según signos vitales
- Códigos QR únicos y no repetibles
- 100% cobertura de tests en servicios
- Documentación clara de protocolo TRIAGE

---

### Issue #21: Implementar componente de admisión de pacientes
**Prioridad:** Alta
**Etiquetas:** `enhancement`, `livewire`, `sprint-2`
**Estimación:** 8 puntos

**Descripción:**
Crear el componente Livewire para admisión de pacientes en Urgencias con registro de datos demográficos, signos vitales iniciales y clasificación TRIAGE.

**Tareas:**
- [ ] Crear componente `AdmisionPaciente`
- [ ] Implementar formulario de datos demográficos
- [ ] Implementar formulario de signos vitales iniciales
- [ ] Integrar `TriageCalculator` para clasificación automática
- [ ] Implementar override manual de TRIAGE
- [ ] Integrar `QRCodeGenerator` para código único
- [ ] Implementar selección de cama (opcional)
- [ ] Crear registro en `HistorialPaciente` al admitir
- [ ] Crear vista Blade responsiva
- [ ] Implementar validaciones frontend y backend
- [ ] Crear tests de feature para admisión

**Criterios de Aceptación:**
- Formulario intuitivo con validaciones en tiempo real
- Código QR se genera automáticamente
- TRIAGE se calcula automáticamente con posibilidad de override
- Confirmación visual del paciente admitido
- Tests cubren todos los flujos (con/sin cama, con/sin override)

---

### Issue #22: Implementar dashboard de lista de pacientes
**Prioridad:** Alta
**Etiquetas:** `enhancement`, `livewire`, `sprint-2`
**Estimación:** 5 puntos

**Descripción:**
Crear componente para visualizar lista de pacientes activos con filtros por TRIAGE, búsqueda y ordenamiento por prioridad.

**Tareas:**
- [ ] Crear componente `ListaPacientes`
- [ ] Implementar tabla de pacientes con datos clave
- [ ] Implementar filtro por nivel de TRIAGE
- [ ] Implementar búsqueda por nombre, CURP o código QR
- [ ] Implementar ordenamiento por TRIAGE (prioridad)
- [ ] Mostrar badge visual por nivel de TRIAGE
- [ ] Mostrar tiempo de espera desde admisión
- [ ] Implementar paginación
- [ ] Crear vista Blade responsiva
- [ ] Crear tests de feature

**Criterios de Aceptación:**
- Lista se ordena automáticamente por prioridad TRIAGE
- Filtros funcionan en tiempo real
- Búsqueda encuentra por múltiples criterios
- Badge de TRIAGE visualmente claro (colores)
- Performance < 2s con 100+ pacientes

---

### Issue #23: Implementar expediente del paciente
**Prioridad:** Alta
**Etiquetas:** `enhancement`, `livewire`, `sprint-2`
**Estimación:** 8 puntos

**Descripción:**
Crear componente para visualizar el expediente completo del paciente con datos demográficos, historial de eventos y acceso rápido via QR.

**Tareas:**
- [ ] Crear componente `ExpedientePaciente`
- [ ] Implementar sección de datos demográficos (modo lectura)
- [ ] Implementar timeline de historial de eventos
- [ ] Mostrar último registro de signos vitales
- [ ] Implementar botón de acceso a registro de signos vitales
- [ ] Implementar sección de alergias (destacada)
- [ ] Implementar sección de antecedentes médicos
- [ ] Crear ruta de acceso rápido via código QR
- [ ] Crear vista Blade responsiva
- [ ] Crear tests de feature

**Criterios de Aceptación:**
- Expediente se carga en < 1s
- Historial ordenado cronológicamente (más reciente primero)
- Alergias visualmente destacadas (color rojo/warning)
- Acceso via QR redirige correctamente al expediente
- Tests cubren acceso normal y via QR

---

### Issue #24: Implementar registro de signos vitales
**Prioridad:** Alta
**Etiquetas:** `enhancement`, `livewire`, `sprint-2`
**Estimación:** 5 puntos

**Descripción:**
Crear componente para registro de signos vitales en la hoja de enfermería digital con validaciones y timestamp automático.

**Tareas:**
- [ ] Crear componente `RegistroSignosVitales`
- [ ] Implementar formulario de signos vitales
- [ ] Implementar validaciones (rangos normales)
- [ ] Implementar timestamp automático
- [ ] Implementar campo de observaciones
- [ ] Crear registro en `HistorialPaciente` automáticamente
- [ ] Mostrar alertas si valores fuera de rango
- [ ] Recalcular TRIAGE si es paciente de Urgencias
- [ ] Crear vista Blade responsiva
- [ ] Crear tests de feature

**Criterios de Aceptación:**
- Formulario simple y rápido de llenar
- Validaciones indican rangos normales
- Timestamp se registra automáticamente
- TRIAGE se actualiza si valores críticos
- Tests cubren valores normales y críticos

---

### Issue #25: Implementar visualización de tendencias con gráficos
**Prioridad:** Media
**Etiquetas:** `enhancement`, `livewire`, `charts`, `sprint-2`
**Estimación:** 8 puntos

**Descripción:**
Crear componente para visualizar gráficos de tendencias de signos vitales del paciente usando Chart.js o ApexCharts.

**Tareas:**
- [ ] Evaluar e instalar librería de gráficos (Chart.js vs ApexCharts)
- [ ] Crear componente `GraficoTendencias`
- [ ] Implementar selector de signo vital a visualizar
- [ ] Implementar selector de rango de fechas
- [ ] Generar dataset desde `RegistroSignosVitales`
- [ ] Renderizar gráfico interactivo
- [ ] Implementar marcadores para valores fuera de rango
- [ ] Implementar tooltip con detalles al hover
- [ ] Crear vista Blade responsiva
- [ ] Crear tests de feature

**Criterios de Aceptación:**
- Gráficos se renderizan correctamente en móvil y desktop
- Selector cambia el gráfico sin recargar página
- Valores críticos se marcan visualmente
- Performance < 2s con 100+ registros
- Tests validan generación correcta de datasets

---

### Issue #26: Implementar navegación y rutas del módulo RCE
**Prioridad:** Media
**Etiquetas:** `enhancement`, `navigation`, `sprint-2`
**Estimación:** 3 puntos

**Descripción:**
Crear rutas web y agregar enlaces de navegación en el sidebar para acceder a las funcionalidades del módulo RCE.

**Tareas:**
- [ ] Crear grupo de rutas para Urgencias
- [ ] Crear grupo de rutas para Enfermería General
- [ ] Crear ruta de acceso rápido via QR
- [ ] Actualizar `admin-sidebar.blade.php` con sección RCE
- [ ] Agregar ícono de pacientes
- [ ] Agregar enlace a Admisión de Pacientes
- [ ] Agregar enlace a Lista de Pacientes
- [ ] Crear tests de rutas

**Criterios de Aceptación:**
- Rutas protegidas por middleware de roles
- Navegación visible para roles autorizados
- Enlaces activos resaltados correctamente
- Tests verifican acceso autorizado y no autorizado

---

### Issue #27: Testing integral del módulo RCE
**Prioridad:** Alta
**Etiquetas:** `testing`, `sprint-2`
**Estimación:** 5 puntos

**Descripción:**
Crear suite completa de tests unitarios, de integración y de feature para el módulo RCE.

**Tareas:**
- [ ] Crear `PacienteTest` (modelo)
- [ ] Crear `RegistroSignosVitalesTest` (modelo)
- [ ] Crear `TriageCalculatorTest` (servicio)
- [ ] Crear `QRCodeGeneratorTest` (servicio)
- [ ] Crear `AdmisionPacienteTest` (componente)
- [ ] Crear `ListaPacientesTest` (componente)
- [ ] Crear `ExpedientePacienteTest` (componente)
- [ ] Crear `RegistroSignosVitalesTest` (componente)
- [ ] Crear `GraficoTendenciasTest` (componente)
- [ ] Alcanzar 90%+ de cobertura de código

**Criterios de Aceptación:**
- Todos los tests pasan
- Cobertura > 90% en código crítico
- Tests documentan casos de uso principales
- Suite completa ejecuta en < 2 minutos

---

### Issue #28: Documentación del módulo RCE
**Prioridad:** Baja
**Etiquetas:** `documentation`, `sprint-2`
**Estimación:** 2 puntos

**Descripción:**
Documentar el módulo RCE con diagramas de flujo, ejemplos de uso y guía para desarrolladores.

**Tareas:**
- [ ] Crear `docs/modules/01-rce.md`
- [ ] Documentar flujo de admisión de pacientes
- [ ] Documentar protocolo de TRIAGE
- [ ] Documentar estructura de datos
- [ ] Crear diagramas de flujo (Mermaid)
- [ ] Documentar API de servicios
- [ ] Crear guía de uso para enfermeros

**Criterios de Aceptación:**
- Documentación clara y completa
- Diagramas visuales de flujos principales
- Ejemplos de código funcionales
- Guía de uso no técnica para usuarios finales

---

## 5. Dependencias y Riesgos

### Dependencias Técnicas
- ✅ Sprint 1 completado (infraestructura base, autenticación, roles)
- ✅ Modelo `User` con roles de enfermería
- ✅ Modelo `Cama` para asignación de pacientes
- 🆕 Librería QR Code (`simplesoftwareio/simple-qrcode`)
- 🆕 Librería de gráficos (Chart.js o ApexCharts)

### Riesgos Identificados

| Riesgo | Probabilidad | Impacto | Mitigación |
|--------|--------------|---------|------------|
| Algoritmo de TRIAGE incorrecto | Media | Alto | Revisar protocolo internacional Manchester/ESI, validar con enfermeros reales |
| Performance de gráficos con muchos datos | Media | Medio | Limitar rango de fechas por defecto, implementar paginación de datos |
| Generación de códigos QR duplicados | Baja | Alto | Usar timestamp + random + constraint único en BD |
| Complejidad del expediente del paciente | Media | Medio | Implementar por fases, empezar con funcionalidad básica |

---

## 6. Definición de "Hecho" (Definition of Done)

Una historia de usuario se considera completada cuando:

- [x] Código implementado y funcionando
- [x] Tests unitarios y de integración pasan al 100%
- [x] Validaciones frontend y backend implementadas
- [x] Vista responsive (móvil, tablet, desktop)
- [x] Modo oscuro soportado
- [x] Documentación técnica actualizada
- [x] Code review aprobado
- [x] Migración ejecutada en ambiente de desarrollo
- [x] Demo funcional para stakeholders

---

## 7. Cronograma Tentativo

### Semana 1 (22-29 Nov)
- Día 1-2: Issue #19 - Modelos y migraciones
- Día 3-4: Issue #20 - Servicios TRIAGE y QR
- Día 5: Issue #21 (inicio) - Componente admisión

### Semana 2 (29 Nov - 6 Dic)
- Día 1-3: Issue #21 (fin) - Componente admisión
- Día 4-5: Issue #22 - Dashboard de pacientes

### Semana 3 (6-13 Dic)
- Día 1-2: Issue #23 - Expediente del paciente
- Día 3: Issue #24 - Registro signos vitales
- Día 4-5: Issue #25 - Gráficos de tendencias
- Día 5: Issue #26, #27, #28 - Navegación, tests, docs

---

## 8. Resultado del Sprint

*(Se completará al finalizar el sprint)*

### Estadísticas Finales
- Issues completados: ?/10
- Porcentaje de completitud: ?%
- Tests creados: ?
- Tests pasando: ?
- Cobertura de código: ?%

### Retrospectiva

#### ¿Qué funcionó bien?
- (Por completar)

#### ¿Qué se puede mejorar?
- (Por completar)

---

**Notas:**
- Este sprint sienta las bases del módulo clínico de NurseHub
- La clasificación TRIAGE automática es crítica para la operación de Urgencias
- Se debe validar el protocolo TRIAGE con personal médico real antes de producción
- Los códigos QR deben ser imprimibles en pulseras de pacientes (tamaño adecuado)
