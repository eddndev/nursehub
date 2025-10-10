# Subsistema de Gestión de Contexto Dinámico (DCM)

Este directorio contiene un sistema autónomo en Python para crear prompts de alta calidad y ricos en contexto para agentes de IA, evitando la sobrecarga de la ventana de contexto de los Modelos de Lenguaje Grandes (LLMs).

## El Problema que Resuelve

Los LLMs tienen una ventana de contexto limitada. Alimentarlos con documentos enteros es ineficiente y costoso. Este sistema soluciona ese problema mediante un enfoque de **Recuperación y Síntesis (Retrieve & Synthesize)**: en lugar de "rellenar" el contexto, busca de forma inteligente la información más relevante de una base de conocimiento y la sintetiza en un prompt conciso y denso en información.

## Arquitectura

El sistema utiliza un enfoque de **Búsqueda Híbrida** en 3 fases:

1.  **Fragmentación (Chunking):** Los documentos fuente se dividen en fragmentos lógicos.
2.  **Indexación Dual:** Se crean dos índices a partir de los fragmentos:
    *   Un **índice de palabras clave** para búsquedas literales y precisas.
    *   Una **base de datos vectorial** para búsquedas semánticas (por significado).
3.  **Búsqueda Híbrida y Re-ranking:** Para una consulta dada, el sistema busca en ambos índices, y luego fusiona los resultados utilizando un algoritmo de Fusión de Rango Recíproco (RRF) para obtener la lista de fragmentos más relevante posible.

## Componentes

- `requirements.txt`: Lista de dependencias de Python.
- `source_docs/` (Recomendado): Carpeta donde deberías colocar tus archivos de documentación (`.md`).
- `01_chunker.py`: Lee los documentos de `../docs/` y los convierte en `chunks.json`.
- `04_vectorize.py`: Lee `chunks.json` y crea una base de datos vectorial en la carpeta `vector_db/`.
- `main.py`: El orquestador principal. Recibe una consulta, ejecuta la búsqueda híbrida y genera el prompt final.

---

## ¿Cómo Usarlo?

### 1. Prerrequisitos

- Python 3.9+
- `pip` para instalar paquetes

### 2. Instalación

Navega a este directorio en tu terminal y ejecuta:

```bash
pip install -r requirements.txt
```

### 3. Configuración de la Documentación

Este script está configurado para buscar documentos en el directorio `../docs/` (un nivel arriba y dentro de `docs/`).

Para usarlo en otro proyecto, asegúrate de que tus documentos `.md` estén en esa ruta o modifica la variable `docs_path` dentro de `01_chunker.py`.

### 4. Generar la Base de Conocimiento

Debes ejecutar estos dos scripts la primera vez o cada vez que tu documentación cambie significativamente.

**a) Crear los fragmentos (chunks):**
```bash
python 01_chunker.py
```

**b) Crear la base de datos vectorial:**
```bash
python 04_vectorize.py
```
*(Nota: La primera vez, esto puede tardar un poco mientras descarga el modelo de embeddings (~90MB).)*

### 5. Realizar una Consulta

Una vez que la base de conocimiento está generada, puedes realizar consultas para generar prompts.

```bash
python main.py "Tu pregunta o tarea para el agente de IA"
```

**Ejemplo:**
```bash
python main.py "Implementar un sistema de autenticación con roles"
```

El sistema ejecutará la búsqueda híbrida y mostrará en la terminal un prompt completo y listo para ser copiado y pegado en tu agente de IA.

## Configuración Avanzada

Puedes ajustar el comportamiento del sistema modificando las constantes al principio de los scripts:

- **`main.py`**:
  - `TOP_N_RESULTS`: Número de fragmentos de contexto a incluir en el prompt final.
  - `RRF_K`: Constante del algoritmo de re-ranking. Un valor más alto le da más peso a los resultados de menor rango.
- **`04_vectorize.py`**:
  - `MODEL_NAME`: Puedes cambiar `all-MiniLM-L6-v2` por cualquier otro modelo de embeddings compatible de Sentence-Transformers.
