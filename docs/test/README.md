# Plan de Pruebas - NurseHub

**Sistema:** NurseHub - Sistema de Gestión Hospitalaria de Enfermería
**Versión:** 1.0
**Fecha de Creación:** 2025-11-25

---

## Descripción

Este directorio contiene los planes de prueba de aceptación de usuario (UAT) para el sistema NurseHub. Cada documento está organizado por rol de usuario y contiene casos de prueba detallados para validar el correcto funcionamiento del sistema.

---

## Documentos de Prueba por Rol

| # | Documento | Rol | Casos de Prueba |
|---|-----------|-----|-----------------|
| 1 | [01-pruebas-admin.md](01-pruebas-admin.md) | Administrador | ~100 casos |
| 2 | [02-pruebas-coordinador.md](02-pruebas-coordinador.md) | Coordinador General | ~90 casos |
| 3 | [03-pruebas-jefe-piso.md](03-pruebas-jefe-piso.md) | Jefe de Piso/Área | ~100 casos |
| 4 | [04-pruebas-enfermero.md](04-pruebas-enfermero.md) | Enfermero | ~160 casos |
| 5 | [05-pruebas-farmaceutico.md](05-pruebas-farmaceutico.md) | Farmacéutico | ~120 casos |
| 6 | [06-pruebas-jefe-capacitacion.md](06-pruebas-jefe-capacitacion.md) | Jefe de Capacitación | ~100 casos |

---

## Roles del Sistema

### 1. Administrador (Admin)
- Acceso completo a todas las funcionalidades
- Gestión de usuarios, infraestructura hospitalaria, y configuración del sistema
- Supervisión de todos los módulos

### 2. Coordinador General de Enfermería
- Gestión del personal de enfermería
- Administración de capacitaciones
- Supervisión de todas las áreas
- Reportes y analytics

### 3. Jefe de Piso/Área
- Gestión de turnos y asignaciones de pacientes
- Supervisión del personal de su área
- Relevo de turnos
- Vista de capacitaciones

### 4. Enfermero
- Atención directa a pacientes
- Registro de signos vitales
- Administración de medicamentos
- Solicitud de medicamentos
- Inscripción a capacitaciones

### 5. Farmacéutico
- Gestión del catálogo de medicamentos
- Control de inventario
- Despacho de solicitudes
- Doble verificación de controlados
- Reportes de farmacia

### 6. Jefe de Capacitación
- Creación de actividades de capacitación
- Gestión de inscripciones
- Control de asistencia
- Aprobación y certificaciones
- Reportes de capacitación

---

## Credenciales de Prueba

| Rol | Email | Password |
|-----|-------|----------|
| Admin | admin@nursehub.test | password |
| Coordinador | coordinador@nursehub.test | password |
| Jefe de Piso | jefe.piso@nursehub.test | password |
| Enfermero | enfermero@nursehub.test | password |
| Farmacéutico | farmaceutico@nursehub.test | password |
| Jefe de Capacitación | jefe.capacitacion@nursehub.test | password |

> **Nota:** Las credenciales pueden variar según la configuración del seeder. Verificar en `database/seeders/DatabaseSeeder.php`

---

## Módulos del Sistema

### Módulo 0: Infraestructura Hospitalaria
- Gestión de Áreas
- Gestión de Pisos
- Gestión de Cuartos
- Gestión de Camas
- Mapa del Hospital

### Módulo 1: Gestión de Usuarios
- CRUD de usuarios
- Asignación de roles
- Perfil de enfermero

### Módulo 2: Registro Clínico Electrónico (RCE)
- Admisión de pacientes
- Expediente del paciente
- Registro de signos vitales
- TRIAGE automático
- Gráficos de tendencias
- Generación de código QR

### Módulo 3: Herramientas de Cuidado
- Balance de líquidos
- Escalas de valoración (EVA, Glasgow, Braden)
- Planes de cuidado

### Módulo 4: Turnos y Asignaciones
- Gestión de turnos
- Asignación de pacientes
- Relevo de turno
- Mis asignaciones

### Módulo 5: Capacitación y Desarrollo
- Gestión de actividades
- Inscripciones
- Control de asistencia
- Aprobaciones
- Certificaciones
- Calendario
- Reportes

### Módulo 6: Farmacia y Medicamentos
- Catálogo de medicamentos
- Interacciones medicamentosas
- Inventario
- Solicitudes
- Despacho
- Administración
- Control de medicamentos controlados
- Reportes

---

## Instrucciones de Ejecución

### Preparación del Ambiente

1. **Verificar base de datos:**
   ```bash
   php artisan migrate:status
   ```

2. **Cargar datos de prueba:**
   ```bash
   php artisan db:seed
   ```

3. **Verificar rutas:**
   ```bash
   php artisan route:list
   ```

### Ejecución de Pruebas

1. Seleccionar el documento de pruebas según el rol a probar
2. Iniciar sesión con las credenciales correspondientes
3. Ejecutar cada caso de prueba en orden
4. Marcar resultado (✓ = Pasado, ✗ = Fallido)
5. Documentar observaciones en caso de fallos
6. Completar el resumen de ejecución al final

### Reporte de Bugs

Si se encuentra un bug durante las pruebas:

1. Documentar en la sección "Notas y Observaciones"
2. Incluir:
   - ID del caso de prueba
   - Pasos para reproducir
   - Resultado esperado vs resultado obtenido
   - Capturas de pantalla si aplica
3. Crear issue en GitHub si es necesario

---

## Criterios de Aceptación

### Para considerar el sistema listo para producción:

- [ ] 100% de casos críticos pasados
- [ ] 95% de casos totales pasados
- [ ] 0 bugs bloqueantes
- [ ] Todos los módulos accesibles según permisos de rol
- [ ] Todas las exportaciones funcionando (Excel, PDF)
- [ ] Notificaciones enviándose correctamente
- [ ] Jobs programados ejecutándose

---

## Historial de Ejecución

| Fecha | Versión | Tester | Resultado |
|-------|---------|--------|-----------|
| | | | |

---

**Generado por:** Claude AI Assistant
**Fecha:** 2025-11-25
