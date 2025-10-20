
import os
import json
import re
import sys

def chunk_markdown_document(file_path):
    """Reads a markdown file and splits it into chunks based on Level 2 headers (##)."""
    chunks = []
    try:
        with open(file_path, 'r', encoding='utf-8') as f:
            content = f.read()
        h1_match = re.search(r'^#\s+(.*)', content, re.MULTILINE)
        doc_title = h1_match.group(1).strip() if h1_match else os.path.basename(file_path)
        split_content = re.split(r'(?=^##\s+)', content, flags=re.MULTILINE)
        for i, part in enumerate(split_content):
            if part.strip():
                chunks.append({
                    "source": file_path.replace('..\\', '').replace('\\', '/'),
                    "title": doc_title,
                    "type": "markdown",
                    "content": part.strip(),
                    "relations": {}
                })
    except Exception as e:
        print(f"Error processing Markdown {file_path}: {e}")
    return chunks

def chunk_php_document(file_path):
    """Reads a PHP file, chunks it by class, and extracts relationships."""
    chunks = []
    try:
        with open(file_path, 'r', encoding='utf-8') as f:
            content = f.read()
        
        # --- Relationship Extraction ---
        relations = {
            'uses_model': [os.path.basename(path) for path in re.findall(r"use\s+App\\Models\\(\w+);", content)],
            'renders_view': re.findall(r"view\s*\(\s*['\"]([\w\.-]+)['\"]", content)
        }

        # --- Chunking by Class ---
        class_pattern = r"(class\s+\w+[\s\S]*?^\})"
        matches = list(re.finditer(class_pattern, content, re.MULTILINE))

        if matches:
            for match in matches:
                chunks.append({
                    "source": file_path.replace('..\\', '').replace('\\', '/'),
                    "title": os.path.basename(file_path),
                    "type": "php_class",
                    "content": match.group(1).strip(),
                    "relations": relations
                })
        else: # If no class, treat the whole file as one chunk
            chunks.append({
                "source": file_path.replace('..\\', '').replace('\\', '/'),
                "title": os.path.basename(file_path),
                "type": "php_file",
                "content": content.strip(),
                "relations": relations
            })
            
    except Exception as e:
        print(f"Error processing PHP {file_path}: {e}")
    return chunks

def main():
    """Main function to walk through source directories, chunk files, and save."""
    base_path = os.path.abspath(os.path.join(os.path.dirname(__file__), '..'))
    source_paths = ['docs', 'app', 'database/migrations', 'routes']
    all_chunks = []
    sys.stdout.reconfigure(encoding='utf-8')
    print(f"Starting chunking process for directories: {source_paths}")

    for path_suffix in source_paths:
        path = os.path.join(base_path, path_suffix)
        if not os.path.exists(path):
            print(f"Warning: Path not found, skipping: {path}")
            continue
        for root, _, files in os.walk(path):
            for file in files:
                file_path = os.path.join(root, file)
                if file.endswith('.md'):
                    print(f"Processing Markdown: {file_path}")
                    all_chunks.extend(chunk_markdown_document(file_path))
                elif file.endswith('.php'):
                    print(f"Processing PHP Code: {file_path}")
                    all_chunks.extend(chunk_php_document(file_path))

    output_path = os.path.join(os.path.dirname(__file__), 'chunks.json')
    with open(output_path, 'w', encoding='utf-8') as f:
        json.dump(all_chunks, f, indent=2, ensure_ascii=False)

    print(f"\nSuccessfully created {len(all_chunks)} chunks from all sources.")
    print(f"Output saved to: {output_path}")

if __name__ == "__main__":
    main()
