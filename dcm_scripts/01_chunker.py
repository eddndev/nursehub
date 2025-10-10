
import os
import json
import re
import sys

def chunk_document(file_path):
    """
    Reads a markdown file and splits it into chunks based on Level 2 headers (##).
    The document title (Level 1 header) is prepended to each chunk for context.
    """
    chunks = []
    try:
        with open(file_path, 'r', encoding='utf-8') as f:
            content = f.read()

        # Find the main title (H1)
        h1_match = re.search(r'^#\s+(.*)', content, re.MULTILINE)
        doc_title = h1_match.group(1).strip() if h1_match else os.path.basename(file_path)

        # Split by H2 headers, keeping the headers as part of the content
        # The regex uses a positive lookahead to keep the delimiter (##)
        split_content = re.split(r'(?=^##\s+)', content, flags=re.MULTILINE)

        # The first element might be content before the first H2, including H1
        # We handle it to ensure it's not lost
        initial_content = split_content[0].strip()
        if h1_match and initial_content.startswith(h1_match.group(0)):
             # If the first chunk is just the H1 and stuff before the first H2
             pass # This content will be part of the first real chunk if it exists
        
        # Process splits
        for i, part in enumerate(split_content):
            if part.strip() == "":
                continue
            
            # For the very first chunk, it might contain the H1 and intro
            if i == 0:
                # If the document doesn't start with H2, this is the whole content or intro
                if not part.strip().startswith("## "):
                   chunks.append({
                       "source": os.path.basename(file_path),
                       "title": doc_title,
                       "content": part.strip()
                   })
                   continue

            # For subsequent chunks starting with H2
            if part.strip().startswith("## "):
                 chunks.append({
                    "source": os.path.basename(file_path),
                    "title": doc_title,
                    "content": part.strip()
                })

    except Exception as e:
        print(f"Error processing {file_path}: {e}")
    
    return chunks

def main():
    """
    Main function to walk through the docs directory, chunk markdown files,
    and save the output to a JSON file.
    """
    docs_path = '../docs'
    all_chunks = []
    sys.stdout.reconfigure(encoding='utf-8')
    print(f"Starting chunking process for directory: {docs_path}")

    for root, _, files in os.walk(docs_path):
        for file in files:
            if file.endswith('.md'):
                file_path = os.path.join(root, file)
                print(f"Processing: {file_path}")
                document_chunks = chunk_document(file_path)
                all_chunks.extend(document_chunks)

    output_path = 'chunks.json'
    with open(output_path, 'w', encoding='utf-8') as f:
        json.dump(all_chunks, f, indent=2, ensure_ascii=False)

    print(f"\nSuccessfully created {len(all_chunks)} chunks from the documentation.")
    print(f"Output saved to: {output_path}")

if __name__ == "__main__":
    main()
