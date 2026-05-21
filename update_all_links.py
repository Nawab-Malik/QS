import os
import glob

# Change to the target directory
os.chdir(r'd:\All completed projects imp portfolio\Movies1\Movies')

# Define files to process
html_files = [
    '404.html', 'about.html', 'cooming-soon.html', 'contact.html', 'index.html',
    'login.html', 'movie-details.html', 'movie.html', 'news-details.html', 'news.html',
    'portfolio.html', 'pricing.html', 'services.html', 'team.html', 'tv-shows-details.html',
    'tv-shows.html', 'web-series-details.html', 'web-series.html'
]

# Define replacements
replacements = [
    (r'href="movie.html"', 'href="portfolio.html"'),
    (r'href="tv-shows.html"', 'href="services.html"'),
    (r'href="web-series.html"', 'href="case-studies.html"'),
]

print("Starting link replacement process...\n")

for file in html_files:
    if os.path.exists(file):
        try:
            with open(file, 'r', encoding='utf-8', errors='ignore') as f:
                content = f.read()
            
            original = content
            
            for old, new in replacements:
                content = content.replace(old, new)
            
            if content != original:
                with open(file, 'w', encoding='utf-8') as f:
                    f.write(content)
                print(f'✓ {file}: Updated')
            else:
                print(f'✓ {file}: Already current')
        except Exception as e:
            print(f'✗ {file}: Error - {e}')
    else:
        print(f'- {file}: File not found')

print("\nLink replacement complete!")
