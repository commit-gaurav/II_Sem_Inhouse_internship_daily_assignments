<?php
/**
 * includes/upload_helper.php
 * ---------------------------
 * Shared, validated photo upload used by both register.php and edit_student.php.
 *
 * Bug fix from the Day 9 version: the old register.php trusted the browser's
 * accept="image/*" hint and saved whatever was uploaded with no server-side
 * check on file type or size, and no folder creation if uploads/ was missing.
 * Anyone could rename a .php file to .jpg and upload it. This version checks
 * the actual MIME type and size on the server, and generates an
 * unguessable filename instead of trusting the original one.
 */

function handle_photo_upload($file)
{
    $allowed_types = ["image/jpeg", "image/png", "image/gif", "image/webp"];
    $max_size = 2 * 1024 * 1024; // 2MB

    if (!isset($file) || $file["error"] !== UPLOAD_ERR_OK) {
        return ["ok" => false, "filename" => "", "error" => ""];
    }

    if (!in_array($file["type"], $allowed_types, true)) {
        return ["ok" => false, "filename" => "", "error" => "Only JPG, PNG, GIF, or WEBP images are allowed."];
    }

    if ($file["size"] > $max_size) {
        return ["ok" => false, "filename" => "", "error" => "Photo must be smaller than 2MB."];
    }

    $upload_dir = __DIR__ . "/../uploads/";
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    $ext = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
    // time() + random bytes instead of the original filename, so two students
    // uploading "photo.jpg" never collide and nobody can guess another
    // student's filename.
    $safe_name = time() . "_" . bin2hex(random_bytes(4)) . "." . $ext;

    if (!move_uploaded_file($file["tmp_name"], $upload_dir . $safe_name)) {
        return ["ok" => false, "filename" => "", "error" => "Could not save the uploaded photo."];
    }

    return ["ok" => true, "filename" => $safe_name, "error" => ""];
}
