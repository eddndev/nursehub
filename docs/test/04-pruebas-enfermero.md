# Plan de Pruebas - Rol: Enfermero

**Sistema:** NurseHub
**Rol:** Enfermero
**Fecha:** 2025-11-25
**Versión:** 1.0

---

## 1. Descripción del Rol

El **Enfermero** es el rol operativo principal del sistema. Se encarga de la atención directa a pacientes, registro de signos vitales, administración de medicamentos, y documentación clínica. Es el usuario más frecuente del sistema.

### Credenciales de Prueba
- **Email:** enfermero@nursehub.test
- **Password:** password (o el configurado en el seeder)

---

## 2. Módulos Accesibles

| Módulo | Ruta | Acceso |
|--------|------|--------|
| Dashboard | `/dashboard` | ✅ |
| Admisión de Pacientes | `/urgencias/admision` | ✅ |
| Lista de Pacientes | `/enfermeria/pacientes` | ✅ |
| Expediente de Paciente | `/enfermeria/paciente/{id}` | ✅ |
| Mis Asignaciones | `/enfermeria/mis-asignaciones` | ✅ |
| Dashboard de Capacitación | `/capacitacion/dashboard` | ✅ |
| Solicitudes de Medicamentos | `/medicamentos/solicitudes` | ✅ |
| Administración de Medicamentos | `/medicamentos/administrar` | ✅ |
| Gestión de Áreas | `/admin/areas` | ❌ |
| Gestión de Usuarios | `/admin/users` | ❌ |
| Mapa del Hospital | `/hospital-map` | ❌ |
| Gestor de Turnos | `/turnos/gestor` | ❌ |
| Gestión de Capacitaciones | `/capacitacion/actividades` | ❌ |
| Catálogo de Medicamentos | `/medicamentos/catalogo` | ❌ |
| Despacho de Farmacia | `/medicamentos/despacho` | ❌ |

---

## 3. Casos de Prueba

### 3.1 Autenticación y Dashboard

| ID | Caso de Prueba | Pasos | Resultado Esperado | ✓/✗ |
|----|----------------|-------|-------------------|-----|
| E-001 | Login exitoso | 1. Ir a `/login` 2. Ingresar credenciales 3. Click "Iniciar Sesión" | Redirige a `/dashboard` | |
| E-002 | Ver dashboard personal | 1. Verificar contenido del dashboard | Muestra información relevante para enfermero | |
| E-003 | Logout | 1. Click menú usuario 2. "Cerrar Sesión" | Sesión cerrada, redirige a login | |

### 3.2 Mis Asignaciones (Función Principal)

| ID | Caso de Prueba | Pasos | Resultado Esperado | ✓/✗ |
|----|----------------|-------|-------------------|-----|
| E-010 | Ver mis pacientes asignados | 1. Ir a `/enfermeria/mis-asignaciones` | Lista de pacientes asignados en turno actual | |
| E-011 | Ver información resumida de paciente | 1. En lista de asignaciones | Nombre, ubicación (cama), TRIAGE, último registro | |
| E-012 | Identificar pacientes por TRIAGE | 1. Verificar badges de color | Rojo=crítico, Naranja=emergencia, Amarillo=urgente, Verde=menos urgente, Azul=no urgente | |
| E-013 | Acceso rápido a expediente | 1. Click en paciente asignado | Abre expediente completo | |
| E-014 | Ver ubicación del paciente | 1. En tarjeta de paciente | Muestra: Área > Piso > Cuarto > Cama | |
| E-015 | Lista vacía sin asignaciones | 1. Usuario sin turno activo | Mensaje "No tienes pacientes asignados" | |

### 3.3 Lista de Pacientes

| ID | Caso de Prueba | Pasos | Resultado Esperado | ✓/✗ |
|----|----------------|-------|-------------------|-----|
| E-020 | Ver lista de pacientes | 1. Ir a `/enfermeria/pacientes` | Lista de pacientes del área/hospital | |
| E-021 | Filtrar por TRIAGE Rojo | 1. Filtro TRIAGE: Rojo | Solo pacientes críticos | |
| E-022 | Filtrar por TRIAGE Naranja | 1. Filtro: Naranja | Solo emergencias | |
| E-023 | Filtrar por TRIAGE Amarillo | 1. Filtro: Amarillo | Solo urgentes | |
| E-024 | Filtrar por TRIAGE Verde | 1. Filtro: Verde | Solo menos urgentes | |
| E-025 | Filtrar por TRIAGE Azul | 1. Filtro: Azul | Solo no urgentes | |
| E-026 | Buscar por nombre | 1. Escribir "García" en búsqueda | Pacientes con apellido García | |
| E-027 | Buscar por CURP | 1. Escribir CURP | Paciente específico | |
| E-028 | Buscar por código QR | 1. Escribir código QR | Paciente específico | |
| E-029 | Ordenamiento por prioridad | 1. Verificar orden de lista | TRIAGE rojo primero, luego naranja, etc. | |
| E-030 | Paginación | 1. Si hay más de 20 pacientes 2. Navegar páginas | Paginación funcional | |

### 3.4 Expediente del Paciente

| ID | Caso de Prueba | Pasos | Resultado Esperado | ✓/✗ |
|----|----------------|-------|-------------------|-----|
| E-040 | Ver expediente completo | 1. Click en paciente | Expediente con todos los datos | |
| E-041 | Ver datos personales | 1. En expediente, sección datos | Nombre, edad, sexo, CURP, teléfono | |
| E-042 | Ver alergias (destacadas) | 1. Sección alergias | Alergias en rojo/destacado si existen | |
| E-043 | Ver antecedentes médicos | 1. Sección antecedentes | Lista de antecedentes | |
| E-044 | Ver contacto de emergencia | 1. Sección contacto | Nombre y teléfono de contacto | |
| E-045 | Ver ubicación actual | 1. Sección ubicación | Área, Piso, Cuarto, Cama | |
| E-046 | Ver signos vitales actuales | 1. Sección signos vitales | Últimos valores con iconos | |
| E-047 | Ver historial de eventos | 1. Timeline de eventos | Cronología de acciones (admisión, registros, etc.) | |
| E-048 | Ver TRIAGE actual | 1. En encabezado | Badge de color con nivel de TRIAGE | |

### 3.5 Registro de Signos Vitales (Función Crítica)

| ID | Caso de Prueba | Pasos | Resultado Esperado | ✓/✗ |
|----|----------------|-------|-------------------|-----|
| E-050 | Abrir modal de registro | 1. En expediente 2. Click "Registrar Signos Vitales" | Modal con formulario | |
| E-051 | Registrar presión arterial | 1. Sistólica: 120 2. Diastólica: 80 | Valores aceptados | |
| E-052 | Registrar frecuencia cardíaca | 1. FC: 75 lpm | Valor aceptado | |
| E-053 | Registrar frecuencia respiratoria | 1. FR: 18 rpm | Valor aceptado | |
| E-054 | Registrar temperatura | 1. Temp: 36.5°C | Valor aceptado | |
| E-055 | Registrar saturación de oxígeno | 1. SpO2: 98% | Valor aceptado | |
| E-056 | Registrar glucosa (opcional) | 1. Glucosa: 95 mg/dL | Valor aceptado | |
| E-057 | Agregar observaciones | 1. Escribir observaciones 2. Guardar | Observaciones guardadas | |
| E-058 | Validación: PA sistólica > diastólica | 1. Sistólica: 80 2. Diastólica: 120 | Error de validación | |
| E-059 | Validación: rango de temperatura | 1. Temp: 50°C | Error: fuera de rango (30-45) | |
| E-060 | Validación: rango de SpO2 | 1. SpO2: 150% | Error: fuera de rango (50-100) | |
| E-061 | TRIAGE calculado automáticamente | 1. Ingresar valores normales 2. Ver TRIAGE sugerido | TRIAGE verde o azul | |
| E-062 | TRIAGE rojo por valores críticos | 1. PA: 200/120, FC: 140, SpO2: 85 | TRIAGE rojo calculado | |
| E-063 | Override manual de TRIAGE | 1. Registrar signos 2. Cambiar TRIAGE manualmente | TRIAGE modificado con indicador | |
| E-064 | Guardar registro | 1. Llenar formulario completo 2. Click "Guardar" | Registro guardado, modal cierra, expediente actualizado | |
| E-065 | Cancelar registro | 1. Llenar datos 2. Click "Cancelar" | Modal cierra sin guardar | |

### 3.6 Gráficos de Tendencias

| ID | Caso de Prueba | Pasos | Resultado Esperado | ✓/✗ |
|----|----------------|-------|-------------------|-----|
| E-070 | Ver gráficos de tendencias | 1. En expediente 2. Tab/sección gráficos | Gráficos visibles | |
| E-071 | Gráfico de presión arterial | 1. Ver gráfico de PA | Líneas sistólica/diastólica en el tiempo | |
| E-072 | Gráfico de frecuencia cardíaca | 1. Ver gráfico FC | Evolución de FC | |
| E-073 | Gráfico de temperatura | 1. Ver gráfico temp | Evolución de temperatura | |
| E-074 | Gráfico de SpO2 | 1. Ver gráfico SpO2 | Evolución de saturación | |
| E-075 | Cambiar período: 24 horas | 1. Seleccionar "24h" | Datos de últimas 24 horas | |
| E-076 | Cambiar período: 7 días | 1. Seleccionar "7 días" | Datos de última semana | |
| E-077 | Cambiar período: 30 días | 1. Seleccionar "30 días" | Datos del último mes | |
| E-078 | Ver estadísticas (promedio, min, max) | 1. Ver tarjetas de estadísticas | Valores calculados correctamente | |

### 3.7 Balance de Líquidos

| ID | Caso de Prueba | Pasos | Resultado Esperado | ✓/✗ |
|----|----------------|-------|-------------------|-----|
| E-080 | Acceder a control de líquidos | 1. En expediente 2. Tab "Control de Líquidos" | Interfaz de balance | |
| E-081 | Registrar ingreso (oral) | 1. Tipo: Oral 2. Cantidad: 200ml 3. Guardar | Ingreso registrado | |
| E-082 | Registrar ingreso (IV) | 1. Tipo: Intravenoso 2. Cantidad: 500ml | Ingreso IV registrado | |
| E-083 | Registrar egreso (orina) | 1. Tipo: Orina 2. Cantidad: 300ml | Egreso registrado | |
| E-084 | Registrar egreso (vómito) | 1. Tipo: Vómito 2. Cantidad: 100ml | Egreso registrado | |
| E-085 | Balance calculado automáticamente | 1. Ver balance | Ingresos - Egresos = Balance | |
| E-086 | Balance por turno | 1. Ver totales por turno | Totales del turno actual | |
| E-087 | Balance acumulado 24h | 1. Ver total 24h | Suma de últimas 24 horas | |

### 3.8 Escalas de Valoración

| ID | Caso de Prueba | Pasos | Resultado Esperado | ✓/✗ |
|----|----------------|-------|-------------------|-----|
| E-090 | Aplicar escala EVA (dolor) | 1. Tab "Escalas" 2. Seleccionar EVA 3. Puntaje: 5 | Interpretación: "Dolor moderado" | |
| E-091 | Aplicar escala Glasgow | 1. Seleccionar Glasgow 2. Llenar componentes | Puntaje total y nivel de conciencia | |
| E-092 | Aplicar escala Braden | 1. Seleccionar Braden 2. Evaluar factores | Riesgo de úlceras calculado | |
| E-093 | Historial de escalas | 1. Ver historial | Valoraciones anteriores | |

### 3.9 Admisión de Pacientes

| ID | Caso de Prueba | Pasos | Resultado Esperado | ✓/✗ |
|----|----------------|-------|-------------------|-----|
| E-100 | Acceder a admisión | 1. Ir a `/urgencias/admision` | Formulario de admisión | |
| E-101 | Llenar datos personales | 1. Nombre, apellidos, fecha nacimiento, sexo | Datos aceptados | |
| E-102 | Registrar CURP (opcional) | 1. CURP válido | CURP guardado | |
| E-103 | Validación de CURP | 1. CURP inválido | Error de formato | |
| E-104 | Registrar contacto de emergencia | 1. Nombre y teléfono | Datos guardados | |
| E-105 | Registrar alergias | 1. Escribir alergias conocidas | Alergias guardadas | |
| E-106 | Registrar signos vitales iniciales | 1. Llenar PA, FC, FR, Temp, SpO2 | TRIAGE calculado | |
| E-107 | Seleccionar cama | 1. Seleccionar cama disponible | Cama asignada | |
| E-108 | Admitir sin cama | 1. No seleccionar cama 2. Guardar | Paciente admitido sin ubicación | |
| E-109 | Código QR generado | 1. Completar admisión | Código QR único generado | |
| E-110 | Redirección a expediente | 1. Después de admitir | Redirige a expediente del nuevo paciente | |

### 3.10 Solicitudes de Medicamentos

| ID | Caso de Prueba | Pasos | Resultado Esperado | ✓/✗ |
|----|----------------|-------|-------------------|-----|
| E-110 | Ver mis solicitudes | 1. Ir a `/medicamentos/solicitudes` | Lista de solicitudes creadas | |
| E-111 | Crear nueva solicitud | 1. Click "Nueva Solicitud" | Formulario de solicitud | |
| E-112 | Seleccionar paciente | 1. Buscar y seleccionar paciente | Paciente seleccionado | |
| E-113 | Agregar medicamento | 1. Buscar medicamento 2. Seleccionar 3. Cantidad | Medicamento agregado a lista | |
| E-114 | Agregar múltiples medicamentos | 1. Agregar 3 medicamentos diferentes | Todos en la solicitud | |
| E-115 | Verificar disponibilidad | 1. Al agregar medicamento | Muestra stock disponible | |
| E-116 | Prioridad normal | 1. Seleccionar prioridad: Normal | Prioridad establecida | |
| E-117 | Prioridad urgente | 1. Seleccionar: Urgente | Prioridad urgente | |
| E-118 | Prioridad STAT | 1. Seleccionar: STAT | Prioridad inmediata | |
| E-119 | Enviar solicitud | 1. Click "Enviar Solicitud" | Solicitud creada con número único | |
| E-120 | Ver estado de solicitud | 1. En lista, ver columna estado | Estado visible (pendiente, aprobada, despachada) | |
| E-121 | Cancelar solicitud pendiente | 1. Solicitud en estado pendiente 2. Click "Cancelar" | Solicitud cancelada | |
| E-122 | No puede cancelar solicitud despachada | 1. Solicitud despachada 2. Intentar cancelar | Botón no disponible o error | |

### 3.11 Administración de Medicamentos

| ID | Caso de Prueba | Pasos | Resultado Esperado | ✓/✗ |
|----|----------------|-------|-------------------|-----|
| E-130 | Acceder a administración | 1. Ir a `/medicamentos/administrar` | Interfaz de administración | |
| E-131 | Seleccionar paciente | 1. Buscar paciente 2. Seleccionar | Lista de medicamentos pendientes | |
| E-132 | Ver medicamentos pendientes | 1. Después de seleccionar paciente | Medicamentos de solicitudes despachadas | |
| E-133 | Alerta de alergia | 1. Paciente con alergia a medicamento | Alerta roja visible | |
| E-134 | Alerta de interacción | 1. Medicamento con interacción conocida | Alerta de interacción | |
| E-135 | Bloqueo por alergia grave | 1. Intentar administrar con alergia | Administración bloqueada | |
| E-136 | Confirmar administración | 1. Seleccionar medicamento 2. Confirmar | Registro de administración creado | |
| E-137 | Registrar dosis administrada | 1. Ingresar dosis real | Dosis guardada | |
| E-138 | Registrar vía de administración | 1. Seleccionar vía | Vía guardada | |
| E-139 | Agregar observaciones | 1. Escribir observaciones | Observaciones guardadas | |
| E-140 | Registrar reacción adversa | 1. Marcar "Tuvo reacción" 2. Describir | Reacción registrada en expediente | |
| E-141 | Ver historial de administraciones | 1. En paciente, historial | Lista de administraciones anteriores | |

### 3.12 Capacitación Personal

| ID | Caso de Prueba | Pasos | Resultado Esperado | ✓/✗ |
|----|----------------|-------|-------------------|-----|
| E-150 | Ver dashboard de capacitación | 1. Ir a `/capacitacion/dashboard` | Mi portal de capacitación | |
| E-151 | Ver capacitaciones disponibles | 1. Tab "Disponibles" | Lista de capacitaciones abiertas | |
| E-152 | Ver detalles de capacitación | 1. Click en capacitación | Información, fechas, requisitos | |
| E-153 | Inscribirse a capacitación | 1. Click "Inscribirse" | Inscripción registrada (pendiente) | |
| E-154 | Ver mis inscripciones | 1. Tab "Mis Inscripciones" | Lista con estados | |
| E-155 | Ver estado de inscripción | 1. En lista | Estado: pendiente/aprobada/completada/rechazada | |
| E-156 | Ver mis certificaciones | 1. Tab "Certificaciones" | Certificaciones obtenidas | |
| E-157 | Descargar certificación PDF | 1. Click en certificación 2. "Descargar" | PDF descargado | |
| E-158 | Ver certificación en navegador | 1. Click "Ver" | PDF abre en nueva pestaña | |

---

## 4. Pruebas de Seguridad y Restricciones

| ID | Caso de Prueba | Pasos | Resultado Esperado | ✓/✗ |
|----|----------------|-------|-------------------|-----|
| E-S01 | No puede acceder a gestión de usuarios | 1. Ir a `/admin/users` | Acceso denegado (403) | |
| E-S02 | No puede acceder a gestión de áreas | 1. Ir a `/admin/areas` | Acceso denegado | |
| E-S03 | No puede acceder a gestor de turnos | 1. Ir a `/turnos/gestor` | Acceso denegado | |
| E-S04 | No puede crear capacitaciones | 1. Ir a `/capacitacion/actividades` | Acceso denegado | |
| E-S05 | No puede acceder a catálogo de medicamentos | 1. Ir a `/medicamentos/catalogo` | Acceso denegado | |
| E-S06 | No puede despachar medicamentos | 1. Ir a `/medicamentos/despacho` | Acceso denegado | |
| E-S07 | No puede ver reportes de farmacia | 1. Ir a `/medicamentos/reportes` | Acceso denegado | |
| E-S08 | No puede ver mapa del hospital | 1. Ir a `/hospital-map` | Acceso denegado | |

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
