<?php
require_once __DIR__ . "/auth.php";
require_admin();

$db = db_connect();
$categories = db_fetch_all($db->query("SELECT id, name FROM portfolio_categories ORDER BY sort_order ASC, name ASC"));

$errors = [];
$title = "";
$subtitle = "";
$imageUrl = "";
$linkUrl = "";
$categoryId = $categories[0]["id"] ?? 0;
$sortOrder = 0;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = trim($_POST["title"] ?? "");
    $subtitle = trim($_POST["subtitle"] ?? "");
    $imageUrl = trim($_POST["image_url"] ?? "");
    $linkUrl = trim($_POST["link_url"] ?? "");
    $categoryId = (int) ($_POST["category_id"] ?? 0);
    $sortOrder = (int) ($_POST["sort_order"] ?? 0);

    $uploadResult = admin_handle_upload(
        $_FILES["image_file"] ?? [],
        __DIR__ . "/../assets/img/portfolio-uploads"
    );
    if ($uploadResult["error"] !== "") {
        $errors[] = $uploadResult["error"];
    }
    if ($uploadResult["path"] !== "") {
        $imageUrl = $uploadResult["path"];
    }

    if ($categoryId <= 0) {
        $errors[] = "Category is required.";
    }
    if ($title === "") {
        $errors[] = "Title is required.";
    }
    if ($subtitle === "") {
        $errors[] = "Subtitle is required.";
    }
    if ($imageUrl === "") {
        $errors[] = "Image URL or upload is required.";
    }

    if (empty($errors)) {
        $stmt = $db->prepare(
            "INSERT INTO portfolio_items (category_id, title, subtitle, image_url, link_url, sort_order)
             VALUES (?, ?, ?, ?, ?, ?)"
        );
        $linkValue = $linkUrl !== "" ? $linkUrl : null;
        $stmt->bind_param("issssi", $categoryId, $title, $subtitle, $imageUrl, $linkValue, $sortOrder);
        $stmt->execute();
        $stmt->close();
        $db->close();
        header("Location: index.php");
        exit;
    }
}

$db->close();
include __DIR__ . "/partials_header.php";
?>
<div class="admin-card">
    <h3 class="mb-4">Add Portfolio Item</h3>
    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <?php foreach ($errors as $error): ?>
                <div><?php echo admin_escape($error); ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">Category</label>
            <select name="category_id" class="form-select admin-input" required>
                <?php foreach ($categories as $category): ?>
                    <option value="<?php echo (int) $category["id"]; ?>" <?php echo ((int) $categoryId === (int) $category["id"]) ? "selected" : ""; ?>>
                        <?php echo admin_escape($category["name"]); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Title</label>
            <input type="text" name="title" class="form-control admin-input" value="<?php echo admin_escape($title); ?>"
                required />
        </div>
        <div class="mb-3">
            <label class="form-label">Subtitle</label>
            <input type="text" name="subtitle" class="form-control admin-input"
                value="<?php echo admin_escape($subtitle); ?>" required />
        </div>
        <div class="mb-3">
            <label class="form-label">Image Upload (optional)</label>
            <input type="file" name="image_file" class="form-control admin-input" accept="image/*" />
        </div>
        <div class="mb-3">
            <label class="form-label">Image URL (optional if uploaded)</label>
            <input type="text" name="image_url" class="form-control admin-input"
                value="<?php echo admin_escape($imageUrl); ?>" />
        </div>
        <div class="mb-3">
            <label class="form-label">Optional Link (opens in new tab)</label>
            <input type="text" name="link_url" class="form-control admin-input"
                value="<?php echo admin_escape($linkUrl); ?>" />
        </div>
        <div class="mb-3">
            <label class="form-label">Sort Order</label>
            <input type="number" name="sort_order" class="form-control admin-input"
                value="<?php echo admin_escape((string) $sortOrder); ?>" />
        </div>
        <button class="theme-btn" type="submit">Save Item</button>
    </form>
</div>
<?php include __DIR__ . "/partials_footer.php"; ?>