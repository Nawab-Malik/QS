<?php
require_once __DIR__ . "/auth.php";
require_admin();

$db = db_connect();
$categories = db_fetch_all($db->query("SELECT * FROM portfolio_categories ORDER BY sort_order ASC, id ASC"));
$items = db_fetch_all(
    $db->query(
        "SELECT portfolio_items.*, portfolio_categories.name AS category_name
         FROM portfolio_items
         JOIN portfolio_categories ON portfolio_categories.id = portfolio_items.category_id
         ORDER BY portfolio_items.sort_order ASC, portfolio_items.id ASC"
    )
);
$db->close();

include __DIR__ . "/partials_header.php";
?>
<div class="admin-card mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">Categories</h3>
        <a href="category_create.php" class="theme-btn">Add Category</a>
    </div>
    <div class="table-responsive">
        <table class="table admin-table bg-dark">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Slug</th>
                    <th>Icon</th>
                    <th>Sort</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $category): ?>
                    <tr>
                        <td><?php echo admin_escape($category["name"]); ?></td>
                        <td><?php echo admin_escape($category["slug"]); ?></td>
                        <td><i class="<?php echo admin_escape($category["icon_class"]); ?>"></i></td>
                        <td><?php echo admin_escape((string) $category["sort_order"]); ?></td>
                        <td>
                            <a href="category_edit.php?id=<?php echo (int) $category["id"]; ?>"
                                class="btn btn-sm btn-outline-info">Edit</a>
                            <form method="post" action="category_delete.php" style="display:inline-block;">
                                <input type="hidden" name="id" value="<?php echo (int) $category["id"]; ?>" />
                                <button class="btn btn-sm btn-outline-danger" type="submit"
                                    onclick="return confirm('Delete this category and all its items?');">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="admin-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">Portfolio Items</h3>
        <a href="item_create.php" class="theme-btn">Add Item</a>
    </div>
    <div class="row mb-3">
        <div class="col-md-4">
            <label class="form-label">Filter by Category</label>
            <select id="item-category-filter" class="form-select admin-input">
                <option value="all">All Categories</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?php echo (int) $category["id"]; ?>">
                        <?php echo admin_escape($category["name"]); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table admin-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Subtitle</th>
                    <th>Sort</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                    <tr data-category-id="<?php echo (int) $item["category_id"]; ?>">
                        <td><?php echo admin_escape($item["title"]); ?></td>
                        <td><?php echo admin_escape($item["category_name"]); ?></td>
                        <td><?php echo admin_escape($item["subtitle"]); ?></td>
                        <td><?php echo admin_escape((string) $item["sort_order"]); ?></td>
                        <td>
                            <a href="item_edit.php?id=<?php echo (int) $item["id"]; ?>"
                                class="btn btn-sm btn-outline-info">Edit</a>
                            <form method="post" action="item_delete.php" style="display:inline-block;">
                                <input type="hidden" name="id" value="<?php echo (int) $item["id"]; ?>" />
                                <button class="btn btn-sm btn-outline-danger" type="submit"
                                    onclick="return confirm('Delete this item?');">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<script>
    const categoryFilter = document.getElementById("item-category-filter");
    if (categoryFilter) {
        categoryFilter.addEventListener("change", () => {
            const selected = categoryFilter.value;
            document.querySelectorAll("tbody tr[data-category-id]").forEach((row) => {
                if (selected === "all" || row.dataset.categoryId === selected) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        });
    }
</script>
<?php include __DIR__ . "/partials_footer.php"; ?>