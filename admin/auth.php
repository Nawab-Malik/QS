<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/../includes/db.php";
require_once __DIR__ . "/helpers.php";

function require_admin(): void
{
    if (empty($_SESSION["admin_id"])) {
        header("Location: login.php");
        exit;
    }
}
