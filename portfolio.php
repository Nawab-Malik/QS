<?php
require_once __DIR__ . "/includes/db.php";

function portfolio_escape(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, "UTF-8");
}

$db = db_connect();
$categories = db_fetch_all($db->query("SELECT * FROM portfolio_categories ORDER BY sort_order ASC, id ASC"));
$items = db_fetch_all(
    $db->query(
        "SELECT * FROM portfolio_items ORDER BY sort_order ASC, id ASC"
    )
);
$db->close();

$itemsByCategory = [];
foreach ($items as $item) {
    $itemsByCategory[$item["category_id"]][] = $item;
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="author" content="QS Marketing" />
    <meta name="description" content="QS Marketing Portfolio - Digital Marketing Case Studies & Work" />
    <title>Portfolio | QS Marketing</title>
    <link rel="shortcut icon" href="/assets/img/favicon.jpeg" />
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/css/all.min.css" />
    <link rel="stylesheet" href="assets/css/animate.css" />
    <link rel="stylesheet" href="assets/css/magnific-popup.css" />
    <link rel="stylesheet" href="assets/css/meanmenu.css" />
    <link rel="stylesheet" href="assets/css/swiper-bundle.min.css" />
    <link rel="stylesheet" href="assets/css/nice-select.css" />
    <link rel="stylesheet" href="assets/css/color.css" />
    <link rel="stylesheet" href="assets/css/main.css" />
    <style>
        .portfolio-service-card {
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .portfolio-service-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 10px 30px rgba(14, 165, 233, 0.3);
        }

        .portfolio-works-grid {
            display: none;
        }

        .portfolio-works-grid.active {
            display: grid;
        }

        .work-item {
            position: relative;
            overflow: hidden;
            border-radius: 12px;
            aspect-ratio: 1;
        }

        .work-link {
            display: block;
            height: 100%;
            color: inherit;
        }

        .work-link:hover {
            color: inherit;
        }

        .work-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .work-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
            text-align: center;
            padding: 12px;
        }

        .work-item:hover .work-overlay {
            opacity: 1;
        }

        .work-overlay h5 {
            color: #fff;
            margin-bottom: 0.5rem;
        }

        .work-overlay p {
            color: #A30F71;
            font-size: 0.9rem;
        }

        .service-tabs {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 3rem;
        }

        .service-tab {
            padding: 0.75rem 1.5rem;
            border: 2px solid rgba(14, 165, 233, 0.3);
            border-radius: 8px;
            background: transparent;
            color: #dbe6ff;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .service-tab:hover,
        .service-tab.active {
            border-color: #A30F71;
            background: rgba(14, 165, 233, 0.1);
            color: #A30F71;
        }
    </style>
</head>

<body class="body-bg">
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
    </div>

    <div class="mouse-cursor cursor-outer"></div>
    <div class="mouse-cursor cursor-inner"></div>

    <button id="back-top" class="back-to-top">
        <i class="fa-solid fa-chevron-up"></i>
    </button>

    <div class="fix-area">
        <div class="offcanvas__info">
            <div class="offcanvas__wrapper">
                <div class="offcanvas__content">
                    <div class="offcanvas__top mb-5 d-flex justify-content-between align-items-center">
                        <div class="offcanvas__logo">
                            <a href="index.html">
                                <img width="200" src="assets/img/logo/QS Logo.png" alt="logo-img" />
                            </a>
                        </div>
                        <div class="offcanvas__close">
                            <button>
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <p class="text">
                        QS Marketing is a performance-driven digital marketing agency helping brands scale through
                        strategy, creative, and data.
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
                                    <a target="_blank" href="#">Lahore, Pakistan (Serving Worldwide)</a>
                                </div>
                            </li>
                            <li class="d-flex align-items-center">
                                <div class="offcanvas__contact-icon mr-15">
                                    <i class="fal fa-envelope"></i>
                                </div>
                                <div class="offcanvas__contact-text">
                                    <a href="mailto:hello@qsmarketingexpert.com">
                                        <span
                                            class="mailto:hello@qsmarketingexpert.com">hello@qsmarketingexpert.com</span>
                                    </a>
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
                            <a href="contact.html" class="theme-btn text-center">Get Proposal</a>
                        </div>
                        <div class="social-icon d-flex align-items-center">
                            <a href="#"><i class="fa-brands fa-tiktok"></i></a>
                            <a href="#"><i class="fab fa-facebook-f"></i></a>
                            <a href="#"><i class="fa-brands fa-youtube"></i></a>
                            <a href="#"><i class="fa-brands fa-twitter"></i></a>
                            <a href="#"><i class="fab fa-instagram"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="offcanvas__overlay"></div>

    <!-- Header Section Start -->
    <header class="header-1 header-4 header-5">
        <div class="container-fluid">
            <div class="mega-menu-wrapper">
                <div class="header-main style-2">
                    <div class="logo">
                        <a href="index.html" class="header-logo">
                            <img width="200" src="assets/img/logo/QS Logo.png" alt="logo-img" />
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
                                    <li class="has-dropdown">
                                        <a href="about.html"> Company </a>
                                        <ul class="submenu">
                                            <li><a href="about.html">About QS Marketing</a></li>
                                            <li><a href="team.html">Team</a></li>
                                            <li><a href="pricing.html">Pricing</a></li>
                                            <li><a href="404.html">Error 404</a></li>
                                        </ul>
                                    </li>
                                    <li><a href="contact.html">Contact</a></li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <div class="header-right d-flex justify-content-end align-items-center">
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
    </header>

    <div class="tp-header-search-bar d-flex align-items-center">
        <button class="tp-search-close">×</button>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="tp-search-bar text-center">
                        <h2 class="tp-search-bar-title mb-30">What do you want to grow?</h2>
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

    <section class="hero-ssection hero-5 bg-cover" style="background-image: url(assets/img/bg2.avif)">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-lg-12">
                    <div class="hero-content text-center">
                        <h5 class="wow fadeInUp" data-wow-delay=".3s">Our Portfolio</h5>
                        <h1 class="wow fadeInUp" data-wow-delay=".5s">Strategic Work That Drives Real Results</h1>
                        <p class="wow fadeInUp" data-wow-delay=".7s">
                            Explore our collection of digital marketing campaigns, designs, and growth strategies across
                            all service categories.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="trending-section-2 section-padding fix">
        <div class="container">
            <div class="section-title-area mb-5">
                <div class="section-title">
                    <h2 class="wow fadeInUp" data-wow-delay=".3s">Select a Service Category</h2>
                    <p class="text wow fadeInUp" data-wow-delay=".5s">
                        Click any service to view our portfolio works and case studies
                    </p>
                </div>
            </div>

            <div class="service-tabs wow fadeInUp" data-wow-delay=".3s">
                <?php foreach ($categories as $index => $category): ?>
                    <button class="service-tab <?php echo $index === 0 ? "active" : ""; ?>"
                        data-service="<?php echo portfolio_escape($category["slug"]); ?>">
                        <i class="<?php echo portfolio_escape($category["icon_class"]); ?> me-2"></i>
                        <?php echo portfolio_escape($category["name"]); ?>
                    </button>
                <?php endforeach; ?>
            </div>

            <?php foreach ($categories as $index => $category): ?>
                <div class="portfolio-works-grid <?php echo $index === 0 ? "active" : ""; ?>"
                    data-service="<?php echo portfolio_escape($category["slug"]); ?>">
                    <div class="row g-3">
                        <?php foreach ($itemsByCategory[$category["id"]] ?? [] as $item): ?>
                            <div class="col-md-6 col-lg-4">
                                <div class="work-item">
                                    <?php if (!empty($item["link_url"])): ?>
                                        <a class="work-link" href="<?php echo portfolio_escape($item["link_url"]); ?>"
                                            target="_blank" rel="noopener">
                                        <?php endif; ?>
                                        <img src="<?php echo portfolio_escape($item["image_url"]); ?>"
                                            alt="<?php echo portfolio_escape($item["title"]); ?>" />
                                        <div class="work-overlay">
                                            <h5><?php echo portfolio_escape($item["title"]); ?></h5>
                                            <p><?php echo portfolio_escape($item["subtitle"]); ?></p>
                                        </div>
                                        <?php if (!empty($item["link_url"])): ?>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="movie-ticket-section section-padding fix bg-cover"
        style="background-image: url(https://images.unsplash.com/photo-1553877522-43269d4ea984?auto=format&fit=crop&w=1600&q=80);">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <h2 class="mb-4">Ready to See Your Brand's Potential?</h2>
                    <p class="mb-4">Let's discuss how our proven strategies can scale your business.</p>
                    <a href="contact.html" class="theme-btn style-3">
                        Schedule Free Strategy Call
                        <i class="fa-regular fa-arrow-up-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <footer class="footer-section-5">
        <div class="footer-widgets-wrapper style-5">
            <div class="container">
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-6 wow fadeInUp" data-wow-delay=".2s">
                        <div class="single-footer-widget">
                            <div class="widget-head">
                                <a href="index.html">
                                    <img width="200" src="assets/img/logo/QS Logo.png" alt="logo-img" />
                                </a>
                            </div>
                            <div class="footer-content">
                                <p>
                                    QS Marketing is a full-service digital agency helping brands grow through
                                    performance marketing, creative, and strategy.
                                </p>
                                <div class="social-icon d-flex align-items-center">
                                    <a href="#"><i class="fa-brands fa-tiktok"></i></a>
                                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                                    <a href="#"><i class="fa-brands fa-youtube"></i></a>
                                    <a href="#"><i class="fa-brands fa-twitter"></i></a>
                                    <a href="#"><i class="fab fa-instagram"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-md-6 ps-lg-5 wow fadeInUp" data-wow-delay=".4s">
                        <div class="single-footer-widget">
                            <div class="widget-head">
                                <h3>Quick links</h3>
                            </div>
                            <div class="footer-content">
                                <ul class="list-area">
                                    <li>
                                        <a href="index.html"><i class="fa-solid fa-chevrons-right"></i> Home</a>
                                    </li>
                                    <li>
                                        <a href="services.html"><i class="fa-solid fa-chevrons-right"></i> Services</a>
                                    </li>
                                    <li>
                                        <a href="case-studies.html"><i class="fa-solid fa-chevrons-right"></i> Case
                                            Studies</a>
                                    </li>
                                    <li>
                                        <a href="portfolio.php"><i class="fa-solid fa-chevrons-right"></i> Portfolio</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-4 col-md-6 ps-lg-3 wow fadeInUp" data-wow-delay=".6s">
                        <div class="single-footer-widget">
                            <div class="widget-head">
                                <h3>Services</h3>
                            </div>
                            <div class="footer-content">
                                <ul class="list-area">
                                    <li><a href="#"><i class="fa-solid fa-chevrons-right"></i> Social Media</a></li>
                                    <li><a href="#"><i class="fa-solid fa-chevrons-right"></i> Google Ads</a></li>
                                    <li><a href="#"><i class="fa-solid fa-chevrons-right"></i> SEO</a></li>
                                    <li><a href="#"><i class="fa-solid fa-chevrons-right"></i> Web Dev</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-md-6 ps-lg-5 wow fadeInUp" data-wow-delay=".8s">
                        <div class="single-footer-widget">
                            <div class="widget-head">
                                <h3>Contact Us</h3>
                            </div>
                            <div class="contact-us-item">
                                <div class="contact-item">
                                    <div class="icon">
                                        <i class="fa-solid fa-location-dot"></i>
                                    </div>
                                    <h6>Lahore, Pakistan | Serving Worldwide</h6>
                                </div>
                                <div class="contact-item">
                                    <div class="icon">
                                        <i class="fa-solid fa-envelope"></i>
                                    </div>
                                    <h6>
                                        <a href="mailto:hello@qsmarketingexpert.com">hello@qsmarketingexpert.com</a>
                                    </h6>
                                </div>
                                <div class="contact-item mb-0">
                                    <div class="icon">
                                        <i class="fa-solid fa-phone"></i>
                                    </div>
                                    <h6>
                                        <a href="tel:+923000000000">+92 300 0000000</a>
                                    </h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-bottom style-2">
            <div class="container">
                <p class="wow fadeInUp" data-wow-delay=".3s">
                    Â© Copyright 2024
                    <a href="https://qsmarketingexperts.online" style="color: #A30F71">QS Marketing</a>
                    | Developed by
                    <a href="https://www.linkedin.com/in/adnanbashir-full-stack-developer" target="_blank"
                        style="color: #A30F71">Adnan
                        Bashir</a>
                </p>
            </div>
        </div>
    </footer>

    <script src="assets/js/jquery-3.7.1.min.js"></script>
    <script src="assets/js/viewport.jquery.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/jquery.nice-select.min.js"></script>
    <script src="assets/js/jquery.waypoints.js"></script>
    <script src="assets/js/jquery.counterup.min.js"></script>
    <script src="assets/js/swiper-bundle.min.js"></script>
    <script src="assets/js/jquery.meanmenu.min.js"></script>
    <script src="assets/js/jquery.magnific-popup.min.js"></script>
    <script src="assets/js/wow.min.js"></script>
    <script src="assets/js/main.js"></script>
    <script>
        document.querySelectorAll(".service-tab").forEach((tab) => {
            tab.addEventListener("click", function () {
                const service = this.getAttribute("data-service");

                document
                    .querySelectorAll(".service-tab")
                    .forEach((t) => t.classList.remove("active"));
                document
                    .querySelectorAll(".portfolio-works-grid")
                    .forEach((g) => g.classList.remove("active"));

                this.classList.add("active");
                const grid = document.querySelector(
                    `.portfolio-works-grid[data-service="${service}"]`
                );
                if (grid) grid.classList.add("active");
            });
        });
    </script>
</body>

</html>