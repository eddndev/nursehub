# Plan de Pruebas - Rol: Jefe de Capacitación

**Sistema:** NurseHub
**Rol:** Jefe de Capacitación
**Fecha:** 2025-11-25
**Versión:** 1.0

---

## 1. Descripción del Rol

El **Jefe de Capacitación** es responsable de la gestión del programa de capacitación del personal de enfermería. Crea actividades de capacitación, gestiona inscripciones, registra asistencia, aprueba completaciones y genera certificaciones.

### Credenciales de Prueba
- **Email:** jefe.capacitacion@nursehub.test
- **Password:** password (o el configurado en el seeder)

---

## 2. Módulos Accesibles

| Módulo | Ruta | Acceso |
|--------|------|--------|
| Dashboard | `/dashboard` | ✅ |
| Gestión de Capacitaciones | `/capacitacion/actividades` | ✅* |
| Inscripciones | `/capacitacion/inscripciones/{id}` | ✅* |
| Control de Asistencia | `/capacitacion/asistencia/{id}/{id}` | ✅* |
| Aprobaciones | `/capacitacion/aprobaciones/{id}` | ✅* |
| Reportes de Capacitación | `/capacitacion/reportes` | ✅* |
| Dashboard de Capacitación | `/capacitacion/dashboard` | ✅ |
| Calendario de Capacitaciones | `/capacitacion/calendario` | ✅ |
| Gestión de Áreas | `/admin/areas` | ❌ |
| Gestión de Usuarios | `/admin/users` | ❌ |
| Lista de Pacientes | `/enfermeria/pacientes` | ❌ |
| Gestor de Turnos | `/turnos/gestor` | ❌ |
| Módulo de Farmacia | `/medicamentos/*` | ❌ |

> *Nota: Verificar si el rol `jefe_capacitacion` tiene acceso a las rutas de gestión de capacitación. Actualmente las rutas están configuradas para `coordinador,admin`. Puede requerir actualización de middleware.

---

## 3. Casos de Prueba

### 3.1 Autenticación y Dashboard

| ID | Caso de Prueba | Pasos | Resultado Esperado | ✓/✗ |
|----|----------------|-------|-------------------|-----|
| JC-001 | Login exitoso | 1. Ir a `/login` 2. Ingresar credenciales 3. Click "Iniciar Sesión" | Redirige a `/dashboard` | |
| JC-002 | Ver dashboard | 1. Verificar contenido | Muestra información relevante | |
| JC-003 | Logout | 1. Click menú 2. "Cerrar Sesión" | Sesión cerrada | |

### 3.2 Gestión de Actividades de Capacitación

| ID | Caso de Prueba | Pasos | Resultado Esperado | ✓/✗ |
|----|----------------|-------|-------------------|-----|
| JC-010 | Ver lista de actividades | 1. Ir a `/capacitacion/actividades` | Lista de todas las capacitaciones | |
| JC-011 | Filtrar por tipo | 1. Filtro: Curso/Taller/Plática/Congreso/Beca/Campaña | Solo actividades de ese tipo | |
| JC-012 | Filtrar por estado | 1. Filtro: Programada/En curso/Completada/Cancelada | Actividades filtradas | |
| JC-013 | Buscar actividad | 1. Escribir nombre en búsqueda | Actividad encontrada | |
| JC-014 | Crear curso | 1. Click "Nueva Actividad" 2. Tipo: Curso 3. Nombre, descripción, fechas 4. Guardar | Curso creado | |
| JC-015 | Crear taller | 1. Nueva Actividad 2. Tipo: Taller | Taller creado | |
| JC-016 | Crear plática | 1. Nueva Actividad 2. Tipo: Plática | Plática creada | |
| JC-017 | Crear congreso | 1. Nueva Actividad 2. Tipo: Congreso | Congreso creado | |
| JC-018 | Crear beca | 1. Nueva Actividad 2. Tipo: Beca | Beca creada | |
| JC-019 | Crear campaña | 1. Nueva Actividad 2. Tipo: Campaña | Campaña creada | |
| JC-020 | Definir cupo máximo | 1. En creación, establecer cupo: 30 | Límite de inscripciones | |
| JC-021 | Actividad sin límite de cupo | 1. Cupo: vacío o 0 | Sin límite de inscripciones | |
| JC-022 | Definir requisitos | 1. Escribir requisitos previos | Requisitos guardados | |
| JC-023 | Marcar como obligatoria | 1. Activar "Obligatoria" | Actividad obligatoria para personal | |
| JC-024 | Habilitar autoservicio | 1. Activar "Permitir inscripción voluntaria" | Enfermeros pueden auto-inscribirse | |
| JC-025 | Editar actividad | 1. Click editar 2. Modificar datos 3. Guardar | Cambios guardados | |
| JC-026 | Cancelar actividad | 1. Click "Cancelar Actividad" 2. Confirmar | Estado: Cancelada | |
| JC-027 | Eliminar actividad | 1. Click eliminar 2. Confirmar | Actividad eliminada | |

### 3.3 Gestión de Sesiones

| ID | Caso de Prueba | Pasos | Resultado Esperado | ✓/✗ |
|----|----------------|-------|-------------------|-----|
| JC-030 | Agregar sesión a actividad | 1. Editar actividad 2. Tab "Sesiones" 3. Click "Nueva Sesión" | Formulario de sesión | |
| JC-031 | Crear sesión con fecha/hora | 1. Fecha, hora inicio, hora fin 2. Guardar | Sesión creada | |
| JC-032 | Crear múltiples sesiones | 1. Agregar 5 sesiones diferentes | Todas las sesiones guardadas | |
| JC-033 | Definir ubicación de sesión | 1. En sesión, escribir ubicación/aula | Ubicación guardada | |
| JC-034 | Definir instructor | 1. En sesión, escribir nombre del instructor | Instructor guardado | |
| JC-035 | Editar sesión | 1. Click editar en sesión 2. Modificar 3. Guardar | Cambios guardados | |
| JC-036 | Eliminar sesión | 1. Click eliminar sesión 2. Confirmar | Sesión eliminada | |
| JC-037 | Validar conflicto de horario | 1. Crear sesión que solapa con turno de enfermero | Alerta de conflicto | |

### 3.4 Gestión de Inscripciones

| ID | Caso de Prueba | Pasos | Resultado Esperado | ✓/✗ |
|----|----------------|-------|-------------------|-----|
| JC-040 | Ver inscripciones de actividad | 1. Ir a `/capacitacion/inscripciones/{id}` | Lista de inscritos | |
| JC-041 | Inscribir enfermero manualmente | 1. Click "Inscribir" 2. Buscar enfermero 3. Seleccionar | Inscripción creada | |
| JC-042 | Inscripción masiva | 1. Click "Inscripción Masiva" 2. Seleccionar múltiples enfermeros 3. Inscribir | Todas las inscripciones creadas | |
| JC-043 | Inscribir por área | 1. Seleccionar "Todos del área X" | Todos los enfermeros del área inscritos | |
| JC-044 | Validar cupo máximo | 1. Actividad con cupo 10 2. Intentar inscribir #11 | Error: "Cupo lleno" | |
| JC-045 | Ver estado de inscripciones | 1. En lista | Estados visibles: Pendiente/Aprobada/Completada/Rechazada | |
| JC-046 | Cancelar inscripción | 1. Seleccionar inscripción 2. Click "Cancelar" | Inscripción cancelada | |
| JC-047 | Ver inscripciones por autoservicio | 1. Filtrar por tipo "Voluntaria" | Solo inscripciones autoservicio | |

### 3.5 Control de Asistencia

| ID | Caso de Prueba | Pasos | Resultado Esperado | ✓/✗ |
|----|----------------|-------|-------------------|-----|
| JC-050 | Acceder a control de asistencia | 1. Ir a `/capacitacion/asistencia/{actividadId}/{sesionId}` | Grid de asistencia | |
| JC-051 | Marcar asistencia individual | 1. Checkbox de participante | Asistencia marcada | |
| JC-052 | Marcar todos presentes | 1. Click "Marcar Todos Presentes" | Todos con asistencia | |
| JC-053 | Marcar todos ausentes | 1. Click "Marcar Todos Ausentes" | Todos sin asistencia | |
| JC-054 | Porcentaje calculado automáticamente | 1. Marcar asistencias 2. Ver porcentaje | % calculado correctamente | |
| JC-055 | Asistencia de múltiples sesiones | 1. Ir a diferentes sesiones 2. Marcar asistencias | Cada sesión tiene su registro | |
| JC-056 | Ver resumen de asistencia por participante | 1. Ver columna de resumen | Sesiones asistidas / Total sesiones | |

### 3.6 Evaluaciones (Si Aplica)

| ID | Caso de Prueba | Pasos | Resultado Esperado | ✓/✗ |
|----|----------------|-------|-------------------|-----|
| JC-060 | Actividad con evaluación | 1. Crear actividad 2. Marcar "Requiere Evaluación" | Campo de calificación habilitado | |
| JC-061 | Definir calificación mínima | 1. Establecer mínimo: 70 | Mínimo guardado | |
| JC-062 | Registrar calificación | 1. En inscripción, agregar calificación | Calificación guardada | |
| JC-063 | Validar calificación mínima | 1. Calificación < mínimo 2. Intentar aprobar | Advertencia o bloqueo | |
| JC-064 | Agregar retroalimentación | 1. Escribir comentarios de evaluación | Retroalimentación guardada | |

### 3.7 Aprobaciones y Certificaciones

| ID | Caso de Prueba | Pasos | Resultado Esperado | ✓/✗ |
|----|----------------|-------|-------------------|-----|
| JC-070 | Ver inscripciones para aprobar | 1. Ir a `/capacitacion/aprobaciones/{id}` | Lista de inscripciones completadas | |
| JC-071 | Aprobar inscripción individual | 1. Seleccionar inscripción 2. Click "Aprobar" | Certificación generada automáticamente | |
| JC-072 | Aprobar en lote | 1. Seleccionar múltiples 2. Click "Aprobar Seleccionados" | Todas aprobadas con certificaciones | |
| JC-073 | Rechazar inscripción | 1. Seleccionar inscripción 2. Click "Rechazar" 3. Escribir motivo | Inscripción rechazada | |
| JC-074 | Verificar certificación generada | 1. Después de aprobar 2. Ver certificaciones | Certificación con folio único CERT-YYYY-NNNNN | |
| JC-075 | Ver PDF de certificación | 1. Click en certificación 2. "Ver PDF" | PDF abre en navegador | |
| JC-076 | Descargar certificación | 1. Click "Descargar" | PDF descargado | |

### 3.8 Calendario de Capacitaciones

| ID | Caso de Prueba | Pasos | Resultado Esperado | ✓/✗ |
|----|----------------|-------|-------------------|-----|
| JC-080 | Ver calendario mensual | 1. Ir a `/capacitacion/calendario` | Vista de calendario con sesiones | |
| JC-081 | Navegar meses | 1. Click anterior/siguiente mes | Cambia el mes visualizado | |
| JC-082 | Ver sesiones en día | 1. Click en día con sesiones | Lista de sesiones de ese día | |
| JC-083 | Acceso rápido a actividad | 1. Click en sesión del calendario | Abre detalle de la actividad | |
| JC-084 | Diferenciar por tipo | 1. Ver colores de sesiones | Diferentes colores por tipo de actividad | |

### 3.9 Reportes de Capacitación

| ID | Caso de Prueba | Pasos | Resultado Esperado | ✓/✗ |
|----|----------------|-------|-------------------|-----|
| JC-090 | Acceder a reportes | 1. Ir a `/capacitacion/reportes` | Interfaz de reportes | |
| JC-091 | Reporte de participación | 1. Tipo: "Participación" | Estadísticas de asistencia | |
| JC-092 | Reporte por área | 1. Tipo: "Por Área" | Capacitaciones por área | |
| JC-093 | Reporte de certificaciones | 1. Tipo: "Certificaciones" | Lista de certificaciones emitidas | |
| JC-094 | Reporte de cumplimiento | 1. Tipo: "Cumplimiento" | % de personal capacitado | |
| JC-095 | Filtrar por período | 1. Establecer fechas inicio/fin | Datos del período | |
| JC-096 | Filtrar por área | 1. Seleccionar área específica | Datos de esa área | |
| JC-097 | Ver gráficos | 1. Sección de gráficos | Visualizaciones correctas | |
| JC-098 | Exportar a Excel | 1. Click "Exportar Excel" | Archivo .xlsx descargado | |
| JC-099 | Exportar a PDF | 1. Click "Exportar PDF" | Archivo .pdf descargado | |

### 3.10 Dashboard Personal de Capacitación

| ID | Caso de Prueba | Pasos | Resultado Esperado | ✓/✗ |
|----|----------------|-------|-------------------|-----|
| JC-100 | Ver mi dashboard | 1. Ir a `/capacitacion/dashboard` | Mi portal de capacitación | |
| JC-101 | Ver mis certificaciones | 1. Tab "Certificaciones" | Mis certificaciones | |
| JC-102 | Inscribirse a capacitación | 1. Capacitación con autoservicio 2. "Inscribirse" | Inscripción registrada | |

---

## 4. Pruebas de Seguridad y Restricciones

| ID | Caso de Prueba | Pasos | Resultado Esperado | ✓/✗ |
|----|----------------|-------|-------------------|-----|
| JC-S01 | No puede acceder a gestión de usuarios | 1. Ir a `/admin/users` | Acceso denegado (403) | |
| JC-S02 | No puede acceder a gestión de áreas | 1. Ir a `/admin/areas` | Acceso denegado | |
| JC-S03 | No puede acceder a lista de pacientes | 1. Ir a `/enfermeria/pacientes` | Acceso denegado | |
| JC-S04 | No puede acceder a turnos | 1. Ir a `/turnos/gestor` | Acceso denegado | |
| JC-S05 | No puede acceder a farmacia | 1. Ir a `/medicamentos/catalogo` | Acceso denegado | |

---

## 5. Pruebas de Integridad de Datos

| ID | Caso de Prueba | Pasos | Resultado Esperado | ✓/✗ |
|----|----------------|-------|-------------------|-----|
| JC-I01 | Folios de certificación únicos | 1. Aprobar múltiples inscripciones | Cada certificación tiene folio único | |
| JC-I02 | No duplicar inscripciones | 1. Intentar inscribir mismo enfermero dos veces | Error: "Ya está inscrito" | |
| JC-I03 | Porcentaje asistencia correcto | 1. Marcar 3 de 5 sesiones | Porcentaje: 60% | |
| JC-I04 | Historial de certificaciones | 1. Ver certificaciones de enfermero | Todas las certificaciones históricas | |

---

## 6. Notas y Observaciones

| Fecha | Tester | Observación |
|-------|--------|-------------|
| | | |
| | | Verificar que el rol jefe_capacitacion tenga acceso a rutas de gestión |

---

## 7. Resumen de Ejecución

| Total Casos | Pasados | Fallidos | Bloqueados | No Ejecutados |
|-------------|---------|----------|------------|---------------|
| | | | | |

**Firma del Tester:** _______________________
**Fecha de Ejecución:** _______________________
