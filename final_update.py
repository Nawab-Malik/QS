#!/usr/bin/env python3
import os
import glob

os.chdir(r'd:\All completed projects imp portfolio\Movies1\Movies')

# Simple regex-based replacements
replacements = [
    ('href="movie.html"', 'href="portfolio.html"'),
    ('href="tv-shows.html"', 'href="services.html"'),
    ('href="web-series.html"', 'href="case-studies.html"'),
]

files = glob.glob('*.html')
total_replacements = 0

for file in files:
    try:
        with open(file, 'r', encoding='utf-8', errors='ignore') as f:
            content = f.read()
        
        original = content
        for old, new in replacements:
            if old in content:
                count = content.count(old)
                content = content.replace(old, new)
                total_replacements += count
        
        if content != original:
            with open(file, 'w', encoding='utf-8') as f:
                f.write(content)
            print(f'Updated: {file}')
    except Exception as e:
        print(f'Error {file}: {e}')

print(f'\nTotal replacements made: {total_replacements}')
print('Complete!')
