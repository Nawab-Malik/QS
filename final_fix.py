import os

files = ["404.html", "about.html", "case-studies.html", "contact.html", "login.html", "portfolio.html", "portfolio.php", "pricing.html", "services.html", "team.html"]

fixed_search = """    <!-- tp header search  -->
    <div class="tp-header-search-bar d-flex align-items-center">
      <button class="tp-search-close">×</button>
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-6">
            <div class="tp-search-bar text-center">
              <h2 class="tp-search-bar-title mb-30">
                What do you want to grow?
              </h2>
              <div class="contact-form-box contact-search-form-box">
                <form action="#">
                  <input type="email" placeholder="Search services*" />
                  <button type="submit"><i class="far fa-search"></i></button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>"""

import re

pattern = re.compile(r"<!-- tp header search  -->.*?<!--\s*(?:<< Breadcrumb|Upcoming-Movie|about-section|service|portfolio|contact|login|pricing|error|team)\s*Section\s*Start\s*-->", re.DOTALL)
section_pattern = re.compile(r"<!--\s*(?:<< Breadcrumb|Upcoming-Movie|about-section|service|portfolio|contact|login|pricing|error|team)\s*Section\s*Start\s*-->")

for f in files:
    if os.path.exists(f):
        with open(f, 'r', encoding='utf-8') as file:
            content = file.read()
        
        match = pattern.search(content)
        if match:
            matched_text = match.group(0)
            section_match = section_pattern.search(matched_text)
            if section_match:
                section_start = section_match.group(0)
                new_block = fixed_search + "\n\n    " + section_start
                content = content.replace(matched_text, new_block)
        
        content = content.replace("Ã—", "×")
        content = content.replace("weâ€™re", "we're")
        
        with open(f, 'w', encoding='utf-8') as file:
            file.write(content)

print("Final cleanup successful.")
