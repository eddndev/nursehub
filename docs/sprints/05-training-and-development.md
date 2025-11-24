# Sprint 5: Capacitación y Desarrollo Profesional

**Epic:** Epic #4 - Módulo de Capacitación y Certificación de Enfermería
**Duración:** 2 semanas
**Fecha de inicio:** 2025-11-25
**Fecha de finalización:** 2025-12-09
**Estado:** Planificado
**Épica Maestra en GitHub:** [Issue #41](https://github.com/eddndev/nursehub/issues/41) (Por crear)

---

## 1. Objetivos del Sprint

### Objetivo Principal
Implementar el sistema completo de gestión de capacitación y desarrollo profesional para el personal de enfermería, permitiendo al Jefe de Capacitación organizar actividades educativas, gestionar inscripciones (manuales y autoservicio), controlar asistencia, evaluar aprovechamiento y generar certificaciones automáticas, mientras que los enfermeros pueden visualizar oportunidades de desarrollo y gestionar su portafolio profesional.

### Objetivos Específicos
1. Crear el modelo de datos para actividades de capacitación, inscripciones, asistencias y certificaciones.
2. Implementar el gestor de actividades para el Jefe de Capacitación (CRUD completo).
3. Desarrollar el sistema de inscripciones (manual por jefe + autoservicio por enfermeros).
4. Construir el módulo de registro de asistencia por sesión con cálculo automático de porcentajes.
5. Implementar el sistema de aprobación/reprobación con generación automática de certificaciones.
6. Crear dashboard personalizado para enfermeros con su portafolio de desarrollo profesional.
7. Integrar validaciones de disponibilidad con el módulo de turnos (Sprint 4).
8. Implementar generación de PDFs de certificaciones con folios únicos.

### Métricas de Éxito
- Crear una actividad de capacitación completa (con sesiones) en menos de 3 minutos.
- Inscribir a 20 enfermeros en una actividad en menos de 2 minutos.
- Registrar asistencia de 50 enfermeros a una sesión en menos de 5 minutos.
- Generar certificaciones para 30 enfermeros aprobados en menos de 10 segundos.
- 100% de trazabilidad de inscripciones, asistencias y certificaciones.
- Validación automática de conflictos de horario con turnos activos.

---

## 2. Alcance del Sprint

### Historias de Usuario

#### **Gestión de Actividades de Capacitación (Jefe de Capacitación)**
- [ ] US-CAP-001: Como Jefe de Capacitación, quiero poder crear actividades de capacitación (cursos, becas, campañas, pláticas) para organizar el desarrollo profesional.
- [ ] US-CAP-002: Como Jefe de Capacitación, quiero poder definir fechas, horarios por sesión y capacidad máxima para cada actividad para planificar recursos.
- [ ] US-CAP-003: Como Jefe de Capacitación, quiero poder definir el porcentaje de asistencia mínima requerida para aprobar para establecer criterios.
- [ ] US-CAP-004: Como Jefe de Capacitación, quiero poder inscribir manualmente a enfermeros en actividades para asignar capacitación obligatoria.
- [ ] US-CAP-005: Como Jefe de Capacitación, quiero poder ver la lista de inscritos en cada actividad para monitorear la participación.

#### **Control de Asistencia y Evaluación**
- [ ] US-CAP-006: Como Jefe de Capacitación, quiero poder registrar asistencia diaria por sesión de cada inscrito para llevar control.
- [ ] US-CAP-007: Como Jefe de Capacitación, quiero poder calcular automáticamente el porcentaje de asistencia de cada inscrito para evaluar aprobación.
- [ ] US-CAP-008: Como Jefe de Capacitación, quiero poder aprobar o reprobar inscripciones basado en asistencia para actualizar el estado.

#### **Certificaciones**
- [ ] US-CAP-009: Como Jefe de Capacitación, quiero poder generar certificaciones automáticamente para inscritos aprobados para reconocer su logro.
- [ ] US-CAP-010: Como Jefe de Capacitación, quiero poder asignar números de folio únicos a cada certificación para trazabilidad.
- [ ] US-CAP-011: Como Jefe de Capacitación, quiero poder generar reportes de participación en capacitaciones por enfermero para evaluación de desempeño.
- [ ] US-CAP-012: Como Jefe de Capacitación, quiero poder ver qué enfermeros tienen certificaciones próximas a vencer para programar renovaciones.

#### **Dashboard de Enfermeros**
- [ ] US-CAP-013: Como Enfermero, quiero poder ver un dashboard con capacitaciones disponibles para inscripción para conocer oportunidades de desarrollo.
- [ ] US-CAP-014: Como Enfermero, quiero poder inscribirme por autoservicio en actividades abiertas para participar activamente en mi desarrollo.
- [ ] US-CAP-015: Como Enfermero, quiero poder ver mis inscripciones activas con fechas y horarios para planificar mi agenda.
- [ ] US-CAP-016: Como Enfermero, quiero poder ver mi historial de capacitaciones completadas para consultar mi desarrollo profesional.
- [ ] US-CAP-017: Como Enfermero, quiero poder ver mis certificaciones obtenidas con fechas de vigencia para conocer mi perfil profesional.
- [ ] US-CAP-018: Como Enfermero, quiero poder descargar el PDF de mis certificaciones para tener evidencia física.
- [ ] US-CAP-019: Como Enfermero, quiero que el sistema me alerte si una capacitación en la que estoy inscrito tiene conflicto de horario con un turno para evitar problemas.

#### **Integración con Módulo de Turnos**
- [ ] US-CAP-020: Como Jefe de Piso, quiero poder ver qué enfermeros de mi área están inscritos en capacitaciones para planificar asignaciones.
- [ ] US-CAP-021: Como Jefe de Piso, quiero que el sistema me bloquee la asignación de enfermeros que están en capacitación para evitar conflictos.
- [ ] US-CAP-022: Como Jefe de Piso, quiero poder ver el calendario de capacitaciones de mi personal para planificar la cobertura.

#### **Reportes y Supervisión (Coordinador)**
- [ ] US-CAP-023: Como Coordinador General, quiero poder ver reportes de participación general en capacitaciones por área para evaluar el compromiso.
- [ ] US-CAP-024: Como Coordinador General, quiero poder ver el porcentaje de personal con certificaciones vigentes por área para monitorear calidad.
- [ ] US-CAP-025: Como Coordinador General, quiero poder identificar áreas con bajo nivel de capacitación para enfocar esfuerzos de desarrollo.
- [ ] US-CAP-026: Como Coordinador General, quiero poder generar reportes de horas de capacitación por enfermero para evaluaciones anuales.

---

## 2.1 Issues del Sprint

Este sprint se divide en 5 issues técnicas principales:

| Issue | Título | Tipo | Prioridad | Historias |
|-------|--------|------|-----------|-----------|
| [#42](https://github.com/eddndev/nursehub/issues/42) | Infraestructura de Datos para Capacitación | Chore | Critical | Todas |
| [#43](https://github.com/eddndev/nursehub/issues/43) | Gestor de Actividades de Capacitación | Feature | High | US-CAP-001 a 005 |
| [#44](https://github.com/eddndev/nursehub/issues/44) | Sistema de Control de Asistencia y Certificaciones | Feature | High | US-CAP-006 a 012 |
| [#45](https://github.com/eddndev/nursehub/issues/45) | Dashboard de Desarrollo Profesional para Enfermeros | Feature | High | US-CAP-013 a 019 |
| [#46](https://github.com/eddndev/nursehub/issues/46) | Integración con Módulo de Turnos y Reportes | Feature | Medium | US-CAP-020 a 026 |

---

## 3. Arquitectura Técnica

### 3.1 Nuevos Modelos

#### **ActividadCapacitacion**
```php
Schema::create('actividades_capacitacion', function (Blueprint $table) {
    $table->id();
    $table->string('titulo');
    $table->text('descripcion')->nullable();
    $table->enum('tipo', ['curso', 'beca', 'campana', 'platica', 'taller', 'congreso']);
    $table->date('fecha_inicio');
    $table->date('fecha_fin');
    $table->integer('capacidad_maxima')->default(30);
    $table->decimal('porcentaje_asistencia_minima', 5, 2)->default(80.00); // 80%
    $table->integer('duracion_horas')->nullable(); // Total de horas
    $table->boolean('permite_autoservicio')->default(true);
    $table->enum('estado', ['programada', 'en_curso', 'finalizada', 'cancelada'])->default('programada');
    $table->foreignId('instructor_id')->nullable()->constrained('users');
    $table->string('lugar')->nullable();
    $table->text('objetivo')->nullable();
    $table->foreignId('creado_por')->constrained('users');
    $table->timestamps();
    $table->softDeletes();
});
```

#### **SesionCapacitacion**
```php
Schema::create('sesiones_capacitacion', function (Blueprint $table) {
    $table->id();
    $table->foreignId('actividad_id')->constrained('actividades_capacitacion')->onDelete('cascade');
    $table->integer('numero_sesion'); // 1, 2, 3...
    $table->date('fecha');
    $table->time('hora_inicio');
    $table->time('hora_fin');
    $table->text('tema')->nullable();
    $table->enum('estado', ['programada', 'realizada', 'cancelada'])->default('programada');
    $table->timestamps();

    $table->unique(['actividad_id', 'numero_sesion']);
});
```

#### **InscripcionCapacitacion**
```php
Schema::create('inscripciones_capacitacion', function (Blueprint $table) {
    $table->id();
    $table->foreignId('actividad_id')->constrained('actividades_capacitacion')->onDelete('cascade');
    $table->foreignId('enfermero_id')->constrained('enfermeros')->onDelete('cascade');
    $table->timestamp('fecha_inscripcion');
    $table->enum('tipo_inscripcion', ['manual', 'autoservicio'])->default('autoservicio');
    $table->foreignId('inscrito_por')->constrained('users'); // Quién hizo la inscripción
    $table->enum('estado', ['activa', 'aprobada', 'reprobada', 'cancelada'])->default('activa');
    $table->decimal('porcentaje_asistencia', 5, 2)->default(0); // Se calcula automáticamente
    $table->timestamp('fecha_aprobacion')->nullable();
    $table->foreignId('aprobado_por')->nullable()->constrained('users');
    $table->text('observaciones')->nullable();
    $table->timestamps();

    // Un enfermero solo puede estar inscrito una vez en una actividad
    $table->unique(['actividad_id', 'enfermero_id']);
    $table->index(['enfermero_id', 'estado']);
});
```

#### **AsistenciaCapacitacion**
```php
Schema::create('asistencias_capacitacion', function (Blueprint $table) {
    $table->id();
    $table->foreignId('sesion_id')->constrained('sesiones_capacitacion')->onDelete('cascade');
    $table->foreignId('inscripcion_id')->constrained('inscripciones_capacitacion')->onDelete('cascade');
    $table->boolean('asistio')->default(false);
    $table->timestamp('hora_registro')->nullable();
    $table->foreignId('registrado_por')->constrained('users');
    $table->text('observaciones')->nullable();
    $table->timestamps();

    // Una asistencia por sesión por inscripción
    $table->unique(['sesion_id', 'inscripcion_id']);
    $table->index(['inscripcion_id']);
});
```

#### **Certificacion**
```php
Schema::create('certificaciones', function (Blueprint $table) {
    $table->id();
    $table->string('folio')->unique(); // Formato: CAP-2025-0001
    $table->foreignId('inscripcion_id')->constrained('inscripciones_capacitacion');
    $table->foreignId('enfermero_id')->constrained('enfermeros');
    $table->foreignId('actividad_id')->constrained('actividades_capacitacion');
    $table->date('fecha_emision');
    $table->date('fecha_vigencia')->nullable(); // Algunas certificaciones caducan
    $table->integer('horas_acreditadas');
    $table->decimal('calificacion_final', 5, 2)->nullable(); // Por si en el futuro se evalúa
    $table->foreignId('emitido_por')->constrained('users');
    $table->string('pdf_path')->nullable(); // Ruta al PDF generado
    $table->timestamps();

    $table->index(['enfermero_id', 'fecha_vigencia']);
    $table->index(['folio']);
});
```

### 3.2 Relaciones de Modelos

**ActividadCapacitacion:**
- belongsTo: User (instructor)
- belongsTo: User (creado_por)
- hasMany: SesionCapacitacion
- hasMany: InscripcionCapacitacion
- hasMany: Certificacion

**SesionCapacitacion:**
- belongsTo: ActividadCapacitacion
- hasMany: AsistenciaCapacitacion

**InscripcionCapacitacion:**
- belongsTo: ActividadCapacitacion
- belongsTo: Enfermero
- belongsTo: User (inscrito_por)
- belongsTo: User (aprobado_por)
- hasMany: AsistenciaCapacitacion
- hasOne: Certificacion

**AsistenciaCapacitacion:**
- belongsTo: SesionCapacitacion
- belongsTo: InscripcionCapacitacion
- belongsTo: User (registrado_por)

**Certificacion:**
- belongsTo: InscripcionCapacitacion
- belongsTo: Enfermero
- belongsTo: ActividadCapacitacion
- belongsTo: User (emitido_por)

**Enfermero (modificaciones):**
- hasMany: InscripcionCapacitacion
- hasMany: Certificacion
- hasManyThrough: ActividadCapacitacion (through InscripcionCapacitacion)

### 3.3 Enums

#### **TipoActividad**
```php
enum TipoActividad: string
{
    case CURSO = 'curso';
    case BECA = 'beca';
    case CAMPANA = 'campana';
    case PLATICA = 'platica';
    case TALLER = 'taller';
    case CONGRESO = 'congreso';

    public function getLabel(): string;
    public function getIcon(): string;
    public function getColor(): string;
}
```

#### **EstadoActividad**
```php
enum EstadoActividad: string
{
    case PROGRAMADA = 'programada';
    case EN_CURSO = 'en_curso';
    case FINALIZADA = 'finalizada';
    case CANCELADA = 'cancelada';

    public function getLabel(): string;
    public function getColor(): string;
}
```

#### **EstadoInscripcion**
```php
enum EstadoInscripcion: string
{
    case ACTIVA = 'activa';
    case APROBADA = 'aprobada';
    case REPROBADA = 'reprobada';
    case CANCELADA = 'cancelada';

    public function getLabel(): string;
    public function getColor(): string;
}
```

#### **TipoInscripcion**
```php
enum TipoInscripcion: string
{
    case MANUAL = 'manual';
    case AUTOSERVICIO = 'autoservicio';

    public function getLabel(): string;
}
```

### 3.4 Componentes Livewire

#### **GestorActividades** (Jefe de Capacitación)
**Propósito:** CRUD completo de actividades de capacitación

**Propiedades:**
- $actividades (lista paginada)
- $filtros (estado, tipo, fecha)
- $actividadSeleccionada
- $modalCrear, $modalEditar

**Métodos:**
- crearActividad($datos)
- editarActividad($id, $datos)
- cancelarActividad($id, $motivo)
- agregarSesion($actividadId, $datosSesion)
- eliminarSesion($sesionId)
- cambiarEstadoActividad($id, $nuevoEstado)

**Vista:**
- Tabla de actividades con filtros
- Modal de creación/edición con wizard (datos generales → sesiones)
- Vista detalle de actividad con lista de sesiones
- Botones de acciones rápidas

---

#### **GestorInscripciones** (Jefe de Capacitación)
**Propósito:** Gestión de inscripciones manuales y visualización

**Propiedades:**
- $actividadId
- $inscripciones
- $enferm erosDisponibles
- $modalInscribir

**Métodos:**
- inscribirEnfermero($enfermeroId, $actividadId)
- inscribirMultiples($enfermeroIds, $actividadId)
- cancelarInscripcion($inscripcionId, $motivo)
- verDetalleInscripcion($inscripcionId)

**Vista:**
- Lista de inscritos con estado y % asistencia
- Buscador de enfermeros para inscribir
- Checkbox múltiple para inscripción masiva
- Indicador visual de cupos disponibles

---

#### **ControlAsistencia** (Jefe de Capacitación)
**Propósito:** Registro de asistencia por sesión

**Propiedades:**
- $sesionId
- $inscripciones (con checkbox de asistencia)
- $busqueda (para filtrar inscritos)

**Métodos:**
- registrarAsistencia($inscripcionId, $asistio)
- registrarAsistenciaMultiple($datos) // Guardar todo al final
- marcarTodosPresentes()
- marcarTodosAusentes()
- calcularPorcentajesAsistencia($actividadId)

**Vista:**
- Header con info de sesión (fecha, hora, tema)
- Lista de inscritos con checkbox de asistencia
- Búsqueda en vivo de inscritos
- Botones de "Todos Presentes" / "Todos Ausentes"
- Indicador de asistencia registrada vs pendiente

---

#### **GestorAprobaciones** (Jefe de Capacitación)
**Propósito:** Aprobar/reprobar inscripciones y generar certificaciones

**Propiedades:**
- $actividadId
- $inscripciones (filtradas por estado activa)
- $criterioAprobacion (% asistencia mínima)

**Métodos:**
- aprobarInscripcion($inscripcionId)
- reprobarInscripcion($inscripcionId, $motivo)
- aprobarMasivo($inscripcionIds) // Aprobar múltiples y generar certs
- generarCertificacion($inscripcionId)
- regenerarCertificacion($certificacionId)

**Vista:**
- Tabla de inscritos con % asistencia calculado
- Indicador visual de cumplimiento de criterio
- Checkbox para aprobación masiva
- Botón "Aprobar Seleccionados y Generar Certificaciones"
- Preview de certificaciones generadas

---

#### **DashboardCapacitacion** (Enfermero)
**Propósito:** Vista personalizada del portafolio de desarrollo del enfermero

**Propiedades:**
- $enfermeroId
- $capacitacionesDisponibles
- $misInscripciones
- $misCertificaciones

**Métodos:**
- inscribirmeEnActividad($actividadId)
- cancelarMiInscripcion($inscripcionId)
- descargarCertificacion($certificacionId)
- verDetalleActividad($actividadId)

**Vista:**
- Sección "Capacitaciones Disponibles" (cards con info y botón inscribirse)
- Sección "Mis Inscripciones Activas" (timeline con próximas sesiones)
- Sección "Mi Historial" (capacitaciones completadas)
- Sección "Mis Certificaciones" (grid de certificaciones con descarga PDF)
- Alertas de conflictos de horario con turnos

---

#### **CalendarioCapacitaciones** (Jefe de Piso)
**Propósito:** Ver calendario de capacitaciones de su personal

**Propiedades:**
- $areaId
- $enfermeros (de su área)
- $inscripciones (filtradas por área)
- $mesActual

**Métodos:**
- cambiarMes($mes, $año)
- verDetalleDia($fecha)
- verEnfermerosEnCapacitacion($fecha)

**Vista:**
- Calendario mensual con sesiones marcadas
- Lista de enfermeros con sus inscripciones activas
- Indicador de conflictos con turnos programados

---

#### **ReportesCapacitacion** (Coordinador General)
**Propósito:** Reportes y analytics de capacitación

**Propiedades:**
- $periodoInicio, $periodoFin
- $areaFiltro
- $tipoReporte

**Métodos:**
- generarReporteParticipacion()
- generarReporteCertificaciones()
- generarReporteHoras()
- exportarPDF()
- exportarExcel()

**Vista:**
- Filtros de período y área
- Tabs de diferentes reportes
- Gráficos de participación (Chart.js)
- Tablas de datos exportables
- KPIs principales (% participación, horas totales, etc.)

---

## 4. Reglas de Negocio

### Gestión de Actividades
1. Una actividad debe tener al menos **una sesión** para poder activarse.
2. No se pueden editar fechas de sesiones que **ya pasaron**.
3. Solo se pueden cancelar actividades en estado **"programada"** o **"en_curso"**.
4. El porcentaje de asistencia mínima debe estar entre **50% y 100%**.
5. La capacidad máxima debe ser **mayor a 0**.
6. Las sesiones deben estar **dentro del rango** de fecha_inicio y fecha_fin de la actividad.

### Inscripciones
1. Un enfermero solo puede inscribirse **una vez** en una actividad.
2. No se pueden inscribir enfermeros si la actividad está **llena** (inscripciones >= capacidad_maxima).
3. No se pueden inscribir enfermeros si la actividad está **cancelada** o **finalizada**.
4. La inscripción por autoservicio solo está disponible si `permite_autoservicio = true`.
5. Al inscribir, el sistema debe **validar conflictos** con turnos programados del enfermero.

### Asistencia
1. Solo se puede registrar asistencia a sesiones en estado **"realizada"** o del **día actual**.
2. El porcentaje de asistencia se calcula como: `(sesiones_asistidas / total_sesiones) * 100`.
3. El porcentaje se **actualiza automáticamente** cada vez que se registra una asistencia.
4. No se puede modificar asistencia después de **7 días** de la fecha de la sesión.

### Aprobación y Certificaciones
1. Solo se pueden aprobar inscripciones en estado **"activa"**.
2. Una inscripción se aprueba si: `porcentaje_asistencia >= porcentaje_asistencia_minima`.
3. Al aprobar, se genera automáticamente una **certificación** con folio único.
4. El folio de certificación sigue el formato: `CAP-{AÑO}-{CONSECUTIVO}` (ej: CAP-2025-0001).
5. Las certificaciones son **inmutables** (no se pueden editar, solo regenerar el PDF).
6. Al reprobar, se debe registrar el **motivo** de reprobación.
7. Las certificaciones pueden tener fecha de vigencia (opcional, para renovaciones).

### Validación con Turnos (Integración Sprint 4)
1. Al inscribir un enfermero, validar si tiene **turnos activos** en las fechas/horarios de las sesiones.
2. Si hay conflicto, **alertar** al Jefe de Capacitación (no bloquear).
3. Al asignar un enfermero a un turno (en GestorTurnos), validar si tiene **sesiones programadas** en ese horario.
4. Si hay conflicto, **bloquear** la asignación y mostrar mensaje.
5. Un enfermero con inscripción activa debe aparecer con badge **"En Capacitación"** en el GestorTurnos.

---

## 5. Casos de Uso Principales

### Caso de Uso 1: Crear Actividad de Capacitación

**Actor:** Jefe de Capacitación
**Flujo Principal:**
1. Jefe ingresa a "Gestión de Capacitaciones"
2. Click en "Nueva Actividad"
3. Sistema muestra formulario con tabs: Datos Generales → Sesiones → Confirmación
4. Jefe completa datos generales:
   - Título, tipo, descripción
   - Fechas inicio/fin
   - Capacidad máxima
   - % asistencia mínima
   - Permite autoservicio (checkbox)
   - Instructor (opcional)
5. Sistema valida datos y permite avanzar al tab "Sesiones"
6. Jefe agrega sesiones dinámicamente:
   - Fecha, hora inicio, hora fin, tema
7. Sistema valida que sesiones estén dentro del rango de la actividad
8. Jefe confirma creación
9. Sistema crea actividad en estado "programada" y todas las sesiones
10. Sistema muestra mensaje de éxito con ID de la actividad

**Resultado:** Actividad creada y lista para inscripciones.

---

### Caso de Uso 2: Inscripción por Autoservicio (Enfermero)

**Actor:** Enfermero
**Flujo Principal:**
1. Enfermero ingresa a "Mi Desarrollo Profesional"
2. Sistema muestra capacitaciones disponibles (permite_autoservicio = true, estado = programada)
3. Enfermero hace click en card de una capacitación
4. Sistema muestra modal con detalles:
   - Descripción completa
   - Fechas y horarios de sesiones
   - Capacidad disponible
   - Requisitos de aprobación
5. Enfermero click en "Inscribirme"
6. Sistema valida:
   - No está inscrito previamente
   - Hay cupos disponibles
   - No hay conflictos de horario con turnos programados
7. Si hay conflicto con turno, sistema muestra alerta: "Tienes un turno programado el [fecha] en [área]. ¿Deseas inscribirte de todas formas?"
8. Enfermero confirma
9. Sistema crea inscripción tipo "autoservicio", inscrito_por = enfermero
10. Sistema envía notificación (opcional)

**Resultado:** Enfermero inscrito en la actividad, puede ver sus sesiones programadas.

---

### Caso de Uso 3: Registro de Asistencia

**Actor:** Jefe de Capacitación
**Flujo Principal:**
1. Jefe ingresa a "Control de Asistencia"
2. Sistema muestra lista de actividades en curso con sesiones del día
3. Jefe selecciona una sesión
4. Sistema muestra lista de inscritos (checkbox para cada uno)
5. Jefe marca presentes/ausentes usando checkboxes
6. Jefe puede usar filtro de búsqueda para encontrar inscritos rápidamente
7. Jefe puede usar botón "Marcar Todos Presentes" si es necesario
8. Jefe click en "Guardar Asistencia"
9. Sistema registra asistencia para cada inscrito
10. Sistema recalcula porcentaje_asistencia para cada inscripción
11. Sistema actualiza estado de sesión a "realizada"

**Resultado:** Asistencia registrada, porcentajes actualizados en tiempo real.

---

### Caso de Uso 4: Aprobación Masiva y Generación de Certificaciones

**Actor:** Jefe de Capacitación
**Flujo Principal:**
1. Jefe ingresa a "Aprobaciones y Certificaciones"
2. Sistema muestra actividades finalizadas con inscripciones activas
3. Jefe selecciona una actividad
4. Sistema muestra tabla de inscritos con:
   - Nombre del enfermero
   - % asistencia calculado
   - Indicador visual (verde si cumple criterio, rojo si no)
   - Checkbox para aprobación masiva
5. Jefe selecciona inscritos que cumplen criterio (o usa "Seleccionar Todos que Cumplen Criterio")
6. Jefe click en "Aprobar Seleccionados y Generar Certificaciones"
7. Sistema confirma: "Se aprobarán X inscripciones y se generarán X certificaciones. ¿Continuar?"
8. Jefe confirma
9. Sistema ejecuta en transacción:
   - Actualiza estado de inscripciones a "aprobada"
   - Registra fecha_aprobacion y aprobado_por
   - Genera folio único para cada certificación
   - Crea registros de certificación
   - Genera PDFs de certificaciones (background job)
10. Sistema muestra mensaje: "Se aprobaron X inscripciones. Las certificaciones están siendo generadas."

**Resultado:** Inscripciones aprobadas, certificaciones creadas y PDFs generados.

---

### Caso de Uso 5: Validación de Conflicto con Turno (Integración)

**Actor:** Jefe de Piso (en GestorTurnos)
**Flujo Principal:**
1. Jefe de Piso intenta asignar Enfermero A al Paciente B en turno matutino del 2025-12-01
2. Sistema valida si Enfermero A tiene inscripciones activas con sesiones programadas ese día/horario
3. Sistema encuentra que Enfermero A tiene sesión de capacitación el 2025-12-01 de 08:00 a 10:00
4. Sistema bloquea la asignación y muestra mensaje:
   "❌ No se puede asignar. Enfermero A está inscrito en: [Título de Capacitación] - Sesión 2025-12-01 08:00-10:00"
5. Sistema sugiere alternativas:
   - Ver otros enfermeros disponibles
   - Contactar al Jefe de Capacitación para cancelar inscripción

**Resultado:** Conflicto detectado, asignación bloqueada, transparencia para Jefe de Piso.

---

## 6. Wireframes Conceptuales

### Pantalla: Gestión de Actividades (Jefe de Capacitación)

```
┌─────────────────────────────────────────────────────────────┐
│ Gestión de Capacitaciones                                   │
├─────────────────────────────────────────────────────────────┤
│                                                             │
│ [Nueva Actividad]  [Filtros ▼]  [Buscar...           ]     │
│                                                             │
│ ┌──────────────────────────────────────────────────────┐   │
│ │ 📚 Curso: Manejo Avanzado de Vías Periféricas       │   │
│ │ Fecha: 01/12 - 15/12/2025 | Capacidad: 25/30        │   │
│ │ Sesiones: 8 | % Asistencia Mín: 80%                 │   │
│ │ Estado: [🟢 En Curso]                                 │   │
│ │ [Ver Detalles] [Inscritos] [Asistencia] [⚙️]        │   │
│ └──────────────────────────────────────────────────────┘   │
│                                                             │
│ ┌──────────────────────────────────────────────────────┐   │
│ │ 🎓 Taller: RCP Avanzado y ACLS                       │   │
│ │ Fecha: 05/01 - 07/01/2026 | Capacidad: 18/20        │   │
│ │ Sesiones: 3 | % Asistencia Mín: 100%                │   │
│ │ Estado: [🟡 Programada]                               │   │
│ │ [Ver Detalles] [Inscritos] [⚙️]                       │   │
│ └──────────────────────────────────────────────────────┘   │
│                                                             │
└─────────────────────────────────────────────────────────────┘
```

---

### Pantalla: Dashboard de Enfermero

```
┌─────────────────────────────────────────────────────────────┐
│ Mi Desarrollo Profesional                                   │
├─────────────────────────────────────────────────────────────┤
│                                                             │
│ 📊 Resumen:  3 Inscripciones Activas | 12 Certificaciones  │
│                                                             │
│ ━━━━━━━ CAPACITACIONES DISPONIBLES ━━━━━━━                 │
│                                                             │
│ ┌──────────────────┐  ┌──────────────────┐                 │
│ │ 📚 Manejo de     │  │ 🎓 Cuidados Post │                 │
│ │    Dolor Agudo   │  │    Quirúrgicos   │                 │
│ │ Inicio: 10/12    │  │ Inicio: 15/12    │                 │
│ │ 16 horas | 4 ses │  │ 20 horas | 5 ses │                 │
│ │ Cupos: 5/25      │  │ Cupos: 12/20     │                 │
│ │ [Inscribirme]    │  │ [Inscribirme]    │                 │
│ └──────────────────┘  └──────────────────┘                 │
│                                                             │
│ ━━━━━━━ MIS INSCRIPCIONES ACTIVAS ━━━━━━━                  │
│                                                             │
│ ⏱ Próxima Sesión: Mañana 08:00 - 10:00                     │
│ 📚 Manejo Avanzado de Vías Periféricas                     │
│ Progreso: ████████░░ 80% (Sesión 6 de 8)                   │
│ Asistencia: 5/6 (83%) - ✅ Cumple Criterio                 │
│                                                             │
│ ━━━━━━━ MIS CERTIFICACIONES ━━━━━━━                        │
│                                                             │
│ ┌────────────┐  ┌────────────┐  ┌────────────┐             │
│ │ RCP Básico │  │ Triage     │  │ Manejo de  │             │
│ │ Vigente    │  │ Vigente    │  │ Hemorragias│             │
│ │ ✅ 2026    │  │ ✅ 2025    │  │ ⚠️ Vence   │             │
│ │ [PDF 📄]   │  │ [PDF 📄]   │  │ [PDF 📄]   │             │
│ └────────────┘  └────────────┘  └────────────┘             │
│                                                             │
└─────────────────────────────────────────────────────────────┘
```

---

## 7. Criterios de Aceptación Generales

### Funcionalidad
- [ ] Un Jefe de Capacitación puede crear una actividad completa (con 4 sesiones) en menos de 3 minutos.
- [ ] El sistema valida que no haya sobreposición de horarios de sesiones.
- [ ] La inscripción por autoservicio está disponible solo para actividades que lo permiten.
- [ ] El sistema calcula automáticamente el porcentaje de asistencia al registrar cada asistencia.
- [ ] Las certificaciones se generan automáticamente al aprobar inscripciones.
- [ ] El sistema bloquea asignación de enfermeros en GestorTurnos si están en capacitación.

### Trazabilidad
- [ ] Todas las inscripciones registran quién las creó (manual vs autoservicio).
- [ ] Todas las asistencias registran quién las registró y cuándo.
- [ ] Todas las aprobaciones/reprobaciones registran quién, cuándo y por qué.
- [ ] Todas las certificaciones tienen folio único y no modificable.

### Performance
- [ ] El dashboard de enfermero carga en menos de 2 segundos.
- [ ] La aprobación masiva de 50 inscripciones con generación de PDFs toma menos de 30 segundos.
- [ ] El registro de asistencia para 30 inscritos toma menos de 3 minutos (usabilidad).

### Integración
- [ ] La validación de conflictos con turnos consulta correctamente el módulo de turnos.
- [ ] El calendario de capacitaciones muestra correctamente sesiones programadas.
- [ ] Los reportes generan datos precisos consultando múltiples tablas.

### Testing
- [ ] Tests unitarios para cálculo de porcentaje de asistencia.
- [ ] Tests de integración para flujo completo: crear → inscribir → asistencia → aprobar → certificar.
- [ ] Tests de validación de conflictos con turnos.

---

## 8. Plan de Implementación

### Día 1-2: Infraestructura de Datos
- [ ] Crear migración `create_actividades_capacitacion_table`
- [ ] Crear migración `create_sesiones_capacitacion_table`
- [ ] Crear migración `create_inscripciones_capacitacion_table`
- [ ] Crear migración `create_asistencias_capacitacion_table`
- [ ] Crear migración `create_certificaciones_table`
- [ ] Crear modelos con relaciones
- [ ] Crear Enums: `TipoActividad`, `EstadoActividad`, `EstadoInscripcion`, `TipoInscripcion`
- [ ] Ejecutar migraciones
- [ ] Crear factories y seeders de prueba

### Día 3-4: Gestor de Actividades
- [ ] Crear componente Livewire `GestorActividades`
- [ ] Implementar CRUD de actividades
- [ ] Implementar gestión de sesiones (agregar/eliminar)
- [ ] Crear vista con wizard de creación
- [ ] Crear tests de actividades y sesiones

### Día 5-6: Sistema de Inscripciones
- [ ] Crear componente Livewire `GestorInscripciones`
- [ ] Implementar inscripción manual (individual y masiva)
- [ ] Implementar validación de cupos y duplicados
- [ ] Crear componente `DashboardCapacitacion` para enfermeros
- [ ] Implementar inscripción por autoservicio
- [ ] Implementar validación de conflictos con turnos
- [ ] Crear tests de inscripciones

### Día 7-8: Control de Asistencia y Aprobaciones
- [ ] Crear componente Livewire `ControlAsistencia`
- [ ] Implementar registro de asistencia por sesión
- [ ] Implementar cálculo automático de porcentajes
- [ ] Crear componente Livewire `GestorAprobaciones`
- [ ] Implementar aprobación/reprobación individual y masiva
- [ ] Implementar generación automática de certificaciones
- [ ] Crear generador de folios únicos
- [ ] Crear tests de asistencia y aprobaciones

### Día 9: Generación de PDFs y Certificaciones
- [ ] Implementar servicio de generación de PDFs (Laravel DOMPDF o similar)
- [ ] Crear plantilla de certificación con diseño profesional
- [ ] Implementar almacenamiento de PDFs en `storage/certificaciones`
- [ ] Implementar descarga de certificaciones por enfermeros
- [ ] Implementar regeneración de PDFs si es necesario
- [ ] Crear tests de generación de PDFs

### Día 10: Integración con Turnos y Reportes
- [ ] Modificar `GestorTurnos` para validar enfermeros en capacitación
- [ ] Crear componente `CalendarioCapacitaciones` para Jefe de Piso
- [ ] Implementar badge "En Capacitación" en GestorTurnos
- [ ] Crear componente `ReportesCapacitacion` para Coordinador
- [ ] Implementar reportes de participación
- [ ] Implementar reportes de certificaciones vigentes
- [ ] Implementar exportación a PDF/Excel
- [ ] Crear tests de integración

### Día 11-12: Testing y Refinamiento
- [ ] Tests de integración del flujo completo
- [ ] Tests de validación de reglas de negocio
- [ ] Optimización de queries (eager loading)
- [ ] Ajustes de UX según pruebas
- [ ] Actualizar documentación

---

## 9. Definición de "Hecho" (DoD)

Una historia de usuario se considera completada cuando:

- [ ] El código está implementado y funciona según criterios de aceptación
- [ ] Existen tests unitarios que cubren la lógica de negocio
- [ ] Existen tests de integración que validan el flujo completo
- [ ] La interfaz es responsive (móvil, tablet, desktop)
- [ ] Se validaron las reglas de negocio con datos de prueba
- [ ] La generación de PDFs funciona correctamente
- [ ] La integración con módulo de turnos está probada
- [ ] La documentación técnica está actualizada
- [ ] El código fue revisado (self-review mínimo)
- [ ] No hay errores conocidos bloqueantes
- [ ] Las migraciones se ejecutan sin errores
- [ ] Los seeders funcionan correctamente

---

## 10. Riesgos y Mitigaciones

| Riesgo | Probabilidad | Impacto | Mitigación |
|--------|--------------|---------|------------|
| Generación de PDFs lenta con muchas certificaciones | Media | Medio | Implementar queue/jobs para procesamiento asíncrono |
| Conflictos complejos entre horarios de sesiones y turnos | Alta | Alto | Validación exhaustiva al inscribir y al asignar turnos |
| Cálculo incorrecto de porcentajes de asistencia | Media | Alto | Tests exhaustivos y validaciones en modelo |
| Folios duplicados de certificaciones | Baja | Crítico | Constraint unique en DB + generación atómica con lock |
| Pérdida de PDFs de certificaciones | Baja | Medio | Backup automático de storage + posibilidad de regenerar |

---

## 11. Dependencias

### Internas (Sprints Previos)
- **Sprint 1:** Requiere modelo de Enfermero y sistema de roles
- **Sprint 4:** Requiere modelo de Turno para validar conflictos de horario

### Externas
- **Laravel DOMPDF** o **Barryvdh/laravel-dompdf** para generación de PDFs
- Opcional: **Laravel Excel** para exportación de reportes

---

## 12. Notas Finales

### Priorización dentro del Sprint

**Must Have (Crítico):**
- Crear actividades y sesiones
- Inscripciones manual y autoservicio
- Registro de asistencia
- Cálculo de porcentajes
- Aprobaciones y certificaciones

**Should Have (Importante):**
- Generación de PDFs profesionales
- Validación de conflictos con turnos
- Dashboard de enfermero completo
- Reportes básicos

**Could Have (Deseable):**
- Calendario visual de capacitaciones
- Reportes avanzados con gráficos
- Exportación a Excel
- Notificaciones por email

**Won't Have (Fuera de Alcance):**
- Evaluaciones con calificaciones numéricas (solo asistencia)
- Firma digital de certificaciones
- Integración con plataformas de e-learning externas
- App móvil nativa para registro de asistencia

---

**Siguiente Sprint Sugerido:** Sprint 6 - Módulo de Farmacia e Insumos

**Fecha de Creación:** 2025-11-23
**Responsable:** Claude AI Assistant
**Revisión:** Pendiente de revisión por equipo
