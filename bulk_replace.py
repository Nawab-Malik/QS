#!/usr/bin/env python3
"""Bulk replace remaining navigation links in footer sections"""

import os
import glob

os.chdir(r'd:\All completed projects imp portfolio\Movies1\Movies')

# Simple string replacements for footer links
replacements = [
    ('href="movie.html"', 'href="portfolio.html"'),
    ('href="tv-shows.html"', 'href="services.html"'),
    ('href="web-series.html"', 'href="case-studies.html"'),
]

files_to_process = [
    '404.html', 'contact.html', 'login.html', 'movie-details.html', 'movie.html',
    'news-details.html', 'news.html', 'portfolio.html', 'pricing.html',
    'team.html', 'tv-shows-details.html', 'tv-shows.html', 'web-series-details.html',
    'web-series.html'
]

total_changes = 0
for file in files_to_process:
    if os.path.exists(file):
        with open(file, 'r', encoding='utf-8', errors='ignore') as f:
            original = f.read()
        
        content = original
        for old, new in replacements:
            if old in content:
                count_before = content.count(old)
                content = content.replace(old, new)
                total_changes += count_before
        
        if content != original:
            with open(file, 'w', encoding='utf-8') as f:
                f.write(content)
            print(f'✓ {file}')

print(f'\nTotal replacements: {total_changes}')
print('Bulk link update complete!')
