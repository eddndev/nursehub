
import json
import chromadb
from langchain_community.embeddings import HuggingFaceEmbeddings
from langchain.docstore.document import Document

# --- Configuration ---
CHUNKS_FILE = 'chunks.json'
DB_PATH = 'vector_db'
COLLECTION_NAME = 'nursehub_docs'
# Use a reliable, small, and fast model for embeddings
MODEL_NAME = 'all-MiniLM-L6-v2'

def main():
    """
    Main function to load chunks, generate embeddings, and store them in ChromaDB.
    """
    print("--- Starting Vectorization Process ---")

    # 1. Load the chunks from the JSON file
    try:
        with open(CHUNKS_FILE, 'r', encoding='utf-8') as f:
            chunks_data = json.load(f)
        print(f"Loaded {len(chunks_data)} chunks from {CHUNKS_FILE}")
    except FileNotFoundError:
        print(f"Error: {CHUNKS_FILE} not found. Please run 01_chunker.py first.")
        return

    # 2. Convert chunks into LangChain Document objects for processing
    # This format is useful for compatibility with LangChain's ecosystem
    documents = []
    for i, chunk in enumerate(chunks_data):
        doc = Document(
            page_content=chunk.get('content', ''),
            metadata={
                "source": chunk.get('source', 'N/A'),
                "title": chunk.get('title', 'N/A'),
                "chunk_id": i
            }
        )
        documents.append(doc)

    # 3. Initialize the embedding model
    print(f"Initializing embedding model: {MODEL_NAME}")
    # device='cpu' is a good default to avoid issues on systems without a GPU
    model_kwargs = {'device': 'cpu'}
    encode_kwargs = {'normalize_embeddings': False}
    embeddings = HuggingFaceEmbeddings(
        model_name=MODEL_NAME,
        model_kwargs=model_kwargs,
        encode_kwargs=encode_kwargs
    )
    print("Embedding model loaded.")

    # 4. Initialize ChromaDB client and create or get the collection
    # This will create a local directory `vector_db` to persist the data
    client = chromadb.PersistentClient(path=DB_PATH)
    collection = client.get_or_create_collection(name=COLLECTION_NAME)
    print(f"ChromaDB collection '{COLLECTION_NAME}' ready.")

    # 5. Generate embeddings and add documents to the collection
    # We'll add documents in batches to be memory-efficient
    batch_size = 50
    total_docs = len(documents)
    print(f"Adding {total_docs} documents to the collection in batches of {batch_size}...")

    for i in range(0, total_docs, batch_size):
        batch_docs = documents[i:i+batch_size]
        
        ids = [f"doc_{doc.metadata['chunk_id']}" for doc in batch_docs]
        contents = [doc.page_content for doc in batch_docs]
        metadatas = [doc.metadata for doc in batch_docs]

        # The embedding model will be called automatically by ChromaDB if not provided
        # but it's often clearer to manage it yourself if needed.
        # For simplicity, we let Chroma handle it with the collection's default.
        # However, since we want to use LangChain's HuggingFaceEmbeddings, we embed first.
        embedded_contents = embeddings.embed_documents(contents)

        collection.add(
            ids=ids,
            embeddings=embedded_contents,
            documents=contents,
            metadatas=metadatas
        )
        print(f"  ... Added batch {i//batch_size + 1}/{(total_docs + batch_size - 1)//batch_size}")

    count = collection.count()
    print(f"\n--- Vectorization Complete ---")
    print(f"Successfully added {count} documents to the '{COLLECTION_NAME}' collection.")
    print(f"Vector database is stored in: '{DB_PATH}/'")

if __name__ == "__main__":
    main()
