# Sprint 7: Administración de Medicamentos e Insumos

**Epic:** Epic #6 - Módulo de Farmacia y Control de Medicamentos
**Duración:** 2-3 semanas
**Fecha de inicio:** 2025-12-10
**Fecha de finalización:** 2025-12-31
**Estado:** Planificado
**Épica Maestra en GitHub:** [Issue #48](https://github.com/eddndev/nursehub/issues/48)

---

## 1. Objetivos del Sprint

### Objetivo Principal
Implementar el sistema completo de gestión de medicamentos e insumos médicos, permitiendo el control de inventario por área, solicitudes y despachos de medicamentos, registro de administración a pacientes, alertas de interacciones medicamentosas, control de caducidades, y trazabilidad completa desde la entrada al almacén hasta la administración al paciente.

### Objetivos Específicos
1. Crear catálogo maestro de medicamentos con información completa (principio activo, presentación, dosis, vía de administración)
2. Implementar control de inventario multi-nivel (almacén general + stock por área/piso)
3. Desarrollar sistema de solicitudes de medicamentos por enfermeros y despachos por farmacia
4. Implementar registro de administración de medicamentos a pacientes con integración al Registro Clínico
5. Crear sistema de alertas de interacciones medicamentosas y alergias del paciente
6. Implementar control de medicamentos controlados (estupefacientes) con trazabilidad especial
7. Desarrollar alertas de stock mínimo, caducidades próximas y reorden automático
8. Crear reportes de consumo, costos, desperdicios y auditoría

### Métricas de Éxito
- Registrar solicitud de medicamento y despachar en menos de 2 minutos
- Alertas de interacciones medicamentosas en menos de 1 segundo al seleccionar medicamento
- 100% de trazabilidad desde entrada hasta administración
- Alertas automáticas de caducidades con 60 días de anticipación
- Reportes de consumo por área/paciente en menos de 5 segundos
- Control de medicamentos controlados con doble verificación

---

## 2. Alcance del Sprint

### Historias de Usuario

#### **Catálogo de Medicamentos**
- [ ] US-MED-001: Como Farmacéutico, quiero poder registrar medicamentos en el catálogo maestro con toda su información (nombre comercial, genérico, laboratorio, presentación) para mantener base de datos actualizada.
- [ ] US-MED-002: Como Farmacéutico, quiero poder clasificar medicamentos por categoría terapéutica (analgésicos, antibióticos, etc.) para facilitar búsqueda y reportes.
- [ ] US-MED-003: Como Farmacéutico, quiero poder marcar medicamentos como "controlados" para aplicar controles especiales.
- [ ] US-MED-004: Como Farmacéutico, quiero poder registrar interacciones medicamentosas conocidas para alertar al personal de enfermería.
- [ ] US-MED-005: Como Farmacéutico, quiero poder definir equivalencias entre medicamentos (genéricos-comerciales) para sustituciones autorizadas.

#### **Control de Inventario**
- [ ] US-INV-001: Como Farmacéutico, quiero poder registrar entradas de medicamentos al almacén (compras, donaciones, transferencias) para actualizar inventario.
- [ ] US-INV-002: Como Farmacéutico, quiero poder definir stock mínimo por medicamento para recibir alertas de reorden.
- [ ] US-INV-003: Como Farmacéutico, quiero ver dashboard de inventario con stock actual, alertas de mínimos y caducidades para tomar decisiones.
- [ ] US-INV-004: Como Farmacéutico, quiero poder hacer ajustes de inventario (mermas, devoluciones, conteos) para mantener stock exacto.
- [ ] US-INV-005: Como Farmacéutico, quiero poder transferir stock entre áreas para optimizar distribución.
- [ ] US-INV-006: Como Jefe de Piso, quiero ver el stock disponible en mi área para saber qué medicamentos tengo disponibles.

#### **Solicitudes y Despachos**
- [ ] US-SOL-001: Como Enfermero, quiero poder solicitar medicamentos para mis pacientes desde el sistema para evitar desplazamientos innecesarios.
- [ ] US-SOL-002: Como Enfermero, quiero que el sistema valide disponibilidad del medicamento antes de enviar solicitud para evitar rechazos.
- [ ] US-SOL-003: Como Enfermero, quiero ver el estado de mis solicitudes (pendiente, aprobada, despachada, rechazada) para dar seguimiento.
- [ ] US-SOL-004: Como Farmacéutico, quiero recibir notificación cuando haya una nueva solicitud de medicamento para atenderla de inmediato.
- [ ] US-SOL-005: Como Farmacéutico, quiero poder aprobar o rechazar solicitudes con comentarios para comunicar decisiones.
- [ ] US-SOL-006: Como Farmacéutico, quiero poder despachar medicamentos y descontar automáticamente del inventario para mantener stock actualizado.
- [ ] US-SOL-007: Como Enfermero, quiero recibir notificación cuando mi solicitud sea despachada para recoger el medicamento.

#### **Administración a Pacientes**
- [ ] US-ADM-001: Como Enfermero, quiero poder registrar la administración de un medicamento a un paciente para llevar control exacto.
- [ ] US-ADM-002: Como Enfermero, quiero que el sistema me muestre las indicaciones médicas activas del paciente para administrar correctamente.
- [ ] US-ADM-003: Como Enfermero, quiero que el sistema me alerte si el paciente tiene alergias al medicamento que voy a administrar para evitar reacciones adversas.
- [ ] US-ADM-004: Como Enfermero, quiero poder registrar efectos adversos o reacciones del paciente después de administrar medicamento para documentar eventos.
- [ ] US-ADM-005: Como Enfermero, quiero ver el historial de medicamentos administrados al paciente para evitar duplicidades y conocer tratamiento.
- [ ] US-ADM-006: Como Médico, quiero poder ver desde el expediente del paciente todos los medicamentos administrados para evaluar tratamiento.

#### **Alertas y Validaciones**
- [ ] US-ALE-001: Como Enfermero, quiero recibir alerta si hay interacción medicamentosa entre el medicamento que voy a administrar y otros que ya recibe el paciente para prevenir efectos adversos.
- [ ] US-ALE-002: Como Enfermero, quiero recibir alerta si la dosis que voy a administrar excede el máximo recomendado para evitar sobredosis.
- [ ] US-ALE-003: Como Farmacéutico, quiero recibir alertas automáticas de medicamentos próximos a caducar (60 días) para gestionar rotación.
- [ ] US-ALE-004: Como Farmacéutico, quiero recibir alertas cuando el stock llegue al mínimo definido para hacer pedidos a tiempo.
- [ ] US-ALE-005: Como Coordinador, quiero recibir alertas de medicamentos controlados que requieren reorden para gestionar compra con tiempo.

#### **Medicamentos Controlados**
- [ ] US-CON-001: Como Farmacéutico, quiero que los medicamentos controlados requieran doble verificación (dos usuarios) para despachar para cumplir normativa.
- [ ] US-CON-002: Como Farmacéutico, quiero llevar un registro especial de medicamentos controlados con detalle de quién solicitó, despachó y administró para auditoría.
- [ ] US-CON-003: Como Coordinador, quiero poder generar reporte de auditoría de medicamentos controlados por período para cumplimiento regulatorio.
- [ ] US-CON-004: Como Farmacéutico, quiero que el sistema valide que solo personal autorizado pueda solicitar medicamentos controlados para control de acceso.

#### **Reportes y Analytics**
- [ ] US-REP-001: Como Farmacéutico, quiero generar reporte de consumo de medicamentos por área para analizar patrones de uso.
- [ ] US-REP-002: Como Farmacéutico, quiero generar reporte de costos de medicamentos por área/paciente para control presupuestal.
- [ ] US-REP-003: Como Coordinador, quiero generar reporte de desperdicios (caducados, mermas) para identificar mejoras.
- [ ] US-REP-004: Como Coordinador, quiero generar reporte de medicamentos más consumidos para planificación de compras.
- [ ] US-REP-005: Como Coordinador, quiero exportar reportes de farmacia a Excel/PDF para presentaciones y auditorías.
- [ ] US-REP-006: Como Farmacéutico, quiero ver gráficos de consumo histórico por medicamento para identificar tendencias.

---

## 2.1 Issues del Sprint

Este sprint se divide en 7 issues técnicas principales:

| Issue | Título | Tipo | Prioridad | Historias |
|-------|--------|------|-----------|-----------|
| [#49](https://github.com/eddndev/nursehub/issues/49) | Infraestructura de Datos de Farmacia | Chore | Critical | Todas |
| [#50](https://github.com/eddndev/nursehub/issues/50) | Catálogo de Medicamentos e Inventario | Feature | Critical | US-MED-001 a 005, US-INV-001 a 006 |
| [#51](https://github.com/eddndev/nursehub/issues/51) | Sistema de Solicitudes y Despachos | Feature | Critical | US-SOL-001 a 007 |
| [#52](https://github.com/eddndev/nursehub/issues/52) | Administración de Medicamentos a Pacientes | Feature | Critical | US-ADM-001 a 006 |
| [#53](https://github.com/eddndev/nursehub/issues/53) | Alertas de Interacciones y Validaciones | Feature | High | US-ALE-001 a 005 |
| [#54](https://github.com/eddndev/nursehub/issues/54) | Control de Medicamentos Controlados | Feature | High | US-CON-001 a 004 |
| [#55](https://github.com/eddndev/nursehub/issues/55) | Reportes y Analytics de Farmacia | Feature | Medium | US-REP-001 a 006 |

---

## 3. Arquitectura Técnica

### 3.1 Nuevos Modelos

#### **Medicamento**
```php
Schema::create('medicamentos', function (Blueprint $table) {
    $table->id();
    $table->string('codigo_medicamento')->unique(); // Código interno
    $table->string('nombre_comercial');
    $table->string('nombre_generico');
    $table->string('principio_activo');
    $table->string('laboratorio')->nullable();
    $table->string('presentacion'); // Tableta, Ampolleta, Jarabe, etc.
    $table->string('concentracion'); // 500mg, 10ml, etc.
    $table->enum('via_administracion', ['oral', 'intravenosa', 'intramuscular', 'subcutanea', 'topica', 'rectal', 'inhalatoria']);
    $table->foreignId('categoria_id')->nullable()->constrained('categorias_medicamento');
    $table->boolean('es_controlado')->default(false);
    $table->decimal('precio_unitario', 10, 2)->nullable();
    $table->text('indicaciones')->nullable();
    $table->text('contraindicaciones')->nullable();
    $table->text('efectos_adversos')->nullable();
    $table->decimal('dosis_maxima_24h', 10, 2)->nullable();
    $table->string('unidad_medida')->default('mg'); // mg, ml, UI, etc.
    $table->boolean('requiere_refrigeracion')->default(false);
    $table->boolean('activo')->default(true);
    $table->timestamps();
    $table->softDeletes();

    $table->index(['nombre_comercial', 'nombre_generico']);
    $table->index(['es_controlado', 'activo']);
});
```

#### **CategoriaMedicamento**
```php
Schema::create('categorias_medicamento', function (Blueprint $table) {
    $table->id();
    $table->string('nombre'); // Analgésicos, Antibióticos, etc.
    $table->string('codigo')->unique();
    $table->text('descripcion')->nullable();
    $table->timestamps();
});
```

#### **InteraccionMedicamentosa**
```php
Schema::create('interacciones_medicamentosas', function (Blueprint $table) {
    $table->id();
    $table->foreignId('medicamento_a_id')->constrained('medicamentos');
    $table->foreignId('medicamento_b_id')->constrained('medicamentos');
    $table->enum('severidad', ['leve', 'moderada', 'grave', 'contraindicada']);
    $table->text('descripcion');
    $table->text('recomendacion')->nullable();
    $table->timestamps();

    $table->unique(['medicamento_a_id', 'medicamento_b_id']);
});
```

#### **InventarioMedicamento**
```php
Schema::create('inventario_medicamentos', function (Blueprint $table) {
    $table->id();
    $table->foreignId('medicamento_id')->constrained('medicamentos');
    $table->foreignId('area_id')->nullable()->constrained('areas'); // NULL = almacén general
    $table->string('lote');
    $table->date('fecha_caducidad');
    $table->integer('cantidad_actual');
    $table->integer('cantidad_inicial');
    $table->integer('stock_minimo')->default(10);
    $table->integer('stock_maximo')->nullable();
    $table->decimal('costo_unitario', 10, 2);
    $table->enum('estado', ['disponible', 'cuarentena', 'caducado', 'agotado'])->default('disponible');
    $table->timestamps();

    $table->index(['medicamento_id', 'area_id', 'estado']);
    $table->index('fecha_caducidad');
});
```

#### **MovimientoInventario**
```php
Schema::create('movimientos_inventario', function (Blueprint $table) {
    $table->id();
    $table->foreignId('inventario_id')->constrained('inventario_medicamentos');
    $table->enum('tipo_movimiento', ['entrada', 'salida', 'ajuste', 'transferencia', 'devolucion', 'merma']);
    $table->integer('cantidad');
    $table->integer('cantidad_anterior');
    $table->integer('cantidad_nueva');
    $table->foreignId('area_origen_id')->nullable()->constrained('areas');
    $table->foreignId('area_destino_id')->nullable()->constrained('areas');
    $table->text('motivo')->nullable();
    $table->foreignId('usuario_id')->constrained('users');
    $table->timestamp('fecha_movimiento');
    $table->string('referencia')->nullable(); // # de factura, # de solicitud, etc.
    $table->timestamps();

    $table->index(['inventario_id', 'tipo_movimiento']);
    $table->index('fecha_movimiento');
});
```

#### **SolicitudMedicamento**
```php
Schema::create('solicitudes_medicamento', function (Blueprint $table) {
    $table->id();
    $table->string('numero_solicitud')->unique(); // SOL-2025-0001
    $table->foreignId('enfermero_id')->constrained('enfermeros');
    $table->foreignId('paciente_id')->constrained('pacientes');
    $table->foreignId('area_id')->constrained('areas');
    $table->enum('prioridad', ['normal', 'urgente', 'stat'])->default('normal');
    $table->enum('estado', ['pendiente', 'aprobada', 'despachada', 'rechazada', 'cancelada'])->default('pendiente');
    $table->timestamp('fecha_solicitud');
    $table->foreignId('aprobado_por')->nullable()->constrained('users');
    $table->timestamp('fecha_aprobacion')->nullable();
    $table->foreignId('despachado_por')->nullable()->constrained('users');
    $table->timestamp('fecha_despacho')->nullable();
    $table->text('observaciones')->nullable();
    $table->text('motivo_rechazo')->nullable();
    $table->timestamps();

    $table->index(['estado', 'prioridad']);
    $table->index('fecha_solicitud');
});
```

#### **DetalleSolicitudMedicamento**
```php
Schema::create('detalles_solicitud_medicamento', function (Blueprint $table) {
    $table->id();
    $table->foreignId('solicitud_id')->constrained('solicitudes_medicamento')->onDelete('cascade');
    $table->foreignId('medicamento_id')->constrained('medicamentos');
    $table->integer('cantidad_solicitada');
    $table->integer('cantidad_despachada')->default(0);
    $table->foreignId('inventario_id')->nullable()->constrained('inventario_medicamentos'); // Lote despachado
    $table->text('indicaciones_medicas')->nullable(); // "Cada 8 horas por 7 días"
    $table->timestamps();
});
```

#### **AdministracionMedicamento**
```php
Schema::create('administraciones_medicamento', function (Blueprint $table) {
    $table->id();
    $table->foreignId('paciente_id')->constrained('pacientes');
    $table->foreignId('enfermero_id')->constrained('enfermeros');
    $table->foreignId('medicamento_id')->constrained('medicamentos');
    $table->foreignId('solicitud_id')->nullable()->constrained('solicitudes_medicamento');
    $table->foreignId('admision_id')->nullable()->constrained('admisiones'); // Vinculación con RCE
    $table->timestamp('fecha_hora_administracion');
    $table->string('dosis_administrada'); // "500mg"
    $table->string('via_administracion');
    $table->text('observaciones')->nullable();
    $table->boolean('tuvo_reaccion_adversa')->default(false);
    $table->text('descripcion_reaccion')->nullable();
    $table->foreignId('verificado_por')->nullable()->constrained('users'); // Segunda verificación
    $table->timestamps();

    $table->index(['paciente_id', 'fecha_hora_administracion']);
    $table->index(['medicamento_id', 'fecha_hora_administracion']);
});
```

#### **RegistroMedicamentoControlado**
```php
Schema::create('registro_medicamento_controlado', function (Blueprint $table) {
    $table->id();
    $table->foreignId('medicamento_id')->constrained('medicamentos');
    $table->foreignId('solicitud_id')->nullable()->constrained('solicitudes_medicamento');
    $table->foreignId('administracion_id')->nullable()->constrained('administraciones_medicamento');
    $table->enum('tipo_operacion', ['entrada', 'salida', 'ajuste', 'destruccion']);
    $table->integer('cantidad');
    $table->foreignId('usuario_id')->constrained('users'); // Quien solicitó/despachó
    $table->foreignId('autorizado_por')->constrained('users'); // Segunda firma
    $table->string('numero_receta')->nullable();
    $table->text('justificacion');
    $table->timestamp('fecha_operacion');
    $table->timestamps();

    $table->index(['medicamento_id', 'fecha_operacion']);
});
```

### 3.2 Relaciones de Modelos

**Medicamento:**
- belongsTo: CategoriaMedicamento
- hasMany: InventarioMedicamento
- hasMany: SolicitudMedicamento (through DetalleSolicitud)
- hasMany: AdministracionMedicamento
- belongsToMany: Medicamento (interacciones) through InteraccionMedicamentosa

**InventarioMedicamento:**
- belongsTo: Medicamento
- belongsTo: Area
- hasMany: MovimientoInventario
- hasMany: DetalleSolicitudMedicamento

**SolicitudMedicamento:**
- belongsTo: Enfermero
- belongsTo: Paciente
- belongsTo: Area
- hasMany: DetalleSolicitudMedicamento
- belongsTo: User (aprobado_por, despachado_por)

**AdministracionMedicamento:**
- belongsTo: Paciente
- belongsTo: Enfermero
- belongsTo: Medicamento
- belongsTo: SolicitudMedicamento
- belongsTo: Admision

**Paciente (modificaciones):**
- hasMany: SolicitudMedicamento
- hasMany: AdministracionMedicamento

**Enfermero (modificaciones):**
- hasMany: SolicitudMedicamento
- hasMany: AdministracionMedicamento

### 3.3 Enums

#### **CategoriaMedicamento**
```php
enum CategoriaMedicamento: string
{
    case ANALGESICO = 'analgesico';
    case ANTIBIOTICO = 'antibiotico';
    case ANTIINFLAMATORIO = 'antiinflamatorio';
    case CARDIOVASCULAR = 'cardiovascular';
    case GASTROINTESTINAL = 'gastrointestinal';
    case RESPIRATORIO = 'respiratorio';
    case ENDOCRINO = 'endocrino';
    case NEUROLOGICO = 'neurologico';
    case HEMATOLOGICO = 'hematologico';
    case DERMATOLOGICO = 'dermatologico';
    case OTRO = 'otro';

    public function getLabel(): string;
    public function getIcon(): string;
}
```

#### **ViaAdministracion**
```php
enum ViaAdministracion: string
{
    case ORAL = 'oral';
    case INTRAVENOSA = 'intravenosa';
    case INTRAMUSCULAR = 'intramuscular';
    case SUBCUTANEA = 'subcutanea';
    case TOPICA = 'topica';
    case RECTAL = 'rectal';
    case INHALATORIA = 'inhalatoria';
    case OFTALMICA = 'oftalmica';
    case OTICA = 'otica';

    public function getLabel(): string;
}
```

#### **EstadoSolicitud**
```php
enum EstadoSolicitud: string
{
    case PENDIENTE = 'pendiente';
    case APROBADA = 'aprobada';
    case DESPACHADA = 'despachada';
    case RECHAZADA = 'rechazada';
    case CANCELADA = 'cancelada';

    public function getLabel(): string;
    public function getColor(): string;
}
```

#### **PrioridadSolicitud**
```php
enum PrioridadSolicitud: string
{
    case NORMAL = 'normal';
    case URGENTE = 'urgente';
    case STAT = 'stat'; // Inmediato

    public function getLabel(): string;
    public function getColor(): string;
}
```

---

## 4. Componentes Livewire

### 4.1 CatalogoMedicamentos (Farmacéutico)
**Propósito:** CRUD de medicamentos y gestión de catálogo maestro

**Propiedades:**
- $medicamentos (paginados)
- $filtros (categoría, tipo, activo/inactivo)
- $modalCrear, $modalEditar
- $medicamentoSeleccionado

**Métodos:**
- crearMedicamento($datos)
- editarMedicamento($id, $datos)
- desactivarMedicamento($id)
- registrarInteraccion($medicamentoA, $medicamentoB, $datos)
- buscarMedicamento($termino)

**Vista:**
- Tabla de medicamentos con búsqueda avanzada
- Modal de creación/edición con tabs
- Gestión de interacciones medicamentosas
- Vista de equivalencias

---

### 4.2 GestorInventario (Farmacéutico)
**Propósito:** Control de inventario y movimientos

**Propiedades:**
- $inventario (por área/almacén)
- $alertas (stock mínimo, caducidades)
- $modalEntrada, $modalSalida, $modalTransferencia

**Métodos:**
- registrarEntrada($datos)
- registrarSalida($datos)
- transferirEntreAreas($origen, $destino, $medicamento, $cantidad)
- ajustarInventario($inventarioId, $nuevaCantidad, $motivo)
- calcularValorInventario()

**Vista:**
- Dashboard con métricas de inventario
- Alertas destacadas (caducidades, mínimos)
- Tabla de stock actual por medicamento/área
- Modales para movimientos
- Historial de movimientos

---

### 4.3 SolicitudesMedicamentos (Enfermero)
**Propósito:** Crear y dar seguimiento a solicitudes de medicamentos

**Propiedades:**
- $pacienteSeleccionado
- $medicamentosAgregados
- $misSolicitudes
- $modalNuevaSolicitud

**Métodos:**
- agregarMedicamento($medicamentoId, $cantidad)
- validarDisponibilidad($medicamentoId)
- enviarSolicitud()
- cancelarSolicitud($solicitudId)
- verDetalleSolicitud($solicitudId)

**Vista:**
- Formulario de nueva solicitud con autocomplete de medicamentos
- Validación de disponibilidad en tiempo real
- Lista de mis solicitudes con estados
- Modal de detalles de solicitud

---

### 4.4 DespachoFarmacia (Farmacéutico)
**Propósito:** Aprobar, despachar y rechazar solicitudes

**Propiedades:**
- $solicitudesPendientes
- $solicitudesAprobadas
- $solicitudSeleccionada
- $modalDespacho

**Métodos:**
- aprobarSolicitud($solicitudId)
- rechazarSolicitud($solicitudId, $motivo)
- despacharSolicitud($solicitudId, $lotes)
- seleccionarLote($medicamentoId) // FIFO
- verificarDobleControlado($solicitudId, $usuarioVerificador)

**Vista:**
- Dashboard de solicitudes pendientes
- Priorización visual (STAT en rojo)
- Modal de despacho con selección de lotes
- Doble verificación para controlados

---

### 4.5 AdministracionMedicamentos (Enfermero)
**Propósito:** Registrar administración de medicamentos a pacientes

**Propiedades:**
- $pacienteSeleccionado
- $medicamentosDisponibles
- $indicacionesMedicas
- $historialAdministraciones

**Métodos:**
- seleccionarPaciente($pacienteId)
- validarAlergias($pacienteId, $medicamentoId)
- validarInteracciones($pacienteId, $medicamentoId)
- registrarAdministracion($datos)
- registrarReaccionAdversa($administracionId, $datos)

**Vista:**
- Búsqueda de paciente
- Lista de indicaciones médicas activas
- Alertas de alergias e interacciones
- Formulario de registro de administración
- Historial de medicamentos administrados al paciente

---

### 4.6 ReportesFarmacia (Coordinador/Farmacéutico)
**Propósito:** Reportes y analytics de farmacia

**Propiedades:**
- $tipoReporte
- $fechaInicio, $fechaFin
- $areaFiltro
- $datosReporte

**Métodos:**
- generarReporteConsumo()
- generarReporteCostos()
- generarReporteDesperdicios()
- generarReporteControlados()
- exportarExcel()
- exportarPDF()

**Vista:**
- Filtros de período y área
- Tabs de diferentes reportes
- Gráficos de consumo histórico
- Tablas de datos exportables
- KPIs principales

---

### 4.7 AlertasMedicamentos (Sistema)
**Propósito:** Sistema de alertas automáticas

**Jobs:**
- AlertasCaducidadesJob (diario)
- AlertasStockMinimoJob (diario)
- AlertasControlados Job (semanal)

---

## 5. Servicios Especializados

### 5.1 InteraccionMedicamentosaService
```php
class InteraccionMedicamentosaService
{
    public function verificarInteracciones(Paciente $paciente, int $medicamentoId): array
    {
        // Obtener medicamentos activos del paciente
        $medicamentosActivos = $paciente->administracionesRecientes(24); // Últimas 24h

        $interacciones = [];
        foreach ($medicamentosActivos as $medicamentoActivo) {
            $interaccion = InteraccionMedicamentosa::where(function ($query) use ($medicamentoId, $medicamentoActivo) {
                $query->where('medicamento_a_id', $medicamentoId)
                    ->where('medicamento_b_id', $medicamentoActivo->medicamento_id);
            })->orWhere(function ($query) use ($medicamentoId, $medicamentoActivo) {
                $query->where('medicamento_a_id', $medicamentoActivo->medicamento_id)
                    ->where('medicamento_b_id', $medicamentoId);
            })->first();

            if ($interaccion) {
                $interacciones[] = $interaccion;
            }
        }

        return $interacciones;
    }
}
```

### 5.2 AlertaMedicamentoService
```php
class AlertaMedicamentoService
{
    public function alertarCaducidadesCercanas(int $diasAnticipacion = 60): void
    {
        $inventarios = InventarioMedicamento::where('fecha_caducidad', '<=', now()->addDays($diasAnticipacion))
            ->where('fecha_caducidad', '>', now())
            ->where('cantidad_actual', '>', 0)
            ->get();

        foreach ($inventarios as $inventario) {
            // Notificar a farmacéutico
        }
    }

    public function alertarStockMinimo(): void
    {
        $inventarios = InventarioMedicamento::whereRaw('cantidad_actual <= stock_minimo')
            ->get();

        foreach ($inventarios as $inventario) {
            // Notificar a farmacéutico
        }
    }
}
```

---

## 6. Reglas de Negocio

### Gestión de Inventario
1. El inventario se gestiona por **lote** (cada entrada tiene lote y fecha de caducidad)
2. Los despachos deben seguir **FIFO** (First In, First Out) - se despacha primero lo que caduca antes
3. No se pueden despachar medicamentos con fecha de caducidad **menor a 30 días**
4. Alertas de stock mínimo se generan cuando `cantidad_actual <= stock_minimo`
5. Alertas de caducidad se generan con **60 días de anticipación**

### Solicitudes y Despachos
1. Una solicitud puede tener **múltiples medicamentos** (detalles)
2. Solo el farmacéutico puede **aprobar/rechazar** solicitudes
3. Solo el farmacéutico puede **despachar** medicamentos
4. Al despachar, se debe descontar **automáticamente** del inventario
5. Si no hay stock suficiente, el farmacéutico puede despachar **parcialmente**
6. Solicitudes STAT se muestran en **rojo** y en la parte superior de la lista

### Medicamentos Controlados
1. Requieren **doble verificación** (dos usuarios diferentes) para despachar
2. Requieren justificación médica (**número de receta**)
3. Llevan registro especial en `registro_medicamento_controlado`
4. Solo personal autorizado (role específico) puede solicitar y despachar
5. Auditoría mensual obligatoria con reporte detallado

### Administración a Pacientes
1. Antes de administrar, validar:
   - **Alergias del paciente** al medicamento
   - **Interacciones** con otros medicamentos administrados en últimas 24h
   - **Dosis máxima** permitida en 24h
2. Si hay alergias, **bloquear** administración y mostrar alerta
3. Si hay interacciones graves, **alertar** pero permitir con confirmación
4. Registro de administración queda vinculado al **Registro Clínico Electrónico**
5. Si hubo reacción adversa, crear alerta en perfil del paciente

### Validaciones de Seguridad
1. No se puede administrar medicamento sin tener solicitud aprobada (excepto emergencias)
2. No se puede despachar más cantidad de la existente en inventario
3. No se puede modificar registro de administración después de 24 horas
4. Medicamentos controlados solo se despachan con receta médica válida

---

## 7. Casos de Uso Principales

### Caso de Uso 1: Solicitar Medicamento (Enfermero)

**Actor:** Enfermero
**Flujo Principal:**
1. Enfermero selecciona paciente desde su listado de asignaciones
2. Click en "Solicitar Medicamentos"
3. Sistema muestra formulario con autocomplete de medicamentos
4. Enfermero escribe "paracetamol" → Sistema sugiere opciones disponibles
5. Enfermero selecciona "Paracetamol 500mg tableta"
6. Enfermero ingresa cantidad: 10 tabletas
7. Sistema valida stock disponible → ✅ Hay 200 en almacén
8. Enfermero agrega a la lista (puede agregar más medicamentos)
9. Enfermero selecciona prioridad: "Normal"
10. Enfermero agrega observaciones: "Para fiebre post-quirúrgica"
11. Enfermero confirma solicitud
12. Sistema genera número SOL-2025-0001 y envía notificación a farmacia

**Resultado:** Solicitud creada, farmacia notificada.

---

### Caso de Uso 2: Despachar Medicamento (Farmacéutico)

**Actor:** Farmacéutico
**Flujo Principal:**
1. Farmacéutico recibe notificación de nueva solicitud
2. Ingresa a "Despacho de Farmacia"
3. Sistema muestra solicitud SOL-2025-0001 en lista de pendientes
4. Farmacéutico click en "Ver Detalles"
5. Sistema muestra:
   - Paciente: Juan Pérez
   - Enfermero solicitante: María García
   - Medicamento: Paracetamol 500mg x10
   - Prioridad: Normal
6. Farmacéutico valida que hay stock
7. Farmacéutico click en "Aprobar y Despachar"
8. Sistema muestra lotes disponibles ordenados por fecha de caducidad (FIFO):
   - Lote A: Cad 2025-06-01, Stock: 50
   - Lote B: Cad 2025-12-01, Stock: 150
9. Sistema pre-selecciona Lote A (caduca primero)
10. Farmacéutico confirma despacho
11. Sistema:
    - Descuenta 10 del Lote A (queda 40)
    - Cambia estado solicitud a "Despachada"
    - Registra movimiento de inventario
    - Notifica al enfermero

**Resultado:** Medicamento despachado, inventario actualizado, enfermero notificado.

---

### Caso de Uso 3: Administrar Medicamento a Paciente (Enfermero)

**Actor:** Enfermero
**Flujo Principal:**
1. Enfermero recibe notificación: "Medicamento despachado"
2. Enfermero recoge medicamento en farmacia
3. Enfermero va con el paciente y escanea su código (o selecciona manualmente)
4. Sistema muestra perfil del paciente con:
   - Alergias registradas
   - Medicamentos administrados recientemente
   - Indicaciones médicas activas
5. Enfermero click en "Administrar Medicamento"
6. Enfermero selecciona: Paracetamol 500mg
7. Sistema ejecuta validaciones:
   - ❌ ALERTA: "Paciente alérgico a Paracetamol" → Bloquea administración
8. Enfermero ve alerta y decide:
   - Contactar al médico para cambiar indicación
   - O registrar que no se pudo administrar con motivo

**Flujo Alternativo (sin alergias):**
7. Sistema valida:
   - ✅ Sin alergias
   - ✅ Sin interacciones graves
   - ✅ Dosis dentro del límite 24h
8. Sistema permite administrar
9. Enfermero ingresa:
   - Dosis administrada: 500mg
   - Vía: Oral
   - Observaciones: "Paciente toleró bien"
10. Enfermero confirma
11. Sistema registra en:
    - `administraciones_medicamento`
    - Registro Clínico del paciente (integración Sprint 2)
    - Kardex de enfermería
12. Enfermero puede marcar "Vigilar por 30 min" para efectos adversos

**Resultado:** Medicamento administrado, registro en historial clínico.

---

### Caso de Uso 4: Medicamento Controlado (Morfina)

**Actor:** Farmacéutico (y Usuario Verificador)
**Flujo Principal:**
1. Enfermero solicita Morfina 10mg para paciente con dolor severo
2. Sistema marca solicitud como "Controlado" (badge rojo)
3. Farmacéutico recibe alerta prioritaria
4. Farmacéutico abre solicitud
5. Sistema solicita:
   - Número de receta médica (obligatorio)
   - Justificación detallada
6. Farmacéutico ingresa datos
7. Sistema requiere "Segunda Verificación"
8. Farmacéutico solicita a otro usuario autorizado escanear su credencial
9. Usuario verificador escanea y confirma
10. Sistema valida que son dos usuarios diferentes
11. Sistema permite despacho
12. Sistema registra en:
    - `registro_medicamento_controlado`
    - Movimiento de inventario
    - Solicitud despachada
13. Sistema genera log de auditoría con ambas firmas

**Resultado:** Medicamento controlado despachado con doble verificación y trazabilidad completa.

---

## 8. Plan de Implementación

### Semana 1: Infraestructura y Catálogo

**Día 1-2: Modelos y Migraciones**
- [ ] Crear migraciones de todas las tablas
- [ ] Crear modelos con relaciones
- [ ] Crear enums
- [ ] Crear factories y seeders
- [ ] Ejecutar migraciones y probar seeders

**Día 3-4: Catálogo de Medicamentos**
- [ ] Crear componente `CatalogoMedicamentos`
- [ ] Implementar CRUD de medicamentos
- [ ] Implementar gestión de categorías
- [ ] Implementar gestión de interacciones
- [ ] Crear tests

**Día 5: Inventario Básico**
- [ ] Crear componente `GestorInventario`
- [ ] Implementar registro de entradas
- [ ] Implementar ajustes de inventario
- [ ] Implementar vista de stock actual
- [ ] Crear tests

---

### Semana 2: Solicitudes y Despachos

**Día 6-7: Solicitudes de Enfermeros**
- [ ] Crear componente `SolicitudesMedicamentos`
- [ ] Implementar formulario de nueva solicitud
- [ ] Implementar autocomplete de medicamentos
- [ ] Implementar validación de disponibilidad
- [ ] Implementar seguimiento de solicitudes
- [ ] Crear tests

**Día 8-9: Despacho de Farmacia**
- [ ] Crear componente `DespachoFarmacia`
- [ ] Implementar aprobación/rechazo de solicitudes
- [ ] Implementar despacho con selección de lotes (FIFO)
- [ ] Implementar descuento automático de inventario
- [ ] Implementar notificaciones
- [ ] Crear tests

**Día 10: Transferencias entre Áreas**
- [ ] Implementar transferencias de stock
- [ ] Implementar validaciones
- [ ] Crear tests

---

### Semana 3: Administración y Controles

**Día 11-12: Administración a Pacientes**
- [ ] Crear componente `AdministracionMedicamentos`
- [ ] Implementar selección de paciente
- [ ] Implementar formulario de administración
- [ ] Crear servicio `InteraccionMedicamentosaService`
- [ ] Implementar validaciones de alergias e interacciones
- [ ] Integrar con Registro Clínico Electrónico (Sprint 2)
- [ ] Implementar registro de reacciones adversas
- [ ] Crear tests

**Día 13: Medicamentos Controlados**
- [ ] Implementar doble verificación
- [ ] Crear modelo `RegistroMedicamentoControlado`
- [ ] Implementar registro especial de auditoría
- [ ] Implementar validaciones de acceso
- [ ] Crear tests

**Día 14: Alertas Automáticas**
- [ ] Crear servicio `AlertaMedicamentoService`
- [ ] Crear job `AlertasCaducidadesJob`
- [ ] Crear job `AlertasStockMinimoJob`
- [ ] Programar jobs en Schedule
- [ ] Crear notificaciones por email
- [ ] Crear tests

**Día 15: Reportes**
- [ ] Crear componente `ReportesFarmacia`
- [ ] Implementar reporte de consumo
- [ ] Implementar reporte de costos
- [ ] Implementar reporte de desperdicios
- [ ] Implementar reporte de controlados (auditoría)
- [ ] Implementar gráficos con Chart.js
- [ ] Implementar exportación Excel/PDF
- [ ] Crear tests

---

### Días 16-18: Testing y Refinamiento
- [ ] Tests de integración completos
- [ ] Tests de servicios
- [ ] Tests de jobs
- [ ] Pruebas de performance
- [ ] Optimización de queries
- [ ] Ajustes de UX
- [ ] Documentación

---

## 9. Criterios de Aceptación

### Funcionalidad
- [ ] Enfermero puede solicitar medicamento en menos de 2 minutos
- [ ] Farmacéutico puede despachar solicitud en menos de 2 minutos
- [ ] Alertas de interacciones aparecen en menos de 1 segundo
- [ ] Despacho sigue FIFO automáticamente
- [ ] Medicamentos controlados requieren doble verificación
- [ ] Integración con RCE funciona correctamente

### Seguridad
- [ ] Validación de alergias bloquea administración
- [ ] Validación de interacciones alerta correctamente
- [ ] Solo personal autorizado puede despachar controlados
- [ ] Trazabilidad completa de medicamentos controlados
- [ ] No se pueden hacer modificaciones después de 24h

### Performance
- [ ] Dashboard de inventario carga en menos de 3 segundos
- [ ] Búsqueda de medicamentos con autocomplete en menos de 500ms
- [ ] Reportes generan en menos de 5 segundos
- [ ] Sistema soporta 50 solicitudes simultáneas

### Alertas
- [ ] Alertas de caducidades se envían 60 días antes
- [ ] Alertas de stock mínimo se envían diariamente
- [ ] Alertas de interacciones se muestran inmediatamente

---

## 10. Dependencias

### Internas
- **Sprint 2:** Integración con Registro Clínico Electrónico para registrar administraciones
- **Sprint 1:** Sistema de roles para control de acceso a medicamentos controlados

### Externas
- Laravel Excel (ya instalado en Sprint 6)
- DomPDF (ya instalado en Sprint 6)
- Chart.js (ya instalado en Sprint 6)

---

## 11. Riesgos y Mitigaciones

| Riesgo | Probabilidad | Impacto | Mitigación |
|--------|--------------|---------|------------|
| Datos de interacciones medicamentosas incompletos | Alta | Alto | Iniciar con base de datos médica confiable + actualización periódica |
| Errores en cálculo de dosis máximas | Media | Crítico | Revisión médica de validaciones + tests exhaustivos |
| Stock negativo por concurrencia | Media | Alto | Transacciones DB + validación de disponibilidad en tiempo real |
| Pérdida de trazabilidad de controlados | Baja | Crítico | Logs inmutables + backup diario + auditoría mensual |

---

## 12. Definición de "Hecho"

- [ ] Código implementado según especificaciones
- [ ] Tests unitarios y de integración pasando (>80% cobertura)
- [ ] Validaciones de seguridad probadas
- [ ] Integración con RCE funcionando
- [ ] Alertas automáticas operativas
- [ ] Reportes generando correctamente
- [ ] Documentación médica revisada
- [ ] Code review completado
- [ ] Manual de usuario creado

---

**Siguiente Sprint Sugerido:** Sprint 8 - Módulo de Urgencias y Triaje

**Fecha de Creación:** 2025-11-24
**Responsable:** Claude AI Assistant
**Estado:** Planificado
