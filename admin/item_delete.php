<?php
require_once __DIR__ . "/auth.php";
require_admin();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.php");
    exit;
}

$id = (int) ($_POST["id"] ?? 0);
if ($id > 0) {
    $db = db_connect();
    $stmt = $db->prepare("DELETE FROM portfolio_items WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    $db->close();
}

header("Location: index.php");
exit;
