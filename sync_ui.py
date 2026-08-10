import os
import re

# Reference blocks from index.html (Source of Truth)
PRELOADER_BLOCK = """    <!-- Preloader Start -->
    <div id="preloader" class="preloader">
      <div class="animation-preloader">
        <div class="spinner"></div>
        <div class="txt-loading">
          <span data-text-preloader="Q" class="letters-loading"> Q </span>
          <span data-text-preloader="S" class="letters-loading"> S </span>
          <span data-text-preloader=" " class="letters-loading"> </span>
          <span data-text-preloader="M" class="letters-loading"> M </span>
          <span data-text-preloader="A" class="letters-loading"> A </span>
          <span data-text-preloader="R" class="letters-loading"> R </span>
          <span data-text-preloader="K" class="letters-loading"> K </span>
          <span data-text-preloader="E" class="letters-loading"> E </span>
          <span data-text-preloader="T" class="letters-loading"> T </span>
          <span data-text-preloader="I" class="letters-loading"> I </span>
          <span data-text-preloader="N" class="letters-loading"> N </span>
          <span data-text-preloader="G" class="letters-loading"> G </span>
        </div>
        <p class="text-center">Loading</p>
      </div>
      <div class="loader">
        <div class="row">
          <div class="col-3 loader-section section-left">
            <div class="bg"></div>
          </div>
          <div class="col-3 loader-section section-left">
            <div class="bg"></div>
          </div>
          <div class="col-3 loader-section section-right">
            <div class="bg"></div>
          </div>
          <div class="col-3 loader-section section-right">
            <div class="bg"></div>
          </div>
        </div>
      </div>
    </div>"""

OFFCANVAS_BLOCK = """    <!-- Offcanvas Area Start -->
    <div class="fix-area">
      <div class="offcanvas__info">
        <div class="offcanvas__wrapper">
          <div class="offcanvas__content">
            <div
              class="offcanvas__top mb-5 d-flex justify-content-between align-items-center"
            >
              <div class="offcanvas__logo">
                <a href="index.html">
                  <img
                    width="200"
                    src="assets/img/logo/QS Logo.png"
                    alt="logo-img"
                  />
                </a>
              </div>
              <div class="offcanvas__close">
                <button>
                  <i class="fas fa-times"></i>
                </button>
              </div>
            </div>
            <p class="text">
              QS Marketing is a performance-driven digital marketing agency
              helping brands scale through strategy, creative, and data.
            </p>
            <div class="mobile-menu fix mb-5"></div>
            <div class="offcanvas__contact">
              <h4>QS Marketing</h4>
              <ul>
                <li class="d-flex align-items-center">
                  <div class="offcanvas__contact-icon">
                    <i class="fal fa-map-marker-alt"></i>
                  </div>
                  <div class="offcanvas__contact-text">
                    <a target="_blank" href="#"
                      >Lahore, Pakistan (Serving Worldwide)</a
                    >
                  </div>
                </li>
                <li class="d-flex align-items-center">
                  <div class="offcanvas__contact-icon mr-15">
                    <i class="fal fa-envelope"></i>
                  </div>
                  <div class="offcanvas__contact-text">
                    <a href="mailto:hello@qsmarketingexpert.com"
                      ><span class="mailto:hello@qsmarketingexpert.com"
                        >hello@qsmarketingexpert.com</span
                      ></a
                    >
                  </div>
                </li>
                <li class="d-flex align-items-center">
                  <div class="offcanvas__contact-icon mr-15">
                    <i class="fal fa-clock"></i>
                  </div>
                  <div class="offcanvas__contact-text">
                    <a target="_blank" href="#">Mon-Friday, 09am - 06pm</a>
                  </div>
                </li>
                <li class="d-flex align-items-center">
                  <div class="offcanvas__contact-icon mr-15">
                    <i class="far fa-phone"></i>
                  </div>
                  <div class="offcanvas__contact-text">
                    <a href="tel:+923000000000">+92 300 0000000</a>
                  </div>
                </li>
              </ul>
              <div class="header-button mt-4">
                <a href="pricing.html" class="theme-btn text-center">
                  Get Proposal
                </a>
              </div>
              <div class="social-icon d-flex align-items-center">
                <a href="https://www.facebook.com/qsmarketingexpert"><i class="fab fa-facebook-f"></i></a>
                <a href="tiktok.com/qasimshamsigfx"><i class="fab fa-tiktok"></i></a>
                <a href="https://www.instagram.com/qsmarketingexpert/"><i class="fab fa-instagram"></i></a>

                <a href="https://www.linkedin.com/in/ghulam-qasim-marketingexpert/"><i class="fab fa-linkedin-in"></i></a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="offcanvas__overlay"></div>"""

HEADER_BLOCK = """    <!-- Header Section Start -->
    <header class="header-1 header-4 header-5">
      <div class="container-fluid">
        <div class="mega-menu-wrapper">
          <div class="header-main style-2">
            <div class="logo">
              <a href="index.html" class="header-logo">
                <img
                  width="200"
                  src="assets/img/logo/QS Logo.png"
                  alt="logo-img"
                />
              </a>
            </div>
            <div class="mean__menu-wrapper">
              <div class="main-menu">
                <nav id="mobile-menu">
                  <ul>
                    <li class="has-dropdown active">
                      <a href="index.html"> Home </a>
                    </li>
                    <li>
                      <a href="services.html"> Services </a>
                    </li>
                    <li>
                      <a href="case-studies.html"> Case Studies </a>
                    </li>
                    <li>
                      <a href="portfolio.php"> Portfolio </a>
                    </li>
                    <!-- <li class="has-dropdown">
                      <a href="about.html"> Company </a>
                      <ul class="submenu">
                        <li><a href="about.html">About QS Marketing</a></li>
                        <li><a href="team.html">Team</a></li>
                        <li><a href="pricing.html">Pricing</a></li>
                        <li><a href="404.html">Error 404</a></li>
                      </ul>
                    </li> -->
                    <li><a href="contact.html">Contact</a></li>
                  </ul>
                </nav>
              </div>
            </div>
            <div
              class="header-right d-flex justify-content-end align-items-center"
            >
              <div class="tp-header-search d-none d-md-flex">
                <button class="d-flex align-items-center tp-search-toggle">
                  <i class="fa-solid fa-magnifying-glass"></i>
                </button>
              </div>
              <a href="contact.html" class="theme-btn style-3">
                Get Free Consultation
                <i class="fa-regular fa-arrow-up-right"></i>
              </a>
              <div class="header__hamburger d-xl-none">
                <div class="sidebar__toggle">
                  <i class="fas fa-bars"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </header>"""

SEARCH_BLOCK = """    <!-- tp header search  -->
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

# Other elements to sync: Title and Meta description if they differ significantly from the brand
META_AND_TITLE = """    <meta name="author" content="QS Marketing" />
    <meta
      name="description"
      content="QS Marketing | Digital Marketing Agency"
    />
    <!-- ======== Page title ============ -->
    <title>QS Marketing | Digital Marketing Agency</title>
    <!--<< Favcion >>-->
    <link
      rel="shortcut icon"
      href="/assets/img/favicon.jpeg"
    />"""

FILES_TO_UPDATE = [
    "404.html",
    "about.html",
    "case-studies.html",
    "contact.html",
    "login.html",
    "portfolio.html",
    "portfolio.php",
    "pricing.html",
    "services.html",
    "team.html"
]

def update_file(filepath):
    if not os.path.exists(filepath):
        print(f"File {filepath} not found.")
        return

    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()

    # Replace Preloader
    content = re.sub(r'<!-- Preloader Start -->.*?<!--<< Mouse Cursor Start >>-->', 
                     PRELOADER_BLOCK + '\n\n    <!--<< Mouse Cursor Start >>-->', 
                     content, flags=re.DOTALL)

    # Replace Offcanvas
    content = re.sub(r'<!-- Offcanvas Area Start -->.*?<div class="offcanvas__overlay"></div>', 
                     OFFCANVAS_BLOCK + '\n    <div class="offcanvas__overlay"></div>', 
                     content, flags=re.DOTALL)

    # Replace Header
    # We look for Header Section Start until the end of the header tag
    content = re.sub(r'<!-- Header Section Start -->.*?<!-- tp header search  -->', 
                     HEADER_BLOCK + '\n\n    ' + SEARCH_BLOCK, 
                     content, flags=re.DOTALL)
    
    # If the search bar replacement didn't happen because of different comments, try direct header tag replacement
    if HEADER_BLOCK not in content:
        content = re.sub(r'<header.*?</header>', HEADER_BLOCK, content, flags=re.DOTALL)

    # Replace Meta/Title/Favicon
    # Find the range between author meta and favcion link
    content = re.sub(r'<meta\s+name="author".*?<!--<< Bootstrap min.css >>-->', 
                     META_AND_TITLE + '\n    <!--<< Bootstrap min.css >>-->', 
                     content, flags=re.DOTALL)

    with open(filepath, 'w', encoding='utf-8') as f:
        f.write(content)
    print(f"Updated {filepath}")

for file in FILES_TO_UPDATE:
    update_file(file)
