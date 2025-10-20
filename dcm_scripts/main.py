
import sys
import json
import re
import chromadb
import os
from sentence_transformers import SentenceTransformer

# --- Configuration ---
SCRIPT_DIR = os.path.dirname(__file__)
DB_PATH = os.path.join(SCRIPT_DIR, 'vector_db')
CHUNKS_FILE = os.path.join(SCRIPT_DIR, 'chunks.json')
COLLECTION_NAME = 'nursehub_docs'
MODEL_NAME = 'all-MiniLM-L6-v2'
TOP_N_RESULTS = 5
RRF_K = 60

_model_cache = None

def get_embedding_model():
    global _model_cache
    if _model_cache is None:
        print(f"Loading embedding model for the first time: {MODEL_NAME}")
        _model_cache = SentenceTransformer(MODEL_NAME, device='cpu')
        print("Model loaded.")
    return _model_cache

def run_keyword_search(query, chunks):
    """Performs a simple keyword search on the provided chunks."""
    print("--- [Sub-step] Running Keyword Search ---")
    query_words = set(re.findall(r'\w+', query.lower()))
    if not query_words: return []

    ranked_chunks = []
    for i, chunk in enumerate(chunks):
        chunk_content_lower = chunk.get('content', '').lower()
        match_count = sum(1 for word in query_words if word in chunk_content_lower)
        
        if match_count > 0:
            score = match_count / len(query_words)
            chunk['id'] = f"chunk_{i}"
            chunk['keyword_score'] = score
            ranked_chunks.append(chunk)

    ranked_chunks.sort(key=lambda x: x['keyword_score'], reverse=True)
    return ranked_chunks

def run_semantic_search(query):
    """Performs a semantic search against the ChromaDB vector store."""
    print("--- [Sub-step] Running Semantic Search ---")
    try:
        client = chromadb.PersistentClient(path=DB_PATH)
        collection = client.get_collection(name=COLLECTION_NAME)
        model = get_embedding_model()
        
        query_embedding = model.encode(query).tolist()
        
        results = collection.query(
            query_embeddings=[query_embedding],
            n_results=TOP_N_RESULTS * 2
        )
        
        retrieved_chunks = []
        if results and results['ids']:
            for i, doc_id in enumerate(results['ids'][0]):
                # We need the original chunk ID to fetch relations later
                chunk_id_num = int(doc_id.split('_')[1])
                chunk = {
                    'id': f"chunk_{chunk_id_num}",
                    'content': results['documents'][0][i],
                    'metadata': results['metadatas'][0][i],
                    'semantic_score': results['distances'][0][i]
                }
                retrieved_chunks.append(chunk)
        
        retrieved_chunks.sort(key=lambda x: x['semantic_score'])
        return retrieved_chunks

    except Exception as e:
        print(f"An error occurred during semantic search: {e}")
        return []

def hybrid_search_and_rerank(query):
    """Runs both searches and re-ranks results using Reciprocal Rank Fusion."""
    print("--- [STEP 1/2] Running Hybrid Search & Re-ranking ---")
    
    try:
        with open(CHUNKS_FILE, 'r', encoding='utf-8') as f:
            all_chunks = json.load(f)
    except FileNotFoundError:
        print(f"Error: {CHUNKS_FILE} not found.")
        return []

    keyword_results = run_keyword_search(query, all_chunks)
    semantic_results = run_semantic_search(query)

    rrf_scores = {}
    for i, doc in enumerate(keyword_results):
        doc_id = doc['id']
        if doc_id not in rrf_scores: rrf_scores[doc_id] = 0
        rrf_scores[doc_id] += 1.0 / (i + RRF_K)

    for i, doc in enumerate(semantic_results):
        doc_id = doc['id']
        if doc_id not in rrf_scores: rrf_scores[doc_id] = 0
        rrf_scores[doc_id] += 1.0 / (i + RRF_K)

    reranked_ids = sorted(rrf_scores.keys(), key=lambda x: rrf_scores[x], reverse=True)

    final_chunks = []
    chunk_map = {f"chunk_{i}": chunk for i, chunk in enumerate(all_chunks)}

    for doc_id in reranked_ids[:TOP_N_RESULTS]:
        chunk = chunk_map.get(doc_id)
        if chunk:
            chunk['final_score'] = rrf_scores[doc_id]
            final_chunks.append(chunk)
            
    return final_chunks

def build_final_prompt(query, chunks):
    """Constructs the final, context-rich prompt."""
    print("--- [STEP 2/2] Building Final Prompt ---")
    context_str = ""
    arch_context_str = ""

    if not chunks:
        context_str = "No relevant context was found in the documentation."
    else:
        # Build main content context
        for i, chunk in enumerate(chunks):
            source = chunk.get('source', 'N/A')
            title = chunk.get('title', 'N/A')
            content = chunk.get('content', '')
            score = chunk.get('final_score', 0.0)
            context_str += f"\n--- Context Chunk {i+1} (Source: {source} | Title: {title} | Relevance Score: {score:.4f}) ---"
            context_str += content
            context_str += "\n--- End of Chunk ---"
        
        # Build architectural context from the most relevant chunk
        top_chunk = chunks[0]
        relations = top_chunk.get('relations', {})
        if relations and (relations.get('uses_model') or relations.get('renders_view')):
            arch_context_str = "\n\n### ARCHITECTURAL CONTEXT (from Knowledge Graph):\n"
            arch_context_str += f"The primary context file (`{top_chunk['source']}`) has the following relationships:"
            if relations.get('uses_model'):
                arch_context_str += f"\n- **Uses Models:** { ', '.join(relations['uses_model']) }"
            if relations.get('renders_view'):
                arch_context_str += f"\n- **Renders Views:** { ', '.join(relations['renders_view']) }"

    final_prompt = f"""
# ======================================================================
# AI AGENT TASK PROMPT (Generated by DCM Subsystem - Phase 3: Hybrid Search)
# ======================================================================

### USER'S PRIMARY TASK:
{query}
{arch_context_str}

### RELEVANT CONTEXT FROM PROJECT DOCUMENTATION (Hybrid Search):
{context_str}

### INSTRUCTION:
Based on the user's task and the provided context from the documentation, please proceed with generating the necessary code or taking the required actions.

# ======================================================================
"""
    return final_prompt

if __name__ == "__main__":
    if len(sys.argv) < 2:
        print("Usage: python main.py <your query>")
        sys.exit(1)

    user_query = ' '.join(sys.argv[1:])
    
    retrieved_chunks = hybrid_search_and_rerank(user_query)
    final_prompt = build_final_prompt(user_query, retrieved_chunks)

    output_filename = os.path.join(SCRIPT_DIR, "prompt.txt")
    try:
        with open(output_filename, 'w', encoding='utf-8') as f:
            f.write(final_prompt)
        print(f"\n[SUCCESS] Rich prompt saved to: {output_filename}")
    except IOError as e:
        print(f"\n[ERROR] Could not write prompt to file: {e}")

    print("\n--- Generated Prompt Below ---")
    sys.stdout.reconfigure(encoding='utf-8')
    print(final_prompt)
