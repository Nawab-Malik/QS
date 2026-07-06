$files = @("404.html", "about.html", "case-studies.html", "contact.html", "login.html", "portfolio.html", "portfolio.php", "pricing.html", "services.html", "team.html")

foreach ($f in $files) {
    if (Test-Path $f) {
        $c = [System.IO.File]::ReadAllText((Resolve-Path $f))
        
        # Remove any doubled "tp header search" comments
        $c = $c.Replace("<!-- tp header search  -->`r`n        <!-- tp header search  -->", "    <!-- tp header search  -->")
        $c = $c.Replace("<!-- tp header search  -->`n        <!-- tp header search  -->", "    <!-- tp header search  -->")
        
        # Fix the messy end of search bar where extra divs remain
        # This regex matches the search bar and the following extra divs up to the next section
        $search_bar_fixed = @"
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
        
        $c = [regex]::Replace($c, "(?s)<!-- tp header search  -->.*?<!-- (?:team|<< Breadcrumb|Upcoming-Movie|about-section|service|portfolio|contact|login|pricing|error|team) Section Start -->", "$search_bar_fixed`n`n    <!-- ")
        
        # fix back the extra comment start
        $c = $c.Replace("<!-- `n`n    <!-- ", "`n`n    <!-- ")
        $c = $c.Replace("<!-- `n    <!-- ", "`n    <!-- ")
        $c = $c.Replace("    <!-- `n`n    <!-- ", "`n`n    <!-- ")

        # Encoding fixes
        $c = $c.Replace("Ã—", "×")
        $c = $c.Replace("weâ€™re", "we're")
        $c = $c.Replace("weâ\u20AC\u2122re", "we're")

        [System.IO.File]::WriteAllText((Resolve-Path $f), $c, [System.Text.Encoding]::UTF8)
    }
}
Write-Host "Sync and cleanup complete."
