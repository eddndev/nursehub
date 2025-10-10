# Diario del Sprint 1: Infrastructure Setup

**Periodo:** 2025-10-07 al 2025-10-21
**Duración:** 2 semanas
**Épica Maestra en GitHub:** #1
**GitHub Project:** [NurseHub - Development Board](https://github.com/users/eddndev/projects/5)

---

## 1. Objetivo del Sprint

Establecer la infraestructura técnica completa del proyecto NurseHub, implementar el sistema de autenticación con roles, y crear el módulo de configuración hospitalaria (Módulo 0) que servirá como base para todos los módulos subsecuentes. Al finalizar este sprint, el sistema debe permitir que un administrador configure la estructura completa del hospital (áreas, pisos, cuartos, camas) y que los usuarios puedan autenticarse con roles diferenciados.

## 2. Alcance y Tareas Incluidas

### Historias de Usuario Incluidas (del `04-user-stories.md`)

**MÓDULO 0: Configuración Hospitalaria**

- [x] `#7` - Migración y Modelo de Áreas del Hospital ✅ **Completada 2025-10-08**
- [x] `#8` - Migración y Modelo de Pisos ✅ **Completada 2025-10-08**
- [x] `#9` - Migración y Modelo de Cuartos ✅ **Completada 2025-10-08**
- [x] `#10` - Migración y Modelo de Camas ✅ **Completada 2025-10-08**
- [x] `#11` - CRUD de Áreas con Livewire ✅ **Completada 2025-10-09**
- [x] `#12` - CRUD de Pisos con Livewire ✅ **Completada 2025-10-09**
- [x] `#13` - CRUD de Cuartos y Camas con Livewire ✅ **Completada 2025-10-09**
- [x] `#14` - Mapa Visual del Hospital ✅ **Completada 2025-10-09**

**MÓDULO 2: Autenticación y Roles (Básico)**

- [x] `#5` - Extender Tabla Users con Campo Role ✅ **Completada 2025-10-08**
- [x] `#6` - Crear Middleware de Autorización por Roles ✅ **Completada 2025-10-08**
- [x] `#15` - Migración y Modelo de Enfermeros ✅ **Completada 2025-10-09**
- [ ] `#16` - CRUD de Usuarios y Enfermeros
- [ ] `#17` - Dashboard del Administrador

**Tareas Técnicas de Infraestructura**

- [x] `#2` - Configuración de Variables de Entorno y Base de Datos ✅ **Completada 2025-10-08**
- [x] `#3` - Configurar Tailwind CSS v4 con Design Tokens de NurseHub ✅ **Completada 2025-10-08**
- [x] `#4` - Crear Layouts Base (Guest, Authenticated, Admin) ✅ **Completada 2025-10-08**
- [ ] `#18` - Configurar GitHub Actions para CI

---

## 3. Registro de Decisiones Técnicas

### 2025-10-07: Configuración de GitHub Project

- **Decisión:** Usar GraphQL API en lugar de `gh project item-add` para agregar issues al proyecto
- **Razón:** El PROJECT_ID de NurseHub termina con guión bajo (`PVT_kwHOCUkKF84BFAj_`) lo cual causa conflictos con el CLI de GitHub
- **Solución:** Implementar mutation `addProjectV2ItemById` directamente en el workflow
- **Archivo afectado:** `.github/workflows/project-board-automation.yml`

### 2025-10-07: Creación de Labels del Proyecto

- **Labels creados:**
  - `Epic`, `Sprint: 1`
  - `Priority: Critical`, `Priority: High`, `Priority: Medium`, `Priority: Low`
  - `Type: Feature`, `Type: Chore`
  - `Module: Core`, `Module: Database`, `Module: Auth`, `Module: UI/UX`

---

## 4. Registro de Bloqueos y Soluciones

### 2025-10-08: Issue #2 - Configuración exitosa de BD

- **Issue completada:** #2 - Configuración de Variables de Entorno y Base de Datos
- **Resultado:**
  - ✅ Base de datos MySQL `nursehub` creada exitosamente
  - ✅ Variables de entorno configuradas correctamente en `.env`
  - ✅ Conexión verificada con `php artisan tinker`
  - ✅ Comando `php artisan migrate:fresh` ejecuta sin errores
- **Notas:** Primera tarea del sprint completada sin bloqueos. Infraestructura base lista para migraciones.

### 2025-10-08: Issue #3 - Tailwind CSS v4 con Design Tokens

- **Issue completada:** #3 - Configurar Tailwind CSS v4 con Design Tokens de NurseHub
- **Resultado:**
  - ✅ Tokens de diseño implementados en `resources/css/app.css` con `@theme`
  - ✅ Fuentes Inter y JetBrains Mono importadas desde Google Fonts
  - ✅ Colores TRIAGE personalizados definidos (rojo, naranja, amarillo, verde, azul)
  - ✅ Sistema de tema oscuro implementado con localStorage
  - ✅ Componente `<x-theme-toggle>` creado con Alpine.js
  - ✅ JavaScript de inicialización de tema en `app.js`
- **Archivos creados:**
  - `resources/views/components/theme-toggle.blade.php`
  - Modificado: `resources/css/app.css` (tokens `@theme`)
  - Modificado: `resources/js/app.js` (funciones `initTheme()`, `applyTheme()`)
  - Modificado: `resources/views/layouts/app.blade.php` y `guest.blade.php` (fuentes)
- **Notas:** Sistema de diseño completamente implementado siguiendo `docs/02-design-system.md`. Modo oscuro funcional con detección de preferencias del sistema.

### 2025-10-08: Issue #4 - Layouts Base Creados

- **Issue completada:** #4 - Crear Layouts Base (Guest, Authenticated, Admin)
- **Resultado:**
  - ✅ Layout `guest.blade.php` actualizado con fuentes de NurseHub
  - ✅ Layout `app.blade.php` actualizado con theme toggle
  - ✅ Layout `admin.blade.php` creado con sidebar responsive completo
  - ✅ Componente `<x-sidebar-link>` para enlaces principales del sidebar
  - ✅ Componente `<x-sidebar-team-link>` para enlaces de áreas/equipos
  - ✅ Componente `<x-nursehub-logo>` con logo inteligente del sistema
  - ✅ Navegación responsive con Alpine.js (mobile + desktop)
  - ✅ Sidebar con 7 secciones principales + 4 áreas del hospital
- **Archivos creados:**
  - `resources/views/layouts/admin.blade.php`
  - `resources/views/layouts/partials/admin-sidebar.blade.php`
  - `resources/views/components/sidebar-link.blade.php`
  - `resources/views/components/sidebar-team-link.blade.php`
  - `resources/views/components/nursehub-logo.blade.php`
- **Decisión técnica:** Se usaron colores `blue` (Medical Blue) y `slate` en lugar de `indigo` y `gray` para seguir fielmente el design system de NurseHub.
- **Notas:** Todos los layouts siguen el sistema de diseño documentado. El sidebar admin incluye navegación para: Dashboard, Enfermeros, Pacientes, Turnos, Medicamentos, Capacitaciones y Reportes.

### 2025-10-08: Issue #5 - Sistema de Roles Implementado

- **Issue completada:** #5 - Extender Tabla Users con Campo Role
- **Resultado:**
  - ✅ Migración `create_users_table.php` extendida con campo `role` (ENUM) e `is_active` (BOOLEAN)
  - ✅ Enum `UserRole` creado con 5 roles: ADMIN, COORDINADOR, JEFE_PISO, ENFERMERO, JEFE_CAPACITACION
  - ✅ Métodos helper en enum: `label()`, `isAdmin()`, `isCoordinadorOrAbove()`, `isJefe()`, `toArray()`
  - ✅ Modelo `User` actualizado con casts y scopes: `active()`, `byRole()`
  - ✅ Métodos helper en modelo: `isAdmin()`, `isCoordinadorOrAbove()`
  - ✅ DatabaseSeeder actualizado con 5 usuarios de ejemplo (uno por rol)
  - ✅ UserFactory actualizado con defaults (`role => ENFERMERO`, `is_active => true`)
  - ✅ Suite completa de 14 tests implementada en `UserRoleTest.php`
  - ✅ Migración ejecutada exitosamente con `php artisan migrate:fresh --seed`
  - ✅ Todos los 14 tests pasando (37 assertions, 1.73s)
- **Archivos modificados:**
  - `database/migrations/0001_01_01_000000_create_users_table.php`
  - `app/Models/User.php`
  - `database/seeders/DatabaseSeeder.php`
  - `database/factories/UserFactory.php`
- **Archivos creados:**
  - `app/Enums/UserRole.php`
  - `tests/Feature/UserRoleTest.php`
- **Usuarios seeder creados:**
  - `admin@nursehub.com` (Administrador) - rol: ADMIN
  - `coordinador@nursehub.com` (La Planchada) - rol: COORDINADOR
  - `jefe.pediatria@nursehub.com` (Lula Enfermera) - rol: JEFE_PISO
  - `capacitacion@nursehub.com` (Patch Addams) - rol: JEFE_CAPACITACION
  - `enfermero@nursehub.com` (Buen Samaritano) - rol: ENFERMERO
- **Tests implementados:**
  1. Crear usuarios con cada rol (ADMIN, COORDINADOR, JEFE_PISO, ENFERMERO, JEFE_CAPACITACION)
  2. Verificar rol default (ENFERMERO)
  3. Verificar is_active default (true)
  4. Crear usuario inactivo
  5. Scope `active()` filtra solo usuarios activos
  6. Scope `byRole()` filtra por rol específico
  7. Labels correctos del enum
  8. Método `isAdmin()` del enum
  9. Método `isCoordinadorOrAbove()` del enum
  10. Método `isJefe()` del enum
- **Decisión técnica:** Se usó PHP Enum backed por string en lugar de constantes o tablas separadas para los roles, permitiendo type safety y métodos helper directamente en el enum.
- **Notas:** Sistema de roles completo y funcional, listo para implementar middleware de autorización en Issue #6. Todos los passwords de ejemplo son `password`.

### 2025-10-08: Issue #6 - Middleware de Autorización Implementado

- **Issue completada:** #6 - Crear Middleware de Autorización por Roles
- **Resultado:**
  - ✅ Middleware `CheckRole` creado en `app/Http/Middleware/CheckRole.php`
  - ✅ Middleware acepta múltiples roles como parámetros: `->middleware('role:admin,coordinador')`
  - ✅ Validación de usuario autenticado (abort 401 si no autenticado)
  - ✅ Validación de rol permitido (abort 403 si sin permisos)
  - ✅ Conversión automática de strings a UserRole enum
  - ✅ Middleware registrado como alias `'role'` en `bootstrap/app.php`
  - ✅ Suite completa de 9 tests en `RoleMiddlewareTest.php`
  - ✅ Todos los 9 tests pasando (10 assertions, 1.69s)
  - ✅ Rutas de ejemplo creadas para testing en `routes/web.php`
- **Archivos creados:**
  - `app/Http/Middleware/CheckRole.php`
  - `tests/Feature/RoleMiddlewareTest.php`
- **Archivos modificados:**
  - `bootstrap/app.php` (registro del middleware)
  - `routes/web.php` (rutas de prueba)
- **Tests implementados:**
  1. Guest no puede acceder a ruta protegida (redirige a login)
  2. Usuario autenticado sin rol adecuado recibe 403
  3. Admin puede acceder a ruta de admin
  4. Coordinador puede acceder a ruta de coordinador
  5. Enfermero no puede acceder a ruta de coordinador
  6. Admin puede acceder a ruta con múltiples roles
  7. Coordinador puede acceder a ruta con múltiples roles
  8. Jefe de Piso puede acceder a ruta con múltiples roles
  9. Enfermero no puede acceder a ruta con múltiples roles
- **Uso del middleware:**
  ```php
  // Una sola rol
  Route::middleware(['auth', 'role:admin'])->group(function () {
      // Rutas solo para admin
  });

  // Múltiples roles
  Route::middleware(['auth', 'role:admin,coordinador,jefe_piso'])->group(function () {
      // Rutas para jefes
  });
  ```
- **Decisión técnica:** Se usa `abort(401)` y `abort(403)` en lugar de redirects para permitir manejo flexible de errores. Laravel automáticamente redirige a login en contexto web.
- **Notas:** Sistema de autorización completo y funcional. Listo para proteger rutas de administración de áreas, pisos, cuartos y camas en las siguientes issues.

### 2025-10-08: Issue #7 - Áreas del Hospital Implementadas

- **Issue completada:** #7 - Migración y Modelo de Áreas del Hospital
- **Resultado:**
  - ✅ Migración `create_areas_table` con todos los campos del esquema
  - ✅ Constraints UNIQUE en campos `nombre` y `codigo`
  - ✅ Campos: nombre, codigo, descripcion, opera_24_7, ratio_enfermero_paciente, requiere_certificacion
  - ✅ Modelo `Area` con fillable, casts y defaults
  - ✅ Relaciones definidas: `pisos()` y `rotaciones()` (hasMany)
  - ✅ Factory `AreaFactory` con 5 áreas de ejemplo
  - ✅ Seeder `AreaSeeder` con 8 áreas reales del hospital
  - ✅ Suite completa de 8 tests en `AreaTest.php`
  - ✅ Todos los 8 tests pasando (21 assertions, 0.82s)
  - ✅ Migración ejecutada exitosamente
- **Archivos creados:**
  - `database/migrations/2025_10_08_174625_create_areas_table.php`
  - `app/Models/Area.php`
  - `database/factories/AreaFactory.php`
  - `database/seeders/AreaSeeder.php`
  - `tests/Feature/AreaTest.php`
- **Áreas del seeder:**
  1. Urgencias (URG) - ratio 0.25, opera 24/7, requiere certificación
  2. Unidad de Cuidados Intensivos (UCI) - ratio 0.50, opera 24/7, requiere certificación
  3. Cirugía General (CIR) - ratio 0.17, opera 24/7, requiere certificación
  4. Pediatría (PED) - ratio 0.20, opera 24/7, requiere certificación
  5. Oncología (ONC) - ratio 0.20, no 24/7, requiere certificación
  6. Ginecología y Obstetricia (GINO) - ratio 0.20, opera 24/7, requiere certificación
  7. Medicina Interna (MI) - ratio 0.15, no 24/7, no requiere certificación
  8. Hospitalización General (HOSP) - ratio 0.12, opera 24/7, no requiere certificación
- **Tests implementados:**
  1. Crear área con todos los campos
  2. Validar constraint UNIQUE en nombre
  3. Validar constraint UNIQUE en codigo
  4. Verificar valores por defecto (opera_24_7: true, ratio: 1.00, certificacion: false)
  5. Actualizar área
  6. Eliminar área
  7. Factory crea área válida
  8. Seeder crea 8 áreas
- **Decisión técnica:** Se usó `$attributes` en el modelo para definir defaults además de la migración, asegurando que Eloquent aplique los valores por defecto correctamente.
- **Notas:** Tabla `areas` lista para relacionarse con `pisos` en Issue #8. Los ratios enfermero-paciente siguen estándares hospitalarios reales.

### 2025-10-08: Issue #8 - Pisos del Hospital Implementados

- **Issue completada:** #8 - Migración y Modelo de Pisos
- **Resultado:**
  - ✅ Migración `create_pisos_table` con FK a `areas` y onDelete cascade
  - ✅ Campos: area_id (FK), nombre, numero_piso, especialidad
  - ✅ Índice en `area_id` para optimizar queries
  - ✅ Modelo `Piso` con fillable y casts
  - ✅ Relación `belongsTo(Area::class)` implementada
  - ✅ Relación `hasMany(Cuarto::class)` definida
  - ✅ Factory `PisoFactory` con especialidades de ejemplo
  - ✅ Seeder `PisoSeeder` con 12 pisos distribuidos en 8 áreas
  - ✅ Suite completa de 9 tests en `PisoTest.php`
  - ✅ Todos los 9 tests pasando (18 assertions, 0.93s)
  - ✅ Migración ejecutada exitosamente
- **Archivos creados:**
  - `database/migrations/2025_10_08_180952_create_pisos_table.php`
  - `app/Models/Piso.php`
  - `database/factories/PisoFactory.php`
  - `database/seeders/PisoSeeder.php`
  - `tests/Feature/PisoTest.php`
- **Distribución de pisos del seeder:**
  - **Urgencias (Planta Baja - Piso 0):** Traumatología y Emergencias
  - **Ginecología (Piso 2):** 2 pisos - Ginecología y Maternidad
  - **Cirugía (Pisos 3-4):** Quirófanos y Recuperación Post-Quirúrgica
  - **UCI (Piso 5):** Cuidados Intensivos
  - **Pediatría (Piso 6):** 2 pisos - Pediatría General y Neonatología
  - **Oncología (Piso 7):** Oncología Médica
  - **Medicina Interna (Piso 8):** Medicina Interna
  - **Hospitalización General (Pisos 9-10):** 2 pisos de hospitalización
- **Tests implementados:**
  1. Crear piso con área asociada
  2. Relación belongsTo con Area
  3. Relación hasMany desde Area
  4. Validar FK requerida (area_id)
  5. Actualizar piso
  6. Eliminar piso
  7. Cascade delete cuando se elimina área
  8. Factory crea piso válido
  9. Seeder crea 12 pisos
- **Decisión técnica:** Se usó `onDelete('cascade')` en la FK para asegurar integridad referencial. Al eliminar un área, sus pisos se eliminan automáticamente.
- **Notas:** Tabla `pisos` lista para relacionarse con `cuartos` en Issue #9. La distribución de pisos refleja una estructura hospitalaria realista con 10 niveles.

### 2025-10-08: Issue #9 - Cuartos del Hospital Implementados

- **Issue completada:** #9 - Migración y Modelo de Cuartos
- **Resultado:**
  - ✅ Migración `create_cuartos_table` con FK a `pisos` y onDelete cascade
  - ✅ Campos: piso_id (FK), numero_cuarto, tipo (enum)
  - ✅ ENUM con valores: individual, doble, multiple
  - ✅ Índice en `piso_id` para optimizar queries
  - ✅ Modelo `Cuarto` con fillable, casts y defaults
  - ✅ Relación `belongsTo(Piso::class)` implementada
  - ✅ Relación `hasMany(Cama::class)` definida
  - ✅ Factory `CuartoFactory` con tipos aleatorios
  - ✅ Seeder `CuartoSeeder` con lógica inteligente por tipo de piso
  - ✅ Suite completa de 11 tests en `CuartoTest.php`
  - ✅ Todos los 11 tests pasando (20 assertions, 1.39s)
  - ✅ Migración ejecutada exitosamente
- **Archivos creados:**
  - `database/migrations/2025_10_08_184120_create_cuartos_table.php`
  - `app/Models/Cuarto.php`
  - `database/factories/CuartoFactory.php`
  - `database/seeders/CuartoSeeder.php`
  - `tests/Feature/CuartoTest.php`
- **Distribución inteligente de cuartos (seeder):**
  - **UCI:** 8 cuartos individuales (100%)
  - **Urgencias:** 12 cuartos mixtos
  - **Quirófanos:** 6 cuartos individuales (100%)
  - **Recuperación Post-Quirúrgica:** 10 cuartos (80% individual, 20% doble)
  - **Pediatría General:** 15 cuartos (40% individual, 40% doble, 20% múltiple)
  - **Neonatología:** 12 cuartos (60% individual, 40% múltiple)
  - **Oncología:** 20 cuartos (50% individual, 50% doble)
  - **Ginecología:** 18 cuartos mixtos
  - **Maternidad:** 16 cuartos individuales (100%)
  - **Medicina Interna:** 25 cuartos mixtos
  - **Hospitalización General:** 30 cuartos por piso (2 pisos = 60 cuartos)
  - **Total aproximado:** ~220 cuartos en el hospital
- **Tests implementados:**
  1. Crear cuarto con piso asociado
  2. Relación belongsTo con Piso
  3. Relación hasMany desde Piso
  4. Validar FK requerida (piso_id)
  5. Verificar default tipo (individual)
  6. Validar tipos permitidos (individual, doble, multiple)
  7. Actualizar cuarto
  8. Eliminar cuarto
  9. Cascade delete cuando se elimina piso
  10. Factory crea cuarto válido
  11. Seeder crea cuartos para todos los pisos
- **Decisión técnica:** El seeder usa lógica `match()` para asignar cantidades y distribuciones específicas según el tipo de piso, reflejando necesidades reales de cada área médica.
- **Notas:** Tabla `cuartos` lista para relacionarse con `camas` en Issue #10. La numeración de cuartos sigue el patrón: número de piso × 100 + secuencial (ej: Piso 3 = cuartos 301-330).

### 2025-10-08: Issue #10 - Camas del Hospital Implementadas

- **Issue completada:** #10 - Migración y Modelo de Camas
- **Resultado:**
  - ✅ Enum `CamaEstado` con 4 estados: libre, ocupada, en_limpieza, en_mantenimiento
  - ✅ Métodos helper en enum: `label()`, `color()`, `isDisponible()`, `toArray()`
  - ✅ Migración `create_camas_table` con FK a `cuartos` y onDelete cascade
  - ✅ Campos: cuarto_id (FK), numero_cama, estado (enum)
  - ✅ Índices en `cuarto_id` y `estado` para optimizar queries
  - ✅ Modelo `Cama` con fillable, casts y defaults
  - ✅ Relación `belongsTo(Cuarto::class)` implementada
  - ✅ Scopes implementados: `libre()`, `ocupada()`, `byEstado()`
  - ✅ Factory `CamaFactory` con estados aleatorios y métodos `libre()`, `ocupada()`
  - ✅ Seeder `CamaSeeder` con distribución realista de estados
  - ✅ Suite completa de 15 tests en `CamaTest.php`
  - ✅ Todos los 15 tests pasando (34 assertions, 1.77s)
  - ✅ Migración ejecutada exitosamente
- **Archivos creados:**
  - `app/Enums/CamaEstado.php`
  - `database/migrations/2025_10_08_192617_create_camas_table.php`
  - `app/Models/Cama.php`
  - `database/factories/CamaFactory.php`
  - `database/seeders/CamaSeeder.php`
  - `tests/Feature/CamaTest.php`
- **Distribución de estados del seeder:**
  - **60% Libres** (verde) - Disponibles para asignación
  - **30% Ocupadas** (rojo) - Con paciente asignado
  - **7% En Limpieza** (amarillo) - En proceso de sanitización
  - **3% En Mantenimiento** (gris) - Fuera de servicio
- **Cantidad de camas según tipo de cuarto:**
  - **Individual:** 1 cama
  - **Doble:** 2 camas
  - **Múltiple:** 4-6 camas (aleatorio)
  - **Total aproximado:** ~400-500 camas en todo el hospital
- **Tests implementados:**
  1. Crear cama con cuarto asociado
  2. Relación belongsTo con Cuarto
  3. Relación hasMany desde Cuarto
  4. Validar FK requerida (cuarto_id)
  5. Verificar default estado (libre)
  6. Validar todos los estados permitidos
  7. Cambiar estado de cama
  8. Scope libre filtra correctamente
  9. Scope ocupada filtra correctamente
  10. Cascade delete cuando se elimina cuarto
  11. Factory crea cama válida
  12. Seeder crea camas para todos los cuartos
  13. Enum tiene labels correctos
  14. Enum tiene colores correctos
  15. Método isDisponible() funciona correctamente
- **Decisión técnica:** Se agregaron índices tanto en `cuarto_id` como en `estado` para optimizar las queries frecuentes de búsqueda de camas libres por área/piso.
- **Notas:** Sistema completo de configuración hospitalaria implementado (Areas → Pisos → Cuartos → Camas). El hospital virtual cuenta con ~400-500 camas distribuidas en 8 áreas, 12 pisos y ~220 cuartos. Listo para implementar CRUDs en las siguientes issues.

### 2025-10-09: Issue #11 - CRUD de Áreas con Livewire Implementado

- **Issue completada:** #11 - CRUD de Áreas con Livewire
- **Resultado:**
  - ✅ Componente Livewire `AreaManager` con CRUD completo
  - ✅ Vista Blade responsiva con tabla, formulario inline y paginación
  - ✅ Ruta protegida `/admin/areas` con middleware `role:admin`
  - ✅ Validaciones con #[Validate] attributes en Livewire 3
  - ✅ Suite completa de 13 tests en `AreaManagerTest.php`
  - ✅ Todos los 13 tests pasando (39 assertions, 1.34s)
- **Archivos creados:**
  - `app/Livewire/Admin/AreaManager.php`
  - `resources/views/livewire/admin/area-manager.blade.php`
  - `tests/Feature/AreaManagerTest.php`
- **Archivos modificados:**
  - `routes/web.php` (agregada ruta admin.areas)
- **Funcionalidades implementadas:**
  - Crear área con validación de campos únicos (nombre, código)
  - Editar área con validación que excluye el registro actual
  - Eliminar área con confirmación wire:confirm
  - Listar áreas con paginación (10 por página)
  - Formulario inline que se muestra/oculta con $showForm
  - Validación de ratio_enfermero_paciente (0.01 - 99.99)
  - Badges con colores para estados booleanos (opera_24_7, requiere_certificacion)
  - Soporte completo para dark mode
- **Tests implementados:**
  1. Admin puede acceder a la página
  2. No-admin recibe 403
  3. Guest redirige a login
  4. Componente se renderiza correctamente
  5. Lista de áreas se muestra
  6. Crear área
  7. Validar campos requeridos
  8. Validar nombre único
  9. Validar código único
  10. Editar área
  11. Eliminar área
  12. Cancelar formulario
  13. Validar rango de ratio
- **Decisiones técnicas:**
  - Uso de #[Validate] attributes de Livewire 3 en lugar de método rules()
  - WithPagination trait para manejar paginación automática
  - Patrón inline form con toggle $showForm para mejor UX
  - session()->flash() para mensajes de éxito
  - Validación unique con exclusión de ID en modo edición
  - wire:confirm para confirmación de eliminación sin JavaScript adicional
  - Badge system con Tailwind para visualizar estados booleanos
- **Notas:** Primera implementación de CRUD con Livewire en el proyecto. Establece el patrón a seguir para los demás CRUDs (Pisos, Cuartos, Camas).

### 2025-10-09: Issue #12 - CRUD de Pisos con Livewire Implementado

- **Issue completada:** #12 - CRUD de Pisos con Livewire
- **Resultado:**
  - ✅ Componente Livewire `PisoManager` con CRUD completo
  - ✅ Vista Blade responsiva con select dropdown para áreas
  - ✅ Ruta protegida `/admin/pisos` con middleware `role:admin`
  - ✅ Eager loading de relación `area` para optimizar queries
  - ✅ Suite completa de 14 tests en `PisoManagerTest.php`
  - ✅ Todos los 14 tests pasando (42 assertions, 1.36s)
- **Archivos creados:**
  - `app/Livewire/Admin/PisoManager.php`
  - `resources/views/livewire/admin/piso-manager.blade.php`
  - `tests/Feature/PisoManagerTest.php`
- **Archivos modificados:**
  - `routes/web.php` (agregada ruta admin.pisos)
- **Funcionalidades implementadas:**
  - Crear piso con selección de área mediante dropdown
  - Editar piso con validación de FK area_id
  - Eliminar piso con confirmación
  - Listar pisos con eager loading de área (10 por página)
  - Validación exists:areas,id para area_id
  - Validación de numero_piso (1-50)
  - Badges diferenciados: azul para número de piso, medical para área
  - Especialidad opcional por piso
- **Tests implementados:**
  1. Admin puede acceder a la página
  2. No-admin recibe 403
  3. Guest redirige a login
  4. Componente se renderiza correctamente
  5. Lista de pisos se muestra
  6. Crear piso
  7. Validar campos requeridos
  8. Validar que área exista
  9. Validar rango de numero_piso (1-50)
  10. Editar piso
  11. Eliminar piso
  12. Cancelar formulario
  13. Áreas se cargan en dropdown
  14. Piso se muestra con relación de área
- **Decisiones técnicas:**
  - Select dropdown poblado con $areas ordenadas alfabéticamente
  - Eager loading `Piso::with('area')->paginate(10)` para evitar N+1
  - Validación exists para garantizar integridad referencial
  - Badge con color azul para numero_piso vs medical para área
  - Campo especialidad nullable para flexibilidad por piso
- **Notas:** Segundo CRUD implementado siguiendo el patrón de AreaManager. Demuestra manejo de relaciones belongsTo en Livewire con select dropdowns.

### 2025-10-09: Issue #13 - CRUD de Cuartos y Camas con Livewire Implementado

- **Issue completada:** #13 - CRUD de Cuartos y Camas con Livewire
- **Resultado:**
  - ✅ Componente Livewire `CuartoManager` con CRUD completo
  - ✅ Componente Livewire `CamaManager` con CRUD completo y gestión de estados
  - ✅ Vistas Blade responsivas con tablas y formularios inline
  - ✅ Rutas protegidas `/admin/cuartos` y `/admin/camas` con middleware `role:admin`
  - ✅ Validaciones con #[Validate] attributes en Livewire 3
  - ✅ Eager loading de relaciones para optimizar queries
  - ✅ Suite completa de 14 tests en `CuartoManagerTest.php`
  - ✅ Suite completa de 16 tests en `CamaManagerTest.php`
  - ✅ Todos los 30 tests pasando (76 assertions total)
- **Archivos creados:**
  - `app/Livewire/Admin/CuartoManager.php`
  - `resources/views/livewire/admin/cuarto-manager.blade.php`
  - `tests/Feature/CuartoManagerTest.php`
  - `app/Livewire/Admin/CamaManager.php`
  - `resources/views/livewire/admin/cama-manager.blade.php`
  - `tests/Feature/CamaManagerTest.php`
- **Archivos modificados:**
  - `routes/web.php` (agregadas rutas admin.cuartos y admin.camas)
- **Funcionalidades implementadas:**
  - **CuartoManager:**
    - Crear cuarto con selección de piso mediante dropdown
    - Editar cuarto con validación de tipo (individual, doble, multiple)
    - Eliminar cuarto con confirmación wire:confirm
    - Listar cuartos con eager loading de piso.area (10 por página)
    - Validación exists:pisos,id para piso_id
    - Badges diferenciados por tipo de cuarto
    - Link directo a gestión de camas del cuarto
  - **CamaManager:**
    - Crear cama con selección de cuarto y estado
    - Editar cama con validación de estado permitido
    - Eliminar cama con confirmación
    - Actualizar estado de cama en tiempo real con método `updateEstado()`
    - Filtrar camas por cuarto específico
    - Listar camas con eager loading de cuarto.piso.area
    - Badges con colores según estado (verde=libre, rojo=ocupada, amarillo=limpieza, gris=mantenimiento)
    - Select dropdown para cambio rápido de estado
- **Tests implementados CuartoManager:**
  1. Admin puede acceder a la página
  2. No-admin recibe 403
  3. Guest redirige a login
  4. Componente se renderiza correctamente
  5. Lista de cuartos se muestra
  6. Crear cuarto
  7. Validar campos requeridos
  8. Validar que piso exista
  9. Validar tipos permitidos (individual, doble, multiple)
  10. Editar cuarto
  11. Eliminar cuarto
  12. Cancelar formulario
  13. Pisos se cargan en dropdown
  14. Cuarto se muestra con relaciones completas (piso.area)
- **Tests implementados CamaManager:**
  1. Admin puede acceder a la página
  2. No-admin recibe 403
  3. Guest redirige a login
  4. Componente se renderiza correctamente
  5. Lista de camas se muestra
  6. Crear cama
  7. Validar campos requeridos
  8. Validar que cuarto exista
  9. Validar estados permitidos (libre, ocupada, en_limpieza, en_mantenimiento)
  10. Editar cama
  11. Eliminar cama
  12. Actualizar estado de cama con método `updateEstado()`
  13. Cancelar formulario
  14. Filtrar camas por cuarto específico
  15. Cuartos se cargan en dropdown
  16. Cama se muestra con relaciones completas (cuarto.piso.area)
- **Decisiones técnicas:**
  - Select dropdown poblado con pisos/cuartos ordenados con eager loading
  - Eager loading `Cuarto::with('piso.area')` y `Cama::with('cuarto.piso.area')` para evitar N+1
  - Validación exists para garantizar integridad referencial
  - Método `updateEstado()` para cambio rápido de estado de camas sin editar toda la cama
  - Filtrado opcional por cuarto en CamaManager usando parámetro de ruta
  - Badges con sistema de colores consistente usando enum `CamaEstado::color()`
  - Accordion en vista de cuartos para expandir/colapsar camas (implementación futura)
- **Bloqueo resuelto:**
  - **Error:** Tests no se ejecutaban por falta de prefijo `test_` en nombres de funciones
  - **Solución:** Renombrar todas las funciones de test con prefijo `test_` (ej: `test_admin_puede_acceder_a_cuarto_manager`)
  - **Resultado:** Todos los 30 tests pasando correctamente
- **Notas:** Tercer y cuarto CRUD implementados siguiendo el patrón establecido. Demuestra manejo avanzado de relaciones anidadas (cuarto.piso.area) y gestión de estados en tiempo real con Livewire. El sistema de camas incluye cambio rápido de estado mediante select dropdown.

### 2025-10-09: Issue #14 - Mapa Visual del Hospital Implementado

- **Issue completada:** #14 - Mapa Visual del Hospital
- **Resultado:**
  - ✅ Componente Livewire `HospitalMap` con visualización jerárquica completa
  - ✅ Vista Blade con estructura accordion multinivel usando Alpine.js
  - ✅ Ruta protegida `/hospital-map` con middleware `role:admin,coordinador`
  - ✅ Sistema de filtros avanzados (por área, por estado, solo disponibles)
  - ✅ Cálculo de estadísticas generales del hospital
  - ✅ Eager loading optimizado de toda la jerarquía
  - ✅ Suite completa de 16 tests en `HospitalMapTest.php`
  - ✅ Todos los 16 tests pasando (34 assertions, 1.40s)
- **Archivos creados:**
  - `app/Livewire/HospitalMap.php`
  - `resources/views/livewire/hospital-map.blade.php`
  - `tests/Feature/HospitalMapTest.php`
- **Archivos modificados:**
  - `routes/web.php` (agregada ruta hospital.map)
- **Funcionalidades implementadas:**
  - **Visualización jerárquica:**
    - Accordion de 4 niveles: Áreas → Pisos → Cuartos → Camas
    - Expansión/colapso con Alpine.js usando `x-collapse`
    - Navegación intuitiva con iconos de chevron
    - Badges de color para estados de camas
    - Badges informativos para tipo de cuarto (individual, doble, multiple)
  - **Estadísticas generales:**
    - Total de áreas, pisos, cuartos y camas
    - Camas libres, ocupadas, en limpieza, en mantenimiento
    - Porcentaje de ocupación del hospital
    - Porcentaje de disponibilidad
    - Tarjetas con iconos y colores diferenciados
  - **Sistema de filtros:**
    - Filtro por área específica (dropdown)
    - Filtro por estado de cama (libre, ocupada, en_limpieza, en_mantenimiento)
    - Checkbox "Solo camas disponibles" (filtra solo libres)
    - Botón "Limpiar filtros" para resetear
    - Actualización reactiva con Livewire
  - **Optimizaciones:**
    - Eager loading de 4 niveles: `areas.pisos.cuartos.camas`
    - Filtrado a nivel de query para reducir datos cargados
    - Cálculo de estadísticas con queries agregadas
    - Ordenamiento automático (áreas por nombre, pisos por número, etc.)
- **Tests implementados:**
  1. Admin puede acceder al mapa
  2. Coordinador puede acceder al mapa
  3. Enfermero no puede acceder al mapa (403)
  4. Guest redirige a login
  5. Componente se renderiza correctamente
  6. Muestra estadísticas generales (total áreas, camas, libres, ocupadas)
  7. Muestra áreas con pisos y cuartos
  8. Muestra camas con estados
  9. Filtro por área funciona correctamente
  10. Filtro por estado funciona correctamente
  11. Checkbox "Solo disponibles" filtra camas libres
  12. Limpiar filtros resetea todos los filtros
  13. Calcula porcentaje de ocupación correctamente
  14. Muestra mensaje cuando no hay resultados
  15. Muestra tipo de cuarto correctamente
  16. Cuenta pisos y cuartos por área correctamente
- **Decisiones técnicas:**
  - Uso de Alpine.js `x-collapse` para animaciones suaves de accordion
  - Eager loading con filtros aplicados en closures para optimizar memoria
  - Método `calcularEstadisticas()` separado para reutilización
  - Método `aplicarFiltros()` que recarga datos con criterios actuales
  - Layout `layouts.admin` para consistencia con otros módulos
  - Acceso permitido a admins y coordinadores (múltiples roles en middleware)
  - Tarjetas de estadísticas con gradientes y colores del design system
  - Grid responsive que se adapta a diferentes tamaños de pantalla
- **Notas:** Componente central del módulo de configuración hospitalaria. Permite visualizar la estructura completa del hospital en una sola vista. Útil para coordinadores y admins que necesitan tener una visión general de disponibilidad de camas. El sistema de filtros facilita búsqueda rápida de recursos específicos.

### 2025-10-09: Issue #15 - Enfermeros Implementados

- **Issue completada:** #15 - Migración y Modelo de Enfermeros
- **Resultado:**
  - ✅ Enum `TipoAsignacion` creado con 2 tipos: fijo, rotativo
  - ✅ Métodos helper en enum: `label()`, `descripcion()`, `toArray()`
  - ✅ Migración `create_enfermeros_table` con FK única a `users` (relación 1:1)
  - ✅ Campos: user_id (FK única), cedula_profesional (unique), tipo_asignacion (enum), area_fija_id (FK nullable), especialidades (text), anos_experiencia (int)
  - ✅ Índices en `user_id`, `tipo_asignacion`, `area_fija_id`
  - ✅ Modelo `Enfermero` con fillable, casts, defaults y relaciones
  - ✅ Relación `belongsTo(User::class)` implementada
  - ✅ Relación `belongsTo(Area::class, 'area_fija_id')` para área fija
  - ✅ Scopes implementados: `fijos()`, `rotativos()`, `byArea()`
  - ✅ Modelo `User` actualizado con relación `hasOne(Enfermero::class)`
  - ✅ Factory `EnfermeroFactory` con métodos `fijo()` y `rotativo()`
  - ✅ Seeder `EnfermeroSeeder` con distribución 60% fijos, 40% rotativos
  - ✅ Suite completa de 15 tests en `EnfermeroTest.php`
  - ✅ Todos los 15 tests pasando (37 assertions, 1.69s)
  - ✅ Migración ejecutada exitosamente
  - ✅ Seeder ejecutado: 30 enfermeros creados
- **Archivos creados:**
  - `app/Enums/TipoAsignacion.php`
  - `database/migrations/2025_10_09_044245_create_enfermeros_table.php`
  - `app/Models/Enfermero.php`
  - `database/factories/EnfermeroFactory.php`
  - `database/seeders/EnfermeroSeeder.php`
  - `tests/Feature/EnfermeroTest.php`
- **Archivos modificados:**
  - `app/Models/User.php` (agregada relación hasOne)
- **Relación 1:1 User-Enfermero:**
  - FK única `user_id` en tabla `enfermeros`
  - `User::enfermero()` - hasOne
  - `Enfermero::user()` - belongsTo
  - onDelete cascade: Al eliminar usuario se elimina perfil de enfermero
- **Tipos de asignación:**
  - **Fijo:** Enfermero asignado permanentemente a un área (requiere `area_fija_id`)
  - **Rotativo:** Enfermero que rota entre diferentes áreas (area_fija_id es null)
- **Tests implementados:**
  1. Crear enfermero con usuario asociado
  2. Relación 1:1 con User (belongsTo)
  3. Relación hasOne desde User
  4. Relación belongsTo con Area para área fija
  5. Validar FK única user_id
  6. Validar cédula profesional única
  7. Verificar default tipo_asignacion (fijo)
  8. Validar tipos permitidos (fijo, rotativo)
  9. Actualizar enfermero
  10. Eliminar enfermero
  11. Cascade delete cuando se elimina usuario
  12. Scope fijos()
  13. Scope rotativos()
  14. Scope byArea()
  15. Factory crea enfermero válido
- **Distribución del seeder:**
  - 60% enfermeros fijos (18 de 30)
  - 40% enfermeros rotativos (12 de 30)
  - Reúsa usuarios existentes con rol enfermero o crea nuevos
- **Decisión técnica:** Se usó FK única en `user_id` en lugar de FK en `users` para garantizar la relación 1:1 estricta. Solo usuarios con rol enfermero tendrán perfil en esta tabla.
- **Notas:** Factory incluye lógica condicional para asignar `area_fija_id` solo cuando `tipo_asignacion` es fijo. La relación 1:1 garantiza que cada usuario solo puede tener un perfil de enfermero.

---

## 5. Resultado del Sprint (A completar al final)

*   **Tareas Completadas:** [ ] X de Y
*   **Resumen:** [Se completará al finalizar el sprint]
*   **Aprendizajes / Retrospectiva:**
    *   **Qué funcionó bien:** [Se completará al finalizar]
    *   **Qué se puede mejorar:** [Se completará al finalizar]

---

## Anexo A: Desglose Detallado de Issues

### ISSUE 1: Configuración de Variables de Entorno y Base de Datos

**Descripción:**
Configurar el archivo `.env` con todas las variables necesarias para el proyecto y establecer la conexión con la base de datos MySQL.

**Criterios de Aceptación:**
- [ ] El archivo `.env` contiene todas las variables requeridas (DB, APP, MAIL)
- [ ] La conexión a la base de datos MySQL funciona correctamente
- [ ] El comando `php artisan migrate:fresh` ejecuta sin errores

**Tareas Técnicas:**
- [ ] Configurar variables de base de datos en `.env`
- [ ] Crear base de datos MySQL `nursehub`
- [ ] Verificar conexión con `php artisan tinker`

**Labels:** `Type: Chore`, `Module: Core`, `Priority: Critical`, `Sprint: 1`

---

### ISSUE 2: Configurar Tailwind CSS v4 con Design Tokens de NurseHub

**Descripción:**
Implementar Tailwind CSS v4 con los tokens de diseño definidos en `02-design-system.md`, incluyendo colores personalizados, fuentes y configuración de modo oscuro.

**Criterios de Aceptación:**
- [ ] Tailwind CSS v4 está configurado correctamente con Vite
- [ ] Los colores de NurseHub (blue, cyan, slate) están disponibles como clases
- [ ] Las fuentes Inter y JetBrains Mono se cargan correctamente
- [ ] El modo oscuro funciona con `dark:` prefix
- [ ] Los tokens TRIAGE están disponibles (rojo, naranja, amarillo, verde, azul)

**Tareas Técnicas:**
- [ ] Actualizar `resources/css/app.css` con `@theme` de NurseHub
- [ ] Importar fuentes Inter y JetBrains Mono desde Google Fonts
- [ ] Configurar colores personalizados en `@theme`
- [ ] Implementar toggle de modo oscuro con localStorage
- [ ] Crear componente Livewire `ThemeToggle`
- [ ] Probar que las clases de Tailwind funcionan correctamente

**Labels:** `Type: Chore`, `Module: UI/UX`, `Priority: High`, `Sprint: 1`

---

### ISSUE 3: Crear Layouts Base (Guest, Authenticated, Admin)

**Descripción:**
Crear los layouts principales de Blade/Livewire que se usarán en toda la aplicación, siguiendo el sistema de diseño de NurseHub.

**Criterios de Aceptación:**
- [ ] Layout `guest.blade.php` existe para páginas públicas (login, register)
- [ ] Layout `app.blade.php` existe para usuarios autenticados
- [ ] Layout `admin.blade.php` existe para administradores
- [ ] Todos los layouts incluyen el toggle de modo oscuro
- [ ] Los layouts son responsive (móvil, tablet, desktop)
- [ ] La navegación principal muestra opciones según el rol del usuario

**Tareas Técnicas:**
- [ ] Crear `resources/views/layouts/guest.blade.php`
- [ ] Crear `resources/views/layouts/app.blade.php` con sidebar
- [ ] Crear `resources/views/layouts/admin.blade.php`
- [ ] Crear componente de navegación `<x-nav>`
- [ ] Crear componente de sidebar `<x-sidebar>`
- [ ] Implementar navegación responsive con hamburger menu en móvil

**Labels:** `Type: Feature`, `Module: UI/UX`, `Priority: High`, `Sprint: 1`

---

### ISSUE 4: Extender Tabla Users con Campo Role

**Descripción:**
Modificar la migración de usuarios de Laravel Breeze para agregar los campos `role` (enum) e `is_active` (boolean) según el esquema de base de datos.

**Criterios de Aceptación:**
- [ ] La tabla `users` tiene el campo `role` tipo ENUM con valores: admin, coordinador, jefe_piso, enfermero, jefe_capacitacion
- [ ] La tabla `users` tiene el campo `is_active` tipo BOOLEAN con default `true`
- [ ] El modelo `User` tiene un cast para `role` como enum
- [ ] Existe un seeder que crea un usuario admin por defecto
- [ ] Los tests verifican que los roles funcionan correctamente

**Tareas Técnicas:**
- [ ] Modificar migración `create_users_table.php` para agregar `role` e `is_active`
- [ ] Crear enum `app/Enums/UserRole.php` con los roles
- [ ] Actualizar modelo `User` con cast `'role' => UserRole::class`
- [ ] Crear seeder `DatabaseSeeder.php` con usuario admin
- [ ] Ejecutar `php artisan migrate:fresh --seed`
- [ ] Crear test `UserRoleTest.php`

**Labels:** `Type: Feature`, `Module: Auth`, `Priority: Critical`, `Sprint: 1`

---

### ISSUE 5: Crear Middleware de Autorización por Roles

**Descripción:**
Implementar middleware que valide el rol del usuario para proteger rutas según permisos.

**Criterios de Aceptación:**
- [ ] Existe middleware `CheckRole` que valida roles
- [ ] Las rutas pueden protegerse con `->middleware('role:admin,coordinador')`
- [ ] Si un usuario sin permisos intenta acceder, recibe un error 403
- [ ] El middleware está registrado en `Kernel.php`

**Tareas Técnicas:**
- [ ] Crear middleware `app/Http/Middleware/CheckRole.php`
- [ ] Registrar middleware en `app/Http/Kernel.php`
- [ ] Crear test `RoleMiddlewareTest.php`
- [ ] Aplicar middleware a rutas de ejemplo

**Labels:** `Type: Feature`, `Module: Auth`, `Priority: High`, `Sprint: 1`

---

### ISSUE 6: Migración y Modelo de Áreas del Hospital

**Descripción:**
Crear la tabla `areas` con todos sus campos según el esquema de base de datos, su modelo Eloquent y seeder con datos de ejemplo.

**Criterios de Aceptación:**
- [ ] La tabla `areas` existe con todos los campos del esquema
- [ ] El modelo `Area` existe con relaciones a `pisos` y `rotaciones`
- [ ] Existe un seeder con áreas de ejemplo (Urgencias, UCI, Cirugía, etc.)
- [ ] Los tests verifican CRUD de áreas
- [ ] Los campos únicos (nombre, codigo) tienen constraint

**Tareas Técnicas:**
- [ ] Crear migración `create_areas_table.php`
- [ ] Crear modelo `app/Models/Area.php` con relaciones
- [ ] Crear factory `AreaFactory.php`
- [ ] Crear seeder `AreaSeeder.php` con datos reales del hospital
- [ ] Ejecutar migración y seeder
- [ ] Crear test `AreaTest.php`

**Labels:** `Type: Feature`, `Module: Database`, `Priority: Critical`, `Sprint: 1`

---

### ISSUE 7: Migración y Modelo de Pisos

**Descripción:**
Crear la tabla `pisos` con su relación a `areas`, modelo Eloquent y seeder.

**Criterios de Aceptación:**
- [ ] La tabla `pisos` existe con FK a `areas`
- [ ] El modelo `Piso` tiene relación `belongsTo(Area::class)`
- [ ] Existe un seeder con pisos de ejemplo
- [ ] Los tests verifican la relación con áreas

**Tareas Técnicas:**
- [ ] Crear migración `create_pisos_table.php`
- [ ] Crear modelo `app/Models/Piso.php`
- [ ] Crear factory `PisoFactory.php`
- [ ] Crear seeder `PisoSeeder.php`
- [ ] Crear test `PisoTest.php`

**Labels:** `Type: Feature`, `Module: Database`, `Priority: Critical`, `Sprint: 1`

---

### ISSUE 8: Migración y Modelo de Cuartos

**Descripción:**
Crear la tabla `cuartos` con su relación a `pisos`, modelo y seeder.

**Criterios de Aceptación:**
- [ ] La tabla `cuartos` existe con FK a `pisos`
- [ ] El modelo `Cuarto` tiene relación `belongsTo(Piso::class)` y `hasMany(Cama::class)`
- [ ] Existe un seeder con cuartos de ejemplo
- [ ] Los tests verifican las relaciones

**Tareas Técnicas:**
- [ ] Crear migración `create_cuartos_table.php`
- [ ] Crear modelo `app/Models/Cuarto.php`
- [ ] Crear factory `CuartoFactory.php`
- [ ] Crear seeder `CuartoSeeder.php`
- [ ] Crear test `CuartoTest.php`

**Labels:** `Type: Feature`, `Module: Database`, `Priority: Critical`, `Sprint: 1`

---

### ISSUE 9: Migración y Modelo de Camas

**Descripción:**
Crear la tabla `camas` con su relación a `cuartos`, modelo, enum de estados y seeder.

**Criterios de Aceptación:**
- [ ] La tabla `camas` existe con FK a `cuartos` y campo `estado` enum
- [ ] El modelo `Cama` tiene enum `CamaEstado` (libre, ocupada, en_limpieza, en_mantenimiento)
- [ ] Existe un seeder con camas de ejemplo
- [ ] Los tests verifican cambios de estado

**Tareas Técnicas:**
- [ ] Crear migración `create_camas_table.php`
- [ ] Crear enum `app/Enums/CamaEstado.php`
- [ ] Crear modelo `app/Models/Cama.php` con cast del enum
- [ ] Crear factory `CamaFactory.php`
- [ ] Crear seeder `CamaSeeder.php`
- [ ] Crear test `CamaTest.php`

**Labels:** `Type: Feature`, `Module: Database`, `Priority: Critical`, `Sprint: 1`

---

### ISSUE 10: CRUD de Áreas con Livewire

**Descripción:**
Implementar interfaz completa de administración de áreas del hospital usando Livewire.

**Criterios de Aceptación:**
- [ ] Existe una página `/admin/areas` que lista todas las áreas
- [ ] Se pueden crear, editar y eliminar áreas desde la interfaz
- [ ] La tabla muestra: nombre, código, opera 24/7, ratio enfermero-paciente
- [ ] Los formularios validan datos (nombre único, código único)
- [ ] La interfaz sigue el sistema de diseño de NurseHub
- [ ] Solo usuarios con rol `admin` pueden acceder

**Tareas Técnicas:**
- [x] Crear componente Livewire `app/Livewire/Admin/AreaManager.php`
- [x] Crear vista `resources/views/livewire/admin/area-manager.blade.php`
- [x] Implementar método `create()`, `update()`, `delete()`
- [x] Agregar validaciones en el componente
- [x] Crear ruta protegida en `routes/web.php`
- [x] Aplicar middleware `role:admin`
- [x] Crear test `AreaManagerTest.php`

**Estado:** ✅ **COMPLETADO**

**Archivos creados/modificados:**
- `app/Livewire/Admin/AreaManager.php`
- `resources/views/livewire/admin/area-manager.blade.php`
- `routes/web.php` (añadida ruta `/admin/areas`)
- `tests/Feature/AreaManagerTest.php`

**Resultados de tests:** ✅ 13 tests pasados (39 assertions)

**Decisiones técnicas:**
- Uso de atributos `#[Validate]` de Livewire 3 para validaciones en línea
- Implementación de paginación con `WithPagination` trait
- Formulario inline que aparece/desaparece con `$showForm`
- Mensajes flash con `session()->flash()` para feedback al usuario
- Validación única con exclusión del ID actual en modo edición
- Confirmación de eliminación con `wire:confirm`
- Interfaz responsive con Tailwind CSS y sistema de diseño NurseHub
- Uso de badges con colores para estados (Opera 24/7, Certificación requerida)

**Labels:** `Type: Feature`, `Module: Core`, `Priority: High`, `Sprint: 1`

---

### ISSUE 11: CRUD de Pisos con Livewire

**Descripción:**
Implementar interfaz de administración de pisos vinculados a áreas.

**Criterios de Aceptación:**
- [ ] Existe una página `/admin/pisos` que lista todos los pisos
- [ ] Se pueden crear, editar y eliminar pisos
- [ ] Al crear un piso se selecciona el área a la que pertenece
- [ ] La tabla muestra: nombre, número de piso, área, especialidad
- [ ] La interfaz es responsive y sigue el sistema de diseño

**Tareas Técnicas:**
- [ ] Crear componente Livewire `app/Livewire/Admin/PisoManager.php`
- [ ] Crear vista `resources/views/livewire/admin/piso-manager.blade.php`
- [ ] Implementar CRUD completo
- [ ] Agregar select de áreas con relación
- [ ] Crear ruta y aplicar middleware
- [ ] Crear test `PisoManagerTest.php`

**Labels:** `Type: Feature`, `Module: Core`, `Priority: High`, `Sprint: 1`

---

### ISSUE 12: CRUD de Cuartos y Camas con Livewire

**Descripción:**
Implementar interfaz de administración de cuartos y camas con gestión de estados.

**Criterios de Aceptación:**
- [ ] Existe una página `/admin/cuartos` que lista cuartos y sus camas
- [ ] Se pueden crear cuartos seleccionando el piso
- [ ] Se pueden agregar camas a cada cuarto
- [ ] Se puede cambiar el estado de las camas (libre, ocupada, en_limpieza, en_mantenimiento)
- [ ] La interfaz usa badges de colores según el estado de la cama
- [ ] Los cambios de estado son en tiempo real con Livewire

**Tareas Técnicas:**
- [ ] Crear componente Livewire `app/Livewire/Admin/CuartoManager.php`
- [ ] Crear componente Livewire `app/Livewire/Admin/CamaManager.php`
- [ ] Implementar CRUD de cuartos y camas
- [ ] Crear método `updateEstado()` para camas
- [ ] Aplicar colores de badges según sistema de diseño
- [ ] Crear tests

**Labels:** `Type: Feature`, `Module: Core`, `Priority: High`, `Sprint: 1`

---

### ISSUE 13: Mapa Visual del Hospital

**Descripción:**
Crear una vista tipo "mapa del hospital" que muestre la estructura completa (áreas → pisos → cuartos → camas) con estados visuales.

**Criterios de Aceptación:**
- [ ] Existe una página `/hospital-map` accesible por coordinadores y admins
- [ ] El mapa muestra áreas expandibles/colapsables
- [ ] Al expandir un área, muestra sus pisos
- [ ] Al expandir un piso, muestra cuartos y camas
- [ ] Las camas muestran su estado con colores (verde=libre, rojo=ocupada, amarillo=limpieza, gris=mantenimiento)
- [ ] La vista es responsive y usa accordion o tree view

**Tareas Técnicas:**
- [ ] Crear componente Livewire `app/Livewire/HospitalMap.php`
- [ ] Crear vista `resources/views/livewire/hospital-map.blade.php`
- [ ] Implementar estructura de árbol con Alpine.js o componentes anidados
- [ ] Aplicar colores según estados de camas
- [ ] Agregar filtros (por área, solo camas libres, etc.)
- [ ] Crear ruta protegida
- [ ] Crear test

**Labels:** `Type: Feature`, `Module: Core`, `Priority: Medium`, `Sprint: 1`

---

### ISSUE 14: Migración y Modelo de Enfermeros

**Descripción:**
Crear la tabla `enfermeros` con relación 1:1 a `users` y todos los campos del esquema.

**Criterios de Aceptación:**
- [ ] La tabla `enfermeros` existe con FK única a `users`
- [ ] El modelo `Enfermero` tiene relación `belongsTo(User::class)`
- [ ] El modelo `User` tiene relación `hasOne(Enfermero::class)`
- [ ] Existe enum `TipoAsignacion` (fijo, rotativo)
- [ ] Existe un seeder con enfermeros de ejemplo
- [ ] Los tests verifican la relación 1:1

**Tareas Técnicas:**
- [x] Crear migración `create_enfermeros_table.php`
- [x] Crear enum `app/Enums/TipoAsignacion.php`
- [x] Crear modelo `app/Models/Enfermero.php`
- [x] Actualizar modelo `User` con relación `hasOne`
- [x] Crear factory `EnfermeroFactory.php`
- [x] Crear seeder `EnfermeroSeeder.php`
- [x] Crear test `EnfermeroTest.php`

**Estado:** ✅ **COMPLETADO**

**Archivos creados/modificados:**
- `database/migrations/2025_10_09_044245_create_enfermeros_table.php`
- `app/Enums/TipoAsignacion.php`
- `app/Models/Enfermero.php`
- `app/Models/User.php` (añadida relación `hasOne`)
- `database/factories/EnfermeroFactory.php`
- `database/seeders/EnfermeroSeeder.php`
- `tests/Feature/EnfermeroTest.php`

**Resultados de tests:** ✅ 15 tests pasados (37 assertions)

**Decisiones técnicas:**
- Se implementó relación 1:1 entre `User` y `Enfermero` usando FK única con `onDelete('cascade')`
- Los enfermeros pueden ser de tipo `FIJO` (asignados permanentemente a un área) o `ROTATIVO` (rotan entre áreas)
- El factory incluye métodos `fijo()` y `rotativo()` para generar perfiles específicos
- El seeder crea 30 enfermeros por defecto (60% fijos, 40% rotativos) o usa usuarios existentes con rol enfermero
- Se añadieron scopes útiles: `fijos()`, `rotativos()`, `byArea()`
- El campo `area_fija_id` es nullable con `onDelete('set null')` para manejar eliminación de áreas

**Labels:** `Type: Feature`, `Module: Database`, `Priority: High`, `Sprint: 1`

---

### ISSUE 15: CRUD de Usuarios y Enfermeros

**Descripción:**
Implementar interfaz de administración de usuarios del sistema y sus perfiles de enfermero.

**Criterios de Aceptación:**
- [ ] Existe una página `/admin/users` que lista todos los usuarios
- [ ] Se pueden crear usuarios con rol específico
- [ ] Al crear un usuario con rol `enfermero`, se crea automáticamente su perfil de enfermero
- [ ] Se pueden editar datos de usuario y perfil de enfermero
- [ ] Se puede desactivar un usuario (is_active = false)
- [ ] La interfaz muestra: nombre, email, rol, estado (activo/inactivo)

**Tareas Técnicas:**
- [ ] Crear componente Livewire `app/Livewire/Admin/UserManager.php`
- [ ] Crear vista `resources/views/livewire/admin/user-manager.blade.php`
- [ ] Implementar creación de usuario + enfermero en una transacción
- [ ] Agregar validaciones (email único, cédula única)
- [ ] Implementar activar/desactivar usuario
- [ ] Crear test `UserManagerTest.php`

**Labels:** `Type: Feature`, `Module: Auth`, `Priority: High`, `Sprint: 1`

---

### ISSUE 16: Dashboard del Administrador

**Descripción:**
Crear el dashboard principal para usuarios con rol `admin` con estadísticas generales del sistema.

**Criterios de Aceptación:**
- [ ] Existe una página `/admin/dashboard` accesible solo por admins
- [ ] Muestra tarjetas con: total de áreas, total de pisos, total de camas, total de usuarios
- [ ] Muestra gráfico de distribución de camas por estado
- [ ] Muestra lista de últimos usuarios registrados
- [ ] La interfaz usa el sistema de diseño de NurseHub

**Tareas Técnicas:**
- [ ] Crear componente Livewire `app/Livewire/Admin/Dashboard.php`
- [ ] Crear vista `resources/views/livewire/admin/dashboard.blade.php`
- [ ] Implementar queries para obtener estadísticas
- [ ] Crear componentes de tarjetas reutilizables
- [ ] Aplicar estilos según sistema de diseño
- [ ] Crear ruta `/admin/dashboard`

**Labels:** `Type: Feature`, `Module: Core`, `Priority: Medium`, `Sprint: 1`

---

### ISSUE 17: Configurar GitHub Actions para CI

**Descripción:**
Implementar pipeline básico de CI/CD que ejecute tests y linters en cada push.

**Criterios de Aceptación:**
- [ ] Existe workflow `.github/workflows/ci.yml`
- [ ] El workflow ejecuta `composer install` y `npm install`
- [ ] El workflow ejecuta `php artisan test`
- [ ] El workflow ejecuta `npm run build`
- [ ] El workflow falla si los tests fallan
- [ ] El badge de CI aparece en el README

**Tareas Técnicas:**
- [ ] Crear archivo `.github/workflows/ci.yml`
- [ ] Configurar job de testing con PHP 8.3
- [ ] Configurar cache de Composer y NPM
- [ ] Agregar badge al README.md
- [ ] Probar que el workflow funcione

**Labels:** `Type: Chore`, `Module: Core`, `Priority: Low`, `Sprint: 1`

---

## Resumen de Issues del Sprint 1

**Total de Issues:** 17

**Distribución por tipo:**
- Features: 11
- Chores: 6

**Distribución por prioridad:**
- Critical: 6
- High: 8
- Medium: 2
- Low: 1

**Módulos involucrados:**
- Core: 7
- Database: 6
- Auth: 3
- UI/UX: 2

---

**Notas:**
- Los números de issue (`#TBD`) se asignarán automáticamente al crearlas en GitHub
- Todas las issues deben seguir la plantilla definida en `docs/AGENTS.md`
- Cada issue debe tener labels: `Type`, `Module`, `Priority`, `Sprint: 1`
