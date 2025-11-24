# Sprint 4: Gestión de Turnos y Asignación de Pacientes

**Epic:** Epic #3 - Módulo de Asignación y Turnos
**Duración:** 2 semanas
**Fecha de inicio:** 2025-11-25
**Fecha de finalización:** 2025-12-08
**Estado:** Planificado
**Épica Maestra en GitHub:** [Issue #36](https://github.com/eddndev/nursehub/issues/36)

---

## 1. Objetivos del Sprint

### Objetivo Principal
Implementar el sistema de gestión de turnos y asignación de enfermeros a pacientes, permitiendo a los jefes de piso organizar el trabajo diario, distribuir la carga equitativamente y facilitar el relevo entre turnos, mientras que los enfermeros pueden visualizar claramente sus pacientes asignados.

### Objetivos Específicos
1. Crear el modelo de datos para gestión de turnos por área y fecha.
2. Implementar el sistema de asignación de enfermeros a pacientes con trazabilidad completa.
3. Desarrollar interfaz intuitiva para jefes de piso que permita asignaciones rápidas.
4. Construir dashboard personalizado para que enfermeros vean sus pacientes asignados.
5. Implementar sistema de relevo de turno con registro de novedades y pendientes.
6. Crear visualizaciones de carga de trabajo para distribución equitativa.

### Métricas de Éxito
- Crear un turno completo en menos de 1 minuto.
- Asignar 10 pacientes a enfermeros en menos de 3 minutos.
- Visualización clara y en tiempo real de la carga de trabajo por enfermero.
- 100% de trazabilidad de quién asignó a quién y cuándo.
- Acceso al expediente del paciente en 1 clic desde dashboard de enfermero.

---

## 2. Alcance del Sprint

### Historias de Usuario

#### **Gestión de Turnos**
- [ ] US-ASIG-001: Como Jefe de Piso, quiero crear turnos para mi área con fecha y tipo (matutino/vespertino/nocturno) para organizar el trabajo diario.
- [ ] US-ASIG-002: Como Jefe de Piso, quiero ver la lista de enfermeros disponibles para el turno para realizar asignaciones informadas.
- [ ] US-ASIG-003: Como Jefe de Piso, quiero poder cerrar un turno al finalizar para marcar su conclusión.

#### **Asignación de Pacientes**
- [ ] US-ASIG-004: Como Jefe de Piso, quiero asignar enfermeros a pacientes de forma manual para distribuir la carga de trabajo.
- [ ] US-ASIG-005: Como Jefe de Piso, quiero ver la carga de trabajo de cada enfermero (número de pacientes asignados) para distribuir equitativamente.
- [ ] US-ASIG-006: Como Jefe de Piso, quiero reasignar pacientes entre enfermeros si es necesario para ajustar la carga durante el turno.
- [ ] US-ASIG-007: Como Jefe de Piso, quiero ver el historial de asignaciones de un paciente para saber quién lo ha atendido.
- [ ] US-ASIG-008: Como Jefe de Piso, quiero que el sistema libere automáticamente asignaciones cuando un paciente es dado de alta para mantener datos actualizados.

#### **Dashboard de Enfermeros**
- [ ] US-ASIG-009: Como Enfermero, quiero ver mi asignación del día en un dashboard personalizado para saber qué pacientes debo atender.
- [ ] US-ASIG-010: Como Enfermero, quiero ver la ubicación (área, piso, cuarto, cama) de cada paciente asignado para encontrarlos fácilmente.
- [ ] US-ASIG-011: Como Enfermero, quiero acceder rápidamente al expediente clínico de mis pacientes asignados para revisar su información.
- [ ] US-ASIG-012: Como Enfermero, quiero ver alertas visuales de pacientes críticos (Triage I-II) en mis asignaciones para priorizar atención.

#### **Relevo de Turno**
- [ ] US-ASIG-013: Como Jefe de Piso, quiero registrar novedades y pendientes del turno para comunicar al siguiente turno.
- [ ] US-ASIG-014: Como Jefe de Piso, quiero ver las novedades del turno anterior para conocer el estado de los pacientes al iniciar.
- [ ] US-ASIG-015: Como Enfermero, quiero ver las novedades del relevo relacionadas con mis pacientes asignados para estar informado.

#### **Supervisión (Coordinador)**
- [ ] US-ASIG-016: Como Coordinador General, quiero ver un dashboard con todos los turnos activos por área para supervisar la operación completa.
- [ ] US-ASIG-017: Como Coordinador General, quiero ver indicadores de carga de trabajo por área para identificar áreas sobrecargadas.

---

## 2.1 Issues del Sprint

Este sprint se divide en 4 issues técnicas:

| Issue | Título | Tipo | Prioridad | Historias |
|-------|--------|------|-----------|-----------|
| [#37](https://github.com/eddndev/nursehub/issues/37) | Infraestructura de Datos para Gestión de Turnos y Asignaciones | Chore | Critical | Todas |
| [#38](https://github.com/eddndev/nursehub/issues/38) | Gestor de Turnos y Asignaciones para Jefe de Piso | Feature | High | US-ASIG-001 a 007 |
| [#39](https://github.com/eddndev/nursehub/issues/39) | Dashboard de Asignaciones para Enfermeros | Feature | High | US-ASIG-009 a 012 |
| [#40](https://github.com/eddndev/nursehub/issues/40) | Sistema de Relevo de Turno con Novedades | Feature | Medium | US-ASIG-013 a 015 |

**Nota:** Las historias US-ASIG-016 y US-ASIG-017 (Dashboard de Coordinador) se consideran "Could Have" y se implementarán si hay tiempo disponible.

---

## 3. Arquitectura Técnica

### 3.1 Nuevos Modelos

#### **Turno**
```php
Schema::create('turnos', function (Blueprint $table) {
    $table->id();
    $table->foreignId('area_id')->constrained('areas')->onDelete('cascade');
    $table->date('fecha');
    $table->enum('tipo', ['matutino', 'vespertino', 'nocturno']);
    $table->time('hora_inicio'); // 07:00, 15:00, 23:00
    $table->time('hora_fin'); // 15:00, 23:00, 07:00
    $table->foreignId('jefe_turno_id')->constrained('users'); // Jefe de piso responsable
    $table->text('novedades_relevo')->nullable(); // Información para el siguiente turno
    $table->enum('estado', ['activo', 'cerrado'])->default('activo');
    $table->timestamp('cerrado_at')->nullable();
    $table->foreignId('cerrado_por')->nullable()->constrained('users');
    $table->timestamps();

    // Un solo turno por área/fecha/tipo
    $table->unique(['area_id', 'fecha', 'tipo']);
});
```

#### **AsignacionPaciente**
```php
Schema::create('asignacion_pacientes', function (Blueprint $table) {
    $table->id();
    $table->foreignId('turno_id')->constrained('turnos')->onDelete('cascade');
    $table->foreignId('enfermero_id')->constrained('enfermeros')->onDelete('cascade');
    $table->foreignId('paciente_id')->constrained('pacientes')->onDelete('cascade');
    $table->timestamp('fecha_hora_asignacion');
    $table->timestamp('fecha_hora_liberacion')->nullable(); // Cuando se reasigna o paciente es dado de alta
    $table->foreignId('asignado_por')->constrained('users'); // Quién hizo la asignación
    $table->foreignId('liberado_por')->nullable()->constrained('users');
    $table->text('motivo_liberacion')->nullable(); // "Reasignación", "Alta del paciente", etc.
    $table->timestamps();

    // Índices para consultas frecuentes
    $table->index(['turno_id', 'enfermero_id']);
    $table->index(['paciente_id', 'fecha_hora_liberacion']); // Para asignación actual
});
```

### 3.2 Relaciones de Modelos

**Turno:**
- belongsTo: Area
- belongsTo: User (jefe_turno)
- hasMany: AsignacionPaciente

**AsignacionPaciente:**
- belongsTo: Turno
- belongsTo: Enfermero
- belongsTo: Paciente
- belongsTo: User (asignado_por)
- belongsTo: User (liberado_por)

**Paciente (modificaciones):**
- hasMany: AsignacionPaciente
- hasOne: AsignacionPaciente (asignación actual - donde liberado es null)

**Enfermero (modificaciones):**
- hasMany: AsignacionPaciente

### 3.3 Enums

#### **TipoTurno**
```php
enum TipoTurno: string
{
    case MATUTINO = 'matutino';     // 07:00 - 15:00
    case VESPERTINO = 'vespertino'; // 15:00 - 23:00
    case NOCTURNO = 'nocturno';     // 23:00 - 07:00

    public function getLabel(): string;
    public function getHoraInicio(): string;
    public function getHoraFin(): string;
    public function getColor(): string;
}
```

#### **EstadoTurno**
```php
enum EstadoTurno: string
{
    case ACTIVO = 'activo';
    case CERRADO = 'cerrado';

    public function getLabel(): string;
    public function getColor(): string;
}
```

### 3.4 Componentes Livewire

#### **GestorTurnos** (Jefe de Piso)
**Propósito:** Crear turnos y asignar enfermeros a pacientes

**Propiedades:**
- $areaId
- $turnoActual
- $enfermeros (disponibles)
- $pacientes (del área)
- $asignaciones (del turno)

**Métodos:**
- crearTurno($fecha, $tipo)
- asignarPaciente($enfermeroId, $pacienteId)
- reasignarPaciente($asignacionId, $nuevoEnfermeroId)
- liberarAsignacion($asignacionId, $motivo)
- cerrarTurno($novedades)

**Vista:**
- Formulario de creación de turno
- Lista de enfermeros con contador de pacientes asignados
- Lista de pacientes con selector de enfermero
- Tarjetas de asignaciones agrupadas por enfermero

---

#### **MisAsignaciones** (Enfermero)
**Propósito:** Dashboard personalizado con pacientes asignados al enfermero

**Propiedades:**
- $enfermeroId
- $turnoActual
- $pacientesAsignados

**Métodos:**
- refrescarAsignaciones()
- verExpediente($pacienteId)

**Vista:**
- Header con info del turno y novedades
- Grid de cards de pacientes asignados
  - Nombre, edad, ubicación
  - Nivel de triage (con color)
  - Signos vitales recientes
  - Botón rápido a expediente

---

#### **RelevoTurno** (Jefe de Piso)
**Propósito:** Registrar y consultar información del relevo de turno

**Propiedades:**
- $turnoActual
- $turnoAnterior
- $novedades

**Métodos:**
- guardarNovedades()
- cerrarTurnoConRelevo()

**Vista:**
- Sección "Novedades del Turno Anterior" (readonly)
- Textarea para "Novedades para el Siguiente Turno"
- Resumen de asignaciones actuales
- Botón "Cerrar Turno y Hacer Relevo"

---

#### **DashboardCoordinador** (Coordinador General)
**Propósito:** Vista general de todos los turnos activos

**Propiedades:**
- $turnosActivos
- $indicadoresCarga

**Métodos:**
- calcularIndicadores()

**Vista:**
- Grid de tarjetas por área mostrando:
  - Turno activo (fecha, tipo, jefe)
  - # Enfermeros asignados
  - # Pacientes totales
  - Ratio enfermero:paciente
  - Alertas de sobrecarga

---

## 4. Riesgos y Dependencias

### Dependencias
- **Sprint 1:** Requiere que existan Áreas, Enfermeros y Usuarios.
- **Sprint 2:** Requiere que existan Pacientes activos con ubicación (cama).

### Riesgos Identificados

1. **Riesgo: Complejidad de reasignaciones**
   - *Descripción:* Reasignar pacientes puede generar inconsistencias si no se manejan correctamente las liberaciones.
   - *Mitigación:* Usar transacciones de base de datos y validar que solo haya una asignación activa por paciente.

2. **Riesgo: Turnos que cruzan medianoche**
   - *Descripción:* El turno nocturno (23:00 - 07:00) cruza dos fechas.
   - *Mitigación:* Almacenar fecha de inicio del turno y calcular fecha de fin programáticamente.

3. **Riesgo: Sobrecarga de dashboard con muchos pacientes**
   - *Descripción:* Un enfermero con 15+ pacientes puede tener una interfaz lenta.
   - *Mitigación:* Implementar paginación o scroll infinito, lazy loading de datos del paciente.

4. **Riesgo: Asignaciones huérfanas al dar de alta paciente**
   - *Descripción:* Si se da de alta un paciente, su asignación debe liberarse automáticamente.
   - *Mitigación:* Observer en modelo Paciente que libera asignaciones al cambiar estado a 'dado_alta'.

---

## 5. Reglas de Negocio

### Gestión de Turnos
1. Solo puede haber **un turno activo** por área/fecha/tipo.
2. Un turno solo puede ser cerrado por el jefe de turno que lo creó o por un Coordinador.
3. Al cerrar un turno, **todas las asignaciones se liberan automáticamente**.
4. No se pueden crear turnos con fecha anterior al día actual.

### Asignación de Pacientes
1. Un paciente solo puede estar asignado a **un enfermero** a la vez.
2. Solo se pueden asignar pacientes que estén en estado **"activo"**.
3. Solo se pueden asignar enfermeros del **área correspondiente** (si es enfermero fijo).
4. Al reasignar un paciente, se debe registrar el **motivo de la reasignación**.
5. Al dar de alta un paciente, su asignación se **libera automáticamente**.

### Carga de Trabajo
1. El sistema debe mostrar **alertas visuales** si un enfermero tiene más de 8 pacientes asignados.
2. El ratio recomendado es **1 enfermero : 6 pacientes** (configurable por área).

### Relevo de Turno
1. El relevo solo se puede hacer al **cerrar el turno**.
2. Las novedades del relevo se heredan al **siguiente turno del mismo tipo** (matutino → matutino siguiente).

---

## 6. Casos de Uso Principales

### Caso de Uso 1: Crear Turno y Asignar Pacientes

**Actor:** Jefe de Piso
**Flujo Principal:**
1. Jefe ingresa a "Gestión de Turnos"
2. Selecciona área, fecha y tipo de turno
3. Sistema crea turno y lo marca como activo
4. Sistema muestra lista de enfermeros disponibles del área
5. Sistema muestra lista de pacientes activos del área
6. Jefe selecciona enfermero y paciente, presiona "Asignar"
7. Sistema crea AsignacionPaciente con timestamp
8. Sistema actualiza contador de pacientes del enfermero
9. Jefe repite pasos 6-8 para todos los pacientes

**Resultado:** Todos los pacientes tienen un enfermero asignado, enfermeros ven sus asignaciones en dashboard.

---

### Caso de Uso 2: Enfermero Revisa Sus Asignaciones

**Actor:** Enfermero
**Flujo Principal:**
1. Enfermero ingresa a "Mis Asignaciones"
2. Sistema detecta turno activo del enfermero (basado en hora actual)
3. Sistema muestra pacientes asignados en el turno activo
4. Para cada paciente muestra:
   - Nombre, edad, ubicación (piso-cuarto-cama)
   - Nivel de triage con color
   - Último registro de signos vitales
5. Enfermero hace clic en "Ver Expediente"
6. Sistema redirige al ExpedientePaciente

**Resultado:** Enfermero conoce sus pacientes y puede acceder rápidamente a sus expedientes.

---

### Caso de Uso 3: Reasignar Paciente por Sobrecarga

**Actor:** Jefe de Piso
**Flujo Principal:**
1. Jefe ve que Enfermero A tiene 10 pacientes, Enfermero B tiene 4
2. Jefe selecciona un paciente del Enfermero A
3. Jefe presiona "Reasignar" y selecciona Enfermero B
4. Sistema solicita motivo de reasignación
5. Jefe ingresa "Balanceo de carga"
6. Sistema:
   - Marca asignación actual como liberada (con timestamp y motivo)
   - Crea nueva asignación al Enfermero B
7. Sistema actualiza contadores de ambos enfermeros

**Resultado:** Carga balanceada, trazabilidad completa de la reasignación.

---

### Caso de Uso 4: Relevo de Turno

**Actor:** Jefe de Piso (Turno Matutino)
**Flujo Principal:**
1. Al finalizar el turno matutino (14:50), Jefe ingresa a "Relevo de Turno"
2. Sistema muestra novedades del turno nocturno anterior
3. Jefe redacta novedades para el turno vespertino:
   - "Paciente en cama 301-A requiere control estricto de PA cada 2h"
   - "Pendiente de alta: Paciente en 205-B"
4. Jefe presiona "Cerrar Turno y Hacer Relevo"
5. Sistema:
   - Marca turno como cerrado
   - Guarda novedades
   - Libera todas las asignaciones del turno
6. Jefe del turno vespertino crea nuevo turno y ve las novedades

**Resultado:** Comunicación efectiva entre turnos, trazabilidad de responsabilidades.

---

## 7. Wireframes Conceptuales

### Pantalla: Gestor de Turnos (Jefe de Piso)

```
┌─────────────────────────────────────────────────────────────┐
│ Gestión de Turnos - Urgencias                               │
├─────────────────────────────────────────────────────────────┤
│                                                             │
│ Turno Actual: Matutino - 24/11/2025                        │
│ Jefe de Turno: María González                              │
│ [Cerrar Turno y Hacer Relevo]                              │
│                                                             │
├─────────────────────────────────────────────────────────────┤
│                                                             │
│ ENFERMEROS DISPONIBLES                PACIENTES SIN ASIGNAR│
│ ┌──────────────────────┐            ┌──────────────────┐   │
│ │ Juan Pérez      (6)  │            │ Ana Torres       │   │
│ │ [Ver Asignaciones]   │            │ Triage: I (Rojo) │   │
│ └──────────────────────┘            │ Cama: 301-A      │   │
│                                     │ [Asignar a: ▼]   │   │
│ ┌──────────────────────┐            └──────────────────┘   │
│ │ Carlos Ruiz     (4)  │                                   │
│ │ [Ver Asignaciones]   │            ┌──────────────────┐   │
│ └──────────────────────┘            │ Luis Mendoza     │   │
│                                     │ Triage: III      │   │
│ ┌──────────────────────┐            │ Cama: 205-B      │   │
│ │ Patricia Díaz   (8) ⚠│            │ [Asignar a: ▼]   │   │
│ │ [Ver Asignaciones]   │            └──────────────────┘   │
│ └──────────────────────┘                                   │
│                                                             │
└─────────────────────────────────────────────────────────────┘
```

---

### Pantalla: Mis Asignaciones (Enfermero)

```
┌─────────────────────────────────────────────────────────────┐
│ Mis Pacientes Asignados - Turno Vespertino                  │
├─────────────────────────────────────────────────────────────┤
│ 📋 Novedades del Turno Anterior:                            │
│ • Paciente en 301-A requiere control PA cada 2h            │
│ • Pendiente de alta: Paciente 205-B                         │
├─────────────────────────────────────────────────────────────┤
│                                                             │
│ ┌────────────────────┐  ┌────────────────────┐             │
│ │ 🔴 Ana Torres      │  │ 🟡 Luis Mendoza    │             │
│ │ 45 años, Femenino  │  │ 62 años, Masculino │             │
│ │ Cama: Urg-301-A    │  │ Cama: Urg-205-B    │             │
│ │ Triage: I (Crítico)│  │ Triage: III        │             │
│ │ PA: 160/95  FC: 98 │  │ PA: 130/80  FC: 72 │             │
│ │ [Ver Expediente]   │  │ [Ver Expediente]   │             │
│ └────────────────────┘  └────────────────────┘             │
│                                                             │
│ ┌────────────────────┐  ┌────────────────────┐             │
│ │ 🟢 Pedro Sánchez   │  │ 🟢 María López     │             │
│ │ 28 años, Masculino │  │ 35 años, Femenino  │             │
│ │ Cama: Urg-120-C    │  │ Cama: Urg-118-A    │             │
│ │ Triage: IV (Menor) │  │ Triage: V          │             │
│ │ PA: 120/70  FC: 68 │  │ PA: 110/65  FC: 65 │             │
│ │ [Ver Expediente]   │  │ [Ver Expediente]   │             │
│ └────────────────────┘  └────────────────────┘             │
│                                                             │
└─────────────────────────────────────────────────────────────┘
```

---

## 8. Criterios de Aceptación Generales

### Funcionalidad
- [ ] Un jefe de piso puede crear un turno para su área en menos de 1 minuto.
- [ ] Un jefe de piso puede asignar 10 pacientes a enfermeros en menos de 3 minutos.
- [ ] El sistema valida que solo haya una asignación activa por paciente.
- [ ] El sistema muestra alertas si un enfermero tiene más de 8 pacientes.
- [ ] Un enfermero ve solo sus pacientes asignados en el turno activo.
- [ ] El historial de asignaciones de un paciente muestra quién, cuándo y motivo.

### Trazabilidad
- [ ] Todas las asignaciones registran quién las realizó y cuándo.
- [ ] Todas las liberaciones/reasignaciones registran quién, cuándo y por qué.
- [ ] No se pueden eliminar asignaciones, solo liberarlas (soft delete conceptual).

### Performance
- [ ] El dashboard de enfermero carga en menos de 2 segundos con 10 pacientes.
- [ ] El gestor de turnos soporta 50 pacientes sin degradación de UX.

### Testing
- [ ] Tests unitarios para lógica de asignación/liberación.
- [ ] Tests de integración para flujo completo de crear turno → asignar → cerrar.
- [ ] Tests de validación para reglas de negocio (un paciente, una asignación).

---

## 9. Deuda Técnica Conocida

### Items Pospuestos para Sprints Futuros

1. **Drag-and-Drop Visual**
   - *Descripción:* Interfaz drag-and-drop para asignar pacientes arrastrándolos a enfermeros.
   - *Razón de postponer:* Complejidad de UX, mejor hacer MVP con selects.
   - *Sprint propuesto:* Sprint 6 (Refinamiento)

2. **Firma Digital de Relevo**
   - *Descripción:* Captura de firma electrónica al hacer relevo de turno.
   - *Razón de postponer:* Requiere librería externa y validación legal.
   - *Sprint propuesto:* Sprint 7 (Seguridad y Auditoría)

3. **Notificaciones Push**
   - *Descripción:* Notificar a enfermero cuando se le asigna un nuevo paciente.
   - *Razón de postponer:* Requiere sistema de notificaciones en tiempo real.
   - *Sprint propuesto:* Sprint 8 (Tiempo Real)

4. **Sugerencias Automáticas de Asignación**
   - *Descripción:* IA que sugiere asignaciones óptimas basadas en habilidades y carga.
   - *Razón de postponer:* Requiere algoritmos complejos y datos históricos.
   - *Sprint propuesto:* v2.0 (IA)

5. **Integración con Capacitación**
   - *Descripción:* Validar que enfermeros asignados no estén en capacitación.
   - *Razón de postponer:* El módulo de capacitación es Sprint 5.
   - *Sprint propuesto:* Sprint 5

---

## 10. Plan de Implementación

### Semana 1: Infraestructura y Gestor de Turnos

#### Día 1-2: Modelos y Migraciones
- [ ] Crear migración `create_turnos_table`
- [ ] Crear migración `create_asignacion_pacientes_table`
- [ ] Crear modelo `Turno` con relaciones
- [ ] Crear modelo `AsignacionPaciente` con relaciones
- [ ] Crear Enums: `TipoTurno`, `EstadoTurno`
- [ ] Ejecutar migraciones
- [ ] Crear seeders de prueba (turnos y asignaciones)

#### Día 3-4: Componente GestorTurnos
- [ ] Crear componente Livewire `GestorTurnos`
- [ ] Implementar lógica de creación de turno
- [ ] Implementar lógica de asignación de pacientes
- [ ] Implementar visualización de carga de trabajo
- [ ] Crear vista con lista de enfermeros y pacientes
- [ ] Crear tests unitarios de asignación

#### Día 5: Reasignación y Liberación
- [ ] Implementar método `reasignarPaciente()`
- [ ] Implementar método `liberarAsignacion()`
- [ ] Implementar observer en Paciente para liberar asignaciones al dar de alta
- [ ] Crear tests de reasignación

---

### Semana 2: Dashboards y Relevo

#### Día 6-7: Dashboard de Enfermeros
- [ ] Crear componente Livewire `MisAsignaciones`
- [ ] Implementar lógica para detectar turno actual del enfermero
- [ ] Implementar visualización de pacientes asignados
- [ ] Crear cards de pacientes con info resumida
- [ ] Integrar con ExpedientePaciente (botón "Ver Expediente")
- [ ] Crear tests de visualización

#### Día 8: Relevo de Turno
- [ ] Crear componente Livewire `RelevoTurno`
- [ ] Implementar método `cerrarTurnoConRelevo()`
- [ ] Implementar visualización de novedades del turno anterior
- [ ] Crear formulario de registro de novedades
- [ ] Crear tests de relevo

#### Día 9: Dashboard de Coordinador
- [ ] Crear componente Livewire `DashboardCoordinador`
- [ ] Implementar visualización de turnos activos por área
- [ ] Implementar cálculo de indicadores de carga
- [ ] Crear alertas visuales de sobrecarga
- [ ] Crear tests de indicadores

#### Día 10: Testing e Integración
- [ ] Tests de integración del flujo completo
- [ ] Tests de validaciones de reglas de negocio
- [ ] Optimización de queries (eager loading)
- [ ] Ajustes de UX según pruebas
- [ ] Actualizar documentación

---

## 11. Definición de "Hecho" (DoD)

Una historia de usuario se considera completada cuando:

- [ ] El código está implementado y funciona según criterios de aceptación
- [ ] Existen tests unitarios que cubren la lógica de negocio
- [ ] Existen tests de integración que validan el flujo completo
- [ ] La interfaz es responsive (móvil, tablet, desktop)
- [ ] Se validaron las reglas de negocio con datos de prueba
- [ ] La documentación técnica está actualizada
- [ ] El código fue revisado (self-review mínimo)
- [ ] No hay errores conocidos bloqueantes
- [ ] Las migraciones se ejecutan sin errores
- [ ] Los seeders funcionan correctamente

---

## 12. Riesgos y Mitigaciones (Resumen)

| Riesgo | Probabilidad | Impacto | Mitigación |
|--------|--------------|---------|------------|
| Reasignaciones generan inconsistencias | Media | Alto | Transacciones DB, validaciones estrictas |
| Turnos nocturnos cruzan medianoche | Alta | Medio | Lógica de cálculo de fechas robusta |
| Dashboard lento con muchos pacientes | Baja | Medio | Eager loading, paginación |
| Asignaciones huérfanas al dar de alta | Media | Medio | Observer en Paciente |
| Confusión de UX en asignaciones | Baja | Alto | Wireframes claros, pruebas de usabilidad |

---

## 13. Dependencias Externas

- **Ninguna:** Este sprint no requiere librerías externas adicionales.
- **Infraestructura:** Requiere que el servidor soporte transacciones de base de datos (InnoDB en MySQL).

---

## 14. Notas Finales

### Priorización dentro del Sprint

**Must Have (Crítico):**
- Crear turnos
- Asignar pacientes a enfermeros
- Dashboard de enfermero con pacientes asignados
- Historial de asignaciones

**Should Have (Importante):**
- Reasignar pacientes
- Relevo de turno con novedades
- Indicadores de carga de trabajo

**Could Have (Deseable):**
- Dashboard de coordinador
- Alertas de sobrecarga
- Filtros avanzados en gestor de turnos

**Won't Have (Fuera de Alcance):**
- Drag-and-drop visual
- Firma digital de relevo
- Notificaciones push
- Sugerencias automáticas de asignación

---

**Siguiente Sprint Sugerido:** Sprint 5 - Módulo de Farmacia e Insumos
