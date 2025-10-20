
import os
import json
import subprocess
import sys

def analyze_php_file(file_path):
    """Calls the PHP worker script to analyze a single file via stdin."""
    script_dir = os.path.dirname(__file__)
    worker_path = os.path.join(script_dir, 'parser_worker.php')
    
    try:
        process = subprocess.run(
            ['php', worker_path],
            input=file_path,
            capture_output=True, text=True, check=True, encoding='utf-8'
        )
        return json.loads(process.stdout)
    except subprocess.CalledProcessError as e:
        print(f"  - Error analyzing {os.path.basename(file_path)}: {e.stderr}", file=sys.stderr)
        return None
    except json.JSONDecodeError as e:
        print(f"  - JSON decode error for {os.path.basename(file_path)}: {e.stdout}", file=sys.stderr)
        return None
    except FileNotFoundError:
        print("Error: 'php' executable not found in PATH. Please ensure PHP is installed and accessible.", file=sys.stderr)
        sys.exit(1)

def main():
    """Walks through the app directory and builds a knowledge graph."""
    print("--- Starting Knowledge Graph Build Process ---")
    
    base_path = os.path.abspath(os.path.join(os.path.dirname(__file__), '..'))
    app_path = os.path.join(base_path, 'app')
    
    knowledge_graph = {}

    for root, _, files in os.walk(app_path):
        for file in files:
            if file.endswith('.php'):
                file_path = os.path.join(root, file)
                print(f"Analyzing: {file_path}")
                
                relations = analyze_php_file(file_path)
                
                if relations and not relations.get('error'):
                    # Use a clean, relative path for the graph key
                    relative_path = os.path.relpath(file_path, base_path).replace('\\', '/')
                    # Only add to graph if relations were found
                    if relations['uses_model'] or relations['renders_view']:
                        knowledge_graph[relative_path] = relations

    output_path = os.path.join(os.path.dirname(__file__), 'knowledge_graph.json')
    with open(output_path, 'w', encoding='utf-8') as f:
        json.dump(knowledge_graph, f, indent=2, ensure_ascii=False)

    print(f"\n--- Knowledge Graph Build Complete ---")
    print(f"Found relationships in {len(knowledge_graph)} files.")
    print(f"Graph saved to: {output_path}")

if __name__ == "__main__":
    main()
