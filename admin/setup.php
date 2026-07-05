<?php
require_once __DIR__ . "/../includes/config.php";

$adminEmail = "admin@qsmarketingexperts.online";
$adminPassword = "QSmarketing@1";

$root = new mysqli(DB_HOST, DB_USER, DB_PASS);
if ($root->connect_error) {
    die("Connection failed: " . $root->connect_error);
}
// Removed database creation for live server as it's usually pre-created
// $root->query("CREATE DATABASE IF NOT EXISTS " . DB_NAME . " CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
$root->close();

require_once __DIR__ . "/../includes/db.php";
$db = db_connect();

$db->query(
    "CREATE TABLE IF NOT EXISTS admin_users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        email VARCHAR(191) NOT NULL UNIQUE,
        password_hash VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB"
);

$db->query(
    "CREATE TABLE IF NOT EXISTS portfolio_categories (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(191) NOT NULL,
        slug VARCHAR(191) NOT NULL UNIQUE,
        icon_class VARCHAR(191) NOT NULL,
        sort_order INT DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB"
);

$db->query(
    "CREATE TABLE IF NOT EXISTS portfolio_items (
        id INT AUTO_INCREMENT PRIMARY KEY,
        category_id INT NOT NULL,
        title VARCHAR(191) NOT NULL,
        subtitle VARCHAR(255) NOT NULL,
        image_url TEXT NOT NULL,
        link_url TEXT NULL,
        sort_order INT DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (category_id) REFERENCES portfolio_categories(id) ON DELETE CASCADE
    ) ENGINE=InnoDB"
);

$adminExists = $db->query("SELECT id FROM admin_users LIMIT 1")->num_rows > 0;
if (!$adminExists) {
    $hash = password_hash($adminPassword, PASSWORD_BCRYPT);
    $stmt = $db->prepare("INSERT INTO admin_users (email, password_hash) VALUES (?, ?)");
    $stmt->bind_param("ss", $adminEmail, $hash);
    $stmt->execute();
    $stmt->close();
}

$categoryCount = $db->query("SELECT id FROM portfolio_categories LIMIT 1")->num_rows;
if ($categoryCount === 0) {
    $categories = [
        ["Social Media", "social-media", "fa-solid fa-hashtag", 1],
        ["FB & Instagram Ads", "facebook-instagram", "fa-brands fa-meta", 2],
        ["Google Ads", "google-ads", "fa-brands fa-google", 3],
        ["SEO", "seo", "fa-solid fa-magnifying-glass-chart", 4],
        ["Web Development", "web-dev", "fa-solid fa-code", 5],
        ["Graphic Design", "graphic-design", "fa-solid fa-pen-nib", 6],
        ["Content", "content", "fa-solid fa-file-lines", 7],
        ["Branding", "branding", "fa-solid fa-layer-group", 8],
        ["Lead Gen", "lead-gen", "fa-solid fa-funnel-dollar", 9],
        ["Email", "email", "fa-solid fa-envelope-open-text", 10],
        ["E-commerce", "ecommerce", "fa-solid fa-cart-shopping", 11],
        ["Video Editing", "video", "fa-solid fa-video", 12]
    ];

    $stmt = $db->prepare("INSERT INTO portfolio_categories (name, slug, icon_class, sort_order) VALUES (?, ?, ?, ?)");
    foreach ($categories as $category) {
        $stmt->bind_param("sssi", $category[0], $category[1], $category[2], $category[3]);
        $stmt->execute();
    }
    $stmt->close();

    $categoryMap = [];
    $result = $db->query("SELECT id, slug FROM portfolio_categories");
    while ($row = $result->fetch_assoc()) {
        $categoryMap[$row["slug"]] = (int) $row["id"];
    }

    $items = [
        ["social-media", "Instagram Growth Campaign", "+180K followers in 90 days", "https://images.unsplash.com/photo-1611162616305-c69b3fa7fbe0?auto=format&fit=crop&w=500&q=80"],
        ["social-media", "TikTok Viral Campaign", "5.2M views in 1 month", "https://images.unsplash.com/photo-1626446174620-444af74461c7?auto=format&fit=crop&w=500&q=80"],
        ["social-media", "LinkedIn B2B Strategy", "320% engagement increase", "https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&w=500&q=80"],
        ["social-media", "Community Engagement", "45K qualified leads generated", "https://images.unsplash.com/photo-1559027615-cd2628902d4a?auto=format&fit=crop&w=500&q=80"],
        ["social-media", "Content Calendar Execution", "6 months of consistent posting", "https://images.unsplash.com/photo-1611162617305-c69b3fa7fbe0?auto=format&fit=crop&w=500&q=80"],
        ["social-media", "Influencer Collaboration", "8.5M combined reach", "https://images.unsplash.com/photo-1552664730-d307ca884978?auto=format&fit=crop&w=500&q=80"],
        ["facebook-instagram", "E-commerce Sales Campaign", "3.8x ROAS | $420K revenue", "https://images.unsplash.com/photo-1552664730-d307ca884978?auto=format&fit=crop&w=500&q=80"],
        ["facebook-instagram", "Lead Generation Funnel", "$8 cost per lead | 420 leads", "https://images.unsplash.com/photo-1556761175-4b46a572b786?auto=format&fit=crop&w=500&q=80"],
        ["facebook-instagram", "Video Ad Campaign", "12% video view-through rate", "https://images.unsplash.com/photo-1504384308090-c894fdcc538d?auto=format&fit=crop&w=500&q=80"],
        ["facebook-instagram", "Retargeting Optimization", "28% conversion uplift", "https://images.unsplash.com/photo-1517694712202-14dd9538aa97?auto=format&fit=crop&w=500&q=80"],
        ["facebook-instagram", "Audience Segmentation", "6 custom audience tiers", "https://images.unsplash.com/photo-1499209974431-9dddcece7f88?auto=format&fit=crop&w=500&q=80"],
        ["facebook-instagram", "Carousel Ad Testing", "18% CTR improvement", "https://images.unsplash.com/photo-1552664730-d307ca884978?auto=format&fit=crop&w=500&q=80"],
        ["google-ads", "Search Ad Campaign", "4.2x ROAS | 2,300 conversions", "https://images.unsplash.com/photo-1516321318423-f06f70504c38?auto=format&fit=crop&w=500&q=80"],
        ["google-ads", "Shopping Campaign", "$1.2M revenue | 68% YoY", "https://images.unsplash.com/photo-1557804506-669a67965ba0?auto=format&fit=crop&w=500&q=80"],
        ["google-ads", "YouTube Ad Placement", "92M impressions | 2.1% CTR", "https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&w=500&q=80"],
        ["google-ads", "Display Network Expansion", "320K impressions daily", "https://images.unsplash.com/photo-1499209974431-9dddcece7f88?auto=format&fit=crop&w=500&q=80"],
        ["google-ads", "Bid Strategy Optimization", "$4.20 avg CPC reduction", "https://images.unsplash.com/photo-1516321318423-f06f70504c38?auto=format&fit=crop&w=500&q=80"],
        ["google-ads", "Keyword Expansion", "560 high-intent keywords", "https://images.unsplash.com/photo-1557804506-669a67965ba0?auto=format&fit=crop&w=500&q=80"],
        ["seo", "Organic Traffic Growth", "+240% in 6 months", "https://images.unsplash.com/photo-1504384308090-c894fdcc538d?auto=format&fit=crop&w=500&q=80"],
        ["seo", "Keyword Rankings", "152 keywords in top 10", "https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=500&q=80"],
        ["seo", "Technical SEO Audit", "45 critical issues fixed", "https://images.unsplash.com/photo-1551434678-e076c223a692?auto=format&fit=crop&w=500&q=80"],
        ["seo", "Content Cluster Strategy", "68 pillar pages + clusters", "https://images.unsplash.com/photo-1552664730-d307ca884978?auto=format&fit=crop&w=500&q=80"],
        ["seo", "Link Building Campaign", "420 high-authority backlinks", "https://images.unsplash.com/photo-1516321318423-f06f70504c38?auto=format&fit=crop&w=500&q=80"],
        ["seo", "Local SEO Optimization", "1,200 local searches/month", "https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&w=500&q=80"],
        ["web-dev", "Conversion Funnel Website", "32% conversion rate", "https://images.unsplash.com/photo-1517694712202-14dd9538aa97?auto=format&fit=crop&w=500&q=80"],
        ["web-dev", "E-commerce Platform", "$2.1M annual sales", "https://images.unsplash.com/photo-1519389950473-47ba0277781c?auto=format&fit=crop&w=500&q=80"],
        ["web-dev", "Landing Page Suite", "12 high-converting pages", "https://images.unsplash.com/photo-1452587925148-ce544e77e70d?auto=format&fit=crop&w=500&q=80"],
        ["web-dev", "Mobile-First Design", "98/100 Lighthouse score", "https://images.unsplash.com/photo-1517694712202-14dd9538aa97?auto=format&fit=crop&w=500&q=80"],
        ["web-dev", "CRM Integration", "2,400 leads/month captured", "https://images.unsplash.com/photo-1552664730-d307ca884978?auto=format&fit=crop&w=500&q=80"],
        ["web-dev", "Performance Optimization", "2.1s load time | 42% faster", "https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&w=500&q=80"],
        ["graphic-design", "Brand Identity System", "Logo + 40 brand assets", "https://images.unsplash.com/photo-1561070791-2526d30994b5?auto=format&fit=crop&w=500&q=80"],
        ["graphic-design", "Ad Creatives Suite", "180 ad banners & variations", "https://images.unsplash.com/photo-1561070791-2526d30994b5?auto=format&fit=crop&w=500&q=80"],
        ["graphic-design", "Infographic Design", "28 data visualizations", "https://images.unsplash.com/photo-1561070791-2526d30994b5?auto=format&fit=crop&w=500&q=80"],
        ["graphic-design", "Social Media Templates", "120 ready-to-use designs", "https://images.unsplash.com/photo-1561070791-2526d30994b5?auto=format&fit=crop&w=500&q=80"],
        ["graphic-design", "Packaging Design", "3 product lines designed", "https://images.unsplash.com/photo-1561070791-2526d30994b5?auto=format&fit=crop&w=500&q=80"],
        ["graphic-design", "Email Newsletter Design", "24 issue template set", "https://images.unsplash.com/photo-1561070791-2526d30994b5?auto=format&fit=crop&w=500&q=80"],
        ["content", "Blog Content Strategy", "120 SEO-optimized posts", "https://images.unsplash.com/photo-1455849318169-8381d78b3b69?auto=format&fit=crop&w=500&q=80"],
        ["content", "Copywriting Services", "42 conversion-focused pieces", "https://images.unsplash.com/photo-1455849318169-8381d78b3b69?auto=format&fit=crop&w=500&q=80"],
        ["content", "Whitepaper & Case Study", "8 in-depth reports", "https://images.unsplash.com/photo-1455849318169-8381d78b3b69?auto=format&fit=crop&w=500&q=80"],
        ["content", "Social Media Captions", "480 monthly posts", "https://images.unsplash.com/photo-1455849318169-8381d78b3b69?auto=format&fit=crop&w=500&q=80"],
        ["content", "Email Newsletter", "52 weekly issues", "https://images.unsplash.com/photo-1455849318169-8381d78b3b69?auto=format&fit=crop&w=500&q=80"],
        ["content", "Press Release Series", "12 high-impact releases", "https://images.unsplash.com/photo-1455849318169-8381d78b3b69?auto=format&fit=crop&w=500&q=80"],
        ["branding", "Complete Brand Rebrand", "Identity + messaging + guidelines", "https://images.unsplash.com/photo-1561070791-2526d30994b5?auto=format&fit=crop&w=500&q=80"],
        ["branding", "Brand Positioning Strategy", "Market analysis + roadmap", "https://images.unsplash.com/photo-1561070791-2526d30994b5?auto=format&fit=crop&w=500&q=80"],
        ["branding", "Brand Guidelines Document", "120-page comprehensive manual", "https://images.unsplash.com/photo-1561070791-2526d30994b5?auto=format&fit=crop&w=500&q=80"],
        ["branding", "Corporate Identity", "Logo + stationery suite", "https://images.unsplash.com/photo-1561070791-2526d30994b5?auto=format&fit=crop&w=500&q=80"],
        ["branding", "Brand Voice Training", "Tone guide + content standards", "https://images.unsplash.com/photo-1561070791-2526d30994b5?auto=format&fit=crop&w=500&q=80"],
        ["branding", "Brand Audit & Evolution", "Competitive analysis + refresh", "https://images.unsplash.com/photo-1561070791-2526d30994b5?auto=format&fit=crop&w=500&q=80"],
        ["lead-gen", "Full Funnel Campaign", "3,200 qualified leads", "https://images.unsplash.com/photo-1552664730-d307ca884978?auto=format&fit=crop&w=500&q=80"],
        ["lead-gen", "Landing Page Optimization", "42% conversion improvement", "https://images.unsplash.com/photo-1552664730-d307ca884978?auto=format&fit=crop&w=500&q=80"],
        ["lead-gen", "Lead Magnet Strategy", "6 high-converting offers", "https://images.unsplash.com/photo-1552664730-d307ca884978?auto=format&fit=crop&w=500&q=80"],
        ["lead-gen", "Lead Scoring System", "4 qualification tiers", "https://images.unsplash.com/photo-1552664730-d307ca884978?auto=format&fit=crop&w=500&q=80"],
        ["lead-gen", "CRM Automation", "12 automated workflows", "https://images.unsplash.com/photo-1552664730-d307ca884978?auto=format&fit=crop&w=500&q=80"],
        ["lead-gen", "Lead Nurture Campaign", "8-email drip sequence", "https://images.unsplash.com/photo-1552664730-d307ca884978?auto=format&fit=crop&w=500&q=80"],
        ["email", "Email Automation Funnel", "32% open rate | 8.2% CTR", "https://images.unsplash.com/photo-1451187580459-43490279c0fa?auto=format&fit=crop&w=500&q=80"],
        ["email", "Newsletter Series", "52 weekly editions | 24K subscribers", "https://images.unsplash.com/photo-1451187580459-43490279c0fa?auto=format&fit=crop&w=500&q=80"],
        ["email", "Behavioral Triggers", "14 automated email sequences", "https://images.unsplash.com/photo-1451187580459-43490279c0fa?auto=format&fit=crop&w=500&q=80"],
        ["email", "A/B Testing Program", "52 subject line tests", "https://images.unsplash.com/photo-1451187580459-43490279c0fa?auto=format&fit=crop&w=500&q=80"],
        ["email", "List Segmentation", "8 custom audience segments", "https://images.unsplash.com/photo-1451187580459-43490279c0fa?auto=format&fit=crop&w=500&q=80"],
        ["email", "Abandoned Cart Recovery", "$184K revenue recovered", "https://images.unsplash.com/photo-1451187580459-43490279c0fa?auto=format&fit=crop&w=500&q=80"],
        ["ecommerce", "Store Revenue Growth", "$2.1M annual | +68% YoY", "https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?auto=format&fit=crop&w=500&q=80"],
        ["ecommerce", "Product Page Optimization", "28% conversion uplift", "https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?auto=format&fit=crop&w=500&q=80"],
        ["ecommerce", "Checkout Funnel Recovery", "$420K recovered revenue", "https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?auto=format&fit=crop&w=500&q=80"],
        ["ecommerce", "Customer Retention", "31% repeat purchase rate", "https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?auto=format&fit=crop&w=500&q=80"],
        ["ecommerce", "AOV Increase Campaign", "$42 average order value +18%", "https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?auto=format&fit=crop&w=500&q=80"],
        ["ecommerce", "Inventory & Fulfillment", "95% order fulfillment", "https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?auto=format&fit=crop&w=500&q=80"],
        ["video", "Product Demo Video", "3.2M views | 12.1% CTR", "https://images.unsplash.com/photo-1502920917128-1aa500764cbd?auto=format&fit=crop&w=500&q=80"],
        ["video", "Social Media Reels", "120 short-form videos", "https://images.unsplash.com/photo-1502920917128-1aa500764cbd?auto=format&fit=crop&w=500&q=80"],
        ["video", "Ad Video Campaign", "42 video ad variations", "https://images.unsplash.com/photo-1502920917128-1aa500764cbd?auto=format&fit=crop&w=500&q=80"],
        ["video", "Testimonial Series", "24 client testimonials", "https://images.unsplash.com/photo-1502920917128-1aa500764cbd?auto=format&fit=crop&w=500&q=80"],
        ["video", "Explainer Animation", "8 animated explainers", "https://images.unsplash.com/photo-1502920917128-1aa500764cbd?auto=format&fit=crop&w=500&q=80"],
        ["video", "Live Stream Production", "52 weekly live sessions", "https://images.unsplash.com/photo-1502920917128-1aa500764cbd?auto=format&fit=crop&w=500&q=80"]
    ];

    $stmt = $db->prepare(
        "INSERT INTO portfolio_items (category_id, title, subtitle, image_url, link_url, sort_order) VALUES (?, ?, ?, ?, ?, ?)"
    );
    $sortOrder = 1;
    foreach ($items as $item) {
        $categoryId = $categoryMap[$item[0]] ?? null;
        if (!$categoryId) {
            continue;
        }
        $linkUrl = null;
        $stmt->bind_param("issssi", $categoryId, $item[1], $item[2], $item[3], $linkUrl, $sortOrder);
        $stmt->execute();
        $sortOrder++;
    }
    $stmt->close();
}

$db->close();
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin Setup</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css" />
</head>

<body class="p-5">
    <h2>Setup Complete</h2>
    <p>Database: <strong><?php echo DB_NAME; ?></strong></p>
    <p>Admin user: <strong><?php echo $adminEmail; ?></strong></p>
    <p>Password: <strong><?php echo $adminPassword; ?></strong></p>
    <p><a href="login.php">Go to Admin Login</a></p>
    <p class="text-danger">Delete this setup.php file after verifying.</p>
</body>

</html>