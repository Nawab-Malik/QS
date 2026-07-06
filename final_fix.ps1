$files = @("404.html", "about.html", "case-studies.html", "contact.html", "login.html", "portfolio.html", "portfolio.php", "pricing.html", "services.html", "team.html")

$fixed_search = @"
    <!-- tp header search  -->
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
    </div>
"@

foreach ($f in $files) {
    if (Test-Path $f) {
        $c = [System.IO.File]::ReadAllText((Resolve-Path $f))
        
        # Match from the first "tp header search" to the next significant section
        $pattern = "(?s)<!-- tp header search  -->.*?<!--\s*(?:<< Breadcrumb|Upcoming-Movie|about-section|service|portfolio|contact|login|pricing|error|team)\s*Section\s*Start\s*-->"
        
        if ($c -match $pattern) {
            $matched_text = $matches[0]
            # Get the exact section start comment to preserve it
            $section_start = [regex]::Match($matched_text, "<!--\s*(?:<< Breadcrumb|Upcoming-Movie|about-section|service|portfolio|contact|login|pricing|error|team)\s*Section\s*Start\s*-->").Value
            
            $new_block = $fixed_search + "`r`n`r`n    " + $section_start
            $c = $c.Replace($matched_text, $new_block)
        }

        # Sync characters
        $c = $c.Replace("Ã—", "×")
        $c = $c.Replace("weâ€™re", "we're")

        [System.IO.File]::WriteAllText((Resolve-Path $f), $c, [System.Text.Encoding]::UTF8)
    }
}
Write-Host "Sync and cleanup complete."
