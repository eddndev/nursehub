# Plan de Pruebas - Rol: Coordinador General de Enfermería

**Sistema:** NurseHub
**Rol:** Coordinador
**Fecha:** 2025-11-25
**Versión:** 1.0

---

## 1. Descripción del Rol

El **Coordinador General de Enfermería** es responsable de la supervisión general del personal de enfermería, gestión de capacitaciones, coordinación entre áreas, y tiene acceso administrativo similar al admin pero enfocado en la operación de enfermería.

### Credenciales de Prueba
- **Email:** coordinador@nursehub.test
- **Password:** password (o el configurado en el seeder)

---

## 2. Módulos Accesibles

| Módulo | Ruta | Acceso |
|--------|------|--------|
| Dashboard | `/dashboard` | ✅ |
| Gestión de Áreas | `/admin/areas` | ✅ |
| Gestión de Pisos | `/admin/pisos` | ✅ |
| Gestión de Cuartos | `/admin/cuartos` | ✅ |
| Gestión de Camas | `/admin/camas` | ✅ |
| Gestión de Usuarios | `/admin/users` | ✅ |
| Mapa del Hospital | `/hospital-map` | ✅ |
| Admisión de Pacientes | `/urgencias/admision` | ✅ |
| Lista de Pacientes | `/enfermeria/pacientes` | ✅ |
| Expediente de Paciente | `/enfermeria/paciente/{id}` | ✅ |
| Mis Asignaciones | `/enfermeria/mis-asignaciones` | ✅ |
| Gestor de Turnos | `/turnos/gestor` | ✅ |
| Relevo de Turno | `/turnos/relevo` | ✅ |
| Gestión de Capacitaciones | `/capacitacion/actividades` | ✅ |
| Inscripciones | `/capacitacion/inscripciones/{id}` | ✅ |
| Control de Asistencia | `/capacitacion/asistencia/{id}/{id}` | ✅ |
| Aprobaciones | `/capacitacion/aprobaciones/{id}` | ✅ |
| Reportes de Capacitación | `/capacitacion/reportes` | ✅ |
| Dashboard de Capacitación | `/capacitacion/dashboard` | ✅ |
| Calendario de Capacitaciones | `/capacitacion/calendario` | ✅ |
| Catálogo de Medicamentos | `/medicamentos/catalogo` | ✅ |
| Inventario de Medicamentos | `/medicamentos/inventario` | ✅ |
| Despacho de Farmacia | `/medicamentos/despacho` | ✅ |
| Reportes de Farmacia | `/medicamentos/reportes` | ✅ |
| Solicitudes de Medicamentos | `/medicamentos/solicitudes` | ✅ |
| Administración de Medicamentos | `/medicamentos/administrar` | ✅ |

---

## 3. Casos de Prueba

### 3.1 Autenticación y Dashboard

| ID | Caso de Prueba | Pasos | Resultado Esperado | ✓/✗ |
|----|----------------|-------|-------------------|-----|
| C-001 | Login exitoso | 1. Ir a `/login` 2. Ingresar credenciales de coordinador 3. Click en "Iniciar Sesión" | Redirige a `/dashboard` | |
| C-002 | Ver dashboard con estadísticas | 1. Verificar dashboard | Muestra: total usuarios, pacientes activos, camas disponibles, ocupación por área | |
| C-003 | Ver distribución de personal | 1. En dashboard, ver sección de personal | Muestra enfermeros por turno y área | |

### 3.2 Gestión de Infraestructura Hospitalaria

| ID | Caso de Prueba | Pasos | Resultado Esperado | ✓/✗ |
|----|----------------|-------|-------------------|-----|
| C-010 | Crear nueva área | 1. Ir a `/admin/areas` 2. Crear área "Oncología" | Área creada correctamente | |
| C-011 | Crear piso en área | 1. Ir a `/admin/pisos` 2. Seleccionar área 3. Crear piso | Piso vinculado al área | |
| C-012 | Configurar cuartos | 1. Ir a `/admin/cuartos` 2. Crear cuartos para el piso | Cuartos creados | |
| C-013 | Agregar camas | 1. Ir a `/admin/camas` 2. Crear camas en cuartos | Camas creadas con estado "Libre" | |
| C-014 | Ver mapa completo del hospital | 1. Ir a `/hospital-map` | Visualiza toda la estructura jerárquica | |

### 3.3 Gestión de Personal

| ID | Caso de Prueba | Pasos | Resultado Esperado | ✓/✗ |
|----|----------------|-------|-------------------|-----|
| C-020 | Ver lista de todo el personal | 1. Ir a `/admin/users` | Lista completa de usuarios | |
| C-021 | Crear nuevo enfermero | 1. Click "Nuevo Usuario" 2. Rol: Enfermero 3. Llenar datos 4. Guardar | Usuario y perfil de enfermero creados | |
| C-022 | Crear jefe de piso | 1. Click "Nuevo Usuario" 2. Rol: Jefe de Piso 3. Guardar | Jefe de piso creado | |
| C-023 | Editar datos de enfermero | 1. Buscar enfermero 2. Editar 3. Modificar cédula profesional 4. Guardar | Datos actualizados | |
| C-024 | Desactivar usuario | 1. Buscar usuario 2. Toggle "Activo" a desactivado | Usuario no puede iniciar sesión | |
| C-025 | Filtrar por rol | 1. Filtro: "Enfermero" | Solo muestra enfermeros | |
| C-026 | Filtrar por estado activo/inactivo | 1. Filtro: "Inactivos" | Solo muestra usuarios inactivos | |

### 3.4 Supervisión de Pacientes

| ID | Caso de Prueba | Pasos | Resultado Esperado | ✓/✗ |
|----|----------------|-------|-------------------|-----|
| C-030 | Ver todos los pacientes | 1. Ir a `/enfermeria/pacientes` | Lista de todos los pacientes del hospital | |
| C-031 | Filtrar pacientes por TRIAGE | 1. Filtro: "Rojo" | Solo pacientes críticos | |
| C-032 | Filtrar por área | 1. Seleccionar área específica | Pacientes de esa área | |
| C-033 | Buscar paciente por nombre | 1. Escribir nombre en búsqueda | Encuentra paciente | |
| C-034 | Buscar por código QR | 1. Escribir código QR | Encuentra paciente específico | |
| C-035 | Ver expediente completo | 1. Click en paciente | Expediente con: datos, signos vitales, historial, gráficos | |
| C-036 | Ver gráficos de tendencias | 1. En expediente, ir a gráficos | Gráficos de signos vitales en el tiempo | |

### 3.5 Gestión de Turnos (Supervisión)

| ID | Caso de Prueba | Pasos | Resultado Esperado | ✓/✗ |
|----|----------------|-------|-------------------|-----|
| C-040 | Ver turnos de todas las áreas | 1. Ir a `/turnos/gestor` | Lista de turnos de todas las áreas | |
| C-041 | Crear turno para cualquier área | 1. Seleccionar área 2. Crear turno | Turno creado | |
| C-042 | Ver asignaciones de un turno | 1. Click en turno | Muestra enfermeros y sus pacientes asignados | |
| C-043 | Reasignar paciente entre enfermeros | 1. En turno activo 2. Mover paciente a otro enfermero | Reasignación exitosa | |
| C-044 | Cerrar turno de cualquier área | 1. Seleccionar turno activo 2. Cerrar | Turno cerrado, historial guardado | |
| C-045 | Ver historial de relevos | 1. Ir a `/turnos/relevo` | Historial de todos los relevos | |

### 3.6 Gestión Completa de Capacitaciones

| ID | Caso de Prueba | Pasos | Resultado Esperado | ✓/✗ |
|----|----------------|-------|-------------------|-----|
| C-050 | Crear curso de capacitación | 1. Ir a `/capacitacion/actividades` 2. Click "Nueva Actividad" 3. Tipo: Curso 4. Llenar datos 5. Guardar | Curso creado | |
| C-051 | Crear taller | 1. Nueva Actividad 2. Tipo: Taller 3. Guardar | Taller creado | |
| C-052 | Agregar sesiones | 1. Editar actividad 2. Tab "Sesiones" 3. Agregar fechas/horarios | Sesiones programadas | |
| C-053 | Inscribir enfermeros manualmente | 1. Ir a inscripciones de actividad 2. Seleccionar enfermeros 3. Inscribir | Inscripciones creadas | |
| C-054 | Inscripción masiva | 1. Seleccionar múltiples enfermeros 2. Inscribir todos | Todas las inscripciones creadas | |
| C-055 | Cancelar inscripción | 1. Buscar inscripción 2. Cancelar | Inscripción cancelada | |
| C-056 | Registrar asistencia | 1. Ir a control de asistencia 2. Marcar presentes/ausentes | Porcentaje de asistencia calculado | |
| C-057 | Aprobar inscripción | 1. Ir a aprobaciones 2. Aprobar inscripción completada | Certificación generada automáticamente | |
| C-058 | Aprobar en lote | 1. Seleccionar múltiples inscripciones 2. Aprobar todas | Todas aprobadas, certificaciones generadas | |
| C-059 | Rechazar inscripción | 1. Seleccionar inscripción 2. Rechazar con motivo | Inscripción rechazada | |
| C-060 | Ver calendario de capacitaciones | 1. Ir a `/capacitacion/calendario` | Vista mensual de todas las sesiones | |

### 3.7 Reportes de Capacitación

| ID | Caso de Prueba | Pasos | Resultado Esperado | ✓/✗ |
|----|----------------|-------|-------------------|-----|
| C-070 | Reporte de participación | 1. Ir a `/capacitacion/reportes` 2. Tipo: Participación | Estadísticas de asistencia por actividad | |
| C-071 | Reporte por área | 1. Tipo: Por área | Capacitaciones completadas por área | |
| C-072 | Reporte de certificaciones | 1. Tipo: Certificaciones | Lista de certificaciones emitidas | |
| C-073 | Exportar a Excel | 1. Generar reporte 2. Click "Excel" | Archivo .xlsx descargado | |
| C-074 | Exportar a PDF | 1. Generar reporte 2. Click "PDF" | Archivo .pdf descargado | |

### 3.8 Gestión de Farmacia (Supervisión)

| ID | Caso de Prueba | Pasos | Resultado Esperado | ✓/✗ |
|----|----------------|-------|-------------------|-----|
| C-080 | Ver catálogo de medicamentos | 1. Ir a `/medicamentos/catalogo` | Lista completa de medicamentos | |
| C-081 | Agregar medicamento al catálogo | 1. Click "Nuevo" 2. Llenar datos 3. Guardar | Medicamento agregado | |
| C-082 | Ver inventario | 1. Ir a `/medicamentos/inventario` | Stock actual por medicamento y lote | |
| C-083 | Ver alertas de stock | 1. En inventario, ver alertas | Medicamentos con stock bajo | |
| C-084 | Ver alertas de caducidad | 1. Ver alertas | Medicamentos próximos a caducar | |
| C-085 | Aprobar despacho de controlados | 1. Ir a `/medicamentos/despacho` 2. Ver solicitud con controlados 3. Ser segundo verificador | Verificación completada | |
| C-086 | Generar reporte de consumo | 1. Ir a `/medicamentos/reportes` 2. Tipo: Consumo | Reporte generado | |
| C-087 | Generar reporte de controlados | 1. Tipo: Controlados | Auditoría de medicamentos controlados | |
| C-088 | Ver costos por área | 1. Tipo: Costos por área | Desglose de costos de medicamentos | |

### 3.9 Solicitudes de Medicamentos

| ID | Caso de Prueba | Pasos | Resultado Esperado | ✓/✗ |
|----|----------------|-------|-------------------|-----|
| C-090 | Ver todas las solicitudes | 1. Ir a `/medicamentos/solicitudes` | Lista de solicitudes de todas las áreas | |
| C-091 | Crear solicitud | 1. Click "Nueva Solicitud" 2. Seleccionar paciente 3. Agregar medicamentos 4. Enviar | Solicitud creada | |
| C-092 | Ver estado de solicitud | 1. Buscar solicitud | Muestra estado: pendiente, aprobada, despachada, rechazada | |

---

## 4. Pruebas de Seguridad y Permisos

| ID | Caso de Prueba | Pasos | Resultado Esperado | ✓/✗ |
|----|----------------|-------|-------------------|-----|
| C-S01 | No puede crear usuarios Admin | 1. Crear usuario 2. Intentar seleccionar rol "Admin" | Rol Admin no disponible o error | |
| C-S02 | Acceso a todas las áreas | 1. Navegar a diferentes áreas | Puede ver y gestionar cualquier área | |
| C-S03 | Puede gestionar cualquier turno | 1. Ir a turnos de otra área | Puede crear/cerrar turnos | |

---

## 5. Notas y Observaciones

| Fecha | Tester | Observación |
|-------|--------|-------------|
| | | |

---

## 6. Resumen de Ejecución

| Total Casos | Pasados | Fallidos | Bloqueados | No Ejecutados |
|-------------|---------|----------|------------|---------------|
| | | | | |

**Firma del Tester:** _______________________
**Fecha de Ejecución:** _______________________
