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

- [ ] `#7` - Migración y Modelo de Áreas del Hospital
- [ ] `#8` - Migración y Modelo de Pisos
- [ ] `#9` - Migración y Modelo de Cuartos
- [ ] `#10` - Migración y Modelo de Camas
- [ ] `#11` - CRUD de Áreas con Livewire
- [ ] `#12` - CRUD de Pisos con Livewire
- [ ] `#13` - CRUD de Cuartos y Camas con Livewire
- [ ] `#14` - Mapa Visual del Hospital

**MÓDULO 2: Autenticación y Roles (Básico)**

- [ ] `#5` - Extender Tabla Users con Campo Role
- [ ] `#6` - Crear Middleware de Autorización por Roles
- [ ] `#15` - Migración y Modelo de Enfermeros
- [ ] `#16` - CRUD de Usuarios y Enfermeros
- [ ] `#17` - Dashboard del Administrador

**Tareas Técnicas de Infraestructura**

- [ ] `#2` - Configuración de Variables de Entorno y Base de Datos
- [ ] `#3` - Configurar Tailwind CSS v4 con Design Tokens de NurseHub
- [ ] `#4` - Crear Layouts Base (Guest, Authenticated, Admin)
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

*Esta sección documentará problemas inesperados y cómo se resolvieron.*

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
- [ ] Crear componente Livewire `app/Livewire/Admin/AreaManager.php`
- [ ] Crear vista `resources/views/livewire/admin/area-manager.blade.php`
- [ ] Implementar método `create()`, `update()`, `delete()`
- [ ] Agregar validaciones en el componente
- [ ] Crear ruta protegida en `routes/web.php`
- [ ] Aplicar middleware `role:admin`
- [ ] Crear test `AreaManagerTest.php`

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
- [ ] Crear migración `create_enfermeros_table.php`
- [ ] Crear enum `app/Enums/TipoAsignacion.php`
- [ ] Crear modelo `app/Models/Enfermero.php`
- [ ] Actualizar modelo `User` con relación `hasOne`
- [ ] Crear factory `EnfermeroFactory.php`
- [ ] Crear seeder `EnfermeroSeeder.php`
- [ ] Crear test `EnfermeroTest.php`

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
