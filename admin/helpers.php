<?php

function admin_slugify(string $text): string
{
    $text = strtolower(trim($text));
    $text = preg_replace("/[^a-z0-9]+/", "-", $text);
    $text = trim($text, "-");
    return $text === "" ? "category" : $text;
}

function admin_escape(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, "UTF-8");
}

function admin_handle_upload(array $file, string $targetDir): array
{
    if (!isset($file["error"]) || $file["error"] === UPLOAD_ERR_NO_FILE) {
        return ["path" => "", "error" => ""];
    }

    if ($file["error"] !== UPLOAD_ERR_OK) {
        return ["path" => "", "error" => "Upload failed. Please try again."];
    }

    $allowed = ["jpg", "jpeg", "png", "webp", "gif"];
    $extension = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
    if (!in_array($extension, $allowed, true)) {
        return ["path" => "", "error" => "Only JPG, PNG, WEBP, or GIF files are allowed."];
    }

    if (!is_dir($targetDir) && !mkdir($targetDir, 0755, true)) {
        return ["path" => "", "error" => "Upload folder is not available."];
    }

    $filename = uniqid("portfolio_", true) . "." . $extension;
    $destination = rtrim($targetDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $filename;

    if (!move_uploaded_file($file["tmp_name"], $destination)) {
        return ["path" => "", "error" => "Unable to save the uploaded file."];
    }

    $publicPath = "assets/img/portfolio-uploads/" . $filename;
    return ["path" => $publicPath, "error" => ""];
}
