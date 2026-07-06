$search_bar = @"
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

$files = @("404.html", "about.html", "case-studies.html", "contact.html", "login.html", "portfolio.html", "portfolio.php", "pricing.html", "services.html", "team.html")

foreach ($f in $files) {
    if (Test-Path $f) {
        $c = Get-Content $f -Raw
        # Fix the messy duplication between </header> and the next section
        # We look for </header> and match everything until <!-- ... Section Start -->
        $c = [regex]::Replace($c, "(?s)</header>.*?(?=<!-- (?:team|<< Breadcrumb|Upcoming-Movie|about-section|service|portfolio|contact|login|pricing|error|team) Section Start -->)", "</header>`n`n$search_bar`n`n    ")
        
        # General Encoding Fixes
        $c = $c.Replace("Ã—", "×")
        $c = $c.Replace("weâ€™re", "we're")
        $c = $c.Replace("Mod-friday", "Mon-Friday")
        # Fix logo height/width inconsistency (some had width vs height)
        $c = $c.Replace("widht=`"200`"", "width=`"200`"")
        $c = $c.Replace("height=`"200`"", "width=`"200`"") # user usually prefers width consistency, index.html had width="200" typo

        [System.IO.File]::WriteAllText((Resolve-Path $f), $c, [System.Text.Encoding]::UTF8)
        Write-Host "Re-fixed $f"
    }
}
