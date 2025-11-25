# Plan de Pruebas - Rol: Jefe de Piso/Área

**Sistema:** NurseHub
**Rol:** Jefe de Piso
**Fecha:** 2025-11-25
**Versión:** 1.0

---

## 1. Descripción del Rol

El **Jefe de Piso/Área** es responsable de la supervisión directa del personal de enfermería en su área asignada. Gestiona turnos, asignaciones de pacientes, y supervisa la atención de enfermería en su piso.

### Credenciales de Prueba
- **Email:** jefe.piso@nursehub.test
- **Password:** password (o el configurado en el seeder)

---

## 2. Módulos Accesibles

| Módulo | Ruta | Acceso |
|--------|------|--------|
| Dashboard | `/dashboard` | ✅ |
| Gestión de Áreas | `/admin/areas` | ❌ |
| Gestión de Pisos | `/admin/pisos` | ❌ |
| Gestión de Cuartos | `/admin/cuartos` | ❌ |
| Gestión de Camas | `/admin/camas` | ❌ |
| Gestión de Usuarios | `/admin/users` | ❌ |
| Mapa del Hospital | `/hospital-map` | ❌ |
| Admisión de Pacientes | `/urgencias/admision` | ✅ |
| Lista de Pacientes | `/enfermeria/pacientes` | ✅ |
| Expediente de Paciente | `/enfermeria/paciente/{id}` | ✅ |
| Mis Asignaciones | `/enfermeria/mis-asignaciones` | ✅ |
| Gestor de Turnos | `/turnos/gestor` | ✅ |
| Relevo de Turno | `/turnos/relevo` | ✅ |
| Gestión de Capacitaciones | `/capacitacion/actividades` | ❌ |
| Dashboard de Capacitación | `/capacitacion/dashboard` | ✅ |
| Calendario de Capacitaciones | `/capacitacion/calendario` | ✅ |
| Solicitudes de Medicamentos | `/medicamentos/solicitudes` | ✅ |
| Administración de Medicamentos | `/medicamentos/administrar` | ✅ |

---

## 3. Casos de Prueba

### 3.1 Autenticación y Dashboard

| ID | Caso de Prueba | Pasos | Resultado Esperado | ✓/✗ |
|----|----------------|-------|-------------------|-----|
| JP-001 | Login exitoso | 1. Ir a `/login` 2. Ingresar credenciales 3. Click "Iniciar Sesión" | Redirige a `/dashboard` | |
| JP-002 | Ver dashboard | 1. Verificar contenido | Muestra estadísticas relevantes a su área | |
| JP-003 | Verificar restricción a admin | 1. Intentar acceder a `/admin/users` | Acceso denegado (403 o redirección) | |

### 3.2 Gestión de Turnos (Función Principal)

| ID | Caso de Prueba | Pasos | Resultado Esperado | ✓/✗ |
|----|----------------|-------|-------------------|-----|
| JP-010 | Ver gestor de turnos | 1. Ir a `/turnos/gestor` | Muestra panel de gestión de turnos | |
| JP-011 | Crear turno matutino | 1. Seleccionar área 2. Fecha: hoy 3. Tipo: Matutino 4. Click "Crear Turno" | Turno creado correctamente | |
| JP-012 | Crear turno vespertino | 1. Tipo: Vespertino 2. Crear | Turno vespertino creado | |
| JP-013 | Crear turno nocturno | 1. Tipo: Nocturno 2. Crear | Turno nocturno creado | |
| JP-014 | Ver enfermeros disponibles | 1. En turno activo | Lista de enfermeros para asignar | |
| JP-015 | Asignar paciente a enfermero | 1. Seleccionar paciente 2. Seleccionar enfermero 3. Asignar | Asignación creada, aparece en lista del enfermero | |
| JP-016 | Ver carga de trabajo por enfermero | 1. En panel de turno | Muestra número de pacientes por enfermero | |
| JP-017 | Reasignar paciente | 1. Click en asignación existente 2. Cambiar enfermero 3. Confirmar | Paciente reasignado | |
| JP-018 | Desasignar paciente | 1. Click en asignación 2. Remover asignación | Paciente sin enfermero asignado | |
| JP-019 | Cerrar turno | 1. Click "Cerrar Turno" 2. Confirmar | Turno cerrado, asignaciones finalizadas | |
| JP-020 | Intentar crear turno duplicado | 1. Crear turno con misma área/fecha/tipo | Error: "Ya existe un turno para esta combinación" | |

### 3.3 Relevo de Turno

| ID | Caso de Prueba | Pasos | Resultado Esperado | ✓/✗ |
|----|----------------|-------|-------------------|-----|
| JP-030 | Acceder a relevo de turno | 1. Ir a `/turnos/relevo` | Muestra interfaz de relevo | |
| JP-031 | Ver información del turno anterior | 1. En relevo | Muestra pacientes y notas del turno saliente | |
| JP-032 | Escribir notas de relevo | 1. Escribir observaciones importantes 2. Guardar | Notas guardadas para el siguiente turno | |
| JP-033 | Ver historial de relevos | 1. En relevo, ver historial | Lista de relevos anteriores con notas | |

### 3.4 Gestión de Pacientes del Área

| ID | Caso de Prueba | Pasos | Resultado Esperado | ✓/✗ |
|----|----------------|-------|-------------------|-----|
| JP-040 | Ver lista de pacientes | 1. Ir a `/enfermeria/pacientes` | Lista de pacientes (puede ver todos o solo su área) | |
| JP-041 | Filtrar por TRIAGE rojo | 1. Filtro: TRIAGE = Rojo | Solo pacientes críticos | |
| JP-042 | Filtrar por TRIAGE amarillo | 1. Filtro: TRIAGE = Amarillo | Solo pacientes urgentes | |
| JP-043 | Ordenar por prioridad | 1. Verificar orden de lista | Pacientes TRIAGE rojo primero | |
| JP-044 | Buscar paciente por nombre | 1. Escribir nombre en búsqueda | Encuentra paciente | |
| JP-045 | Buscar por código QR | 1. Escanear o escribir código QR | Encuentra paciente exacto | |
| JP-046 | Ver expediente de paciente | 1. Click en paciente | Expediente completo | |
| JP-047 | Ver signos vitales actuales | 1. En expediente, sección signos vitales | Últimos valores registrados | |
| JP-048 | Ver historial de signos vitales | 1. En expediente, ver historial | Timeline de registros | |
| JP-049 | Ver gráficos de tendencias | 1. En expediente, tab gráficos | Gráficos de evolución | |

### 3.5 Registro Clínico

| ID | Caso de Prueba | Pasos | Resultado Esperado | ✓/✗ |
|----|----------------|-------|-------------------|-----|
| JP-050 | Registrar signos vitales | 1. En expediente 2. Click "Registrar Signos" 3. Llenar valores 4. Guardar | Signos registrados, TRIAGE recalculado | |
| JP-051 | Registrar con valores críticos | 1. Ingresar PA: 200/120, FC: 150, SpO2: 85 2. Guardar | TRIAGE cambia a ROJO | |
| JP-052 | Override manual de TRIAGE | 1. Registrar signos 2. Cambiar TRIAGE manualmente | TRIAGE modificado con indicador de override | |
| JP-053 | Agregar observaciones | 1. En registro, escribir observaciones 2. Guardar | Observaciones guardadas en historial | |
| JP-054 | Registrar balance de líquidos | 1. En expediente, tab líquidos 2. Registrar ingreso/egreso | Balance calculado automáticamente | |
| JP-055 | Aplicar escala de valoración | 1. Tab escalas 2. Aplicar Glasgow/EVA/Braden | Puntaje calculado con interpretación | |

### 3.6 Admisión de Pacientes

| ID | Caso de Prueba | Pasos | Resultado Esperado | ✓/✗ |
|----|----------------|-------|-------------------|-----|
| JP-060 | Admitir nuevo paciente | 1. Ir a `/urgencias/admision` 2. Llenar datos personales 3. Guardar | Paciente creado con código QR | |
| JP-061 | Admitir con signos vitales | 1. En admisión 2. Registrar signos vitales iniciales | TRIAGE calculado automáticamente | |
| JP-062 | Asignar cama en admisión | 1. Seleccionar cama disponible 2. Guardar | Paciente asignado a cama, cama marcada como ocupada | |
| JP-063 | Registrar alergias | 1. En admisión, campo alergias 2. Escribir alergias | Alergias guardadas y visibles en expediente | |
| JP-064 | Registrar antecedentes | 1. Campo antecedentes 2. Escribir | Antecedentes guardados | |

### 3.7 Capacitación (Vista Personal)

| ID | Caso de Prueba | Pasos | Resultado Esperado | ✓/✗ |
|----|----------------|-------|-------------------|-----|
| JP-070 | Ver dashboard de capacitación | 1. Ir a `/capacitacion/dashboard` | Mis capacitaciones y certificaciones | |
| JP-071 | Ver capacitaciones disponibles | 1. En dashboard, tab "Disponibles" | Lista de capacitaciones abiertas | |
| JP-072 | Inscribirse a capacitación | 1. Click en capacitación 2. "Inscribirse" | Inscripción registrada (pendiente aprobación) | |
| JP-073 | Ver mis inscripciones | 1. Tab "Mis Inscripciones" | Lista de inscripciones con estado | |
| JP-074 | Ver mis certificaciones | 1. Tab "Certificaciones" | Certificaciones obtenidas | |
| JP-075 | Descargar certificación PDF | 1. Click en certificación 2. "Descargar PDF" | PDF descargado | |
| JP-076 | Ver calendario de capacitaciones | 1. Ir a `/capacitacion/calendario` | Vista mensual de sesiones | |

### 3.8 Solicitudes de Medicamentos

| ID | Caso de Prueba | Pasos | Resultado Esperado | ✓/✗ |
|----|----------------|-------|-------------------|-----|
| JP-080 | Ver solicitudes de su área | 1. Ir a `/medicamentos/solicitudes` | Lista de solicitudes | |
| JP-081 | Crear nueva solicitud | 1. Click "Nueva Solicitud" 2. Seleccionar paciente 3. Agregar medicamentos 4. Enviar | Solicitud creada con estado "Pendiente" | |
| JP-082 | Agregar múltiples medicamentos | 1. En solicitud 2. Agregar varios medicamentos | Todos los medicamentos en la solicitud | |
| JP-083 | Establecer prioridad STAT | 1. En solicitud 2. Prioridad: STAT | Solicitud marcada como inmediata | |
| JP-084 | Ver estado de solicitud | 1. Buscar solicitud | Estado visible (pendiente/aprobada/despachada) | |
| JP-085 | Cancelar solicitud pendiente | 1. Solicitud pendiente 2. Click "Cancelar" | Solicitud cancelada | |

### 3.9 Administración de Medicamentos

| ID | Caso de Prueba | Pasos | Resultado Esperado | ✓/✗ |
|----|----------------|-------|-------------------|-----|
| JP-090 | Acceder a administración | 1. Ir a `/medicamentos/administrar` | Interfaz de administración | |
| JP-091 | Seleccionar paciente | 1. Buscar paciente 2. Seleccionar | Muestra medicamentos pendientes del paciente | |
| JP-092 | Ver alertas de alergias | 1. Paciente con alergia registrada | Alerta visible de alergia | |
| JP-093 | Ver alertas de interacciones | 1. Medicamento con interacción conocida | Alerta de interacción mostrada | |
| JP-094 | Registrar administración | 1. Seleccionar medicamento 2. Confirmar administración | Registro guardado | |
| JP-095 | Administración bloqueada por alergia | 1. Intentar administrar medicamento alergénico | Sistema bloquea administración | |
| JP-096 | Registrar reacción adversa | 1. En historial de administración 2. Marcar reacción 3. Describir | Reacción registrada en expediente | |

### 3.10 Mis Asignaciones

| ID | Caso de Prueba | Pasos | Resultado Esperado | ✓/✗ |
|----|----------------|-------|-------------------|-----|
| JP-100 | Ver mis asignaciones actuales | 1. Ir a `/enfermeria/mis-asignaciones` | Lista de pacientes asignados | |
| JP-101 | Acceso rápido a expediente | 1. Click en paciente de mis asignaciones | Abre expediente directamente | |
| JP-102 | Ver TRIAGE de asignaciones | 1. En lista | Badges de color por TRIAGE | |

---

## 4. Pruebas de Seguridad y Restricciones

| ID | Caso de Prueba | Pasos | Resultado Esperado | ✓/✗ |
|----|----------------|-------|-------------------|-----|
| JP-S01 | No puede acceder a gestión de usuarios | 1. Ir a `/admin/users` | Acceso denegado | |
| JP-S02 | No puede acceder a gestión de áreas | 1. Ir a `/admin/areas` | Acceso denegado | |
| JP-S03 | No puede crear capacitaciones | 1. Ir a `/capacitacion/actividades` | Acceso denegado | |
| JP-S04 | No puede acceder a catálogo de medicamentos | 1. Ir a `/medicamentos/catalogo` | Acceso denegado | |
| JP-S05 | No puede despachar medicamentos | 1. Ir a `/medicamentos/despacho` | Acceso denegado | |

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
