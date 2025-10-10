# Manifiesto del Proyecto: NurseHub
**Versión:** 1.0
**Fecha:** 2025-10-07
**Autor:** Equipo de Desarrollo NurseHub

## 1. Resumen Ejecutivo

**NurseHub** es un Sistema de Gestión Hospitalaria integral diseñado para digitalizar y optimizar los procesos de asignación, seguimiento y administración del personal de enfermería en el **Hospital de Alta Especialidad Casa Alivio del Sufrimiento**. El sistema reemplaza los métodos manuales actuales (pizarrones, folders plastificados, registros en papel) con una plataforma web moderna que centraliza la información clínica de pacientes, la gestión de turnos, la trazabilidad de medicamentos e insumos, y la capacitación del personal de enfermería.

El proyecto busca mejorar la eficiencia operativa, reducir errores médicos por falta de trazabilidad, y proporcionar una experiencia de usuario intuitiva para todo el personal hospitalario, desde enfermeros de piso hasta coordinadores generales. NurseHub está diseñado para operar 24/7/365, soportando las necesidades críticas de un hospital que nunca cierra.

Al completar el MVP, el hospital contará con un registro clínico electrónico básico, gestión digital de turnos y asignaciones, trazabilidad completa de medicamentos mediante códigos QR, y un portal de capacitación integrado que valida la disponibilidad del personal antes de asignar turnos.

## 2. Declaración del Problema

* **Problema 1 - Gestión Manual de Asignaciones:** Actualmente, la asignación de enfermeros a pacientes se realiza escribiendo en un pizarrón físico durante el cambio de turno. Esto carece de trazabilidad, historial y dificulta la auditoría de quién atendió a cada paciente en momentos específicos.

* **Problema 2 - Registros Clínicos en Papel:** Los signos vitales, balances de líquidos, escalas de valoración y notas de enfermería se registran en "folders plastificados" físicos. Estos registros son propensos a pérdida, deterioro, difíciles de consultar y no permiten análisis de tendencias automáticos.

* **Problema 3 - Falta de Trazabilidad de Medicamentos:** Los medicamentos se toman manualmente de un almacén, se cortan con tijeras de tiras compartidas, y se registran en papel. No hay forma eficiente de rastrear qué enfermero suministró qué medicamento, a qué paciente, en qué momento, ni de validar que sea el medicamento correcto.

* **Problema 4 - Ausencia de Control de Inventario en Tiempo Real:** El control de insumos y medicamentos es manual. Las alertas de stock bajo son reactivas en lugar de preventivas, lo que puede generar desabasto en áreas críticas.

* **Problema 5 - Gestión de Capacitación Descentralizada:** Los registros de cursos, certificaciones, evaluaciones de desempeño y becas del personal de enfermería se llevan de forma independiente, sin integración con los perfiles profesionales del personal ni con la asignación de turnos.

* **Problema 6 - Imposibilidad de Análisis de Datos:** La falta de digitalización impide generar reportes de calidad, indicadores de desempeño, o identificar patrones que podrían mejorar la atención al paciente.

## 3. Visión y Solución Propuesta

**Visión:** Transformar al Hospital Casa Alivio del Sufrimiento en un referente de eficiencia operativa y calidad en la atención de enfermería mediante la digitalización completa de sus procesos, permitiendo al personal médico y administrativo enfocarse en lo más importante: el cuidado humano y compasivo de los pacientes.

**Solución:** Desarrollar **NurseHub**, una plataforma web modular que digitaliza los 6 procesos críticos identificados:

1.  **Módulo 0 - Configuración Hospitalaria:** Sistema de gestión de áreas (Urgencias, UCI, Cirugía, etc.), pisos, cuartos y camas con estados en tiempo real (Libre, Ocupada, En Limpieza). Incluye definición de indicadores operativos por área (ej: ratio enfermero-paciente).

2.  **Módulo 1 - Registro Clínico Electrónico (RCE):** Admisión digital de pacientes, sistema TRIAGE automatizado con override manual, hoja de enfermería electrónica para registro de signos vitales, balances de líquidos, escalas de valoración (EVA-dolor, Braden, Norton), diagnósticos de enfermería y planes de cuidado.

3.  **Módulo 2 - Gestión de Personal de Enfermería:** Perfiles profesionales con certificaciones, habilidades y historial de capacitaciones. Sistema de roles y permisos granular (Coordinador General, Jefe de Piso, Enfermero de Base, etc.). Gestión de tipo de asignación (fijo/rotativo) e historial completo de rotaciones.

4.  **Módulo 3 - Asignación de Pacientes y Turnos:** Dashboard visual de asignación por piso/área con funcionalidad drag-and-drop. Asignación inteligente basada en carga de trabajo y habilidades. Sistema de relevo de turno digital con firmas electrónicas. Validación automática de disponibilidad considerando capacitaciones programadas. Notificaciones automáticas al personal sobre sus pacientes asignados.

5.  **Módulo 4 - Farmacia e Insumos:** Catálogo central de medicamentos (Fármaco → SKU → Lote) con códigos QR/Barras. Trazabilidad completa: al escanear el QR del medicamento y de la pulsera del paciente, el sistema valida la prescripción médica, registra quién suministró qué y cuándo, y descuenta automáticamente del inventario. Alertas de stock bajo preventivas.

6.  **Módulo 5 - Capacitación y Desarrollo:** Portal integrado de gestión de capacitaciones (cursos, becas, campañas, pláticas) con inscripción dual (autoservicio del enfermero o asignación por Jefe de Capacitación). Dashboard personalizado para cada enfermero mostrando capacitaciones disponibles, inscritas y completadas. Registro de asistencia por día/sesión. **Validación crítica:** El sistema bloquea automáticamente la asignación de turnos durante las horas/días en que un enfermero está inscrito en actividades de capacitación.

## 4. Perfiles de Usuario (Target Audience)

* **Enfermero de Piso ("Buen Samaritano"):** Personal operativo que atiende pacientes directamente. Necesita consultar su asignación diaria, registrar signos vitales, suministrar medicamentos, documentar cuidados, y ver/inscribirse en capacitaciones disponibles. Espera una interfaz móvil-friendly para usar desde tablets durante sus rondas.

* **Enfermera de Urgencias ("Holaaaaa Enfermeeera"):** Personal del área de Urgencias que realiza admisión de pacientes, toma de signos vitales iniciales, clasificación TRIAGE y preparación para médicos de guardia. Requiere formularios rápidos y eficientes dado el alto volumen de pacientes.

* **Jefe de Piso/Área (Ej: "Lula Enfermera" - Pediatría):** Supervisor de un piso o área específica. Responsable de asignar enfermeros a pacientes durante el cambio de turno, supervisar el desempeño, gestionar inventario local y reportar a coordinación general. El sistema debe alertarle si intenta asignar a un enfermero que está en capacitación. Necesita dashboards visuales, capacidad de reasignación rápida y reportes de su área.

* **Coordinador General de Enfermería ("La Planchada"):** Máxima autoridad del cuerpo de enfermería. Supervisa todas las áreas, gestiona rotaciones de personal, aprueba asignaciones críticas, revisa indicadores hospitalarios y toma decisiones estratégicas. Requiere vistas panorámicas del hospital completo, reportes ejecutivos y control total del sistema.

* **Jefe de Capacitación ("Patch Addams"):** Responsable de programas de desarrollo profesional, evaluaciones de desempeño, cursos, becas y campañas de sensibilización. Necesita herramientas para publicar convocatorias, gestionar inscripciones (tanto autoservicio como asignación manual), registrar asistencias diarias, y generar reportes de capacitación. Al inscribir a un enfermero, el sistema automáticamente lo marca como "no disponible" para turnos en esas fechas/horas.

* **Personal Administrativo:** Encargados de admisión, asignación de camas, gestión de inventarios centrales y facturación. Requieren acceso a módulos específicos según su rol.

## 5. Objetivos Principales (Goals)

* **Para el Hospital/Organización:**
    * [ ] **Reducir errores médicos en un 40%** mediante trazabilidad completa de medicamentos y validación automática de prescripciones médicas antes del suministro.
    * [ ] **Aumentar la eficiencia operativa en un 30%** al eliminar procesos manuales (escritura en pizarrones, búsqueda de folders físicos, conteo manual de inventarios).
    * [ ] **Cumplir con estándares de acreditación hospitalaria** que requieren registros clínicos electrónicos, trazabilidad de medicamentos y auditorías documentadas.
    * [ ] **Generar reportes de calidad en tiempo real** para toma de decisiones estratégicas basadas en datos (indicadores de carga de trabajo, tiempos de atención, uso de insumos).
    * [ ] **Aumentar participación en capacitaciones en un 50%** mediante un portal accesible con inscripción autoservicio y visibilidad de todas las oportunidades de desarrollo.

* **Para el Usuario (Personal de Enfermería):**
    * [ ] **Reducir el tiempo de documentación en un 50%** mediante formularios digitales precargados, validaciones automáticas y eliminación de duplicación de registros.
    * [ ] **Mejorar la visibilidad de asignaciones y responsabilidades** para que cada enfermero sepa exactamente qué pacientes debe atender sin depender de un pizarrón físico.
    * [ ] **Facilitar el acceso a oportunidades de desarrollo profesional** mediante un portal centralizado de capacitaciones y becas, con registro automático en su expediente profesional.
    * [ ] **Evitar conflictos de horario** al impedir que se les asigne turno cuando están programados para capacitaciones.

## 6. Alcance del Proyecto (Scope)

### Funcionalidades INCLUIDAS (Producto Mínimo Viable - MVP)

* **Módulo 0 - Configuración Hospitalaria:**
    * CRUD de Áreas del hospital (Urgencias, UCI, Cirugía, Hospitalización, etc.)
    * CRUD de Pisos, Cuartos y Camas
    * Estados de cama: Libre, Ocupada, En Limpieza, En Mantenimiento
    * Visualización tipo "mapa del hospital" por piso

* **Módulo 1 - Registro Clínico Electrónico (RCE):**
    * Admisión de pacientes con datos demográficos básicos
    * TRIAGE automatizado con sugerencia de nivel (Rojo, Naranja, Amarillo, Verde, Azul) basado en signos vitales iniciales
    * Hoja de enfermería digital con registro de:
        * Signos vitales (PA, FC, FR, Temp, SpO2, Glucosa)
        * Balance de líquidos (ingestas, orina, evacuaciones)
        * Escalas de valoración: EVA-dolor, Braden (úlceras por presión)
    * Historial cronológico del paciente desde admisión hasta alta
    * Visualización de gráficos de tendencias de signos vitales

* **Módulo 2 - Gestión de Personal de Enfermería:** ✅ **Parcialmente Implementado** (2025-10-09)
    * ✅ CRUD de usuarios con gestión de roles (Admin, Coordinador, Jefe de Piso, Enfermero, Jefe de Capacitación)
    * ✅ CRUD de perfiles de enfermeros con datos profesionales (cédula, certificaciones)
    * ✅ Relación 1:1 automática User-Enfermero con creación/actualización/eliminación sincronizada
    * ✅ Asignación de tipo: Fijo (a un área específica) o Rotativo
    * ✅ Validaciones condicionales basadas en rol (campos de enfermero solo si role='enfermero')
    * ✅ Toggle de activación/desactivación de usuarios
    * ✅ Gestión de passwords con actualización opcional en edición
    * [ ] Registro de habilidades/certificaciones específicas (ej: "Manejo de Paciente Pediátrico") - Pendiente para v1.1

* **Módulo 3 - Asignación de Pacientes y Turnos:**
    * Programación de turnos: Matutino (8-19), Nocturno (19-8)
    * Dashboard de asignación con lista de enfermeros disponibles y pacientes hospitalizados
    * **Validación de disponibilidad:** El sistema verifica automáticamente si un enfermero está inscrito en capacitación antes de permitir su asignación
    * Asignación manual (drag-and-drop o selección)
    * Sistema de relevo de turno con checklist de pacientes transferidos y firma digital
    * Vista personalizada para enfermeros: "Mis pacientes del día"

* **Módulo 4 - Farmacia e Insumos (Versión Básica):**
    * Catálogo de medicamentos: Fármaco → SKU (presentaciones) → Lote
    * Generación de códigos QR para cada SKU y para pulseras de pacientes
    * Flujo de suministro con escaneo de QR:
        1. Escanear medicamento
        2. Escanear pulsera del paciente
        3. Sistema valida prescripción médica
        4. Registra quién suministró, qué, cuándo
        5. Descuenta automáticamente del inventario
    * Alertas básicas de stock bajo

* **Módulo 5 - Capacitación y Desarrollo:**
    * CRUD de actividades de capacitación (cursos, becas, campañas, pláticas) con:
        * Nombre, descripción, tipo de actividad
        * Fechas/horarios (inicio, fin, días específicos, horario por sesión)
        * Capacidad máxima de participantes
        * Horas de capacitación otorgadas
    * **Doble flujo de inscripción:**
        * Autoservicio: Enfermero visualiza convocatorias abiertas y se inscribe
        * Manual: Jefe de Capacitación inscribe directamente al personal
    * **Dashboard del Enfermero con:**
        * Capacitaciones disponibles (abiertas a inscripción)
        * Capacitaciones inscritas (próximas y en curso)
        * Capacitaciones completadas (historial)
    * **Registro de asistencia por día/sesión:**
        * Jefe de Capacitación puede marcar asistencia diaria
        * Cálculo automático de % de asistencia mínima para aprobar (ej: 80%)
    * **Actualización automática del perfil:** Al completar un curso, se añade la certificación/habilidad al perfil del enfermero
    * **Validación crítica de turnos:**
        * Si un enfermero está inscrito en capacitación con fechas/horas específicas, el sistema lo marca como "NO DISPONIBLE" para asignación de turnos en esos horarios
        * Al intentar asignar, el sistema muestra alerta: "Enfermero no disponible - En capacitación: [Nombre del curso] del [fecha] al [fecha]"

### Funcionalidades EXCLUIDAS (Fuera de Alcance para la v1.0)

* **Prescripción médica electrónica:** En v1.0, los médicos seguirán prescribiendo de forma tradicional. El sistema solo validará que exista una prescripción registrada manualmente por enfermería antes del suministro.

* **Integración con sistemas de laboratorio o imagenología:** Los resultados de estudios se seguirán gestionando externamente.

* **Facturación y cobranza:** Fuera del alcance del MVP. Se enfoca solo en operaciones de enfermería.

* **Mobile App Nativa:** El MVP será responsive y usable desde navegadores móviles, pero no habrá una app nativa iOS/Android.

* **Telemedicina o consulta remota:** No aplica para este proyecto hospitalario.

* **IA para predicción de complicaciones médicas:** Se considerará para futuras versiones, pero no en el MVP.

* **Gestión de quirófanos y cirugías:** Aunque el hospital tiene el servicio de Cirugía, la programación quirúrgica está fuera del alcance de v1.0.

* **Evaluaciones de desempeño automatizadas:** En v1.0 solo se registrará la asistencia a capacitaciones. Las evaluaciones formales de desempeño se implementarán en versiones futuras.

## 7. Stack Tecnológico

* **Backend:** Laravel 12 (PHP 8.3+)
* **Frontend:** Livewire 3.x con Blade Templates (stack Breeze-Livewire)
* **CSS Framework:** Tailwind CSS v4 (sin PostCSS, integrado con Vite)
* **Bundler:** Vite 5.x
* **Animaciones:** GSAP (para microinteracciones y transiciones suaves)
* **Panel de Admin:** NO se usará Laravel Nova. Se construirán vistas personalizadas con Livewire.
* **Autenticación:** Laravel Breeze (con sistema de roles y permisos personalizados)
* **Base de Datos:** MySQL 8.0+
* **Servidor:** Apache (XAMPP/WAMP en desarrollo, Apache/Nginx en producción)
* **Generación de QR:** Librería PHP `simple-qrcode` o `bacon/bacon-qr-code`
* **Internacionalización:** Laravel Lang (ES como idioma principal)
* **Control de Versiones:** Git + GitHub
* **Gestión de Proyecto:** GitHub Projects (metodología AGENTS.md)

## 8. Stakeholders Clave

* **Propietario del Producto:** Dirección Administrativa del Hospital Casa Alivio del Sufrimiento
* **Coordinador General de Enfermería ("La Planchada"):** Usuario experto y validador principal de flujos de trabajo
* **Líder de Desarrollo:** @eddndev
* **Jefes de Área/Piso:** Validadores de módulos de asignación y registro clínico (representantes: "Lula Enfermera" de Pediatría)
* **Personal de Enfermería Operativo:** Testers de usabilidad y UX ("Buen Samaritano", "Holaaaaa Enfermeeera")
* **Jefe de Capacitación ("Patch Addams"):** Validador del Módulo 5 y gestión de desarrollo profesional

## 9. Métricas de Éxito

* **Tasa de Adopción:** 80% del personal de enfermería usando el sistema activamente dentro de los primeros 3 meses post-lanzamiento.
* **Reducción de Tiempo de Documentación:** Disminución del 50% en el tiempo promedio de registro de signos vitales por paciente (de ~5 minutos a ~2.5 minutos).
* **Trazabilidad de Medicamentos:** 100% de los suministros de medicamentos controlados registrados digitalmente con validación de prescripción médica.
* **Participación en Capacitaciones:** Aumento del 50% en inscripciones a cursos/actividades de desarrollo profesional vs. sistema manual previo.
* **Satisfacción del Usuario:** NPS (Net Promoter Score) superior a 50 entre el personal de enfermería en encuestas trimestrales.
* **Disponibilidad del Sistema:** Uptime del 99.5% durante horarios críticos (no más de 3.6 horas de downtime mensual).
* **Errores de Medicación:** Reducción del 40% en incidentes reportados de medicamento incorrecto o dosis incorrecta en los primeros 6 meses.
* **Conflictos de Horario:** 0% de asignaciones de turno a enfermeros durante sus horas de capacitación programada.

## 10. Línea de Tiempo Estimada

* **Fase 1 - Infraestructura y Módulo 0:** 2 semanas - Setup del proyecto, configuración de BD, CRUD de áreas/pisos/camas, sistema de autenticación y roles.
* **Fase 2 - Módulo 1 (RCE Básico):** 3 semanas - Admisión de pacientes, TRIAGE, hoja de enfermería con signos vitales y escalas básicas.
* **Fase 3 - Módulos 2 y 3 (Personal y Asignación):** 3 semanas - Gestión de enfermeros, turnos, asignación manual, relevo de turno, validación de disponibilidad.
* **Fase 4 - Módulo 4 (Farmacia Básica):** 2 semanas - Catálogo de medicamentos, generación de QR, flujo de suministro con validación.
* **Fase 5 - Módulo 5 (Capacitación Integrada):** 2 semanas - Portal de capacitación con doble flujo de inscripción, registro de asistencia, validación de turnos, dashboard de enfermero.
* **Fase 6 - Testing y Capacitación:** 1 semana - Pruebas con usuarios reales, capacitación al personal, ajustes finales.
* **Lanzamiento MVP:** 13 semanas desde el inicio del desarrollo (aproximadamente 3 meses)

## 11. Riesgos y Consideraciones

* **Riesgo 1 - Resistencia al cambio por parte del personal:** Muchos enfermeros están acostumbrados a registros en papel. **Mitigación:** Capacitación intensiva, diseño de UX intuitivo, incorporar feedback del personal desde sprints tempranos, y designar "champions" (enfermeros líderes que adopten primero y evangelicen).

* **Riesgo 2 - Falta de conectividad o dispositivos:** El hospital puede tener zonas con WiFi débil o falta de tablets suficientes. **Mitigación:** Diseño responsive que funcione en cualquier dispositivo (incluyendo smartphones personales), optimización de carga de datos para conexiones lentas, modo offline básico para registros críticos (sincronización posterior).

* **Riesgo 3 - Complejidad del modelo de datos:** El esquema de base de datos será extenso (pacientes, enfermeros, turnos, asignaciones, medicamentos, lotes, capacitaciones, asistencias, etc.). **Mitigación:** Documentación exhaustiva en `03-database-schema.md`, uso de migraciones de Laravel, y pruebas de integridad referencial constantes.

* **Riesgo 4 - Requisitos médico-legales:** Los registros clínicos electrónicos pueden tener implicaciones legales (auditorías, demandas). **Mitigación:** Implementar logs de auditoría inmutables (quién modificó qué, cuándo), firmas digitales en relevos de turno, y respaldos diarios de la base de datos.

* **Riesgo 5 - Escalabilidad:** El hospital opera 24/7 con múltiples usuarios concurrentes. **Mitigación:** Pruebas de carga desde fases tempranas, optimización de queries con índices de BD, uso de cache (Redis en producción), y plan de escalamiento horizontal si crece el hospital.

* **Riesgo 6 - Conflictos de lógica de validación de turnos:** La validación automática que impide asignar enfermeros en capacitación debe ser robusta para evitar bloqueos incorrectos. **Mitigación:** Testing exhaustivo de edge cases (capacitaciones que se solapan, cancelaciones, inscripciones de último minuto), y permitir override manual con justificación para casos excepcionales de emergencia.

---

**Notas Finales:**

Este manifiesto es un documento vivo. A medida que se descubran nuevos requisitos o se ajuste el alcance durante los sprints, este documento deberá actualizarse. Cualquier cambio debe ser aprobado por los stakeholders clave y documentado en la sección de "Registro de Decisiones Técnicas" del sprint correspondiente en `/docs/sprints/`.

El éxito de NurseHub depende de la colaboración estrecha entre el equipo de desarrollo y el personal hospitalario. La filosofía Docs-First y la metodología AGENTS.md aseguran que cada decisión esté documentada, cada funcionalidad esté justificada, y cada línea de código tenga un propósito claro alineado con la visión del proyecto.
