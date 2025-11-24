# Sprint 3: Herramientas Avanzadas de Cuidado

**Epic:** Epic #2 - Módulo RCE (Registro Clínico Electrónico) - Fase 2
**Duración:** 2 semanas
**Fecha de inicio:** 2025-12-13
**Fecha de finalización:** 2025-12-27
**Estado:** En Progreso

---

## 1. Objetivos del Sprint

### Objetivo Principal
Dotar al personal de enfermería de herramientas digitales avanzadas para la valoración integral del paciente, control estricto de líquidos y planificación de cuidados, eliminando por completo los registros auxiliares en papel.

### Objetivos Específicos
1. Implementar el módulo de Balance de Líquidos (Ingresos y Egresos) con cálculo automático.
2. Digitalizar las escalas de valoración clínica estándar (EVA, Braden, Norton, Glasgow).
3. Crear el sistema de Diagnósticos de Enfermería y Planes de Cuidado.
4. Generar reportes consolidados por turno (SBAR/entrega de guardia).

### Métricas de Éxito
- Cálculo automático de balance hídrico en tiempo real (cero errores aritméticos).
- Reducción del tiempo de documentación de escalas en un 50% vs papel.
- Visualización clara de alertas de riesgo (ej: Alto riesgo de úlceras por presión).

---

## 2. Alcance del Sprint

### Historias de Usuario

#### **Control de Líquidos**
- [x] US-RCE-016: Registrar ingresos de líquidos (Vía oral, Parenteral, Enteral) con tipo y volumen.
- [x] US-RCE-017: Registrar egresos de líquidos (Orina, Evacuaciones, Drenajes, Vómito) con características.
- [x] US-RCE-018: Visualizar balance acumulado por turno (Matutino/Vespertino/Nocturno) y 24h.
- [x] US-RCE-019: Configurar metas de balance hídrico por paciente.

#### **Escalas de Valoración**
- [x] US-RCE-020: Realizar valoración de dolor (Escala EVA) y registrar en historial.
- [x] US-RCE-021: Realizar valoración de riesgo de úlceras (Escala Braden) con cálculo de puntaje automático.
- [x] US-RCE-022: Realizar valoración de nivel de conciencia (Escala Glasgow).
- [x] US-RCE-023: Visualizar gráfico de evolución de puntajes de escalas. (Implementado como lista histórica)

#### **Planes de Cuidado**
- [x] US-RCE-024: Seleccionar diagnósticos de enfermería predefinidos.
- [x] US-RCE-025: Asignar intervenciones de cuidado a un diagnóstico.
- [x] US-RCE-026: Marcar intervenciones como realizadas durante el turno.

---

## 3. Arquitectura Técnica

### 3.1 Nuevos Modelos

#### **BalanceLiquido**
- `paciente_id`
- `tipo` (ingreso/egreso)
- `via` (oral, iv, sonda / orina, deposición, etc.)
- `solucion` (tipo de líquido)
- `volumen_ml`
- `fecha_hora`
- `turno` (M/V/N)
- `registrado_por`

#### **ValoracionEscala**
- `paciente_id`
- `tipo_escala` (EVA, BRADEN, GLASGOW)
- `puntaje_total`
- `detalle_json` (valores de cada ítem de la escala)
- `riesgo_interpretado` (Bajo, Medio, Alto)
- `fecha_hora`
- `registrado_por`

### 3.2 Componentes Livewire Sugeridos
- `ControlLiquidos`: Tabla interactiva con inputs rápidos.
- `CalculadoraEscalas`: Wizard paso a paso para llenar escalas complejas (Braden).
- `PlanCuidadosManager`: Gestor de diagnósticos e intervenciones.

---

## 4. Riesgos y Dependencias

- **Dependencia:** Requiere que el paciente esté admitido (Sprint 2).
- **Riesgo:** La lista de diagnósticos de enfermería es muy extensa.
  - *Mitigación:* Implementar solo los 20 diagnósticos más comunes inicialmente.
- **Riesgo:** Interfaz compleja para balance de líquidos en móviles.
  - *Mitigación:* Diseño 'Mobile-First' simplificado con botones grandes para ingresos rápidos.

---