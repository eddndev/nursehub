# Plan de Pruebas - Rol: Farmacéutico

**Sistema:** NurseHub
**Rol:** Farmacéutico
**Fecha:** 2025-11-25
**Versión:** 1.0

---

## 1. Descripción del Rol

El **Farmacéutico** es responsable de la gestión completa del módulo de farmacia: catálogo de medicamentos, control de inventario, despacho de solicitudes, y generación de reportes. Es el rol especializado para el manejo de medicamentos.

### Credenciales de Prueba
- **Email:** farmaceutico@nursehub.test
- **Password:** password (o el configurado en el seeder)

> **Nota:** Este rol debe ser agregado al enum UserRole si no existe. Actualmente se usa en las rutas pero puede no estar definido formalmente.

---

## 2. Módulos Accesibles

| Módulo | Ruta | Acceso |
|--------|------|--------|
| Dashboard | `/dashboard` | ✅ |
| Catálogo de Medicamentos | `/medicamentos/catalogo` | ✅ |
| Inventario de Medicamentos | `/medicamentos/inventario` | ✅ |
| Despacho de Farmacia | `/medicamentos/despacho` | ✅ |
| Reportes de Farmacia | `/medicamentos/reportes` | ✅ |
| Solicitudes de Medicamentos | `/medicamentos/solicitudes` | ✅ |
| Administración de Medicamentos | `/medicamentos/administrar` | ✅ |
| Gestión de Áreas | `/admin/areas` | ❌ |
| Gestión de Usuarios | `/admin/users` | ❌ |
| Lista de Pacientes | `/enfermeria/pacientes` | ❌ |
| Gestor de Turnos | `/turnos/gestor` | ❌ |
| Gestión de Capacitaciones | `/capacitacion/actividades` | ❌ |

---

## 3. Casos de Prueba

### 3.1 Autenticación y Dashboard

| ID | Caso de Prueba | Pasos | Resultado Esperado | ✓/✗ |
|----|----------------|-------|-------------------|-----|
| F-001 | Login exitoso | 1. Ir a `/login` 2. Ingresar credenciales 3. Click "Iniciar Sesión" | Redirige a `/dashboard` | |
| F-002 | Ver dashboard | 1. Verificar contenido | Muestra información relevante de farmacia | |
| F-003 | Logout | 1. Click en menú 2. "Cerrar Sesión" | Sesión cerrada | |

### 3.2 Catálogo de Medicamentos (CRUD)

| ID | Caso de Prueba | Pasos | Resultado Esperado | ✓/✗ |
|----|----------------|-------|-------------------|-----|
| F-010 | Ver catálogo de medicamentos | 1. Ir a `/medicamentos/catalogo` | Lista de todos los medicamentos | |
| F-011 | Buscar medicamento por nombre | 1. Escribir nombre en búsqueda | Filtra medicamentos | |
| F-012 | Filtrar por categoría | 1. Seleccionar categoría (Analgésico, Antibiótico, etc.) | Solo medicamentos de esa categoría | |
| F-013 | Filtrar por vía de administración | 1. Seleccionar vía (Oral, IV, IM, etc.) | Solo medicamentos de esa vía | |
| F-014 | Filtrar solo controlados | 1. Activar filtro "Solo Controlados" | Solo medicamentos controlados | |
| F-015 | Filtrar activos/inactivos | 1. Cambiar filtro de estado | Muestra activos o inactivos | |
| F-016 | Crear medicamento básico | 1. Click "Nuevo Medicamento" 2. Llenar: código, nombre comercial, genérico, presentación 3. Guardar | Medicamento creado | |
| F-017 | Crear medicamento completo | 1. Llenar todos los campos: concentración, laboratorio, precio, indicaciones, contraindicaciones | Medicamento con información completa | |
| F-018 | Crear medicamento controlado | 1. Nuevo medicamento 2. Marcar "Es Controlado" 3. Guardar | Badge "Controlado" visible | |
| F-019 | Editar medicamento | 1. Click editar en medicamento 2. Modificar datos 3. Guardar | Cambios guardados | |
| F-020 | Desactivar medicamento | 1. Toggle de activo 2. Desactivar | Medicamento inactivo (no aparece en búsquedas normales) | |
| F-021 | Reactivar medicamento | 1. Filtrar inactivos 2. Activar medicamento | Medicamento activo nuevamente | |
| F-022 | Eliminar medicamento | 1. Click eliminar 2. Confirmar | Medicamento eliminado (soft delete) | |

### 3.3 Interacciones Medicamentosas

| ID | Caso de Prueba | Pasos | Resultado Esperado | ✓/✗ |
|----|----------------|-------|-------------------|-----|
| F-030 | Ver interacciones de medicamento | 1. En catálogo, click "Interacciones" en un medicamento | Lista de interacciones conocidas | |
| F-031 | Registrar interacción leve | 1. Click "Nueva Interacción" 2. Seleccionar otro medicamento 3. Severidad: Leve 4. Descripción 5. Guardar | Interacción registrada | |
| F-032 | Registrar interacción moderada | 1. Nueva interacción 2. Severidad: Moderada | Interacción guardada | |
| F-033 | Registrar interacción grave | 1. Nueva interacción 2. Severidad: Grave | Interacción guardada | |
| F-034 | Registrar interacción contraindicada | 1. Nueva interacción 2. Severidad: Contraindicada | Bloquea administración conjunta | |
| F-035 | Agregar recomendación | 1. En interacción, agregar recomendación | Recomendación guardada | |
| F-036 | Editar interacción | 1. Click editar 2. Modificar 3. Guardar | Cambios guardados | |
| F-037 | Eliminar interacción | 1. Click eliminar 2. Confirmar | Interacción eliminada | |

### 3.4 Gestión de Inventario

| ID | Caso de Prueba | Pasos | Resultado Esperado | ✓/✗ |
|----|----------------|-------|-------------------|-----|
| F-040 | Ver inventario | 1. Ir a `/medicamentos/inventario` | Lista de stock por medicamento y lote | |
| F-041 | Ver alertas de stock bajo | 1. En inventario, sección alertas | Medicamentos con stock bajo mínimo | |
| F-042 | Ver alertas de caducidad | 1. Sección alertas de caducidad | Medicamentos próximos a caducar (60 días) | |
| F-043 | Filtrar por medicamento | 1. Buscar medicamento específico | Stock de ese medicamento | |
| F-044 | Filtrar por área | 1. Seleccionar área | Stock en esa área | |
| F-045 | Filtrar por estado | 1. Seleccionar estado (disponible, cuarentena, caducado) | Filtro aplicado | |
| F-046 | Registrar entrada de inventario | 1. Click "Nueva Entrada" 2. Seleccionar medicamento 3. Lote, cantidad, fecha caducidad, costo 4. Guardar | Entrada registrada, stock actualizado | |
| F-047 | Registrar entrada con lote existente | 1. Nueva entrada 2. Mismo lote | Stock sumado al lote existente | |
| F-048 | Registrar ajuste positivo | 1. Click "Ajuste" 2. Tipo: Positivo 3. Cantidad, motivo | Stock incrementado | |
| F-049 | Registrar ajuste negativo (merma) | 1. Ajuste negativo 2. Cantidad, motivo | Stock decrementado, registrado como merma | |
| F-050 | Transferir entre áreas | 1. Click "Transferir" 2. Origen, destino, cantidad | Stock transferido entre áreas | |
| F-051 | Marcar lote como cuarentena | 1. Seleccionar lote 2. Cambiar estado a "Cuarentena" | Lote no disponible para despacho | |
| F-052 | Marcar lote como caducado | 1. Lote vencido 2. Estado: Caducado | Lote excluido de despachos | |
| F-053 | Ver historial de movimientos | 1. Click "Historial" en lote | Todos los movimientos del lote | |

### 3.5 Despacho de Solicitudes (Función Principal)

| ID | Caso de Prueba | Pasos | Resultado Esperado | ✓/✗ |
|----|----------------|-------|-------------------|-----|
| F-060 | Ver solicitudes pendientes | 1. Ir a `/medicamentos/despacho` | Lista de solicitudes por atender | |
| F-061 | Ver solicitudes por prioridad | 1. Verificar orden | STAT primero (rojo), luego Urgente (naranja), luego Normal | |
| F-062 | Ver detalle de solicitud | 1. Click en solicitud | Paciente, enfermero solicitante, medicamentos, cantidades | |
| F-063 | Aprobar solicitud | 1. Click "Aprobar" | Estado cambia a "Aprobada" | |
| F-064 | Rechazar solicitud | 1. Click "Rechazar" 2. Escribir motivo | Estado: Rechazada, motivo guardado | |
| F-065 | Despachar solicitud simple | 1. Solicitud aprobada 2. Click "Despachar" 3. Confirmar | Medicamentos despachados, stock descontado | |
| F-066 | Selección automática de lote (FIFO) | 1. Al despachar | Sistema selecciona lote que caduca primero | |
| F-067 | Selección manual de lote | 1. Cambiar lote predeterminado | Lote manual seleccionado | |
| F-068 | Despacho parcial | 1. Cantidad solicitada > stock 2. Despachar cantidad disponible | Despacho parcial registrado | |
| F-069 | Ver solicitudes despachadas | 1. Tab "Despachadas" | Historial de despachos | |

### 3.6 Despacho de Medicamentos Controlados (Doble Verificación)

| ID | Caso de Prueba | Pasos | Resultado Esperado | ✓/✗ |
|----|----------------|-------|-------------------|-----|
| F-070 | Identificar solicitud con controlados | 1. Ver solicitud con medicamento controlado | Badge "Controlado" visible | |
| F-071 | Requerir número de receta | 1. Intentar despachar controlado sin receta | Error: "Número de receta requerido" | |
| F-072 | Ingresar número de receta | 1. Escribir número de receta médica | Campo aceptado | |
| F-073 | Requerir justificación | 1. Intentar despachar sin justificación | Error: "Justificación requerida" | |
| F-074 | Requerir segunda verificación | 1. Completar datos 2. Intentar despachar | Sistema solicita segundo verificador | |
| F-075 | Segunda verificación - usuario diferente | 1. Ingresar email de otro farmacéutico 2. Ingresar contraseña | Verificación por usuario diferente | |
| F-076 | Segunda verificación - mismo usuario | 1. Intentar verificar con el mismo usuario | Error: "Debe ser un usuario diferente" | |
| F-077 | Segunda verificación - contraseña incorrecta | 1. Contraseña incorrecta | Error de autenticación | |
| F-078 | Segunda verificación - rol no autorizado | 1. Usuario sin rol de farmacia | Error: "Usuario no autorizado" | |
| F-079 | Despacho exitoso con doble verificación | 1. Completar todos los requisitos | Despacho registrado con ambas firmas | |
| F-080 | Registro en auditoría de controlados | 1. Verificar registro después del despacho | Entrada en `registro_medicamento_controlado` | |

### 3.7 Reportes de Farmacia

| ID | Caso de Prueba | Pasos | Resultado Esperado | ✓/✗ |
|----|----------------|-------|-------------------|-----|
| F-090 | Acceder a reportes | 1. Ir a `/medicamentos/reportes` | Interfaz de reportes | |
| F-091 | Generar reporte de consumo | 1. Tipo: "Consumo por Medicamento" 2. Rango de fechas 3. Generar | Lista de medicamentos con cantidades consumidas | |
| F-092 | Generar reporte de costos | 1. Tipo: "Costos por Área" | Desglose de costos por área | |
| F-093 | Generar reporte de desperdicios | 1. Tipo: "Desperdicios y Mermas" | Medicamentos caducados y mermas | |
| F-094 | Generar reporte de controlados | 1. Tipo: "Medicamentos Controlados" | Auditoría completa de controlados | |
| F-095 | Generar reporte de inventario | 1. Tipo: "Estado de Inventario" | Stock actual con alertas | |
| F-096 | Generar reporte de movimientos | 1. Tipo: "Historial de Movimientos" | Todos los movimientos del período | |
| F-097 | Filtrar reporte por área | 1. Seleccionar área específica | Reporte filtrado | |
| F-098 | Cambiar rango de fechas | 1. Modificar fecha inicio/fin 2. Regenerar | Datos del nuevo período | |
| F-099 | Ver gráficos del reporte | 1. Sección de gráficos | Visualización gráfica de datos | |
| F-100 | Exportar a Excel | 1. Click "Exportar Excel" | Archivo .xlsx descargado | |
| F-101 | Exportar a PDF | 1. Click "Exportar PDF" | Archivo .pdf descargado | |

### 3.8 Solicitudes de Medicamentos (Vista)

| ID | Caso de Prueba | Pasos | Resultado Esperado | ✓/✗ |
|----|----------------|-------|-------------------|-----|
| F-110 | Ver todas las solicitudes | 1. Ir a `/medicamentos/solicitudes` | Lista completa de solicitudes | |
| F-111 | Filtrar por estado | 1. Filtro: Pendiente/Aprobada/Despachada/Rechazada | Solicitudes filtradas | |
| F-112 | Filtrar por prioridad | 1. Filtro: Normal/Urgente/STAT | Solicitudes de esa prioridad | |
| F-113 | Buscar por número de solicitud | 1. Escribir número SOL-XXXX | Solicitud específica | |
| F-114 | Buscar por paciente | 1. Escribir nombre de paciente | Solicitudes de ese paciente | |

### 3.9 Administración de Medicamentos (Verificación)

| ID | Caso de Prueba | Pasos | Resultado Esperado | ✓/✗ |
|----|----------------|-------|-------------------|-----|
| F-120 | Ver historial de administraciones | 1. Ir a `/medicamentos/administrar` | Puede ver historial de administraciones | |
| F-121 | Verificar administración de controlado | 1. Buscar administración de controlado | Registro con verificador visible | |

---

## 4. Pruebas de Seguridad y Restricciones

| ID | Caso de Prueba | Pasos | Resultado Esperado | ✓/✗ |
|----|----------------|-------|-------------------|-----|
| F-S01 | No puede acceder a gestión de usuarios | 1. Ir a `/admin/users` | Acceso denegado (403) | |
| F-S02 | No puede acceder a gestión de áreas | 1. Ir a `/admin/areas` | Acceso denegado | |
| F-S03 | No puede acceder a lista de pacientes | 1. Ir a `/enfermeria/pacientes` | Acceso denegado | |
| F-S04 | No puede acceder a gestor de turnos | 1. Ir a `/turnos/gestor` | Acceso denegado | |
| F-S05 | No puede acceder a capacitaciones | 1. Ir a `/capacitacion/actividades` | Acceso denegado | |
| F-S06 | No puede modificar expediente de paciente | 1. Intentar acceder a expediente | Sin acceso o solo lectura | |

---

## 5. Pruebas de Integridad de Datos

| ID | Caso de Prueba | Pasos | Resultado Esperado | ✓/✗ |
|----|----------------|-------|-------------------|-----|
| F-I01 | Stock no puede ser negativo | 1. Intentar despachar más de lo disponible | Error o despacho parcial | |
| F-I02 | Lote caducado no se puede despachar | 1. Intentar despachar lote caducado | Sistema no permite | |
| F-I03 | Movimientos registrados correctamente | 1. Realizar entrada 2. Verificar movimiento | Movimiento en historial | |
| F-I04 | Trazabilidad de controlados | 1. Despachar controlado 2. Ver registro | Registro completo con firmas | |

---

## 6. Notas y Observaciones

| Fecha | Tester | Observación |
|-------|--------|-------------|
| | | |

---

## 7. Resumen de Ejecución

| Total Casos | Pasados | Fallidos | Bloqueados | No Ejecutados |
|-------------|---------|----------|------------|---------------|
| | | | | |

**Firma del Tester:** _______________________
**Fecha de Ejecución:** _______________________
