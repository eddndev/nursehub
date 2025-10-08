# Sistema de Diseño: NurseHub

**Versión:** 1.0
**Fecha:** 2025-10-07
**Filosofía:** Diseño médico profesional, limpio y accesible con soporte completo para modo claro y oscuro

---

## 1. Paleta de Colores

**Filosofía de Color:** NurseHub utiliza una paleta inspirada en el sector médico con tonos azules y cian que transmiten confianza, profesionalismo y calma. Los colores claros evocan limpieza y vitalidad, mientras que el modo oscuro facilita el trabajo nocturno del personal hospitalario sin fatiga visual. Todos los colores cumplen con WCAG 2.1 AA para garantizar accesibilidad.

### Colores Primarios (Medical Blue)
**Uso:** Elementos principales de UI, CTAs primarios, navegación, estados activos

```
- blue-50:  #eff6ff
- blue-100: #dbeafe
- blue-200: #bfdbfe
- blue-300: #93c5fd
- blue-400: #60a5fa
- blue-500: #3b82f6  ← Color principal
- blue-600: #2563eb
- blue-700: #1d4ed8
- blue-800: #1e40af
- blue-900: #1e3a8a
- blue-950: #172554
```

![blue-50](https://img.shields.io/badge/blue--50-eff6ff?style=for-the-badge&logoColor=black&color=eff6ff)
![blue-500](https://img.shields.io/badge/blue--500-3b82f6?style=for-the-badge&logoColor=white&color=3b82f6)
![blue-700](https://img.shields.io/badge/blue--700-1d4ed8?style=for-the-badge&logoColor=white&color=1d4ed8)

### Colores de Acento (Vital Cyan)
**Uso:** Elementos interactivos secundarios, badges de estado activo, notificaciones de nueva información

```
- cyan-50:  #ecfeff
- cyan-100: #cffafe
- cyan-200: #a5f3fc
- cyan-300: #67e8f9
- cyan-400: #22d3ee
- cyan-500: #06b6d4  ← Color de acento principal
- cyan-600: #0891b2
- cyan-700: #0e7490
- cyan-800: #155e75
- cyan-900: #164e63
```

![cyan-50](https://img.shields.io/badge/cyan--50-ecfeff?style=for-the-badge&logoColor=black&color=ecfeff)
![cyan-500](https://img.shields.io/badge/cyan--500-06b6d4?style=for-the-badge&logoColor=white&color=06b6d4)
![cyan-700](https://img.shields.io/badge/cyan--700-0e7490?style=for-the-badge&logoColor=white&color=0e7490)

### Colores Neutrales (Slate)
**Uso:** Textos, fondos, bordes, elementos de UI no interactivos

```
- slate-50:  #f8fafc
- slate-100: #f1f5f9
- slate-200: #e2e8f0
- slate-300: #cbd5e1
- slate-400: #94a3b8
- slate-500: #64748b
- slate-600: #475569
- slate-700: #334155
- slate-800: #1e293b
- slate-900: #0f172a
- slate-950: #020617
```

![slate-50](https://img.shields.io/badge/slate--50-f8fafc?style=for-the-badge&logoColor=black&color=f8fafc)
![slate-500](https://img.shields.io/badge/slate--500-64748b?style=for-the-badge&logoColor=white&color=64748b)
![slate-900](https://img.shields.io/badge/slate--900-0f172a?style=for-the-badge&logoColor=white&color=0f172a)

### Colores Semánticos
**Uso:** Feedback del sistema, alertas, estados de TRIAGE, validaciones

* **Éxito (`success` / Verde):** ![Success](https://img.shields.io/badge/success-10b981?style=for-the-badge&logoColor=white&color=10b981) `#10b981` - Operaciones exitosas, pacientes estables
* **Peligro (`danger` / Rojo):** ![Danger](https://img.shields.io/badge/danger-ef4444?style=for-the-badge&logoColor=white&color=ef4444) `#ef4444` - Errores críticos, TRIAGE rojo, alertas de stock
* **Advertencia (`warning` / Amarillo):** ![Warning](https://img.shields.io/badge/warning-f59e0b?style=for-the-badge&logoColor=white&color=f59e0b) `#f59e0b` - Advertencias, TRIAGE amarillo, stock bajo
* **Información (`info` / Azul claro):** ![Info](https://img.shields.io/badge/info-3b82f6?style=for-the-badge&logoColor=white&color=3b82f6) `#3b82f6` - Información general, tips, ayuda contextual

**Colores TRIAGE (específicos del sistema):**
* **TRIAGE Rojo (Crítico):** `#dc2626` - Riesgo vital inmediato
* **TRIAGE Naranja (Urgente):** `#ea580c` - Urgencia alta
* **TRIAGE Amarillo (Moderado):** `#f59e0b` - Atención prioritaria
* **TRIAGE Verde (Menor):** `#10b981` - Baja prioridad
* **TRIAGE Azul (Consulta):** `#3b82f6` - Sin urgencia

### Uso en Tema Claro vs. Oscuro

**Estrategia:** NurseHub implementa soporte completo para modo oscuro utilizando la clase `dark:` de Tailwind CSS. El sistema detecta automáticamente la preferencia del usuario o permite cambio manual. El modo oscuro prioriza reducir la fatiga visual durante turnos nocturnos.

| Uso Semántico           | Modo Claro (`light`)                                    | Modo Oscuro (`dark`)                                      |
| ----------------------- | ------------------------------------------------------- | --------------------------------------------------------- |
| **Fondo Principal**     | `bg-slate-50`                                           | `dark:bg-slate-950`                                       |
| **Fondo Tarjetas**      | `bg-white`                                              | `dark:bg-slate-900`                                       |
| **Fondo Sidebar/Nav**   | `bg-blue-600`                                           | `dark:bg-slate-900`                                       |
| **Texto Principal**     | `text-slate-900`                                        | `dark:text-slate-100`                                     |
| **Texto Secundario**    | `text-slate-600`                                        | `dark:text-slate-400`                                     |
| **Texto Terciario**     | `text-slate-500`                                        | `dark:text-slate-500`                                     |
| **Botón Primario**      | `bg-blue-600 text-white hover:bg-blue-700`              | `dark:bg-blue-600 dark:hover:bg-blue-700`                 |
| **Botón Secundario**    | `bg-slate-200 text-slate-900 hover:bg-slate-300`        | `dark:bg-slate-800 dark:text-white dark:hover:bg-slate-700` |
| **Bordes**              | `border-slate-200`                                      | `dark:border-slate-700`                                   |
| **Bordes Inputs**       | `border-slate-300`                                      | `dark:border-slate-600`                                   |
| **Hover Card**          | `hover:bg-slate-50`                                     | `dark:hover:bg-slate-800`                                 |
| **Shadow**              | `shadow-slate-200/50`                                   | `dark:shadow-slate-950/50`                                |

---

## 2. Tipografía

* **Fuente Principal:** **Inter** - Fuente sans-serif moderna, optimizada para legibilidad en pantallas médicas y dashboards. Excelente rendering en tamaños pequeños. Disponible en Google Fonts.
* **Fuente Monoespaciada:** **JetBrains Mono** - Para códigos QR, números de expediente, identificadores únicos y datos técnicos.

### Configuración en Tailwind

```css
@theme {
  --font-sans: 'Inter', ui-sans-serif, system-ui, sans-serif;
  --font-mono: 'JetBrains Mono', ui-monospace, monospace;
}
```

### Escala Tipográfica

* **h1 (Títulos principales de página):** `text-3xl md:text-4xl font-bold` (30px → 36px)
* **h2 (Secciones principales):** `text-2xl md:text-3xl font-semibold` (24px → 30px)
* **h3 (Subsecciones, encabezados de cards):** `text-xl font-semibold` (20px)
* **h4 (Subtítulos menores):** `text-lg font-medium` (18px)
* **p (Texto de cuerpo):** `text-base font-normal` (16px)
* **small (Texto secundario, labels):** `text-sm font-normal` (14px)
* **caption (Metadatos, timestamps):** `text-xs font-normal` (12px)
* **badge/pill text:** `text-xs font-medium uppercase tracking-wide` (12px)

### Pesos de Fuente

* **Light:** `font-light` (300) - Uso: textos largos con jerarquía baja
* **Normal:** `font-normal` (400) - Uso: texto de cuerpo estándar
* **Medium:** `font-medium` (500) - Uso: labels, botones secundarios
* **Semibold:** `font-semibold` (600) - Uso: encabezados, navegación activa
* **Bold:** `font-bold` (700) - Uso: títulos principales, CTAs primarios

---

## 3. Espaciado y Rejilla (Grid)

**Sistema:** Utilizamos la escala de espaciado estándar de Tailwind CSS basada en múltiplos de 4px (0.25rem).

### Escala de Espaciado

* `1` = 4px (0.25rem) - Espaciados mínimos entre elementos inline
* `2` = 8px (0.5rem) - Espaciado entre badges, iconos pequeños
* `3` = 12px (0.75rem) - Padding interno de botones pequeños
* `4` = 16px (1rem) - **Espaciado base** - Padding de inputs, margen entre párrafos
* `6` = 24px (1.5rem) - Padding de cards, espaciado entre secciones pequeñas
* `8` = 32px (2rem) - Espaciado entre secciones principales
* `12` = 48px (3rem) - Separación de módulos grandes
* `16` = 64px (4rem) - Separación de layouts principales
* `20` = 80px (5rem) - Márgenes de página en desktop

### Sistema de Grid

* **Columnas:** 12 columnas (sistema estándar de Tailwind)
* **Gaps:** `gap-4` (16px) para dashboards, `gap-6` (24px) para layouts amplios
* **Breakpoints:**
  * `sm`: ≥ 640px - Tablets verticales
  * `md`: ≥ 768px - Tablets horizontales
  * `lg`: ≥ 1024px - Laptops
  * `xl`: ≥ 1280px - Desktops
  * `2xl`: ≥ 1536px - Pantallas grandes (workstations médicas)

---

## 4. Componentes Clave

### Botones

* **Primario:**
    * **Uso:** Acciones principales (guardar, confirmar, asignar)
    * **Estilo:** `px-4 py-2 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors dark:bg-blue-600 dark:hover:bg-blue-700`

* **Secundario:**
    * **Uso:** Acciones alternativas (cancelar, regresar)
    * **Estilo:** `px-4 py-2 bg-slate-200 text-slate-900 font-medium rounded-md hover:bg-slate-300 dark:bg-slate-800 dark:text-white dark:hover:bg-slate-700 transition-colors`

* **Destructivo:**
    * **Uso:** Acciones peligrosas (eliminar, dar de alta)
    * **Estilo:** `px-4 py-2 bg-red-600 text-white font-medium rounded-md hover:bg-red-700 focus:ring-2 focus:ring-red-500 transition-colors`

* **Ghost/Terciario:**
    * **Uso:** Acciones secundarias sin peso visual
    * **Estilo:** `px-4 py-2 text-blue-600 font-medium hover:bg-blue-50 rounded-md dark:text-blue-400 dark:hover:bg-slate-800 transition-colors`

### Inputs de Formulario

* **Estilo General:**
    * `w-full px-4 py-2 border border-slate-300 rounded-md text-slate-900 placeholder:text-slate-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-900 dark:border-slate-600 dark:text-slate-100 dark:placeholder:text-slate-500 transition-all`

* **Estado de Error:**
    * Agregar: `border-red-500 focus:ring-red-500 focus:border-red-500`

* **Estado Deshabilitado:**
    * Agregar: `bg-slate-100 cursor-not-allowed opacity-60 dark:bg-slate-800`

* **Input con Icono:**
    * Contenedor: `relative`
    * Input: agregar `pl-10` (si icono a la izquierda)
    * Icono: `absolute left-3 top-1/2 -translate-y-1/2 text-slate-400`

### Tarjetas (Cards)

* **Estilo General:**
    * `bg-white rounded-lg border border-slate-200 p-6 shadow-sm dark:bg-slate-900 dark:border-slate-700`

* **Variantes:**
    * **Interactiva (clickeable):** Agregar `hover:shadow-md hover:border-slate-300 cursor-pointer transition-all dark:hover:border-slate-600`
    * **Con divisor:** Usar `border-t border-slate-200 dark:border-slate-700` entre secciones internas
    * **Compacta:** Cambiar `p-6` por `p-4`

### Badges/Etiquetas

* **Estilo Base:** `inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium`

* **Variantes por Color:**
    * **Primario:** `bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400`
    * **Éxito:** `bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400`
    * **Advertencia:** `bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400`
    * **Peligro:** `bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400`
    * **Neutral:** `bg-slate-100 text-slate-800 dark:bg-slate-800 dark:text-slate-300`

* **TRIAGE Badges:**
    * **Rojo:** `bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400 ring-1 ring-red-600/20`
    * **Naranja:** `bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-400`
    * **Amarillo:** `bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400`
    * **Verde:** `bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400`
    * **Azul:** `bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400`

### Modales/Diálogos

* **Overlay:** `fixed inset-0 bg-slate-900/50 dark:bg-slate-950/80 backdrop-blur-sm z-40`

* **Contenedor del Modal:**
    * `fixed inset-0 z-50 flex items-center justify-center p-4`
    * Card interno: `bg-white dark:bg-slate-900 rounded-lg shadow-xl max-w-md w-full`

* **Encabezado:** `px-6 py-4 border-b border-slate-200 dark:border-slate-700`

* **Cuerpo:** `px-6 py-4`

* **Footer:** `px-6 py-4 border-t border-slate-200 dark:border-slate-700 flex justify-end gap-3`

### Notificaciones/Alertas

* **Tipo Éxito:**
    * `bg-green-50 border-l-4 border-green-500 p-4 dark:bg-green-900/20 dark:border-green-500`

* **Tipo Error:**
    * `bg-red-50 border-l-4 border-red-500 p-4 dark:bg-red-900/20 dark:border-red-500`

* **Tipo Advertencia:**
    * `bg-yellow-50 border-l-4 border-yellow-500 p-4 dark:bg-yellow-900/20 dark:border-yellow-500`

* **Tipo Información:**
    * `bg-blue-50 border-l-4 border-blue-500 p-4 dark:bg-blue-900/20 dark:border-blue-500`

---

## 5. Iconografía

* **Librería de Iconos:** **Heroicons v2** (outline para UI general, solid para navegación activa)
* **Tamaño por defecto:** `w-5 h-5` (20px) para UI estándar, `w-6 h-6` (24px) para navegación
* **Estilo:** Outline por defecto, Solid para estados activos
* **Color:** Hereda del texto padre: `text-current`

### Iconos Médicos Comunes

| Acción/Contexto | Icono Heroicons | Uso |
|-----------------|-----------------|-----|
| Paciente | `UserIcon` | Perfil de paciente, listados |
| Signos vitales | `HeartIcon` | Registro de signos vitales |
| Medicamento | `BeakerIcon` | Farmacia, suministro |
| Urgencias | `BoltIcon` | Área de urgencias, prioridad alta |
| Calendario | `CalendarIcon` | Turnos, capacitaciones |
| Asignación | `UserGroupIcon` | Asignar enfermeros |
| QR Scanner | `QrCodeIcon` | Escaneo de medicamentos |
| Alerta | `ExclamationTriangleIcon` | Alertas de stock, advertencias |
| Éxito | `CheckCircleIcon` | Confirmaciones |
| Cerrar | `XMarkIcon` | Cerrar modales |
| Búsqueda | `MagnifyingGlassIcon` | Búsqueda de pacientes |
| Configuración | `Cog6ToothIcon` | Ajustes del sistema |

---

## 6. Sombras (Shadows)

* **Ninguna:** `shadow-none`
* **Sutil:** `shadow-sm` - Cards en reposo, inputs
* **Normal:** `shadow-md` - Cards con hover, dropdowns
* **Media:** `shadow-lg` - Modales, popover
* **Alta:** `shadow-xl` - Notificaciones flotantes
* **Extrema:** `shadow-2xl` - Overlays críticos (TRIAGE rojo)

---

## 7. Bordes y Esquinas Redondeadas

### Grosor de Borde

* **Estándar:** `border` (1px)
* **Medio:** `border-2` (2px) - Inputs en foco, elementos destacados
* **Grueso:** `border-4` (4px) - Alertas críticas

### Radio de Esquinas

* **Ninguno:** `rounded-none` (0px) - Elementos adheridos a bordes
* **Pequeño:** `rounded-sm` (2px) - Badges internos
* **Estándar:** `rounded-md` (6px) - **Radio por defecto** - Botones, inputs, cards
* **Grande:** `rounded-lg` (8px) - Cards principales, modales
* **Extra grande:** `rounded-xl` (12px) - Elementos destacados
* **Completo:** `rounded-full` - Avatares, badges circulares, botones circulares

**Estándar del proyecto:** Usar `rounded-md` (6px) como radio base para mantener consistencia.

---

## 8. Animaciones y Transiciones

### Duración

* **Rápida:** `duration-150` (150ms) - Hover de botones, cambios de color
* **Normal:** `duration-200` (200ms) - **Duración por defecto** - Transiciones generales
* **Media:** `duration-300` (300ms) - Modales, dropdowns
* **Lenta:** `duration-500` (500ms) - Animaciones complejas (GSAP)

### Timing Function

* **Ease-in-out:** `ease-in-out` - **Por defecto** - Natural para la mayoría de transiciones
* **Ease-out:** `ease-out` - Entradas de elementos (modales, notificaciones)
* **Ease-in:** `ease-in` - Salidas de elementos

### Propiedades Comunes

* **Colores:** `transition-colors` - Hover de botones
* **Opacidad:** `transition-opacity` - Fade in/out
* **Transform:** `transition-transform` - Escalado, rotación
* **Todo:** `transition-all` (usar solo cuando sea necesario)

### Animaciones con GSAP

**Uso:** Para animaciones complejas en dashboards (gráficos de signos vitales, transiciones de estado TRIAGE)

```javascript
// Ejemplo: Animación de gráfico de signos vitales
gsap.from('.vital-sign-chart', {
  duration: 0.8,
  opacity: 0,
  y: 20,
  stagger: 0.1,
  ease: 'power2.out'
});
```

---

## 9. Accesibilidad (A11y)

### Directrices Obligatorias

* **Contraste de Colores:** WCAG 2.1 AA mínimo (ratio 4.5:1 para texto normal, 3:1 para texto grande)
* **Focus States:** Todos los elementos interactivos DEBEN tener `focus:ring-2 focus:ring-[color]-500 focus:ring-offset-2`
* **Etiquetas ARIA:**
    * Usar `aria-label` en iconos sin texto
    * Usar `aria-describedby` para mensajes de error en formularios
    * Usar `role="alert"` para notificaciones críticas
* **Tamaños de Toque:** Mínimo 44x44px para elementos táctiles (móvil/tablet)
* **Skip Links:** Incluir "Saltar al contenido principal" en navegación
* **Keyboard Navigation:** Todos los flujos deben ser navegables con teclado (Tab, Enter, Esc)

### Lectores de Pantalla

* Usar `sr-only` para textos solo para lectores de pantalla:
  ```html
  <span class="sr-only">Cerrar modal</span>
  ```

---

## 10. Responsive Design

### Estrategia

**Enfoque:** Mobile-first - Diseñamos primero para móvil y escalamos hacia desktop

### Breakpoints y Uso

| Breakpoint | Tamaño | Dispositivo | Uso Principal |
|------------|--------|-------------|---------------|
| `sm` | ≥ 640px | Teléfonos grandes | Ajustar padding, columnas 1→2 |
| `md` | ≥ 768px | Tablets | Sidebar colapsable, grids 2→3 cols |
| `lg` | ≥ 1024px | Laptops | Navegación expandida, dashboards completos |
| `xl` | ≥ 1280px | Desktops | Grids 3→4 cols, máxima densidad de información |
| `2xl` | ≥ 1536px | Workstations médicas | Layout optimizado para pantallas duales |

### Consideraciones Especiales

* **Modo Tablet (md):** El sidebar de navegación se colapsa a drawer/hamburger
* **Dashboards:** En móvil, las cards se apilan verticalmente. En desktop (lg+), usar grid de 2-3 columnas
* **Tablas:** En móvil, usar cards verticales. En tablet+, mostrar tabla completa con scroll horizontal si es necesario
* **Formularios:** Labels arriba del input en móvil, al lado (inline) en desktop si el espacio lo permite

---

## 11. Tokens de Diseño (Design Tokens)

**Implementación en Tailwind CSS v4:**

```css
/* resources/css/app.css */
@import "tailwindcss";

@theme {
  /* Fuentes */
  --font-sans: 'Inter', ui-sans-serif, system-ui, sans-serif;
  --font-mono: 'JetBrains Mono', ui-monospace, monospace;

  /* Colores personalizados (si se necesitan adicionales a Tailwind) */
  --color-triage-red: #dc2626;
  --color-triage-orange: #ea580c;
  --color-triage-yellow: #f59e0b;
  --color-triage-green: #10b981;
  --color-triage-blue: #3b82f6;

  /* Bordes */
  --radius-base: 6px;

  /* Sombras personalizadas */
  --shadow-card: 0 1px 3px 0 rgb(0 0 0 / 0.1);
}
```

---

## 12. Recursos y Referencias

### Fuentes

* **Inter:** [Google Fonts](https://fonts.google.com/specimen/Inter)
* **JetBrains Mono:** [Google Fonts](https://fonts.google.com/specimen/JetBrains+Mono)

### Iconos

* **Heroicons:** [heroicons.com](https://heroicons.com)

### Herramientas de Diseño

* **Paleta de colores:** [Tailwind CSS Colors](https://tailwindcss.com/docs/customizing-colors)
* **Contraste checker:** [WebAIM Contrast Checker](https://webaim.org/resources/contrastchecker/)
* **Generador de sombras:** [Tailwind CSS Shadow Generator](https://www.tailwindshades.com)

### Documentación Técnica

* **Tailwind CSS v4:** [tailwindcss.com/docs](https://tailwindcss.com/docs)
* **GSAP:** [greensock.com/docs](https://greensock.com/docs)
* **Livewire:** [livewire.laravel.com](https://livewire.laravel.com)

---

## Notas Finales

Este sistema de diseño es un documento vivo que evolucionará con el proyecto. Cualquier cambio en tokens, componentes o patrones debe:

1. Documentarse aquí primero (Docs-First)
2. Ser aprobado por el equipo de desarrollo
3. Implementarse de forma consistente en toda la aplicación

**Principios de diseño de NurseHub:**
- **Claridad sobre complejidad:** La información médica debe ser clara e inmediata
- **Consistencia en la UI:** Los patrones visuales se repiten para reducir la curva de aprendizaje
- **Accesibilidad primero:** El diseño debe funcionar para todo el personal, incluyendo condiciones de baja iluminación (modo oscuro) y accesibilidad visual
- **Performance:** Animaciones y transiciones deben ser suaves pero no afectar el rendimiento en dispositivos de gama media