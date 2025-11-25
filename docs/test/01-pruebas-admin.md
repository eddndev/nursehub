# Plan de Pruebas - Rol: Administrador (Admin)

**Sistema:** NurseHub
**Rol:** Administrador
**Fecha:** 2025-11-25
**Versión:** 1.0

---

## 1. Descripción del Rol

El **Administrador** es el rol con mayor nivel de privilegios en el sistema. Tiene acceso completo a todas las funcionalidades, incluyendo la gestión de usuarios, configuración del hospital, y supervisión de todos los módulos.

### Credenciales de Prueba
- **Email:** admin@nursehub.test
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

### 3.1 Autenticación y Acceso

| ID | Caso de Prueba | Pasos | Resultado Esperado | ✓/✗ |
|----|----------------|-------|-------------------|-----|
| A-001 | Login exitoso | 1. Ir a `/login` 2. Ingresar credenciales de admin 3. Click en "Iniciar Sesión" | Redirige a `/dashboard` | |
| A-002 | Acceso a Dashboard | 1. Iniciar sesión como admin 2. Verificar contenido del dashboard | Muestra estadísticas: usuarios, pacientes, camas, áreas | |
| A-003 | Logout | 1. Click en menú de usuario 2. Click en "Cerrar Sesión" | Redirige a página de login | |

### 3.2 Gestión de Áreas

| ID | Caso de Prueba | Pasos | Resultado Esperado | ✓/✗ |
|----|----------------|-------|-------------------|-----|
| A-010 | Ver lista de áreas | 1. Ir a `/admin/areas` | Muestra tabla con todas las áreas | |
| A-011 | Crear nueva área | 1. Click "Nueva Área" 2. Llenar nombre y descripción 3. Click "Guardar" | Área creada, aparece en la lista | |
| A-012 | Editar área | 1. Click icono editar en un área 2. Modificar nombre 3. Click "Actualizar" | Área actualizada correctamente | |
| A-013 | Eliminar área | 1. Click icono eliminar en un área 2. Confirmar eliminación | Área eliminada de la lista | |
| A-014 | Buscar área | 1. Escribir en campo de búsqueda 2. Verificar filtrado | Solo muestra áreas que coinciden | |

### 3.3 Gestión de Pisos

| ID | Caso de Prueba | Pasos | Resultado Esperado | ✓/✗ |
|----|----------------|-------|-------------------|-----|
| A-020 | Ver lista de pisos | 1. Ir a `/admin/pisos` | Muestra tabla con todos los pisos | |
| A-021 | Crear nuevo piso | 1. Click "Nuevo Piso" 2. Seleccionar área 3. Llenar número y nombre 4. Click "Guardar" | Piso creado, aparece en la lista | |
| A-022 | Editar piso | 1. Click icono editar 2. Modificar datos 3. Click "Actualizar" | Piso actualizado correctamente | |
| A-023 | Eliminar piso | 1. Click icono eliminar 2. Confirmar | Piso eliminado | |
| A-024 | Filtrar por área | 1. Seleccionar área en filtro | Solo muestra pisos de esa área | |

### 3.4 Gestión de Cuartos

| ID | Caso de Prueba | Pasos | Resultado Esperado | ✓/✗ |
|----|----------------|-------|-------------------|-----|
| A-030 | Ver lista de cuartos | 1. Ir a `/admin/cuartos` | Muestra tabla con todos los cuartos | |
| A-031 | Crear nuevo cuarto | 1. Click "Nuevo Cuarto" 2. Seleccionar piso 3. Llenar número 4. Click "Guardar" | Cuarto creado correctamente | |
| A-032 | Editar cuarto | 1. Click editar 2. Modificar datos 3. Guardar | Cuarto actualizado | |
| A-033 | Eliminar cuarto | 1. Click eliminar 2. Confirmar | Cuarto eliminado | |

### 3.5 Gestión de Camas

| ID | Caso de Prueba | Pasos | Resultado Esperado | ✓/✗ |
|----|----------------|-------|-------------------|-----|
| A-040 | Ver lista de camas | 1. Ir a `/admin/camas` | Muestra tabla con todas las camas | |
| A-041 | Crear nueva cama | 1. Click "Nueva Cama" 2. Seleccionar cuarto 3. Llenar número 4. Click "Guardar" | Cama creada | |
| A-042 | Cambiar estado de cama | 1. Click en estado de cama 2. Seleccionar nuevo estado | Estado actualizado (Libre/Ocupada/En limpieza/En mantenimiento) | |
| A-043 | Filtrar camas por estado | 1. Seleccionar filtro de estado | Solo muestra camas con ese estado | |

### 3.6 Gestión de Usuarios

| ID | Caso de Prueba | Pasos | Resultado Esperado | ✓/✗ |
|----|----------------|-------|-------------------|-----|
| A-050 | Ver lista de usuarios | 1. Ir a `/admin/users` | Muestra tabla con todos los usuarios | |
| A-051 | Crear usuario admin | 1. Click "Nuevo Usuario" 2. Llenar datos 3. Seleccionar rol "Admin" 4. Guardar | Usuario admin creado | |
| A-052 | Crear usuario enfermero | 1. Click "Nuevo Usuario" 2. Llenar datos 3. Seleccionar rol "Enfermero" 4. Guardar | Usuario y perfil de enfermero creados | |
| A-053 | Editar usuario | 1. Click editar 2. Modificar datos 3. Guardar | Usuario actualizado | |
| A-054 | Desactivar usuario | 1. Click en toggle de activo | Usuario desactivado, no puede iniciar sesión | |
| A-055 | Filtrar por rol | 1. Seleccionar rol en filtro | Solo muestra usuarios de ese rol | |
| A-056 | Buscar usuario | 1. Escribir nombre o email en búsqueda | Filtra usuarios correctamente | |

### 3.7 Mapa del Hospital

| ID | Caso de Prueba | Pasos | Resultado Esperado | ✓/✗ |
|----|----------------|-------|-------------------|-----|
| A-060 | Ver mapa del hospital | 1. Ir a `/hospital-map` | Muestra estructura jerárquica: Áreas > Pisos > Cuartos > Camas | |
| A-061 | Expandir área | 1. Click en un área | Muestra pisos del área | |
| A-062 | Ver estado de camas | 1. Expandir hasta nivel de camas | Muestra estado visual de cada cama (colores) | |

### 3.8 Módulo de Capacitación (Gestión)

| ID | Caso de Prueba | Pasos | Resultado Esperado | ✓/✗ |
|----|----------------|-------|-------------------|-----|
| A-070 | Crear actividad de capacitación | 1. Ir a `/capacitacion/actividades` 2. Click "Nueva Actividad" 3. Llenar datos 4. Guardar | Actividad creada | |
| A-071 | Agregar sesiones a actividad | 1. Editar actividad 2. Agregar sesiones con fecha/hora | Sesiones creadas | |
| A-072 | Gestionar inscripciones | 1. Ir a inscripciones de una actividad 2. Inscribir enfermeros | Inscripciones creadas | |
| A-073 | Registrar asistencia | 1. Ir a control de asistencia 2. Marcar asistencia de participantes | Asistencia registrada, % calculado | |
| A-074 | Aprobar inscripciones | 1. Ir a aprobaciones 2. Aprobar inscripciones completadas | Certificaciones generadas automáticamente | |
| A-075 | Generar reportes | 1. Ir a reportes de capacitación 2. Seleccionar tipo de reporte 3. Generar | Reporte generado correctamente | |
| A-076 | Exportar reporte a Excel | 1. Generar reporte 2. Click "Exportar Excel" | Archivo Excel descargado | |
| A-077 | Exportar reporte a PDF | 1. Generar reporte 2. Click "Exportar PDF" | Archivo PDF descargado | |

### 3.9 Módulo de Farmacia (Gestión)

| ID | Caso de Prueba | Pasos | Resultado Esperado | ✓/✗ |
|----|----------------|-------|-------------------|-----|
| A-080 | Ver catálogo de medicamentos | 1. Ir a `/medicamentos/catalogo` | Muestra lista de medicamentos | |
| A-081 | Crear medicamento | 1. Click "Nuevo Medicamento" 2. Llenar datos completos 3. Guardar | Medicamento creado | |
| A-082 | Marcar medicamento como controlado | 1. Editar medicamento 2. Activar "Es Controlado" 3. Guardar | Badge "Controlado" visible | |
| A-083 | Registrar interacción medicamentosa | 1. En catálogo, click "Interacciones" 2. Agregar interacción entre medicamentos | Interacción registrada | |
| A-084 | Gestionar inventario | 1. Ir a `/medicamentos/inventario` 2. Registrar entrada de lote 3. Verificar stock | Inventario actualizado | |
| A-085 | Ver alertas de caducidad | 1. En inventario, ver sección de alertas | Muestra medicamentos próximos a caducar | |
| A-086 | Ver alertas de stock mínimo | 1. En inventario, ver alertas | Muestra medicamentos con stock bajo | |
| A-087 | Generar reporte de consumo | 1. Ir a `/medicamentos/reportes` 2. Seleccionar "Consumo" 3. Generar | Reporte de consumo por medicamento | |
| A-088 | Generar reporte de controlados | 1. Seleccionar "Controlados" 2. Generar | Reporte de auditoría de controlados | |

### 3.10 Módulo de Pacientes

| ID | Caso de Prueba | Pasos | Resultado Esperado | ✓/✗ |
|----|----------------|-------|-------------------|-----|
| A-090 | Admitir paciente | 1. Ir a `/urgencias/admision` 2. Llenar datos del paciente 3. Registrar signos vitales 4. Guardar | Paciente admitido, QR generado | |
| A-091 | Ver lista de pacientes | 1. Ir a `/enfermeria/pacientes` | Lista ordenada por TRIAGE | |
| A-092 | Ver expediente | 1. Click en paciente 2. Ver expediente completo | Muestra datos, signos vitales, historial | |
| A-093 | Registrar signos vitales | 1. En expediente, click "Registrar Signos" 2. Llenar valores 3. Guardar | TRIAGE recalculado automáticamente | |

### 3.11 Gestión de Turnos

| ID | Caso de Prueba | Pasos | Resultado Esperado | ✓/✗ |
|----|----------------|-------|-------------------|-----|
| A-100 | Crear turno | 1. Ir a `/turnos/gestor` 2. Seleccionar área, fecha, tipo 3. Crear turno | Turno creado | |
| A-101 | Asignar pacientes a enfermeros | 1. En turno activo 2. Asignar pacientes | Asignaciones creadas | |
| A-102 | Cerrar turno | 1. Click "Cerrar Turno" 2. Confirmar | Turno cerrado, asignaciones liberadas | |

---

## 4. Pruebas de Seguridad

| ID | Caso de Prueba | Pasos | Resultado Esperado | ✓/✗ |
|----|----------------|-------|-------------------|-----|
| A-S01 | Acceso sin autenticación | 1. Cerrar sesión 2. Intentar acceder a `/admin/users` | Redirige a login | |
| A-S02 | Modificar datos de otro admin | 1. Editar otro usuario admin 2. Guardar | Cambios guardados (admin tiene todos los permisos) | |
| A-S03 | Eliminar el propio usuario | 1. Intentar eliminar el usuario actual | Debe mostrar error o advertencia | |

---

## 5. Notas y Observaciones

| Fecha | Tester | Observación |
|-------|--------|-------------|
| | | |
| | | |

---

## 6. Resumen de Ejecución

| Total Casos | Pasados | Fallidos | Bloqueados | No Ejecutados |
|-------------|---------|----------|------------|---------------|
| | | | | |

**Firma del Tester:** _______________________
**Fecha de Ejecución:** _______________________
