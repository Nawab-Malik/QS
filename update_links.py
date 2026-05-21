#!/usr/bin/env python3
"""Update navigation links from movie template to marketing agency links"""

import os
import glob

# Change to the target directory
os.chdir(r'd:\All completed projects imp portfolio\Movies1\Movies')

# Define replacements
replacements = [
    # Link URLs
    ('href="movie.html"', 'href="portfolio.html"'),
    ('href="tv-shows.html"', 'href="services.html"'),
    ('href="web-series.html"', 'href="case-studies.html"'),
    # Menu text labels  
    ('>Movie</a>', '>Portfolio</a>'),
    ('>tv Shows</a>', '>Services</a>'),
    ('>web series</a>', '>Case Studies</a>'),
    # Additional variations
    ('>Movie<', '>Portfolio<'),
    ('>tv Shows<', '>Services<'),
    ('>web series<', '>Case Studies<'),
]

# Process all HTML files
html_files = glob.glob('*.html')
print(f"Found {len(html_files)} HTML files to process\n")

for file in html_files:
    try:
        with open(file, 'r', encoding='utf-8') as f:
            content = f.read()
        
        original = content
        changes = 0
        
        for old, new in replacements:
            if old in content:
                new_content = content.replace(old, new)
                if new_content != content:
                    changes += content.count(old)
                    content = new_content
        
        if content != original:
            with open(file, 'w', encoding='utf-8') as f:
                f.write(content)
            print(f'✓ Updated: {file} ({changes} replacements)')
        else:
            print(f'- No changes: {file}')
    except Exception as e:
        print(f'✗ Error processing {file}: {e}')

print('\nAll HTML files processed successfully!')
