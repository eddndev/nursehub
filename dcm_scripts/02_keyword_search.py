
import json
import sys
import re

def search_chunks(query):
    """
    Loads chunks from chunks.json and performs a simple keyword search.
    Ranks chunks based on the number of unique query words they contain.
    """
    try:
        with open('chunks.json', 'r', encoding='utf-8') as f:
            chunks = json.load(f)
    except FileNotFoundError:
        print(json.dumps({"error": "chunks.json not found. Run 01_chunker.py first."}))
        return

    # Split query into unique, lowercased words
    query_words = set(re.findall(r'\w+', query.lower()))

    if not query_words:
        print(json.dumps({"error": "Query is empty or contains no searchable words."}))
        return

    ranked_chunks = []
    for i, chunk in enumerate(chunks):
        chunk_content_lower = chunk.get('content', '').lower()
        match_count = 0
        matched_words = set()

        for word in query_words:
            if word in chunk_content_lower:
                match_count += 1
                matched_words.add(word)
        
        if match_count > 0:
            # Score based on number of unique words matched
            score = match_count / len(query_words)
            chunk['search_score'] = score
            chunk['matched_words'] = list(matched_words)
            ranked_chunks.append(chunk)

    # Sort by score (descending), then by original order (ascending) as a tie-breaker
    ranked_chunks.sort(key=lambda x: x['search_score'], reverse=True)

    # Return top 5 results
    top_5_results = ranked_chunks[:5]

    # Ensure stdout is configured for UTF-8
    sys.stdout.reconfigure(encoding='utf-8')
    # Print results as a JSON array to stdout
    print(json.dumps(top_5_results, indent=2, ensure_ascii=False))

if __name__ == "__main__":
    if len(sys.argv) > 1:
        user_query = ' '.join(sys.argv[1:])
        search_chunks(user_query)
    else:
        # Ensure stdout is configured for UTF-8 for error messages too
        sys.stdout.reconfigure(encoding='utf-8')
        print(json.dumps({"error": "Please provide a search query as a command-line argument."}))
