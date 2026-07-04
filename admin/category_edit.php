<?php
require_once __DIR__ . "/auth.php";
require_admin();

$id = (int) ($_GET["id"] ?? 0);
$db = db_connect();
$stmt = $db->prepare("SELECT * FROM portfolio_categories WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$category = $result->fetch_assoc();
$stmt->close();

if (!$category) {
    $db->close();
    header("Location: index.php");
    exit;
}

$errors = [];
$name = $category["name"];
$slug = $category["slug"];
$iconClass = $category["icon_class"];
$sortOrder = (int) $category["sort_order"];

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
        $update = $db->prepare(
            "UPDATE portfolio_categories SET name = ?, slug = ?, icon_class = ?, sort_order = ? WHERE id = ?"
        );
        $update->bind_param("sssii", $name, $slug, $iconClass, $sortOrder, $id);
        $update->execute();
        $update->close();
        $db->close();
        header("Location: index.php");
        exit;
    }
}

$db->close();
include __DIR__ . "/partials_header.php";
?>
<div class="admin-card">
    <h3 class="mb-4">Edit Category</h3>
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
            <label class="form-label">Slug</label>
            <input type="text" name="slug" class="form-control admin-input"
                value="<?php echo admin_escape($slug); ?>" />
        </div>
        <div class="mb-3">
            <label class="form-label">Font Awesome Icon Class</label>
            <input type="text" name="icon_class" class="form-control admin-input"
                value="<?php echo admin_escape($iconClass); ?>" required />
        </div>
        <div class="mb-3">
            <label class="form-label">Sort Order</label>
            <input type="number" name="sort_order" class="form-control admin-input"
                value="<?php echo admin_escape((string) $sortOrder); ?>" />
        </div>
        <button class="theme-btn" type="submit">Update Category</button>
    </form>
</div>
<?php include __DIR__ . "/partials_footer.php"; ?>