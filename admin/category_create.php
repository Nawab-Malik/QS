<?php
require_once __DIR__ . "/auth.php";
require_admin();

$errors = [];
$name = "";
$slug = "";
$iconClass = "fa-solid fa-hashtag";
$sortOrder = 0;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"] ?? "");
    $slug = trim($_POST["slug"] ?? "");
    $iconClass = trim($_POST["icon_class"] ?? "");
    $sortOrder = (int) ($_POST["sort_order"] ?? 0);

    if ($name === "") {
        $errors[] = "Name is required.";
    }
    if ($iconClass === "") {
        $errors[] = "Icon class is required.";
    }

    if (empty($errors)) {
        if ($slug === "") {
            $slug = admin_slugify($name);
        }
        $db = db_connect();
        $stmt = $db->prepare("INSERT INTO portfolio_categories (name, slug, icon_class, sort_order) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $name, $slug, $iconClass, $sortOrder);
        $stmt->execute();
        $stmt->close();
        $db->close();
        header("Location: index.php");
        exit;
    }
}

include __DIR__ . "/partials_header.php";
?>
<div class="admin-card">
    <h3 class="mb-4">Add Category</h3>
    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <?php foreach ($errors as $error): ?>
                <div><?php echo admin_escape($error); ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <form method="post">
        <div class="mb-3">
            <label class="form-label">Category Name</label>
            <input type="text" name="name" class="form-control admin-input" value="<?php echo admin_escape($name); ?>"
                required />
        </div>
        <div class="mb-3">
            <label class="form-label">Slug (optional)</label>
            <input type="text" name="slug" class="form-control admin-input"
                value="<?php echo admin_escape($slug); ?>" />
        </div>
        <div class="mb-3">
            <label class="form-label">Font Awesome Icon Class</label>
            <input type="text" name="icon_class" class="form-control admin-input"
                value="<?php echo admin_escape($iconClass); ?>" required />
            <small class="text-muted">Example: fa-solid fa-hashtag</small>
        </div>
        <div class="mb-3">
            <label class="form-label">Sort Order</label>
            <input type="number" name="sort_order" class="form-control admin-input"
                value="<?php echo admin_escape((string) $sortOrder); ?>" />
        </div>
        <button class="theme-btn" type="submit">Save Category</button>
    </form>
</div>
<?php include __DIR__ . "/partials_footer.php"; ?>